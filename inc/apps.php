<?php

/*
 * Find all apps and put them in an array.
 * Has it's own class because of performance reasons - you would need to include Smarty every time you want the apps
 */

class apps
{
	private $apps;

	public function __construct()
	{
		//Find the app directory
		$i = 1;
		$appdir = 'apps/';
		if($GLOBALS['MCONF']['home_uri'] == '/')
		{
			$rel = explode('/', $_SERVER['SCRIPT_NAME']);
			$i++;
		}
		else
		{
			$rel = explode('/', str_replace($GLOBALS['MCONF']['home_uri'], '', $_SERVER['SCRIPT_NAME']));
		}
		$count = count($rel);
		//print_r($rel);exit;
		//if (strpos($_SERVER['REQUEST_URI'], '/apps/') !== false && $count !== 1) $count = $count - 1; $appdir = '';

		$i = 1;
		while ($i < $count)
		{
			$appdir = '../'.$appdir;
			$i++;
		}

		//Loop through the apps
		if ($handle = opendir($appdir))
		{
			while (false !== ($app = readdir($handle)))
			{
				if ($app != "." && $app != ".." && !is_file($appdir . $app))
				{
					$appUri = $appdir.$app;
					if (file_exists($appUri.'/config.php'))
					{
						require $appUri.'/config.php';
						$this->apps[$app] = $_CONF;
						//print_r($_CONF);
						$_CONF = [];
					}
				}
			}
			closedir($handle);
		}
	}

	public function getApps()
	{
		return $this->apps;
	}
}