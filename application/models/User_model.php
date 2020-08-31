<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 07 December 2019
 */
class User_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($searchText = '', $role = '')
    {
        $this->db->select('BaseTbl.*, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId', $role);
        if(!empty($searchText)) {
            $this->db->group_start();
            $terms = explode(' ', $searchText);
            if(count($terms)>1){
                $this->db->or_like('BaseTbl.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.lastName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('BaseTbl.firstName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('BaseTbl.lastName', $this->db->escape_like_str($terms[0]));
            }else{
                $this->db->or_like('BaseTbl.email', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.lastName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.mobile', $this->db->escape_like_str($terms[0]));
            }
            $this->db->group_end();
        }
        
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    function getAllUsers($startDate = '', $role, $endDate)
    {
        $this->db->select('*');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->where('BaseTbl.roleId', $role);
        $this->db->where('isDeleted', 0);
        if($startDate != ''){
            $this->db->where('BaseTbl.createdDtm >=', $startDate);
        }
        if($endDate != ''){
            $this->db->where('BaseTbl.createdDtm <=', $endDate);
        }   
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    function lastLogin()
    {
        $this->db->select('*'); 
        $this->db->from('ci_sessions');
        $this->db->order_by('ci_sessions.timestamp', 'DESC');
        $this->db->limit(1);
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
    function users($searchText = '', $page, $segment, $role)
    {
        
        $this->db->select('BaseTbl.*, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId', $role);
        
        if(!empty($searchText)) {
            $this->db->group_start();
            $terms = explode(' ', $searchText);
            if(count($terms)>1){
                $this->db->or_like('BaseTbl.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.lastName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('BaseTbl.firstName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('BaseTbl.lastName', $this->db->escape_like_str($terms[0]));
            }else{
                $this->db->or_like('BaseTbl.email', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.lastName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.mobile', $this->db->escape_like_str($terms[0]));
            }
            $this->db->group_end();
        }
        
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    
    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $userId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_users");
        $this->db->where("email", $email);   
        $this->db->where("isDeleted", 0);
        if($userId != 0){
            $this->db->where("userId !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }
    
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($array)
    {
        $this->db->insert('tbl_users', $array);
        
        $insert_id = $this->db->insert_id();
        
        return $insert_id;
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewManager($userInfo, $permissions)
    {
        $this->db->trans_start();
        $success = $this->db->insert('tbl_users', $userInfo);
        $userID = $this->db->insert_id();

        //We have either inserted a new employee, now lets set permissions. 
        if($success)
        {            
            //First lets clear out any permissions the employee currently has.
            $success=$this->db->delete('tbl_permissions', array('person_id' => $userID));
            
            //Now insert the new permissions
            if($success)
            {
                foreach($permissions as $permission)
                {
                    list($module, $action) = explode('|', $permission);
                    $success = $this->db->insert('tbl_permissions',
                    array(
                    'module_id'=>$module,
                    'action_id'=>$action,
                    'person_id'=>$userID));
                }
            }            
        }
        
        $this->db->trans_complete();  

        return $success;
    }

    function editManager($userInfo, $permissions, $userID)
    {
        $this->db->trans_start();
        $this->db->where('userId', $userID);
        $success = $this->db->update('tbl_users', $userInfo);

        //We have either updated a new employee, now lets set permissions. 
        if($success)
        {            
            //First lets clear out any permissions the employee currently has.
            $success=$this->db->delete('tbl_permissions', array('person_id' => $userID));
            
            //Now insert the new permissions
            if($success)
            {
                foreach($permissions as $permission)
                {
                    list($module, $action) = explode('|', $permission);
                    $success = $this->db->insert('tbl_permissions',
                    array(
                    'module_id'=>$module,
                    'action_id'=>$action,
                    'person_id'=>$userID));
                }
            }            
        }
        $this->db->trans_complete();  

        return $success;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
		$this->db->where('roleId !=', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getPermissions($module_id, $action_id, $person_id)
    {
        //if no module_id is null, allow access
		if($module_id==null)
		{
			return true;
		}
		
		static $cache;
		
		if (isset($cache[$module_id.'|'.$action_id.'|'.$person_id]))
		{
			return $cache[$module_id.'|'.$action_id.'|'.$person_id];
		}
		
		
		$query = $this->db->get_where('tbl_permissions', array('person_id' => $person_id,'module_id'=>$module_id,'action_id'=>$action_id), 1);
		$cache[$module_id.'|'.$action_id.'|'.$person_id] =  $query->num_rows() == 1;
		return $cache[$module_id.'|'.$action_id.'|'.$person_id];
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);
        
        return $this->db->affected_rows();
    }


    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('userId, password');
        $this->db->where('userId', $userId);        
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');
        
        $user = $query->result();

        if(!empty($user)){
            if(verifyHashedPassword($oldPassword, $user[0]->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);
        
        return $this->db->affected_rows();
    }


    /**
     * This function is used to get user login history
     * @param number $userId : This is user id
     */
    function loginHistoryCount($userId, $searchText, $fromDate, $toDate)
    {
        $this->db->select('BaseTbl.*');
        if(!empty($searchText)) {
            $this->db->group_start();
            $this->db->or_like('BaseTbl.sessionData', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.machineIp', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.userAgent', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.agentString', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.platform', $this->db->escape_like_str($searchText));
            $this->db->group_end();
        }
        if($userId >= 1){
            $this->db->where('BaseTbl.userId', $userId);
        }
        $this->db->from('tbl_last_login as BaseTbl');
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    /**
     * This function is used to get user login history
     * @param number $userId : This is user id
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function loginHistory($userId, $searchText, $fromDate, $toDate, $page, $segment)
    {
        $this->db->select('BaseTbl.*');
        $this->db->from('tbl_last_login as BaseTbl');
        if(!empty($searchText)) {
            $this->db->group_start();
            $this->db->or_like('BaseTbl.sessionData', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.machineIp', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.userAgent', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.agentString', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.platform', $this->db->escape_like_str($searchText));
            $this->db->group_end();
        }
        if($userId >= 1){
            $this->db->where('BaseTbl.userId', $userId);
        }
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfoById($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function getUserInfoWithRole($userId)
    {
        $this->db->select('BaseTbl.*, Roles.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.userId', $userId);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function getReferralId($refcode)
    {
        $this->db->select('BaseTbl.*, Roles.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.refCode', $refcode);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getReferrerID($userID)
    {
        $this->db->select('*');
        $this->db->from('tbl_referrals as BaseTbl');
        $this->db->where('BaseTbl.referredId', $userID);
        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->row()->referrerId;
          } else {
            return null;
          }
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addReferral($referralInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_referrals', $referralInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function removeUser($id, $array){
        $this->db->trans_start();
        $this->db->select('*');
        $this->db->where('userId', $id);
        $this->db->update('tbl_users', $array);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return FALSE;
        } else if($this->db->trans_status() === TRUE){
            return TRUE;
        }
    }
}

  