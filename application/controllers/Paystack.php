<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
/**
 * Created By Trimmytech
 * Fiverr Handle : @trimmytech
 * Date: 4/14/2018
 * Time: 9:26 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Paystack extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        
        $this->load->library("session");
        $this->load->model('user_model');
        $this->load->model('plans_model');
        $this->load->model('login_model');
        $this->load->model('transactions_model');
        $this->load->model('settings_model');
        $this->load->model('email_model');
        $this->load->model('payments_model');
        $this->load->model('twilio_model');
        $this->load->model('addons_model');   
        $this->load->model('paystack_model'); 

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


    private function getPaymentInfo($ref) {
        $this->isLoggedIn(); 
        $paystackInfo = $this->addons_model->get_addon_info('Paystack');
        $secret_key = $paystackInfo->secret_key; 

        $result = array();
        $url = 'https://api.paystack.co/transaction/verify/'.$ref;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$secret_key]
        );
        $request = curl_exec($ch);
        curl_close($ch);
        //
        $result = json_decode($request, true);
        //
        return print_r($result);

    }

    public function verify_payment() {
        $ch = curl_init();
        $paystackInfo = $this->addons_model->get_addon_info('Paystack');
        $secret_key = $paystackInfo->secret_key; 
        
        $reference = isset($_GET['reference']) ? $_GET['reference'] : '';
        if(!$reference){
        die('No reference supplied');
        }

        $url = 'https://api.paystack.co/transaction/verify/' . rawurlencode($reference);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$secret_key]
        );

        $response = curl_exec($ch);
        $err = curl_error($ch);

        if($err){
            // there was an error contacting the Paystack API
            //die('Curl returned error: ' . $err);
            header("Location: ".base_url().'paystack/fail');
        }

        $tranx = json_decode($response);

        if(!$tranx->status){
            // there was an error from the API
            //die('API returned error: ' . $tranx->message);
            header("Location: ".base_url().'paystack/fail');
        }

        if('success' == $tranx->data->status){
            // transaction was successful...
            $ref = $tranx->data->reference;

            header("Location: ".base_url().'paystack/success/'.$ref);
        }
    }

    public function paystack_standard() {
        $this->isLoggedIn(); 
        $this->load->helper('string');

        $paystackInfo = $this->addons_model->get_addon_info('Paystack');
        $secret_key = $paystackInfo->secret_key; 

        $userInfo = $this->user_model->getUserInfo($this->vendorId);
        
        $result = array();
        $amount = $_SESSION['DepositAmount'];
        $ref = 'rd'.random_string('alnum',8);
        $callback_url = base_url().'paystack_callback';
        $postdata =  array(
            'email' => $userInfo->email, 
            'amount' => $amount * 100,
            "reference" => $ref, 
            "callback_url" => $callback_url
        );

        $url = "https://api.paystack.co/transaction/initialize";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $headers = [
            'Authorization: Bearer '.$secret_key,
            'Content-Type: application/json',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec ($ch); 
        $err = curl_error($ch);

        if($err)
        {
            // there was an error contacting the Paystack API
            die('Error: ' . $err);
        }

        $tranx = json_decode($response, true);

        if(!$tranx['status']){
            // there was an error from the API
            print_r('API returned error: ' . $tranx['message']);
            exit();
        }
        
        //Add information about Paystack Payment
        $paystackData = array(
            'userId'=>$this->vendorId,
            'ref'=>$ref,
            'plan'=>$_SESSION['planId'],
            'amount'=>$amount,
            'status'=>0,
        );

        $this->paystack_model->paystackCreate($paystackData);

        $redir = $tranx['data']['authorization_url'];
        header("Location: ".$redir);
    }

    public function success($ref) {
        $this->isLoggedIn(); 
        $this->load->helper('string');

        $checkRef = $this->paystack_model->checkPaystackRef($ref);

        if($checkRef->status == 0){
            $order_total = $checkRef->amount;
            $userId = $this->vendorId;
            $planId = $checkRef->plan;
            $invoice = 'NJ'.random_string('alnum',8);
            $method = 'Paystack';

            $date = date('Y-m-d H:i:s');
            $maturityPeriod = $this->plans_model->getMaturity($planId)->period_hrs;
            $payoutsInterval = $this->plans_model->getPeriod($planId)->period_hrs;


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
                //Change status of paystack transaction
                $paystackData = array(
                    'status'=>1
                );

                $this->paystack_model->editPaystack($paystackData, $ref);
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
        }
        $this->global['pageTitle'] = 'Deposit successful';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = 'Deposit'.' <span class="breadcrumb-arrow-right"></span> '.'Success';
        $this->loadViews("payments/success", $this->global, NULL); 
    }

    public function webhook()
    {
        $paystackInfo = $this->addons_model->get_addon_info('Paystack');
        $secret_key = $paystackInfo->secret_key; 

        // only a post with paystack signature header gets our attention
        if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST' ) || !array_key_exists('x-paystack-signature', $_SERVER) ) 
        exit();

        // Retrieve the request's body
        $input = @file_get_contents("php://input");
        define('PAYSTACK_SECRET_KEY',$secret_key);

        // validate event do all at once to avoid timing attack
        if($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $input, PAYSTACK_SECRET_KEY))
        exit();

        http_response_code(200); 

        // parse event (which is json string) as object
        // Do something - that will not take long - with $event
        $event = json_decode($input);
        
        $response = $event['event'];

        if($response == "charge.success")
        exit();

        $ref = $event['data']['reference'];
        
        $checkRef = $this->paystack_model->checkPaystackRef($ref);

        if($checkRef->status == 0){
            $order_total = $checkRef->amount;
            $userId = $this->vendorId;
            $planId = $checkRef->plan;
            $invoice = 'NJ'.random_string('alnum',8);
            $method = 'Paystack';

            $date = date('Y-m-d H:i:s');
            $maturityPeriod = $this->plans_model->getMaturity($planId)->period_hrs;
            $payoutsInterval = $this->plans_model->getPeriod($planId)->period_hrs;


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

            //Add the deposit and earnings
            $result = $this->transactions_model->addNewDeposit($userId, $depositInfo, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod);

            //Send email       
            if($result)
            {
                //Change status of paystack transaction
                $paystackData = array(
                    'status'=>1
                );

                $this->paystack_model->editPaystack($paystackData, $ref);
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
        }

    }

    public function fail() {
        $this->isLoggedIn(); 
        $this->global['pageTitle'] = 'Deposit failed';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = 'Deposit'.' <span class="breadcrumb-arrow-right"></span> '.'Canceled';
        $this->loadViews("payments/cancel", $this->global);   
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
?>