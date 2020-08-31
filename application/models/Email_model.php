<?php

class Email_model extends CI_Model {
	
	
	//Constructor
	
	function __construct()
	{
		parent::__construct();
    }//Controller End
	
	// --------------------------------------------------------------------
		
	/**
	* Get Email settings from database
	*
    * @access	private
    * @param	nil
    * @return	array payment settings informations in array format
	*/
	function getEmailSettings($conditions=array())
	{
        if(count($conditions)>0)
        $this->db->where($conditions);
	
		$this->db->from('tbl_email_templates');
		$this->db->select('*');
		$result = $this->db->get();
		return $result;
			
    }//End of getEmailSettings Function

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getEmailInfoById($emailId)
    {
        $this->db->select('*');
        $this->db->from('tbl_email_templates');
        $this->db->where('id', $emailId);
        $query = $this->db->get();
        
        return $query->row();
    }
    

    /**
     * This function is used to get the email listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function emailListingCount($searchText = '')
    {
        $this->db->select('*');
        $this->db->from('tbl_email_templates as BaseTbl');

        if(!empty($searchText)) {
            $this->db->or_like('BaseTbl.type', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.mail_subject', $this->db->escape_like_str($searchText));
        }

        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * This function is used to get email templates per page count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function emails($searchText = '', $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_email_templates as BaseTbl');

        if(!empty($searchText)) {
            $this->db->or_like('BaseTbl.type', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.mail_subject', $this->db->escape_like_str($searchText));
        }

        $this->db->order_by('BaseTbl.id', 'ASC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function firstEmailRow()
    {
        $this->db->select('*');
        $this->db->from('tbl_email_templates as BaseTbl');
        $this->db->order_by('BaseTbl.id', 'ASC');
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }
	
		
	/**
	* Add Email Settings
	*
    * @access	private
    * @param	array	an associative array of insert values
    * @return	void
	*/
	function addEmailSettings($insertData=array())
	{
		$this->db->insert('tbl_email_templates', $insertData);
		return;
	}//End of addEmailSettings Function
	// --------------------------------------------------------------------
	
	/**
	* delete Email Settings
	*
    * @access	private
    * @param	array	an associative array of insert values
    * @return	void
	*/
	function deleteEmailSettings($condition=array())
	{
	    if(isset($condition) and count($condition) > 0)
        $this->db->where($condition);
		
		$this->db->delete('email_templates');
		return;
	}//End of deleteEmailSettings Function
	//------------------------------------------------------------------------
	
	/**
	* Update Email Settings
	*
    * @access	private
    * @param	array	an associative array of insert values
    * @return	void
	*/
	function updateEmailSettings($emailInfo, $emailId)
	{     
        $this->db->where('id', $emailId);
        $this->db->update('tbl_email_templates', $emailInfo);
        
        return TRUE;
	}//End of updateEmailSettings Function
	
	function sendHtmlMail($to ='',$from ='',$subject='',$message='',$cc='')
	{
        $this->db->from('tbl_settings');
		$this->db->select('*');
        $query = $this->db->get();
        if ($query->num_rows()) {
            foreach ($query->result_array() as $row) {
            // Your data is coming from multiple rows, so need to loop on it.
              $siteData[$row['type']] = $row['value'];
            }
          }
        $result = $siteData;

        if($result['email_active'] != 0)
        {
            $config = array();
            /*SMTP Email Only 1 should be active */
            $config['protocol']    = $result['SMTPProtocol'];
            $config['smtp_host']   = $result['SMTPHost'];
            $config['smtp_port']   = $result['SMTPPort'];
            $config['smtp_user']   = $result['SMTPUser'];
            $config['smtp_pass']   = $result['SMTPPass'];
            $config['smtp_timeout'] = '30';
            /*sendmail email */
            /*
            $config['protocol'] = 'sendmail';
            */
            $config['mailtype'] = 'html';
            $config['charset']  = 'utf-8';
            $config['wordwrap'] = TRUE;
            $config['crlf'] = "\r\n";
            $config['newline'] = "\r\n";
            /*
            $config['mailpath'] = '/usr/sbin/sendmail';
            */
            // load Email Library
            /* Codeigniter library
            $this->load->library('email');
            $this->email->initialize($config);
            $this->email->to($to);
            $this->email->from($from);
            $this->email->cc($cc);
            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->email->send())
            {
            return true;
            } else {
            return false;
            }
            */
            $this->load->library('Mailer');  //PHPMailer library
            $config = array(
                'smtp_host' => $result['SMTPHost'],
                'smtp_user' => $result['SMTPUser'],
                'smtp_pass' => $result['SMTPPass'],
                'smtp_port' => $result['SMTPPort'],
                'name'      => $result['name'],
                'from'      => $from,
                'to'        => $to,
                'replyto'   => $from,
                'subject'   => $subject,
                'msg'       => $message
            );
            $sendmail = new Mailer($config);

            if ($sendmail->send())
            {
                return true;
            } else {
                return false;
            }
        } else
        {
            return false;
        }
	} //End of sendHtmlMail Function
}
// End Email_model Class
/* End of file Email_model.php */
/* Location: ./application/models/Email_model.php */