<?php

namespace Mowie\Updater;

use Alchemy\Zippy\Zippy;

class updater
{
	private $currentVersion;
	private $oldVersion;
	private $newVersion;

	private $updateServer;
	private $updateDir;

	private $newUpdateFiles;

	/**
	 * Every file/folder, including subdirectories which should not be updated or deleted after the update.
	 * @var string
	 */
	public $thingsToNotUpdate;

	public function __construct()
	{
		// Bypass memory error
		ini_set('memory_limit', '-1');

		// Bypass Max execution time
		set_time_limit(0);
	}

	/**
	 * Sets the server to use. Can be an array, in this case it will loop through all servers and check if there is an update
	 * @param $server
	 */
	public function setServer($server)
	{
		$this->updateServer = $server;
	}

	/**
	 * Sets the update directory on which the update is applied on.
	 * @param $dir
	 */
	public function setUpdateDir($dir)
	{
		$this->updateDir = $dir;
	}

	/**
	 * Sets the current version. Checks if it is properly formatted, throws an exeption if not.
	 * @param $version
	 * @throws \Exception
	 */
	public function setCurrentVersion($version)
	{
		// Check if version string is properly formatted
		if (preg_match('/[0-9]+\.[0-9]+\.[0-9]+/', $version))
		{
			$this->currentVersion = $version;
		} else
		{
			throw new \Exception('Version string not properly formatted! Please format it following the conventions on http://semver.org');
		}
	}

	/**
	 * Returns the current version
	 * @return string
	 */
	public function getCurrentVersion()
	{
		return $this->currentVersion;
	}


	/////////////////////////////////////////
	/// Check if an Update is available.  ///
	/////////////////////////////////////////


	/**
	 * Checks if an update is available. If there is, returns an array with all informations for said update.
	 * @return array|null
	 */
	public function checkUpdateAvailable()
	{
		// Loop through all servers
		foreach ($this->updateServer as $server)
		{
			// Get update infos
			$updateInfos = $this->getNewestVersionFromServer($server);
			if (isset($updateInfos))
			{
				$this->newVersion = $updateInfos['version'];
				if (version_compare($updateInfos['version'], $this->currentVersion, '>'))
				{
					$updateInfos['server'] = $server;
					return $updateInfos;
				}
			}
		}
		return null;
	}

	/**
	 * Returns the newest version from a server.
	 * @param $server
	 * @return array|null
	 */
	public function getNewestVersionFromServer($server)
	{
		$fullUpdateList = $this->getUpdateInfoFromServer($server);
		if (isset($fullUpdateList))
		{
			return end($fullUpdateList);
		}
		return null;
	}

	/**
	 * Checks if an update exists on the remote server. If there is, returns an array with update informations.
	 * Otherwise returns an empty array.
	 * @param $server
	 * @return array|null
	 */
	public function getUpdateInfoFromServer($server)
	{
		$updateInfosUrl = $server . 'update.json';
		if ($this->remote_file_exists($updateInfosUrl))
		{
			$updateInfo = json_decode(urldecode(file_get_contents($updateInfosUrl)), true);
			return $updateInfo;
		}
		return null;
	}

	public function getChangelog($changelogUrl)
	{
		if ($this->remote_file_exists($changelogUrl))
		{
			return file_get_contents($changelogUrl);
		}
		return '';
	}


	//////////////////////////////////
	/// Check writing permissions  ///
	//////////////////////////////////


	/**
	 * Checks if the given update folder is writable.
	 * @return bool
	 * @throws \Exception
	 */
	public function updateFolderIsWritable($updateFolder = '')
	{
		if ($updateFolder == '') $updateFolder = $this->updateDir;
		if (is_dir($updateFolder))
		{
			if (is_writable($updateFolder))
			{
				$objects = scandir($updateFolder);
				foreach ($objects as $object)
				{
					if (is_dir($object) && $object != "." && $object != ".." && substr($object, 0, 1) != '.')
					{
						if (!$this->updateFolderIsWritable($updateFolder . "/" . $object))
						{
							return false;
						} else
						{
							continue;
						}
					}
				}
				return true;
			} else
			{
				return false;
			}
		}

		if ($updateFolder == $this->updateDir && !is_dir($this->updateDir))
		{
			throw new \Exception('Update folder is not a folder!');
		}
	}


	////////////////////////
	/// Download Update  ///
	////////////////////////


	/**
	 * Download the update to a temporary path
	 * @param $updateInfos
	 * @return bool
	 */
	public function downloadUpdate($updateInfos)
	{
		return copy($updateInfos['server'] . $updateInfos['download'], '.update.tmp.zip');
	}

	/**
	 * Verifies the downloaded update file
	 * @param $updateInfos
	 * @return bool
	 */
	public function verifyUpdate($updateInfos)
	{
		$hash = hash_file('sha256', '.update.tmp.zip');
		if ($hash === $updateInfos['hash'])
		{
			return true;
		}
		return false;
	}


	////////////////////////////////
	/// Backup the udate folder  ///
	////////////////////////////////


	/**
	 * Creates a backup of the current state to use later in case we screw something up later
	 * @return bool
	 */
	public function backupUpdateFolder()
	{
		$zippy = Zippy::load();
		$archive = $zippy->create('.update-backup.zip', array(
			'folder' => $this->updateDir
		), true);

		// Check if the creation was successfull
		if (file_exists('.update-backup.zip'))
		{
			return true;
		}
		return false;
	}


	//////////////////////////
	/// The actual update  ///
	//////////////////////////


	/**
	 * Extracts all new files to the update folder, cleans all files which don't exist in the update
	 * @throws \Exception
	 */
	public function rollTheUpdate()
	{
		// Update Versions
		$this->oldVersion = $this->currentVersion;
		$this->currentVersion = $this->newVersion;

		// Upzip the downloaded update
		$zippy = Zippy::load();
		$update = $zippy->open('.update.tmp.zip');

		foreach ($update as $file)
		{
			$name = $this->updateDir . '/' . $file->getLocation();
			if ($file->isDir())
			{
				$name = substr($name, 0, strlen($name) - 1);
			}

			$this->newUpdateFiles[] = $name;
		}

		if (!$update->extract($this->updateDir))
		{
			throw new \Exception('Could not extract the update!');
		}
	}

	/**
	 * Executes the migration script if found in the root update directory
	 */
	public function migrate()
	{
		if (file_exists($this->updateDir . 'migrations.php'))
		{
			require_once $this->updateDir . 'migrations.php';
			unlink($this->updateDir . 'migrations.php');
		}
	}


	////////////////
	/// Cleanup  ///
	////////////////


	/**
	 * Deletes all unessesary files.
	 * @return bool
	 */
	public function cleanup()
	{
		// Delete all Files which don't exist anymore in the update. Ommit everything in $filesToNotUpdate
		$diff = array_diff($this->getDirContents($this->updateDir), $this->newUpdateFiles);

		foreach ($diff as $file)
		{
			if (is_dir($file))
			{
				$success = $this->rrmdir($file);
			} else
			{
				// Empty files wont show up in the zip, which is why we check this here.
				if(filesize($file) != 0)
				{
					$success = unlink($file);
				}
			}
		}

		// cleanup downloaded zips
		$success = unlink('.update.tmp.zip');
		$success = unlink('.update-backup.zip');

		return $success;
	}


	/////////////////////////
	/// Helper functions  ///
	/////////////////////////


	/**
	 * Checks if a remote file exists
	 * @param $url
	 * @return bool
	 */
	private function remote_file_exists($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($retcode == 200)
		{
			return true;
		} else
		{
			return false;
		}
	}

	/**
	 * Returns a list of all files and folders inside a given directory, recursivly. Excluding $thingsNotToUpdate.
	 * @param $dir
	 * @param array $results
	 * @return array
	 */
	private function getDirContents($dir, &$results = array())
	{
		$results = $this->getFolderContentsList($dir);

		// Only include it if it should not be ignored
		foreach ($results as $in => $path)
		{
			$p = $this->str_replace_first($dir, '', $path);
			$p = $this->str_replace_first('/', '', $p);
			if ($this->stringMatchesInArray($p, $this->thingsToNotUpdate))
			{
				unset($results[$in]);
			}
		}
		return $results;
	}

	/**
	 * Gets a list with all files and folders in a given directory, including all subdirectories
	 * @param $dir
	 * @param array $results
	 * @return array
	 */
	private function getFolderContentsList($dir, &$results = array())
	{
		$files = scandir($dir);

		foreach ($files as $key => $value)
		{
			$path = $dir . DIRECTORY_SEPARATOR . $value;

			// Don't show the directory if it's hidden
			if (substr($value, 0, 1) !== ".")
			{
				if (!is_dir($path))
				{
					$results[] = $path;
				} else if ($value != "." && $value != "..")
				{
					$this->getFolderContentsList($path, $results);
					$results[] = $path;
				}
			}
		}

		return $results;
	}

	/**
	 * @param $string
	 * @param $array
	 * @return bool
	 */
	private function stringMatchesInArray($string, $array)
	{
		foreach ($array as $item)
		{
			$itm = '/' . preg_quote($item, '/') . '/';
			if (preg_match($itm, $string))
			{
				return true;
			}

			//if it matches without a slash at the end
			if (substr($item, 0, (strlen($item) - 1)) === $string)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Replaces only the first occurence of a string
	 * @param $search
	 * @param $replace
	 * @param $subject
	 * @return mixed
	 */
	private function str_replace_first($search, $replace, $subject)
	{
		$search = '/' . preg_quote($search, '/') . '/';

		return preg_replace($search, $replace, $subject, 1);
	}

	/**
	 * Deletes a non-empty folder
	 * @param $dir
	 * @return bool
	 */
	private function rrmdir($dir)
	{
		if (is_dir($dir))
		{
			$objects = scandir($dir);
			foreach ($objects as $object)
			{
				if ($object != "." && $object != "..")
				{
					if (filetype($dir . "/" . $object) == "dir") $this->rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
				}
			}
			reset($objects);
			return rmdir($dir);
		} else
		{
			return false;
		}
	}
}