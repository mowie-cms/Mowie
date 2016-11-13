<?php
session_name('adminsession');
session_start();
require_once '../inc/config.php';
require_once '../inc/libs/password.php';
require_once '../inc/libs/functions.php';

$db->setCol('system_admins');
$db->data['username'] = $_POST['username'];
$db->get();
if($db->data[0]['id'] != '')
{
	if(password_verify($_POST['pw'], $db->data[0]['pass']))
	{
        session_regenerate_id();
		$uid = $db->data[0]['id'];

		//Token
		$token = random(64);
		$_SESSION['token'] = $token;
		$db->clear();
		$db->setCol('system_loggedin');
		$db->data['user'] = $uid;
		$db->data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$db->data['ip'] = $_SERVER['REMOTE_ADDR'];
		$db->data['time'] = time();
		$db->data['token'] = $token;
		//if($db->insert()) echo 'token'; else echo 'hm';
		$db->insert();
		//echo $token;

		$db->clear();
		$db->setCol('system_admins');
		$db->data['id'] = $uid;
		$db->get();
		if($db->data[0]['secret'] != '')
		{
			if(isset($_POST['2fa']) && $_POST['2fa'] == '')
			{
				echo '2fa';
				exit;
			}
			else
			{
				require_once '../inc/libs/2fa.php';
				$authenticator = new php2FA();

				if($authenticator->verifyCode($db->data[0]['secret'], $_POST['2fa'], 3))
				{
					echo 'success';
					stream_message('{user} has logged in.', 4);
				}
				else
				{
					echo '2fafail';
					exit;
				}
			}
		}
		else
		{
			echo 'success';
		}

		//Session
		$_SESSION['user'] = $db->data[0]['username'];
		$_SESSION['userid'] = $db->data[0]['id'];
		$_SESSION['lvl'] = $db->data[0]['lvl'];
		$_SESSION['mail'] = $db->data[0]['mail'];
		$_SESSION['guestview'] = 'true';
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