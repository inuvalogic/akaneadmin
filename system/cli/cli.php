<?php

class Akane_CLI {

	public $version = "\nAkane Console Tools\ncreated by Wisnu Hafid \100 19 Aug 2014\n\nLatest version: 1.1\nLatest update: 16 Jun 2017\n\n";

	private static $instance;
	protected $database_config;
    protected $generator;

	public function __construct() {
        $this->generator = new Akane_Generate;
		self::$instance = $this;
	}

	public static function &get_instance() {
		return self::$instance;
	}
	
	public function show_help(){
		$header = "\n".str_repeat('#', 100)."\n\n\033[1;34mAkane Console Tools\033[0m\n\nLatest version: 1.1\nLatest update: 16 Jun 2017\n".str_repeat('_', 100)."\n\n";
		
		$usage = "\033[1;33mUsage:\033[0m\n\n\tphp console [command]\n\n";
		
		$command_head = "\033[1;33mAvailable commands:\033[0m\n\n";
		
		$command_list = "";
		$command_list .= "\tgenerate:all\t\tGenerate CRUD from current database configuration\n";
		$command_list .= "\tgen:all\t\t\tAlias of generate:admin command\n";
		$command_list .= "\tgenerate:single [tablename]\t\tGenerate CRUD from single table\n";
		$command_list .= "\tgen:sin [tablename]\t\t\tAlias of generate:single command\n";
		$command_list .= "\n\t--help -h\t\t\tShow this help message\n";
		$command_list .= "\t--version -v\t\t\tShow version\n";

		$command = $command_head.$command_list;

		$end = "\n\n".str_repeat('~', 100)."\n\n";

		return $header.$usage.$command.$end;
	}

    public function set_db_config($database_config){
        $this->database_config = $database_config;
    }

	public function load_db_class() {
		include "system/class/class.mysqli.php";
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
    		$searchable_column = false;
    		$searchable_column_array = array('tags','title','description','post_title','content');

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

		    		if (in_array($column['Field'], $searchable_column_array)!==false){
		    			$searchable_column = $column['Field'];
		    		} else {
		    			$searchable_column = $dbTable['columns'];
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
				"searchable_column" => $searchable_column,
				"columns" => $table_columns
			);

    	}
    	
    	$dont_generate = array('admin', 'config');

    	foreach ($tables as $key => $value) {
    		if (in_array($key, $dont_generate)==false){
	    		echo "\n".$key."\n";
	    		echo "\tGenerating Model class ...";
	    		$this->generator->create_model($key, $value['primary_key'], $value['searchable_column']);
	    		echo "\tGenerating Admin class ...";
	    		$this->generator->create_admin($key, $value['columns'], $value['primary_key']);
    		}
    	}
    	
    	echo "\nGenerating Admin menu ...";
    	$this->generator->create_admin_menu($tables);

		$output .= "\n\n";

		return $output;
	}

	public function generate_single_admin($tablename){
		$this->load_db_class();

		if (empty($tablename)){
			echo "\nTable name is required\n";
			exit;
		}

	    $getTableQuery = "SHOW TABLES LIKE '" . $tablename . "'";
	    $getTableResult = $this->db->query($getTableQuery);
	    
	    $num = $this->db->num_rows($getTableResult);

	    if ($num <= 0){
	    	echo "\nTable not found\n";
			exit;
	    }

		$dbTable = array('name' => $tablename, 'column_name' => '', 'columns' => '', 'create_table' => '');
		
		$getAllTablesQuery = "SHOW TABLES";
	    $getAllTablesResult = $this->db->query($getAllTablesQuery);
		
		$_dbTables = array();
		while($odata = $this->db->fetch_array($getAllTablesResult)){
			$_dbTables[] = $odata['Tables_in_'.$this->database_config['dbname']];
		}

	    $getTableColumnsQuery = "SHOW COLUMNS FROM `" . $dbTable['name'] . "`";
	    $getTableColumnsResult = $this->db->query($getTableColumnsQuery);

	    while($ocdata = $this->db->fetch_array($getTableColumnsResult)){
	    	$dbTable['columns'][] = $ocdata;
            $dbTable['column_name'][] = $ocdata['Field'];
	    }

    	$getCreateTablesQuery = "SHOW CREATE TABLE `" . $dbTable['name'] . "`";
	    $getCreateTablesResult = $this->db->query($getCreateTablesQuery);
	    
	    while($ctdata = $this->db->fetch_array($getCreateTablesResult)){
		    foreach($ctdata as $k => $v){
	    		$dbTable['create_table'] = $v;
		    }
	    }

		if(count($dbTable['columns']) <= 1){
			echo "\nTable has no column to generate\n";
			exit;
		}

		$table_columns = array();
		$primary_key = false;
		$searchable_column = false;
		$searchable_column_array = array('tags','title','description','post_title','content');

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
	    			continue;
	    		}

				if ($foreign_keys > 0){
					if($column['Key'] == "MUL"){
	    				$pattern = '/CONSTRAINT (.*?) FOREIGN KEY \(`'.$column['Field'].'`\) REFERENCES `(.*?)`(.*?)/si';
	    				preg_match_all($pattern, $dbTable['create_table'], $matches);
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

	    		if (in_array($column['Field'], $searchable_column_array)!==false){
	    			$searchable_column = $column['Field'];
	    		} else {
	    			$searchable_column = $dbTable['column_name'];
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

    	// print_r($dbTable);
    	// print_r($table_columns);

		echo "\n".$dbTable['name']."\n";
		echo "\tGenerating Model class ...";
		$this->generator->create_model($dbTable['name'], $primary_key, $searchable_column);
		echo "\tGenerating Admin class ...";
		$this->generator->create_admin($dbTable['name'], $table_columns, $primary_key);
    	
   	}
}

$cli = new Akane_CLI;

function &get_instance() {
    $cli = new Akane_CLI;
	return $cli->get_instance();
}
