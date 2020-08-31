<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Webcontrol extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('login_model');
        $this->load->model('transactions_model');
        $this->load->model('settings_model');
        $this->load->model('email_model');
        $this->load->model('payments_model');
        $this->load->model('twilio_model');
        $this->load->model('languages_model');
        $this->load->model('web_model');
        $this->isLoggedIn();   

        $userLang = $this->session->userdata('site_lang') == '' ?  "english" : $this->session->userdata('site_lang');

        $this->load->helper('language');
        $this->lang->load('common',$userLang);
        $this->lang->load('dashboard',$userLang);
        $this->lang->load('transactions',$userLang);
        $this->lang->load('users',$userLang);
        $this->lang->load('login',$userLang);
        $this->lang->load('plans',$userLang);
        $this->lang->load('plugins',$userLang);
        $this->lang->load('email_templates',$userLang);
        $this->lang->load('settings',$userLang);
        $this->lang->load('payment_methods',$userLang);
        $this->lang->load('languages',$userLang);
        $this->lang->load('validation',$userLang);
        $this->lang->load('tickets',$userLang);
        $this->lang->load('web_control',$userLang);
    }

    public function templates(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'Templates';   
            $this->global['displayBreadcrumbs'] = true;
            $this->global['breadcrumbs'] = lang('templates').' <span class="breadcrumb-arrow-right"></span> '.lang('settings');

            //Count
            $count = $this->web_model->templatesListingCount();
            $returns = $this->paginationCompress ( "webcontrol/templates", $count, 10 );
            
            $data['templates'] = $this->web_model->allTemplates($returns["page"], $returns["segment"]);

            $this->loadViews("web/templates", $this->global, $data, NULL);
        }
    }

    public function templateBuilder($id){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'Template Builder';   
            $this->global['displayBreadcrumbs'] = true;
            $this->global['breadcrumbs'] = 'Templates'.' <span class="breadcrumb-arrow-right"></span> '.'Settings';

            $this->global['templateInfo'] = $this->web_model->getAllContent($id);

            $this->load->view("web/builder", $this->global);
        }
    }

    public function FAQs(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'Templates';   
            $this->global['displayBreadcrumbs'] = false;

            //Search Data
            $searchText = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $this->global['searchText'] = $this->input->post('searchText', TRUE);

            //Count
            $count = $this->web_model->faqListingCount($searchText);
            $returns = $this->paginationCompress ( "webcontrol/faq", $count, 10 );
            
            $data['faqs'] = $this->web_model->allFaqs($searchText, $returns["page"], $returns["segment"]);

            $this->loadViews("web/faqs", $this->global, $data, NULL);
        }
    }

    public function createFaq(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('question','Answer','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('answer','Answer','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                //$this->session->set_flashdata('errors', validation_errors());
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
                $response['msg'] = html_escape('Please correct the errors and try again.');

                echo json_encode($response); 
            }
            else
            {
                $question = $this->input->post('question', TRUE);
                $answer = $this->input->post('answer', TRUE);

                $array = array(
                    'question' => $question,
                    'answer' => $answer
                );

                $result = $this->web_model->createFaq($array);

                if($result > 0){
                    $res = array(
                        'success'=>true,
                        'msg'=>'Faq created successfully'
                    );

                    echo json_encode($res);
                } else {
                    $res = array(
                        'success'=>false,
                        'msg'=>'Faq creation failed'
                    );

                    echo json_encode($res);
                }
            }
        }
    }

    public function editFaq($id){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('question','Answer','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('answer','Answer','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                //$this->session->set_flashdata('errors', validation_errors());
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
                $response['msg'] = html_escape('Please correct the errors and try again.');

                echo json_encode($response); 
            }
            else
            {
                $question = $this->input->post('question', TRUE);
                $answer = $this->input->post('answer', TRUE);

                $array = array(
                    'question' => $question,
                    'answer' => $answer
                );

                $result = $this->web_model->editFaq($id, $array);

                if($result > 0){
                    $res = array(
                        'success'=>true,
                        'msg'=>'Faq edited successfully'
                    );

                    echo json_encode($res);
                } else {
                    $res = array(
                        'success'=>false,
                        'msg'=>'There was nothing to edit'
                    );

                    echo json_encode($res);
                }
            }
        }
    }

    public function deleteFaq($id){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $res = $this->web_model->deleteFaq($id);

            if($res){
                $res = array(
                    'success'=>true,
                    'msg'=>'Faq deleted successfully'
                );

                echo json_encode($res);
            } else {
                $res = array(
                    'success'=>false,
                    'msg'=>'An error occurred. Please refresh page and try again.'
                );

                echo json_encode($res);
            }
        }
    }

    public function terms(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $data['terms'] = $this->web_model->getContent('terms')->value;
            $this->global['pageTitle'] = 'T&Cs Settings';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = 'Terms & Conditions'.' <span class="breadcrumb-arrow-right"></span> '.'Settings';
            $this->loadViews("web/terms", $this->global, $data); 
        } 
    }

    public function policy(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $data['policy'] = $this->web_model->getContent('policy')->value;
            $this->global['pageTitle'] = 'Policy Settings';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = 'Policy'.' <span class="breadcrumb-arrow-right"></span> '.'Settings';
            $this->loadViews("web/policy", $this->global, $data);  
        }
    }

    public function editPolicy(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('policycontent','Content','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                //$this->session->set_flashdata('errors', validation_errors());
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
                $response['msg'] = html_escape('Please correct the errors and try again.');

                echo json_encode($response); 
            }
            else
            {
                $content = $this->input->post('policycontent', TRUE);

                $array = array(
                    'value'=>$content,
                );

                $res = $this->web_model->editContent($array, 'policy');

                if($res > 0){
                    $res = array(
                        'success'=>true,
                        'msg'=>'Content edited successfully'
                    );
    
                    echo json_encode($res);
                } else {
                    $res = array(
                        'success'=>false,
                        'msg'=>'An error occurred. Please refresh page and try again.'
                    );
    
                    echo json_encode($res);
                }
            }
        }
    }

    public function editTerms(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('content','Content','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                //$this->session->set_flashdata('errors', validation_errors());
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
                $response['msg'] = html_escape('Please correct the errors and try again.');

                echo json_encode($response); 
            }
            else
            {
                $content = $this->input->post('content', TRUE);

                $array = array(
                    'value'=>$content,
                );

                $res = $this->web_model->editContent($array, 'terms');

                if($res > 0){
                    $res = array(
                        'success'=>true,
                        'msg'=>'Content edited successfully'
                    );
    
                    echo json_encode($res);
                } else {
                    $res = array(
                        'success'=>false,
                        'msg'=>'An error occurred. Please refresh page and try again.'
                    );
    
                    echo json_encode($res);
                }
            }
        }
    }

    public function footer(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $data['content'] = $this->web_model->getContent('footer')->value;
            $this->global['pageTitle'] = 'Footer Note';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = 'Footer Note'.' <span class="breadcrumb-arrow-right"></span> '.'Settings';
            $this->loadViews("web/footer", $this->global, $data);  
        }
    }

    public function editFooter(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('content','Content','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                //$this->session->set_flashdata('errors', validation_errors());
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
                $response['msg'] = html_escape('Please correct the errors and try again.');

                echo json_encode($response); 
            }
            else
            {
                $content = $this->input->post('content', TRUE);

                $array = array(
                    'value'=>$content,
                );

                $res = $this->web_model->editContent($array, 'footer');

                if($res > 0){
                    $res = array(
                        'success'=>true,
                        'msg'=>'Content edited successfully'
                    );
    
                    echo json_encode($res);
                } else {
                    $res = array(
                        'success'=>false,
                        'msg'=>'An error occurred. Please refresh page and try again.'
                    );
    
                    echo json_encode($res);
                }
            }
        }
    }

    function editBuilder(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('header_subtitle','Header Subtitle','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('header_title','Header Title','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('header_description','Header Description','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card1_subtitle','Card 1 Subtitle','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card1_title','Card 1 Title','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card1_content','Card 1 Content','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card2_subtitle','Card 2 Subtitle','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card2_title','Card 2 Title','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card3_subtitle','Card 3 Subtitle','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card3_title','Card 3 Title','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card3_content','Card 3 Content','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card4_subtitle','Card 4 Subtitle','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card4_title','Card 4 Title','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card4_content','Card 4 Content','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card5_subtitle','Card 5 Subtitle','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card5_title','Card 5 Title','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('card5_content','Card 5 Content','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('footer_content','Footer Content','required', array(
                'required' => lang('this_field_is_required')
            ));

            if($this->form_validation->run() == FALSE)
            {
                //$this->session->set_flashdata('errors', validation_errors());
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
                $response['msg'] = html_escape('Please correct the errors and try again.');

                echo json_encode($response); 
            }
            else
            {
                //Headers
                $headersubtitle = $this->input->post('header_subtitle', TRUE);
                $headertitle = $this->input->post('header_title', TRUE);
                $headerdescription = $this->input->post('header_description', TRUE);

                //Subtitles
                $card1subtitle = $this->input->post('card1_subtitle', TRUE);
                $card2subtitle = $this->input->post('card2_subtitle', TRUE);
                $card3subtitle = $this->input->post('card3_subtitle', TRUE);
                $card4subtitle = $this->input->post('card4_subtitle', TRUE);
                $card5subtitle = $this->input->post('card5_subtitle', TRUE);

                //Titles
                $card1title = $this->input->post('card1_title', TRUE);
                $card2title = $this->input->post('card2_title', TRUE);
                $card3title = $this->input->post('card3_title', TRUE);
                $card4title = $this->input->post('card4_title', TRUE);
                $card5title = $this->input->post('card5_title', TRUE);

                //Content
                $card1content = $this->input->post('card1_content', TRUE);
                $card3content = $this->input->post('card3_content', TRUE);
                $card4content = $this->input->post('card4_content', TRUE);
                $card5content = $this->input->post('card5_content', TRUE);

                $footercontent = $this->input->post('footer_content', TRUE);

                $templateInfo = array(
                    array(
                        'name' => 'header_sub_title',
                        'value' => $headersubtitle
                    ),
                    array(
                        'name' => 'header_title',
                        'value' => $headertitle
                    ),
                    array(
                        'name' => 'header_description',
                        'value' => $headerdescription
                    ),
                    array(
                        'name' => 'card_1_subtitle',
                        'value' => $card1subtitle
                    ),
                    array(
                        'name' => 'card_1_title',
                        'value' => $card1title
                    ),
                    array(
                        'name' => 'card_1_content',
                        'value' => $card1content
                    ),
                    array(
                        'name' => 'card_2_subtitle',
                        'value' => $card2subtitle
                    ),
                    array(
                        'name' => 'card_2_title',
                        'value' => $card2title
                    ),
                    array(
                        'name' => 'card_3_subtitle',
                        'value' => $card3subtitle
                    ),
                    array(
                        'name' => 'card_3_title',
                        'value' => $card3title
                    ),
                    array(
                        'name' => 'card_3_content',
                        'value' => $card3content
                    ),
                    array(
                        'name' => 'card_4_subtitle',
                        'value' => $card4subtitle
                    ),
                    array(
                        'name' => 'card_4_title',
                        'value' => $card4title
                    ),
                    array(
                        'name' => 'card_4_content',
                        'value' => $card4content
                    ),
                    array(
                        'name' => 'card_5_subtitle',
                        'value' => $card5subtitle
                    ),
                    array(
                        'name' => 'card_5_title',
                        'value' => $card5title
                    ),
                    array(
                        'name'=> 'card_5_content',
                        'value'=> $card5content
                    ),
                    array(
                        'name'=> 'footer',
                        'value'=> $footercontent
                    )
                );

                $this->db->update_batch('tbl_content', $templateInfo, 'name');

                $array = array(
                    'success' => true,
                    'msg' => html_escape(lang('successfully_updated_your_info')),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }

        }
    }
}