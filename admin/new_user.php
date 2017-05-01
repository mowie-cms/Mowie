<?php
require_once '../inc/autoload_adm.php';
printHeader($lang->get('admins_create_new'));
if (hasPerm('manage_admins'))
{
	if (isset($_POST['userN']))
	{
		if ($_POST['userN'] != '' && $_POST['pw1'] != '' && $_POST['pw2'] != '' && $_POST['mail'] != '')
		{
			require_once '../inc/libs/password.php';
			$pw1 = $_POST['pw1'];
			$pw2 = $_POST['pw2'];
			if ($pw1 === $pw2)
			{
				$pw = password_hash($pw1, PASSWORD_DEFAULT);
				$db->setCol('system_admins');
				$db->data['username'] = $_POST['userN'];
				$db->get();
				//var_dump($db->data[0]['id']);

				if (!isset($db->data[0]['id']))
				{
					$db->clear();
					$db->setCol('system_admins');
					$db->data = ['username' => $_POST['userN'], 'pass' => $pw, 'mail' => $_POST['mail']];

					if ($db->insert())
					{
						echo msg('success', sprintf($lang->get('admins_cn_success'), $_POST['userN']) . ' <a href="users.php">' . $lang->get('back') . '</a>');
						stream_message('{user} created the new user "{extra}".', 2, $_POST['userN']);
					} else
					{
						echo msg('fail', $lang->get('admins_cn_fail') . ' {back}');
					}
				} else
				{
					echo msg('fail', $lang->get('admins_cn_name_already_in_use') . ' {back}');
				}
			} else
			{
				echo msg('fail', $lang->get('admins_cn_pw_not_match') . ' {back}');
			}
		} else
		{
			echo msg('fail', $lang->get('admins_cn_missing_inputs') . ' {back}');
		}
	} else
	{
		?>
		<div class="main">
			<div class="form">
				<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST">
                    <input type="hidden" name="askPW" value="askPW">
					<p><span><?php echo $lang->get('admins_cn_username'); ?>:</span><input type="text" name="userN"/>
					</p>
					<p><span><?php echo $lang->get('admins_cn_password'); ?>:</span><input type="password" name="pw1"/>
					</p>
					<p><span><?php echo $lang->get('admins_cn_password_again'); ?>:</span><input type="password"
																								 name="pw2"/></p>
					<p><span><?php echo $lang->get('admins_mail'); ?>:</span><input type="email" name="mail"/></p>
					<p><input type="submit" value="<?php echo $lang->get('admins_cn_create'); ?>"/></p>
				</form>
			</div>
		</div>
		<?php
	}
} else
{
	echo msg('info', $lang->get('missing_permission'));
}
require_once '../inc/footer.php';
?>
