<?php

if(!function_exists('add_br'))
{
	function add_br($text){
		$text2 = str_replace(chr(10), '<br />', $text);
		$text2 = str_replace(chr(13), '<br />', $text);
		$text2 = str_replace("\r\n", '<br />', $text);
		return $text2;
	}
}

if(!function_exists('serve_price'))
{
	function serve_price($num){
		if (empty($num)){ $num = 0; }
		return number_format($num,0,'','.');
	}
}

/* DATE HELPER */

if(!function_exists('serve_date'))
{
	function serve_date($date,$mode='date'){
		switch($mode){
			case 'time';
				$format = "H:i";
			break;
			case 'days';
				$format = "D";
			break;
			case 'fullday';
				$format = "l";
			break;
			case 'full';
				$format = "D, d M Y";
			break;
			default:
				$format = "d M Y";
			break;
		}
		return date($format, strtotime($date));
	}
}

if(!function_exists('is_weekend'))
{
	function is_weekend($date=false){
		if ($date==false){
			$today = date('D');
		} else {
			$today = date('D', strtotime($date));
		}
		if ($today=='Sat' || $today=='Sun'){
			return true;
		} else {
			return false;
		}
	}
}

if(!function_exists('date_different'))
{
	function date_different($einddatum,$startdatum){
		$now = strtotime($startdatum);
		$your_date = strtotime($einddatum);
		$datediff = $your_date - $now;
		$number = floor($datediff/(60*60*24));
		return $number;		
	}
}
