<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
   
class paypal extends BaseController {
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
        $this->load->model('languages_model');
        $this->isLoggedIn();  

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
        $this->lang->load('validation',$userLang);
        $this->lang->load('tickets',$userLang);
    }

    function success(){
        $this->load->helper('string');
        $payId = $this->input->get('paymentId', TRUE);
        $status = 0;
        //Confirm if the payment id exists and that the status is 0
        $result = $this->payments_model->getPaypalPayment($payId, $status);
        if($result)
        {
            $order_currency = 'USD';
            $order_total = $result->local_currency;
            $userId = $result->userid;
            $planId = $result->plan;
            $invoice = $result->invoice;
            $method = 'Paypal';

            $date = date('Y-m-d H:i:s');
            $maturityPeriod = $this->plans_model->getMaturity($planId)->period_hrs;
            $payoutsInterval = $this->plans_model->getPeriod($planId)->period_hrs;

            //change the status code for the transaction
            $payInfo = array(
                'payment_status'=>'1'
            );

            $result2 = $this->payments_model->editPaypalInfo($payInfo, $payId);

            $plan = $this->plans_model->getPlanById($planId);

            //Deposit Array
            $depositInfo = array(
                'userId'=>$userId, 
                'txnCode'=>$invoice,
                'amount'=>$order_total, 
                'paymentMethod'=> $method, 
                'planId' => $planId,
                'status' => $plan->principalReturn == 1 ? '0' : '3',
                'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")),
                'createdBy'=>$userId, 
                'createdDtm'=>$date
            );

            $dAmount = $order_total;
            $profitPercent = $plan->profit/100;

            $earningsAmount = $profitPercent*$dAmount;
            $earningsType = 'interest';
            $startDate = date('Y-m-d H:i:s', strtotime($date."+$payoutsInterval hours"));
            $endDate = date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours"));
            $businessDays = $plan->businessDays;

            //Add the deposit and earnings
            $result = $this->transactions_model->addNewDeposit($userId, $depositInfo, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod, $businessDays);

            //Send email       
            if($result)
            {
                //Process the referal credits
                $this->referralEarnings($userId, $order_total, $result);

                //Send Mail
                $conditionUserMail = array('tbl_email_templates.type'=>'Deposit');
                $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                $companyInfo = $this->settings_model->getsettingsInfo();
            
                if($resultEmail->num_rows() > 0)
                {
                    $userInfo = $this->user_model->getUserInfo($userId);

                    $rowUserMailContent = $resultEmail->row();
                    $splVars = array(
                        "!plan" => $plan->name,
                        "!interest" => $plan->profit.'%',
                        "!period"=> $this->plans_model->getPeriod($planId)->maturity_desc,
                        '!payout'=> to_currency($this->transactions_model->totalPayoutValue($result, $userId)),
                        "!clientName" => $userInfo->firstName,
                        "!depositAmount" => to_currency($order_total),
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

                    //Send SMS
                    $phone = $userInfo->mobile;
                    if($phone){
                        $body = strtr($rowUserMailContent->sms_body, $splVars);

                        $this->twilio_model->send_sms($phone, $body);
                    }
                }
            }
            $this->global['pageTitle'] = 'Deposit succesful';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = lang('deposits').' <span class="breadcrumb-arrow-right"></span> '.lang('success');
            $this->loadViews("payments/success", $this->global);   
        } else 
        {
            //If not redirect to deposits
           redirect('/deposits');
        }

    }

    function canceled(){
        $this->global['pageTitle'] = 'Deposit failed';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = lang('deposits').' <span class="breadcrumb-arrow-right"></span> '.lang('cancelled');
        $this->loadViews("payments/cancel", $this->global);   
    }

    function getDatesFromRange($start, $end, $payoutsInterval, $format = 'Y-m-d H:i:s') {
        $array = array();
        $interval = 'PT'.$payoutsInterval.'H';
        $interval = new DateInterval($interval);
    
        $realEnd = new DateTime($end);
        //$realEnd->add($interval);
    
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    
        foreach($period as $date) { 
            $array[] = $date->format($format); 
        }
    
        return $array;
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