<?php

if (!defined('_INC')){ die('404 Not Found'); }

if ($_SERVER['SERVER_ADDR']=="127.0.0.1"){
	$dbHost="localhost";
	$dbUser="root";
	$dbPass="";
	$dbname="akane_admin";
} else {
	$dbHost = "localhost";
	$dbUser = "";
	$dbPass = "";
	$dbname = "";
}

$koneksi_db = @mysql_connect($dbHost, $dbUser, $dbPass);
if (!$koneksi_db){
	echo 'Database maintenance problem! Please try again';
	exit();
} else {
	@mysql_select_db($dbname, $koneksi_db);
}

