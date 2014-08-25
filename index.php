<?php

define('_INC' ,1);

include "system/define.php";

if (SITE_ENV=='dev'){
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
	ini_set('display_errors',1);
} else {
	error_reporting(0);
}

session_start();

if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

$lang2 = array();
$lang = array();

include DBCONFIG;
include SETTING;
include HELPER;
include CLASSES;
include LANG;
include CONTENT;

$web->init($lang2,$template_use);
$web->show();
