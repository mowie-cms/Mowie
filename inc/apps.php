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

		while(!file_exists($appdir) && $i<21)
		{
			$appdir = '../' . $appdir;
			$i++;
		}

		//When the appdir wasn't found after 20 iterations, throw an error to prevent endless searching
		if(!file_exists($appdir)) echo 'Could not find App dir.';

		//Loop through the apps
		if ($handle = opendir($appdir))
		{
			while (false !== ($app = readdir($handle)))
			{
				if ($app != "." && $app != ".." && !is_file($appdir . $app))
				{
					$appUri = $appdir . $app;
					if (file_exists($appUri . '/config.php'))
					{
						require $appUri . '/config.php';
						$this->apps[$app] = $_CONF;
						//print_r($_CONF);
						$_CONF = [];
					}
				}
			}
			closedir($handle);
		}
	}

	//Returns an Array with all the apps
	public function getApps()
	{
		return $this->apps;
	}

	//has app?
	public function appExists($name)
	{
		foreach ($this->apps as $appDir => $app)
		{
			if($app['app_name'] == $name)
			{
				return true;
			}
		}
		return false;
	}
}