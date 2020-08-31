<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
   
class payeer extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->library("session");
        $this->load->helper('url');
        $this->load->model('settings_model');
        $this->load->model('payments_model');      
        $this->load->model('plans_model');   
        $this->load->model('transactions_model');    
        $this->load->model('user_model');    
        $this->load->model('email_model');  
        $this->load->model('twilio_model');
        $this->load->model('payeer_model');
        $this->load->model('languages_model');

        $userLang = $this->session->userdata('site_lang') == '' ?  "english" : $this->session->userdata('site_lang');

        $this->load->helper('language');
        $this->lang->load('common',$userLang);
        $this->lang->load('dashboard',$userLang);
        $this->lang->load('transactions',$userLang);
        $this->lang->load('users',$userLang);
        $this->lang->load('login',$userLang);
        $this->lang->load('plans',$userLang);
        $this->lang->load('email_templates',$userLang);
        $this->lang->load('settings',$userLang);
        $this->lang->load('payment_methods',$userLang);
        $this->lang->load('languages',$userLang);
        $this->lang->load('plugins',$userLang);
        $this->lang->load('validation',$userLang);
        $this->lang->load('tickets',$userLang);
    }

    function success(){
        $success = $this->IPN_Response();
        if($success){
            $this->isLoggedIn();  
            $this->global['pageTitle'] = 'Deposit successful';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = lang('deposits').' <span class="breadcrumb-arrow-right"></span> '.'Success';
            $this->loadViews("payments/success", $this->global); 
        } else {
            $this->canceled();
        }
    }

    function canceled(){
        $this->isLoggedIn();  
        $this->global['pageTitle'] = 'Deposit failed';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = lang('deposits').' <span class="breadcrumb-arrow-right"></span> '.lang('cancelled');
        $this->loadViews("payments/cancel", $this->global);   
    }

    function IPN_Response(){
        $this->load->helper('string');
        if(isset($_GET) && !$_POST) {           // If the result returned after $ _GET:
            $_POST = $_GET;
        }
        //Check the IP sending the request
        //if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189', '149.202.17.210'))) return;
        if (isset($_POST['m_operation_id']) && isset($_POST['m_sign'])) {
            $methodData = $this->payments_model->getInfo('tbl_addons_api', 'payeer');
            // We form an array to generate a signature
            $arHash = array(
                $_POST['m_operation_id'],
                $_POST['m_operation_ps'],
                $_POST['m_operation_date'],
                $_POST['m_operation_pay_date'],
                $_POST['m_shop'],
                $_POST['m_orderid'],
                $_POST['m_amount'],
                $_POST['m_curr'],
                $_POST['m_desc'],
                $_POST['m_status']
            );
            // If additional parameters were passed, then add them to the array
            if (isset($_POST['m_params'])) {
                $arHash[] = $_POST['m_params'];
            }
            // Add the secret key to the array
            $arHash[] = $methodData->secret_key;
            // Form a signature
            $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

            // If the signatures match and the payment status is “Completed”
            if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success') {

                $paymentAmount = $_POST['m_amount'];
                $paymentID = $_POST['m_orderid'];
                // We return that the payment was processed successfully
                $pData = $this->payeer_model->getInfo($paymentID);

                if($pData->status != 1)
                {
                    //Let us put the data in the database
                    $info = array(
                        'status'=>1
                    );

                    $result = $this->payeer_model->update($info, $paymentID);

                    if($result)
                    {
                        $userId = $pData->userId;
                        $planId = $pData->planId;

                        $plan = $this->plans_model->getPlanById($planId);

                        $date = date('Y-m-d H:i:s');
                        $maturityPeriod = $this->plans_model->getMaturity($planId)->period_hrs;
                        $payoutsInterval = $this->plans_model->getPeriod($planId)->period_hrs;

                        //Deposit Array
                        $depositInfo = array(
                            'userId'=>$userId, 
                            'txnCode'=>'NJ'.random_string('alnum',8),
                            'amount'=>$paymentAmount, 
                            'paymentMethod'=> 'Payeer', 
                            'planId' => $planId,
                            'status' => $plan->principalReturn == 1 ? '0' : '3',
                            'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")),
                            'createdBy'=>$userId, 
                            'createdDtm'=>$date
                        );

                        $dAmount = $paymentAmount;
                        $profitPercent = $plan->profit/100;

                        $earningsAmount = $profitPercent*$dAmount;
                        $earningsType = 'interest';
                        $startDate = date('Y-m-d H:i:s', strtotime($date."+$payoutsInterval hours"));
                        $endDate = date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours"));

                        //Add the deposit and earnings
                        $result1 = $this->transactions_model->addNewDeposit($userId, $depositInfo, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod);

                        //Send email       
                        if($result1)
                        {
                            //Process the referal credits
                            $this->referralEarnings($userId, $paymentAmount, $result);

                            //Send Mail
                            $conditionUserMail = array('tbl_email_templates.type'=>'Deposit');
                            $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                            $companyInfo = $this->settings_model->getsettingsInfo();
                        
                            if($resultEmail->num_rows() > 0)
                            {
                                $userInfo = $this->user_model->getUserInfo($userId);

                                $rowUserMailContent = $resultEmail->row();
                                $splVars = array(
                                    "!clientName" => $userInfo->firstName,
                                    "!depositAmount" => to_currency($paymentAmount),
                                    "!companyName" => $companyInfo['name'],
                                    "!address" => $companyInfo['address'],
                                    "!siteurl" => base_url()
                                );

                                $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                                $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 
                                
                                $toEmail = $userInfo->email;
                                $fromEmail = $companyInfo['SMTPUser'];

                                $name = 'Support';

                                $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                                $send = $this->sendEmail($toEmail,$mailSubject,$mailContent);

                                if($send == true) {
                                    $success = "Reset password link sent successfully, please check email.";
                                } else {
                                    $success = "Email sending has failed, try again.";
                                }

                                //Send SMS
                                $phone = $userInfo->mobile;
                                if($phone){
                                    $body = strtr($rowUserMailContent->sms_body, $splVars);

                                    $this->twilio_model->send_sms($phone, $body);
                                }
                            }
                        }

                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                // Otherwise return an error
                return false;
            }
        } else {
            return false;
        }
    }

    function referralEarnings($userID = NULL, $amount = NULL, $depositID = NULL)
    {
        if($userID == Null || $amount == Null || $depositID == Null)
        {
            return false;
            //print_r('Either the user Id, amount or depositid is not available');
        }
        else 
        {
            //Get the referrer ID
            $referrerID = $this->user_model->getReferrerID($userID);

            //First Let's check whether this user has been referred by anyone
            if($referrerID != null) {
                //Check the referral method & interest
                $refMethod = $this->settings_model->getSettingsInfo()['refType'];
                $refInterest = $this->settings_model->getSettingsInfo(1)['refInterest'];
                $deposit_only_payouts = $this->settings_model->getSettingsInfo(1)['disableRefPayouts'];

                if($refMethod == 'simple')
                {
                    $number_of_deposits = $this->transactions_model->depositsListingCount('', $referrerID);

                    //Calculate the referrer's earnings
                    $earnings = $amount * ($refInterest/100);

                    //for generating the txn code
                    $this->load->helper('string');

                    //Insert earnings into the earnings table
                    $array = array(
                        'type' => 'referral',
                        'userId'=> $referrerID,
                        'depositId' => $depositID,
                        'txnCode' => 'PO'.random_string('alnum',8),
                        'amount' => $earnings,
                        'createdDtm'=> date("Y-m-d H:i:s")
                    );

                    if($deposit_only_payouts == 1 && $number_of_deposits > 0) {
                        $result = $this->transactions_model->addNewEarning($array);
                    } else if($deposit_only_payouts == 0) {
                        $result = $this->transactions_model->addNewEarning($array);
                    } else {
                        $result = 0;
                    }

                    if($result > 0)
                    {
                        return true;
                        //print_r('New simple earning added');
                    } else 
                    {
                        return false;
                        //print_r('New simple earning not added');
                    }

                } else if($refMethod == 'multiple')
                {
                    //Find the referral levels
                    $levels_array = explode(',', $refInterest);
                    $levelsCount = count($levels_array);

                    //Get an array that looks like this [{id: 1, amount: 10}, {id: 2, amount: 15}]
                    for ($i=0; $i<$levelsCount; $i++) {
                        // Here we get the first referredID whose making the deposit
                        $referrerId_[0] = $userID;
                        //We then get multiple referrerIds based on the number of levels
                        $referrerId_[$i + 1] = $this->user_model->getReferrerID($referrerId_[$i]);
                        //We then procced to put it in an array with referrerId_[1] as the first Id
                        $earningsData[] = (object) [
                            "id" => $referrerId_[$i + 1],
                            "interest" => $levels_array[$i],
                            "amount" => $amount * $levels_array[$i]/100
                        ];
                    }

                    //for generating the txn code
                    $this->load->helper('string');

                    //We then take the earnings data and remove all null Ids in the array to get the users
                    //that we should put soe earnings for
                    foreach($earningsData as $data) {
                        if($data->id != null) {
                            $array[] = array(
                                'type' => 'referral',
                                'userId'=> $data->id,
                                'depositId' => $depositID,
                                'txnCode' => 'PO'.random_string('alnum',8),
                                'amount' => $data->amount,
                                'createdDtm'=>date("Y-m-d H:i:s")
                            );
                        }
                    };

                    //Insert the data
                    $result = $this->transactions_model->addNewEarnings($array);

                    if($result > 0)
                    {
                        return true;
                    } else 
                    {
                        return false;
                    }
                }
            } else 
            {
                return false;
            }   
        }
    } 
}