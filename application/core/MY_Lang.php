<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|-------------------------------------------------------------------------------------------------
| Databased Language Extension
|-------------------------------------------------------------------------------------------------
| Author:    Anthony Steiner (better known by just Steiner, lol)
| License:    MIT
|           Edit how you see fit... I do not care about keeping face on this.
| Version:  1.0 (and probably the only version)
| Date:     8/9/2009
|
| Summary:
|           Not everyone on some of the sites we CI junkies create is an
|           admin/developer... hell some might not even have access to
|           change text on the sites...
|
|           So, I came up with a nice extended class that "trumps" the
|           existing load language, so calling the file from the database
|           will be exactly the same as
|           http://codeigniter.com/user_guide/libraries/language.html
|           has written.
|
|           Remember this is just an extension to the original core code
|           so you must put it in the .../application/libraries folder with the
|           file name of "MY_Lang.php".
|           NOTE: "MY_" should be replace with what ever you've listed in your
|           config file under
$config['subclass_prefix'] = 'MY_';
|           for both the filename and class
|
| Dependencies:
|           - CodeIgniter Framework (developed on v1.7.1, not tested on older versions)
|           - PHP 5 (Though can simply change the __construct() function to the same name and
|                   spelling as the class to get it to function in PHP4)
|           - MySQL Database Table named "language" (see below)
|
| SQL to create "language" table:
-- ----------------------------
-- Table structure for language
-- ----------------------------
CREATE TABLE `language`
(
`id` INT(10) NOT NULL auto_increment,
`key` VARCHAR(255) collate utf8_unicode_ci NOT NULL,
`language` VARCHAR(255) collate utf8_unicode_ci NOT NULL DEFAULT 'english',
`set` VARCHAR(255) collate utf8_unicode_ci DEFAULT NULL,
`text` longtext collate utf8_unicode_ci,
PRIMARY KEY (`id`)
)
ENGINE = MyISAM AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci CHECKSUM = 1
| There is a good change, though I have not personally tested, this will work
| with more than just MySQL, though the SQL CREATE above is specifically for
| MySQL.
|
| Unicode was intensional, as there might be more than just english in this
| database and unicode should cover it.
|
|
| When using the database:
|       - key
|           Just like the language_key explained in on the user guide for the original class
|           this is the key in which we will call the single, specific line.
|       - language
|           Same as the original code, the language the record will be in.
|       - set
|           This field is the equivilent to a whole file in the original.
|           You can use "Sets", for instance, if the first 4 records in the database were for
|           your home page, you can make their sets 'home'. Something unqiue to group by.
|       - text
|           The text in which it is calling. You're own back-end coding will be the factor
|           in what is limited here... but as of right now you can throw eval&#40;&#41;s in there and they will work.
|       AND YOU ARE GOOD TO GO!
|
*/

class MY_Lang extends CI_Lang {

    var $language    = array();
    var $is_loaded    = array();
    var $idiom;
    var $set;

    var $line;
    var $CI;

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Load a language file
     *
     * @access    public
     * @param    mixed    the name of the language file to be loaded. Can be an array
     * @param    string    the language (english, etc.)
     * @return    mixed
     */
    function load($langfile = '', $idiom = '', $return = false, $add_suffix = TRUE, $alt_path = '')
    {
        // Calling early before CI reformats them
        $this->set = $langfile;
        $this->idiom = $idiom;

        $langfile = str_replace('.php', '', str_replace('_lang.', '', $langfile)).'_lang'.'.php';

        if (in_array($langfile, $this->is_loaded, TRUE))
        {
            return;
        }

        if ($idiom == '')
        {
            $CI =& get_instance();
            $deft_lang = $CI->config->item('language');
            $idiom = ($deft_lang == '') ? 'english' : $deft_lang;

            $this->idiom = $idiom;
        }

        // Determine where the language file is and load it
        if (file_exists(APPPATH.'language/'.$idiom.'/'.$langfile))
        {
            include(APPPATH.'language/'.$idiom.'/'.$langfile);
        }
        else
        {
            if (file_exists(BASEPATH.'language/'.$idiom.'/'.$langfile))
            {
                include(BASEPATH.'language/'.$idiom.'/'.$langfile);
            }
            else
            {
                $database_lang =  $this->_get_from_db();
                if ( ! empty( $database_lang ) )
                {
                    $lang = $database_lang;
                }else{
                    show_error('Unable to load the requested language file: language/'.$langfile);
                }
            }
        }

        if ( ! isset($lang))
        {
            log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);
            return;
        }

        if ($return == TRUE)
        {
            return $lang;
        }

        $this->is_loaded[] = $langfile;
        $this->language = array_merge($this->language, $lang);
        unset($lang);

        log_message('debug', 'Language file loaded: language/'.$idiom.'/'.$langfile);
        return TRUE;
    }

    /**
     * Load a language from database
     *
     * @access    private
     * @return    array
     */
    private function _get_from_db()
    {
        $CI =& get_instance();

        $CI->db->select('*');
        $CI->db->from('tbl_translations as BaseTbl');
        $CI->db->join('tbl_languages as Lang', 'Lang.id = BaseTbl.lang_id','left');
        $CI->db->join('tbl_lang_modules as Module', 'Module.id = BaseTbl.module','left');
        $CI->db->where('Lang.name', $this->idiom);
        $CI->db->where('Module.code', $this->set);

        $query = $CI->db->get()->result();

        foreach ( $query as $row )
        {
            $return[$row->key] = $row->translation;
        }

        unset($CI, $query);
        return $return;
    }
}