<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 07 December 2019
 */
class Verification_model extends CI_Model
{
    function verification_list($searchText = '', $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_verification  as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $this->db->group_start();
            $this->db->or_like('firstName', $this->db->escape_like_str($searchText));
            $this->db->or_like('lastName', $this->db->escape_like_str($searchText));
            $this->db->or_like('email', $this->db->escape_like_str($searchText));
            $this->db->group_end();
        }
        $this->db->order_by('modifiedDtm', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function verification_list_count($searchText = '')
    {
        $this->db->select('*');
        $this->db->from('tbl_verification  as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $this->db->group_start();
            $this->db->or_like('firstName', $this->db->escape_like_str($searchText));
            $this->db->or_like('lastName', $this->db->escape_like_str($searchText));
            $this->db->or_like('email', $this->db->escape_like_str($searchText));
            $this->db->group_end();
        }
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    function getVerificationInfo($userId){
        $this->db->select();
        $this->db->where('userId', $userId);
        $this->db->from('tbl_verification');

        $query = $this->db->get();

        return $query->row();
    }

    function getVerificationInfoById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_verification');
        $this->db->from('tbl_verification  as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->where('BaseTbl.id', $id);

        $query = $this->db->get();

        $rows = $query->num_rows();

        if($rows > 0){
            return $query->row();
        } else {
            return false;
        }
    }

    function updateInfo($data, $userId)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_verification', $data);
        
        return TRUE;
    }
}