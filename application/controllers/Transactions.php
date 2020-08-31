<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Transactions (TransactionsController)
 * Transactions Class
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Transactions extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('plans_model');
        $this->load->model('login_model');
        $this->load->model('transactions_model');
        $this->load->model('settings_model');
        $this->load->model('email_model');
        $this->load->model('payments_model');
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
     * This function is used to load the earnings list
     */
    function earnings()
    {
        if($this->role == ROLE_CLIENT)
        {
            $searchText = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $this->global['searchText'] = $this->input->post('searchText', TRUE);
            $role = '3';

            $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);
            
            $this->load->library('pagination');
            
            $count = $this->transactions_model->earningsListingCount($searchText, $this->vendorId, date('Y-m-d H:i:s'));
			$returns = $this->paginationCompress ( "earnings/", $count, 10 );
            
            $data['transactions'] = $this->transactions_model->earnings($searchText, $returns["page"], $returns["segment"], $this->vendorId, date('Y-m-d H:i:s'));
            $data['interestEarnings'] = $this->transactions_model->getEarningsTotalByType($this->vendorId, 'interest');
            $data['referralEarnings'] = $this->transactions_model->getEarningsTotalByType($this->vendorId, 'referral');
            $data['principalEarnings'] = $this->transactions_model->getEarningsTotalByType($this->vendorId, 'principal');

            $this->global['pageTitle'] = 'Earnings';   
            $this->global['displayBreadcrumbs'] = false;          
            $this->loadViews("transactions/table", $this->global, $data, NULL);
            
        }
        else
        {     
            $module_id = 'payouts';
            $module_action = 'view';
            if($this->isAdmin($module_id, $module_action) == FALSE)
            {
                $this->loadThis();
            } else
            {
                $searchText = $this->input->post('searchText', TRUE);
                $data['searchText'] = $searchText;
                $this->global['searchText'] = $this->input->post('searchText', TRUE);
                $role = '3';
                
                $this->load->library('pagination');
                
                $count = $this->transactions_model->allEarningsListingCount($searchText, date('Y-m-d H:i:s'));
                $returns = $this->paginationCompress ( "earnings/", $count, 10 );
                
                $data['transactions'] = $this->transactions_model->allEarnings($searchText, $returns["page"], $returns["segment"], date('Y-m-d H:i:s'));
                $data['interestEarnings'] = $this->transactions_model->getEarningsTotalByType(Null, 'interest');
                $data['referralEarnings'] = $this->transactions_model->getEarningsTotalByType(Null, 'referral');
                $data['principalEarnings'] = $this->transactions_model->getEarningsTotalByType(Null, 'principal');

                $this->global['pageTitle'] = 'Earnings'; 
                $this->global['displayBreadcrumbs'] = false;            
                $this->loadViews("transactions/table", $this->global, $data, NULL);
            }
        }
    }

    /**
     * This function is used to load the deposits list
     */
    function deposits()
    {
        if($this->role == ROLE_CLIENT)
        {
            $searchText = $this->input->post('searchText', TRUE);
            $this->global['searchText'] = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $role = '3';
            
            $this->load->library('pagination');
            
            $count = $this->transactions_model->depositsListingCount($searchText, $this->vendorId);
            $returns = $this->paginationCompress ( "deposits/".$searchText, $count, 10 );
            
            $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);
            $data['transactions'] = $this->transactions_model->deposits($searchText, $returns["page"], $returns["segment"], $this->vendorId);
            $data['activeDeposits'] = $this->transactions_model->getActiveDeposits($this->vendorId);
            $data['inActiveDeposits'] = $this->transactions_model->getInActiveDeposits($this->vendorId);
            $data["plans"] = $this->plans_model->getPlans(); 
            
            $this->global['displayBreadcrumbs'] = false;
            $this->global['pageTitle'] = 'Deposits';
            
            $this->loadViews("transactions/table", $this->global, $data, NULL);
        }
        else
        {     
            $module_id = 'deposits';
            $module_action = 'view';
            if($this->isAdmin($module_id, $module_action) == FALSE)
            {
                $this->loadThis();
            } else
            {
                $searchText = $this->input->post('searchText', TRUE);
                $this->global['searchText'] = $this->input->post('searchText', TRUE);
                $data['searchText'] = $searchText;
                $role = '3';
                
                $this->load->library('pagination');
                
                $count = $this->transactions_model->allDepositsListingCount($searchText);
                $returns = $this->paginationCompress ( "deposits/", $count, 10 );
                
                $data['transactions'] = $this->transactions_model->allDeposits($searchText, $returns["page"], $returns["segment"]);
                $data['activeDeposits'] = $this->transactions_model->getActiveDeposits(Null);
                $data['inActiveDeposits'] = $this->transactions_model->getInActiveDeposits(Null);
                $data["plans"] = $this->plans_model->getPlans(); 
                
                $this->global['displayBreadcrumbs'] = false; 
                $this->global['pageTitle'] = 'Deposits';
                
                $this->loadViews("transactions/table", $this->global, $data, NULL);
            }
        }
    }

    /**
     * @access: Admin Only
     * @desc: This function is used to load the add new form
     */
    function newDeposit()
    {
        $companyInfo = $this->settings_model->getsettingsInfo();
        $data["companyInfo"] = $companyInfo ;
        $data["form_url"] = base_url('deposits/new');
        $this->global['pageTitle'] = 'New Deposits';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = lang('deposits').' <span class="breadcrumb-arrow-right"></span> '.lang('new');
        $data['breadcrumbs'] = lang('deposits'). '/' .lang('new_deposit');
        $this->load->helper('string');
        $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);

        if ($this->role == ROLE_CLIENT) 
        {
            $data["plans"] = $this->plans_model->getPlans(1);
            $data["clients"] = $this->user_model->users(Null, Null, Null, ROLE_CLIENT);
            $data['activeDeposits'] = $this->transactions_model->getActiveDeposits($this->vendorId);
            $data['paymentMethods'] = $this->payments_model->getPaymentMethods('1'); //Status 1 are activated payment methods
            
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('plan','Plan','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('amount','Amount','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('payMethod','Payment Method','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $plan = $this->input->post('plan', TRUE);
                $amount = $this->input->post('amount', TRUE);
                $method = $this->input->post('payMethod', TRUE);
                $code = 'NJ'.random_string('alnum',8);

                //Check if the amount is indeed within the plan
                $planData = $this->plans_model->getPlanById($plan);
                $max = $planData->maxInvestment;
                $min = $planData->minInvestment;

                if($amount >= $min && $amount <= $max)
                {
                    //Let us check the method API
                    $method_data = $this->payments_model->getPaymentMethodById($method);
                    $API = $method_data->API;

                    if($API == 0){
                        //This is a manual method of payment
                        $type_ref =  $method_data->ref;

                        if($type_ref == 'BT'){
                            $paymentData = array(
                                'DepositAmount'  => $amount,
                                'planId' => $plan,
                                'id' => $method
                            );
                        
                            $this->session->set_userdata($paymentData);
                            // 'item' will be erased after 300 seconds
                            $this->session->mark_as_temp(array('DepositAmount', 'planId'), 300);
                            redirect('/bank-transfer');
                        } else {
                            $paymentData = array(
                                'DepositAmount'  => $amount,
                                'planId' => $plan,
                                'id' => $method,
                                'name'=>$method_data->name
                            );
                        
                            $this->session->set_userdata($paymentData);
                            // 'item' will be erased after 300 seconds
                            $this->session->mark_as_temp(array('DepositAmount', 'planId'), 300);
                            redirect('/manual-payment');
                        }
                    } else {
                        //Let us check the API in use
                        $API_type = $this->payments_model->getAPIById($API);

                        $paymentMethodAPI = $API_type->name;

                        if($paymentMethodAPI == 'Stripe'){
                            $paymentData = array(
                                'DepositAmount'  => $amount,
                                'planId' => $plan
                            );
                        
                            $this->session->set_userdata($paymentData);
                            // 'item' will be erased after 300 seconds
                            $this->session->mark_as_temp(array('DepositAmount', 'planId'), 300);

                            redirect('/stripe-payment');
                        } else if($paymentMethodAPI == 'PayPal'){
                            $methodData = $this->payments_model->getInfo('tbl_addons_api', 'paypal');
                            $cc_amount = $companyInfo['currency'] == 'USD' ? $amount : $amount/$companyInfo['currency_exchange_rate'];
                            $config = [
                                "clientID"=> $methodData->public_key,
                                "currency"=>"USD", //default is USD
                                "intent"=>"sale", //default is sale
                                "mode"=>$methodData->env,
                                "invoiceNumber"=>$code,
                                "clientSecret"=> $methodData->secret_key,
                                "redirectUrl"=> base_url('paypal/success'),//controller method paypal will return here after success
                                "cancelUrl"=>base_url('paypal/canceled')//localhost/paypal-integration-ci/index.php/welcome/payment/canceled"//controller method paypal will return here after cancel
                            ];
                            $this->load->library('paypal',$config);
                            $result = $this->paypal->pay($cc_amount);
                            $deposit_array = array(
                                'userid'=>$this->vendorId,
                                'invoice'=>$code,
                                'plan'=>$plan,
                                'txn_id'=>$result["payment"]->id,
                                'local_currency'=>$amount,
                                'payment_gross'=>$cc_amount,
                                'currency_code'=>'USD',
                                'payer_email'=>'NA',
                                'payment_status'=>'0',
                                'createdDtm'=>date('Y-m-d H:i:s')

                            );
                            $this->payments_model->addPaypal($deposit_array);
                            if($result["error"] == '') { 
                                redirect($result["approval_url"]);
                            } else { 
                                $this->session->set_flashdata('error', 'There is an error depositing via Paypal');
                                redirect('/deposits/new');
                            }
                        } else if($paymentMethodAPI == 'Payeer'){
                            $this->load->model('payeer_model');
                            $methodData = $this->payments_model->getInfo('tbl_addons_api', 'payeer');
                            $cc_amount = $companyInfo['currency'] == 'USD' ? $amount : $amount/$companyInfo['currency_exchange_rate'];
                            //Let us first upload this info to the db
                            $pmInfo = array(
                                'userId' => $this->vendorId,
                                'planId' => $plan,
                                'order_id' => $code,
                                'amount' => $cc_amount,
                                'currency' => 'USD',
                                'status' => 0
                            );

                            $res = $this->payeer_model->create($pmInfo);

                            if($res){
                                $this->load->library('Payeer');
                                $config = array(
                                    'm_shop'    => $methodData->merchantID,
                                    'm_orderid' => $code,
                                    'm_amount'  => $cc_amount,
                                    'm_curr'    => 'USD',
                                    'm_desc'    => 'Deposit transaction',
                                    'm_key'     => $methodData->secret_key,
                                );
                                $payeer = new Payeer($config);
                                
                                $hash = $payeer->digital_signature();
                                
                                $payAnswer = false;
                                
                                if(isset($_GET['action']) && $_GET['action'] == 'payed'){
                                $payAnswer = $payeer->payment_handler();  // Check if the payment has passed
                                if($payAnswer == 'success')
                                    echo 'Payment was succesful!';
                                }
                                if($payAnswer != 'success'){
                                    $payeer->submitForm(); 
                                }
                            }
                        } else if($paymentMethodAPI == 'Perfect Money'){
                            $this->load->library('PerfectMoney');
                            $cc_amount = $companyInfo['currency'] == 'USD' ? $amount : $amount/$companyInfo['currency_exchange_rate'];
                            $config = array(
                                'payee_account'  => 'U24976189',
                                'payee_name'  => 'ProInvest',
                                'payment_id'  => $code,
                                'payment_amount' => $cc_amount,
                                'payment_units'  => 'USD'
                            );
                            $perfectmoney = new PerfectMoney($config);
                            $perfectmoney->submitForm();
                        } else if($paymentMethodAPI == 'CoinPayments'){
                            $coin = $method_data->ref;

                            $paymentData = array(
                                'DepositAmount'  => $amount,
                                'planId' => $plan,
                                'method' => $coin
                            );
                        
                            $this->session->set_userdata($paymentData);
                            // 'item' will be erased after 300 seconds
                            $this->session->mark_as_temp(array('DepositAmount', 'planId'), 300);
    
                            redirect('/coin-payment');
                        } else if($paymentMethodAPI == 'BTCPay'){
                            $this->load->model('btcpay_model');
                            $methodData = $this->payments_model->getInfo('tbl_addons_api', 'BTCPay');
                            //Let us first upload this info to the db
                            $btcpayInfo = array(
                                'userId' => $this->vendorId,
                                'planId' => $plan,
                                'order_id' => $code,
                                'amount' => $amount,
                                'currency' => $companyInfo['currency'],
                                'status' => 0
                            );

                            $res = $this->btcpay_model->create($btcpayInfo);

                            if($res){
                                $this->load->library('BtcPay');

                                $config = array(
                                    'store_id'    => $methodData->merchantID,
                                    'order_id' => $code,
                                    'amount'  => $amount,
                                    'url'=>$methodData->base_url,
                                    'currency'    => $companyInfo['currency'],
                                    'ipn' => base_url('btcpay/ipn'),
                                    'redirect' => base_url('btcpay/success'),
                                );

                                $btcpay = new BtcPay($config);

                                $btcpay->submitForm(); 
                            }
                        } else if($paymentMethodAPI == 'Paystack'){
                            $paymentData = array(
                                'DepositAmount'  => $amount,
                                'planId' => $plan
                            );
                        
                            $this->session->set_userdata($paymentData);
                            // 'item' will be erased after 300 seconds
                            $this->session->mark_as_temp(array('DepositAmount', 'planId'), 300);
                            redirect('/paystack');
                        }
                    }
                }
                else
                {
                    $this->session->set_flashdata('error', lang('please_input_the_correct_amount_according_to_your_plan'));
                }
            } 
            $this->loadViews("transactions/new", $this->global, $data);   
        } 
        else
        {
            $data["plans"] = $this->plans_model->getPlans();
            $module_id = 'deposits';
            $module_action = 'view';
            if($this->isAdmin($module_id, $module_action) == FALSE)
            {
                $this->loadThis();
            } 
            
            $data["clients"] = $this->user_model->users(Null, Null, Null, ROLE_CLIENT);
            $data['activeDeposits'] = $this->transactions_model->getActiveDeposits($this->vendorId);
            $data['paymentMethods'] = $this->payments_model->getPaymentMethods('1'); //Status 1 are activated payment methods

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('email','Email','required', array(
                'required' => lang('this_field_is_required'),
                'valid_email' => lang('this_email_is_invalid')
            ));
            $this->form_validation->set_rules('plan','Plan','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('amount','Amount','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $plan = $this->input->post('plan', TRUE);
                $email = $this->input->post('email', TRUE);
                $amount = $this->input->post('amount', TRUE);
                $method = 'manual';
                $code = 'NJ'.random_string('alnum',8);
                $date = date('Y-m-d H:i:s');
                $datetime = date('Y-m-d H:i:s', strtotime($date));

                //Check if the amount is indeed within the plan
                $planData = $this->plans_model->getPlanById($plan);
                $max = $planData->maxInvestment;
                $min = $planData->minInvestment;

                if($amount >= $min && $amount <= $max)
                {
                    //Get Plan Details
                    $maturityPeriod = $this->plans_model->getMaturity($plan)->period_hrs;
                    $payoutsInterval = $this->plans_model->getPeriod($plan)->period_hrs;

                    //Check if email exists for client
                    $emailCheck = $this->login_model->checkClientExist($email, '3'); 

                    if($emailCheck)
                    {
                        if($method == 'manual') {
                            $plan = $this->plans_model->getPlanById($this->input->post('plan', TRUE));

                            $depositInfo = array(
                                'userId'=>$emailCheck->userId, 
                                'txnCode'=>$code,
                                'amount'=>$amount, 
                                'paymentMethod'=> $method, 
                                'planId' => $this->input->post('plan', TRUE),
                                'status' => $plan->principalReturn == 1 ? '0' : '3',
                                'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")), 
                                'createdBy'=>$this->vendorId, 
                                'createdDtm'=>$datetime
                            );

                            $userId = $emailCheck->userId;
                            $dAmount = $this->input->post('amount', TRUE);
                            $profitPercent = $plan->profit/100;
                            $earningsAmount = $profitPercent*$dAmount;
                            $earningsType = 'interest';
                            $startDate = date('Y-m-d H:i:s', strtotime($date."+$payoutsInterval hours"));
                            $endDate = date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours"));
                            $businessDays = $plan->businessDays;
                                
                            $result = $this->transactions_model->addNewDeposit($userId, $depositInfo, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod, $businessDays);
                  
                            //Send email
                            if($result > 0){
                                //Put in cash to referree's accounts
                                $this->referralEarnings($userId, $dAmount, $result);

                                //Send Mail
                                $conditionUserMail = array('tbl_email_templates.type'=>'Deposit');
                                $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                                $companyInfo = $this->settings_model->getSettingsInfo();
                            
                                if($resultEmail->num_rows() > 0)
                                {
                                    $rowUserMailContent = $resultEmail->row();
                                    $splVars = array(
                                        "!plan" => $plan->name,
                                        "!interest" => $plan->profit.'%',
                                        "!period"=> $this->plans_model->getPeriod($plan->id)->maturity_desc,
                                        '!payout'=> to_currency($this->transactions_model->totalPayoutValue($result, $userId)),
                                        "!clientName" => $emailCheck->firstName,
                                        "!depositAmount" => to_currency($this->input->post('amount', TRUE)),
                                        "!companyName" => $companyInfo['name'],
                                        "!address" => $companyInfo['address'],
                                        "!siteurl" => base_url()
                                    );

                                    $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                                    $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                                    $toEmail = $email;
                                    $fromEmail = $companyInfo['SMTPUser'];

                                    $name = 'Support';

                                    $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                                    $send = $this->sendEmail($toEmail,$mailSubject,$mailContent);

                                    //Send SMS
                                    $userInfo = $this->user_model->getUserInfo($userId);
                                    $phone = $userInfo->mobile;
                                    if($phone){
                                        $body = strtr($rowUserMailContent->sms_body, $splVars);

                                        $this->twilio_model->send_sms($phone, $body);
                                    }

                                    if($send == true) {
                                        $this->session->set_flashdata('success', lang('deposit_successful'));
                                        redirect('deposits/new');
                                    } else {
                                        $this->session->set_flashdata('success', lang('deposit_successful_email_sending_failed'));
                                        redirect('deposits/new');
                                    }
                                }
                                
                            } else {
                                $this->session->set_flashdata('error', 'Error in depositing the funds');
                                redirect('deposits/new');
                            }

                        }
                    } 
                    else 
                    {
                        $this->session->set_flashdata('error', 'This email does not exist');
                        redirect('deposits/new');
                    }
                }
                else
                {
                    $this->session->set_flashdata('error', lang('please_input_the_correct_amount_according_to_your_plan'));
                    redirect('deposits/new');
                }
            }
            $this->loadViews("transactions/new", $this->global, $data);
        }
        
    }

    function bankTransfer() {
        if(!$this->session->userdata('DepositAmount'))
        {
            redirect('deposits/new');
        } else
        {
            $amount = $this->session->flashdata('amount');
            

            $this->global['pageTitle'] = 'Bank Transfer';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = lang('deposits').' <span class="breadcrumb-arrow-right"></span> '.'Bank Transfer';

            $data['payment'] = ($_SESSION['DepositAmount']);
            $id = ($_SESSION['id']);

            $bankData = $this->payments_model->getPaymentMethodById($id);

            $data['bank_name'] = $bankData->bank_name;
            $data['account_name'] = $bankData->account_name;
            $data['account_number'] = $bankData->account_number;
            $data['swift_code'] = $bankData->swift_code;
            $this->loadViews("payments/banktransfer", $this->global, $data, NULL);
        }
    }

    function manualTransfer() {
        if(!$this->session->userdata('DepositAmount'))
        {
            redirect('deposits/new');
        } else
        {
            $amount = $this->session->flashdata('amount');
            

            $this->global['pageTitle'] = 'Bank Transfer';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = lang('deposits').' <span class="breadcrumb-arrow-right"></span> '.'New';

            $data['payment'] = ($_SESSION['DepositAmount']);
            $id = ($_SESSION['id']);

            $manualData = $this->payments_model->getPaymentMethodById($id);

            $data['note'] = $manualData->note;
            $data['account'] = $manualData->name;
            $this->loadViews("payments/manualtransfer", $this->global, $data, NULL);
        }
    }

    public function validate_image() {
        $config["upload_path"] = './uploads';
        $config['allowed_types'] = 'jpg|png|pdf';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('img'))
        {
            $this->form_validation->set_message('validate_image',$this->upload->display_errors());
            return false;
        } else {
            return true;
        }
    }

    function add_bank_transfer(){
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();

        $this->load->helper('string');
        $this->load->library('form_validation');
            
        $this->form_validation->set_rules('img','Bank Slip','callback_validate_image');

        if($this->form_validation->run() == FALSE)
        {
            //Failed
            $res = array(
                'success' => false,
                'msg' => 'Please upload a valid bank slip either as PDF, JPG or PNG',
                "csrfTokenName" => $csrfTokenName,
                "csrfHash" => $csrfHash
            );

            echo json_encode($res);
        }
        else
        {
            $code = 'NJ'.random_string('alnum',8);
            $amount = ($_SESSION['DepositAmount']);
            $plan = ($_SESSION['planId']);
            $date = date('Y-m-d H:i:s');
            $data = ($this->upload->data());
            $ppic = $data["file_name"];

            $planData = $this->plans_model->getPlanById($plan);
            $max = $planData->maxInvestment;
            $min = $planData->minInvestment;

            if($amount >= $min && $amount <= $max)
            {
                //Get Plan Details
                $maturityPeriod = $this->plans_model->getMaturity($plan)->period_hrs;

                $depositInfo = array(
                    'userId'=>$this->vendorId, 
                    'txnCode'=>$code,
                    'amount'=>$amount, 
                    'paymentMethod'=> 'Bank Transfer', 
                    'img'=>$ppic,
                    'planId' => $plan,
                    'status' => '4',
                    'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")), 
                    'createdBy'=>$this->vendorId, 
                    'createdDtm'=>$date
                );

                $result = $this->transactions_model->create_deposit($depositInfo);

                if($result > 0){
                    //redirect('/deposits');
                    //$this->session->set_flashdata('success', lang('deposit_successful'));

                    unset(
                        $_SESSION['DepositAmount'],
                        $_SESSION['planId'],
                        $_SESSION['id']
                    );
                    //Success
                    $res = array(
                        'success' => true,
                        'msg' => lang('deposit_successful'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($res);
                } else {
                    //redirect('/bank-transfer');
                    //$this->session->set_flashdata('error', 'Error in depositing the funds');

                    //Failed
                    $res = array(
                        'success' => false,
                        'msg' => lang('deposit_failed'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($res);
                }
            }
        }
    }

    function add_manual_transfer(){
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();

        $this->load->helper('string');
        $this->load->library('form_validation');
            
        $this->form_validation->set_rules('account','Deposit Account','required', array(
            'required' => lang('this_field_is_required')
        ));

        if($this->form_validation->run() == FALSE)
        {
            //Failed
            $res = array(
                'success' => false,
                'msg' => 'Please enter the account that you\'ve sent the deposit from',
                "csrfTokenName" => $csrfTokenName,
                "csrfHash" => $csrfHash
            );

            echo json_encode($res);
        }
        else
        {
            $code = 'NJ'.random_string('alnum',8);
            $amount = ($_SESSION['DepositAmount']);
            $plan = ($_SESSION['planId']);
            $date = date('Y-m-d H:i:s');
            $account = $this->input->post('account', TRUE);

            $planData = $this->plans_model->getPlanById($plan);
            $max = $planData->maxInvestment;
            $min = $planData->minInvestment;

            if($amount >= $min && $amount <= $max)
            {
                //Get Plan Details
                $maturityPeriod = $this->plans_model->getMaturity($plan)->period_hrs;

                $depositInfo = array(
                    'userId'=>$this->vendorId, 
                    'txnCode'=>$code,
                    'amount'=>$amount, 
                    'paymentMethod'=> ($_SESSION['name']), 
                    'deposit_account'=>$account,
                    'planId' => $plan,
                    'status' => '4',
                    'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")), 
                    'createdBy'=>$this->vendorId, 
                    'createdDtm'=>$date
                );

                $result = $this->transactions_model->create_deposit($depositInfo);

                if($result > 0){
                    //redirect('/deposits');
                    //$this->session->set_flashdata('success', lang('deposit_successful'));
                    //unset session data
                    unset(
                        $_SESSION['DepositAmount'],
                        $_SESSION['planId'],
                        $_SESSION['id'],
                        $_SESSION['name']
                    );

                    //Success
                    $res = array(
                        'success' => true,
                        'msg' => lang('deposit_successful'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($res);
                } else {
                    //redirect('/bank-transfer');
                    //$this->session->set_flashdata('error', 'Error in depositing the funds');

                    //Failed
                    $res = array(
                        'success' => false,
                        'msg' => lang('deposit_failed'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($res);
                }
            }
        }
    }

    function editDeposit($depositID = NULL)
    {
        $module_id = 'deposits';
        $module_action = 'edit';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $depositInfo1 = $this->transactions_model->getDepositInfoById($depositID);
            if($depositID == null)
            {
                redirect('deposits');
            } 
            
            $this->load->helper('string');
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('plan','Plan','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('amount','Amount','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('email','Email','required|valid_email', array(
                'required' => lang('this_field_is_required'),
                'valid_email' => lang('this_email_is_invalid')
            ));
            $this->form_validation->set_rules('payMethod','Payment Method','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('date','Date','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $plan = $this->input->post('plan', TRUE);
                $email = $this->input->post('email', TRUE);
                $amount = $this->input->post('amount', TRUE);
                $method = $this->input->post('payMethod', TRUE);
                $date = $this->input->post('date', TRUE);
                $datetime = date('Y-m-d H:i:s', strtotime($date));

                if($depositInfo1->status == 4){
                    $status = '3';
                } else {
                    $status = $depositInfo1->status;
                }

                //Get Plan Details
                $maturityPeriod = $this->plans_model->getMaturity($plan)->period_hrs;
                $payoutsInterval = $this->plans_model->getPeriod($plan)->period_hrs;

                //Check if email exists for client
                $emailCheck = $this->login_model->checkClientExist($email, '3'); 

                if($emailCheck)
                {
                    $depositInfo = array(
                        'userId'=>$emailCheck->userId, 
                        'amount'=>$amount, 
                        'paymentMethod'=> $method, 
                        'planId' => $plan,
                        'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")),  
                        'status'=>$status,
                        'createdBy'=>$this->vendorId, 
                        'createdDtm'=>$datetime
                    );

                    $plan = $this->plans_model->getPlanById($this->input->post('plan', TRUE));
                    $dAmount = $this->input->post('amount', TRUE);
                    $profitPercent = $plan->profit/100;

                    $earningsAmount = $profitPercent*$dAmount;
                    $earningsType = 'interest';
                    $startDate = date('Y-m-d H:i:s', strtotime($date."+$payoutsInterval hours"));
                    $endDate = date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours"));
                    $businessDays = $plan->businessDays;

                    $result = $this->transactions_model->updateDeposit($emailCheck->userId, $depositID, $depositInfo, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod, $businessDays);
                        
                    if ($result > 0) 
                    {
                        if($depositInfo1->status == 4){
                            //Process the referal credits
                            $this->referralEarnings($emailCheck->userId, $dAmount, $depositID);

                            $this->session->set_flashdata('success', 'Deposit Approved Successfully');
                        } else {
                            $this->session->set_flashdata('success', lang('deposit_edited_successfully'));
                        }
                        redirect('deposits/editTrans/'.$depositID);
                    } else 
                    {
                        if($depositInfo1->status == 4){
                            $this->session->set_flashdata('error', 'Deposit Approval has failed');
                        }else {
                            $this->session->set_flashdata('error', lang('deposit_editing_has_failed'));
                        }
                        redirect('deposits/editTrans/'.$depositID);
                    }
                } 
                else 
                {
                    $this->session->set_flashdata('error', lang('this_email_does_not_exist'));
                    redirect('deposits/editTrans/'.$depositID);
                }
            }

            $data['depositInfo'] = $depositInfo1;
            $data["plans"] = $this->plans_model->getPlans();    
            $data['email'] = $this->user_model->getUserInfoById($depositInfo1->userId)->email;  
            $data['paymentMethods'] = $this->payments_model->getPaymentMethods('1'); //Status 1 are activated payment methods      
            
            $this->global['pageTitle'] = 'Edit Deposit'; 
            $this->global['displayBreadcrumbs'] = false;      
            $this->global['breadcrumbs'] = lang('deposits').' <span class="breadcrumb-arrow-right"></span> '.lang('edit');          
            $this->loadViews("transactions/edit", $this->global, $data, NULL);
        }
    }

    function reInvest()
    {
        if(!$this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        }
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->load->helper('string');
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('password','Password','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('code','code','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('plan','Plan','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                $response['errors'] = array_filter($errors); // Some might be empty
                $response['success'] = false;
                $response["csrfTokenName"] = $csrfTokenName;
                $response["csrfHash"] = $csrfHash;
                $response['msg'] = html_escape(lang('please_correct_errors_and_try_again'));

                echo json_encode($response);
            }
            else
            {
                $userId = $this->vendorId;
                $email = $this->user_model->getUserInfo($userId)->email;
                $password = $this->input->post('password', TRUE);
                $planId = $this->input->post('plan', TRUE);

                //Check if this is the right passsword
                $result = $this->login_model->loginMe($email, $password);

                if(!empty($result))
                {
                    $depositId = $this->input->post('code', TRUE);

                    $res = $this->transactions_model->getDeposit($depositId, $userId);

                    if($res && $res->status == 0)
                    {
                        //First update the record as having been withdrawn
                        $depositInfo = array(
                            'status' => '1'
                        );

                        $result = $this->transactions_model->editDeposit($depositInfo, $res->id);

                        if ($result == true) 
                        {
                            $code = 'NJ'.random_string('alnum',8);
                            $date = date('Y-m-d H:i:s');
                            $datetime = date('Y-m-d H:i:s', strtotime($date));

                            //Get Plan Details
                            $maturityPeriod = $this->plans_model->getMaturity($res->planId)->period_hrs;
                            $payoutsInterval = $this->plans_model->getPeriod($res->planId)->period_hrs;

                            //Create another deposit
                            $depositInfo1 = array(
                                'userId'=>$userId, 
                                'txnCode'=>$code,
                                'amount'=>$res->amount, 
                                'paymentMethod'=> 'reinvestment', 
                                'planId' => $planId,
                                'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")), 
                                'createdBy'=>$userId, 
                                'createdDtm'=>$datetime
                            );

                            //Earnings Info
                            $plan = $this->plans_model->getPlanById($planId);
                            
                            $dAmount = $res->amount;
                            $profitPercent = $plan->profit/100;

                            $earningsAmount = $profitPercent*$dAmount;
                            $earningsType = 'interest';
                            $startDate = date('Y-m-d H:i:s', strtotime($date."+$payoutsInterval hours"));
                            $endDate = date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours"));
                            $businessDays = $plan->businessDays;

                            $result1 = $this->transactions_model->addNewDeposit($userId, $depositInfo1, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod, $businessDays);

                            if($result1>0){                            
                                //Send Mail
                                $conditionUserMail = array('tbl_email_templates.type'=>'Deposit');
                                $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                                $companyInfo = $this->settings_model->getsettingsInfo();
                            
                                if($resultEmail->num_rows() > 0)
                                {
                                    $rowUserMailContent = $resultEmail->row();
                                    $splVars = array(
                                        "!clientName" => $this->firstName,
                                        "!depositAmount" => to_currency($res->amount),
                                        "!companyName" => $companyInfo['name'],
                                        "!address" => $companyInfo['address'],
                                        "!siteurl" => base_url()
                                    );

                                    $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                                    $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                                    $toEmail = $email;
                                    $fromEmail = $companyInfo['SMTPUser'];

                                    $name = 'Support';

                                    $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                                    $send = $this->sendEmail($toEmail,$mailSubject,$mailContent);

                                    if($send == true) {
                                        $array = array(
                                            'success' => true,
                                            "csrfTokenName" => $csrfTokenName,
                                            "csrfHash" => $csrfHash,
                                            'msg' => html_escape(lang('you_have_successfully_reinvested').' '.$res->amount),
                                        );
                            
                                        echo json_encode($array);
                                    } else {
                                        $array = array(
                                            'success' => true,
                                            "csrfTokenName" => $csrfTokenName,
                                            "csrfHash" => $csrfHash,
                                            'msg' => html_escape(lang('you_have_successfully_reinvested').' '.$res->amount),
                                        );
                            
                                        echo json_encode($array);
                                    }

                                    //Send SMS
                                    $userInfo = $this->user_model->getUserInfo($userId);
                                    $phone = $userInfo->mobile;
                                    if($phone){
                                        $body = strtr($rowUserMailContent->sms_body, $splVars);

                                        $this->twilio_model->send_sms($phone, $body);
                                    }
                                }
                                
                            } else {
                                $array = array(
                                    'success' => false,
                                    "csrfTokenName" => $csrfTokenName,
                                    "csrfHash" => $csrfHash,
                                    'msg' => html_escape(lang('there_is_an_error_in_reinvesting_your_funds')),
                                );
                    
                                echo json_encode($array);
                            }
                        }
                    } else
                    {
                        $array = array(
                            'success' => false,
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash,
                            'msg' => html_escape(lang('you_have_either_reinvested_or_withdrawn_these_funds')),
                        );
            
                        echo json_encode($array);
                    }
                }
                else
                {
                    $array = array(
                        'success' => false,
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash,
                        'msg' => html_escape(lang('incorrect_password_try_again'))
                    );

                    echo json_encode($array);
                }
            }
        }
    }

    function withdrawDeposit()
    {
        if(!$this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        }
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->helper('string');
            $depositID = $this->input->post('code', TRUE);
            $code = 'NJ'.random_string('alnum',8);
            if($depositID == null)
            {
                redirect('deposits');
            } 

            $userID = $this->vendorId;
            //Find out if the deposit is indeed matured, has not been withdrawn and belongs to this user
            $res = $this->transactions_model->getDeposit($depositID, $userID);

            if($res && $res->status == 0)
            {
                $depositInfo = array(
                    'status'=> '1'
                );
                        
                $result = $this->transactions_model->editDeposit($depositInfo, $res->id);

                if ($result == true) 
                {
                    $withdrawalInfo = array(
                        'userId'=>$userID, 
                        'txnCode'=>$depositID,
                        'amount'=>$res->amount, 
                        'status' => '0',
                        'createdBy'=>$this->vendorId, 
                        'createdDtm'=> date('Y-m-d H:i:s')
                    );
                    $result1 = $this->transactions_model->addNewWithdrawal($withdrawalInfo);

                    if($result1>0)
                    {
                        $result3 = $this->user_model->getUserInfo($this->vendorId);

                        $earningsInfo = array(
                            'userId'    => $userID,
                            'type'      => 'Principal',
                            'depositId' => $res->id,
                            'txnCode'   => $code,
                            'amount'    =>$res->amount,
                            'email_sent'=> '1'
                        );

                        $this->transactions_model->addNewEarning($earningsInfo);

                        if($result3)
                        {
                            //Send Mail
                            $conditionUserMail = array('tbl_email_templates.type'=>'Withdrawal Request');
                            $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                            $companyInfo = $this->settings_model->getsettingsInfo();
                        
                            if($resultEmail->num_rows() > 0)
                            {
                                $rowUserMailContent = $resultEmail->row();
                                $splVars = array(
                                    "!clientName" => $result3->firstName,
                                    "!withdrawalAmount" => to_currency($res->amount),
                                    "!companyName" => $companyInfo['name'],
                                    "!address" => $companyInfo['address'],
                                    "!siteurl" => base_url()
                                );

                                $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                                $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                                $toEmail = $result3->email;
                                $fromEmail = $companyInfo['SMTPUser'];

                                $name = 'Support';

                                $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                                $send = $this->sendEmail($toEmail,$mailSubject,$mailContent);

                                if($send == true) {
                                    $array = array(
                                        'success' => true,
                                        "csrfTokenName" => $csrfTokenName,
                                        "csrfHash" => $csrfHash,
                                        'msg' => html_escape(lang('your_withdrawal_request_is_successful')),
                                    );
                        
                                    echo json_encode($array);

                                    $this->session->set_flashdata('success', lang('your_withdrawal_request_is_successful'));
                                } else {
                                    $array = array(
                                        'success' => true,
                                        "csrfTokenName" => $csrfTokenName,
                                        "csrfHash" => $csrfHash,
                                        'msg' => html_escape(lang('your_withdrawal_request_is_successful')),
                                    );
                        
                                    echo json_encode($array);

                                    $this->session->set_flashdata('error', lang('your_withdrawal_request_is_successful'));
                                }

                                //Send SMS
                                $phone = $result3->mobile;
                                if($phone){
                                    $body = strtr($rowUserMailContent->sms_body, $splVars);

                                    $this->twilio_model->send_sms($phone, $body);
                                }
                            }
                        }
                    }
                }
                else
                {
                    $array = array(
                        'success' => false,
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash,
                        'msg' => html_escape(''),
                    );
        
                    echo json_encode($array);

                    $this->session->set_flashdata('success', lang('there_is_an_error_in_processing_your_withdrawal_please_try_again_later'));
                }
            } else
            {
                $array = array(
                    'success' => false,
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash,
                    'msg' => html_escape(lang('this_transaction_has_either_been_processed_or_reinvested')),
                );
    
                echo json_encode($array);
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
                        //We then proceed to put it in an array with referrerId_[1] as the first Id
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

    function cancelDeposit($depositID)
    {
        $module_id = 'deposits';
        $module_action = 'edit';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        { 
            if($depositID == null)
            {
                redirect('deposits');
            } 

            $depositInfo = $this->transactions_model->getDepositInfoById($depositID);

            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            $this->form_validation->set_rules('id','ID','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('password','Password','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                $array = array(
                    'success' => false,
                    'msg' => html_escape(lang('enter_password_to_proceed')),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
            else
            {
                $password = $this->input->post('password', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result = $this->login_model->loginMe($useremail, $password);
                if(!empty($result))
                {
                    $result1 = $this->transactions_model->cancelDeposit($depositID);
                    if ($result1 == true){
                        $this->session->set_flashdata('success', lang('you_have_successfully_deleted_the_transaction'));
                        $array = array(
                            'success' => true,
                            'msg' => html_escape(lang('you_have_successfully_deleted_the_transaction')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    } else {
                        $this->session->set_flashdata('error', lang('there_is_a_problem_in_deleting_your_deposit_please_reload_and_try_again'));
                        $array = array(
                            'success' => false,
                            'msg' => html_escape(lang('there_is_a_problem_in_deleting_your_deposit_please_reload_and_try_again')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('incorrect_password_try_again')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );
    
                    echo json_encode($array);
                }
            }
        }
    }

    function deleteDeposit($depositID = NULL)
    {
        $module_id = 'deposits';
        $module_action = 'edit';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        { 
            if($depositID == null)
            {
                redirect('deposits');
            } 

            $depositInfo = $this->transactions_model->getDepositInfoById($depositID);

            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            $this->form_validation->set_rules('id','ID','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('password','Password','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                $array = array(
                    'success' => false,
                    'msg' => html_escape(lang('enter_password_to_proceed')),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
            else
            {
                $password = $this->input->post('password', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result = $this->login_model->loginMe($useremail, $password);
                if(!empty($result))
                {
                    $result1 = $this->transactions_model->deleteDeposit($depositID);
                    if ($result1 == true){
                        $this->session->set_flashdata('success', lang('you_have_successfully_deleted_the_transaction'));
                        $array = array(
                            'success' => true,
                            'msg' => html_escape(lang('you_have_successfully_deleted_the_transaction')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    } else {
                        $this->session->set_flashdata('error', lang('there_is_a_problem_in_deleting_your_deposit_please_reload_and_try_again'));
                        $array = array(
                            'success' => false,
                            'msg' => html_escape(lang('there_is_a_problem_in_deleting_your_deposit_please_reload_and_try_again')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('incorrect_password_try_again')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );
    
                    echo json_encode($array);
                }
            }
        }
    }

    
    /**
     * This function is used to load the deposits list
     */
    function withdrawals()
    {
        $searchText = $this->input->post('searchText', TRUE);
        $this->global['searchText'] = $this->input->post('searchText', TRUE);

        if($this->role == ROLE_CLIENT)
        {
            
            $role = '3';

            $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);
            
            $this->load->library('pagination');
            
            $count = $this->transactions_model->userWithdrawalsListingCount($searchText, $this->vendorId);
			$returns = $this->paginationCompress ( "withdrawals/", $count, 10 );
            
            $data['transactions'] = $this->transactions_model->withdrawalsById($searchText, $returns["page"], $returns["segment"], $this->vendorId);
            $data['earningsInfo'] = $this->transactions_model->getEarningsTotal($this->vendorId);
            $data['withdrawalsInfo'] = $this->transactions_model->getPendingWithdrawalsTotal($this->vendorId);
            
            $this->global['pageTitle'] = 'Withdrawals';
            $this->global['displayBreadcrumbs'] = false; 
            $this->loadViews("transactions/table", $this->global, $data, NULL);
        }
        else
        {  
            $module_id = 'withdrawals';
            $module_action = 'view';
            if($this->isAdmin($module_id, $module_action) == FALSE)
            {
                $this->loadThis();
            } else 
            {
                $role = '3';
            
                $this->load->library('pagination');
                
                $count = $this->transactions_model->allWithdrawalsListingCount($searchText);
                $returns = $this->paginationCompress ( "withdrawals/", $count, 10 );
                
                $data['transactions'] = $this->transactions_model->allwithdrawals($searchText, $returns["page"], $returns["segment"], $role);
                $data['completedWithdrawals'] = $this->transactions_model->getApprovedWithdrawalsTotal();
                $data['withdrawalsInfo'] = $this->transactions_model->getPendingWithdrawalsTotal(Null);
                
                $this->global['pageTitle'] = 'Withdrawals';
                $this->global['displayBreadcrumbs'] = false; 
                $this->loadViews("transactions/table", $this->global, $data, NULL);
            }      
        }
    }

    function approveWithdrawal($withdrawalId = NULL)
    {
        $module_id = 'withdrawals';
        $module_action = 'approve';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            if($withdrawalId == null)
            {
                redirect('withdrawals');
            }  
            
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            $this->form_validation->set_rules('id','ID','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('password','Password','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                $array = array(
                    'success' => false,
                    'msg' => html_escape(lang('enter_password_to_proceed')),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
            else
            {
                $password = $this->input->post('password', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result = $this->login_model->loginMe($useremail, $password);
                if(!empty($result))
                {
                    $withdrawalInfo = array('status'=> 1);
                    $result1 = $this->transactions_model->updateWithdrawal($withdrawalInfo, $withdrawalId);

                    if($result1 == true)
                    {
                        $withdrawalInfo = $this->transactions_model->getWithdrawalInfo($withdrawalId);
                        $userId = $withdrawalInfo->userId;
                        $amount = $withdrawalInfo->amount;
                        $userInfo = $this->user_model->getUserInfo($userId);
                        $email = $userInfo->email;
                        $name = $userInfo->firstName;
                        //Send Mail
                        $conditionUserMail = array('tbl_email_templates.type'=>'Withdrawal Approval');
                        $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                        $companyInfo = $this->settings_model->getsettingsInfo();
                    
                        if($resultEmail->num_rows() > 0)
                        {
                            $rowUserMailContent = $resultEmail->row();
                            $splVars = array(
                                "!clientName" => $name,
                                "!withdrawalAmount" => to_currency($amount),
                                "!companyName" => $companyInfo['name'],
                                "!address" => $companyInfo['address'],
                                "!siteurl" => base_url()
                            );

                            $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                            $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                            $toEmail = $email;
                            $fromEmail = $companyInfo['SMTPUser'];

                            $name = 'Support';

                            $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                            $send = $this->sendEmail($toEmail,$mailSubject,$mailContent);

                            if($send == true) {
                                $this->session->set_flashdata('success', lang('you_have_successfully_approved_the_withdrawal'));
                                $array = array(
                                    'success' => true,
                                    'msg' => html_escape(lang('you_have_successfully_approved_the_withdrawal')),
                                    "csrfTokenName" => $csrfTokenName,
                                    "csrfHash" => $csrfHash
                                );
                
                                echo json_encode($array);
                            } else {
                                $this->session->set_flashdata('error', 'Email sending has failed, try again.');
                                $array = array(
                                    'success' => true,
                                    'msg' => html_escape(lang('you_have_successfully_approved_the_withdrawal')),
                                    "csrfTokenName" => $csrfTokenName,
                                    "csrfHash" => $csrfHash
                                );
                
                                echo json_encode($array);
                            }

                            //Send SMS
                            $phone = $userInfo->mobile;
                            if($phone){
                                $body = strtr($rowUserMailContent->sms_body, $splVars);

                                $this->twilio_model->send_sms($phone, $body);
                            }
                        }
                    } else
                    {
                        redirect('withdrawals');
                    }
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('incorrect_password_try_again')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );
    
                    echo json_encode($array);
                }
            }
        }

    }

    function declineWithdrawal($withdrawalId = NULL)
    {
        $module_id = 'withdrawals';
        $module_action = 'approve';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            if($withdrawalId == null)
            {
                redirect('withdrawals');
            }  
            
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            $this->form_validation->set_rules('id','ID','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('password','Password','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                $array = array(
                    'success' => false,
                    'msg' => html_escape(lang('enter_password_to_proceed')),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
            else
            {
                $password = $this->input->post('password', TRUE);
                $reason = $this->input->post('reason', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result = $this->login_model->loginMe($useremail, $password);
                if(!empty($result))
                {
                    $withdrawalInfo = array(
                        'status'=> 2,
                        'reason'=>$reason
                    );

                    $result1 = $this->transactions_model->updateWithdrawal($withdrawalInfo, $withdrawalId);

                    if($result1 == true)
                    {
                        $withdrawalInfo = $this->transactions_model->getWithdrawalInfo($withdrawalId);
                        $userId = $withdrawalInfo->userId;
                        $amount = $withdrawalInfo->amount;
                        $userInfo = $this->user_model->getUserInfo($userId);
                        $email = $userInfo->email;
                        $name = $userInfo->firstName;
                        //Send Mail
                        $conditionUserMail = array('tbl_email_templates.type'=>'Withdrawal Rejected');
                        $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                        $companyInfo = $this->settings_model->getsettingsInfo();
                    
                        if($resultEmail->num_rows() > 0)
                        {
                            $rowUserMailContent = $resultEmail->row();
                            $splVars = array(
                                "!clientName" => $name,
                                "!withdrawalAmount" => to_currency($amount),
                                "!companyName" => $companyInfo['name'],
                                "!address" => $companyInfo['address'],
                                "!siteurl" => base_url()
                            );

                            $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                            $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                            $toEmail = $email;
                            $fromEmail = $companyInfo['SMTPUser'];

                            $name = 'Support';

                            $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                            $send = $this->sendEmail($toEmail,$mailSubject,$mailContent);

                            if($send == true) {
                                $this->session->set_flashdata('success', 'Withdrawal Request Rejected');
                                $array = array(
                                    'success' => true,
                                    'msg' => html_escape('Withdrawal Request Marked As Declined'),
                                    "csrfTokenName" => $csrfTokenName,
                                    "csrfHash" => $csrfHash
                                );
                
                                echo json_encode($array);
                            } else {
                                $this->session->set_flashdata('error', 'Email sending has failed, try again.');
                                $array = array(
                                    'success' => true,
                                    'msg' => html_escape('Withdrawal Request Marked As Declined'),
                                    "csrfTokenName" => $csrfTokenName,
                                    "csrfHash" => $csrfHash
                                );
                
                                echo json_encode($array);
                            }

                            //Send SMS
                            $phone = $userInfo->mobile;
                            if($phone){
                                $body = strtr($rowUserMailContent->sms_body, $splVars);

                                $this->twilio_model->send_sms($phone, $body);
                            }
                        }
                    } else
                    {
                        redirect('withdrawals');
                    }
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('incorrect_password_try_again')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );
    
                    echo json_encode($array);
                }
            }
        }

    }

    /**
     * @access: Admin Only
     * @desc: This function is used to load the add new form
     */
    /**
     * @access: Admin Only
     * @desc: This function is used to load the add new form
     */
    function newWithdrawal()
    {
        //$data["plans"] = $this->plans_model->getPlans();
        $this->global['pageTitle'] = 'New Withdrawal';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = 'Withdrawals'.' <span class="breadcrumb-arrow-right"></span> '.'New';
        $data['form_url'] = base_url('withdrawals/new');
        $data['displayBreadcrumbs'] = true;
        $data['breadcrumbs'] = "Withdrawals / New Withdrawal";
        $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);
        $this->load->helper('string');

        $data['earningsInfo'] = $this->transactions_model->getEarningsTotal($this->vendorId);
        $data['withdrawals'] = $this->transactions_model->getWithdrawalsTotal($this->vendorId);
        $data['pendingWithdrawals'] = $this->transactions_model->getPendingWithdrawalsTotal($this->vendorId);
        $data['accountInfo'] = abs($data['earningsInfo'] - $data['withdrawals'] - $data['pendingWithdrawals']);

        if ($this->role == ROLE_CLIENT) {
            $data['withdrawalMethods'] = $this->payments_model->getPaymentMethods('1'); //Status 1 are activated payment methods
            $this->loadViews("transactions/new", $this->global, $data);
        } else {
            $this->loadThis();
        }
    }


    function withdraw()
    {
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();

        $this->global['pageTitle'] = 'New Withdrawal';
        $this->global['displayBreadcrumbs'] = true; 

        $this->global['breadcrumbs'] = lang('withdrawals').' <span class="breadcrumb-arrow-right"></span> '.lang('new');

        $data['displayBreadcrumbs'] = true;
        $data['breadcrumbs'] = lang("withdrawals"). '/'. lang('new_withdrawal');
        $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);
        $this->load->helper('string');

        $method = $this->input->post('withdrawalMethod'); 
        $methodInfo = $this->payments_model->getPaymentMethodById($method);

        if ($this->role == ROLE_CLIENT) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('amount','Amount','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($methodInfo->API == 1){
                $this->form_validation->set_rules('cardNumber');
            } else if($methodInfo->API == 0 && $methodInfo->ref == 'BT'){
                $this->form_validation->set_rules('bank_name','Bank Name','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('account_name','Account Name','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('account_number','Account Number','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('swift_code','Swift Code','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else {
                $this->form_validation->set_rules('account','Account','required', array(
                    'required' => lang('this_field_is_required')
                ));
            }

            if($this->form_validation->run() == FALSE)
            {
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                $response['errors'] = array_filter($errors); // Some might be empty
                $response['success'] = false;
                $response["csrfTokenName"] = $csrfTokenName;
                $response["csrfHash"] = $csrfHash;
                $response['msg'] = html_escape(lang('please_correct_errors_and_try_again'));

                echo json_encode($response); 
            }
            else
            {
                $earnings = $this->transactions_model->getEarningsTotal($this->vendorId);
                $withdrawals = $this->transactions_model->getWithdrawalsTotal($this->vendorId);
                $pendingWithdrawals = $this->transactions_model->getPendingWithdrawalsTotal($this->vendorId);
                $availableFunds =  $earnings-$withdrawals;
                $amount = $this->input->post('amount', TRUE);
                $companyInfo = $this->settings_model->getSettingsInfo();
                $minwithdrawal = $companyInfo['min_withdrawal'];

                //First check min withdrawal amount
                if($amount < $minwithdrawal){
                    $this->session->set_flashdata('error', 'The minimum withdrawal amount is '.$minwithdrawal);
                } else {
                    if($availableFunds < $amount){
                        //$this->session->set_flashdata('error', 'You don\'t have enough funds to make a withdrawal');
                        $res = array(
                            'success' => false,
                            'msg' => 'You don\'t have enough funds to make a withdrawal',
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );
        
                        echo json_encode($res);
                    } else if($amount > ($availableFunds - $pendingWithdrawals)) {
                        //$this->session->set_flashdata('error', 'You have pending withdrawals. You can only make a withdrawal of'.' $'.($availableFunds - $pendingWithdrawals));
                        $res = array(
                            'success' => false,
                            'msg' => 'You have pending withdrawals. You can only make a withdrawal of'.' $'.($availableFunds - $pendingWithdrawals),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );
        
                        echo json_encode($res);
                    } else {
                        $userId = $this->vendorId;
                        $code = 'WT'.random_string('alnum',8);
                        $status = 0;
                        $createdBy = $this->vendorId;
                        $createdDtm = date('Y-m-d H:i:s'); 
    
                        $withdrawal_method = $this->payments_model->getPaymentMethodById($method)->name;
    
                        if($methodInfo->API == 1){
                            $withdrawal_account = $this->input->post('cardNumber');
                            $bank_name = NULL;
                            $account_name = NULL;
                            $account_number = NULL;
                            $swift_code = NULL;
                        } else if($methodInfo->API == 0 && $methodInfo->ref == 'BT'){
                            $withdrawal_account = NULL;
                            $bank_name = $this->input->post('bank_name');
                            $account_name = $this->input->post('account_name');
                            $account_number = $this->input->post('account_number');
                            $swift_code = $this->input->post('swift_code');
                        } else {
                            $withdrawal_account = $this->input->post('account');
                            $bank_name = NULL;
                            $account_name = NULL;
                            $account_number = NULL;
                            $swift_code = NULL;
                        }
    
                        $withdrawalInfo = array(
                            'userId'=>$userId, 
                            'txnCode'=>$code,
                            'amount'=>$amount, 
                            'withdrawal_method'=>$withdrawal_method,
                            'withdrawal_Account'=>$withdrawal_account,
                            'bank_name'=>$bank_name,
                            'account_name'=>$account_name,
                            'account_number'=>$account_number,
                            'swift_code'=>$swift_code,
                            'status' => $status,
                            'createdBy'=>$createdBy, 
                            'createdDtm'=>$createdDtm
                        );
                        $result = $this->transactions_model->addNewWithdrawal($withdrawalInfo);
    
                        if($result > 0)
                        {
                            $result3 = $this->user_model->getUserInfo($this->vendorId);
    
                            if($result3)
                            {
                                //Send Mail
                                $conditionUserMail = array('tbl_email_templates.type'=>'Withdrawal Request');
                                $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);
    
                                $companyInfo = $this->settings_model->getsettingsInfo();
                            
                                if($resultEmail->num_rows() > 0)
                                {
                                    $rowUserMailContent = $resultEmail->row();
                                    $splVars = array(
                                        "!clientName" => $result3->firstName,
                                        "!withdrawalAmount" => to_currency($this->input->post('amount', TRUE)),
                                        "!companyName" => $companyInfo['name'],
                                        "!address" => $companyInfo['address'],
                                        "!siteurl" => base_url()
                                    );
    
                                    $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                                    $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	
    
                                    $toEmail = $result3->email;
                                    $fromEmail = $companyInfo['SMTPUser'];
    
                                    $name = 'Support';
    
                                    $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields
    
                                    $send = $this->sendEmail($toEmail,$mailSubject,$mailContent);
    
                                    if($send == true) {
                                        //$this->session->set_flashdata('success', lang('your_withdrawal_request_is_successful'));
                                        $res = array(
                                            'success' => true,
                                            'msg' => lang('your_withdrawal_request_is_successful'),
                                            "csrfTokenName" => $csrfTokenName,
                                            "csrfHash" => $csrfHash
                                        );
                        
                                        echo json_encode($res);
                                    } else {
                                        //$this->session->set_flashdata('error', lang('your_withdrawal_request_is_successful'));
                                        $res = array(
                                            'success' => true,
                                            'msg' => lang('your_withdrawal_request_is_successful'),
                                            "csrfTokenName" => $csrfTokenName,
                                            "csrfHash" => $csrfHash
                                        );
                        
                                        echo json_encode($res);
                                    }
    
                                    //Send SMS
                                    $phone = $result3->mobile;
                                    if($phone){
                                        $body = strtr($rowUserMailContent->sms_body, $splVars);
    
                                        $this->twilio_model->send_sms($phone, $body);
                                    }
                                }
                            } else {
                                //$this->session->set_flashdata('success', lang('your_withdrawal_request_has_been_received'));
                                $res = array(
                                    'success' => true,
                                    'msg' => lang('your_withdrawal_request_has_been_received'),
                                    "csrfTokenName" => $csrfTokenName,
                                    "csrfHash" => $csrfHash
                                );
                
                                echo json_encode($res);
                            }
                        }
                        else
                        {
                            //$this->session->set_flashdata('error', lang('there_is_an_error_in_processing_your_withdrawal_please_try_again_later'));
                            $res = array(
                                'success' => false,
                                'msg' => lang('there_is_an_error_in_processing_your_withdrawal_please_try_again_later'),
                                "csrfTokenName" => $csrfTokenName,
                                "csrfHash" => $csrfHash
                            );
            
                            echo json_encode($res);
                        }
                    }
                }
            }
        } else {
            $this->loadThis();
        }
        
    }

    function withdrawalInfo($id, $ref, $amount)
    {
        if ($this->role == ROLE_CLIENT) {
            //Check the minimum and maximum limits of this withdrawal method
            $methodInfo = $this->payments_model->getInfoById('tbl_payment_methods', $id);

            $earnings = $this->transactions_model->getEarningsTotal($this->vendorId);
            $withdrawals = $this->transactions_model->getWithdrawalsTotal($this->vendorId);
            $pendingWithdrawals = $this->transactions_model->getPendingWithdrawalsTotal($this->vendorId);
            $withdrawable_funds = abs($earnings - $withdrawals - $pendingWithdrawals);

            if($withdrawable_funds >= $amount){
                $result = $this->user_model->getUserInfo($this->vendorId);
            
                $variable_comm = ($methodInfo->variableComm/100) * $amount;
                $fixed_comm = $methodInfo->fixedComm;
                $transFee = $methodInfo->transFee;

                $cumulative_transfee = $variable_comm + $fixed_comm + $transFee;
                $final_amount = $amount - $cumulative_transfee;

                if($result)
                {
                    /* Seperate withdrawal transactions into three
                     * 1. Bank withdrawals
                     * 2. Visa/Card withdrawals
                     * 3. Other methods (Paypal, Payeer, manual methods etc)
                     * */
                    if($methodInfo->API == 0)
                    {
                        if($methodInfo->ref == 'BT')
                        {
                            $method = '1';
                        } else {
                            $method = '3';
                        }
                    } else if($methodInfo->API == 1) {
                        $method = '2';
                    } else {
                        $method = '3';
                    }

                    $array = array(
                        'success'=>true,
                        'transaction_fee'=>to_currency($cumulative_transfee),
                        'final_amount'=>to_currency($final_amount),
                        'account'=>$result->pmtAccount,
                        'type'=>$result->pmtType,
                        'method'=>$method
                    );
            
                    echo json_encode($array);
                } else
                {
                    $array = array(
                        'success'=>false
                    );
        
                    echo json_encode($array);
                }
            } else
            {
                $array = array(
                    'success'=>false,
                    'msg'=>'You don\'t have enough funds to withdraw '.to_currency($amount).'<br>'.'The balance in your account is '.to_currency($withdrawable_funds)
                );

                echo json_encode($array);
            }
        } else {
            $this->loadThis();
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
}