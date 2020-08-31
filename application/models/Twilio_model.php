<?php
defined('BASEPATH') or exit('No direct script access allowed');
// Load the autuloader
require_once FCPATH . '/vendor/autoload.php';
use Twilio\Rest\Client;

class Twilio_model extends CI_Model {
	
	private $sid;
    private $token;
    public function __construct()
    {
        parent::__construct();
        $this->db->select('*');
        $this->db->from('tbl_addons_api');
        $this->db->where('name', 'Twilio');
        $query = $this->db->get();

        //SID and Token Info
        $this->SID = $query->row('public_key');
        $this->token = $query->row('secret_key');

    }

    public function send_sms($phone, $body)
    {
        $this->db->select('*');
        $this->db->from('tbl_settings');
        $this->db->where('type', 'sms_active');
        $result = $this->db->get();

        if($result->row('value') != 0) {
            $this->db->select('*');
            $this->db->from('tbl_settings');
            $this->db->where('type', 'sms_phone');
            $from_number = $this->db->get()->row('value');

            // Reciever's phone number
            $phone_number = $phone;

            // Create Twilio client
            $client = new Client($this->SID, $this->token);

            try{
                $client->messages->create(
                $phone_number,
                  array(
                      'from' => $from_number,
                      'body' => $body
                  )
                );
                //sent successfully
                $response = TRUE;
            }catch(Exception $e){
                //echo $e->getCode() . ' : ' . $e->getMessage()."<br>";
                $response = FALSE;
            }

            return $response;
        }
    }
}