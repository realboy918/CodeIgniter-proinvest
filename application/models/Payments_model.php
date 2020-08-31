<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Payments_model (Payments Model)
 * User model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 07 December 2019
 */
class Payments_model extends CI_Model
{
    function listingCount($table, $searchText = '')
    {
        $this->db->select('*');
        $this->db->from($table);
        if(!empty($searchText)) {
            $this->db->or_like('name', $this->db->escape_like_str($searchText));
        }
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    function getAll($table, $searchText = '', $page = NULL, $segment = NULL)
    {
        $this->db->select('*');
        $this->db->from($table);
        if(!empty($searchText)) {
            $this->db->or_like('name', $this->db->escape_like_str($searchText));
        }
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function getPaymentMethods($status)
    {
        $this->db->select('*');
        $this->db->from('tbl_payment_methods');
        $this->db->where('status =', $status);
        $query = $this->db->get();
        
        return $query->result();  
    }

    function getAPIStatus($method)
    {
        $this->db->select('BaseTbl.id, BaseTbl.API, API.status, BaseTbl.name, API.name, API.id');
        $this->db->from('tbl_payment_methods as BaseTbl');
        $this->db->join('tbl_addons_api as API', 'API.id = BaseTbl.API','left');
        $this->db->where('BaseTbl.name =', $method);
        $query = $this->db->get();

        return $query->row(); 
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */

    function getAllPaymentAPIs()
    {
        $this->db->select('*');
        $this->db->from('tbl_addons_api');
        $this->db->where('type', 'payment');
        $query = $this->db->get();
        
        return $query->result();  
    }

    function getAllPaymentMethods()
    {
        $this->db->select('*');
        $this->db->from('tbl_payment_methods');
        $query = $this->db->get();
        
        return $query->result();  
    }

    function getAllPeriods()
    {
        $this->db->select('*');
        $this->db->from('tbl_periods');
        $query = $this->db->get();
        
        return $query->result();  
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function getInfo($table, $method)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('name =', $method);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getInfoById($table, $id)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('id =', $id);
        $query = $this->db->get();
        
        return $query->row();
    }

    function editInfo($table, $info, $id)
    {
        $this->db->where('id', $id);
        $this->db->update($table, $info);
        
        return TRUE;
    }

    function addPaypal($info)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_paypal', $info);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function editPaypalInfo($info, $id)
    {
        $this->db->where('txn_id', $id);
        $this->db->update('tbl_paypal', $info);
        
        return TRUE;
    }

    function getPaypalPayment($payId, $status)
    {
        $this->db->select('*');
        $this->db->from('tbl_paypal');
        $this->db->where('txn_id =', $payId);
        $this->db->where('payment_status =', $status);
        $query = $this->db->get();
        
        return $query->row(); 
    }

    function addCoinPayment($info)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_coinpayments', $info);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function getCoinPayment($invoice)
    {
        $this->db->select('*');
        $this->db->from('tbl_coinpayments');
        $this->db->where('invoice =', $invoice);
        $query = $this->db->get();
        
        return $query->row(); 
    }

    function paymentCheck($userId, $amount, $method, $date, $status)
    {
        $this->db->select('*');
        $this->db->from('tbl_coinpayments');
        $this->db->where('userid =', $userId);
        $this->db->where('amount1 =', $amount);
        $this->db->where('method =', $method);
        $this->db->where('expiry >', $date);
        $this->db->where('status =', $status);

        $query = $this->db->get();

        return $query->row();
    }

    function getWithdrawalMethods()
    {
        $this->db->select('*');
        $this->db->from('tbl_payment_methods');
        $this->db->where('API !=', 1);
        $query = $this->db->get();
        
        return $query->result();  
    }

    function addPaymentMethod($info)
    {
        $this->db->insert('tbl_payment_methods', $info);
        
        $insert_id = $this->db->insert_id();
        
        return $insert_id;
    }

    function getPaymentMethodInfoById($id)
    {
        $this->db->select('BaseTbl.logo as logo, BaseTbl.*, API.name as Aname');
        $this->db->from('tbl_payment_methods as BaseTbl');
        $this->db->join('tbl_addons_api as API', 'API.id = BaseTbl.API','left');
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();
        
        return $query->row();
    }

    function deletePayMethod($array)
    {
        $this->db->delete('tbl_payment_methods', $array);

        return TRUE;
    }

    function getAPIStatusById($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.API, BaseTbl.type, API.status as apistatus, BaseTbl.name, API.name, API.id');
        $this->db->from('tbl_payment_methods as BaseTbl');
        $this->db->join('tbl_addons_api as API', 'API.id = BaseTbl.API','left');
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();

        return $query->row(); 
    }

    function getPaymentMethodById($id){
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->from('tbl_payment_methods');
        $query = $this->db->get();

        return $query->row();
    }

    function getAPIById($id){
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->from('tbl_addons_api');
        $query = $this->db->get();

        return $query->row();
    }
}