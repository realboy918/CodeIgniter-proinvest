<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Settings (SettingsController)
 * Settings Class
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Settings extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
        $this->load->model('user_model');
        $this->load->model('login_model');
        $this->load->model('email_model');
        $this->load->model('payments_model');
        $this->load->model('addons_model');
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
        $this->lang->load('plugins',$userLang);
        $this->lang->load('validation',$userLang);
        $this->lang->load('tickets',$userLang);
    }

    function settings()
    {
        $module_id = 'settings';
        $module_action = 'general_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
        $this->global['pageTitle'] = 'Settings';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = lang('settings').' <span class="breadcrumb-arrow-right"></span> '.lang('general');
        $data["companyInfo"] = $this->settings_model->getSettingsInfo();
        $data['periods'] = $this->payments_model->getAllPeriods();
        $this->loadViews("settings/settings", $this->global, $data, NULL);
        }
    }

    function email_templates(){
        $module_id = 'settings';
        $module_action = 'email_templates';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {        
            $this->global['pageTitle'] = 'Email Templates';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = lang('settings').' <span class="breadcrumb-arrow-right"></span> '.lang('emails');
            
            $searchText = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            $count = $this->email_model->emailListingCount($searchText);
			$returns = $this->paginationCompress ( "settings/email_templates/", $count, 13 );
            
            $data['emailTemplates'] = $this->email_model->emails($searchText, $returns["page"], $returns["segment"]);

            //Let us load the first email
            $data['emailID'] = $this->email_model->firstEmailRow()->id;
            $data['emailSubject'] = $this->email_model->firstEmailRow()->mail_subject;
            $data['emailBody'] = html_purify($this->email_model->firstEmailRow()->mail_body);
            
            $this->loadViews("settings/emailTemplates", $this->global, $data, NULL);
        }

    }

    function editEmailTemplate() {
        $module_id = 'settings';
        $module_action = 'email_templates';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email_subject','Email Subject','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('email_body','Email Body','required', array(
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
                $subject = $this->input->post('email_subject', TRUE);
                $body    = html_purify($this->input->post('email_body'));
                $emailId = $this->input->post('email_id', TRUE);

                $emailInfo = array(
                    'mail_subject'=>$subject, 
                    'mail_body'=>$body, 
                    'modifiedBy'=>$this->vendorId, 
                    'modifiedDtm'=>date('Y-m-d H:i:s')
                );

                $result = $this->email_model->updateEmailSettings($emailInfo, $emailId);

                if($result == true)
                {
                    $array = array(
                        'success' => true,
                        'msg' => html_escape(lang('successfully_edited_email_template')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('failed_to_edited_email_template')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
            }
        }
    }

    function companyInfoUpdate()
    {
        $this->global['pageTitle'] = 'Settings';
        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
        $module_id = 'settings';
        $module_action = 'general_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();

        $data["companyInfo"] = $this->settings_model->getsettingsInfo();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('companyName','Company Name','required', array(
            'required' => lang('this_field_is_required')
        ));
        $this->form_validation->set_rules('email','Company Email','required', array(
            'required' => lang('this_field_is_required')
        ));
        $this->form_validation->set_rules('currency','Currency','required', array(
            'required' => lang('this_field_is_required')
        ));
        $this->form_validation->set_rules('position','Currency Position','required', array(
            'required' => lang('this_field_is_required')
        ));
        if($this->input->post('currency', TRUE) !== 'USD'){
            $this->form_validation->set_rules('excurrency','Exchange Rate','required', array(
                'required' => lang('this_field_is_required')
            ));
        }
        $this->form_validation->set_rules('password','Password','required', array(
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
            $companyName = $this->input->post('companyName', TRUE);
            $phone1 = $this->input->post('phone1', TRUE);
            $phone2 = $this->input->post('phone2', TRUE);
            $email = $this->input->post('email', TRUE);
            $address = $this->input->post('address', TRUE);
            $currency = $this->input->post('currency', TRUE);
            $cu_position = $this->input->post('position', TRUE);
            $currency_ex = $this->input->post('excurrency', TRUE);
            $url = $this->input->post('url', TRUE);
            $password = $this->input->post('password', TRUE);
            $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;
            $minwithdrawal = $this->input->post('minwithdrawal', TRUE);

            $result1 = $this->login_model->loginMe($useremail, $password);
            if(!empty($result1))
            {
                //Upload the logos First
                if(isset($_FILES["white-logo"]["name"])){
                    if ($this->security->xss_clean($this->input->post('white-logo'), TRUE) === TRUE)
                    {
                        $config["upload_path"] = './uploads';
                        $config['allowed_types'] = 'jpg|png';
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('white-logo')){
                            $data = ($this->upload->data());
                            $nameLogoWhite = $data["file_name"];
                            $white_logourl = '<img class="logo-showcase-white" src="'.base_url().'uploads/'.$data["file_name"].'">';
                        }else{
                            $errors = $this->upload->display_errors();
                            $nameLogoWhite = $this->settings_model->getSettingsInfo()['whiteLogo'];
                            $white_logourl = '';
                        }; 
                    }
                } 

                if(isset($_FILES["dark-logo"]["name"])){
                    if ($this->security->xss_clean($this->input->post('white-logo'), TRUE) === TRUE)
                    {
                        $config["upload_path"] = './uploads';
                        $config['allowed_types'] = 'jpg|png';
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('dark-logo')){
                            $data = ($this->upload->data());
                            $nameLogoDark = $data["file_name"];
                            $dark_logourl = '<img class="logo-showcase-dark" src="'.base_url().'uploads/'.$data["file_name"].'">';
                        }else{
                            $errors = $this->upload->display_errors();
                            $nameLogoDark = $this->settings_model->getSettingsInfo()['darkLogo'];
                            $dark_logourl = '';
                        }; 
                    }
                }

                if(isset($_FILES["favicon-logo"]["name"])){
                    if ($this->security->xss_clean($this->input->post('favicon-logo'), TRUE) === TRUE)
                    {
                        $config["upload_path"] = './uploads';
                        $config['allowed_types'] = 'jpg|png';
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('favicon-logo')){
                            $data = ($this->upload->data());
                            $nameFavicon = $data["file_name"];
                            $favicon_url = '<img class="logo-favicon" src="'.base_url().'uploads/'.$data["file_name"].'">';
                        }else{
                            $errors = $this->upload->display_errors();
                            $nameFavicon = $this->settings_model->getSettingsInfo()['favicon'];
                            $favicon_url = '';
                        }; 
                    }
                }

                 $companyInfo = array(
                    array(
                        'type' => 'name',
                        'value' => $companyName
                    ),
                    array(
                        'type' => 'phone1',
                        'value' => $phone1
                    ),
                    array(
                        'type' => 'phone2',
                        'value' => $phone2
                    ),
                    array(
                        'type' => 'email',
                        'value' => $email
                    ),
                    array(
                        'type' => 'address',
                        'value' => $address
                    ),
                    array(
                        'type' => 'url',
                        'value' => $url
                    ),
                    array(
                        'type' => 'whiteLogo',
                        'value' => $nameLogoWhite
                    ),
                    array(
                        'type' => 'darkLogo',
                        'value' => $nameLogoDark
                    ),
                    array(
                        'type' => 'favicon',
                        'value' => $nameFavicon
                    ),
                    array(
                        'type' => 'currency',
                        'value' => $currency
                    ),
                    array(
                        'type' => 'currency_position',
                        'value' => $cu_position
                    ),
                    array(
                        'type' => 'currency_exchange_rate',
                        'value' => $currency_ex
                    ),
                    array(
                        'type' => 'min_withdrawal',
                        'value' => $minwithdrawal
                    )
                );
                
               $this->db->update_batch('tbl_settings', $companyInfo, 'type');

                 $array = array(
                    'success' => true,
                    'msg' => html_escape(lang('successfully_updated_your_info')),
                    'whiteLogo' => $white_logourl,
                    'darkLogo' => $dark_logourl,
                    'favicon' => $favicon_url,
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);

            } else {
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

    function SEO_Update()
    {
        $module_id = 'settings';
        $module_action = 'general_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('title','Page Title','required', array(
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
            $title = $this->input->post('title', TRUE);
            $description = $this->input->post('description', TRUE);
            $keywords = $this->input->post('keywords', TRUE);
            $recaptcha = $this->input->post('actrecaptcha', TRUE) == null ? 0 : 1;
            $twfa = $this->input->post('auth', TRUE);
            $twfa_active = $this->input->post('acttfa', TRUE) == null ? 0 : 1;
            $chatplugin = $this->input->post('chatplugin', TRUE);
            $chat_active = $this->input->post('actchatplugin', TRUE) == null ? 0 : 1;

            $password = $this->input->post('password', TRUE);
            $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

            $result1 = $this->login_model->loginMe($useremail, $password);
            if(!empty($result1))
            {
                $companyInfo = array(
                    array(
                        'type' => 'title',
                        'value' => $title
                    ),
                    array(
                        'type' => 'description',
                        'value' => $description
                    ),
                    array(
                        'type' => 'keywords',
                        'value' => $keywords
                    ),
                    array(
                        'type' => 'chat_plugin',
                        'value' => $chatplugin
                    ),
                    array(
                        'type' => 'chat_plugin_active',
                        'value' => $chat_active
                    ),
                    array(
                        'type' => 'two_factor_auth',
                        'value' => $twfa
                    ),
                    array(
                        'type' => 'two_factor_auth_active',
                        'value' => $twfa_active
                    ),
                    array(
                        'type' => 'google_recaptcha',
                        'value' => $recaptcha
                    )
                );
                
                $result = $this->db->update_batch('tbl_settings', $companyInfo, 'type');
            
                if($result == true)
                {
                    $array = array(
                        'success' => true,
                        'msg' => html_escape(lang('successfully_updated_your_info')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('there_is_nothing_to_update_please_check_and_try_again')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }

            } else {
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

    function paymentMethods()
    {
        $module_id = 'settings';
        $module_action = 'payment_methods';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $searchText = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $this->load->library('pagination');
            $count = $this->payments_model->listingCount('tbl_payment_methods', $searchText);
            $returns = $this->paginationCompress ( "settings/paymentMethods/", $count, 9, 3 );
            $data['paymentAPIs'] = $this->payments_model->getAllPaymentAPIs();
            $data['paymentMethods'] = $this->payments_model->getAll('tbl_payment_methods',$searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Payment Methods';
            $this->global['displayBreadcrumbs'] = false; 
            $this->loadViews("settings/payments", $this->global, $data, NULL);
        }
    }

    function addpaymentmethod()
    {
        $this->load->library('form_validation');
        $this->load->library('upload');

        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();

        $ptype = $this->input->post('ptype', TRUE);
        $api = $this->input->post('api', TRUE);

        if($ptype == 'bank'){
            $this->form_validation->set_rules('bankname','Bank Name','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('acname','Account Name','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('acnumber','Account Number','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('swiftcode','Swift Code','required', array(
                'required' => lang('this_field_is_required')
            ));

        }else if($ptype == 'manual'){
            $this->form_validation->set_rules('methodname','Method Name','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('note','Note','required', array(
                'required' => lang('this_field_is_required')
            ));
            if (empty($_FILES['img']['name'])) {
                $this->form_validation->set_rules('logo', 'Logo', 'required', array(
                    'required' => lang('this_field_is_required')
                ));
            }
        } else if($ptype == 'auto'){
            $this->form_validation->set_rules('api','API','required');
            $this->form_validation->set_rules('methodname','Method Name','required', array(
                'required' => lang('this_field_is_required')
            ));
            if (empty($_FILES['img']['name'])) {
                $this->form_validation->set_rules('logo', 'Logo', 'required', array(
                    'required' => lang('this_field_is_required')
                ));
            }
            
            if($api == 4)
            {
                $this->form_validation->set_rules('code','Code','required', array(
                    'required' => lang('this_field_is_required')
                ));
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
            $response["csrfTokenName"] = $csrfTokenName;
            $response["csrfHash"] = $csrfHash;
            $response['msg'] = html_escape(lang('please_correct_errors_and_try_again'));

            echo json_encode($response); 
        }
        else
        {
            $bankname = $this->input->post('bankname', TRUE);
            $acname = $this->input->post('acname', TRUE);
            $acnumber = $this->input->post('acnumber', TRUE);
            $swiftcode = $this->input->post('swiftcode', TRUE);
            $methodname = $this->input->post('methodname', TRUE);
            $note = $this->input->post('note', TRUE);
            $api = $this->input->post('api', TRUE);
            $code = $this->input->post('code', TRUE);
            $withdrawable = isset($_POST['isnewwithdrawable']) ? $this->input->post('isnewwithdrawable', TRUE) : 0 ;

            if(isset($_FILES["img"]["name"])){
                if ($this->security->xss_clean($this->input->post('img'), TRUE) === TRUE)
                {
                    $config["upload_path"] = './uploads';
                    $config['allowed_types'] = 'jpg|png';
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('img')){
                        $data = ($this->upload->data());
                        $nameLogo = $data["file_name"];
                    }else{
                        $errors = $this->upload->display_errors();
                        $nameLogo = '';
                    }; 
                }
            }

            if($ptype == 'bank')
            {
                $array = array( 
                    'name'=>'Bank Transfer',
                    'logo'=>'bank-transfer.png',
                    'type'=>'bank',
                    'bank_name'=>$bankname,
                    'account_name'=>$acname,
                    'account_number'=>$acnumber,
                    'swift_code'=>$swiftcode,
                    'ref'=>'BT',
                    'status'=>1,
                    'API'=>0,
                    'iswithdrawable'=>$withdrawable
                );

                $result = $this->payments_model->addPaymentMethod($array);
            } else if($ptype == 'manual')
            {
                $array = array( 
                    'name'=>$methodname,
                    'logo'=>$nameLogo,
                    'type'=>'manual',
                    'ref'=>'MN',
                    'note'=>$note,
                    'status'=>1,
                    'API'=>0,
                    'iswithdrawable'=>$withdrawable
                );

                $result = $this->payments_model->addPaymentMethod($array);
            } else if($ptype == 'auto')
            {
                $array = array( 
                    'name'=>$methodname,
                    'logo'=>$nameLogo,
                    'type'=>'auto',
                    'ref'=>$code,
                    'status'=>1,
                    'API'=>$api,
                    'iswithdrawable'=>$withdrawable
                );

                $result = $this->payments_model->addPaymentMethod($array);
            }

            if($result > 0)
            {
                //Success
                $res = array(
                    'success' => true,
                    'msg' => 'You have successfully added a new payment method',
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($res);
            } else
            {
                //Failed
                $res = array(
                    'success' => false,
                    'msg' => 'There was an issue in processing your request, please try again later.',
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($res);
            }

        }
        
    }

    function addons()
    {
        $module_id = 'settings';
        $module_action = 'API_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $searchText =$this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $this->load->library('pagination');
            $count = $this->payments_model->listingCount('tbl_addons_api', $searchText);
			$returns = $this->paginationCompress ( "settings/addons/", $count, 9, 3 );
            $data['paymentMethods'] = $this->payments_model->getAll('tbl_addons_api', $searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Add-ons Settings';
            $this->global['displayBreadcrumbs'] = false; 
            $this->loadViews("settings/addons", $this->global, $data, NULL);
        }
    }

    function paymentmethodInfo()
    {
        $module_id = 'settings';
        $module_action = 'payment_methods';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $method = $this->input->post('method', TRUE);
            $paymentMethod = $this->payments_model->getPaymentMethodInfoById($method);
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $array = array(
                'id' => $paymentMethod->id,
                'name' => $paymentMethod->name,
                'APIname'=> $paymentMethod->Aname,
                'logo' => base_url('uploads/'.$paymentMethod->logo),
                'type' => $paymentMethod->type,
                'ref' => $paymentMethod->ref,
                'API' => $paymentMethod->API,
                'bname' => $paymentMethod->bank_name,
                'acname' => $paymentMethod->account_name,
                'acnumber' => $paymentMethod->account_number,
                'swcode' => $paymentMethod->swift_code,
                'note' => $paymentMethod->note,
                'status' => $paymentMethod->status,
                'iswithdrawable'=>$paymentMethod->iswithdrawable,
                "csrfTokenName" => $csrfTokenName,
                "csrfHash" => $csrfHash
            );

            echo json_encode($array);
        }
    }

    function deletepaymentmethod($id)
    {
        $module_id = 'settings';
        $module_action = 'payment_methods';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $data = array(
                'id'=>$id
            );
            $result = $this->payments_model->deletePayMethod($data);

            if($result)
            {
                $array = array(
                    'success'=>true,
                    'msg'=>'Method deleted succesfully'
                );

                echo json_encode($array);
                //$this->session->set_flashdata('success', 'Payment methods updated successfully');

                //redirect('settings/paymentMethods');
            } else
            {
                $array = array(
                    'success'=>false,
                    'msg'=>'Failed to delete method'
                );

                echo json_encode($array);
                //$this->session->set_flashdata('error', 'Payment methods updated successfully');
                //redirect('settings/paymentMethods');
            }
        }
    }

    function addons_info()
    {
        $module_id = 'settings';
        $module_action = 'API_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $method = $this->input->post('method', TRUE);
            $paymentMethod = $this->payments_model->getInfo('tbl_addons_api', $method);

            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            
            $array = array(
                'id' => $paymentMethod->id,
                'name' => $paymentMethod->name,
                'logo' => base_url('assets/dist/img/'.$paymentMethod->logo),
                'publicKey' => $paymentMethod->public_key,
                'secretKey' => $paymentMethod->secret_key,
                'merchantName' => $paymentMethod->merchantName,
                'merchantID' => $paymentMethod->merchantID,
                'IPNKey' => $paymentMethod->IPN_secret,
                'IPNURL' => $paymentMethod->base_url,
                'status' => $paymentMethod->status,
                'env' => $paymentMethod->env,
                "csrfTokenName" => $csrfTokenName,
                "csrfHash" => $csrfHash
            );

            echo json_encode($array);
        }
    }

    function paymentMethodEdit()
    {
        $this->load->library('upload');

        $module_id = 'settings';
        $module_action = 'payment_methods';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            
            $this->load->library('form_validation');

            $ptype = $this->input->post('ptype', TRUE);
            $api = $this->input->post('api', TRUE);
            $id = $this->input->post('method', TRUE);

            if($ptype == 'bank'){
                $this->form_validation->set_rules('bname','Bank Name','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('acname','Account Name','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('acnumber','Account Number','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('swcode','Swift Code','required', array(
                    'required' => lang('this_field_is_required')
                ));
            }else if($ptype == 'manual'){
                $this->form_validation->set_rules('methodname','Method Name','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('note','Note','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($ptype == 'auto'){
                $this->form_validation->set_rules('api','API','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('methodname','Method Name','required', array(
                    'required' => lang('this_field_is_required')
                ));
                
                if($api == 4)
                {
                    $this->form_validation->set_rules('code','Code','required', array(
                        'required' => lang('this_field_is_required')
                    ));
                } 
            }
            $this->form_validation->set_rules('status','Status','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('iswithdrawable','Status','required', array(
                'required' => lang('this_field_is_required')
            ));
            
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
                //find out if the API is active
                $api_id = $this->input->post('api', TRUE); //API Id

                //Get API info
                $api_info = $this->payments_model->getAPIById($api);

                //Get method info
                $method_info = $this->payments_model->getPaymentMethodById($id);

                $method = $method_info->name;
                $status = $this->input->post('status', TRUE);
                $withdrawable = $this->input->post('iswithdrawable', TRUE);

                if(isset($_FILES["img"]["name"])){
                    if ($this->security->xss_clean($this->input->post('img'), TRUE) === TRUE)
                    {
                        $config["upload_path"] = './uploads';
                        $config['allowed_types'] = 'jpg|png';
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('img')){
                            $data = ($this->upload->data());
                            $nameLogo = $data["file_name"];
                        }else{
                            $errors = $this->upload->display_errors();
                            $nameLogo = $method_info->logo;
                        }; 
                    }
                }

                if($ptype == 'bank')
                {
                    $bank_name = $this->input->post('bname', TRUE);
                    $bank_account = $this->input->post('acname', TRUE);
                    $ac_number = $this->input->post('acnumber', TRUE);
                    $swift = $this->input->post('swcode', TRUE);
                    $paymentInfo = array(
                        'status'=>$status,
                        'bank_name'=>$bank_name,
                        'account_name'=>$bank_account,
                        'account_number'=>$ac_number,
                        'iswithdrawable'=>$withdrawable,
                        'swift_code'=>$swift,
                        'logo'=>$nameLogo
                    );

                    $result = $this->payments_model->editInfo('tbl_payment_methods', $paymentInfo, $id);

                    if($result == true)
                    {
                        $array = array(
                            'success' => true,
                            '_id'=> $id,
                            'status'=>$status,
                            'msg' => html_escape($method.' '.lang('method_has_been_updated')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                    else
                    {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape(lang('there_is_a_problem_in_updating_your_information')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                } else if($ptype == 'manual')
                {
                    $methodname = $this->input->post('methodname', TRUE);
                    $note = $this->input->post('note', TRUE);

                    $paymentInfo = array(
                        'name' => $methodname,
                        'note' => $note,
                        'status'=>$status,
                        'iswithdrawable'=>$withdrawable,
                        'logo'=>$nameLogo
                    );

                    $result = $this->payments_model->editInfo('tbl_payment_methods', $paymentInfo, $id);

                    if($result == true)
                    {
                        $array = array(
                            'success' => true,
                            '_id'=> $id,
                            'status'=>$status,
                            'msg' => html_escape($method.' '.lang('method_has_been_updated')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                    else
                    {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape(lang('there_is_a_problem_in_updating_your_information')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                } else if($ptype == 'auto')
                {
                    if($api_info->status == 0)
                    {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape(lang('please_activate').' '.$api_info->name.' API'),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    } else 
                    {
                        $name = $this->input->post('methodname', TRUE);
                        $ref = $this->input->post('code', TRUE);
                        $API = $this->input->post('api', TRUE);

                        $paymentInfo = array(
                            'name' => $name,
                            'ref' => $ref,
                            'API' => $API,
                            'status'=>$status,
                            'iswithdrawable'=>$withdrawable,
                            'logo'=>$nameLogo
                        );

                        $result = $this->payments_model->editInfo('tbl_payment_methods', $paymentInfo, $id);

                        if($result == true)
                        {
                            $array = array(
                                'success' => true,
                                '_id'=> $id,
                                'status'=>$status,
                                'msg' => html_escape($method.' '.lang('method_has_been_updated')),
                                "csrfTokenName" => $csrfTokenName,
                                "csrfHash" => $csrfHash
                            );

                            echo json_encode($array);
                        }
                        else
                        {
                            $array = array(
                                'success' => false,
                                'msg' => html_escape(lang('there_is_a_problem_in_updating_your_information')),
                                "csrfTokenName" => $csrfTokenName,
                                "csrfHash" => $csrfHash
                            );

                            echo json_encode($array);
                        }
                    }
                }
            }
        }
    }

    function addons_update()
    {
        $module_id = 'settings';
        $module_action = 'API_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $method = $this->input->post('method', TRUE);
            $id = $this->payments_model->getInfo('tbl_addons_api', $method)->id;
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('status','Status','required', array(
                'required' => lang('this_field_is_required')
            ));
            if($method == 'CoinPayments'){
                $this->form_validation->set_rules('merchantID','Merchant ID','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('IPNKey','IPN Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('IPNURL','IPN Url','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('sKey','Secret Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('pKey','Public Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'PayPal'){
                $this->form_validation->set_rules('mode','Mode','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('sKey','Secret','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('pKey','Client ID','required', array(
                    'required' => lang('this_field_is_required')
                ));
            }else if($method == 'Monnify'){
                $this->form_validation->set_rules('contractcode','Contract Code','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('sKey','Secret Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('pKey','Public Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'Perfect Money'){
                $this->form_validation->set_rules('payee_account','Payee Account','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('payee_name','Payee Name','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'Google Recaptcha'){
                $this->form_validation->set_rules('pKey','Site Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('sKey','Secret Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('version','Version','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'BTCPay'){
                $this->form_validation->set_rules('merchantID1','Store ID','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('btcpayurl','url','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'Payeer'){
                $this->form_validation->set_rules('merchantID1','Merchant ID','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('sKey','Secret Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'Stripe'){
                $this->form_validation->set_rules('pKey','Public Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('sKey','Secret Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'Twilio'){
                $this->form_validation->set_rules('pKey','Account SID','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('sKey','Auth Token','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'Authy'){
                $this->form_validation->set_rules('pKey','API Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'Tawk.To'){
                $this->form_validation->set_rules('pKey','Property ID','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'Paystack'){
                $this->form_validation->set_rules('pKey','Public Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('sKey','Secret Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'Capitalist'){
                $this->form_validation->set_rules('pKey','API Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('sKey','Secret Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('contractcode','Contract Code','required', array(
                    'required' => lang('this_field_is_required')
                ));
            } else if($method == 'AdvCash'){
                $this->form_validation->set_rules('pKey','Public Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('sKey','Secret Key','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('merchantName','Merchant Name','required', array(
                    'required' => lang('this_field_is_required')
                ));
                $this->form_validation->set_rules('merchantID1','Merchant ID','required', array(
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
                $pKey = $this->input->post('pKey', TRUE);
                $sKey = $this->input->post('sKey', TRUE);
                $merchant1 = $this->input->post('merchantID1', TRUE);
                $merchant = $this->input->post('merchantID', TRUE);
                $IPNKey = $this->input->post('IPNKey', TRUE);
                $IPNURL = $this->input->post('IPNURL', TRUE);
                $env = $this->input->post('mode', TRUE);
                $status = $this->input->post('status', TRUE);
                $contractcode = $this->input->post('contractcode', TRUE);
                $version = $this->input->post('version', TRUE);
                $payee_account = $this->input->post('payee_account', TRUE);
                $payee_name = $this->input->post('payee_name', TRUE);
                $url = $this->input->post('btcpayurl', TRUE);
                $merchantname = $this->input->post('merchantName', TRUE);

                if($method == 'CoinPayments'){
                    $paymentInfo = array(
                        'public_key'=>$pKey, 
                        'secret_key'=>$sKey,
                        'merchantID'=>$merchant, 
                        'IPN_secret'=>$IPNKey,
                        'base_url'=>$IPNURL,
                        'status'=>$status
                    );
                } else if($method == 'Stripe') {
                    $paymentInfo = array(
                        'public_key'=>$pKey, 
                        'secret_key'=>$sKey, 
                        'status'=>$status
                    );
                } else if($method == 'Payeer') {
                    $paymentInfo = array(
                        'secret_key'=>$sKey,
                        'merchantID'=>$merchant1, 
                        'status'=>$status
                    );
                } else if($method == 'PayPal') {
                    $paymentInfo = array(
                        'public_key'=>$pKey,
                        'secret_key'=>$sKey, 
                        'env'=>$env,
                        'status'=>$status
                    );
                }else if($method == 'Twilio') {
                    $paymentInfo = array(
                        'secret_key'=>$sKey,
                        'public_key'=>$pKey, 
                        'status'=>$status
                    );
                }else if($method == 'Monnify') {
                    $paymentInfo = array(
                        'secret_key'=>$sKey,
                        'public_key'=>$pKey, 
                        'IPN_secret'=>$contractcode, 
                        'status'=>$status
                    );
                }else if($method == 'Paystack') {
                    $paymentInfo = array(
                        'secret_key'=>$sKey,
                        'public_key'=>$pKey, 
                        'status'=>$status
                    );
                }else if($method == 'Perfect Money') {
                    $paymentInfo = array(
                        'merchantID'=>$payee_account,
                        'secret_key'=>$payee_name, 
                        'status'=>$status
                    );
                }else if($method == 'Tawk.To') {
                    $paymentInfo = array(
                        'public_key'=>$pKey,
                        'status'=>$status
                    );
                }else if($method == 'Google Recaptcha') {
                    $paymentInfo = array(
                        'secret_key'=>$sKey,
                        'public_key'=>$pKey, 
                        'env'=>$version,
                        'status'=>$status
                    );

                    $versionInfo = array(
                        array(
                            'type' => 'recaptcha_version',
                            'value' => $version
                        )
                    );

                    $settings_change = $this->db->update_batch('tbl_settings', $versionInfo, 'type');
                }else if($method == 'Google Authenticator') {
                    $paymentInfo = array(
                        'status'=>$status
                    );
                }else if($method == 'Authy') {
                    $paymentInfo = array(
                        'public_key'=>$pKey, 
                        'status'=>$status
                    );
                }else if($method == 'Capitalist'){
                    $paymentInfo = array(
                        'public_key'=>$pKey, 
                        'secret_key'=>$sKey,
                        'merchantID'=>$contractcode,
                        'status'=>$status
                    );
                } else if($method == 'BTCPay'){
                    $paymentInfo = array(
                        'merchantID'=>$merchant1,
                        'base_url'=>$url,
                        'status'=>$status
                    );
                } else if($method == 'AdvCash'){
                    $paymentInfo = array(
                        'merchantID'=>$merchant1,
                        'merchantName'=>$merchantname,
                        'public_key'=>$pKey, 
                        'secret_key'=>$sKey,
                        'status'=>$status
                    );
                }
                
                $result = $this->payments_model->editInfo('tbl_addons_api', $paymentInfo, $id);

                if($result == true)
                {
                    $array = array(
                        'success' => true,
                        'msg' => html_escape($method.' '.lang('method_has_been_updated')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('there_is_a_problem_in_updating_your_information')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
            }
        }
    }

    function testEmail(){
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email','Email address','required', array(
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
        } else
        {
            $email = $this->input->post('email', TRUE);

            //Send Mail
            $conditionUserMail = array('tbl_email_templates.type'=>'Test Email');
            $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

            $companyInfo = $this->settings_model->getsettingsInfo();
        
            if($resultEmail->num_rows() > 0)
            {
                $rowUserMailContent = $resultEmail->row();
                $splVars = array(
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

                $send = $this->sendEmail($toEmail, $mailSubject, $mailContent);

                if($send == true) {
                    $array = array(
                        'success' => true,
                        'msg' => html_escape(lang('email_sending_successful')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );
    
                    echo json_encode($array);
                } else {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('email_sending_has_failed_try_again')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );
    
                    echo json_encode($array);
                }
            }
        }
    }

    function testSMS(){
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('phone','SMS Phone Number','required', array(
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
        } else
        {
            $this->load->model('twilio_model');
            $phone = $this->input->post('phone', TRUE);
            $body = 'Test SMS';

            $result = $this->twilio_model->send_sms($phone, $body);

            if($result)
            {
                $array = array(
                    'success' => true,
                    'msg' => html_escape(lang('sms_sent_successfully')),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            } else
            {
                $array = array(
                    'success' => false,
                    'msg' => html_escape(lang('sms_sending_failed')),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
        }
    }

    function SMSInfoUpdate()
    {
        $module_id = 'settings';
        $module_action = 'email_templates';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->global['pageTitle'] = 'Settings';
            $data["companyInfo"] = $this->settings_model->getsettingsInfo();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('sms_phone','SMS Phone Number','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('smsactive','SMS Active','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('password','Password','required', array(
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
            } else {
                //Test 1: First check if Twilio API is active, if not break and tell the user to setup the API
                $API_status = $this->addons_model->get_addon_info('Twilio');

                if($API_status->status == 1){
                    $SMSPhone  = $this->input->post('sms_phone', TRUE);
                    $SMSActive = $this->input->post('smsactive', TRUE);
                    $password  = $this->input->post('password', TRUE);

                    $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;
                    $result = $this->login_model->loginMe($useremail, $password);

                    if(!empty($result))
                    {
                        $companyInfo = array(
                            array(
                                'type' => 'sms_active',
                                'value' => $SMSActive
                            ),
                            array(
                                'type' => 'sms_phone',
                                'value' => $SMSPhone
                            )
                        );
                        
                        $result = $this->db->update_batch('tbl_settings', $companyInfo, 'type');
                    
                        if($result == true)
                        {
                            $array = array(
                                'success' => true,
                                'msg' => html_escape(lang('updated_successfully')),
                                "csrfTokenName" => $csrfTokenName,
                                "csrfHash" => $csrfHash
                            );

                            echo json_encode($array);
                        }
                        else
                        {
                            $array = array(
                                'success' => false,
                                'msg' => html_escape(lang('there_is_nothing_to_update_please_check_and_try_again')),
                                "csrfTokenName" => $csrfTokenName,
                                "csrfHash" => $csrfHash
                            );

                            echo json_encode($array);
                        }

                    } else {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape(lang('incorrect_password_try_again')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                } else {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('please_activate').' Twilio API'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
            }
        }
    }
    

    function emailInfoUpdate()
    {
        $module_id = 'settings';
        $module_action = 'email_templates';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->global['pageTitle'] = 'Settings';
            $data["companyInfo"] = $this->settings_model->getsettingsInfo();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('SMTPHost','SMTP Host','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('SMTPPort','SMTP Port','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('SMTPEmail','SMTP User','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('SMTPPass','SMTP Password','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('SMTPProtocol','SMTP Protocol','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('emailactive','SMS Active','required', array(
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
                $SMTPHost = $this->input->post('SMTPHost', TRUE);
                $SMTPPort = $this->input->post('SMTPPort', TRUE);
                $SMTPProtocol = $this->input->post('SMTPProtocol', TRUE);
                $SMTPUser = $this->input->post('SMTPEmail', TRUE);
                $SMTPPassword = $this->input->post('SMTPPass', TRUE);
                $emailActive = $this->input->post('emailactive', TRUE);

                $password = $this->input->post('password', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result1 = $this->login_model->loginMe($useremail, $password);
                if(!empty($result1))
                {
                    $companyInfo = array(
                        array(
                            'type' => 'SMTPHost',
                            'value' => $SMTPHost
                        ),
                        array(
                            'type' => 'SMTPPort',
                            'value' => $SMTPPort
                        ),
                        array(
                            'type' => 'SMTPProtocol',
                            'value' => $SMTPProtocol
                        ),
                        array(
                            'type' => 'SMTPUser',
                            'value' => $SMTPUser
                        ),
                        array(
                            'type' => 'SMTPPass',
                            'value' => $SMTPPassword
                        ),
                        array(
                            'type' => 'email_active',
                            'value' => $emailActive
                        )
                    );
                    
                    $result = $this->db->update_batch('tbl_settings', $companyInfo, 'type');
                
                    if($result == true)
                    {
                        $array = array(
                            'success' => true,
                            'msg' => html_escape(lang('updated_successfully')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                    else
                    {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape(lang('there_is_nothing_to_update_please_check_and_try_again')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }

                } else {
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

    function email_template(){
        $module_id = 'settings';
        $module_action = 'email_templates';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            if(empty($this->input->post('id', TRUE)))
            {
                return null;
            }
            else
            {
                $emailInfo = $this->email_model->getEmailInfoById($this->input->post('id', TRUE));
                $emailSubject = $emailInfo->mail_subject;
                $emailBody = $emailInfo->mail_body;
                $emailID = $emailInfo->id;

                $array = array(
                    'id' => $emailID,
                    'subject' => $emailSubject,
                    'body' => html_purify($emailBody),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
        }
    }

    function referralEdit()
    {
        $module_id = 'settings';
        $module_action = 'general_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            if($this->input->post('refType', TRUE) == 'simple')
            {
                $refType = 'simple';
                $int = $this->input->post('simpleInt', TRUE);
                $refpayouts = $this->input->post('refpayouts', TRUE);

                $password = $this->input->post('password', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result1 = $this->login_model->loginMe($useremail, $password);
                if(!empty($result1))
                {
                    $companyInfo = array(
                        array(
                            'type' => 'refType',
                            'value' => $refType
                        ),
                        array(
                            'type' => 'refInterest',
                            'value' => $int
                        ),
                        array(
                            'type' => 'disableRefPayouts',
                            'value' => $refpayouts
                        )
                    );
                    
                    $result = $this->db->update_batch('tbl_settings', $companyInfo, 'type');

                    if($result == true)
                    {
                        $array = array(
                            'success' => true,
                            'msg' => html_escape(lang('successfully_changed_earnings_settings')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                    else
                    {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape(lang('failed_to_change_earnings_settings')),
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
            else if($this->input->post('refType', TRUE) == 'multiple') 
            {
                $wkdpayouts = $this->input->post('wkdpayouts', TRUE);
                $refpayouts = $this->input->post('refpayouts', TRUE);
                $refType = 'multiple';
                $field_values_array = $_POST['multipleInt'];
                if(count($field_values_array) <= 1) 
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('please_input_more_than_1_level_of_interest')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
                else
                {
                    $int = implode(', ', $field_values_array);

                    $password = $this->input->post('password', TRUE);
                    $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                    $result1 = $this->login_model->loginMe($useremail, $password);
                    if(!empty($result1))
                    {

                        $companyInfo = array(
                            array(
                                'type' => 'refType',
                                'value' => $refType
                            ),
                            array(
                                'type' => 'refInterest',
                                'value' => $int
                            ),
                            array(
                                'type' => 'disableRefPayouts',
                                'value' => $refpayouts
                            )
                        );
                        
                        $result = $this->db->update_batch('tbl_settings', $companyInfo, 'type');

                        if($result == true)
                        {
                            $array = array(
                                'success' => true,
                                'msg' => html_escape(lang('successfully_changed_earnings_settings')),
                                "csrfTokenName" => $csrfTokenName,
                                "csrfHash" => $csrfHash
                            );

                            echo json_encode($array);
                        }
                        else
                        {
                            $array = array(
                                'success' => false,
                                'msg' => html_escape(lang('failed_to_change_earnings_settings')),
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
    }
}