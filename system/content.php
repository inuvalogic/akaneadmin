<?php

$lang2['MAINSITEURL'] = MAINSITEURL;
$lang2['TEMPLATES_DIR'] = TEMPLATE_DIR.$template_use;

define('TEMPLATES_DIR',$lang2['TEMPLATES_DIR']);
	
$CSSLoadFile = CSS_DIR."load.php";
$JavascriptLoadFile = JAVA_DIR."load.php";
include $CSSLoadFile;
include $JavascriptLoadFile;

include SYS_DIR."custom.php";

$weboutput = '';

if ($web->check_login()==1)
{
	$weboutput = '';
	$webcontent = '';
	
	if ( isset($_GET['content']) )
	{
		$ContentFileGet = $_GET['content'];
		if ( file_exists(CONTENTS_DIR.'page/'.$ContentFileGet.'.php') )
		{
			define('THISFILE',$ContentFileGet);
			$webcontent .= $web->addcontent('page/'.$ContentFileGet.'.php');
		} else {
			$ContentFileGet = 'home';
			$webcontent .= $web->addcontent("page/home.php");
		}
	} else {
		$ContentFileGet = 'home';
		$webcontent .= $web->addcontent("page/home.php");
	}
	
	$header = $web->addcontent("section/header.php");
	$footer = $web->addcontent("section/footer.php");
	
	$weboutput .= $header.$webcontent.$footer;
	
} else {
	$weboutput .= $web->addcontent("page/login.php");
}

$lang2['CONTENTS'] = $weboutput;
