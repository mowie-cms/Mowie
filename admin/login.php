<?php
session_name('adminsession');
session_start();

require_once '../vendor/autoload.php';
require_once '../inc/config.php';
require_once '../inc/libs/password.php';
require_once '../inc/libs/functions.php';

if(isset($_POST['username']))
{
	$db->setCol('system_admins');
	$db->data['username'] = $_POST['username'];
	$db->get();
	if (isset($db->data[0]) && $db->data[0]['id'] != '')
	{
		$userData = $db->data[0];
		if (password_verify($_POST['pw'], $userData['pass']))
		{
			session_regenerate_id();

			//Token
			$token = random(64);
			$_SESSION['token'] = $token;
			$db->clear();
			$db->setCol('system_loggedin');
			$db->data['user'] = $userData['id'];
			$db->data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$db->data['ip'] = $_SERVER['REMOTE_ADDR'];
			$db->data['time'] = time();
			$db->data['token'] = $token;
			$db->insert();

			$db->clear();
			$db->setCol('system_admins');
			$db->data['id'] = $userData['id'];
			$db->get();
			if ($db->data[0]['secret'] != '')
			{
				if (isset($_POST['2fa']) && $_POST['2fa'] == '')
				{
					echo '2fa';
					exit;
				} else
				{
					require_once '../inc/libs/2fa.php';
					$authenticator = new php2FA();

					if ($authenticator->verifyCode($db->data[0]['secret'], $_POST['2fa'], 3))
					{
						echo 'success';
						stream_message('{user} has logged in.', 4);
					} else
					{
						echo '2fafail';
						exit;
					}
				}
			} else
			{
				echo 'success';
			}

			//Session
			$_SESSION['user'] = $userData['username'];
			$_SESSION['userid'] = $userData['id'];
			$_SESSION['lvl'] = $userData['lvl'];
			$_SESSION['mail'] = $userData['mail'];
			$_SESSION['guestview'] = 'true';

			stream_message('{user} has logged in.', 4);
		} else
		{
			echo 'fail';
		}
	} else
	{
		echo 'fail';
	}
}

//Check for password - needed for user's confirmation
if(isset($_GET['checkPassword']))
{
	if(is_loggedin())
	{
		if(isset($_POST['pw']))
		{
			$db->setCol('system_admins');
			$db->data['id'] = $_SESSION['userid'];
			$db->get();
			if (isset($db->data[0]) && $db->data[0]['id'] != '')
			{
				if (password_verify($_POST['pw'], $db->data[0]['pass']))
				{
					echo 'success';
					$_SESSION['sudomode'] = time();
				}
				else
				{
					echo 'fail';
				}
			}
			else
			{
				echo 'fail';
			}
		}
	}
	else
	{
		echo 'login first.';
	}
}

// Check if the user already entered his password
if (isset($_GET['checkSudo']))
{
	if(is_loggedin())
	{
		// Check if the user entered his password less then 10 minutes ago
		if (isset($_SESSION['sudomode']) && $_SESSION['sudomode'] >= (time() - 600))
		{
			echo 'true';
		} else
		{
			echo 'false';
		}
	}
	else
	{
		echo 'login first.';
	}
}