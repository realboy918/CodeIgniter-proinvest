<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 07 December 2019
 */
class Perfectmoney_model extends CI_Model
{
    function create($array)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_perfect_money', $array);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function update($array, $id)
    {
        $this->db->trans_start();
        $this->db->where('payment_id', $id);
        $success = $this->db->update('tbl_perfect_money', $array);
        $this->db->trans_complete();  

        return $success;
    }

    function getInfo($payment_Id)
    {
        $this->db->select('*');
        $this->db->from('tbl_perfect_money');
        $this->db->where('payment_id', $payment_Id);
        $query = $this->db->get();
        
        return $query->row();
    }
}