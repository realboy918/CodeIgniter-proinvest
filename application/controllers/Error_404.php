<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Errorr_404 (ErrorController)
 * Error class to redirect on errors
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Error_404 extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->isLoggedIn();
    }
    
    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('login');
        }
        else
        {
            redirect('pageNotFound');
        }
    }
}

?>
