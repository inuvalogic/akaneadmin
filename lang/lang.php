<?php

if (!isset($language)){
	$language = 'indonesia';
}

$error = array();

include $language."/lang-error.php";

$error_key = array_keys($error);
foreach($error_key as $key)
{
	$defines = 'define("'.$key.'","<div id=\'warning\'>".$error[\''.$key.'\']."</div>");';
	eval($defines);
}

$langmenu = array();

include $language."/lang-menu.php";
include "lang-link.php";

$menu_key = array_keys($langmenu);
foreach($menu_key as $key2)
{
	$defines2 = 'define("'.$key2.'",$langmenu[\''.$key2.'\']);';
	eval($defines2);
}

include $language."/lang-main.php";
?>