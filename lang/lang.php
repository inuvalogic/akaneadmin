<?php

if (!isset($language)){
	$language = 'indonesia';
}

$error = array();

include $language."/lang-error.php";

$error_key = array_keys($error);

$error_format = '<div class="alert alert-danger alert-dismissable">
    <i class="fa fa-ban"></i>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    %s
</div>';

foreach($error_key as $key)
{
    define($key, sprintf($error_format, $error[$key]));
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