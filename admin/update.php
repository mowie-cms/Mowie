<?php
require_once '../inc/autoload_adm.php';

//Update-Checker
if (isset($_GET['checkUpdate']))
{
	//sleep(50);
	if (hasPerm('update'))
	{
		//Check for newer Version
		$nextVersion = $MCONF['version_num'] + 1;
		$foundNewVersion = false;
		$hasChangelog = false;

		foreach ($MCONF['update_uri'] as $update_server)
		{
			$updateUrl = $update_server . 'System/v' . $nextVersion;
			if (remote_file_exists($updateUrl . '/version.json'))
			{
				$version_remote = json_decode(file_get_contents($updateUrl . '/version.json'));
				if ($version_remote->versionNum > $MCONF['version_num'])
				{
					$foundNewVersion = true;

					//Check for Changelog
					if (remote_file_exists($updateUrl . '/changelog.md'))
					{
						$hasChangelog = $update_server;
					}
				}
			}
		}

		if ($foundNewVersion)
		{
			echo $lang->get('update_new_version') . ' <b>' . $version_remote->version . '</b> <a href="update.php?update" class="button">' . $lang->get('update_title') . '</a>';
			if ($hasChangelog !== false)
			{
				echo '<a href="update.php?showChangelog&server=' . urlencode($hasChangelog) . '&v=' . $nextVersion . '" class="button"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp;Changelog</a>';
			}
		} else
		{
			echo $lang->get('update_version_current_new');
		}

		//Check for App-Updates
		foreach ($apps->getApps() as $appdir => $app)
		{
			if(isset($app['app_build']))
			{
				$nextVersion = $app['app_build'] + 1;
				$foundNewVersion = false;
				$hasChangelog = false;

				foreach ($MCONF['update_uri'] as $update_server)
				{
					$updateUrl = $update_server . 'apps/' . str_replace(' ', '-', $app['app_name']) . '/v' . $nextVersion;
					if (remote_file_exists($updateUrl . '/version.json'))
					{
						$version_remote = json_decode(file_get_contents($updateUrl . '/version.json'));
						if ($version_remote->versionNum > $app['app_build'])
						{
							$foundNewVersion = true;

							//Check for Changelog
							if (remote_file_exists($updateUrl . '/changelog.md'))
							{
								$hasChangelog = $update_server;
							}
						}
					}
				}

				if ($foundNewVersion)
				{
					echo '<br/>'.sprintf($lang->get('update_app_update_available'), $app['app_name'], $version_remote->version). ' <a href="update.php?update&appUpdate='.urlencode($appdir).'" class="button">' . $lang->get('update_title') . '</a>';
					if ($hasChangelog !== false)
					{
						echo '<a href="update.php?showChangelog&app='.str_replace(' ', '-', $app['app_name']).'&server=' . urlencode($hasChangelog) . '&v=' . $nextVersion . '" class="button"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp;Changelog</a>';
					}
				}
			}
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
		if(isset($_GET['server']))
		{
			//If we want to see the changelog for an app, we need to look in a different directory
			$remoteSubDir = 'System';
			if(isset($_GET['app']))
			{
				$remoteSubDir = 'apps/'.$_GET['app'];
			}
			if (isset($_GET['v']))
			{
				if (remote_file_exists(urldecode($_GET['server']) . $remoteSubDir . '/v' . $_GET['v'] . '/changelog.md'))
				{
					require_once '../inc/libs/Parsedown.php';
					$Parsedown = new Parsedown();
					echo $Parsedown->text(file_get_contents(urldecode($_GET['server']) . $remoteSubDir . '/v' . $_GET['v'] . '/changelog.md'));
				}
			} else
			{
				echo 'Missing Version.';
			}
		}
		else
		{
			echo 'Missing Server';
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
	if (hasPerm('update'))
	{
		$updated = false;
		foreach ($MCONF['update_uri'] as $update_server)
		{
				$nextVersion = $MCONF['version_num'] + 1;
				$installedVersion = $MCONF['version_num'];

				//If we want to see the changelog for an app, we need to look in a different directory
				$remoteSubDir = 'System';
				$systemSubDir = '../';
				if (isset($_GET['appUpdate']))
				{
					require '../apps/' . urldecode($_GET['appUpdate']) . '/config.php';
					$remoteSubDir = 'apps/' . str_replace(' ', '-', $_CONF['app_name']);
					$nextVersion = $_CONF['app_build'] + 1;
					$systemSubDir = '../apps/' . urldecode($_GET['appUpdate']) . '/';
					$installedVersion = $_CONF['app_build'];
				}

				//Check for version.json on the remote server
				$dUri = $update_server . $remoteSubDir . '/v' . $nextVersion . '/';
				if (remote_file_exists($dUri . 'version.json'))
				{
					$version_remote = json_decode(file_get_contents($dUri . 'version.json'));
					//Check if the remote version is newer
					if ($version_remote->versionNum > $installedVersion)
					{
						//Download the update
						if (copy($dUri . 'update.v' . $version_remote->versionNum . '.incremental.zip', 'update.zip'))
						{
							$updated = true;
							//Check for md5 hash
							if (md5_file('update.zip') == $version_remote->md5)
							{
								//unzip to temporary folder
								$updateTmpDir = 'updateTmp/';
								if (!file_exists($updateTmpDir))
								{
									if (mkdir($updateTmpDir, 0777) === false)
									{
										echo msg('fail', 'Error creating temporary folder.');
									}
								}

								$zip = new ZipArchive;
								$res = $zip->open('update.zip');
								if ($res === true)
								{
									$zip->extractTo($updateTmpDir);
									$zip->close();
									$updateInfos = json_decode(file_get_contents($updateTmpDir . 'filesToUpdate.json'));

									$isUp = false;
									$fTU = [];
									foreach ($updateInfos->files as $num => $file)
									{
										$fTU[] = $file;
										$upNeu = $updateTmpDir . $file;
										$upRem = $systemSubDir . $file;
										if (copy($upNeu, $upRem))
										{
											echo msg('succes', sprintf($lang->get('update_item_succss'), $file));
											$isUp = true;
										} else
										{
											echo msg('fail', sprintf($lang->get('update_item_fail'), $file));
										}
									}

									//Update Version in Config File - only if we don't update an app
									if (!isset($_GET['appUpdate']))
									{
										$config = Yaml::parse(file_get_contents('../inc/config.yml', FILE_USE_INCLUDE_PATH));
										$config['Versioning']['version'] = $version_remote->version;
										$config['Versioning']['version_num'] = $version_remote->versionNum;
										$configfile = Yaml::dump($config);
										if (!file_put_contents('../inc/config.yml', $configfile))
										{
											echo msg('fail', $lang->get('general_config_fail'));
										}
									}

									//Remove "old" update
									if (rrmdir($updateTmpDir) && $isUp && unlink('update.zip'))
									{
										if(isset($_GET['appUpdate']))
										{
											echo msg('succes', sprintf($lang->get('update_app_succss'), $_CONF['app_name']) . ' <a href="general_config.php">' . $lang->get('back') . '</a>');
											stream_message('{user} updated an app.', 2);
										}
										else
										{
											echo msg('succes', $lang->get('update_succss') . ' <a href="general_config.php">' . $lang->get('back') . '</a>');
											stream_message('{user} updated the system.', 2);
										}
									} else
									{
										echo msg('fail', $lang->get('update_fail') . ' <a href="general_config.php">' . $lang->get('back') . '</a>');
									}
								} else
								{
									echo msg('fail', $lang->get('update_fail_unzip'));
								}
							} else
							{
								echo msg('fail', $lang->get('update_md5_fake'));
							}
						} else
						{
							echo msg('fail', $lang->get('update_fail_copy'));
						}
					} else
					{
						echo msg('info', $lang->get('update_version_current_new'));
					}
				}
		}

		if(!$updated)
		{
			echo msg('info', $lang->get('update_version_current_new'));
		}
	}
	require_once '../inc/footer.php';
}