<?php

class forms {
	
	var $forms;
	var $form_header;
	var $forms_row;
	var $form_params = array();
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
		$help = '';
		$required = '';
		$params = array();

		if (array_key_exists('label', $option)){
			$label = $option['label'];
		} else {
			$lang = substr($fieldname, -3);
			
			if ($lang=='_en'){
				$colname = str_replace('_en', '_(English)', $fieldname);
			} else if ($lang=='_in'){
				$colname = str_replace('_in', '_(Indonesia)', $fieldname);
			} else {
				$colname = $fieldname;
			}
			
			$label = str_replace('id_', '', $colname);
			$label = str_replace('_', ' ', $label);
			$label = ucwords($label);
		}
		
		if (array_key_exists('value', $option)){
			$value = $option['value'];
		}
		
		if (array_key_exists('params', $option)){
			$params = $option['params'];
		}
		
		if (array_key_exists('valign', $option)){
			$valign = ' valign="'.$option['valign'].'"';
		}

		if (array_key_exists('required', $option)){
			if ($option['required']==true){
				$required = '<span class="required"></span>';
				$params['required'] = 'required';
			}
		}

		if (array_key_exists('help', $option)){
			$help = '<div class="help">'.$option['help'].'</div>';
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
						$form = $this->input_select($fieldname,$select_data,$params,$value);
					} else {
						$form = 'select_data option is required';
					}					
				} else {
					$form = 'select_data option is required';
				}
				break;
			case 'relation':
				if (array_key_exists('table', $option) && array_key_exists('primary_column', $option) && array_key_exists('display_column', $option)){
					if (!empty($option['table'])){
						load_model($option['table']);
						
						$all = $this->main->{$option['table']}->all();
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
			case 'file':
				$valign = ' valign="top"';
				$form = '';
				if (array_key_exists('edit_pic', $option)){
					if ($option['edit_pic']['filename']!=''){
						if($option['edit_pic']['mode']=='watermark' || $option['edit_pic']['mode']=='clients' || $option['edit_pic']['mode']=='partnership'){
							$form .= '<div>'.$this->main->serve_pics_ori($option['edit_pic']['mode'], $option['edit_pic']['filename']).'</div>';
						} else {				
							$form .= '<div>'.$this->main->serve_pics($option['edit_pic']['mode'], $option['edit_pic']['filename']).'</div>';
						}
						// if (!array_key_exists('required', $option)){
						// 	$form .= '<div><a href="?content='.THISFILE.'&action=delete_pic&p='.$option['edit_pic']['mode'].'&f='.$fieldname.'&idb='.$_GET['idb'].'"><span class="fa fa-times"></span> Delete this picture</a></div>';
						// }
						$form .= '<br /> Change to ';
					}
				}
				if (array_key_exists('edit_file', $option)){
					if ($option['edit_file']['filename']!=''){						
						$form .= '<div><a href="'.MAINSITEURL.ASSETS_DIR.$option['edit_file']['mode'].'/'.$option['edit_file']['filename'].'">'.$option['edit_file']['filename'].'</a></div>';
						// if (!array_key_exists('required', $option)){
						// 	$form .= '<div><a href="?content='.THISFILE.'&action=delete_file&p='.$option['edit_file']['mode'].'&f='.$fieldname.'&idb='.$_GET['idb'].'"><span class="fa fa-times"></span> Delete this file</a></div>';
						// }
						$form .= '<br /> Change to ';
					}
				}
				$form .= $this->upload_form($fieldname, $params);
				break;
			case 'password':
				$form = $this->input_password($fieldname, $params);
				break;
			case 'date':
				$form = '<div class="input-group date" id="datepicker_'.$fieldname.'">';
				$form .='<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';
				$form .= $this->input_text($fieldname, $value, $params);
                $form .= '</div>';
                $form .= '<script>$(function() {$(\'#datepicker_'.$fieldname.'\').datetimepicker({format: \'YYYY-MM-DD HH:mm:ss\',sideBySide: true});});</script>';
				break;
			case 'number':
				$form = $this->input_number($fieldname, $value, $params);
				break;
			case 'text':
			default:
				$form = $this->input_text($fieldname, $value, $params);
				break;
		}

		$this->forms_row .= '<div class="form-group">
                <label for="'.$label.'">'.$label.$required.'</label>
                '.$form.$help.'
            </div>';
		
		return $this;
	}

	public function form_header($heading_title){
		$this->form_header = $heading_title;
		return $this;
	}

	public function add_form_params($newparams){
		if (is_array($newparams)){
			foreach ($newparams as $key => $value) {
				if (array_key_exists($key, $this->form_params)==false){
					$this->form_params[$key] = $value;
				}
			}
		}
	}

	public function buildForm($action, $option = false){
		
		if (is_array($option)){
			if (array_key_exists('form_params', $option)){
				$this->add_form_params($option['form_params']);
				
			}
		}

		$form_params = $this->get_params($this->form_params);

		$this->forms = '<div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">'.$this->form_header.'</h3>
                </div>
                <div class="box-body"><form class="form" method="post"'.$form_params.'>';
		$this->forms .= $this->forms_row;
		
		$submit_params = '';
		if (is_array($option)){
			if (array_key_exists('submit_params', $option)){
				$submit_params = $this->get_params($option['submit_params']);
			}
		}

		$this->forms .= '</div><div class="box-footer">'.$this->submit_button($action, false, $submit_params).'</div>';
		$this->forms .= '</form>';
		$this->forms .= '<script type="text/javascript" src="'.SITEURL.'tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea.tinymce",
    menubar : false,
    plugins: "paste, textcolor, code, image, jbimages",
    paste_word_valid_elements: "b,strong,i,em,h1,h2",
    toolbar: ["styleselect | fontselect | fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | numlist bullist | forecolor backcolor | image jbimages | link code"],
});
</script>';

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
			echo '<a class="btn btn-sm btn-primary" href="?content='.THISFILE.'&action=add'.$uri.'"><span class="fa fa-plus"></span> '.MENU_ADD.'</a>';
		} else {
			echo '<a class="btn btn-sm btn-primary" href="?content='.THISFILE.'&action=add"><span class="fa fa-plus"></span> '.MENU_ADD.'</a>';
		}
	}
	
	public function search_form($placeholder='enter keyword here', $customize = ''){
		$key = '';
		if (isset($_GET['keyword'])){
			$key = $_GET['keyword'];
		}
		echo '
		<form method="get">
			<input type="hidden" name="content" value="'.THISFILE.'"/>
			<div class="input-group input-group-sm">
                <input type="text" name="keyword" value="'.$key.'" placeholder="'.$placeholder.'" class="form-control" required>
                <span class="input-group-btn">
                    <button name="search" class="btn btn-info btn-flat" type="submit">Find</button>
                </span>
                '.$customize.'
            </div>
		</form>
		';
	}
	
	public function action_button($action,$thisfile,$id,$title=''){
		$link = '';
		switch($action){
			case 'view':
				$link = '<a class="btn btn-default btn-sm" title="'.$title.'" href="?content='.$thisfile.'&action=view&idb='.$id.'"><span class="fa fa-eye"></span></a>';
			break;
			case 'edit':
				$link = '<a class="btn btn-default btn-sm" title="'.$title.'" href="?content='.$thisfile.'&action=edit&idb='.$id.'"><span class="fa fa-pencil"></span></a>';
			break;
			case 'delete':
				$link = '<a class="btn btn-default btn-sm confirm_delete" title="'.$title.'" href="?content='.$thisfile.'&action=delete&idb='.$id.'"><span class="fa fa-times"></span></a>';
			break;
			case 'approve':
				$link = '<a class="btn btn-default btn-sm" title="'.$title.'" href="?content='.$thisfile.'&action=approve&idb='.$id.'"><img src="sysimages/icon_tick.png" border="0" /></a>';
			break;
			case 'reply':
				$link = '<a class="btn btn-default btn-sm" title="'.$title.'" href="?content='.$thisfile.'&action=reply&idb='.$id.'"><img src="sysimages/icon_tick.png" border="0" /></a>';
			break;
			case 'activate':
				$link = '<a class="btn btn-default btn-sm" title="'.$title.'" href="?content='.$thisfile.'&action=activate&idb='.$id.'"><img src="sysimages/icon_tick.png" border="0" /></a>';
			break;
			case 'suspend':
				$link = '<a class="btn btn-default btn-sm" title="'.$title.'" href="?content='.$thisfile.'&action=suspend&idb='.$id.'"><img src="sysimages/icon_suspend.png" border="0" /></a>';
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
		$submit = '<input class="btn btn-primary" type="submit" name="'.$name.'" value="'.$label.'"'.$params.' />';
		return $submit;
	}
	
	public function upload_form($name,$param=false){
		$params = '';
		if (is_array($param)){
			foreach($param as $c=>$d){
				$params .= ' '.$c.'="'.$d.'"';
			}
		}
		$this->add_form_params(array('enctype' => 'multipart/form-data'));
		$input = '<input type="file" name="'.$name.'"'.$params.' />';
		return $input;
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
			$paramkey = array();
			foreach($param as $c=>$d){
				if ($c=='class'){
					$d = 'form-control '.$d;
				}
				$params .= ' '.$c.'="'.$d.'"';
				$paramkey[] = $c;
			}
			if (in_array('class',$paramkey)===false){
				$params .= ' class="form-control"';
			}
		} else {
			$params .= ' class="form-control"';
		}

		$input = '<input type="'.$type.'" name="'.$name.'"'.$values.$params.' />';
		return $input;
	}
	
	public function input_password($name,$param=false){
		$params = '';
						
		if (is_array($param)){
			$paramkey = array();
			foreach($param as $c=>$d){
				if ($c=='class'){
					$d = 'form-control '.$d;
				}
				$params .= ' '.$c.'="'.$d.'"';
				$paramkey[] = $c;
			}
			if (in_array('class',$paramkey)===false){
				$params .= ' class="form-control"';
			}
		} else {
			$params .= ' class="form-control"';
		}
		
		$input = '<input type="password" name="'.$name.'"'.$params.' />';
		return $input;
	}

	public function input_number($name,$value=false,$param=false){
		$values = '';
		$params = '';
		
		if ($value!=false){
			$values = ' value="'.$value.'"';
		}
		
		if (isset($_POST[$name])){
			$values = ' value="'.$_POST[$name].'"';
		}

		if (is_array($param)){
			$paramkey = array();
			foreach($param as $c=>$d){
				if ($c=='class'){
					$d = 'form-control '.$d;
				}
				$params .= ' '.$c.'="'.$d.'"';
				$paramkey[] = $c;
			}
			if (in_array('class',$paramkey)===false){
				$params .= ' class="form-control"';
			}
		} else {
			$params .= ' class="form-control"';
		}
		
		$input = '<input type="number" name="'.$name.'"'.$values.$params.' />';
		return $input;
	}

	public function input_select($name,$data=array(),$param=false,$current=false){
		$params = '';
		if (is_array($param)){
			foreach($param as $c=>$d){
				$params .= ' '.$c.'="'.$d.'"';
			}
		}
		if (isset($_POST[$name])){
			$current = $_POST[$name];
		}
		$select = '<select class="form-control" name="'.$name.'"'.$params.'>';
		$select .= '<option value="">- Choose -</option>';
		foreach($data as $k=>$v){
			$selected = '';
			if ($current!=false && $current==$k){
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
			$paramkey = array();
			foreach($param as $c=>$d){
				if ($c=='class'){
					$d = 'form-control '.$d;
				}
				$params .= ' '.$c.'="'.$d.'"';
				$paramkey[] = $c;
			}
			if (in_array('class',$paramkey)===false){
				$params .= ' class="form-control"';
			}
		} else {
			$params .= ' class="form-control"';
		}
		
		$input = '<textarea name="'.$name.'"'.$params.'>'.$value.'</textarea>';
		return $input;
	}

	public function addTableHeader($th){
		if (is_array($th) && count($th > 0)){
			$this->tablehead = '<thead><tr>';
			foreach ($th as $value) {
				$this->tablehead .= '<th>'.ucwords($value).'</th>';
			}
			$this->tablehead .= '</tr></thead>';
		}
	}

	public function topbar($add = true, $search = true){
		echo '<div class="row list-top-bar">';
		
		if ($add==true){
			echo '<div class="pull-left col-md-9 col-xs-4 col-sm-6">';
			$this->add_button();
			echo '</div>';
		}

		if ($search==true){
			echo '<div class="pull-right col-md-3 col-xs-8 col-sm-6">';
			$this->search_form();
			echo '</div>';
		}
		
		echo '</div>';
	}

	public function renderTable($thead, $tdata)
	{
		if ( is_array($thead) && count($thead > 0) )
		{
			$th = '<thead><tr>';
			foreach ($thead as $key => $fieldname) {
				if ($fieldname!='id'){
					if ($key=='action'){
						// $fieldname = $key;
					}
					$autolabel = 1;
					$width = '';
					if (is_array($fieldname)){
						if (array_key_exists('width', $fieldname)){
							$width = ' width="'.$fieldname['width'].'"';
						}
						if (array_key_exists('label', $fieldname)){
							$label = $fieldname['label'];
							$autolabel = 0;
						} else {
							$fieldname = $key;
						}
					}
					if ($autolabel==1){
						$label = str_replace('id_', '', $fieldname);

						$lang = substr($label, -3);
						if ($lang=='_en'){
							$label = str_replace('_en', '_(English)', $label);
						} else if ($lang=='_in'){
							$label = str_replace('_in', '_(Indonesia)', $label);
						}

						$label = str_replace('_', ' ', $label);
						$label = ucwords($label);
					}
					$th .= '<th'.$width.'>'.$label.'</th>';
				}
			}
			$th .= '</tr></thead>';

			$tb = '<tbody>';
			foreach ($tdata as $data) {
				$tb .= '<tr>';
				foreach ($thead as $key => $fieldname) {
					if ($fieldname!='id'){
						if (is_array($fieldname)){
							if ($key=='action'){
								$action = '';
								if (count($fieldname) > 0){
									$actbtn = array('view','edit','delete');
									foreach ($fieldname as $act) {
										if (in_array($act, $actbtn)==true){
											$action .= $this->action_button($act,THISFILE,$data['id']);
										}
									}
								}
								$tb .= '<td><div class="btn-group">'.$action.'</div></td>';
							} else if (array_key_exists('relation', $fieldname)){
								if (!empty($fieldname['relation'])){
									load_model($fieldname['relation']);
									$tb .= '<td>'.$this->main->{$fieldname['relation']}->name($data[$key]).'</td>';
								}
							} else if (array_key_exists('htmlentities', $fieldname)){
								if ($fieldname['htmlentities']==true){
									$tb .= '<td>'.htmlentities($data[$key]).'</td>';
								}
							} else if (array_key_exists('custom_value', $fieldname) && array_key_exists('custom_replace', $fieldname)){
								$tbs = str_replace($fieldname['custom_replace'], $data[$key], $fieldname['custom_value']);
								$tb .= '<td>'.$tbs.'</td>';
							} else if (array_key_exists('pic', $fieldname)){
								$tb .= '<td>'.$this->main->serve_pics($fieldname['pic'], $data[$key]).'</td>';
							} else if (array_key_exists('picori', $fieldname)){
								$tb .= '<td>'.$this->main->serve_pics_ori($fieldname['picori'], $data[$key]).'</td>';
							} else {
								$tb .= '<td>'.$data[$key].'</td>';
							}
						} else {
							$tb .= '<td>'.$data[$fieldname].'</td>';
						}
					}
				}
				$tb .= '</tr>';
			}
			$tb .= '</tbody>';

			$table = '
			<div class="box-body table-responsive">
				<table id="" class="table table-bordered table-hover">
				'.$th.$tb.'
				</table>
			</div>
<script type="text/javascript">
	$().ready(function() {
		$(\'.confirm_delete\').click(function(){
			var answer = confirm(\'Are you sure to delete \'+jQuery(this).attr(\'title\')+\' ?\');
			return answer;
		});
	});
</script>';

			echo $table;
		}
	}

	public function renderView($thead, $tdata)
	{
		if ( is_array($thead) && count($thead > 0) )
		{
			$td = '';
			foreach ($thead as $key => $fieldname) {
				if ($fieldname!='id'){
					$autolabel = 1;
					if (is_array($fieldname)){
						$value = '';
						$label = $key;
						if (array_key_exists('label', $fieldname)){
							$value = $tdata[$key];
							$label = $fieldname['label'];
							$autolabel = 0;
						}
						if (array_key_exists('relation', $fieldname)){
							if (!empty($fieldname['relation'])){
								load_model($fieldname['relation']);								
								$value = $this->main->{$fieldname['relation']}->name($tdata[$key]);
							}
						}
						if (array_key_exists('htmlentities', $fieldname)){
							if ($fieldname['htmlentities']==true){
								$value = htmlentities($tdata[$key]);
							}
						}
						if (array_key_exists('pic', $fieldname)){
							if (!empty($tdata[$key])){
								if ($fieldname['pic']=='watermark' || $fieldname['pic']=='clients' || $fieldname['pic']=='partnership'){
									$value = $this->main->serve_pics_ori($fieldname['pic'], $tdata[$key]);
								} else {
									$value = $this->main->serve_pics($fieldname['pic'], $tdata[$key]);
								}
							}
						}
						if (array_key_exists('picori', $fieldname)){
								$value = $this->main->serve_pics_ori($fieldname['picori'], $tdata[$key]);
						}
						if (array_key_exists('file', $fieldname)){
							if (!empty($tdata[$key])){
								$value = '<a href="'.MAINSITEURL.ASSETS_DIR.$fieldname['file'].'/'.$tdata[$key].'">'.$tdata[$key].'</a>';
							}
						}
						if (array_key_exists('custom_value', $fieldname) && array_key_exists('custom_replace', $fieldname)){
							$value = str_replace($fieldname['custom_replace'], $tdata[$key], $fieldname['custom_value']);
							$autolabel = 0;
						}
					} else {
						$value = $tdata[$fieldname];
						$label = $fieldname;
					}

					if ($autolabel==1){
						$label = str_replace('id_', '', $label);

						$lang = substr($label, -3);
						if ($lang=='_en'){
							$label = str_replace('_en', '_(English)', $label);
						} else if ($lang=='_in'){
							$label = str_replace('_in', '_(Indonesia)', $label);
						}
						
						$label = str_replace('_', ' ', $label);
						$label = ucwords($label);
					}

					$td .= '<tr>';
					$td .= '<th width="25%">'.$label.'</th>';
					$td .= '<td>'.$value.'</td>';
					$td .= '</tr>';
				}
			}

			$table = '
			<div class="box-body table-responsive">
				<table id="" class="table table-bordered table-striped">
				'.$td.'
				</table>
			</div>';

			echo $table;
		}
	}

}