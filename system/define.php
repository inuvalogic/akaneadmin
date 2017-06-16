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

if ($_SERVER['HTTP_HOST']!="localhost")
{
    define('SITE_ENV', 'prod');
	define('SITEPATH', '/');
    define('IMAGES_DIR', '../public_html/assets/images/');
    define('ASSETS_DIR', '../public_html/assets/');
    define('MAINSITEURL', 'http://websiteutama.com/');
} else {
    define('SITE_ENV', 'dev');
	define('SITEPATH', '/akaneadmin/');
    define('IMAGES_DIR', '../websiteutama/assets/images/');
    define('ASSETS_DIR', '../websiteutama/assets/');
    define('MAINSITEURL', 'http://localhost/websiteutama/');
}

define('SITEURL', 'http://'.$_SERVER['HTTP_HOST'].SITEPATH);

define('__PASSWORD_SALT__', '94r3mny4p455w0rdk4mu');
