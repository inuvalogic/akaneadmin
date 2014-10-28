<?php
/*
 *
 * Admin Controller for table article
 * generated on 28 October 2014 13:54:29
 *
 *
 * This file is auto generated by Akane Console Tools
 * you can customize it to your need
 * for more information
 * type command "php console" from Akane directory on Terminal console
 * 
 */

$web->admin();
$web->set_heading('Article');
load_model('article');

if ( (isset($_GET['action'])) && (!empty($_GET['action'])) )
{
	$action = $_GET['action'];
} else {
	$action = '';
}
	switch($action)
	{
		case 'add':
			echo BACKLINK;
			if ( (isset($_POST['ppost'])) && ($_POST['ppost']==MENU_ADD) )
			{
				if (!empty($_POST['judul']) && !empty($_POST['isi']) && !empty($_POST['tags']) && !empty($_POST['publish'])) {
					unset($_POST['ppost']);
					$_POST['action'] = 'insert';
					$_POST['table'] = 'article';

					$web->db->auto_save();
					$web->gotopage(THISFILE);
				} else { echo ERROR_NULL; }
			}

			$this->forms
				->add('judul', 'text', array('required' => true))
				->add('isi', 'textarea', array('required' => true, 'params' => array('class' => 'tinymce')))
				->add('tags', 'text', array('required' => true))
				->add('publish', 'select', array('select_data' => array('Yes' => 'Yes', 'No' => 'No'), 'required' => true))

			->renderForm(MENU_ADD);

		break;
		case 'edit':
			echo BACKLINK;
			if ( (isset($_GET['idb'])) && (!empty($_GET['idb'])) )
			{
				$idb = $_GET['idb'];
				$data = $web->article->single($idb);
				$cb = count($data);
				if ($cb!=0)
				{
					$rb = $data[0];

					if ( (isset($_POST['ppost'])) && ($_POST['ppost']==MENU_EDIT) )
					{
						if (!empty($_POST['judul']) && !empty($_POST['isi']) && !empty($_POST['tags']) && !empty($_POST['publish'])) {
							unset($_POST['ppost']);
							$_POST['action'] = 'update';
							$_POST['table'] = 'article';
							$_POST['where'] = 'id='.$rb['id'];
							$web->db->auto_save();
							$web->gotopage(THISFILE);
						} else { echo ERROR_NULL; }
					}

					$this->forms
				->add('judul', 'text', array('required' => true, 'value' => $rb['judul']))
				->add('isi', 'textarea', array('required' => true, 'value' => $rb['isi'], 'params' => array('class' => 'tinymce')))
				->add('tags', 'text', array('required' => true, 'value' => $rb['tags']))
				->add('publish', 'select', array('select_data' => array('Yes' => 'Yes', 'No' => 'No'), 'required' => true, 'value' => $rb['publish']))

					->renderForm(MENU_EDIT);

				} else {
					echo ERROR_IDB_NULL;
				}
			} else {
				echo ERROR_IDB_NULL;
			}
		break;
		case 'delete':
			if ( (isset($_GET['idb'])) && (!empty($_GET['idb'])) )
			{
				$idb = $_GET['idb'];
				$data = $web->article->single($idb);
				$cb = count($data);
				if ($cb!=0)
				{
					$rb = $data[0];
					$delete = $web->db->query_delete('article',array('id' => $rb['id']));
					if ($delete){
						$web->gotopage(THISFILE);
					}
				} else {
					echo ERROR_IDB_NULL;
				}
			} else {
				echo ERROR_IDB_NULL;
			}
		break;
		default:
			$web->add_button();
			$web->search_form();
			
			$keyword = '';
			$searchlink = '';
			if (isset($_GET['keyword'])){
				$keyword = $_GET['keyword'];
				$searchlink = '&keyword='.$_GET['keyword'];
			}
			
			$data = $web->article->all('',$keyword);
			$jmlrec = count($data);
			
			$url = SITEURL.'?content='.THISFILE.$searchlink.'&paged=[[paged]]';
            $paging = $web->pagination_seo($jmlrec, $url);
            
            $data = $web->article->all($paging['limit'],$keyword);
            $jmlr = count($data);
            
			if ($jmlrec>0)
			{
				$x = 0;
				echo '<table width="100%" cellpadding="3" cellspacing="0" border="0">
				<tr>
					<td class="tabel-head2">Judul</td>
					<td class="tabel-head2">Publish</td>
					<td width="50" class="tabel-head2-end" align="center">Action</td>
				</tr>';
				foreach($data as $data)
				{
					$color = ($x% 2  ? '' : ' class="diffcolor"');
					?>
					<tr<?php echo $color; ?>>
						<td class="tabel-content"><?php echo $data['judul']; ?></td>
						<td class="tabel-content"><?php echo $data['publish']; ?></td>
						<td class="tabel-content-end" align="center">
							<?php echo $web->action_button('edit',THISFILE,$data['id']); ?>
							<?php echo $web->action_button('delete',THISFILE,$data['id'],'this data'); ?>
						</td>
					</tr>
					<?php
					$x++;
				}
				echo '</table>';
				echo $paging['output'];
			} else {
				echo ERROR_EMPTY;
				echo "<br /><br />";
			}
		break;
	}
?>
