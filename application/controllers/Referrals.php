<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Referrals (ReferralsController)
 * Referrals Class
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Referrals extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('transactions_model');
        $this->load->model('settings_model');
        $this->load->model('email_model');
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
     * This function used to send an invite link to new users
     */
    public function invite()
    {
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email','Email','required|valid_email', array(
            'required' => lang('this_field_is_required'),
            'valid_email' => lang('this_email_is_invalid')
        ));

        if($this->form_validation->run() == FALSE)
        {
            $array = array(
                'success' => false,
                'msg' => html_escape(lang('please_enter_email_of_person_you_want_to_refer_us_to')),
                "csrfTokenName" => $csrfTokenName,
                "csrfHash" => $csrfHash
            );

            echo json_encode($array);
        }
        else
        { 
            $data = $this->user_model->getUserInfo($this->vendorId);
            $name = $data->firstName;
            $refcode = $data->refCode;
            $joinLink = base_url()."signup/".$data->refCode;

            //Send Mail
            $conditionUserMail = array('tbl_email_templates.type'=>'Referral Invitation');
            $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

            $companyInfo = $this->settings_model->getsettingsInfo();
        
            if($resultEmail->num_rows() > 0)
            {
                $rowUserMailContent = $resultEmail->row();
                $splVars = array(
                    "!referrerName" => $name,
                    "!referralLink" => $joinLink,
                    "!companyName" => $companyInfo['name'],
                    "!address" => $companyInfo['address'],
                    "!siteurl" => base_url()
                );

            $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
            $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

            $toEmail = $this->security->xss_clean($this->input->post('email'));
            $fromEmail = $companyInfo['SMTPUser'];

            $name = 'Support';

            $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

            $send = $this->sendEmail($toEmail,$mailSubject,$mailContent);

            if($send == true) {
                $array = array(
                    'success' => true,
                    'msg' => html_escape(lang('your_invitation_has_been_sent_successfully')),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            } else {
                $array = array(
                    'success' => false,
                    'msg' => html_escape(lang('there_is_an_error_in_sending_your_invite_try_again_later')),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
            }           
        }
    }

}