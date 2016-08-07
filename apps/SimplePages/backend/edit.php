<?php
require_once '../../../inc/autoload.php';
printHeader($lang->get('sp_edit'));
if (hasPerm('manage_pages'))
{
	tinymce();
	if (isset($_GET['id']) || isset($_GET['new']))
	{
		$db->setCol('simplePages_pages');
		if (isset($_POST['submit']))
		{
			$content = $_POST['content'];

			$pos = strpos($content, 'EXEC');
			if ($pos !== false)
			{
				$content = strip_tags($content);
			}

			/*if (!$confirmationRequierd)
			{
				$time = time();
				$seite = str_replace('-', ' ', $seite);
				$seite = str_replace('ä', '&auml;', $seite);
				$seite = str_replace('ö', '&ouml;', $seite);
				$seite = str_replace('ü', '&uuml;', $seite);
				//echo $seite;
				$stmt = $DBH->prepare("UPDATE " . $MCONF['db_prefix'] . "seiten SET inhalt=:inhalt, datel=:datel, userl=:userl WHERE name=:seite");
				$stmt->bindParam(':inhalt', $content);
				$stmt->bindParam(':datel', $time);
				$stmt->bindParam(':userl', $_SESSION['user']);
				$stmt->bindParam(':seite', $seite);

				if ($stmt->execute())
				{
					echo msg('succes', 'Die Seite "' . $seite . '" wurde erfolgreich ge&auml;ndert. <a href="management.php">Zurück</a>');
				} else
				{
					echo msg('fail');
				}
			} else
			{
				$bnutzrid = $_SESSION['user'];
				//echo $seite;
				$seite = str_replace('-', ' ', $seite);
				$seite = str_replace('ä', '&auml;', $seite);
				$seite = str_replace('ö', '&ouml;', $seite);
				$seite = str_replace('ü', '&uuml;', $seite);
				//echo $seite;
				if ($_SESSION['user'] === $confirmationUser)
				{
					$time = time();
					$STH = $DBH->prepare("UPDATE " . $MCONF['db_prefix'] . "seiten SET inhalt=:inhalt, datel=:datel, userl=:userl WHERE name=:seite");
					$STH->bindParam(':inhalt', $content);
					$STH->bindParam(':datel', $time);
					$STH->bindParam(':userl', $_SESSION['user']);
					$STH->bindParam(':seite', $seite);

					if ($STH->execute())
					{
						$STH2 = $DBH->prepare("DELETE FROM " . $MCONF['db_prefix'] . "seitenneu WHERE name=:name");
						$STH2->bindParam(':name', $seite);
						if ($STH2->execute())
						{
							echo msg('succes', 'Die Seite "' . $seite . '" wurde erfolgreich ge&auml;ndert. <a href="management.php">Zurück</a>');
						} else
						{
							echo msg('fail');
						}
					} else
					{
						echo msg('fail');
					}
				} else
				{
					$time = time();
					$stmt2 = $DBH->prepare("INSERT INTO " . $MCONF['db_prefix'] . "seitenneu (name, inhalt, geaendertby, datum) VALUES (:name, :inhalt, :geaendertby, :datum)");
					$stmt2->bindParam(':name', $seite);
					$stmt2->bindParam(':inhalt', $content);
					$stmt2->bindParam(':geaendertby', $bnutzrid);
					$stmt2->bindParam(':datum', $time);

					if ($stmt2->execute() and freischaltemail($seite))
					{
						echo msg('succes', 'Die Seite ' . $seite . ' wurde erfolgreich ge&auml;ndert. Diese &Auml;nderungen werden &ouml;ffentlich, sobald ' . $confirmationUser . ' sie Freigegeben hat. <a href="management.php">Zurück</a>');
					} else
					{
						echo msg('fail');
					}
				}*/
			//
			$db->clear();
			//If a confirmation by user is requiered, insert it in another table and send an email
			if($confirmationRequierd)
			{
				if($_SESSION['userid'] == $confirmationUser)
				{
					$db->setCol('simplePages_pages');
				}
				else
				{
					//Delete old pages, we only want the newest version in the database
					$db->clear();
					$db->setCol('simplePages_pages_confirm');
					$db->data['page_id'] = $_GET['id'];
					$db->delete();

					$db->setCol('simplePages_pages_confirm');
					$db->data['page_id'] = $_GET['id'];
				}
			}
			else
			{
				$db->setCol('simplePages_pages');
			}

			$db->data['title'] = $_POST['title'];
			$db->data['alias'] = str_replace(' ', '-', $_POST['alias']);
			$db->data['content'] = $_POST['content'];
			$db->data['user'] = $_SESSION['userid'];
			$status = 0;
			if (isset($_POST['status'])) $status = $_POST['status'];
			$db->data['status'] = $status;
			$db->data['meta_description'] = $_POST['meta_description'];
			$db->data['meta_keywords'] = $_POST['meta_keywords'];
			$db->data['lastedit'] = time();

			//$db->update(['id' => $_GET['id']]);
			$id = 0;
			if (isset($_GET['new']))
			{
				if(hasPerm('create_new'))
				{
					if ($db->insert())
					{
						$id = $db->lastID();
						if($confirmationRequierd && ($_SESSION['userid'] != $confirmationUser))
						{
							echo msg('succes', sprintf($lang->get('sp_edit_created'), $_POST['title']).' '.sprintf($lang->get('sp_edit_edited_need_confirm'), getUserByID($confirmationUser)).' <a href="management.php">'.$lang->get('back').'</a>');
						}
						else
						{
							echo msg('succes', sprintf($lang->get('sp_edit_created'), $_POST['title']).' <a href="management.php">'.$lang->get('back').'</a>');
						}
					}
					else
					{
						echo msg('fail', $lang->get('sp_edit_edit_error'));
					}
				}
				else
				{
					echo msg('info', $lang->get('missing_permission').' {back}');
				}
			}
			else
			{
				$id = $_GET['id'];
				if($confirmationRequierd && ($_SESSION['userid'] != $confirmationUser))
				{
					if ($db->insert())
					{
						echo msg('succes', sprintf($lang->get('sp_edit_edited'), $_POST['title']).' '.sprintf($lang->get('sp_edit_edited_need_confirm'), getUserByID($confirmationUser)).' <a href="management.php">'.$lang->get('back').'</a>');
					}
					else
					{
						echo msg('fail', $lang->get('sp_edit_edit_error'));
					}
				}
				else
				{
					if ($db->update(['id' => $_GET['id']]))
					{
						echo msg('succes', sprintf($lang->get('sp_edit_edited'), $_POST['title']).' <a href="management.php">'.$lang->get('back').'</a>');
					}
					else
					{
						echo msg('fail', $lang->get('sp_edit_edit_error'));
					}
				}
			}

			//Send an email
			if($confirmationRequierd && ($_SESSION['userid'] != $confirmationUser))
			{
				$subject = sprintf($lang->get('sp_edit_confirm_subject'), $_POST['title']);
				$message = sprintf($lang->get('sp_edit_confirm_message'), $_SESSION['user'], $_POST['title'], $MCONF['web_uri'] . 'apps/SimplePages/backend/confirm.php?page=' . $id);
				$header = 'From: noreply@' . $_SERVER['SERVER_NAME'] . "\r\n" .
					'Reply-To: noreply@' . $_SERVER['SERVER_NAME'] . "\r\n" .
					'X-Mailer: PHP/' . phpversion() .
					'MIME-Version: 1.0' . "\r\n" .
					'content-type: text/html; charset=UTF-8' . "\r\n";
				mail($confirmationUserMail, $subject, $message, $header);
			}
		}
		else
		{
			if (isset($_GET['del']))
			{
				if (isset($_POST['confirm']))
				{
					$db->data['id'] = $_GET['id'];
					if ($db->delete())
					{
						echo msg('succes', $lang->get('sp_edit_delete_success').' <a href="management.php">'.$lang->get('back').'</a>');
					}
					else
					{
						echo msg('fail');
					}
				}
				else
				{
					echo '<div class="main">';
					$db->data['id'] = $_GET['id'];
					$db->get();
					if (isset($db->data[0]['title']))
					{
						echo '<h1>'.sprintf($lang->get('sp_edit_delete'), $db->data[0]['title']) . '</h1>
						<p>'.sprintf($lang->get('sp_edit_delete_confirm'), $db->data[0]['title']).'<br/>
							<form acrion="" method="post">
								<input type="submit" name="confirm" value="'.$lang->get('general_yes').'"/>
								<a href="management.php" class="button btn_del">'.$lang->get('general_no').'</a>
							</form>';
					}
					else
					{
						echo $lang->get('sp_edit_page_not_found').'';
					}
				}
			}
			else
			{
				if(!hasPerm('create_new') && isset($_GET['new']))
				{
					echo msg('info', $lang->get('missing_permission').' {back}');
					exit;
				}
				echo '<div class="main">';

				//If the page has unconfirmed changes, show a message
				if(isset($_GET['id']))
				{
					$db->clear();
					$db->setCol('simplePages_pages_confirm');
					$db->data['page_id'] = $_GET['id'];
					$db->get();
					if (isset($db->data[0]))
					{
						echo '<p><i class="icon-info-circle"></i> ' . sprintf($lang->get('sp_edit_edited_not_confirmed'), date('d.m.Y \u\m H:i', $db->data[0]['lastedit']), getUserByID($db->data[0]['user'])) . '</p>';
					}
				}

				$data[0] = ['title' => '', 'alias' => '', 'status' => '', 'meta_keywords' => '', 'meta_description' => '', 'content' => ''];
				if (isset($_GET['id']))
				{
					$db->setCol('simplePages_pages');
					$db->data['id'] = $_GET['id'];
					$db->get();
					$data[0] = $db->data[0];
				}

				if (isset($db->data[0]['id']) || isset($_GET['new']))
				{
					if (isset($_GET['new']))
					{
						echo '<h1>'.$lang->get('sp_edit_create_new').'</h1>';
					}
					else
					{
						echo '<h1>'.sprintf($lang->get('sp_edit_edit_page'), $data[0]['title']) . '</h1>';
					}

					if (isset($data[0]['user'], $data[0]['lastedit'])) echo '<p>'.sprintf($lang->get('sp_edit_last_edited'), getUserByID($data[0]['user']), date('d.m.Y H:i', $data[0]['lastedit'])) . '</p>';
					?>

					<span id="response"></span>
					<form id="edit" method="post" action="<?php echo $_SERVER['REQUEST_URI']?>" class="form">
						<p><span><?php echo $lang->get('sp_edit_title');?>:</span><input type="text" name="title" value="<?php echo $data[0]['title']; ?>"/>
						</p>
						<p><span><?php echo $lang->get('sp_edit_alias');?>:</span><input type="text" name="alias"
														   value="<?php echo $data[0]['alias']; ?>" autocomplete="off"/>
						</p>
						<p><span><?php echo $lang->get('sp_edit_public');?>:</span><input type="checkbox" name="status" id="status" value="1"<?php
							if ($data[0]['status'] == 1) echo ' checked';
							?>/><label for="status"><i></i></label></p>
						<p><span><?php echo $lang->get('sp_edit_keywords');?>:</span><input type="text" name="meta_keywords"
														value="<?php echo $data[0]['meta_keywords']; ?>"/></p>
						<p><span><?php echo $lang->get('sp_edit_description');?>:</span><textarea
								name="meta_description"><?php echo $data[0]['meta_description']; ?></textarea></p>
						<p></p>
						<p><?php echo $lang->get('sp_edit_content');?>:</p>
						<textarea name="content" id="editor"><?php echo $data[0]['content']; ?></textarea>
						<input type="submit" name="submit" value="<?php echo $lang->get('sp_edit_save');?>"/>
					</form>
					<?php
				}
				else
				{
					echo $lang->get('sp_edit_page_not_found');
				}
			}
		}
	} else
	{
		echo $lang->get('sp_edit_no_page_defined');
	}
	?>
	</div>
	<?php
}
else
{
	echo msg('info', $lang->get('missing_permission').' {back}');
}
require_once '../../../inc/footer.php';
?>