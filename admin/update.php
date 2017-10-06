<?php
require_once '../inc/autoload_adm.php';
require_once '../inc/libs/updater.php';

$update = new Mowie\Updater\updater();
$update->setServer($MCONF['update_servers']);
$update->setCurrentVersion($MCONF['version']);
$update->setUpdateDir('../');
$update->thingsToNotUpdate = [
	'apps/',
	'config/',
	'content/',
	'vendor/',
	'templates_c'
];

//Update-Checker
if (isset($_GET['checkUpdate']))
{
	//sleep(50);
	if (hasPerm('update') && $MCONF['update_enabled'])
	{
		//Check for newer Version
		try
		{
			$new = $update->checkUpdateAvailable();
		} catch (\Exception $e)
		{
			echo 'Error. ' . $e->getMessage();
		}

		// If we have a new version, show it
		if (isset($new))
		{
			echo $lang->get('update_new_version') . ' <b>' . $new['version'] . '</b> <a href="update.php?update" class="button">' . $lang->get('update_title') . '</a>';
			if (isset($new['changelog']))
			{
				echo '<a href="update.php?showChangelog&url=' . urlencode($new['server'] . $new['changelog']) . '" class="button"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp;Changelog</a>';
			}
		} else
		{
			echo $lang->get('update_version_current_new');
		}
	}
	exit;
}

//Show Changelog
if (isset($_GET['showChangelog']))
{
	printHeader($lang->get('update_showChangelog'));
	echo '<div class="main">';
	if (hasPerm('update'))
	{
		if(isset($_GET['url']))
		{
			$parsedown = new Parsedown();
			$change = $update->getChangelog(urldecode($_GET['url']));
			echo $parsedown->parse($change);
		}
		else
		{
			echo 'Missing Url';
		}
	} else
	{
		echo msg('info', $lang->get('missing_permission'));
	}
	echo '<a href="general_config.php">'.$lang->get('back').'</a> </div>';
	exit;
}


//Update
if (isset($_GET['update']))
{
	printHeader($lang->get('update_title'));
	if (hasPerm('update') && $MCONF['update_enabled'])
	{
		//Check for newer Version
		try
		{
			$new = $update->checkUpdateAvailable();
		} catch (\Exception $e)
		{
			echo 'Error. ' . $e->getMessage();
		}

		// Update if we have one
		if(isset($new))
		{
			//Check writing permissions
			if($update->updateFolderIsWritable())
			{
				// Download the update
				if ($update->downloadUpdate($new))
				{
					// Check downloaded update file
					if($update->verifyUpdate($new))
					{
						// Put the site in "under construction mode"
						if (copy('../content/.system/construction2.txt', '../content/.system/construction.txt'))
						{
							stream_message('{user} put the site into construction mode.', 2);

							// Create a backup
							if($update->backupUpdateFolder())
							{
								// The actual update
								try
								{
									$update->rollTheUpdate();
								}
								catch (\Exception $e)
								{
									echo msg('fail', $lang->get('update_fail_unzip'). ' ('.$e->getMessage().')');
								}

								// Execute migrations
								$update->migrate();

								// Clean afterwards
								if($update->cleanup())
								{
									// Update new Version in Config file
									$conf = \Symfony\Component\Yaml\Yaml::parse(file_get_contents('../config/config.yml'));
									$conf['Versioning']['version'] = $update->getCurrentVersion();

									$configfile = \Symfony\Component\Yaml\Yaml::dump($conf);
									if (file_put_contents('../config/config.yml', $configfile))
									{
										// Disable Construction mode
										if (unlink('../content/.system/construction.txt'))
										{
											echo msg('success', $lang->get('update_succss') . ' <a href="general_config.php">' . $lang->get('back') . '</a>');
											stream_message('{user} updated the System.', 2);
											stream_message('{user} put the site into production mode.', 2);
										}
										else
										{
											echo msg('success', $lang->get('action_construction_removed_error'));
										}
									}
								}
								else
								{
									echo msg('fail', $lang->get('update_cleanup_error'));
								}
							}
							else
							{
								echo msg('fail', $lang->get('update_create_backup_error'));
							}
						} else
						{
							echo msg('fail', $lang->get('action_construction_error'));
						}
					}
					else
					{
						echo msg('fail', $lang->get('update_wrong_hash'));
					}
				}
				else
				{
					echo msg('fail', $lang->get('update_fail_copy'));
				}
			}
			else
			{
				echo msg('fail', $lang->get('update_folder_not_writeable'));
			}
		}
		else
		{
			echo msg('info', $lang->get('update_version_current_new'));
		}
	}
	require_once '../inc/footer.php';
}