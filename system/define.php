<?php

define('ONLINEMODE',0);

define("UP","../");

define("SYS_DIR","./system/");

define("LANG_DIR","./lang/");

define("LANG","./lang/lang.php");

define("TEMPLATE_DIR","./templates/");

define("JAVA_DIR","./java/");

define("CSS_DIR","./css/");

define("FEATURES_DIR","./contents/features/");

define("CONTENTS_DIR","./contents/");

define("SYSIMAGES_DIR","./sysimages/");

define('NEWS_PIC_DIR','images/content/');

define("LOGIN_DIR","./login/");

define("DBCONFIG",SYS_DIR."dbconfig.php");

define("CLASS_DIR",SYS_DIR."class/");

define("MODEL_DIR",SYS_DIR."model/");

define("HELPER",SYS_DIR."helper.php");

define("CLASSES",SYS_DIR."class/main.php");

define("SETTING",SYS_DIR."setting.php");

define("CONTENT",SYS_DIR."content.php");

if ($_SERVER['HTTP_HOST']=='localhost')
{
	define('SITEPATH', '/akane_admin/');
	define('SITE_ENV', 'dev');
} else {
	define('SITEPATH', '/');
	define('SITE_ENV', 'prod');
}

define('SITEURL', 'http://'.$_SERVER['HTTP_HOST'].SITEPATH);
