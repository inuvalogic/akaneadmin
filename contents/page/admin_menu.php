<?php if (!defined('_INC')) { die('404 Not Found'); } ?>
<?php $web->admin(); ?>
<ul id="jsddm">

	<li class="menu"><a href="<?php echo SITEURL; ?>?content=cpanel"><?php echo MENU_HOME ?></a></li>
	
	<?php include "admin_menu_master.php"; ?>
	
	<li class="menu"><a href="javascript:void(0);"><?php echo MENU_SETUP ?></a>
		<ul>
			<li><a href="<?php echo SITEURL; ?>?content=web_config"><?php echo MENU_CONFIG ?></a></li>
			<li><a href="<?php echo SITEURL; ?>?content=change_password"><?php echo MENU_PASS ?></a></li>
		</ul>
	</li>
	
	<li class="menu"><a href="<?php echo SITEURL; ?>?content=logout"><?php echo MENU_LOGOUT; ?></a></li>

</ul>

