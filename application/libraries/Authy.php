<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Authy_ci
 *
 * Very basic CodeIgniter library for verify Authy tokens and registering new Authy clients
 * Authy (http://authy.com)
 *
 * @package		Authy_ci
 * @author		Matt Williams
 * @version		1.0.0
 * @license		MIT License Copyright (c) 2013 Matt Williams
 */

class Authy
{
    private $base_url = "http://api.authy.com/protected/json/";    	//SET TO THE APPROPRIATE API URL
    
/**
 * Verify a token with Authy's API
 *
 * @param	string	(user's Authy ID)
 * @param	string	(user's entered Authy token *from their phone*)
 **/
    function verify_token($id,$token,$api_key)
    {
        //build the URL
        $curl = $this->base_url;
        $curl .= "verify/";
        $curl .= $token."/";
        $curl .= $id."?api_key=";
        $curl .= $api_key;
        
	//init the curl
        $curl = curl_init($curl);
        
	//bind response
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		
	//exec curl
        $json_response = curl_exec($curl);

        //close curl
		curl_close($curl);
		
	//decode response
        $response = json_decode($json_response);
        
	//basic check if the token was correct
	//will be exapanding on this later to return other errors, etc...
        if($response->success == 'true')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
	
 /**
 * Register a new phone with Authy for your application, returns an array containg
 * the status of the request (T/F) and the authy_id if true. The authy id should be
 * stored for each user
 *
 * @param	string	(user's Authy ID)
 * @param	string	(cellphone)
 * @param	string	(country calling code eg. 1 => USA)
 **/
    function new_user($email,$cellphone,$country_code,$api_key)
    {
        //build the url
		$curl = $this->base_url;
        $curl .= "users/new?api_key=".$api_key;
        
	//build the Authy API query info
        $acc_info = array(
            'email'         => $email,
            'cellphone'     => $cellphone,
            'country_code'  => $country_code
        );
			
	//encode it
        $datatopost = http_build_query(array('user'=>$acc_info));
        
	//init curl
        $ch = curl_init ($curl);
		
	//set some options
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		
	//exec the curl
        $returndata = curl_exec ($ch);
        
	//decode the response
        $response = json_decode($returndata);
        
	//check if it succeeded and return the authy id, otherwise just return false
        if($response->success == 'true')
        {
            return array('success'=>'true','authy_id'=>$response->user->id);
        }
        return array('success'=>'false');
    }
    
}

/* End of file Authy.php */