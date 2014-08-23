<?php
if (!defined('_INC')) { die('404 Not Found'); }
$web->admin();
$web->set_heading(MENU_CONFIG);

if ( (isset($_GET['action'])) && (!empty($_GET['action'])) )
{
	$action = $_GET['action'];
} else {
	$action = '';	
}
	switch($action)
	{
		case 'edit':
			echo BACKLINK;
			if ( (isset($_POST['ppost'])) && ($_POST['ppost']==MENU_CONFIG_EDIT) )
			{
				$config_value =  $_POST['config_value'];
				if (!empty($config_value))
				{
					$idb = $_GET['idb'];
					$sb = "SELECT config_id FROM config WHERE config_id='$idb'";
					$gb = mysql_query($sb) or die(ERROR_DB);
					$cb = mysql_num_rows($gb);
					if ($cb!=0)
					{
						$rb = mysql_fetch_object($gb);
						$did = $rb->config_id;
						$config_value = mysql_real_escape_string($config_value);
						$qp = "UPDATE config SET config_value='$config_value' WHERE config_id='$did'";
						if (!mysql_query($qp)){
							echo '<div class="warning">'.ERROR_DB.'</div>';
						} else {
							$web->gotopage(THISFILE);
						}
					} else {
						echo '<div class="warning">'.ERROR_IDB_NULL.'</div>';
					}
				} else {
					echo '<div class="warning">'.ERROR_NULL.'</div>';
				}
			}
			if ( (isset($_GET['idb'])) && (!empty($_GET['idb'])) )
			{
				$idb = $_GET['idb'];
				$sb = "SELECT * FROM config WHERE config_id='$idb'";
				$gb = mysql_query($sb) or die(ERROR_DB);
				$cb = mysql_num_rows($gb);
				if ($cb!=0)
				{
					$rb = mysql_fetch_object($gb);
					$did = $rb->config_id;
					$config_name = $rb->config_name;
					$config_desc = $rb->config_desc;
					$config_value = $rb->config_value;
			?>
			<form method="post" action="?content=<?php echo THISFILE;?>&action=edit&idb=<?php echo $did;?>" onsubmit="javascript:submitForm();">
			<table border="0">
			  <tr>
			    <td valign="top"><?php echo MENU_CONFIG_DESC;?></td>
			    <td valign="top"> : </td>
			    <td><?php echo $config_desc; ?></td>
			  </tr>
			  <tr>
			    <td valign="top"><?php echo MENU_CONFIG_VALUE;?></td>
			    <td valign="top"> : </td>
			    <td><?php if ($config_name=='beta_site'){ ?>
			    	<input type="radio" name="config_value" value="Ya"<?php if ($config_value=="Ya"){ echo ' checked="checked"'; } ?> /> Ya
			    	<input type="radio" name="config_value" value="Tidak"<?php if ($config_value=="Tidak"){ echo ' checked="checked"'; } ?> /> Tidak
			    	<?php } else { ?> 
			    	<textarea style="width:400px;height:100px;" name="config_value"><?php echo $config_value; ?></textarea>
			    	<?php }?></td>
			  </tr>
			  <tr>
			  	<td colspan="2"></td>
			    <td><input class="input-button" type="submit" name="ppost" value="<?php echo MENU_CONFIG_EDIT;?>" /></td>
			  </tr>
			</table>
			</form>
			<?php				} else {
					echo '<div class="warning">'.ERROR_IDB_NULL.'</div>';
				}
			} else {
				echo '<div class="warning">'.ERROR_IDB_NULL.'</div>';
			}
		break;
		default:
			$sp = "SELECT * FROM config WHERE config_edit=1 ORDER BY config_id ASC";
			$gp = mysql_query($sp) or die(ERROR_DB);
			$jmlrec = mysql_num_rows($gp);
			
			if ($jmlrec>0)
			{
				$x = 0;
				echo '<table width="100%" cellpadding="3" cellspacing="0" border="0">
				<tr>
					<td class="tabel-head2">'.MENU_CONFIG_DESC.'</td>
					<td class="tabel-head2">'.MENU_CONFIG_VALUE.'</td>
					<td width="50" class="tabel-head2-end" align="center">Change</td>
				</tr>';
				while ($rp = mysql_fetch_object($gp))
				{
					$color = ($x% 2  ? '' : ' class="diffcolor"');
					$idb = $rp->config_id;
					$config_desc = $rp->config_desc;
					$config_value = $rp->config_value;
					?>
					<tr<?php echo $color; ?>>
						<td class="tabel-content"><?php echo $config_desc; ?></td>
						<td class="tabel-content"><?php echo htmlentities($config_value); ?></td>
						<td class="tabel-content-end" align="center">
							<?php echo $web->action_button('edit',THISFILE,$idb); ?>
						</td>
					</tr>
					<?php
					$x++;
				}
				echo '</table>';
			} else {
				echo MENU_PAGE_NULL;
				echo "<br /><br />";
			}
		break;
	}
?>
