<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Verification extends BaseController
{
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
        $this->isLoggedIn(); 
        $this->checkVerification(); 
    }

    function verify(){
        if($this->role != ROLE_CLIENT)
        {
            $searchText = $this->input->post('searchText', TRUE);
            $this->global['searchText'] = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $role = '3';
            
            $this->load->library('pagination');
            
            $count = $this->verification_model->verification_list_count($searchText);
            $returns = $this->paginationCompress ( "verification/", $count, 10 );
            
            $data['verifications'] = $this->verification_model->verification_list($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['displayBreadcrumbs'] = false; 
            $this->global['pageTitle'] = 'Verification';
            
            $this->loadViews("verification/table", $this->global, $data, NULL);
        }
        else
        {
            $data['verificationInfo'] = $this->verification_model->getVerificationInfo($this->vendorId);

            if($this->global ['isVerified'] == false )
            {
                $this->global['pageTitle'] = 'Verify'; 
                $this->global['displayBreadcrumbs'] = true;       
                $this->global['breadcrumbs'] = 'Verification <span class="breadcrumb-arrow-right"></span> verify account';     
                $this->loadViews("verification/verify", $this->global, $data, NULL);
            } else {
                redirect('dashboard');
            }
        }
    }

    function approve($verificationId){
        if($this->role == ROLE_CLIENT)
        { 
            $this->loadThis();
        } else {
            $verificationInfo = $this->verification_model->getVerificationInfoById($verificationId);
            if($verificationInfo == false){
                redirect('/verification');
            } else {
                $data['verificationInfo'] = $verificationInfo;
                $this->global['pageTitle'] = 'Verification'; 
                $this->global['displayBreadcrumbs'] = true;       
                $this->global['breadcrumbs'] = 'Verification <span class="breadcrumb-arrow-right"></span> approval';     
                $this->loadViews("verification/approve.php", $this->global, $data, NULL);
            }  
        }
    }

    function reject_approval($userId){
        if($this->role == ROLE_CLIENT)
        { 
            $this->loadThis();
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
                    
            $this->form_validation->set_rules('reason','Rejection reason','trim');

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
                $response['msg'] = html_escape('Please correct the errors and try again.');

                echo json_encode($response); 
            }
            else
            {
                $approval = 'resubmit';
                $reason = $this->input->post('reason');

                $data = array(
                    'verification_status' => $approval,
                    'rejection_reason' => $reason,
                );

                $result = $this->verification_model->updateInfo($data, $userId);

                if($result){
                    $verificationInfo = $this->verification_model->getVerificationInfo($userId);

                    $stat = '[Pending Resubmission]';

                    $res = array(
                        'success'=>true,
                        'status'=>'reject',
                        'status_value'=>$stat,
                        'msg'=>'Submission rejected',
                        'csrfTokenName'=>$csrfTokenName,
                        'csrfHash'=>$csrfHash
                    );

                    echo json_encode($res);

                } else {
                    $res = array(
                        'success'=>false,
                        'msg'=>'There is an error in approving the submitted documents',
                        'csrfTokenName'=>$csrfTokenName,
                        'csrfHash'=>$csrfHash
                    );

                    echo json_encode($res);
                }
            }
        }
    }

    function accept_approval($userId){
        if($this->role == ROLE_CLIENT)
        { 
            $this->loadThis();
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $approval = 'approved';

            $data = array(
                'verification_status' => $approval,
                'rejection_reason' => NULL,
            );

            $result = $this->verification_model->updateInfo($data, $userId);

            if($result){
                $verificationInfo = $this->verification_model->getVerificationInfo($userId);

                $stat = '[Approved]';

                $res = array(
                    'success'=>true,
                    'status'=>'approved',
                    'status_value'=>$stat,
                    'msg'=>'Submission approved',
                    'csrfTokenName'=>$csrfTokenName,
                    'csrfHash'=>$csrfHash
                );

                echo json_encode($res);

            } else {
                $res = array(
                    'success'=>false,
                    'msg'=>'There is an error in approving the submitted documents',
                    'csrfTokenName'=>$csrfTokenName,
                    'csrfHash'=>$csrfHash
                );

                echo json_encode($res);
            }
            
        }
    }

    function submit(){
        if($this->role != ROLE_CLIENT)
        {
            $this->loadThis();
        }else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $verificationInfo = $this->verification_model->getVerificationInfo($this->vendorId);

            $this->load->library('upload');

            //Check Files Uploads first
            if(isset($_FILES["img"]["name"])){
                if ($this->security->xss_clean($this->input->post('img'), TRUE) === TRUE)
                {
                    $config["upload_path"] = './uploads';
                    $config['allowed_types'] = 'pdf|jpg|png';
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('img')){
                        $data = ($this->upload->data());
                        $name_img1 = $data["file_name"];
                    }else{
                        $errors = $this->upload->display_errors();
                        $name_img1 = $verificationInfo->payment_proof_img;
                    }; 
                }
            }
            
            $array = array(
                'userId'=>$this->vendorId,
                'payment_proof_img'=>$name_img1,
                'verification_status'=>'submitted'
            );

            $result = $this->verification_model->updateInfo($array, $this->vendorId);

            if($result > 0)
            {
                $res = array(
                    'success'=>true,
                    'msg'=>'You have successfully submitted your payment details'
                );

                echo json_encode($res);
            } else
            {
                $res = array(
                    'success'=>false,
                    'msg'=>'There is a problem in uploading your payment details. Please refresh the page and try again.'
                );

                echo json_encode($res);
            } 
        }
    }
}