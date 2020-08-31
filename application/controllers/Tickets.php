<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Tickets (TicketsController)
 * Tickets Class
 * @author : Axis96
 * @version : 1.0
 * @since : 22 June 2020
 */
class Tickets extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('login_model');
        $this->load->model('ticket_model');
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

    function listTickets($state = NULL){
        //Pagination lib
        $this->load->library('pagination');

        //Page Data
        $this->global['pageTitle'] = 'Tickets';   
        $this->global['displayBreadcrumbs'] = false;  

        //Search Data
        $searchText = $this->input->post('searchText', TRUE);
        $data['searchText'] = $searchText;
        $this->global['searchText'] = $this->input->post('searchText', TRUE);

        if($this->role == ROLE_CLIENT)
        {   if(isset($_SESSION['helpdesk_priority'])){
                $priority = $_SESSION['helpdesk_priority'];
            } else {
                $priority = NULL;
            }
            $userId = $this->vendorId;
            $count = $this->ticket_model->ticketListingCount($this->vendorId, $state, $priority);
            $returns = $this->paginationCompress ( "tickets/", $count, 10 );
        } else {
            if(isset($_SESSION['helpdesk_priority'])){
                $priority = $_SESSION['helpdesk_priority'];
            } else {
                $priority = NULL;
            }
            //print_r($_SESSION);
            $userId = NULL;
            $count = $this->ticket_model->ticketListingCount(NULL, $state, $priority);
			$returns = $this->paginationCompress ( "tickets/", $count, 10 );
        }
        $data['tickets'] = $this->ticket_model->listTickets($userId, $state, $priority, $returns["page"], $returns["segment"]);

        $this->loadViews("tickets/table", $this->global, $data, NULL);
    }

    function priority_filter(){
        $priority = $this->input->post('priority', TRUE);
        $_SESSION['helpdesk_priority'] = $priority;

        $res = array(
            'success'=>true,
        );

        echo json_encode($res);
    }

    function team_filter(){
        $team = $this->input->post('team', TRUE);
        $SESSION['helpdesk_team'] = $team;

        $res = array(
            'success'=>true,
        );

        echo json_encode($res);
    }

    function remove_filter(){
        $this->session->unset_userdata('helpdesk_priority');
        $this->session->unset_userdata('helpdesk_team');

        $res = array(
            'success'=>true,
        );

        echo json_encode($res);
    }

    function ticketCategories(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            //Page Data
            $this->global['pageTitle'] = 'Tickets';   
            $this->global['displayBreadcrumbs'] = false;

            //Search Data
            $searchText = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $this->global['searchText'] = $this->input->post('searchText', TRUE);

            //Count
            $count = $this->ticket_model->listCategoriesCount();
            $returns = $this->paginationCompress ( "ticket_categories/", $count, 10 );
            
            $data['categories'] = $this->ticket_model->listCategories($returns["page"], $returns["segment"]);

            $this->loadViews("tickets/categories", $this->global, $data, NULL);
        }
    }

    function createTicket(){
        if($this->role == ROLE_CLIENT)
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('subject','Subject','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('message','Message','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('category','Category','required', array(
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
                $subject = $this->input->post('subject', TRUE);
                $message = $this->input->post('message', TRUE);
                $categoryID = $this->input->post('category', TRUE);
                $priority = $this->input->post('priority', TRUE);

                $array = array(
                    'userId' => $this->vendorId,
                    'message' => $message,
                    'subject' => $subject,
                    'priority'=> $priority,
                    'categoryId' => $categoryID,
                    'assignedTo' => 0,
                    'resolved' => 0,
                );

                $result = $this->ticket_model->createTicket($array);

                if($result > 0){
                    $res = array(
                        'success'=>true,
                        'msg'=>'Ticket created successfully'
                    );

                    echo json_encode($res);
                } else {
                    $res = array(
                        'success'=>false,
                        'msg'=>'Ticket creation failed'
                    );

                    echo json_encode($res);
                }
            }
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('subject','Subject','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('email', 'Email', 'required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('message','Message','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('category','Category','required', array(
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
                $email = $this->input->post('email', TRUE);
                $subject = $this->input->post('subject', TRUE);
                $message = $this->input->post('message', TRUE);
                $categoryID = $this->input->post('category', TRUE);
                $priority = $this->input->post('priority', TRUE);
                $assignee = $this->input->post('assignee', TRUE);

                //Check if email exists for client
                $emailCheck = $this->login_model->checkClientExist($email, '3');

                if($emailCheck)
                {
                    $array = array(
                        'userId' => $emailCheck->userId,
                        'message' => $message,
                        'subject' => $subject,
                        'categoryId' => $categoryID,
                        'priority' => $priority,
                        'assignedTo' => $assignee
                    );

                    $result = $this->ticket_model->createTicket($array);

                    if($result > 0){
                        $res = array(
                            'success'=>true,
                            'msg'=>'Ticket created successfully'
                        );
    
                        echo json_encode($res);
                    } else {
                        $res = array(
                            'success'=>false,
                            'msg'=>'Ticket creation failed'
                        );
    
                        echo json_encode($res);
                    }
                } else {
                    $res = array(
                        'success'=>false,
                        'msg'=>'This email does not exist'
                    );

                    echo json_encode($res);
                }
            }
        }
    }

    function viewTicket($id){
        if($this->role == ROLE_CLIENT)
        {
            //Indicate that it has been viewed
            $viewarr = array(
                'supportReply'=>0
            );
            $this->ticket_model->updateTicket($id, $viewarr);

            //check if ticket belongs to this user
            $ticketInfo = $this->ticket_model->ticketInfo($id);
            $owner = $ticketInfo->userId;

            if($this->vendorId != $owner){
                $this->loadThis();
            } else {
                //Page Data
                $this->global['pageTitle'] = 'Tickets';   
                $this->global['displayBreadcrumbs'] = true;
                $this->global['breadcrumbs'] = 'Tickets'.' <span class="breadcrumb-arrow-right"></span> '.'CRPISUEO';

                $data['ticket'] = $this->ticket_model->ticketInfo($id);
                $data['timeago'] = $this->time_elapsed_string($data['ticket']->createdDtm);
                $data['team'] = $this->ticket_model->team();

                //Count
                $data['count'] = $this->ticket_model->listRepliesCount($id);
                $data['limit'] = 2;
                $segment = 0;
                $data['replies'] = $this->ticket_model->listReplies($id, $data['limit'], $segment);

                $this->loadViews("tickets/view", $this->global, $data, NULL);
            }
        } else {
            //Indicate that it has been viewed
            $viewarr = array(
                'clientReply'=>0
            );
            $this->ticket_model->updateTicket($id, $viewarr);

            //Page Data
            $this->global['pageTitle'] = 'Tickets';   
            $this->global['displayBreadcrumbs'] = true;
            $this->global['breadcrumbs'] = 'Tickets'.' <span class="breadcrumb-arrow-right"></span> '.'CRPISUEO';

            $data['ticket'] = $this->ticket_model->ticketInfo($id);
            $data['timeago'] = $this->time_elapsed_string($data['ticket']->createdDtm);
            $data['team'] = $this->ticket_model->team();

            //Count
            $data['count'] = $this->ticket_model->listRepliesCount($id);
            $data['limit'] = 2;
            $segment = 0;
            $data['replies'] = $this->ticket_model->listReplies($id, $data['limit'], $segment);

            $this->loadViews("tickets/view", $this->global, $data, NULL);
        }
    }

    function resolve($id){
        if($this->role == ROLE_CLIENT)
        {
            //check if ticket belongs to this user
            $ticketInfo = $this->ticket_model->ticketInfo($id);
            $owner = $ticketInfo->userId;

            if($this->vendorId != $owner){
                $this->loadThis();
            } else {
                $ticketId = $id;

                $array = array(
                    'resolved'=>1
                );

                $result = $this->ticket_model->updateTicket($ticketId, $array);

                if($result > 0){
                    $res = array(
                        'success'=>true
                    );

                    echo json_encode($res);
                }
            }
        } else {
            $ticketId = $id;

            $array = array(
                'resolved'=>1
            );

            $result = $this->ticket_model->updateTicket($ticketId, $array);

            if($result > 0){
                $res = array(
                    'success'=>true
                );

                echo json_encode($res);
            }
        }
    }

    function reopen($id){
        if($this->role == ROLE_CLIENT)
        {
            //check if ticket belongs to this user
            $ticketInfo = $this->ticket_model->ticketInfo($id);
            $owner = $ticketInfo->userId;

            if($this->vendorId != $owner){
                $this->loadThis();
            } else {
                $ticketId = $id;

                $array = array(
                    'resolved'=>0
                );

                $result = $this->ticket_model->updateTicket($ticketId, $array);

                if($result > 0){
                    $res = array(
                        'success'=>true
                    );

                    echo json_encode($res);
                }
            }
        } else {
            $ticketId = $id;

            $array = array(
                'resolved'=>0
            );

            $result = $this->ticket_model->updateTicket($ticketId, $array);

            if($result > 0){
                $res = array(
                    'success'=>true
                );

                echo json_encode($res);
            }
        }
    }

    function viewPreviousMessages($id){
        $replies = $this->ticket_model->allReplies($id);

        $res = array(
            'success' => true,
            'data' => $replies
        );

        echo json_encode($res);
    }

    function comment($ticketId){
        if($this->role == ROLE_CLIENT)
        {
            //check if ticket belongs to this user
            $ticketInfo = $this->ticket_model->ticketInfo($ticketId);
            $owner = $ticketInfo->userId;

            if($this->vendorId != $owner){
                $this->loadThis();
            } else {
                $csrfTokenName = $this->security->get_csrf_token_name();
                $csrfHash = $this->security->get_csrf_hash();
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('comment','Comment','required', array(
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
                    $response['msg'] = html_escape('Please correct the errors and try again.');
    
                    echo json_encode($response); 
                }
                else
                {
                    $comment = $this->input->post('comment', TRUE);
    
                    $array = array(
                        'ticketId'=>$ticketId,
                        'message'=>$comment,
                        'repliedById'=>$this->vendorId
                    );
    
                    $result = $this->ticket_model->reply($array);
    
                    if($result > 0){
                        //Indicate that there is a reply
                        $ticarray = array(
                            'clientReply' => 1
                        );
                        $isReply = $this->ticket_model->updateTicket($ticketId, $ticarray);

                        $res = array(
                            'success'=>true
                        );
                        echo json_encode($res);
                    }
                }
            }
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('comment','Comment','required', array(
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
                $response['msg'] = html_escape('Please correct the errors and try again.');

                echo json_encode($response); 
            }
            else
            {
                $comment = $this->input->post('comment', TRUE);

                $array = array(
                    'ticketId'=>$ticketId,
                    'message'=>$comment,
                    'repliedById'=>$this->vendorId
                );

                $result = $this->ticket_model->reply($array);

                if($result > 0){
                    //Indicate that there is a reply
                    $ticarray = array(
                        'supportReply' => 1
                    );
                    $isReply = $this->ticket_model->updateTicket($ticketId, $ticarray);

                    $res = array(
                        'success'=>true
                    );
                    echo json_encode($res);
                }
            }
        }
    }

    function bulkPriority(){
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('priority','Priority','required', array(
            'required' => lang('this_field_is_required')
        ));
        $this->form_validation->set_rules('ticketId[]','Ticket ID','required', array(
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
            $response['msg'] = html_escape('Please correct the errors and try again.');

            echo json_encode($response); 
        } else {
            $priority = $this->input->post('priority', TRUE);
            $ticketID = $this->input->post('ticketId', TRUE);

            foreach($ticketID as $id)
            {
                $data[] = array(
                    'id' => $id,
                    'priority' => $priority
                );
            }

            $res = $this->db->update_batch('tbl_tickets', $data, 'id');

            if($res){
                $array = array(
                    'success'=>true,
                    'priority'=>$priority
                );

                echo json_encode($array);
            }
        }
    }

    function bulkResolve(){
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('resolve','Resolve','required', array(
            'required' => lang('this_field_is_required')
        ));
        $this->form_validation->set_rules('ticketId[]','Ticket ID','required', array(
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
            $response['msg'] = html_escape('Please correct the errors and try again.');

            echo json_encode($response); 
        } else {
            $resolve = $this->input->post('resolve', TRUE);
            $ticketID = $this->input->post('ticketId', TRUE);

            foreach($ticketID as $id)
            {
                $assignedData[] = array(
                    'id' => $id,
                    'resolved' => $resolve
                );
            }

            $res = $this->db->update_batch('tbl_tickets', $assignedData, 'id');

            if($res){
                $def = $resolve == 0 ? 'Pending' : 'Resolved';
                $array = array(
                    'success'=>true,
                    'resolve'=>$def
                );

                echo json_encode($array);
            }
        }   
    }

    function bulkAssign(){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('assignee','Assignee','required', array(
                'required' => lang('this_field_is_required')
            ));
            $this->form_validation->set_rules('ticketId[]','Ticket ID','required', array(
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
                $response['msg'] = html_escape('Please correct the errors and try again.');

                echo json_encode($response); 
            } else {
                $assignee = $this->input->post('assignee', TRUE);
                $ticketID = $this->input->post('ticketId', TRUE);

                foreach($ticketID as $id)
                {
                    $assignedData[] = array(
                        'id' => $id,
                        'assignedTo' => $assignee
                    );
                }

                $res = $this->db->update_batch('tbl_tickets', $assignedData, 'id');

                if($res){
                    $userInfo = $this->user_model->getUserInfoById($assignee);
                    
                    $array = array(
                        'success'=>true,
                        'assignee'=>$userInfo->firstName
                    );

                    echo json_encode($array);
                }
            }
        }
    }

    function assignTicket($id, $owner){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $array = array(
                'assignedTo'=>$owner
            );

            $result = $this->ticket_model->updateTicket($id, $array);

            if($result > 0){
                $info = $this->ticket_model->ticketInfo($id);

                $res = array(
                    'success'=>true,
                    'name'=>$info->supportFirstName
                );
                echo json_encode($res);
            }
        }
    }

    function ticketPriority($id){
        if($this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        } else {
            $priority = $this->input->post('priority', TRUE);

            $array = array(
                'priority'=>$priority
            );

            $result = $this->ticket_model->updateTicket($id, $array);

            if($result > 0){
                $res = array(
                    'success'=>true
                );

                echo json_encode($res);
            }
        }
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}