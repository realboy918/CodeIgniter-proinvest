<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Plans_model (Plans Model)
 * User model class to get to handle user related data 
 * @author : Axis 96
 * @version : 1.1
 * @since : 07 December 2019
 */
class Plans_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function planListingCount($searchText = '')
    {
        $this->db->select('id, name, minInvestment, maxInvestment, intAfterMaturity, principalReturn, profit, period, createdBy, createdDtm');
        $this->db->from('tbl_plans');
        if(!empty($searchText)) {
            $this->db->like('name', $this->db->escape_like_str($searchText));
        }
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function getPlanById($planId)
    {
        $this->db->select('*');
        $this->db->from('tbl_plans');
        $this->db->where('id =', $planId);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function plans($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.id, Maturity.maturity_desc, Period.periodName, BaseTbl.name, BaseTbl.minInvestment, BaseTbl.maxInvestment, BaseTbl.intAfterMaturity, BaseTbl.principalReturn, BaseTbl.profit, BaseTbl.period, BaseTbl.clientDisplay, BaseTbl.maturity, BaseTbl.createdBy, BaseTbl.createdDtm');
        $this->db->from('tbl_plans as BaseTbl');
        $this->db->join('tbl_periods as Period', 'Period.id = BaseTbl.period','left');
        $this->db->join('tbl_periods as Maturity', 'Maturity.id = BaseTbl.maturity','left');
        if(!empty($searchText)) {
            $this->db->like('name', $this->db->escape_like_str($searchText));
        }
        $this->db->order_by('createdDtm', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewPlan($planInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_plans', $planInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editPlan($planInfo, $planId)
    {
        $this->db->where('id', $planId);
        $this->db->update('tbl_plans', $planInfo);
        
        return TRUE;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getPlanInfo($planId)
    {
        $this->db->select('*');
        $this->db->from('tbl_plans');
        $this->db->where('id', $planId);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getPeriod($planId)
    {
        $this->db->select('*');
        $this->db->from('tbl_plans as BaseTbl');
        $this->db->join('tbl_periods as Period', 'Period.id = BaseTbl.period','left');
        $this->db->where('BaseTbl.id', $planId);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getMaturity($planId)
    {
        $this->db->select('*');
        $this->db->from('tbl_plans as BaseTbl');
        $this->db->join('tbl_periods as Period', 'Period.id = BaseTbl.maturity','left');
        $this->db->where('BaseTbl.id', $planId);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deletePlan($planId)
    {
        $this->db->where('id', $planId);
        $this->db->delete('tbl_plans');
        
        return $this->db->affected_rows();
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function getPlans($display = null)
    {
        $this->db->select('BaseTbl.id, Maturity.maturity_desc, Period.periodName, BaseTbl.name, BaseTbl.minInvestment, BaseTbl.maxInvestment, BaseTbl.intAfterMaturity, BaseTbl.principalReturn, BaseTbl.profit, BaseTbl.period, BaseTbl.clientDisplay, BaseTbl.maturity, BaseTbl.createdBy, BaseTbl.createdDtm');
        $this->db->from('tbl_plans as BaseTbl');
        $this->db->join('tbl_periods as Period', 'Period.id = BaseTbl.period','left');
        $this->db->join('tbl_periods as Maturity', 'Maturity.id = BaseTbl.maturity','left');
        if($display != null){
        $this->db->where('clientDisplay', $display);
        }
        $this->db->order_by('minInvestment', 'ASC');
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function minInvest()
    {
        $this->db->select('*'); 
        $this->db->from('tbl_plans');
        $this->db->order_by('minInvestment', 'ASC');
        //$this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row();
          } else {
            return null;
          }
    }

    function getAllPlans()
    {
        $this->db->select('*');
        $this->db->from('tbl_plans');
        $query = $this->db->get();
        
        return $query->num_rows();
    }

}