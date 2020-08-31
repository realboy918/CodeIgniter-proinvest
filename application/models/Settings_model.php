<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Settings_model (Settings Model)
 * User model class to get to handle user related data 
 * @author : Axis96 
 * @version : 1.1
 * @since : 07 December 2019
 */
class Settings_model extends CI_Model
{
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getSettingsInfo()
    {
        $this->db->select('*');
        $this->db->from('tbl_settings');
        $query = $this->db->get();
        if ($query->num_rows()) {
            foreach ($query->result_array() as $row) {
            // Your data is coming from multiple rows, so need to loop on it.
              $siteData[$row['type']] = $row['value'];
            }
          }
          return $siteData;
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    public function insert($data = array()){
      if(!array_key_exists("updatedDtm",$data)){
          $data['updatedDtm'] = date("Y-m-d H:i:s");
      }
      $insert = $this->db->insert($this->tableName,$data);
      if($insert){
          return $this->db->insert_id();
      }else{
          return false;    
      }
  }
}
