<?php if (!defined('_INC')) { die('404 Not Found'); } ?>
<?php

if (isset($_SESSION['logid']))
{
	$web->gotopage('cpanel');
}

$error = array(
	'users' => '',
	'user' => '',
	'pass' => ''
);

if ( (isset($_POST["submit"])) ) {
	$username=strip_tags($_POST["username"]);
	$password=strip_tags($_POST["password"]);
	if (empty($username)) {
		$error['user'] = ERROR_USER_NULL;
	}
	if (empty($password)) {
		$error['pass'] = ERROR_PASS_NULL;
	}
	if ( (!empty($username)) && (!empty($password)))
    {
        load_model('admin');
        
        $password = $web->encrypt_password($password);
        $check = $web->admin->login($username, $password);

		if ($check!=NULL) {
			$user = $check[0];
			if ($user['status']=='active')
			{
				$_SESSION["logid"]=$user['id'];
				$_SESSION["username"]=$user['username'];
                $_SESSION["name"]=$user['name'];
				$_SESSION["level"]=$user['level'];
				if ( (isset($_GET['next'])) && (!empty($_GET['next'])) )
				{
					$web->gotopage($_GET['next']);
				} else {
					$web->gotopage('cpanel');
				}
            } else {
                $error['users'] = ERROR_USER_NO_ACTIVE;
			}
		} else {
			$error['users'] = ERROR_PASS_WRONG;
		}
        
	}
}

?>
<link href="css/signin.css" rel="stylesheet" type="text/css" />
<div class="form-box" id="login-box">
    <div class="header">Akane Admin<sup>v1.1</sup></div>
    <form action="?content=login&action=login" method="post">
        <div class="body bg-gray">
            <?php echo $error['users']; ?>
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username"/>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password"/>
            </div>          
        </div>
        <div class="footer">                                                               
            <button type="submit" name="submit" value="submit" class="btn bg-olive btn-block">Sign in</button>
        </div>
    </form>
    
</div>
<script type="text/javascript">
	$(function(){
		$('body').addClass('bg-black');
	});
</script>
