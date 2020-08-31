<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 07 December 2019
 */
class Languages_model extends CI_Model
{
    function addLanguage($info)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_languages', $info);
        
        $insert_id = $this->db->insert_id();

        if($insert_id > 0){
            $this->db->select('lang_id, module, key, translation');// select your filed
            $this->db->where('lang_id', 1);
            $result = $this->db->get('tbl_translations')->result(); // get result from table
            $translations_array = array();
            foreach($result as $row)
            {
                $translations_array[] = array(
                    'lang_id' => $insert_id,
                    'module' => $row->module,
                    'key' => $row->key,
                    'translation'=> $row->translation
                );
            }
            $this->db->insert_batch('tbl_translations', $translations_array); // insert each row to translations table
        }
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function editLanguage($info, $id)
    {     
          $this->db->where('id', $id);
          $this->db->update('tbl_languages', $info);
          
          return TRUE;
    }

    function getNumLanguages()
    {
        $this->db->select('*');
        $this->db->from('tbl_languages');

        $query = $this->db->get();
        return $query->num_rows();
    }

    function getLang($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_languages');
        $this->db->where('id', $id);

        $query = $this->db->get();
        $result = $query->row();        
        return $result; 
    }

    function getLangByName($name)
    {
        $this->db->select('*');
        $this->db->from('tbl_languages');
        $this->db->where('name', $name);

        $query = $this->db->get();
        $result = $query->row();        
        return $result;
    }

    function getLanguages($page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_languages');

        $this->db->order_by('id', 'ASC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function changeLang($userId, $data)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $data);
        
        return TRUE;
    }

    function all_Languages($currentLang)
    {
        $this->db->select('*');
        $this->db->from('tbl_languages');
        $this->db->where('id !=', $currentLang);

        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function userLang($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_languages as BaseTbl');
        $this->db->join('tbl_users as UserTbl','UserTbl.lang_id = BaseTbl.id','left');
        $this->db->where('UserTbl.userId', $userId);

        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    function getLangModule($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_translations as BaseTbl');
        $this->db->join('tbl_languages as Lang', 'Lang.id = BaseTbl.lang_id','left');
        $this->db->join('tbl_lang_modules as Module', 'Module.id = BaseTbl.module','left');
        $this->db->where('BaseTbl.module', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    function editTranslation($langId, $data)
    {
        $this->db->trans_start();
        $this->db->where('lang_id', $langId);
        $this->db->update_batch('tbl_translations', $data, 'key');
        $this->db->trans_complete();        
        return ($this->db->trans_status() === FALSE)? FALSE:TRUE;
    }

    function getLangModules()
    {
        $this->db->select('*');
        $this->db->from('tbl_lang_modules');

        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function getLangSettings($id, $module)
    {
        $this->db->select('key, translation');
        $this->db->from('tbl_translations');
        $this->db->where('lang_id', $id);
        $this->db->where('module', $module);

        //$this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function firstLangRow()
    {
        $this->db->select('*');
        $this->db->from('tbl_languages as BaseTbl');
        $this->db->order_by('BaseTbl.id', 'ASC');
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }
}