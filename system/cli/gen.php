<?php

class Akane_Generate {
	
	public function writefile($path, $filename, $content){
		$path = rtrim($path, '/');
		if (!file_exists($path)){
			mkdir($path,0775);
		}
		if (!file_put_contents($path.'/'.$filename, $content)){
			echo "\n\tWriting file to ".$path.'/'.$filename." - \033[0;31mError\033[0m";
		} else {
			echo "\n\tWriting file to ".$path.'/'.$filename." - \033[1;33mOK\033[0m";
		}
		echo "\n";
	}

	public function create_model($tabelname, $primary_keys, $searchable_column){
		if (is_array($searchable_column)){
			$sc = array();
			foreach ($searchable_column as $field){
				$sc[] = "'".$field."'";
			}
			if (count($searchable_column) > 1){
				$searchable_column_string = implode(',',$sc);
			} else {
				$searchable_column_string = $sc[0];
			}
		} else {
			$searchable_column_string = "'".$searchable_column."'";
		}

		$formatted_content = '<?php
/*
 *
 * Model Class for table '.$tabelname.'
 * generated on '.date("d F Y H:i:s").'
 *
 *
 * This file is auto generated by Akane Console Tools
 * you can customize it to your need
 * for more information
 * type command "php console" from Akane directory on Terminal console
 * 
 */
class %tabelname%
{
	var $main;
	
	function __construct() {
		$this->main = get_instance();
	}
	
	function single($%primary_keys%){
		return $this->main->db->get_data(\'%tabelname%\', \'\', "%primary_keys%=\'$%primary_keys%\'");
	}
	
	function name($id){
		$data = $this->main->db->get_data(\'%tabelname%\', \'\', "%primary_keys%=\'$%primary_keys%\'");
		return $data[0][\'name\']; # change this to field that contain name
	}

	function all($limit=\'\',$keyword=\'\'){
		$where = \'\';
		if ($keyword!=\'\'){
			# please change this searchable column name to your need
			$where = "%searchable_column% LIKE \'%$keyword%\'";
			$searchable = array('.$searchable_column_string.');

			if (count($searchable) > 1){
				$wheres = array();
				foreach ($searchable as $field){
					$wheres[] = $field." LIKE \'%".$keyword."%\'";
				}
				$where = implode(\' OR \',$wheres);
			} else {
				$where = $searchable[0]." LIKE \'%".$keyword."%\'";
			}
		}
		$data = $this->main->db->get_data(\'%tabelname%\', \'\', $where, \'\', $limit);
		return $data;
	}	
}
';
		$content = str_replace('%tabelname%', $tabelname, $formatted_content);
		$content = str_replace('%primary_keys%', $primary_keys, $content);

		$model_path = 'system/model/';
		$this->writefile($model_path, $tabelname.'.php', $content);
	}

	public function create_admin_menu($tables){

		$admin_menu = '';
		$format_menu = '<li><a href="<?php echo SITEURL; ?>?content=%s">%s</a></li>';

		foreach ($tables as $key => $value) {
			$label = str_replace('_', ' ', $key);
			$label = ucwords($label);
			$admin_menu .= "\t\t\t".sprintf($format_menu, $key, $label)."\n";
		}

		$formatted_content = '<?php
/*
 *
 * Admin Menu
 * generated on '.date("d F Y H:i:s").'
 *
 *
 * This file is auto generated by Akane Console Tools
 * you can customize it to your need
 * for more information
 * type command "php console" from Akane directory on Terminal console
 * 
 */

if (!defined(\'_INC\')) { die(\'404 Not Found\'); }

$web->admin();

?>
	<li class="menu"><a href="javascript:void(0);">Master</a>
		<ul>
'.$admin_menu.'
		</ul>
	</li>
';

		$model_path = 'contents/page/';
		$this->writefile($model_path, 'admin_menu_master.php', $formatted_content);
	}
	public function create_admin($tabelname, $columns, $primary_keys){
		
		// print_r($columns);
		
		$autotime = '';
		$autotime_format = '$_POST[\'%s\'] = \'NOW()\';';

		$autotime_column = array('create_date', 'created_date', 'created_at', 'post_date', 'postdate', 'posted_at', 'update_date', 'updated_at');

		$not_empty_start = '';
		$not_empty_end = '';
		$not_empty_string = '!empty($_POST[\'%s\'])';
		$not_empty_array = array();
		
		$required_format = '\'required\' => true';
		
		$form_add = '';
		$form_edit = '';

		$table_heading = '';
		$table_head = array();
		$table_head_format = '<td class="tabel-head2">%s</td>';

		$table_rows = '';
		$table_row = array();
		$table_row_format = '<td class="tabel-content"><?php echo $data[\'%s\']; ?></td>';


		foreach ($columns as $col_keys => $col) {
			
			if (!$col['primary']) {

				$form_param = array();
				$form_parameter = '';
				$form_type = 'text';

				$label = str_replace('_', ' ', $col['name']);
				$label = ucwords($label);
				$table_head[] = "\t\t\t\t\t".sprintf($table_head_format,$label);
				$table_row[] = "\t\t\t\t\t\t".sprintf($table_row_format,$col['name']);

				if ($col['type']=='text'){
					$form_type = 'textarea';
				} else if ($col['type']=='enum'){
					if (count($col['enum_data'])!=0){

						$form_type = 'select';
						
						$enum_array = array();

						foreach ($col['enum_data'] as $enumkey => $enumvalue) {
							$enumvalue_trim = trim($enumvalue, "'");
							$enum_array[] = '\''.$enumvalue_trim.'\' => \''.$enumvalue_trim.'\'';
						}
						
						$enums = implode(', ', $enum_array);

						$form_param[] = '\'select_data\' => array('.$enums.')';
					}
				} else if ($col['type']=='date' || $col['type']=='datetime'){
					$form_param[] = '\'params\' => array(\'class\' => \'datepicker\')';
					if (in_array($col['name'], $autotime_column)===true){
						$autotime .= "\t\t\t\t\t".sprintf($autotime_format, $col['name'])."\n";
						continue;
					}
				}

				if (!$col['nullable']){
					$not_empty_array[] = sprintf($not_empty_string, $col['name']);
					$form_param[] = $required_format;
				}

				if ($col['external']!=false){
					$form_type = 'relation';
					$form_param[] = '\'table\' => \''.$col['external'].'\'';
					$form_param[] = '\'primary_column\' => \'id\'';
					$form_param[] = '\'display_column\' => \'name\'';
					$form_add .= "\t\t\t\t# Change primary_column and display_column to your need\n";
					$form_edit .= "\t\t\t\t# Change primary_column and display_column to your need\n";
				}

				if (count($form_param)!=0){
					$form_params = implode(', ', $form_param);
					$form_parameter = ', array('.$form_params.')';
				}

				$form_add .= "\t\t\t\t".'->add(\''.$col['name'].'\', \''.$form_type.'\''.$form_parameter.')'."\n";
				
				if (count($form_param)!=0){
					$form_param[] = '\'value\' => $rb[\''.$col['name'].'\']';
					$form_params = implode(', ', $form_param);
					$form_parameter = ', array('.$form_params.')';
				}

				$form_edit .= "\t\t\t\t".'->add(\''.$col['name'].'\', \''.$form_type.'\''.$form_parameter.')'."\n";

			}
		}

		if (count($not_empty_array)!=0){
			$not_empty_if = implode(' && ', $not_empty_array);
			$not_empty_start = 'if ('.$not_empty_if.') {';
			$not_empty_end = '} else { echo ERROR_NULL; }';
		}

		if (count($table_head)!=0){
			$table_heading = implode("\n", $table_head);
		}

		if (count($table_row)!=0){
			$table_rows = implode("\n", $table_row);
		}

		$formatted_content = '<?php
/*
 *
 * Admin Controller for table '.$tabelname.'
 * generated on '.date("d F Y H:i:s").'
 *
 *
 * This file is auto generated by Akane Console Tools
 * you can customize it to your need
 * for more information
 * type command "php console" from Akane directory on Terminal console
 * 
 */

$web->admin();

load_model(\'%tabelname%\');

if ( (isset($_GET[\'action\'])) && (!empty($_GET[\'action\'])) )
{
	$action = $_GET[\'action\'];
} else {
	$action = \'\';
}
	switch($action)
	{
		case \'add\':
			echo BACKLINK;
			if ( (isset($_POST[\'ppost\'])) && ($_POST[\'ppost\']==MENU_ADD) )
			{
				'.$not_empty_start.'
					unset($_POST[\'ppost\']);
					$_POST[\'action\'] = \'insert\';
					$_POST[\'table\'] = \'%tabelname%\';
'.$autotime.'
					$web->db->auto_save();
					$web->gotopage(THISFILE);
				'.$not_empty_end.'
			}

			$this->forms
'.$form_add.'
			->renderForm(MENU_ADD);

		break;
		case \'edit\':
			echo BACKLINK;
			if ( (isset($_GET[\'idb\'])) && (!empty($_GET[\'idb\'])) )
			{
				$idb = $_GET[\'idb\'];
				$data = $web->%tabelname%->single($idb);
				$cb = count($data);
				if ($cb!=0)
				{
					$rb = $data[0];

					if ( (isset($_POST[\'ppost\'])) && ($_POST[\'ppost\']==MENU_EDIT) )
					{
						'.$not_empty_start.'
							unset($_POST[\'ppost\']);
							$_POST[\'action\'] = \'update\';
							$_POST[\'table\'] = \'%tabelname%\';
							$_POST[\'where\'] = \'%primary_keys%=\'.$rb[\'%primary_keys%\'];
							$web->db->auto_save();
							$web->gotopage(THISFILE);
						'.$not_empty_end.'
					}

					$this->forms
'.$form_edit.'
					->renderForm(MENU_EDIT);

				} else {
					echo ERROR_IDB_NULL;
				}
			} else {
				echo ERROR_IDB_NULL;
			}
		break;
		case \'delete\':
			if ( (isset($_GET[\'idb\'])) && (!empty($_GET[\'idb\'])) )
			{
				$idb = $_GET[\'idb\'];
				$data = $web->%tabelname%->single($idb);
				$cb = count($data);
				if ($cb!=0)
				{
					$rb = $data[0];
					$delete = $web->db->query_delete(\'%tabelname%\',array(\'%primary_keys%\' => $rb[\'%primary_keys%\']));
					if ($delete){
						$web->gotopage(THISFILE);
					}
				} else {
					echo ERROR_IDB_NULL;
				}
			} else {
				echo ERROR_IDB_NULL;
			}
		break;
		default:
			$web->add_button();
			$web->search_form();
			
			$keyword = \'\';
			$searchlink = \'\';
			if (isset($_GET[\'keyword\'])){
				$keyword = $_GET[\'keyword\'];
				$searchlink = \'&keyword=\'.$_GET[\'keyword\'];
			}
			
			$data = $web->%tabelname%->all(\'\',$keyword);
			$jmlrec = count($data);
			
			$url = SITEURL.\'?content=\'.THISFILE.$searchlink.\'&paged=[[paged]]\';
            $paging = $web->pagination_seo($jmlrec, $url);
            
            $data = $web->%tabelname%->all($paging[\'limit\'],$keyword);
            $jmlr = count($data);
            
			if ($jmlrec>0)
			{
				$x = 0;
				echo \'<table width="100%" cellpadding="3" cellspacing="0" border="0">
				<tr>
'.$table_heading.'
					<td width="50" class="tabel-head2-end" align="center">Action</td>
				</tr>\';
				foreach($data as $data)
				{
					$color = ($x% 2  ? \'\' : \' class="diffcolor"\');
					?>
					<tr<?php echo $color; ?>>
'.$table_rows.'
						<td class="tabel-content-end" align="center">
							<?php echo $web->action_button(\'edit\',THISFILE,$data[\'%primary_keys%\']); ?>
							<?php echo $web->action_button(\'delete\',THISFILE,$data[\'%primary_keys%\'],\'this data\'); ?>
						</td>
					</tr>
					<?php
					$x++;
				}
				echo \'</table>\';
				echo $paging[\'output\'];
			} else {
				echo ERROR_EMPTY;
				echo "<br /><br />";
			}
		break;
	}
?>
';
		$content = str_replace('%tabelname%', $tabelname, $formatted_content);
		$content = str_replace('%primary_keys%', $primary_keys, $content);
		
		// echo $content."\n";
		// echo str_repeat('=', 100)."\n\n\n\n";

		$admin_path = 'contents/page/';
		$this->writefile($admin_path, $tabelname.'.php', $content);
	}
}