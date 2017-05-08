<?php

/*
 * Find all apps and put them in an array.
 * Has it's own class because of performance reasons - you would need to include Smarty every time you want the apps
 */

class apps
{
	private $apps;
	public $unresolvedDependencies;

	public function __construct()
	{
		//Find the app directory
		$i = 1;
		$appdir = 'apps/';

		//When the appdir wasn't found after 30 iterations, throw an error to prevent endless searching
		while(!file_exists($appdir) && $i<31)
		{
			$appdir = '../' . $appdir;
			$i++;
		}

		if(!file_exists($appdir))
		{
			echo 'Could not find app dir. (Too many iterations)';
			exit;
		}

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
						$this->apps[$_CONF['app_name']] = $_CONF;
						$this->apps[$_CONF['app_name']]['app_path'] = $app;
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
		foreach ($this->apps as $app_name => $app)
		{
			if($app_name == $name)
			{
				return true;
			}
		}
		return false;
	}

	//Returns informations about an app
	public function getApp($app)
	{
		if($this->appExists($app))
		{
			return $this->apps[$app];
		}
		return false;
	}

	//Get app by path
	public function getAppByPath($path)
	{
		foreach ($this->apps as $app_name => $app)
		{
			if($app['app_path'] == $path)
			{
				return $this->getApp($app_name);
			}
		}
		return false;
	}

	//Check for app dependencies
	public function checkDependencies($app)
	{
		$appconf = $this->getApp($app);
		$dep = true;
		if(isset($appconf['dependencies']))
		{
			//Min System Build
			if(isset($appconf['dependencies']['mowie-version']))
			{
				if(!version_compare($GLOBALS['MCONF']['version'], $appconf['dependencies']['mowie-version'], '>='))
				{
					$this->unresolvedDependencies['mowie-version'] = $appconf['dependencies']['mowie-version'];
					$dep = false;
				}
			}

			//Required Apps
			if(isset($appconf['dependencies']['apps']))
			{
				foreach ($appconf['dependencies']['apps'] as $dep_app)
				{
					if (!$this->appExists($dep_app))
					{
						$this->unresolvedDependencies['apps'][] = $dep_app;
						$dep = false;
					}
				}
			}

			//Required PHP-Version
			if(isset($appconf['dependencies']['php']))
			{
				if(!version_compare(PHP_VERSION, $appconf['dependencies']['php'], '>='))
				{
					$this->unresolvedDependencies['php'] = $appconf['dependencies']['php'];
					$dep = false;
				}
			}
		}

		return $dep;
	}
}