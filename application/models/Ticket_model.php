<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Ticket_model (Ticket Model)
 * Ticket model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 07 December 2019
 */
class Ticket_model extends CI_Model
{
    /**
     * Ticket Actions
     */
    function createTicket($array){
        $this->db->insert('tbl_tickets', $array);

        $insertID = $this->db->insert_id();
        return $insertID;
    }
    
    function updateTicket($key, $array){
        $this->db->select();
        $this->db->where('id', $key);
        $this->db->update('tbl_tickets', $array);

        return $this->db->affected_rows();
    }

    function listTickets($userId, $state, $priority, $page, $segment){
        $this->db->select('BaseTbl.*, Category.*, User.firstName as userFirstName, User.lastName as userLastName, User.email as userEmail, Support.firstName as supportFirstName, Support.lastName as supportLastName, Support.email as supportEmail, Support.ppic as supportppic');
        $this->db->from('tbl_tickets as BaseTbl');
        $this->db->join('tbl_ticket_categories as Category', 'Category.categoryId = BaseTbl.categoryId', 'left');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId', 'left');
        $this->db->join('tbl_users as Support', 'Support.userId = BaseTbl.assignedTo', 'left');
        if($userId != NULL){
            $this->db->where('BaseTbl.userId', $userId);
        }
        if($state != NULL){
            if($state == 'open'){
                $this->db->where('BaseTbl.resolved', 0);
            } else if($state == 'resolved'){
                $this->db->where('BaseTbl.resolved', 1);
            }
        }
        if($priority != NULL){
            $this->db->where('BaseTbl.priority', $priority);
        }
        $this->db->order_by('BaseTbl.createdDtm', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        return $query->result();
    }

    function ticketListingCount($userId, $state, $priority){
        $this->db->select('*');
        $this->db->from('tbl_tickets as BaseTbl');
        $this->db->join('tbl_ticket_categories as Category', 'Category.categoryId = BaseTbl.categoryId', 'left');
        if($userId != NULL){
            $this->db->where('BaseTbl.userId', $userId);
        }
        if($state != NULL){
            if($state == 'open'){
                $this->db->where('BaseTbl.resolved', 0);
            } else if($state == 'resolved'){
                $this->db->where('BaseTbl.resolved', 1);
            }
        }
        if($priority != NULL){
            $this->db->where('BaseTbl.priority', $priority);
        }
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    function ticketInfo($id){
        $this->db->select('BaseTbl.*, Category.*, User.firstName as userFirstName, User.lastName as userLastName, User.email as userEmail, Support.firstName as supportFirstName, Support.lastName as supportLastName, Support.email as supportEmail, Support.ppic as supportppic');
        $this->db->from('tbl_tickets as BaseTbl');
        $this->db->join('tbl_ticket_categories as Category', 'Category.categoryId = BaseTbl.categoryId', 'left');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId', 'left');
        $this->db->join('tbl_users as Support', 'Support.userId = BaseTbl.assignedTo', 'left');
        $this->db->where('BaseTbl.id', $id);

        $query = $this->db->get();

        return $query->row();
    }

    /**
     * Reply Actions
     */

    function reply($array){
        $this->db->insert('tbl_ticket_replies', $array);

        $insertID = $this->db->insert_id();
        return $insertID;
    }

    function listReplies($id, $limit, $segment){
        $this->db->select('*');
        $this->db->where('ticketId', $id);
        $this->db->from('tbl_ticket_replies as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.repliedById', 'left');
        $this->db->limit($limit, $segment);
        $this->db->order_by('BaseTbl.replyId', 'desc');

        $query = $this->db->get();

        return array_reverse($query->result());
    }

    function listRepliesCount($id){
        $this->db->select('*');
        $this->db->where('ticketId', $id);
        $this->db->from('tbl_ticket_replies as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.repliedById', 'left');

        $query = $this->db->get();

        return $query->num_rows();
    }

    function allReplies($id){
        $this->db->select('*');
        $this->db->where('ticketId', $id);
        $this->db->from('tbl_ticket_replies as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.repliedById', 'left');

        $query = $this->db->get();

        return $query->result();
    }
    
    /**
     * Category Actions
     */

    function createCategory($array){
        $this->db->insert('tbl_ticket_categories', $array);

        $insertID = $this->db->insert_id();
        return $insertID;
    }

    function updateCategory($key, $array){
        $this->db->select();
        $this->db->where('categoryId', $key);
        $this->db->update('tbl_ticket_categories', $array);

        return $this->db->affected_rows();
    }

    function listCategories($page, $segment){
        $this->db->select();
        $this->db->from('tbl_ticket_categories');
        $query = $this->db->get();
        
        return $query->result();
    }

    function listCategoriesCount(){
        $this->db->select();
        $this->db->from('tbl_ticket_categories');
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    function deleteCategory($id){
        $this->db->where('categoryId', $id);
        $this->db->delete('tbl_ticket_categories');

        return $this->db->affected_rows();
    }

    function allCategories(){
        $this->db->select();
        $this->db->from('tbl_ticket_categories');
        $query = $this->db->get();
        
        return $query->result();    
    }

    /**
     * Admin Team
     */

     function team(){
         $this->db->select();
         $this->db->where('roleId !=', 3);
         $this->db->from('tbl_users');

         $query = $this->db->get();

         return $query->result();
     }

}