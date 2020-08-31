<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Addons_model (Addons Model)
 * User model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 02 February 2019
 */
class Addons_model extends CI_Model
{
    function get_addon_info($addon)
    {
        $this->db->select('*');
        $this->db->from('tbl_addons_api');
        $this->db->where('name', $addon);

        $query = $this->db->get();
        return $query->row();
    }
}