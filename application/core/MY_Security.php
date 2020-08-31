<?php
/**
 * This funcion is used to redirect the page to the previous page due to CSRF errors
**/
class MY_Security extends CI_Security {

public function __construct()
{
parent::__construct();
}

public function csrf_show_error()
{
header('Location: ' . htmlspecialchars($_SERVER['REQUEST_URI']), TRUE, 200);
}
}