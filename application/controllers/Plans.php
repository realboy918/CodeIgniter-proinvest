<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Plans (PlansController)
 * Error class to perform CRUD functions on investment plans
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Plans extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('plans_model');
        $this->load->model('payments_model');
        $this->load->model('user_model');
        $this->load->model('login_model');
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
     * @access: Admin Only
     * This function is used to load the investment plans list
     */
    function inPlans()
    {
        $module_id = 'plans';
        $module_action = 'view';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = $this->input->post('searchText' ,TRUE);
            $data['searchText'] = $searchText;
            $role = '3';
            
            $this->load->library('pagination');
            
            $count = $this->plans_model->planListingCount($searchText);

			$returns = $this->paginationCompress ( "plans/", $count, 10 );
            
            $data['plans'] = $this->plans_model->plans($searchText, $returns["page"], $returns["segment"], $role);

            $data['minInvest'] = $this->plans_model->minInvest();

            $data['allPlans'] = $this->plans_model->getAllPlans();
            
            $this->global['pageTitle'] = 'Investment Plans';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = lang('investment_plans').' <span class="breadcrumb-arrow-right"></span> '.lang('view'); 
            
            $this->loadViews("plans/table", $this->global, $data, NULL);
        }
    }

    /**
     * @access: Admin Only
     * @desc: This function is used to load the add new plan form
     */
    function addNewPlan()
    {
        $module_id = 'plans';
        $module_action = 'add';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'Add New Plan';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = lang('investment_plans').' <span class="breadcrumb-arrow-right"></span> '.lang('new'); 
            $data['periods'] = $this->payments_model->getAllPeriods();

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('pname','Plan Name','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('minInv','Minimum Investment','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('maxInv','Maximum Investment','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('profit','Profit','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('period','Interest Period','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('maturityDate','Maturity Period','required', array(
                'required' => lang('this_field_is_required')
            ));
            
            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $pName = ucwords(strtolower($this->input->post('pname', TRUE)));
                $minInv = strtolower($this->input->post('minInv', TRUE));
                $maxInv = strtolower($this->input->post('maxInv', TRUE));
                $int = $this->input->post('int', TRUE) == null ? 0 : 1;
                $principalReturn = $this->input->post('principalReturn', TRUE) == null ? 0 : 1;
                $clientDisplay = $this->input->post('clientdisp', TRUE) == null ? 0 : 1;
                $profit = strtolower($this->input->post('profit', TRUE));
                $period = strtolower($this->input->post('period', TRUE));
                $maturityDate = strtolower($this->input->post('maturityDate', TRUE));
                $businessDays = $this->input->post('businessDays', TRUE) == null ? 0 : 1;
                
                $planInfo = array(
                    'name'=>$pName, 
                    'minInvestment'=>$minInv, 
                    'maxInvestment'=>$maxInv, 
                    'intAfterMaturity'=> $int, 
                    'businessDays'=> $businessDays,
                    'principalReturn' => $principalReturn,
                    'profit'=>$profit, 
                    'period'=>$period, 
                    'maturity'=>$maturityDate,
                    'clientDisplay'=>$clientDisplay,
                    'createdBy'=>$this->vendorId, 
                    'createdDtm'=>date('Y-m-d H:i:s')
                );
                    
                $result = $this->plans_model->addNewPlan($planInfo);
                    
                if($result>0)
                {
                    $this->session->set_flashdata('success', lang('new_plan_created_successfully'));
                }
                else
                {
                    $this->session->set_flashdata('error',lang('plan_creation_failed'));
                }

                redirect('plans/new');
            }
            
            $this->loadViews("plans/new", $this->global, $data, NULL);
        }
    }

    /**
     * @access: Admin Only
     * This function is used load the edit plan form
     * @param number $planId : Optional : This is the plan id
     */
    function editPlan($planId = NULL)
    {
        $module_id = 'plans';
        $module_action = 'edit';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {

            $this->global['pageTitle'] = 'Edit Plan';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = lang('investment_plans').' <span class="breadcrumb-arrow-right"></span> '.lang('edit'); 
            $data['periods'] = $this->payments_model->getAllPeriods();

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('pname','Plan Name','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('minInv','Minimum Investment','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('maxInv','Maximum Investment','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('profit','Profit','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('period','Interest Period','required', array(
                'required' => lang('this_field_is_required')
            ));
            
            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $pName = ucwords(strtolower($this->input->post('pname', TRUE)));
                $minInv = strtolower($this->input->post('minInv', TRUE));
                $maxInv = strtolower($this->input->post('maxInv', TRUE));
                $int = $this->input->post('int', TRUE) == null ? 0 : 1;
                $principalReturn = $this->input->post('principalReturn', TRUE) == null ? 0 : 1;
                $profit = strtolower($this->input->post('profit', TRUE));
                $period = strtolower($this->input->post('period', TRUE));
                $clientDisplay = strtolower($this->input->post('clientdisp', TRUE));
                $maturity = strtolower($this->input->post('maturityDate', TRUE));
                $businessDays = $this->input->post('businessDays', TRUE) == null ? 0 : 1;
                
                $planInfo = array(
                    'name'=>$pName, 
                    'minInvestment'=>$minInv, 
                    'maxInvestment'=>$maxInv, 
                    'intAfterMaturity'=> $int, 
                    'principalReturn' => $principalReturn,
                    'businessDays'=> $businessDays,
                    'profit'=>$profit, 
                    'period'=>$period, 
                    'maturity'=>$maturity,
                    'clientDisplay'=>$clientDisplay,
                    'createdBy'=>$this->vendorId, 
                    'createdDtm'=>date('Y-m-d H:i:s')
                );
                
                $result = $this->plans_model->editPlan($planInfo, $planId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', lang('updated_successfully'));
                }
                else
                {
                    $this->session->set_flashdata('error', lang('update_failed'));
                }
                
                redirect('plans/edit/'.$planId);
            }
            
            $this->global['pageTitle'] = 'Edit Plan';
            $data['planInfo'] = $this->plans_model->getPlanInfo($planId);
            
            $this->loadViews("plans/edit", $this->global, $data, NULL);
        }
    }

    
    /**
     * @access: Admin Only
     * This function is used to delete the plan using the planId
     * @return boolean $result : TRUE / FALSE
     */
    function deletePlan($planId = NULL)
    {
        $module_id = 'plans';
        $module_action = 'edit';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            $this->form_validation->set_rules('password','Password','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('id','Plan ID','required', array(
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
                $userId = $this->input->post('id', TRUE);
                $password = $this->input->post('password', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result = $this->login_model->loginMe($useremail, $password);
                if(!empty($result))
                {
                    $result1 = $this->plans_model->deletePlan($planId);
                
                    if ($result1 > 0) { 
                        $array = array(
                            'success' => true,
                            'msg' => html_escape(lang('successfully_deleted_plan')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                    else { 
                        $array = array(
                            'success' => false,
                            'msg' => html_escape(lang('an_error_occurred_while_deleting_your_plan_reload_page_and_try_again')),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );
                        
                        echo json_encode($array);
                    }

                }else{
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