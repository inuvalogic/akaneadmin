<?php
if (!defined('_INC')) { die('404 Not Found'); }
$web->admin();
$web->set_heading(MENU_PASS);
$web->breadcumbs->add('current','Setup');
$web->breadcumbs->add('current','Change Password');

$id = $_SESSION['logid'];

if ( (isset($_POST['ppost'])) && ($_POST['ppost']==MENU_EDIT) )
{
	$oldpass = $_POST["old_password"];
	$oldpasswd = $web->encrypt_password($oldpass);
	
	$newpass1 = $_POST["new_password"];
	$newpass2 = $_POST["new_password_retype"];

	if (empty($oldpass)) {
        echo ERROR_OLD_PASS_EMPTY;
	}
	if (empty($newpass1) && empty($newpass2)) {
		echo ERROR_NEW_PASS_EMPTY;
	}
	
	if (!empty($oldpass) && !empty($newpass1) && !empty($newpass2))
	{
		load_model('admin');

		$admin = $web->admin->single($id);
		$admindata = $admin[0];
		$pass = $admindata['password'];

		$match = "";
		$match2 = "";
		if ($oldpasswd!==$pass) {
			echo ERROR_OLD_PASS_WRONG;
		} else {
			$match="ok";
		}
		if ($newpass1!==$newpass2) {
			echo ERROR_NEW_PASS_NOT_MATCH;
		} else {
			$match2="ok";
		}
		if (($match==="ok") && ($match2==="ok")) {
			unset($_POST['ppost']);
			unset($_POST['old_password']);
			unset($_POST['new_password']);
			unset($_POST['new_password_retype']);
						
			$_POST['password'] = $web->encrypt_password($newpass1);
			$_POST['action'] = 'update';
			$_POST['table'] = 'admin';
			$_POST['where'] = 'id='.$id;

			if ($web->db->auto_save()) {
				echo $web->success_message('Password Changed Successfully');
				$web->gotopage(THISFILE, 2);
			} else {
				echo ERROR_DB;
			}
		}
	}
}

$this->forms
	->add('old_password', 'password', array('required' => true))
	->add('new_password', 'password', array('required' => true))
	->add('new_password_retype', 'password', array('required' => true))
->renderForm(MENU_EDIT);
