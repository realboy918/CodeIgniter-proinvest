<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
   
class perfectmoney extends BaseController {
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
        $this->load->model('perfectmoney_model');
    }

    function success(){
        $success = $this->IPN_Response();

        $this->isLoggedIn();  
        $this->global['pageTitle'] = 'Deposit succesful';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = 'Deposit'.' <span class="breadcrumb-arrow-right"></span> '.'Success';
        $this->loadViews("payments/success", $this->global); 
    }

    function canceled(){
        $this->isLoggedIn();  
        $this->global['pageTitle'] = 'Deposit failed';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = 'Deposit'.' <span class="breadcrumb-arrow-right"></span> '.'Canceled';
        $this->loadViews("payments/cancel", $this->global);   
    }

    function IPN_Response(){
        $this->load->helper('string');

        $perfectmoneyinfo = $this->payments_model->getInfo('tbl_addons_api', 'Perfect Money');
        $passphrase = $perfectmoneyinfo->public_key;

        if (!isset($_POST['PAYMENT_ID']) || !isset($_POST['PAYEE_ACCOUNT']) || !isset($_POST['PAYMENT_AMOUNT']) || !isset($_POST['PAYMENT_UNITS']) || !isset($_POST['PAYMENT_BATCH_NUM']) || !isset($_POST['PAYER_ACCOUNT']) || !isset($_POST['TIMESTAMPGMT'])) {
            //Stop here
            return false;
        } else {
            $paymentID = $_POST['PAYMENT_ID'];
            $payeeAccount = $_POST['PAYEE_ACCOUNT'];
            $paymentAmount = $_POST['PAYMENT_AMOUNT'];
            $paymentUnits = $_POST['PAYMENT_UNITS'];
            $paymentBatchNum = $_POST['PAYMENT_BATCH_NUM'];
            $payerAccount = $_POST['PAYER_ACCOUNT'];
            $timestampPGMT = $_POST['TIMESTAMPGMT'];
            $v2Hash = $_POST['V2_HASH'];
            //$baggageFields = $_POST['BAGGAGE_FIELDS'];

            $pmData = $this->perfectmoney_model->getInfo($paymentID);

            if($pmData->status != 1)
            {
                $alternatePhraseHash = strtoupper(md5($passphrase));
                $hash = $paymentID . ':' . $payeeAccount . ':' . $paymentAmount . ':' . $paymentUnits . ':' . $paymentBatchNum . ':' . $payerAccount . ':' . $alternatePhraseHash . ':' . $timestampPGMT;
                $hash2 = strtoupper(md5($hash));

                if ($hash2 != $v2Hash)
                {
                    //Stop here
                } else
                {
                    //Let us put the data in the database
                    $info = array(
                        'amount'=>$paymentAmount,
                        'payment_batch_number'=>$paymentBatchNum,
                        'payer_account'=>$payerAccount,
                        'status'=>1
                    );

                    $result = $this->perfectmoney_model->update($info, $paymentID);

                    if($result)
                    {
                        $userId = $pmData->userId;
                        $planId = $pmData->planId;

                        $plan = $this->plans_model->getPlanById($planId);

                        $date = date('Y-m-d H:i:s');
                        $maturityPeriod = $this->plans_model->getMaturity($planId)->period_hrs;
                        $payoutsInterval = $this->plans_model->getPeriod($planId)->period_hrs;

                        //Deposit Array
                        $depositInfo = array(
                            'userId'=>$userId, 
                            'txnCode'=>'NJ'.random_string('alnum',8),
                            'amount'=>$paymentAmount, 
                            'paymentMethod'=> 'Perfect Money', 
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
                }
            }
            return true;
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