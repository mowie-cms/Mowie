<?php
require_once '../inc/autoload_adm.php';
printHeader($lang->get('general_config'));
tinymce();
?>
	<div class="main">
	<form method="POST" action="action.php?general">
		<div class="form">
			<?php
			if (hasPerm('edit_title'))
			{
				?>
				<p><span><?php echo $lang->get('general_website_title'); ?></span>
					<input type="text" name="titel" value="<?php
					echo $MCONF['title'];
					?>"/>
				</p>
				<?php
			}

			if (hasPerm('construction'))
			{
				?>
				<p><span><?php echo $lang->get('general_construction_mode'); ?></span>
					<?php
					if (file_exists('../inc/System/construction.txt'))
					{
						echo '<a href="action.php?construction" class="button">' . $lang->get('general_end_construction_mode') . '</a>';
					} else
					{
						echo '<a href="action.php?construction" class="button">' . $lang->get('general_start_construction_mode') . '</a>';
					}
					?> <a href="action.php?construction&constr_message"
						  class="button"><?php echo $lang->get('general_edit_message'); ?></a>
				</p>
				<?php
			}

			if (hasPerm('update'))
			{
				?>
				<i class="divider"></i>
				<h1><?php echo $lang->get('general_version'); ?></h1>
				<p><?php echo $lang->get('general_version_current'); ?>:
					<?php
					echo $MCONF['version'];
					?>
				</p>
				<p>
					<?php
					$version_remote = json_decode(file_get_contents($MCONF['update_uri'] . 'version.json'));
					if ($version_remote->versionNum > $MCONF['version_num'])
					{
						echo $lang->get('general_new_version') . ' <b>' . $version_remote->version . '</b> <a href="action.php?update" class="button">' . $lang->get('general_update') . '</a>';
					} else
					{
						echo $lang->get('general_version_current_new');
					}
					?>
				</p>
				<?php
			}

			if (hasPerm('db_dump'))
			{
				?>
				<i class="divider"></i>
				<h1><?php echo $lang->get('general_database'); ?></h1>
				<p>
					<a href="action.php?dbbackup" class="button" download="download"><i
							class="fa fa-database"></i> <?php echo $lang->get('general_create_backup'); ?>
					</a>
					<a href="<?php
					echo $MCONF['phpmyadmin'];
					?>" class="button" target="_blank"><?php echo $lang->get('general_go_phpmyadmin'); ?> <i
							class="fa fa-external-link"></i></a>
				</p>
				<?php
			}

			$apps = new apps();
			$appUri = '../apps/';
			foreach ($apps->getApps() as $app => $appconf)
			{

				require $appUri . $app . '/config.php';
				if (isset($_CONF['general_conf']) && $_CONF['general_conf'] != '' && file_exists($appUri . $app . '/' . $_CONF['general_conf']))
				{
					echo '<i class="divider"></i>';
					require $appUri . $app . '/' . $_CONF['general_conf'];
				}

			}
			?>
			<i class="divider"></i>
			<input type="submit" class="speichern" value="<?php echo $lang->get('general_save_changes'); ?>"
				   style="width: auto;"/>
	</form>
<?php
echo '</div>';
require_once '../inc/footer.php';
?>