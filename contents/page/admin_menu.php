<?php if (!defined('_INC')) { die('404 Not Found'); } ?>
<?php $web->admin(); ?>
<ul id="jsddm">

	<li class="menu"><a href="<?php echo SITEURL; ?>?content=cpanel">Dashboard</a></li>
	
	<?php include "admin_menu_master.php"; ?>
	
	<li class="menu"><a href="javascript:void(0);">Setup</a>
		<ul>
			<li><a href="<?php echo SITEURL; ?>?content=web_config">Config</a></li>
			<li><a href="<?php echo SITEURL; ?>?content=change_password">Change Password</a></li>
		</ul>
	</li>
	
	<li class="menu"><a href="<?php echo SITEURL; ?>?content=logout">Logout</a></li>

</ul>

