<?php
require_once '../inc/autoload_adm.php';

printHeader($lang->get('dashboard_title'));

if (file_exists('install.php'))
{
	if (unlink('install.php'))
	{
		echo msg('info', $lang->get('delete_config_success'));
	}
}

if (hasPerm('view_dashboard'))
{
	echo '<div class="cardsContainer cardsContainer-main"><div class="card-yellow" title="' . php_uname() . '"><span>' . substr(php_uname(), 0, strpos(php_uname(), ' ')) . '</span>'.$lang->get('os').'</div>';
	echo '<div class="card-green" title="' . $_SERVER['SERVER_SOFTWARE'] . '"><span>' . $_SERVER['SERVER_SOFTWARE'] . '</span>'.$lang->get('server_software').'</div>';
	echo '<div class="card-indigo" title="' . PHP_VERSION . '"><span>' . str_replace(substr(PHP_VERSION, strpos(PHP_VERSION, '-')), '', PHP_VERSION) . '</span>'.$lang->get('php_version').'</div>';
	echo '<div class="card-orange" title="' . $db->version() . '"><span>' . str_replace(substr($db->version(), strpos($db->version(), '-')), '', $db->version()) . '</span>'.$lang->get('mysql_version').'</div></div><div class="cardsContainer cardsContainer-main">';
	echo '<div class="card-purple"><span>' . date('H:i:s') . '</span>'.$lang->get('system_time').'</div>';
	?>
	<a href="general_config.php" class="card-blue"><span class="icon">&#xe994;</span><br/>
		<?php echo $lang->get('general_config');?></a>
	<a href=".<?php echo $GLOBALS['MCONF']['home_uri']; ?>apps/logfiles/index.php" class="card-lime"><span class="icon">&#xe9bb;</span><br/><?php echo $lang->get('logfiles');?></a>
	</div>
	<div class="cardsContainer cardsContainer-main">
		<a href="<?php echo $GLOBALS['MCONF']['home_uri']; ?>apps/SimplePages/backend/management.php" class="card-red"><span class="icon">&#xe95c;</span><br/><?php echo $lang->get('manage_pages');?></a>
		<a href="<?php echo $GLOBALS['MCONF']['home_uri']; ?>apps/SimplePages/backend/permissions.php" class="card-pink"><span class="icon">&#xe905;</span><br/><?php echo $lang->get('manage_contents');?></a>
		<a href="<?php echo $GLOBALS['MCONF']['home_uri']; ?>apps/Files/index.php" class="card-amber"><span
				class="icon">&#xe90e;</span><br/><?php echo $lang->get('manage_files');?></a>
	</div>
	<?php
}
echo '<div class="main">';
//Find Dashboard files
$moduluri = '../apps/';
if ($handle = opendir($moduluri))
{
	while (false !== ($mod = readdir($handle)))
	{
		if ($mod != "." && $mod != ".." && is_dir($moduluri . $mod))
		{
			require $moduluri . $mod . '/config.php';
			if(isset($_CONF['dashboard']) && $_CONF['dashboard'] != '')
			{
				if (file_exists($moduluri . $mod . '/' . $_CONF['dashboard']))
				{
					echo '<div class="box">';
					require $moduluri . $mod . '/' . $_CONF['dashboard'];
					echo '</div>';
				}
			}
			$_CONF['dashboard'] = '';
		}
	}
	closedir($handle);
}
echo '</div>';
require_once '../inc/footer.php';
