<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Paystack_model (Paystack Model)
 * User model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 07 December 2019
 */
class Paystack_model extends CI_Model
{
    function paystackCreate($data)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_paystack', $data);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function checkPaystackRef($ref){
        $this->db->select('*');
        $this->db->from('tbl_paystack');
        $this->db->where('ref', $ref);

        $query = $this->db->get();

        $result = $query->row();        
        return $result;
    }

    function editPaystack($paystackData, $ref){
        $this->db->where('ref', $ref);
        $this->db->update('tbl_paystack', $paystackData);
        
        $result = $this->db->affected_rows();
        
        return $result;
    }
}