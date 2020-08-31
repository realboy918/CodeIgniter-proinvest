<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Home (HomeController)
 * Home class to display the main site
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Home extends BaseController {

	public function __construct()
    {
        parent::__construct();
		$this->load->model('settings_model');
		$this->load->model('transactions_model');
		$this->load->model('email_model');
		$this->load->model('twilio_model');
		$this->load->model('web_model');

		$userLang = $this->session->userdata('site_lang') == '' ?  "english" : $this->session->userdata('site_lang');

		$this->load->helper('language');
		$this->lang->load('common',$userLang);
		$this->lang->load('login',$userLang);
		$this->lang->load('registration',$userLang);
        $this->lang->load('dashboard',$userLang);
        $this->lang->load('transactions',$userLang);
        $this->lang->load('users',$userLang);
        $this->lang->load('plans',$userLang);
        $this->lang->load('email_templates',$userLang);
        $this->lang->load('settings',$userLang);
        $this->lang->load('payment_methods',$userLang);
		$this->lang->load('languages',$userLang);
		$this->lang->load('validation',$userLang);
        $this->lang->load('tickets',$userLang);
    }

	public function index()
	{
		$this->load->model('plans_model');
		$companyInfo = $this->settings_model->getsettingsInfo();
		$data['companyInfo'] = $companyInfo;
		$data["pageTitle"] = $companyInfo['name'];
		$data["plans"] = $this->plans_model->getPlans(1);

		$template = $companyInfo['template'];

		$header = '/siteContent/template'.$template.'/partials/header';
		$view = '/siteContent/template'.$template.'/home';
		$footer = '/siteContent/template'.$template.'/partials/footer';

		$this->load->view($header, $data);
		$this->load->view($view, $data);
		$this->load->view($footer, $data);
	}

	function switchLang($language = "") {
		$language = ($language != "") ? $language : "english";
		$this->session->set_userdata('site_lang', $language);
		$array = array(
			"success"=>true
		);

		echo json_encode($array);
	}

	public function error_404()
	{
		$data['pageTitle'] = 'Error 404';
		$this->load->model('settings_model');
		$this->load->view('404', $data);
	}

	public function faqs()
	{
		$data['pageTitle'] = 'FAQs';
		$data['companyInfo'] = $this->settings_model->getsettingsInfo();

		$data['faqs'] = $this->web_model->listFaqs();

		$template = $data['companyInfo']['template'];

		$header = '/siteContent/template'.$template.'/partials/header';
		$view = '/siteContent/template'.$template.'/faq';
		$footer = '/siteContent/template'.$template.'/partials/footer';

		$this->load->view($header, $data);
		$this->load->view($view, $data, NULL);
		$this->load->view($footer, $data);
	}

	public function terms()
	{
		$data['pageTitle'] = 'Terms';
		$data['companyInfo'] = $this->settings_model->getsettingsInfo();

		$data['content'] = $this->web_model->getTemplateContent('terms', $data['companyInfo']['template']);

		$template = $data['companyInfo']['template'];

		$header = '/siteContent/template'.$template.'/partials/header';
		$view = '/siteContent/template'.$template.'/terms';
		$footer = '/siteContent/template'.$template.'/partials/footer';

		$this->load->view($header, $data);
		$this->load->view($view, $data, NULL);
		$this->load->view($footer, $data);
	}

	public function privacy()
	{
		$data['pageTitle'] = 'Privacy';
		$data['companyInfo'] = $this->settings_model->getsettingsInfo();

		$data['content'] = $this->web_model->getTemplateContent('policy', $data['companyInfo']['template']);

		$template = $data['companyInfo']['template'];

		$header = '/siteContent/template'.$template.'/partials/header';
		$view = '/siteContent/template'.$template.'/privacy';
		$footer = '/siteContent/template'.$template.'/partials/footer';

		$this->load->view($header, $data);
		$this->load->view($view, $data, NULL);
		$this->load->view($footer, $data);
	}
	public function contact_us()
	{
		$this->load->helper(array('form', 'url'));

		//Validation
		$this->load->library('form_validation'); 
		  
        $this->form_validation->set_rules('name','First Name','trim|required', array(
            'required' => lang('this_field_is_required')
        ));
        $this->form_validation->set_rules('email','Email','trim|required|valid_email', array(
            'required' => lang('this_field_is_required'),
            'valid_email' => lang('this_email_is_invalid')
        ));
        $this->form_validation->set_rules('subject','subject','required', array(
            'required' => lang('this_field_is_required')
        ));
		$this->form_validation->set_rules('comment','comment','required', array(
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
                $response['success'] = false;
                $response['msg'] = html_escape(lang('please_correct_errors_and_try_again'));

                echo json_encode($response); 
			}
        }
		else
		{
			$name = $this->input->post('name', TRUE);
			$email = $this->input->post('email', true);
			$subject = $this->input->post('subject', TRUE);
			$message = $this->input->post('comment', TRUE);

			$companyInfo = $this->settings_model->getsettingsInfo();

			$mailSubject = 'New Enquiry About ' . $companyInfo['name'];
			$mailContent = 'Full Name: '.$name.'<br>'.'Email Address: '.$email.'<br>'.'Subject: '.$subject.'<br>'.'Message: '.$message; 	

			$toEmail = $companyInfo['email'];
			$fromEmail = $companyInfo['email'];

			$name = 'Support';

			$header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

			$send = $this->Email($toEmail,$mailSubject,$mailContent);

			if($send == true) {
				$this->session->set_flashdata('success', lang('your_message_has_been_sent_successfully'));
				$array = array(
					'success' => true,
					'msg' => html_escape(lang('your_message_has_been_sent_successfully')),
				);
	
				echo json_encode($array);
			} else {
				$this->session->set_flashdata('success', lang('your_message_has_not_been_sent_successfully'));
				$array = array(
					'success' => true,
					'msg' => html_escape(lang('your_message_has_not_been_sent_successfully')),
				);
	
				echo json_encode($array);
			}
		}
		if (!$this->input->is_ajax_request()) {
		redirect('/#contact');
		}
	}
	
	function earningsEmails(){
        //Get earnings where emails have not been sent
        $type = 0; //Type 0 are unsent emails 1 are sent email
        $pendingEmails = $this->transactions_model->getEarningsEmails($type);
        foreach($pendingEmails as $client){
            //Send Mail
            $conditionUserMail = array('tbl_email_templates.type'=>'Earnings Email');
            $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);
            $companyInfo = $this->settings_model->getsettingsInfo();
        
            if($resultEmail->num_rows() > 0)
            {
                $rowUserMailContent = $resultEmail->row();
                $splVars = array(
                    "!clientName" => $client->firstName,
                    "!amount" => to_currency($client->amount),
                    "!ref" => $client->txnCode,
                    "!companyName" => $companyInfo['name'],
                    "!address" => $companyInfo['address'],
                    "!siteurl" => base_url()
                );

                $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                $toEmail = $client->email;
                $fromEmail = $companyInfo['SMTPUser'];

                $name = 'Support';

                $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                $send = $this->sendEmail($toEmail,$mailSubject,$mailContent);

				$array = array(
					'email_sent' => '1',
				);
				
				$resultEarnings =$this->transactions_model->editEarning($client->txnCode, $array);
            }
		}
		$array = array(
			'success' => true,
			'msg' => html_escape("Cronjob succesful"),
		);

		echo json_encode($array);
	}
}