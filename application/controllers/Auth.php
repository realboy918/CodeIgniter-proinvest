<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
/**
 * Class : Auth (AuthController)
 * Auth class to control the registration amd authentication of users and start their session.
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Auth extends BaseController
{
    /**
     * Auth constructor
     */
    public function __construct()
    {
        parent::__construct();

        //Site Data
        $this->companyInfo();

        //Models
        $this->load->model('login_model');
        $this->load->model('settings_model');
        $this->load->model('email_model');
        $this->load->model('twilio_model');
        $this->load->model('addons_model');


        $site_lang = $this->session->userdata('site_lang') == '' ?  "english" : $this->session->userdata('site_lang');

		$this->load->helper('language');
        $this->lang->load('common',$site_lang);
        $this->lang->load('registration',$site_lang);
        $this->lang->load('login',$site_lang);
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->isLoggedIn();
    }
    
    /**
     * This function used to check if the user is logged in or not
     */
    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->loadViews('/auth/login', $this->global);
        }
        else
        {
            redirect('/dashboard');
        }
    }

    public function signup()
    {
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();

        //Referral Code 
        $refcode = $this->uri->segment(2);

        //Recaptcha 
        //$recaptchaInfo = $this->addons_model->get_addon_info('Google Recaptcha');
        //$this->global['recaptchaInfo'] = $recaptchaInfo;

        //Page Data
        $this->global['pageTitle']  = 'Signup Page';
        $data['companyName']        = $this->settings_model->getsettingsInfo()['name'];
        $data["code"]               = $refcode;

        //Helpers
        $this->load->helper('url');
        $this->load->helper('security');

        //Validation
        $this->load->library('form_validation');   
        $this->form_validation->set_rules('firstname','First Name','trim|required', array(
            'required' => lang('this_field_is_required')
        ));
        $this->form_validation->set_rules('lastname','Last Name','trim|required', array(
            'required' => lang('this_field_is_required')
        ));
        $this->form_validation->set_rules('email','Email','trim|required|valid_email', array(
            'required' => lang('this_field_is_required'),
            'valid_email' => lang('this_email_is_invalid')
        ));
        $this->form_validation->set_rules('password','Password','required', array(
            'required' => lang('this_field_is_required')
        ));
        $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]', array(
            'required' => lang('this_field_is_required'),
            'matches' => lang('passwords_dont_match')
        ));
        $this->form_validation->set_rules('accept_terms','Terms and Conditions','required', array(
            'required' => lang('this_field_is_required')
        ));
        
        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('errors', validation_errors());

            //Ajax Request
            if ($this->input->is_ajax_request()) {
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }

                $response['errors'] = array_filter($errors); // Some might be empty
                $response["csrfTokenName"] = $csrfTokenName;
                $response["csrfHash"] = $csrfHash;
                $response['success'] = false;
                if (!isset($_POST['accept_terms'])){
                    $response['terms'] = lang('please_read_and_accept_our_terms_and_conditions');
                }
                    $response['msg'] = lang('please_correct_errors_and_try_again');

                echo json_encode($response); 
            }
        }
        else
        {
            $fname = strtolower($this->input->post('firstname', TRUE));
            $lname = strtolower($this->input->post('lastname', TRUE));
            $email = strtolower($this->input->post('email', TRUE));
            $password = $this->input->post('password', TRUE);
            $ref = $this->input->post('ref', TRUE);
            $roleId = '3';
            $dateCreated = date('Y-m-d H:i:s');
            $result = $this->login_model->checkEmailExist($email); 

            if($result>0){
                $this->session->set_flashdata('error', 'Email is in use');
                if ($this->input->is_ajax_request()) {
                    $array = array(
                        'errors' => array(
                            'email' => html_escape(lang('this_email_is_in_use'))
                        ),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash,
                        'success' => false
                    );
    
                    echo json_encode($array);
                }

            } else {
                
                $this->load->helper('string');
                $code = random_string('alnum',8);
                $userInfo = array(
                    'email'=>$email, 
                    'password'=>getHashedPassword($password), 
                    'roleId'=>$roleId, 
                    'firstName'=> $fname,
                    'lastName'=> $lname, 
                    'refCode' => $code,
                    'createdDtm'=> $dateCreated
                );
                $this->load->model('user_model');
                $result1 = $this->user_model->addNewUser($userInfo);
                
                if($result1>0)
                {
                    //Send Mail
				    $conditionUserMail = array('tbl_email_templates.type'=>'registration');
				    $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                    $firstname = $this->input->post('firstname', TRUE);
                    $companyInfo = $this->settings_model->getsettingsInfo();
                    $companyname = $companyInfo['name'];  
				
				    if($resultEmail->num_rows() > 0)
				    {
					    $rowUserMailContent = $resultEmail->row();
					    $splVars = array(
                            "!site_name"   => $this->config->item('site_title'),
                            "!clientName"  => $firstname,
                            "!clientEmail" => $email,
                            "!companyName" => $companyname,
                            "!address"     => $companyInfo['address'],
                            "!siteurl"     => base_url()
                        );

					$mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
					$mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

					$toEmail = $this->input->post('email',TRUE);
					$fromEmail = $companyInfo['SMTPUser'];

					$name = 'Support';

					$header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

					$this->sendEmail($toEmail,$mailSubject,$mailContent);

				    //print_r($mailContent);
				    }

                    $result2 = $this->login_model->loginMe($email, $password);
                    if($ref) 
                    {
                        $isrefcode = $this->user_model->getReferralId($ref);
                        if($isrefcode)
                        {
                            $referrer = $isrefcode->userId;
                            $referred = $result2->userId;
                            $created = date('Y-m-d H:i:s');
                            $referralInfo = array(
                                'referrerId' => $referrer,
                                'referredId' => $referred,
                                'createdDtm' => $created
                            );
                            $this->user_model->addReferral($referralInfo);
                        }
                    } 
                    //$lastLogin = $this->login_model->lastLoginInfo($result2->userId);
                    $sessionArray = array('userId'=>$result2->userId,                    
                                        'role'=>$result2->roleId,
                                        'roleText'=>$result2->role,
                                        'firstName'=>$result2->firstName,
                                        'lastName'=>$result2->lastName,
                                        'isLoggedIn' => TRUE
                                );

                    $this->session->set_userdata($sessionArray);
                    unset($sessionArray['userId'], $sessionArray['isLoggedIn']);
                    $loginInfo = array("userId"=>$result2->userId, "sessionData" => json_encode($sessionArray), "machineIp"=>$_SERVER['REMOTE_ADDR'], "userAgent"=>getBrowserAgent(), "agentString"=>$this->agent->agent_string(), "platform"=>$this->agent->platform());
                    $this->login_model->lastLogin($loginInfo);
                    if (!$this->input->is_ajax_request()) {
                    redirect('/dashboard');
                    } else
                    {
                        $array = array(
                            'success' => true,
                            'msg' => html_escape(lang('signup_successful')),
                            'url' => base_url().'dashboard',
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash,
                        );
        
                        echo json_encode($array);
                    }
                } else {
                    $this->session->set_flashdata('error', lang('signup_failed_try_again'));
                    if ($this->input->is_ajax_request()) {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('signup_failed_try_again')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash,
                    );
    
                    echo json_encode($array);
                    }
                }
            }            
        }
        if (!$this->input->is_ajax_request()) {
        $this->loadViews('/auth/register', $this->global, $data);
        }
    }

    public function loginView()
    {
        $this->global['pageTitle'] = 'Login';
        $this->global['recaptchaInfo'] = $this->addons_model->get_addon_info('Google Recaptcha');
        $this->global['companyInfo'] = $this->settings_model->getsettingsInfo();
        $this->index();
    }    
    
    public function checkPass()
    {
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();
        $companyInfo = $this->settings_model->getsettingsInfo();
        $recaptchaInfo = $this->addons_model->get_addon_info('Google Recaptcha');

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email', array(
            'required' => lang('this_field_is_required'),
            'valid_email' => lang('this_email_is_invalid')
        ));
        $this->form_validation->set_rules('password', 'Password', 'required', array(
            'required' => lang('this_field_is_required')
        ));

        if($companyInfo['google_recaptcha'] != 0){
            if($companyInfo['recaptcha_version'] == 'v2'){
                $this->form_validation->set_rules('g-recaptcha-response','Captcha','callback__recaptcha');
            } else if($companyInfo['recaptcha_version'] == 'v3') {
                $this->form_validation->set_rules('recaptcha_response','Captcha','callback__recaptcha');
            }
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
            $response['v'] = $companyInfo['recaptcha_version'];
            $response['key'] = $recaptchaInfo->public_key;
            $response["csrfTokenName"] = $csrfTokenName;
            $response["csrfHash"] = $csrfHash;
            $response['msg'] = html_escape(lang('please_correct_errors_and_try_again'));

            echo json_encode($response);
        }
        else
        {
            $email = strtolower($this->input->post('email', TRUE));
            $password = $this->input->post('password', TRUE);
            
            $result = $this->login_model->loginMe($email, $password);
            
            if(!empty($result))
            {
                //Check is the user is active
                if($result->isActive == 1){
                    $this->session->set_flashdata('error', lang('account_deactivated_contact_support'));
                    redirect('/login');
                    $array = array(
                        'success'=>false,
                        'v'=>$companyInfo['recaptcha_version'],
                        'key'=>$recaptchaInfo->public_key,
                        'msg'=>lang('account_deactivated_contact_support')
                    );

                    echo json_encode($array);
                } else
                {
                    $active_authenticator = $result->two_factor_auth;
                    $twfa = $companyInfo['two_factor_auth_active']; 

                    if($companyInfo['two_factor_auth'] == 'Authy'){
                        $msg = lang('please_input_the_2FA_code_from_the_authy_app');
                    } else if($companyInfo['two_factor_auth'] == 'Google Authenticator') {
                        $msg = lang('please_input_the_2FA_code_from_the_google_authenticator_app');
                    }
                    if($twfa == 1 && $active_authenticator != 0)
                    {
                        $array = array(
                            'success'=>true,
                            'twfa'=>true,
                            'msg'=>$msg
                        );
    
                        echo json_encode($array);
                    } else 
                    {
                        $sessionArray = array(
                            'userId'=>$result->userId,                    
                            'role'=>$result->roleId,
                            'roleText'=>$result->role,
                            'firstName'=>$result->firstName,
                            'lastName'=>$result->lastName,
                            'ppic'=>$result->ppic,
                            'lastLogin'=> date('Y-m-d H:i:s'),
                            'isLoggedIn' => TRUE
                        );

                        $this->session->set_userdata($sessionArray);
                        unset($sessionArray['userId'], $sessionArray['isLoggedIn'], $sessionArray['lastLogin']);
                        $loginInfo = array("userId"=>$result->userId, "sessionData" => json_encode($sessionArray), "machineIp"=>$_SERVER['REMOTE_ADDR'], "userAgent"=>getBrowserAgent(), "agentString"=>$this->agent->agent_string(), "platform"=>$this->agent->platform());
                        $this->login_model->lastLogin($loginInfo);

                        $array = array(
                            'success'=>true,
                            'twfa'=>false,
                            'url'=>base_url('dashboard'),
                            'msg'=>lang('success')
                        );
    
                        echo json_encode($array);
                    }
                }
            } else {
                $array = array(
                    'success'=>false,
                    'type'=>'pass',
                    'v'=>$companyInfo['recaptcha_version'],
                    'key'=>$recaptchaInfo->public_key,
                    'msg'=>lang('incorrect_login_credentials')
                );

                echo json_encode($array);
            }
        }
    }
    
    /**
     * This function used to logged in the client
     */
    public function loginMe()
    {
        $companyInfo = $this->settings_model->getsettingsInfo();
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();
        
        $this->global['pageTitle'] = 'Login';

        $this->load->library('GoogleAuthenticator');
        $this->load->library('Authy');
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email', array(
            'required' => lang('this_field_is_required'),
            'valid_email' => lang('this_email_is_invalid')
        ));
        $this->form_validation->set_rules('password', 'Password', 'required', array(
            'required' => lang('this_field_is_required')
        ));
        if($companyInfo['two_factor_auth'] == 'Google Authenticator'){
            $this->form_validation->set_rules('token', 'Two-factor token', 'min_length[6]|max_length[6]|required', array(
                'required' => lang('this_field_is_required'),
                'min_length' => lang('minimum_length_is').' 6',
                'max_length' => lang('maximum_length_is').' 6'
            ));
        } else if($companyInfo['two_factor_auth'] == 'Authy'){
            $this->form_validation->set_rules('token', 'Two-factor token', 'min_length[7]|max_length[7]|required', array(
                'required' => lang('this_field_is_required'),
                'min_length' => lang('minimum_length_is').' 7',
                'max_length' => lang('maximum_length_is').' 7'
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
            $response['msg'] = lang('please_correct_errors_and_try_again');

            echo json_encode($response);
        }
        else
        {   
            // 2 factor authentication codes...................................
            $token = $this->input->post('token');
            $email = strtolower($this->input->post('email', TRUE));
            $password = $this->input->post('password', TRUE);

            if($companyInfo['two_factor_auth'] == 'Google Authenticator')
            {
                
                $gaobj = new GoogleAuthenticator();
                $secret = "QFDK6TURKQMBAD2L" ; //$gaobj->createSecret();       
                        
                $checkResult = $gaobj->verifyCode($secret, $token, 2); // 2 = 2*30sec clock tolerance
            } else if($companyInfo['two_factor_auth'] == 'Authy')
            {
                $id = $this->login_model->loginMe($email, $password)->two_factor_auth;
                $api_key = $this->addons_model->get_addon_info('Authy')->public_key;
                    
                $checkResult = $this->authy->verify_token($id,$token,$api_key);
            }

            if (!$checkResult)
            {    
                $array = array(
                    'success'=>false,
                    'errmsg'=>lang('failed')
                );

                echo json_encode($array);     
            }
            else
            {   
                $result = $this->login_model->loginMe($email, $password);
                
                if(!empty($result))
                {
                    //Check is the user is active
                    if($result->isActive == 1){
                        $array = array(
                            'success'=>false,
                            'errmsg'=>lang('account_deactivated_contact_support')
                        );
        
                        echo json_encode($array);     
                    } else
                    {
                        //$lastLogin = $this->login_model->lastLoginInfo($result->userId);
                        $sessionArray = array('userId'=>$result->userId,                    
                                                'role'=>$result->roleId,
                                                'roleText'=>$result->role,
                                                'firstName'=>$result->firstName,
                                                'lastName'=>$result->lastName,
                                                'ppic'=>$result->ppic,
                                                'lastLogin'=> date('Y-m-d H:i:s'),
                                                'isLoggedIn' => TRUE
                                        );

                        $this->session->set_userdata($sessionArray);
                        unset($sessionArray['userId'], $sessionArray['isLoggedIn'], $sessionArray['lastLogin']);
                        $loginInfo = array("userId"=>$result->userId, "sessionData" => json_encode($sessionArray), "machineIp"=>$_SERVER['REMOTE_ADDR'], "userAgent"=>getBrowserAgent(), "agentString"=>$this->agent->agent_string(), "platform"=>$this->agent->platform());
                        $this->login_model->lastLogin($loginInfo);
                        $array = array(
                            'success'=>true,
                            'url'=>base_url('dashboard'),
                            'msg'=>lang('success')
                        );
        
                        echo json_encode($array);
                    }
                }
                else
                {
                    $array = array(
                        'success'=>false,
                        'errmsg'=>lang('incorrect_login_credentials')
                    );
    
                    echo json_encode($array);
                }
            } 
        }
    }

    public function _recaptcha($str)
    {
        $companyInfo = $this->settings_model->getsettingsInfo();
        $recaptchaInfo = $this->addons_model->get_addon_info('Google Recaptcha');
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = $recaptchaInfo->secret_key;
        $recaptcha_response = $str;

        if($companyInfo['recaptcha_version'] == 'v2'){
            $ip=$_SERVER['REMOTE_ADDR'];
            $url=$recaptcha_url."?secret=".$recaptcha_secret."&response=".$recaptcha_response."&remoteip=".$ip;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
            $res = curl_exec($curl);
            curl_close($curl);
            $res= json_decode($res, true);
            //reCaptcha success check
            if($res['success'])
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('_recaptcha', lang('recaptcha_error_please_refresh_page_and_try_again'));
                return FALSE;
            }
        } else if($companyInfo['recaptcha_version'] == 'v3'){
            // Make and decode POST request:
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            $res = json_decode($recaptcha);

            //print_r($res);
            if($res->success == 1)
            {
                // Take action based on the score returned:
                if ($res->score >= 0.5) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('_recaptcha', lang('recaptcha_error_please_refresh_page_and_try_again'));
                    return FALSE;
                }
            } else {
                $this->form_validation->set_message('_recaptcha', lang('recaptcha_error_please_refresh_page_and_try_again'));
                return FALSE;
            }
        }
    }

    /**
     * This function used to load forgot password view
     */
    public function forgotPassword()
    {
        $this->global['pageTitle'] = 'Forgot Password';
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->loadViews('/auth/forgotPassword', $this->global, NULL, NULL);
        }
        else
        {
            redirect('/dashboard');
        }
    }
    
    /**
     * This function used to generate reset password request link
     */
    function resetPasswordUser()
    {
        $status = '';
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('login_email','Email','required|valid_email', array(
            'required' => lang('this_field_is_required'),
            'valid_email' => lang('this_email_is_invalid')
        ));
                
        if($this->form_validation->run() == FALSE)
        {
            $this->forgotPassword();
        }
        else 
        {
            $email = strtolower($this->input->post('login_email', TRUE));
            
            if($this->login_model->checkEmailExist($email))
            {  
                $this->load->helper('string');

                $data = array(
                    'email' => $email,
                    'activation_id' => random_string('alnum',15),
                    'createdDtm' => date('Y-m-d H:i:s'),
                    'agent' => getBrowserAgent(),
                    'client_ip' => $this->input->ip_address()
                );
                
                $save = $this->login_model->resetPasswordUser($data);                
                
                if($save)
                {
                    $reset_link = base_url() . "resetPassword/" . $data['activation_id'];
                    $userInfo = $this->login_model->getCustomerInfoByEmail($email);

                    if(!empty($userInfo)){
                        $data1["name"] = $userInfo->firstName.'&nbsp'.$userInfo->lastName;
                        $data1["email"] = $userInfo->email;
                        $data1["message"] = "Reset Your Password";
                    }

                    //Send Mail and SMS
				    $conditionUserMail = array('tbl_email_templates.type'=>'Forgot Password');
				    $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                    $companyInfo = $this->settings_model->getsettingsInfo();
				
				    if($resultEmail->num_rows() > 0)
				    {
					    $rowUserMailContent = $resultEmail->row();
					    $splVars = array(
                            "!clientName"  => $userInfo->firstName,
                            "!companyName" => $companyInfo['name'],
                            "!address"     => $companyInfo['address'],
                            "!siteurl"     => base_url(),
                            "!resetLink"   => $reset_link
                        );

					$mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
					$mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

					$toEmail   = $userInfo->email;
					$fromEmail = $companyInfo['SMTPUser'];

					$name = 'Support';

					$header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                    $send = $this->sendEmail($toEmail,$mailSubject,$mailContent);

				    //$sendStatus = resetPasswordEmail($data1);
                    if($send == true) {
                        setFlashData('success', lang('reset_password_link_sent_successfully_check_email'));
                    } else {
                        setFlashData('error', lang('email_sending_has_failed_try_again'));
                    }

                    //Send SMS
                    $phone = $userInfo->mobile;
                    if($phone){
                        $body = strtr($rowUserMailContent->sms_body, $splVars);

                        $result = $this->twilio_model->send_sms($phone, $body);
                    }

                    }
                }
                else
                {
                    setFlashData('error', lang('error'));
                }
            }
            else
            {
                setFlashData('error', lang('this_email_is_not_registered_with_us'));
            }
            if ($this->input->post('redirect', TRUE) == 1) {
                redirect('profile');
            } else {
                redirect('forgotPassword');
            }
            
        }
    }

    /**
     * This function used to reset the password 
     * @param string $activation_id : This is unique id
     * @param string $email : This is user email
     */
    function resetPasswordConfirmUser($activation_id)
    {        
        $this->global['pageTitle'] = 'Reset Password';
        // Check activation id in database
        $activationInfo = $this->login_model->checkActivationDetails($activation_id);

        if ($activationInfo->num_rows() > 0) {
            $email = $activationInfo->row()->email;
            $userInfo = $this->login_model->getCustomerInfoByEmail($email);
            $data['email'] = $email;
            $data['name'] = $userInfo->firstName;
            $data['activation_code'] = $activation_id;
            $this->loadViews('/auth/newPassword', $this->global, $data);

            $this->load->library('form_validation');
        
            $this->form_validation->set_rules('password','Password','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('cpassword','Confirm Password','required|matches[password]', array(
                'required' => lang('this_field_is_required'),
                'matches' => lang('passwords_dont_match')
            ));
            
            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('error', validation_errors());
            }
            else
            {
                $password = $this->input->post('password', TRUE);
                $cpassword = $this->input->post('cpassword', TRUE);
                
                // Check activation id in database
                $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
                
                if($is_correct == 1)
                {                
                    $this->login_model->createPasswordUser($email, $password);
                    $this->session->set_flashdata('success', lang('password_reset_successful'));
                    redirect("/login");
                }
                else
                {
                    $this->session->set_flashdata('error', lang('password_reset_failed'));
                    redirect('/resetPassword'.$activation_id);
                }            
            }
        }
        else
        {
            redirect('/login');
        }
    }
    
    /**
     * This function used to create new password for user
     */
    function createPasswordUser()
    {
        $status = '';
        $message = '';
        $email = strtolower($this->input->post("email", TRUE));
        $activation_id = $this->input->post("activation_code", TRUE);
        $encoded_email = urlencode($email);
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('password','Password','required', array(
            'required' => lang('this_field_is_required')
        ));
        $this->form_validation->set_rules('cpassword','Confirm Password','required|matches[password]', array(
            'required' => lang('this_field_is_required'),
            'matches' => lang('passwords_dont_match')
        ));
        
        if($this->form_validation->run() == FALSE)
        {
            //$this->resetPasswordConfirmUser($activation_id, urlencode($email));
            setFlashData('error', validation_errors());
            redirect('/resetPassword'.'/'.$activation_id);
        }
        else
        {
            $password = $this->input->post('password', TRUE);
            $cpassword = $this->input->post('cpassword', TRUE);
            
            // Check activation id in database
            $is_correct = $this->login_model->checkActivationDetails($activation_id);
            
            if($is_correct->num_rows() == 1)
            {                
                $this->login_model->createPasswordUser($email, $password);
                $this->session->set_flashdata('success', lang('password_reset_successful'));
                redirect("/login");
            }
            else
            {
                $this->session->set_flashdata('error', lang('password_reset_failed'));
                redirect('/resetPassword'.$activation_id);
            }            
        }
    }
}

?>