<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

// The following values will probably need to be changed.
$db['default']['username'] = "proinvest";
$db['default']['password'] = "";
$db['default']['database'] = "proinvest";

// The following values can probably stay the same.
$db['default']['hostname'] = "localhost";
$db['default']['dbdriver'] = "mysqli"; //Updated to latest driver.
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";
$db['default']['autoinit'] = FALSE; 

$active_group = "default";
$active_record = TRUE;

/* End of file database.php */
/* Location: ./application/config/database.php */
ini_set('display_errors', 0);

$mysqli_connection = new MySQLi($db['default']['hostname'],
                                $db['default']['username'],
                                $db['default']['password'], 
                                $db['default']['database']);

if ($mysqli_connection->connect_error) {
   //echo "Not connected, error: " . $mysqli_connection->connect_error;
   redirect(base_url().'install');
}
else {
   echo "Connected.";
}
die( 'file: ' .__FILE__ . ' Line: ' .__LINE__);


