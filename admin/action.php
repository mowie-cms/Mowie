<?php
require_once '../inc/autoload_adm.php';
require_once '../inc/libs/YAML/autoload.php';
use Symfony\Component\Yaml\Yaml;
//Datenbank Backup
if (isset($_GET['dbbackup']) && is_loggedin() && hasPerm('db_dump'))
{
	include '../inc/libs/dbbackup.php';
	$db = new DBBackup(array(
		'driver' => 'mysql',
		'host' => $MCONF['db_host'],
		'user' => $MCONF['db_usr'],
		'password' => $MCONF['db_pw'],
		'database' => $MCONF['db_name'],
		'db_prefix' => $MCONF['db_prefix']
	));
	$backup = $db->backup();
	if ($backup['error'])
	{
		echo msg('fail', $lang->get('action_backup_fail'));
	} else
	{
		stream_message('{user} made a database-backup.', 4);
		header("Cache-Control: public");
		header("content-Description: File Transfer");
		header('Content-Disposition: attachment; filename=Backup_' . str_replace(' ', '_', $MCONF['title']) . '_' . date('Y-m-d_h-d') . '.sql');
		header("Content-Type: application/octet-stream; ");
		header("Content-Transfer-Encoding: binary");
		echo $backup['msg'];
		exit;
	}
}
if (hasPerm('manage_system'))
{
	//construction
	if (isset($_GET['construction']))
	{
		printHeader($lang->get('action_construction_message_edit'));
		if (isset($_GET['constr_message']))
		{
			if (isset($_POST['constr_message']))
			{
				if (file_put_contents('../inc/System/construction2.txt', $_POST['constr_message']))
				{
					copy('../inc/System/construction2.txt', '../inc/System/construction.txt');
					echo msg('success', $lang->get('action_construction_message_success') . ' <a href="general_config.php">' . $lang->get('back') . '</a>');
					stream_message('{user} edited the construction-mode message.', 2);
				} else
				{
					echo msg('fail', $lang->get('action_try_again_later') . ' <a href="general_config.php">' . $lang->get('back') . '</a>');
				}
			} else
			{
				tinymce();
				?>
				<div class="main">
					<h1><?php echo $lang->get('action_construction_message_edit'); ?></h1>
					<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
						<textarea id="editor"
								  name="constr_message"><?php require('../inc/System/construction2.txt'); ?></textarea>
						<input type="submit" value="<?php echo $lang->get('general_save_changes'); ?>"/>
					</form>
				</div>
				<?php
			}
		} else
		{
			if (hasPerm('construction'))
			{
				if (!file_exists('../inc/System/construction.txt'))
				{
					if (isset($_GET['confirm']))
					{
						if (copy('../inc/System/construction2.txt', '../inc/System/construction.txt'))
						{
							echo msg('success', $lang->get('action_construction_success') . ' <a href="general_config.php">' . $lang->get('back') . '</a>');
							stream_message('{user} put the site into construction mode.', 2);
						} else
						{
							echo msg('fail', $lang->get('action_try_again_later') . ' <a href="general_config.php">' . $lang->get('back') . '</a>');
						}
					} else
					{
						?>
						<div class="main">
							<p style="text-align: center;">
								<?php echo $lang->get('action_construction_confirm'); ?><br/>
								<a href="action.php?construction&confirm"
								   class="button"><?php echo $lang->get('general_yes'); ?></a>
								<a href="general_config.php"
								   class="button btn_del"><?php echo $lang->get('general_no'); ?></a>
							</p>
						</div>
						<?php
					}
				} else
				{
					if (isset($_GET['confirm']))
					{
						if (unlink('../inc/System/construction.txt'))
						{
							echo msg('success', $lang->get('action_construction_removed_success') . ' <a href="general_config.php">' . $lang->get('back') . '</a>');
							stream_message('{user} put the site into production mode.', 2);
						} else
						{
							echo msg('fail', $lang->get('action_try_again_later') . ' <a href="general_config.php">' . $lang->get('back') . '</a>');
						}
					} else
					{
						?>
						<div class="main">
							<p style="text-align: center;">
								<?php echo $lang->get('action_construction_remove'); ?><br/>
								<a href="action.php?construction&confirm"
								   class="button"><?php echo $lang->get('general_yes'); ?></a>
								<a href="general_config.php"
								   class="button btn_del"><?php echo $lang->get('general_no'); ?></a>
							</p>
						</div>
						<?php
					}
				}
			}
		}
	}

	//General Changes
	if (isset($_GET['general']))
	{
		printHeader($lang->get('general_config'));
		//Header
		if (hasPerm('edit_title'))
		{
			$titel = $_POST['titel'];
			if (file_put_contents('../inc/System/page_title.txt', $titel))
			{
				echo msg('success', $lang->get('action_change_page_title_success'));
				stream_message('{user} edited the page title.', 2);
			} else
			{
				echo msg('fail', $lang->get('action_try_again_later'));
			}
		}

		$apps = new apps();
		$appUri = '../apps/';
		foreach ($apps->getApps() as $app => $appconf)
		{
			require $appUri . $app . '/config.php';
			if (isset($_CONF['general_conf']) && $_CONF['general_conf'] != '' && file_exists($appUri . $app . '/' . $_CONF['general_conf']))
			{
				require $appUri . $app . '/' . $_CONF['general_conf'];
			}
		}
	}
} else
{
	printHeader($lang->get('action_edit_content'));
	echo msg('info', $lang->get('missing_permission'));
}
require_once '../inc/footer.php';
?>