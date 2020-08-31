<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Languages (Languages Controller)
 * Languages Class
 * @author : Axis96
 * @version : 1.0
 * @since : 02 February 2019
 */
class Languages extends BaseController
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
        $this->lang->load('validation',$userLang);
        $this->lang->load('tickets',$userLang);
    }

    function addLanguage(){
        $module_id = 'languages';
        $module_action = 'languages';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('upload');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('lname','Language Name','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('lcode','Language Code','required', array(
                'required' => lang('this_field_is_required')
            ));
            if (empty($_FILES['logo']['name']))
            {
                $this->form_validation->set_rules('logoUpload', 'Logo', 'required', array(
                    'required' => lang('this_field_is_required')
                ));
            }

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
                $name = $this->input->post('lname', TRUE);
                $code = $this->input->post('lcode', TRUE);

                //Upload the logos First
                if(isset($_FILES["logo"]["name"])){
                    if ($this->security->xss_clean($this->input->post('logo'), TRUE) === TRUE)
                    {
                        $config["upload_path"] = './uploads';
                        $config['allowed_types'] = 'jpg|png';
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('logo')){
                            $data = ($this->upload->data());
                            $logo = $data["file_name"];
                        }else{
                            $errors = $this->upload->display_errors();
                            $logo = '';
                        }; 
                    }
                } 
                
                $array = array(
                    'name'=>$name, 
                    'code'=>$code, 
                    'logo'=>$logo
                );

                $result = $this->languages_model->addLanguage($array);

                if($result == true)
                {
                    $array = array(
                        'success' => true,
                        'msg' => html_escape(lang('successfully_added_new_language')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash,
                        "logo"=>$logo,
                        "name"=>$name,
                        "code"=>$code,
                        "id"=>$result
                    );

                    echo json_encode($array);
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('failed_to_add_new_language')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
            }
        }
    }

    function editLanguage(){
        $module_id = 'languages';
        $module_action = 'languages';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            
            $this->load->library('upload');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('lname','Language Name','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('lcode','Language Code','required', array(
                'required' => lang('this_field_is_required')
            ));
            //$this->form_validation->set_rules('logo','Logo','required');

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
                $id   = $this->input->post('lid', TRUE);
                $name = $this->input->post('lname', TRUE);
                $code = $this->input->post('lcode', TRUE);

                //Upload the logos First
                if(isset($_FILES["logo"]["name"])){
                    if ($this->security->xss_clean($this->input->post('logo'), TRUE) === TRUE)
                    {
                        $config["upload_path"] = './uploads';
                        $config['allowed_types'] = 'jpg|png';
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('logo')){
                            $data = ($this->upload->data());
                            $logo = $data["file_name"];
                        }else{
                            $errors = $this->upload->display_errors();
                            $getLangLogo = $this->languages_model->getLang($id);
                            $logo = $getLangLogo->logo;
                        }; 
                    }
                } 

                if (empty($_FILES['logo']['name']))
                {
                    $array = array(
                        'name'=>$name, 
                        'code'=>$code 
                    );
                } else
                {
                    $array = array(
                        'name'=>$name, 
                        'code'=>$code, 
                        'logo'=>$logo
                    );
                }
                
                

                $result = $this->languages_model->editLanguage($array, $id);

                if($result == true)
                {
                    $array = array(
                        'success' => true,
                        'msg' => html_escape(lang('successfully_changed_language')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash,
                        "logo"=>$logo,
                        "name"=>$name,
                        "code"=>$code
                    );

                    echo json_encode($array);
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape(lang('failed_to_edit_language')),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
            }
        }
    }

    function editTranslation(){
        $module_id = 'languages';
        $module_action = 'languages';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $langid = $this->input->post('langId', TRUE);

            //$allvalues = $this->input->post(NULL, TRUE);

            $data = array();
            foreach($_POST as $key => $value){ 
                $data[] = array(
                    'key' => $key,
                    'translation' => $this->input->post($key)
                );
            };

            $result = $this->languages_model->editTranslation($langid, $data);
            if($result == TRUE){
                $array = array(
                    'success' => true,
                    'msg'=>lang('updated_successfully'),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash,
                    "id"=> $data
                );

                echo json_encode($array);
            }
        }
    }

    function change_language($id){
        $newLangId = $id;
        $userId = $this->vendorId;

        $langArray = array(
            'lang_id'=>$id
        );

        $result = $this->languages_model->changeLang($userId, $langArray);

        if($result == true){
            $result_array = array(
                'success' => true,
                'msg'=> lang('updated_successfully')
            );

            echo json_encode($result_array);
        } else {
            $result_array = array(
                'success' => false,
                'msg'=> lang('update_failed')
            );

            echo json_encode($result_array);
        }
    }

    function languages(){
        $module_id = 'languages';
        $module_action = 'languages';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $this->global['pageTitle'] = 'Language Settings';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = lang('settings').' <span class="breadcrumb-arrow-right"></span> '.lang('languages');

            $this->load->library('pagination');
            $count = $this->languages_model->getNumLanguages();
            $returns = $this->paginationCompress ( "settings/languages/", $count, 10 );

            $data['languages'] = $this->languages_model->getLanguages($returns["page"], $returns["segment"]);
            //Let us load the first language
            $data['langID'] = $this->languages_model->firstLangRow()->id;
            $data['langName'] = $this->languages_model->firstLangRow()->name;
            $data['langCode'] = $this->languages_model->firstLangRow()->code;
            $data['langLogo'] = $this->languages_model->firstLangRow()->logo;
            $data['langModules'] = $this->languages_model->getLangModules();

            $data['languageNow'] = $this->languages_model->userLang($this->vendorId);
            
            $this->loadViews("settings/languages", $this->global, $data, NULL);
        }
    }

    function getLangSettings($langId, $module)
    {
        $module_id = 'languages';
        $module_action = 'languages';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $result = $this->languages_model->getLangSettings($langId, $module);
            $module = $this->languages_model->getLangModule($module);
            $lang = $this->languages_model->getLang($langId);
            if($result)
            {
                $array = array(
                    'success' => true,
                    'msg' => html_escape(lang('success')),
                    'lang'=> $lang->name.' '.lang('settings'),
                    'module_code'=>$module->lang_name,
                    "list" => $result
                );

                echo json_encode($array);
            }
            else
            {
                $array = array(
                    'success' => false,
                    'module_name'=>$module->name,
                    'module_code'=>$module->lang_name,
                    'msg' => html_escape(lang('an_error_occurred'))
                );

                echo json_encode($array);
            }
        }
    }

    function getLang($id)
    {
        $module_id = 'languages';
        $module_action = 'languages';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $result = $this->languages_model->getLang($id);
            if($result)
            {
                $array = array(
                    'success' => true,
                    'msg' => html_escape(lang('success')),
                    'logo'=> $result->logo,
                    'name'=> $result->name,
                    'code'=> $result->code
                );

                echo json_encode($array);
            }
            else
            {
                $array = array(
                    'success' => false,
                    'msg' => html_escape(lang('an_error_occurred'))
                );

                echo json_encode($array);
            }
        }
    }
}