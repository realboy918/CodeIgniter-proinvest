<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * MY_Migration class.
 *
 * @extends CI_Migration
 */
class MY_Migration extends CI_Migration
{
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		//Reload config
		if ($this->db->table_exists('app_config'))
		{
			$this->load->model('Appconfig');
			foreach($this->Appconfig->get_all()->result() as $app_config)
			{
				$this->config->set_item($app_config->key,$app_config->value);
			}
		}	
		
		set_time_limit(0);
		ini_set('max_input_time','-1');
		ini_set('max_execution_time', 0);
		
		if ($this->input->is_cli_request() && get_class($this)!='MY_Migration')
		{
			echo "\n\n".date('m/d/Y h:i:s')." - Running: ".get_class($this)."\n";
			
		}
	}
	public function get_migration_number($migration)
	{
		return $this->_get_migration_number($migration);
	}
	
	public function get_migration_version()
	{
		return $this->_migration_version;
	}
	
	public function get_version()
	{
		return $this->_get_version();
	}
	
	/**
	 * Migrate to a schema version
	 *
	 * Calls each migration step required to get to the schema version of
	 * choice
	 * ADDS the below
	 * If we already have target version then we don't need to migrate anymore. This is in the case of database/database.sql initial setup
	 *
	 * @param	string	$target_version	Target schema version
	 * @return	mixed	TRUE if no migrations are found, current version string on success, FALSE on failure
	 */
	public function version($target_version)
	{
		// Note: We use strings, so that timestamp versions work on 32-bit systems
		$current_version = $this->_get_version();

		if ($this->_migration_type === 'sequential')
		{
			$target_version = sprintf('%03d', $target_version);
		}
		else
		{
			$target_version = (string) $target_version;
		}

		$migrations = $this->find_migrations();

		if ($target_version > 0 && ! isset($migrations[$target_version]))
		{
			$this->_error_string = sprintf($this->lang->line('migration_not_found'), $target_version);
			return FALSE;
		}

		if ($target_version > $current_version)
		{
			$method = 'up';
		}
		elseif ($target_version < $current_version)
		{
			$method = 'down';
			// We need this so that migrations are applied in reverse order
			krsort($migrations);
		}
		else
		{
			// Well, there's nothing to migrate then ...
			return TRUE;
		}

		// Validate all available migrations within our target range.
		//
		// Unfortunately, we'll have to use another loop to run them
		// in order to avoid leaving the procedure in a broken state.
		//
		// See https://github.com/bcit-ci/CodeIgniter/issues/4539
		$pending = array();
		foreach ($migrations as $number => $file)
		{
			// Ignore versions out of our range.
			//
			// Because we've previously sorted the $migrations array depending on the direction,
			// we can safely break the loop once we reach $target_version ...
			if ($method === 'up')
			{
				if ($number <= $current_version)
				{
					continue;
				}
				elseif ($number > $target_version)
				{
					break;
				}
			}
			else
			{
				if ($number > $current_version)
				{
					continue;
				}
				elseif ($number <= $target_version)
				{
					break;
				}
			}

			// Check for sequence gaps
			if ($this->_migration_type === 'sequential')
			{
				if (isset($previous) && abs($number - $previous) > 1)
				{
					$this->_error_string = sprintf($this->lang->line('migration_sequence_gap'), $number);
					return FALSE;
				}

				$previous = $number;
			}

			include_once($file);
			$class = 'Migration_'.ucfirst(strtolower($this->_get_migration_name(basename($file, '.php'))));

			// Validate the migration file structure
			if ( ! class_exists($class, FALSE))
			{
				$this->_error_string = sprintf($this->lang->line('migration_class_doesnt_exist'), $class);
				return FALSE;
			}
			elseif ( ! is_callable(array($class, $method)))
			{
				$this->_error_string = sprintf($this->lang->line('migration_missing_'.$method.'_method'), $class);
				return FALSE;
			}

			$pending[$number] = array($class, $method);
		}

		// Now just run the necessary migrations
		foreach ($pending as $number => $migration)
		{
			log_message('debug', 'Migrating '.$method.' from version '.$current_version.' to version '.$number);

			$migration[0] = new $migration[0];
			call_user_func($migration);
			$current_version = $number;
			$db_version = $this->_get_version();
			
			//If we already have latest version then we don't need to migrate anymore. This is in the case of database/database.sql initial setup
			if ($db_version == $this->_migration_version)
			{
				break;
			}
			
			$this->_update_version($current_version);
		}

		// This is necessary when moving down, since the the last migration applied
		// will be the down() method for the next migration up from the target
		if ($current_version <> $target_version)
		{
			$current_version = $target_version;
			$this->_update_version($current_version);
		}

		log_message('debug', 'Finished migrating to '.$current_version);
		return $current_version;
	}
	
	public function execute_sql($file)
	{
		if ($this->db->dbdriver == 'mysqli')
		{
			$result = mysqli_multi_query($this->db->conn_id,file_get_contents($file));
			//Make sure this keeps php waiting for queries to be done and checking for errors (fails on first error)
			while(mysqli_more_results($this->db->conn_id) && ($result = mysqli_next_result($this->db->conn_id))){}
			
			if (!$result)
			{
				if ($this->input->is_cli_request())
				{
					echo 'ERROR: '.mysqli_error($this->db->conn_id)."\n";
				}
			}
			
			return TRUE;
		}
		else
		{
			//2nd Method Use PDO as command. See http://stackoverflow.com/a/6461110/627473
			//Needs php 5.3, mysqlnd driver
			$mysqlnd = function_exists('mysqli_fetch_all');
		
			if ($mysqlnd && version_compare(PHP_VERSION, '5.3.0') >= 0) 
			{
				$database = $this->db->database;
				$db_hostname = $this->db->hostname;
				$db_username= $this->db->username;
				$db_password = $this->db->password;

				$dsn = "mysql:dbname=$database;host=$db_hostname";
				$db = new PDO($dsn, $db_username, $db_password);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
				$sql = file_get_contents($file);
				$db->exec($sql);
			
				return TRUE;
			}				
		}
		
		return FALSE;			
	}
}
?>