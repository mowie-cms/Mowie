<?php
require_once '../../../inc/autoload.php';
printHeader($lang->get('sp_confirm'));
if (isset($_GET['page']))
{
	if ($_SESSION['userid'] == $confirmationUser)
	{
		//Get the new Content
		$db->setCol('simplePages_pages_confirm');
		$db->data['page_id'] = $_GET['page'];
		$db->get();

		if (isset($db->data[0]))
		{
			$data_new = $db->data[0];

			//If submitted, insert the new content
			if(isset($_POST['confirm']))
			{
				$db->setCol('simplePages_pages');
				$db->data = json_decode($_POST['contentToUpdate'], true);
				if($db->update(['id' => $_GET['page']]))
				{
					//Delete the old data
					$db->setCol('simplePages_pages_confirm');
					$db->data['page_id'] = $_GET['page'];
					$db->delete();
					echo msg('success', $lang->get('sp_confirm_success').' <a href="management.php">'.$lang->get('back').'</a>');
				}
				else
				{
					echo msg('fail', $lang->get('sp_confirm_fail').' <a href="management.php">'.$lang->get('back').'</a>');
				}

			}//If request to delete, delete it
			elseif(isset($_POST['delete']))
			{
				$db->setCol('simplePages_pages_confirm');
				$db->data['page_id'] = $_GET['page'];
				if($db->delete())
				{
					echo msg('success', $lang->get('sp_confirm_delete_success').' <a href="management.php">'.$lang->get('back').'</a>');
				}
				else
				{
					echo msg('fail', $lang->get('sp_confirm_delete_fail').' <a href="management.php">'.$lang->get('back').'</a>');
				}
			}
			else
			{
				//Get the old Content
				$db->setCol('simplePages_pages');
				$db->data['id'] = $_GET['page'];
				$db->get();
				if (isset($db->data[0]))
				{
					$changed = false;
					$data_old = $db->data[0];

					//Find differences between them
					$data_display = [];
					foreach ($data_new as $key => $new_data)
					{
						if (array_key_exists($key, $data_old))
						{
							if ($new_data != $data_old[$key] && $key != 'id' && $key != 'user' && $key != 'lastedit')
							{
								$changed = true;
								$data_display[$key] = $new_data;
							}
						}
					}

					//Only display differences if any found
					if($changed)
					{
						echo '<div class="main">';
						echo '<p>'.sprintf($lang->get('sp_confirm_changed'), date('d.m.Y \u\m H:i', $data_new['lastedit']), getUserByID($data_new['user'])).':</p>';
						$msg = [
							'title' => $lang->get('sp_edit_title'),
							'alias' => $lang->get('sp_edit_alias'),
							'content' => $lang->get('sp_edit_content'),
							'status' => $lang->get('sp_edit_status'),
							'meta_description' => $lang->get('sp_edit_description'),
							'meta_keywords' => $lang->get('sp_edit_keywords')
						];
						foreach ($data_display as $key => $data)
						{
							echo '<p><b>' . $msg[$key] . ':</b><br/>' . $data . '</p>';
						}

						$data_display['user'] = $data_new['user'];
						$data_display['lastedit'] = $data_new['lastedit'];

						//echo '<pre>'.print_r($data_old, true).'</pre>';
						echo '<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post"><input type="hidden" name="contentToUpdate" value=\'' . json_encode($data_display) . '\'/><input type="submit" name="confirm" value="'.$lang->get('sp_confirm_confirm').'"/><input type="submit" name="delete" value="'.$lang->get('sp_confirm_delete').'" class="btn_del"/></form>';
						echo '</div>';
					}
					else
					{
						echo msg('info', $lang->get('sp_confirm_no_changes').' <a href="management.php">'.$lang->get('back').'</a>');
					}
				}
				else
				{
					echo msg('info', $lang->get('sp_confirm_no_changes').' <a href="management.php">'.$lang->get('back').'</a>');
				}
			}
		}
		else
		{
			echo msg('info', $lang->get('sp_confirm_no_changes').' <a href="management.php">'.$lang->get('back').'</a>');
		}
	}
	else
	{
		echo msg('info', $lang->get('missing_permission').' <a href="management.php">'.$lang->get('back').'</a>');
	}
}
require_once '../../../inc/footer.php';