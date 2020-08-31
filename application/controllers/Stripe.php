<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
   
class Stripe extends BaseController {
    
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        parent::__construct();
        $this->load->library("session");
        $this->load->helper('url');
        $this->load->model('settings_model');
        $this->load->model('payments_model');
        $this->load->model('plans_model');
        $this->load->model('transactions_model');
        $this->load->model('email_model');
        $this->load->model('user_model');
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
    
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index()
    {
        if(!$_SESSION['DepositAmount'])
        {
            redirect('deposits/new');
        } else
        {
            $amount = $this->session->flashdata('amount');
            $this->global['pageTitle'] = 'Stripe Payment';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = lang('deposits').' <span class="breadcrumb-arrow-right"></span> '.'Stripe';
            $data['publishable_key'] = $this->payments_model->getInfo('tbl_addons_api', 'stripe')->public_key;
            $data['payment'] = ($_SESSION['DepositAmount']);
            $this->loadViews("payments/stripe", $this->global, $data, NULL);
            
            //print_r($_SESSION['error6']);
        }
    }
       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function stripePost()
    {
        $companyInfo = $this->settings_model->getsettingsInfo();
        require_once('application/libraries/stripe-php/init.php');
    
        \Stripe\Stripe::setApiKey($this->payments_model->getInfo('tbl_addons_api','Stripe')->secret_key);

        try {
            $charge = \Stripe\Charge::create(array(
            "amount" => $_SESSION['DepositAmount'] * 100,
                "currency" => $companyInfo['currency'],
                "source" => $this->input->post('stripeToken', TRUE),
                "description" => "Test payment" ));
            $success = 1;
            $paymentProcessor="Credit card (www.stripe.com)";
        } catch(\Stripe\CardError $e) {
          $error1 = $e->getMessage();
        } catch (\Stripe\InvalidRequestError $e) {
          // Invalid parameters were supplied to Stripe's API
          $error2 = $e->getMessage();
        } catch (Stripe_AuthenticationError $e) {
          // Authentication with Stripe's API failed
          $error3 = $e->getMessage();
        } catch (Stripe_ApiConnectionError $e) {
          // Network communication with Stripe failed
          $error4 = $e->getMessage();
        } catch (Stripe_Error $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
          $error5 = $e->getMessage();
        } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
          $error6 = $e->getMessage();
        }
        
        if ($success!=1)
        {
            header('Location: stripe-payment');

            $this->session->set_flashdata('error', $error1);
            $this->session->set_flashdata('error', $error2);
            $this->session->set_flashdata('error', $error3);
            $this->session->set_flashdata('error', $error4);
            $this->session->set_flashdata('error', $error5);
            $this->session->set_flashdata('error', $error6);

            exit();
        }

        else {
            //for creating the txn code
            $this->load->helper('string');

            //Variables
            $plan = $_SESSION['planId'];
            $amount = $_SESSION['DepositAmount'];
            $method = 'stripe';
            $code = 'NJ'.random_string('alnum',8);
            $date = date('Y-m-d H:i:s');
            $datetime = date('Y-m-d H:i:s');

             //Get Plan Details
             $maturityPeriod = $this->plans_model->getMaturity($plan)->period_hrs;
             $payoutsInterval = $this->plans_model->getPeriod($plan)->period_hrs;

             $planInfo = $this->plans_model->getPlanById($plan);

            //Deposit Array
            $depositInfo = array(
                'userId'=>$this->vendorId, 
                'txnCode'=>$code,
                'amount'=>$amount, 
                'paymentMethod'=> $method, 
                'planId' => $plan,
                'status' => $planInfo->principalReturn == 1 ? '0' : '3',
                'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")),
                'createdBy'=>$this->vendorId, 
                'createdDtm'=>$datetime
            );

            $dAmount = $amount;
            $profitPercent = $planInfo->profit/100;

            $earningsAmount = $profitPercent*$dAmount;
            $earningsType = 'interest';
            $startDate = date('Y-m-d H:i:s', strtotime($date."+$payoutsInterval hours"));
            $endDate = date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours"));
            $businessDays = $planInfo->businessDays;

            //Add the deposit
            $result = $this->transactions_model->addNewDeposit($this->vendorId, $depositInfo, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod, $businessDays);

            //Send email       
            if($result > 0)
            {
                //Process the referal credits
                $this->referralEarnings($this->vendorId, $amount, $result);

                //unset session data
                unset(
                    $_SESSION['DepositAmount'],
                    $_SESSION['planId']
                );

                $this->session->set_flashdata('success', lang('your_funds_have_been_deposited_successfully'));
                //Send Mail
                $conditionUserMail = array('tbl_email_templates.type'=>'Deposit');
                $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                if($resultEmail->num_rows() > 0)
                {
                    $rowUserMailContent = $resultEmail->row();
                    $splVars = array(
                        "!plan" => $planInfo->name,
                        "!interest" => $planInfo->profit.'%',
                        "!period"=> $this->plans_model->getPeriod($planInfo->id)->maturity_desc,
                        '!payout'=> to_currency($this->transactions_model->totalPayoutValue($result, $this->vendorId)),
                        "!clientName" => $this->firstName,
                        "!depositAmount" => to_currency($amount),
                        "!companyName" => $companyInfo['name'],
                        "!address" => $companyInfo['address'],
                        "!siteurl" => base_url()
                    );

                    $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                    $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 
                    
                    $userInfo = $this->user_model->getUserInfo($this->vendorId);

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
                
            } else {
                $this->session->set_flashdata('error', lang('depositing_to_your_account_has_failed'));
            }

            redirect('/stripepaymentsuccess', 'refresh');
        }
    }

    function success()
    {
        $this->load->helper('form');
        $success = $this->session->flashdata('success');
        if($success)
        {
            $this->global['pageTitle'] = 'Payment succesful';
            $this->global['displayBreadcrumbs'] = true;
            $this->global['breadcrumbs'] = lang('payments').' <span class="breadcrumb-arrow-right"></span> '.'Stripe';
            $this->loadViews("payments/success", $this->global, NULL);
        }
        else{
            redirect('/deposits/new');
        }
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