<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 07 December 2019
 */
class Web_model extends CI_Model
{
    function templatesListingCount(){
        $this->db->select('*');
        $this->db->from('tbl_templates');
        
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    function allTemplates($page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_templates');

        $this->db->order_by('id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();  

        return $result;
    }

    function faqListingCount($searchText = ''){
        $this->db->select('*');
        $this->db->from('tbl_faqs');
        if(!empty($searchText)) {
            $this->db->group_start();
            $terms = explode(' ', $searchText);
            if(count($terms)>1){
                $this->db->or_like('title', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('answer', $this->db->escape_like_str($terms[1]));
            }else{
                $this->db->or_like('title', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('answer', $this->db->escape_like_str($terms[0]));
            }
            $this->db->group_end();
        }
        
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    function allFaqs($searchText = '', $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_faqs');
        if(!empty($searchText)) {
            $this->db->group_start();
            $terms = explode(' ', $searchText);
            if(count($terms)>1){
                $this->db->or_like('title', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('answer', $this->db->escape_like_str($terms[1]));
            }else{
                $this->db->or_like('title', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('answer', $this->db->escape_like_str($terms[0]));
            }
            $this->db->group_end();
        }
        $this->db->order_by('id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();  

        return $result;
    }

    function listFaqs()
    {
        $this->db->select('*');
        $this->db->from('tbl_faqs');
        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();
        
        $result = $query->result();  

        return $result;
    }

    function createFaq($array){
        $this->db->insert('tbl_faqs', $array);

        $insertID = $this->db->insert_id();
        return $insertID;
    }

    function editFaq($key, $array){
        $this->db->select();
        $this->db->where('id', $key);
        $this->db->update('tbl_faqs', $array);

        return $this->db->affected_rows();
    }

    function deleteFaq($id){
        $success = $this->db->delete('tbl_faqs', array('id' => $id));
        return $success;
    }

    function editContent($array, $key){
        $this->db->select('*');
        $this->db->where('name', $key);
        $this->db->update('tbl_content', $array);

        return $this->db->affected_rows();
    }

    function getContent($key){
        $this->db->select('*');
        $this->db->where('name', $key);
        $this->db->from('tbl_content');

        $query = $this->db->get(); 

        return $query->row();
    }

    function getAllContent($template){
        $this->db->select('*');
        $this->db->where('template', $template);
        $this->db->from('tbl_content');

        $query = $this->db->get(); 

        return $query->result();
    }

    function getTemplateContent($key, $template){
        $this->db->select('*');
        $this->db->where('name', $key);
        $this->db->where('template', $template);
        $this->db->from('tbl_content');

        $query = $this->db->get(); 

        return $query->row();
    }
}