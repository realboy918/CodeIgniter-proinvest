<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 07 December 2019
 */
class Payeer_model extends CI_Model
{
    function create($array)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_payeer', $array);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function update($array, $id)
    {
        $this->db->trans_start();
        $this->db->where('order_id', $id);
        $success = $this->db->update('tbl_payeer', $array);
        $this->db->trans_complete();  

        return $success;
    }

    function getInfo($payment_Id)
    {
        $this->db->select('*');
        $this->db->from('tbl_payeer');
        $this->db->where('order_id', $payment_Id);
        $query = $this->db->get();
        
        return $query->row();
    }
}