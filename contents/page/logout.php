<?php if (!defined('_INC')) { die('404 Not Found'); } ?>
<?php $web->admin(); ?>
<?php
if (isset($_SESSION["logid"])) {
	session_destroy();
	$web->gotopage('home');
}
?>