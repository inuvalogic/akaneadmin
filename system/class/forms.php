<?php

class forms {
	
	var $forms;
	var $forms_row;

	var $main;
	
	function __construct() {
		$this->main = get_instance();
	}

	private function get_params($param = false){
		$params = '';
		if (is_array($param)){
			foreach($param as $c=>$d){
				$params .= ' '.$c.'="'.$d.'"';
			}
		}
		return $params;
	}

	public function add($fieldname, $type, $option = array()){
		
		$valign = '';
		$value = '';
		$params = array();

		if (array_key_exists('label', $option)){
			$label = $option['label'];
		} else {
			$label = str_replace('_', ' ', $fieldname);
			$label = ucwords($label);
		}
		
		if (array_key_exists('value', $option)){
			$value = $option['value'];
		}
		
		if (array_key_exists('params', $option)){
			$params = $option['params'];
		}

		if (array_key_exists('required', $option)){
			if ($option['required']==true){
				$required = '<span class="required"></span>';
				$params['required'] = 'required';
			}
		}
		
		switch ($type) {
			case 'textarea':
				$valign = ' valign="top"';
				$form = $this->textarea($fieldname, $value, $params);
				break;
			case 'select':
				if (array_key_exists('select_data', $option)){
					if (is_array($option['select_data'])){
						$select_data = $option['select_data'];
					}
					$form = $this->input_select($fieldname,$select_data,$params,$value);
				} else {
					$form = 'select_data option is required';
				}
				break;
			case 'relation':
				if (array_key_exists('table', $option) && array_key_exists('primary_column', $option) && array_key_exists('display_column', $option)){
					if (!empty($option['table'])){
						load_model($option['table']);
						
						$all = $this->main->$option['table']->all();
						$select_data = array();
						foreach ($all as $akey => $avalue) {
							$select_data[$avalue[$option['primary_column']]] = $avalue[$option['display_column']];
						}
					}
					$form = $this->input_select($fieldname,$select_data,$params,$value);
				} else {
					$form = 'table, primary_column, display_column option is required';
				}
				break;
			case 'text':
			default:
				$form = $this->input_text($fieldname, $value, $params);
				break;
		}

		$this->forms_row .= '<tr>
			<td'.$valign.'>'.$label.$required.'</td>
		    <td>'.$form.'</td>
		</tr>';
		
		return $this;
	}

	public function buildForm($action, $option = false){
		
		$form_params = '';
		if (is_array($option)){
			if (array_key_exists('form_params', $option)){
				$form_params = $this->get_params($option['form_params']);
			}
		}

		$this->forms = '<form method="post"'.$form_params.'>';
		$this->forms .= '<table border="0">';
		$this->forms .= $this->forms_row;
		
		$submit_params = '';
		if (is_array($option)){
			if (array_key_exists('submit_params', $option)){
				$submit_params = $this->get_params($option['submit_params']);
			}
		}

		$this->forms .= '<tr>
					  	<td></td>
					    <td>'.$this->submit_button($action, false, $submit_params).'</td>
					  </tr>';

		$this->forms .= '</table>';
		$this->forms .= '</form>';
	}

	public function renderForm($action, $option = false){
		$this->buildForm($action, $option);
		echo $this->forms;
	}

	public function add_button($params=false){
		if (is_array($params)==true){
			$uri = '';
			foreach($params as $k=>$v){
				$uri .= '&'.$k.'='.$v;
			}
			echo '<a class="add-menu" href="?content='.THISFILE.'&action=add'.$uri.'">'.MENU_ADD.'</a><br /><br />';
		} else {
			echo '<a class="add-menu" href="?content='.THISFILE.'&action=add">'.MENU_ADD.'</a><br /><br />';
		}
	}
	
	public function search_form($placeholder='enter keyword here'){
		$key = '';
		if (isset($_GET['keyword'])){
			$key = $_GET['keyword'];
		}
		echo '<div align="right">
		<form method="get">
			<input type="hidden" name="content" value="'.THISFILE.'"/>
			<input type="text" style="width:200px;" name="keyword" value="'.$key.'" placeholder="'.$placeholder.'" required />
			<input class="input-button" type="submit" name="search" value="Find" />
		</form>
		</div><br />
		';
	}
	
	public function action_button($action,$thisfile,$id,$title=''){
		$link = '';
		switch($action){
			case 'edit':
				$link = '<a title="'.$title.'" href="?content='.$thisfile.'&action=edit&idb='.$id.'"><img src="sysimages/icon_edit.png" border="0" /></a>';
			break;
			case 'delete':
				$link = '<a title="'.$title.'" href="?content='.$thisfile.'&action=delete&idb='.$id.'" class="confirm_delete"><img src="sysimages/icon_delete.png" border="0" /></a>';
			break;
			case 'approve':
				$link = '<a title="'.$title.'" href="?content='.$thisfile.'&action=approve&idb='.$id.'"><img src="sysimages/icon_tick.png" border="0" /></a>';
			break;
			case 'reply':
				$link = '<a title="'.$title.'" href="?content='.$thisfile.'&action=reply&idb='.$id.'"><img src="sysimages/icon_tick.png" border="0" /></a>';
			break;
			case 'activate':
				$link = '<a title="'.$title.'" href="?content='.$thisfile.'&action=activate&idb='.$id.'"><img src="sysimages/icon_tick.png" border="0" /></a>';
			break;
			case 'suspend':
				$link = '<a title="'.$title.'" href="?content='.$thisfile.'&action=suspend&idb='.$id.'"><img src="sysimages/icon_suspend.png" border="0" /></a>';
			break;
		}
		return $link;
	}
	
	public function submit_button($label, $name = false, $param = false){
		if ($name == false){
			$name = 'ppost';
		}
		$params = '';
		if (is_array($param)){
			foreach($param as $c=>$d){
				$params .= ' '.$c.'="'.$d.'"';
			}
		}
		$submit = '<input class="input-button" type="submit" name="'.$name.'" value="'.$label.'"'.$params.' />';
		return $submit;
	}
	
	public function input_text($name,$value=false,$param=false){
		$values = '';
		$params = '';
		$type = 'text';

		if ($name=='password'){
			$type = 'password';
		}

		if ($value!=false){
			$values = ' value="'.$value.'"';
		}
		
		if (isset($_POST[$name])){
			$values = ' value="'.$_POST[$name].'"';
		}
				
		if (is_array($param)){
			foreach($param as $c=>$d){
				$params .= ' '.$c.'="'.$d.'"';
			}
		}
		
		$input = '<input type="'.$type.'" name="'.$name.'"'.$values.$params.' />';
		return $input;
	}
	
	public function input_select($name,$data=array(),$param=false,$current=false){
		$params = '';
		if (is_array($param)){
			foreach($param as $c=>$d){
				$params .= ' '.$c.'="'.$d.'"';
			}
		}
		$select = '<select name="'.$name.'"'.$params.'>';
		$select .= '<option value="">- Choose -</option>';
		foreach($data as $k=>$v){
			$selected = '';
			if ($current==false){
				$current = $_POST[$name];
			}
			if ($current==$k){
				$selected = ' selected="selected"';
			}
			$select .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
		}
		$select .= '</select>';
		return $select;
	}

	public function textarea($name,$value='',$param=false){
		$params = '';
				
		if (isset($_POST[$name])){
			$value = $_POST[$name];
		}
				
		if (is_array($param)){
			foreach($param as $c=>$d){
				$params .= ' '.$c.'="'.$d.'"';
			}
		}
		
		$input = '<textarea name="'.$name.'"'.$params.'>'.$value.'</textarea>';
		return $input;
	}
}