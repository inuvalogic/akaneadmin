<?php if (!defined('_INC')) { die('404 Not Found'); } ?>
<?php

if( (isset($_SESSION['logid'])) && $_SESSION['level']==1 )
{
	$web->gotopage('cpanel');
}

$error = array(
	'users' => '',
	'user' => '',
	'pass' => ''
);

if ( (isset($_GET["action"])) && ($_GET["action"] == "login") ) {
	$username=strip_tags($_POST["username"]);
	$password=strip_tags($_POST["password"]);
	if (empty($username)) {
		$error['user'] = ERROR_USER_NULL;
	}
	if (empty($password)) {
		$error['pass'] = ERROR_PASS_NULL;
	}
	if ( (!empty($username)) && (!empty($password))){
		$countlog =0;
		$password=md5($password);
		$queryceklog="select id,username,level,nama,status from admin where username='$username' and password='$password' and level=1";
		$resultlog=mysql_query($queryceklog) or die(ERROR_DB);
		$countlog=mysql_num_rows($resultlog);
		if ($countlog==1) {
			$user=mysql_fetch_array($resultlog);
			if ($user['status']==2)
			{
				$error['users'] = ERROR_USER_NO_ACTIVE.'<br />';
			} else {
				$_SESSION["logid"]=$user['id'];
				$_SESSION["username"]=$user['username'];
				$_SESSION["level"]=$user['level'];
				$_SESSION["name"]=$user['nama'];
				if ( (isset($_GET['next'])) && (!empty($_GET['next'])) )
				{
					$web->gotopage($_GET['next']);
				} else {
					$web->gotopage('cpanel');
				}
			}
			mysql_free_result($resultlog);
		} else {
			$error['users'] = ERROR_PASS_WRONG.'<br />';
		}
	}
}
?>

<div id="login-page">
	<div id="login-page-head" class="corner-top">Administrator <?php echo MENU_LOGIN; ?></div>
	<div id="login-page-content" class="corner-bottom">
	
	<div id="loginform">
	<?php echo $error['users']; ?>
		<form action="?content=login&action=login" method="post">
		<label for="uname">Username</label><br />
		<input class="input-text-login" type="text" name="username" value="<?php echo $error['user']; ?>" /><br /><br />
		<label for="pword">Password</label><br />
		<input class="input-text-login" type="password" name="password" value="<?php echo $error['pass']; ?>" /><br /><br />
		<input class="input-button" type="submit" name="Submit" value="Login" /><br />
		</form>
	</div>
	</div>
</div>


