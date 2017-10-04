<?php
error_reporting(E_ALL);
require_once '../inc/autoload_adm.php';
printHeader($lang->get('user_settings_title'));
require_once '../inc/libs/password.php';

$uid = $_SESSION['userid'];
if (isset($_GET['uid'])) $uid = $_GET['uid'];

if (hasPerm('manage_admins') || $uid == $_SESSION['userid'])
{
	if (isset($_GET['pw_new']))
	{
		if (isset($_POST['pw_new']))
		{
			if ($_POST['pw_new'] == $_POST['pw_new2'])
			{
				if ($_POST['pw_new'] == '')
				{
					echo msg('fail', $lang->get('user_settings_pw_not_empty') . ' {back}');
				} else
				{
					$user = $_SESSION['userid'];
					if (isset($_GET['uid'])) $user = $_GET['uid'];

					$db->setCol('system_admins');
					$db->data['pass'] = password_hash($_POST['pw_new'], PASSWORD_DEFAULT);
					if ($db->update(['id' => $user]))
					{
						echo msg('success', $lang->get('user_settings_pw_change_success') . ' <a href="index.php">' . $lang->get('back_dashboard') . '</a>');
						stream_message('{user}\'s password was changed.', 4);
					} else
					{
						echo msg('fail', $lang->get('user_settings_pw_change_fail') . ' {back}');
					}
				}
			} else
			{
				echo msg('fail', $lang->get('user_settings_pw_not_match') . ' {back}');
			}
		} else
		{
			?>
            <div class="main">
                <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
                    <input type="password" name="pw_new"
                           placeholder="<?php echo $lang->get('user_settings_new_pass'); ?>" autofocus/><br/>
                    <input type="password" name="pw_new2"
                           placeholder="<?php echo $lang->get('user_settings_new_pass_confirm'); ?>"/><br/>
                    <input type="submit" value="<?php echo $lang->get('confirm'); ?>"/>
                </form>
            </div>
			<?php
		}
	} elseif (isset($_GET['pw_u']))
	{
		if (isset($_POST['pw']))
		{
			$benutzername = $_SESSION['user'];
			$passwort = $_POST['pw'];
			$db->clear();
			$db->setCol('system_admins');
			$db->data['id'] = $uid;
			$db->get();
			if ($db->data[0]['id'] !== '')
			{
				if (password_verify($_POST['pw'], $db->data[0]['pass']))
				{
					echo '<div class="main"><p>' . $lang->get('user_settings_new_pass') . '</p>';
					?>
                    <form action="<?php echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>?pw_new" method="post">
                        <input type="password" name="pw_new"
                               placeholder="<?php echo $lang->get('user_settings_new_pass'); ?>" autofocus/><br/>
                        <input type="password" name="pw_new2"
                               placeholder="<?php echo $lang->get('user_settings_new_pass_confirm'); ?>"/><br/>
                        <input type="submit" value="<?php echo $lang->get('confirm'); ?>"/>
                    </form>
                    </div>
					<?php
				} else
				{
					echo msg('fail', $lang->get('wrong_pass') . ' {back}');
				}
			}
		} else
		{
			?>
            <div class="main">
                <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
                    <input type="password" name="pw" autofocus
                           placeholder="<?php echo $lang->get('user_settings_enter_current_pass'); ?>"/><br/>
                    <input type="submit" value="<?php echo $lang->get('confirm'); ?>"/>
                </form>
            </div>
			<?php
		}
	}//Sessions
    elseif (isset($_GET['sessions']))
	{
		$db->setCol('system_loggedin');
		if (isset($_POST['smbt']))
		{
			if ($db->delete(['user' => $_SESSION['userid']]))
			{
				header('Location: ' . $MCONF['web_uri'] . 'admin/?msg=4');
			} else
			{
				echo msg('fail', $lang->get('user_settings_error_logout_all_devices'));
			}
		} else
		{
			echo '<div class="main"><h2>' . $lang->get('user_settings_current_sessions') . '</h2>';
			$db->data['user'] = $_SESSION['userid'];
			$db->get();
			echo '<table><tr><th>' . $lang->get('date') . '</th><th>' . $lang->get('ip') . '</th><th>' . $lang->get('user_agent') . '</th></tr>';
			foreach ($db->data as $data)
			{
				echo '<tr><td>' . date('d.m.Y H:i:s', $data['time']) . '</td><td>' . $data['ip'] . '</td><td>' . $data['user_agent'];
				if ($data['token'] == $_SESSION['token']) echo '  <span style="color: #4CAF50;">' . $lang->get('user_settings_current_session') . '</span>';
				echo '</td></tr>';
			}
			echo '</table>';

			?>
            <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post"><input type="submit" name="smbt"
                                                                                      value="<?php echo $lang->get('user_settings_current_sessions_logout_all'); ?>"/>
            </form></div>
			<?php
		}
	}//2-Faktor-Atentifizierung
    elseif (isset($_GET['2fa']))
	{
		echo '<div class="main">';

		$auth = new \PragmaRX\Google2FA\Google2FA();

		$db->clear();
		$db->setCol('system_admins');
		$db->data['id'] = $_SESSION['userid'];
		$db->get();
		//Wenn aktiviert, mgl zum Deaktivieren anzeigen
		if (isset($db->data[0]['secret']) && $db->data[0]['secret'] != '')
		{
			echo '<h2>' . $lang->get('user_settings_2fa_deactivate') . '</h2>';
			if (isset($_POST['confirm']))
			{
				$db->clear();
				$db->setCol('system_admins');
				$db->data['secret'] = '';
				if ($db->update(['id' => $_SESSION['userid']]))
				{
					echo msg('success', $lang->get('user_settings_2fa_deactivate_success') . ' <a href="user_settings.php">' . $lang->get('back') . '</a>');
				} else
				{
					echo msg('fail', $lang->get('user_settings_2fa_deactivate_fail') . ' {back}');
				}
			} else
			{
				?>
                <p><?php echo $lang->get('user_settings_2fa_deactivate_confirm'); ?></p>
                <p>
                <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" style="text-align: center;">
                    <input type="submit" name="confirm" value="<?php echo $lang->get('general_yes'); ?>"/>
                    <a onclick="history.back();" class="button btn_del"><?php echo $lang->get('general_no'); ?></a>
                </form>
                </p>
				<?php
			}
		}//Ansonsten mgl zum aktivieren/Einrichten anzeigen
		else
		{
			echo '<h2>' . $lang->get('user_settings_2fa_activate') . '</h2>';
			if (isset($_POST['smbt']))
			{
				if ($auth->verify($_POST['2fatest'], $_POST['secret']))
				{
					$db->clear();
					$db->setCol('system_admins');
					$db->data['secret'] = $_POST['secret'];
					if ($db->update(['id' => $_SESSION['userid']]))
					{
						echo msg('success', $lang->get('user_settings_2fa_activate_success') . ' <a href="user_settings.php">' . $lang->get('back') . '</a>');
					} else
					{
						echo msg('fail', $lang->get('user_settings_2fa_activate_fail') . ' {back}');
					}
				} else
			{
				echo msg('fail', $lang->get('user_settings_2fa_activate_wrong_code') . ' {back}');
			}
		}
	else
		{
			echo '<p>' . $lang->get('user_settings_2fa_activate_import_code') . '</p>';
			$secret = $auth->generateSecretKey();
			echo '<p><b>' . $lang->get('user_settings_2fa_key') . ':</b> ' . $secret . '<br/><br/>';

			$qrcode = $auth->getQRCodeInline(
				$MCONF['title'] . ' - Admin',
				$_SESSION['user'],
				$secret,
				250
			);
			echo '<img src="' . $qrcode . '" alt=""/></p>';
			?>
            <p><?php echo $lang->get('user_settings_2fa_confirm_code'); ?>:</p>
            <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
                <p><input type="text" placeholder="<?php echo $lang->get('user_settings_2fa_enter_code'); ?>"
                          name="2fatest" autocomplete="off"/>
                    <input type="hidden" name="secret" value="<?php echo $secret; ?>"/>
                    <input type="submit" name="smbt" value="<?php echo $lang->get('user_settings_2fa_test'); ?>"/>
                </p>
            </form>
			<?php
		}
	}
	echo '</div>';
} else
{
	$db->setCol('system_admins');
	if (isset($_POST['smbt']))
	{
		$db->data['username'] = $_POST['username'];
		$db->data['mail'] = $_POST['mail'];
		if ($db->update(['id' => $uid]))
		{
			echo msg('success', $lang->get('user_settings_settings_success') . ' {back}');
			stream_message('{user} changed it\'s username and/or email-adress.', 4);
		} else
		{
			echo msg('fail', $lang->get('user_settings_settings_fail') . ' {back}');
		}

		//Log-Level
		$loglevel = '';
		$loglevelA = [];
		if (isset($_POST['level_1']) && $_POST['level_1'] == 'true') $loglevelA[] = 1;
		if (isset($_POST['level_2']) && $_POST['level_2'] == 'true') $loglevelA[] = 2;
		if (isset($_POST['level_3']) && $_POST['level_3'] == 'true') $loglevelA[] = 3;
		if (isset($_POST['level_4']) && $_POST['level_4'] == 'true') $loglevelA[] = 4;
		$loglevel = json_encode($loglevelA);

		//Get the current status
		$db->setCol('system_show_stream');
		$db->data['user'] = $_SESSION['userid'];
		$db->get();
		if (isset($db->data[0]))//If we already have stream settings saved, update them
		{
			$db->setCol('system_show_stream');
			$db->data['level'] = $loglevel;
			if ($db->update(['user' => $_SESSION['userid']]))
			{
				echo msg('success', $lang->get('user_settings_log_level_success') . ' {back}');
			} else
			{
				echo msg('fail', $lang->get('user_settings_log_level_fail') . ' {back}');
			}
		} else //Otherwise insert them
		{
			$db->setCol('system_show_stream');
			$db->data['user'] = $_SESSION['userid'];
			$db->data['level'] = $loglevel;
			if ($db->insert())
			{
				echo msg('success', $lang->get('user_settings_log_level_success'));
			} else
			{
				echo msg('fail', $lang->get('user_settings_log_level_fail'));
			}
		}
	} else
	{
		$db->data['id'] = $uid;
		$db->get();
		//print_r($db->data);
		if ($db->data[0]['username'] !== '')
		{
			?>
            <div class="main">
                <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" class="form" method="post">
                    <input type="hidden" name="askPW" value="askPW">
                    <p><span><?php echo $lang->get('username'); ?>:</span><input name="username"
                                                                                 value="<?php echo $db->data[0]['username'] ?>"/>
                    </p>
                    <p><span><?php echo $lang->get('admins_mail'); ?>:</span><input name="mail"
                                                                                    value="<?php echo $db->data[0]['mail'] ?>"/>
                    </p>
                    <p><span><?php echo $lang->get('password'); ?>:</span><a
                                href="user_settings.php?uid=<?php echo $db->data[0]['id'];
								if ($db->data[0]['id'] == $_SESSION['userid'])
								{
									echo '&pw_u';
								} else
								{
									echo '&pw_new';
								}
								?>"><?php echo $lang->get('user_settings_settings_pass'); ?></a></p>
                    <p><span><?php echo $lang->get('admins_roles_group'); ?>:</span><?php
						$lvl = $db->data[0]['lvl'];
						$db->setCol('system_roles');
						$db->data['id'] = $lvl;
						$db->get();
						if (isset($db->data[0]['name']))
						{
							echo $db->data[0]['name'];
						} else
						{
							echo '<i>' . sprintf($lang->get('user_settings_none'), $MCONF['web_uri'] . 'admin/roles.php') . '</i>';
						} ?></p>
                    <p><span><?php echo $lang->get('user_settings_last_login'); ?>:</span><?php
						$db->setCol('system_loggedin');
						$db->data['user'] = $uid;
						$db->get();
						if (isset($db->data[0]['time']))
						{
							echo date('d.m.Y H:i:s', $db->data[0]['time']);
						} else
						{
							$last_login = '<i>' . $lang->get('never') . '</i>';
							foreach ($db->data as $data)
							{
								$last_login = date('d.m.Y H:i:s', $data['time']);
							}
							echo $last_login;
						}

						if ($uid == $_SESSION['userid'])
						{
						echo '  <a href="user_settings.php?sessions">' . $lang->get('user_settings_show_current_sessions') . '</a>';
						?></p>
                    <p><span><?php echo $lang->get('user_settings_2fa'); ?>:</span><?php
						$db->clear();
						$db->setCol('system_admins');
						$db->data['id'] = $_SESSION['userid'];
						$db->get();
						if (isset($db->data[0]['secret']) && $db->data[0]['secret'] != '')
						{
							echo $lang->get('general_active') . '. <a href="?2fa">' . $lang->get('general_deactivate') . '</a>';
						} else
						{
							echo $lang->get('general_inactive') . '. <a href="?2fa">' . $lang->get('general_activate') . '</a>';
						}
						}
						?><br/></p>
                    <p><span><?php echo $lang->get('user_settings_log_level'); ?>:</span>
						<?php
						$db->setCol('system_show_stream');
						$db->data['user'] = $_SESSION['userid'];
						$db->get();
						$loglevel = [];
						if (isset($db->data[0]))
						{
							$loglevel = json_decode($db->data[0]['level']);
						}
						?>
                        <input type="checkbox" name="level_1"
                               id="level_1"<?php if (in_array(1, $loglevel)) echo ' checked="checked"'; ?>/>
                        <label for="level_1"><i></i> <?php echo $lang->get('user_settings_log_level_1'); ?></label>
                    <div style="clear: both;"></div>
                    <span>&nbsp;</span>
                    <input type="checkbox" name="level_2"
                           id="level_2"<?php if (in_array(2, $loglevel)) echo ' checked="checked"'; ?>/>
                    <label for="level_2"><i></i> <?php echo $lang->get('user_settings_log_level_2'); ?></label>
                    <div style="clear: both;"></div>
                    <span>&nbsp;</span>
                    <input type="checkbox" name="level_3"
                           id="level_3"<?php if (in_array(3, $loglevel)) echo ' checked="checked"'; ?>/>
                    <label for="level_3"><i></i> <?php echo $lang->get('user_settings_log_level_3'); ?></label>
                    <div style="clear: both;"></div>
                    <span>&nbsp;</span>
                    <input type="checkbox" name="level_4"
                           id="level_4"<?php if (in_array(4, $loglevel)) echo ' checked="checked"'; ?>/>
                    <label for="level_4"><i></i> <?php echo $lang->get('user_settings_log_level_4'); ?></label>
                    </p>
                    <p><input type="submit" name="smbt" value="<?php echo $lang->get('general_save_changes'); ?>"/>
                    </p>
                </form>
            </div>
			<?php
		}
	}
}
} else
{
	echo msg('info', $lang->get('missing_permission'));
}
require_once '../inc/footer.php';
?>