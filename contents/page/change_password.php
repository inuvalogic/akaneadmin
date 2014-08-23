<?php if (!defined('_INC')) { die('404 Not Found'); } ?>
<?php $web->admin(); ?>
<?php

	$id = $_SESSION['logid'];
	
	if ($_POST['change']=="Change Password"){
		$oldpass=$_POST["oldpass"];
		$oldpasswd=md5($oldpass);
		
		$newpass1=$_POST["newpass1"];
		$newpass2=$_POST["newpass2"];

		if (empty($oldpass)) {
            echo "<div class='warning'>Sorry Old Password Form is Empty</div>";
		}
		if (empty($newpass1) && empty($newpass2)) {
			echo "<div class='warning'>Sorry New Password Form is Empty</div>";
		}
		
		if (!empty($oldpass) && !empty($newpass1) && !empty($newpass2)) {
			$queryedit="select password from admin where id=$id";
			$resultedit=mysql_query($queryedit);
			$dataedit=mysql_fetch_array($resultedit);
			$pass=$dataedit['password'];
			$match = "";
			$match2 = "";
			if ($oldpasswd!==$pass) {
				echo "<div class='warning'>Wrong Old Password</div>";
			} else {
				$match="ok";
			}
			if ($newpass1!==$newpass2) {
				echo "<div class='warning'>New Password not Match</div>";
			} else {
				$match2="ok";
			}
			if (($match==="ok") && ($match2==="ok")) {
				$newpass=md5($newpass1);
				$queryupdate="update admin set password='$newpass' where id=$id";
				$resultupdate=mysql_query($queryupdate);
				if ($resultupdate) {
					echo "<div class='success'>Password Changed Successfully</div>";
				} else {
					echo ERROR_DB;
				}
			}
		}
	}


	?>
	<form method="post" action="?content=<?php echo THISFILE; ?>">
	<table width="65%" border="0" cellspacing="0" cellpadding="3">
	  <tr>
		<td>Old Password</td>
		<td><input class="input-text" type="password" name="oldpass" /></td>
	  </tr>
	  <tr>
		<td>New Password</td>
		<td><input class="input-text" type="password" name="newpass1" /></td>
	  </tr>
	  <tr>
		<td>New Password (again)</td>
		<td><input class="input-text" type="password" name="newpass2" /></td>
	  </tr>
	  <tr>
		<td colspan="2"><input class="input-button" type="submit" name="change" value="Change Password" /></td>
	  </tr>
	</table>
	</form>
