<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
   
class btcpay extends BaseController {
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
        $this->load->model('btcpay_model');
    }

    function success(){
        $this->isLoggedIn();  
        $this->global['pageTitle'] = 'Deposit successful';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = 'Deposit'.' <span class="breadcrumb-arrow-right"></span> '.'Success';
        $this->loadViews("payments/success", $this->global);  
    }

    function IPN_Response(){
        $this->load->helper('string');

        //Handle the response
        if(empty($_POST)){
            //Check if there is no response
            header('HTTP/1.1 200 OK'); // feedback for gateway
        } else {
            if($_POST['data']->status == 'confirmed'){
                //Get the order id
                $invoice = $_POST['orderId'];
                $amount = $_POST['price'];

                //First check the database for this invoice
                $data = $this->btcpay_model->getInfo($invoice);

                if($data->status != 1)
                {
                    //Let us put the data in the database
                    $info = array(
                        'status'=>1
                    );

                    $result = $this->btcpay_model->update($info, $invoice);

                    if($result)
                    {
                        $userId = $data->userId;
                        $planId = $data->planId;

                        $plan = $this->plans_model->getPlanById($planId);

                        $date = date('Y-m-d H:i:s');
                        $maturityPeriod = $this->plans_model->getMaturity($planId)->period_hrs;
                        $payoutsInterval = $this->plans_model->getPeriod($planId)->period_hrs;

                        //Deposit Array
                        $depositInfo = array(
                            'userId'=>$userId, 
                            'txnCode'=>'NJ'.random_string('alnum',8),
                            'amount'=>$amount, 
                            'paymentMethod'=> 'BTCPay', 
                            'planId' => $planId,
                            'status' => $plan->principalReturn == 1 ? '0' : '3',
                            'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")),
                            'createdBy'=>$userId, 
                            'createdDtm'=>$date
                        );

                        $profitPercent = $plan->profit/100;

                        $earningsAmount = $profitPercent*$amount;
                        $earningsType = 'interest';
                        $startDate = date('Y-m-d H:i:s', strtotime($date."+$payoutsInterval hours"));
                        $endDate = date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours"));

                        //Add the deposit and earnings
                        $result1 = $this->transactions_model->addNewDeposit($userId, $depositInfo, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod);

                        //Send email       
                        if($result1)
                        {
                            //Process the referal credits
                            $this->referralEarnings($userId, $amount, $result);

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
                                    "!depositAmount" => to_currency($amount),
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
                } else {
                    return false;
                }

            } else {
                // do nothing
            }
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