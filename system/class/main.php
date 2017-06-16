<?php

class WebSite
{
	var $template_use,$lang,$page,$heading_title;
	
	private static $instance;

	public function __construct() {
		self::$instance = $this;
	}

	public static function &get_instance() {
		return self::$instance;
	}
	
	function init($lang,$template_use){
		$this->lang = $lang;
		$this->template_use = $template_use;
	}
	
	function load_config()
	{
		load_model('config');
		$config = $this->config->all();
		if (count($config)!=0){
			foreach ($config as $cfg) {
				$this->lang[$cfg['config_name']] = $cfg['config_value'];
			}
		}
	}
	
	function create($tmpfile)
	{		
		//global $template_use,$lang;
		$template_use = $this->template_use;
		$lang = $this->lang;
		//print_r($lang);			
		if (!isset($template_use)){
			$template_use = 'default';
		}

		$Tfile = TEMPLATE_DIR.$template_use."/".$tmpfile;
		
		if (file_exists($Tfile))
		{
			$parse = file($Tfile);
			$page = implode("",$parse);
			$theme = array_keys($lang);
			foreach($theme as $thme)
			{
				$theme_format = "{".$thme."}";
				$page = str_replace($theme_format,$lang[$thme],$page);
			}
			return $page;
		} else {
			return ERROR_TMPL;
		}
	}
	
	function show()
	{
		echo $this->create('index.tpl');
	}

	function addcontent($file,$var='')
	{
		if (file_exists(CONTENTS_DIR.$file))
		{
			global $web,$shoutbox,$db;
			ob_start();
			include CONTENTS_DIR.$file;
			$tag = ob_get_contents();
			ob_end_clean();
			flush();
			return $tag;
		} else {
			return ERROR_TMPL;
		}
	}

	function deletefile($file,$mode=1){
	  if ($mode==1)
	  {
		$path = CONTENTS_DIR.'txt/'.$file.'.txt';
		if (file_exists($path))
		{
			if (!@unlink($path)){
				return ERROR_CANT_DELETE;
			} else {
				return 1;
			}
		} else {
			return ERROR_NO_DELETE;
		}
	  } else {
		if (file_exists($file))
		{
			if (!@unlink($file)){
				return ERROR_CANT_DELETE;
			} else {
				return 1;
			}
		} else {
			return ERROR_NO_DELETE;
		}
	  }
	}
	
	function writecontent($file,$str){
		$path = CONTENTS_DIR.'txt/'.$file.'.txt';
		$fp = fopen($path,"w+");
		if (fwrite($fp,$str)===false){
			return ERROR_NO_WRITE;
		} else {
			chmod($path,0644);
			return 1;
		}
	}

	function readcontent($file){
		$path = CONTENTS_DIR.'txt/'.$file.'.txt';
		if (file_exists($path))
		{
			$text = @file_get_contents($path);
			//$text2 = $this->addbr($text);
			return $text;
		} else {
			return 0;
		}
	}
	
	function upload($params)
	{
		global $_FILES,$web;
		$upload = array();
		$upload['status'] = 0;
		$add = 0;

		if (array_key_exists('filename', $params)){
			$filename = $params['filename'];
		}
		if (array_key_exists('path', $params)){
			$path = $params['path'];
		}
		if (array_key_exists('edit', $params)){
			$edit = $params['edit'];
		} else {
			$edit = 0;
		}
		if (array_key_exists('prefix', $params)){
			$prefix = $params['prefix'];
		} else {
			$prefix = '';
		}

		if ($edit==1)
		{
			if ($_FILES[$filename]['error']==0)
			{
				$add = 1;
			} else {
				$add = 0;
			}
		} else {
			$add = 1;
		}
		
		if ($add==1)
		{
			$dir = ASSETS_DIR.$path.'/';
			
			if(!file_exists($dir)){
				mkdir($dir,0777);
			}
	
			if ($_FILES[$filename]['size']>=$web->lang['max_upload_size_int'])
			{
				$upload['error'] = sprintf(ERROR_SIZE,$web->lang['max_upload_size_str']);
			} else {
				if ($prefix!=''){
					$prefix = $this->encode_text_url($prefix).'-';
				}
				
				$str = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm';
				$random = str_shuffle($str);
				$hash = hash('adler32',$random);

				$newname = $prefix.$hash.'-'.strtolower(basename($_FILES[$filename]['name']));
				$uploadfile = strtolower($dir.$newname);
				$upload['gfile'] = strtolower($newname);
				
				if (move_uploaded_file($_FILES[$filename]['tmp_name'], $uploadfile))
				{
					if (!chmod($uploadfile,0777))
					{
						$upload['error'] = ERROR_CHMOD;
						$upload['status'] = 0;
					} else {
						$upload['status'] = 1;
					}
				} else {
					$upload['error'] = ERROR_UPLOAD;
					$upload['status'] = 0;
				}
			}
		}
		return $upload;
	}
	
	function upload_img($params)
	{
		global $_FILES,$web;

		$upload = array();
		$upload['status'] = 0;
		$add = 0;

		if (array_key_exists('filename', $params)){
			$filename = $params['filename'];
		}
		if (array_key_exists('mode', $params)){
			$mode = $params['mode'];
		}
		if (array_key_exists('size', $params)){
			$size = $params['size'];
		}
		if (array_key_exists('edit', $params)){
			$edit = $params['edit'];
		} else {
			$edit = 0;
		}
		if (array_key_exists('prefix', $params)){
			$prefix = $params['prefix'];
		} else {
			$prefix = '';
		}
		
		$max_width = $web->lang[$size.'_width'];
		$max_height = $web->lang[$size.'_height'];
		$dir = IMAGES_DIR.$mode.'/';
		$error_size = sprintf(ERROR_UPLOAD_PIC_SIZE, $max_width, $max_height);
				
		if ($edit==1)
		{
			if ($_FILES[$filename]['error']==0)
			{
				$add = 1;
			} else {
				$add = 0;
			}
		} else {
			$add = 1;
		}
		
		if ($add==1)
		{
			if(!file_exists($dir)){
				mkdir($dir,0777);
			}
			$typefile=$_FILES[$filename]['type'];
			
			$appfile = array("image/png","image/gif","image/jpeg","image/pjpeg");
	
			if (!in_array($_FILES[$filename]['type'],$appfile))
			{
				$upload['error'] = ERROR_TYPE;
			} else {
				if ($_FILES[$filename]['size']>=$web->lang['max_upload_size_int'])
				{
					$upload['error'] = sprintf(ERROR_SIZE,$web->lang['max_upload_size_str']);
				} else {
					switch($typefile){
						case "image/png": $ext = ".png"; break;
						case "image/gif": $ext = ".gif"; break;
						case "image/jpeg": $ext = ".jpg"; break;
						case "image/pjpeg": $ext = ".jpg"; break;
					}
					if ($prefix!=''){
						$prefix = $this->encode_text_url($prefix).'-';
					}
					
					$str = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm';
					$random = str_shuffle($str);
					$hash = hash('adler32',$random);

					$newname = $prefix.$hash.$ext;
					$uploadfile = strtolower($dir.$newname);
					$upload['gfile'] = strtolower($newname);
					
					if (move_uploaded_file($_FILES[$filename]['tmp_name'], $uploadfile)) {
						list($width, $height) = getimagesize($uploadfile);
						
						if ($width<$max_width){
							$upload['error'] = $error_size;
							if (!unlink($uploadfile)) {
								$upload['error'] = ERROR_UPLOAD;
							}
						} else {
							$upload['status'] = 1;
						}
					}
				}
			}
		}
		return $upload;
	}
	
	function get_file($path,$file)
	{
		$af = explode(".",$file);
		$link = 'getfile.php?mode='.$path.'&file='.$af[0].'&type='.$af[1];
		return $link;
	}
	
	function get_icon($file)
	{
		$icon = explode(".",$file);
		$imgsrc = './sysimages/icon_'.$icon[1].'.gif';
		if (file_exists($imgsrc))
		{
			$icons = '<img src="'.$imgsrc.'" border=0>';
		} else {
			$icons = $icon[1];
		}
		return $icons;
	}
	
	function addbr($text)
	{
		$text2 = str_replace(chr(10),"<br>",$text);
		$text2 = str_replace(chr(13),"<br>",$text);
		return $text2;
	}
	
	function revbr($text)
	{
		$text = $this->addbr($text);
		$text2 = str_replace("<br>",chr(13),$text);
		//$text2 = str_replace("<br>",chr(10),$text);
		return $text2;
	}
	
	function revnl($text){
		$text2 = str_replace(chr(10),'',$text);
		$text2 = str_replace(chr(13),'',$text);
		$text2 = str_replace("\r\n",'',$text);
		return $text2;
	}
	
	function gotopage($to, $timeout = 0)
	{
		echo '<meta http-equiv="refresh" content="'.$timeout.';url=?content='.$to.'" />';
	}
	
	function check_login()
	{
		if ( isset($_SESSION['logid']) && !empty($_SESSION['logid']) )
		{
			if ($_SESSION['level']==1)
			{
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	function admin()
	{
	  if ( isset($_SESSION['logid']) && !empty($_SESSION['logid']) )
	  {
		if ($_SESSION['level']==1)
		{
		  return 1;
		} else {
		  $this->gotopage('home');
		  exit;
		}
	   } else {
		 $this->gotopage('home');
		 exit;
	   }
	}

	function sendmail($to,$subject,$message)
	{
		global $web;
		$service_mail = $web->lang['service_mail'];
		
		$headers = "From: $service_mail\n".
		"Content-Type: text/html";
		$send = @mail($to, $subject, $message, $headers);
		
		if(!$send){
			return ERROR_SENDMAIL;
		} else {
			return 1;
		}
	}
	
	function get_mail_content($mode,$data=array()){
		$content = '';
		$mode = mysql_real_escape_string($mode);
		$queryceklog = "select message from mail_content where mode='$mode'";
		$resultlog = mysql_query($queryceklog);
		$countlog = mysql_num_rows($resultlog);
		if ($countlog!=0) {
			$d = mysql_fetch_array($resultlog);
			$message = $d['message'];
			if (is_array($data)){
				foreach($data as $k=>$v){
					$message = str_replace('{'.$k.'}',$v,$message);
				}
			}
			$content = $message;
		}
		return $content;
	}
	
	function tanggal($timestamp)
	{
		$days = date("w",$timestamp);
		$date = date("d",$timestamp);
		$month = date("m",$timestamp);
		$month2 = date("M",$timestamp);
		$year = date("Y",$timestamp);
		$hour = date("H",$timestamp);
		$minutes = date("i",$timestamp);
		$second = date("s",$timestamp);
		switch($days){
			case 0:
				$waktu['hari'] = "Minggu";
			break;
			case 1:
				$waktu['hari'] = "Senin";
			break;
			case 2:
				$waktu['hari'] = "Selasa";
			break;
			case 3:
				$waktu['hari'] = "Rabu";
			break;
			case 4:
				$waktu['hari'] = "Kamis";
			break;
			case 5:
				$waktu['hari'] = "Jumat";
			break;
			case 6:
				$waktu['hari'] = "Sabtu";
			break;
		}
		switch($month){
			case "01":
				$waktu['bulan'] = "Januari";
			break;
			case "02":
				$waktu['bulan'] = "Februari";
			break;
			case "03":
				$waktu['bulan'] = "Maret";
			break;
			case "04":
				$waktu['bulan'] = "April";
			break;
			case "05":
				$waktu['bulan'] = "Mei";
			break;
			case "06":
				$waktu['bulan'] = "Juni";
			break;
			case "07":
				$waktu['bulan'] = "Juli";
			break;
			case "08":
				$waktu['bulan'] = "Agustus";
			break;
			case "09":
				$waktu['bulan'] = "September";
			break;
			case "10":
				$waktu['bulan'] = "Oktober";
			break;
			case "11":
				$waktu['bulan'] = "November";
			break;
			case "12":
				$waktu['bulan'] = "Desember";
			break;
		}
		switch($month2){
			case "Jan":
				$waktu['bulan2'] = "Jan";
			break;
			case "Feb":
				$waktu['bulan2'] = "Feb";
			break;
			case "Mar":
				$waktu['bulan2'] = "Mar";
			break;
			case "Apr":
				$waktu['bulan2'] = "Apr";
			break;
			case "May":
				$waktu['bulan2'] = "Mei";
			break;
			case "Jun":
				$waktu['bulan2'] = "Jun";
			break;
			case "Jul":
				$waktu['bulan2'] = "Jul";
			break;
			case "Aug":
				$waktu['bulan2'] = "Agt";
			break;
			case "Sep":
				$waktu['bulan2'] = "Sep";
			break;
			case "Oct":
				$waktu['bulan2'] = "Okt";
			break;
			case "Nov":
				$waktu['bulan2'] = "Nov";
			break;
			case "Dec":
				$waktu['bulan2'] = "Des";
			break;
		}
		$waktu['tanggal'] = $date;
		$waktu['tahun'] = $year;
		$waktu['jam'] = $hour;
		$waktu['menit'] = $minutes;
		$waktu['detik'] = $second;
		return $waktu;
	}
	function change_month($month,$mode) 
	{
	  if ($mode==1)
	  {
		switch($month){
			case "JAN":
				$bulan = "January";
			break;
			case "FEB":
				$bulan = "February";
			break;
			case "MAR":
				$bulan = "March";
			break;
			case "APR":
				$bulan = "April";
			break;
			case "MAY":
				$bulan = "May";
			break;
			case "JUN":
				$bulan = "June";
			break;
			case "JUL":
				$bulan = "July";
			break;
			case "AUG":
				$bulan = "August";
			break;
			case "SEP":
				$bulan = "September";
			break;
			case "OCT":
				$bulan = "October";
			break;
			case "NOV":
				$bulan = "November";
			break;
			case "DEC":
				$bulan = "December";
			break;
		}
	  }
	  if ($mode==2)
	  {
		$month2 = date("M",$month);
		$bln = strtoupper($month2);
		$tgl = date("j",$month);
		$thn = date("Y",$month);
		$bulan = $tgl.'-'.$bln.'-'.$thn;
	  }
	  return $bulan;
	}
	
	function format_uang($nilai)
	{
		return 'Rp. '.number_format($nilai,0,",",".").',-';
	}
	
	function show_date($timestamp)
	{
		return date("d F Y H:i:s",$timestamp);
	}
	
	function date_dmy($date){
		return date("d M Y",strtotime($date));
	}
	
	function encode_text_url($str, $separator = 'dash') {
		if ($separator == 'dash') {
			$search = '_';
			$replace = '-';
		} else {
			$search = '-';
			$replace = '_';
		}
		
		$trans = array($search => $replace, "\s+" => $replace, "[^a-z0-9" . $replace . "]" => '', $replace . "+" => $replace, $replace . "$" => '', "^" . $replace => '');
		
		$str = strip_tags(strtolower($str));
		
		foreach ($trans as $key => $val) {
			$str = preg_replace("#" . $key . "#", $val, $str);
		}
		
		return trim(stripslashes($str));
	}

	function add_button($params=false){
		$this->forms->add_button($params);
	}
	
	function search_form($placeholder='enter keyword here', $customize = ''){
		$this->forms->search_form($placeholder, $customize);
	}
	
	function action_button($action,$thisfile,$id,$title=''){
		$link = '';
		switch($action){
			case 'view':
				$link = '<a title="'.$title.'" href="?content='.$thisfile.'&action=view&idb='.$id.'"><img src="sysimages/icon_view.png" border="0" /></a>';
			break;
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
	
	function submit_button($label,$name='ppost'){
		$params = '';
		if (is_array($param)){
			foreach($param as $c=>$d){
				$params .= ' '.$c.'="'.$d.'"';
			}
		}
		$submit = '<input class="input-button" type="submit" name="'.$name.'" value="'.$label.'"'.$params.' />';
		return $submit;
	}
	
	function input_text($name,$value=false,$param=false){
		$values = '';
		$params = '';
		
		if ($value!=false){
			$values = 'value="'.$value.'"';
		}
		
		if (isset($_POST[$name])){
			$values = 'value="'.$_POST[$name].'"';
		}
				
		if (is_array($param)){
			foreach($param as $c=>$d){
				$params .= ' '.$c.'="'.$d.'"';
			}
		}
		
		$input = '<input type="text" name="'.$name.'"'.$values.$params.' />';
		return $input;
	}
	
	function input_select($name,$data=array(),$param=false,$current=false){
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
	
	function success_message($message){
		return '<div class="alert alert-success alert-dismissable">
            <i class="fa fa-check"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            '.$message.'
        </div>';
	}
	
	/*function add_flash_message($message){
		$_SESSION['flash_message'] = $message;
	}

	function flash_message(){
		if (isset($_SESSION['flash_message'])){
			echo $_SESSION['flash_message'];
			unset($_SESSION['flash_message']);
		}
	}*/
		
	// GLOBAL DATABASE FUNC
	
	function run_query($sql){
		if (!mysql_query($sql)){
			return mysql_error();
		} else {
			return 1;
		}
	}
	
	function run_query_data($sql){
		$r = mysql_query($sql);
		if (!$r){
			return mysql_error();
		} else {
			$data = array();
			$num = mysql_num_rows($r);
			if ($num==1){
				$ddata = mysql_fetch_array($r);
				$data['num'] = $num;
				$data['data'] = $ddata;
				mysql_free_result($r);
			} else if ($num>1){
				$ddata = array();
				$lf = mysql_num_fields($r);
				$w = 0;
				while ($odata = mysql_fetch_array($r)){
					for($i=0;$i<$lf;$i++){
						$ddata[$w][mysql_field_name($r,$i)] = $odata[mysql_field_name($r,$i)];
					}
					$w++;
				}
				$data['num'] = $num;
				$data['data'] = $ddata;
				mysql_free_result($r);
			} else {
				$data['num'] = 0;
			}
			return $data;
		}
	}
	
	function delete_data($table_name,$idb){
		if (!empty($idb))
		{
			$sb = "SELECT id FROM ".$table_name." WHERE id='$idb'";
			$gb = mysql_query($sb) or die(ERROR_DB);
			$cb = mysql_num_rows($gb);
			if ($cb!=0)
			{
				$rb = mysql_fetch_object($gb);
				$banner_id = $rb->id;
				$qp = "DELETE FROM ".$table_name." WHERE id='$banner_id'";
				if (!mysql_query($qp)){
					return ERROR_DB;
				} else {
					return 1;
				}
			} else {
				return ERROR_IDB_NULL;
			}
		} else {
			return ERROR_IDB_NULL;
		}
	}
	
	function get_field_from_id($table,$field,$id){
		$sql = "SELECT $field FROM ".$table." WHERE id='".mysql_real_escape_string($id)."'";
		$data = $this->run_query_data($sql);
		if ($data['num']!=0){
			return $data['data'][$field];
		}
	}
	
	function serve_pics($mode,$filename,$size=''){
		$sizes = '';
		if (!empty($size)){
			$sizes = $size.'/';
		}
		return '<img src="'.MAINSITEURL.'img/'.$mode.'/'.$sizes.$filename.'">';
	}

	function serve_pics_ori($mode,$filename){
		return '<img src="'.MAINSITEURL.'images/'.$mode.'/'.$filename.'">';
	}
	
	function pagination_seo($total, $urls, $max_thread = 25){
		if (isset($_GET["paged"])){
			$paged = $_GET["paged"];
			if(empty($_GET["paged"]) or $_GET["paged"]<1){
				$paged = 1;
			}
		} else {
			$paged = 1;
		}
		
		$range = 10;
		$show_page = '';
		
		$totalpage = ceil($total/$max_thread);
		if($totalpage < $range){
			$range = $totalpage;
		}
		if($paged>$totalpage){
			$paged = 1;
		}
		$offset=($paged-1)*$max_thread;
		$limit = $offset.",".$max_thread;
		if ($totalpage > 1 ) {
		   	$range_min = ($range % 2 == 0) ? ($range / 2) - 1 : ($range - 1) / 2;
	    	$range_max = ($range % 2 == 0) ? $range_min + 1 : $range_min;
	       	$page_min = $paged - $range_min;
	       	$page_max = $paged + $range_max;
		    $page_min = ($page_min < 1) ? 1 : $page_min;
		    $page_max = ($page_max < ($page_min + $range - 1)) ? $page_min + $range - 1 : $page_max;
		    if ($page_max > $totalpage) {
	       		$page_min = ($page_min > 1) ? $totalpage - $range + 1 : 1;
	           	$page_max = $totalpage;
		    }
			$show_page .= '<div class="box-footer clearfix">';
			$show_page .= '<ul class="pagination pagination-sm no-margin pull-right">';
		    $page_min = ($page_min < 1) ? 1 : $page_min;
	       	if ($paged != 1) {
				$prev = $paged - 1;
				$url = str_replace('[[paged]]',$prev,$urls);
		       	$show_page .= '<li><a class="page-prev" href="'.$url.'">&laquo;</a></li>';
		    }
		    if ( ($paged > ($range - $range_min)) && ($totalpage > $range) ) {
				$url = str_replace('[[paged]]',1,$urls);
		       	$show_page .= '<li><a class="page-start" href="'.$url.'"><span class="border radius">1</span></a></li>';
		    }
		    for ($i = $page_min;$i <= $page_max;$i++) {
	    	  	if ($i == $paged){
	       	    	$show_page .= '<li><span class="border radius page-current">'.$i.'</span></li>';
	          	}else{
					$url = str_replace('[[paged]]',$i,$urls);
		            $show_page.= '<li><a class="page-record" href="'.$url.'"><span class="border radius">'.$i.'</span></a></li>';
	      		}
			}
		    if (($paged < ($totalpage - $range_max)) && ($totalpage > $range)) {
				$url = str_replace('[[paged]]',$totalpage,$urls);
		      	$show_page .= '<li><a class="page-end" href="'.$url.'"><span class="border radius">'.$totalpage.'</span></a></li>';
		    }
	 	    if ($paged < $totalpage) {
				$next = $paged + 1;
				$url = str_replace('[[paged]]',$next,$urls);
		       	$show_page .= '<li><a class="page-next" href="'.$url.'">&raquo;</a></li>';
		    }
			$show_page .= '</ul></div>';
		}
		$output = array("output" => $show_page, "limit" => $limit);
		return $output;
	}

	function set_heading($title){
		$this->heading_title = $title;
	}

	function get_heading($title){
		return $this->heading_title;
	}

	function encrypt_password($password){
        $enpassword = hash('sha512', __PASSWORD_SALT__.__PASSWORD_SALT__.$password.__PASSWORD_SALT__);
        $fpassword = md5($enpassword);
        return $fpassword;
    }

}

function &get_instance() {
	return Website::get_instance();
}

function load_class($class_name,$params=false) {
	if (!class_exists($class_name)){
		include CLASS_DIR.$class_name.'.php';
		$web = get_instance();
		if ($params==false){
			$web->{$class_name} = new $class_name;
		} else {
			$web->{$class_name} = new $class_name($params);
		}
	}
}

function load_model($model_name,$params=false) {
	if (!class_exists($model_name)){
		include MODEL_DIR.$model_name.'.php';
		$web = get_instance();
		if ($params==false){
			$web->{$model_name} = new $model_name;
		} else {
			$web->{$model_name} = new $model_name($params);
		}
	}
}

function load_db_class() {
	include DBCONFIG;
	include CLASS_DIR."class.mysqli.php";
	$web = get_instance();
	$web->db = new Database($dbHost, $dbUser, $dbPass, $dbname); 
	$web->db->connect();
}

function is_localhost(){
	if ($_SERVER['REMOTE_ADDR']=='127.0.0.1' || $_SERVER['REMOTE_ADDR']=='::1'){
		return true;
	} else {
		return false;
	}
}

// bootstrap
$web = new WebSite();
load_db_class();
$web->load_config();

load_class('forms');
load_class('breadcumbs');