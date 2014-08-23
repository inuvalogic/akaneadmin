<?php

class Akane_CLI {

	public $version = "\nAkane Console Tools v.1.0.0\ncreated by Wisnu Hafid \100 19 Aug 2014\n\n";

	private static $instance;
	private static $database_config;

	public function __construct() {
		global $database_config;
		$this->database_config = $database_config;
		self::$instance = $this;
	}

	public static function &get_instance() {
		return self::$instance;
	}
	
	public function show_help(){
		$header = "\n".str_repeat('#', 100)."\n\n\033[1;34mAkane Console Tools\033[0m\n".str_repeat('_', 100)."\n\n";
		
		$usage = "\033[1;33mUsage:\033[0m\n\n\tphp console [command]\n\n";
		
		$command_head = "\033[1;33mAvailable commands:\033[0m\n\n";
		
		$command_list = "\tgenerate:admin\t\tGenerate CRUD from current database configuration\n";
		$command_list .= "\tgen:adm\t\t\tAlias of generate:admin command\n";
		$command_list .= "\n\t--help -h\t\t\tShow this help message\n";
		$command_list .= "\t--version -v\t\t\tShow version\n";

		$command = $command_head.$command_list;

		$end = "\n\n".str_repeat('~', 100)."\n\n";

		return $header.$usage.$command.$end;
	}

	public function load_db_class() {
		include "system/class/class.mysql.php";
		$this->db = new Database($this->database_config['dbHost'], $this->database_config['dbUser'], $this->database_config['dbPass'], $this->database_config['dbname']); 
		$this->db->connect();
	}

	public function generate_admin(){
		$this->load_db_class();

		$output = "\n";
		
	    $getTablesQuery = "SHOW TABLES";
	    $getTablesResult = $this->db->query($getTablesQuery);
			    
	    $dbTables = array();

		while($odata = $this->db->fetch_array($getTablesResult)){
			$_dbTables[] = $odata['Tables_in_'.$this->database_config['dbname']];
			$dbTables[] = array(
	    		"name" => $odata['Tables_in_'.$this->database_config['dbname']],
	    		"columns" => array(),
	    		"create_table" => null
	    	);
		}

    	foreach($dbTables as $dbTableKey => $dbTable){
		    $getTableColumnsQuery = "SHOW COLUMNS FROM `" . $dbTable['name'] . "`";
		    $getTableColumnsResult = $this->db->query($getTableColumnsQuery);

		    while($ocdata = $this->db->fetch_array($getTableColumnsResult)){
		    	$dbTables[$dbTableKey]['columns'][] = $ocdata;
		    }

	    	$getCreateTablesQuery = "SHOW CREATE TABLE `" . $dbTable['name'] . "`";
		    $getCreateTablesResult = $this->db->query($getCreateTablesQuery);
		    
		    while($ctdata = $this->db->fetch_array($getCreateTablesResult)){
			    foreach($ctdata as $k => $v){
		    		$dbTables[$dbTableKey]['create_table'] = $v;
			    }
		    }

    	}
    	
		$tables = array();
    	foreach($dbTables as $dbTable){

    		if(count($dbTable['columns']) <= 1){
    			continue;
    		}

    		$table_name = $dbTable['name'];
    		$table_columns = array();
    		$primary_key = false;

    		$primary_keys = 0;
    		$primary_keys_auto = 0;
    		$foreign_keys = 0;

    		foreach($dbTable['columns'] as $column){
    			if($column['Key'] == "PRI"){
    				$primary_keys++;
    			}
    			if($column['Key'] == "MUL"){
    				$foreign_keys++;
    			}    			
    			if($column['Extra'] == "auto_increment"){
    				$primary_keys_auto++;
    			}    			
    		}

    		if($primary_keys === 1 || $foreign_keys > 1 || ($primary_keys > 1 && $primary_keys_auto === 1)){

	    		foreach($dbTable['columns'] as $column){

	    			$external_table = false;

	    			if($primary_keys > 1 && $primary_keys_auto == 1){
		    			if($column['Extra'] == "auto_increment"){
		    				$primary_key = $column['Field'];
		    			}
	    			}
	    			else if($primary_keys == 1){
		    			if($column['Key'] == "PRI"){
		    				$primary_key = $column['Field'];
		    			}
		    		}
		    		else{
		    			continue 2;
		    		}

					if ($foreign_keys > 0){
						if($column['Key'] == "MUL"){
		    				$pattern = '/CONSTRAINT (.*?) FOREIGN KEY \(`'.$column['Field'].'`\) REFERENCES `(.*?)`(.*?)/si';
		    				preg_match_all($pattern, $dbTable['create_table'], $matches);
		    				//print_r($matches);
		    				$_table_name = false;
		    				if (isset($matches[2][0])){
		    					$_table_name = $matches[2][0];
		    				}
		    				if($_table_name!=false && in_array($_table_name, $_dbTables)){
					        	$external_table = $_table_name;
					    	}
		    			}
		    		}
		    		
		    		$array_enum = false;
		    		if (strpos($column['Type'], 'enum')!==false){
		    			$string_enum = preg_replace('#^enum\((.*)\)$#', '$1', $column['Type']);
		    			$array_enum = explode(',',$string_enum);
		    		}

	    			$table_columns[] = array(
	    				"name" => $column['Field'],
	    				"primary" => $column['Field'] == $primary_key ? true : false,
	    				"nullable" => $column['Null'] == "YES" ? true : false,
	    				"auto" => $column['Extra'] == "auto_increment" ? true : false,
	    				"external" => $column['Field'] != $primary_key ? $external_table : false,
	    				"type" => is_array($array_enum) ? 'enum' : $column['Type'],
	    				"enum_data" => is_array($array_enum) ? $array_enum : false
	    			);

	    		}

    		}
    		else{
    			continue;
    		}

			$tables[$table_name] = array(
				"primary_key" => $primary_key,
				"columns" => $table_columns
			);

    	}
    	
    	// print_r($dbTables);
    	// print_r($tables);

    	foreach ($tables as $key => $value) {
    		echo "\n".$key."\n";
    		echo "\tGenerating Model class ...";
    		Akane_Generate::create_model($key, $value['primary_key']);
    		echo "\tGenerating Admin class ...";
    		Akane_Generate::create_admin($key, $value['columns'], $value['primary_key']);
    	}
    	
    	echo "\nGenerating Admin menu ...";
    	Akane_Generate::create_admin_menu($tables);

		$output .= "\n\n";

		return $output;
	}

}

$cli = new Akane_CLI;

function &get_instance() {
	return Akane_CLI::get_instance();
}
