<?php

//Check if installed
if (file_exists('inc/config.yml'))
{
	//Require Libs
	require_once 'inc/libs/Smarty/Smarty.class.php';
	require_once 'inc/config.php';
	require_once 'inc/libs/functions.php';
	require_once 'inc/page.php';
	require_once 'inc/apps.php';

	//Under Construction?
	if (file_exists('inc/System/construction.txt'))
	{
		echo file_get_contents('inc/System/construction.txt');
	} else
	{
		//Page
		$page = new page();
		$page->caching = false;
		$page->error_reporting = E_ALL & ~E_NOTICE;

		//Set Url
		$page->setUrl(str_replace($MCONF['home_uri'], '', $_SERVER['REQUEST_URI']));
		$base = explode('/', str_replace($MCONF['home_uri'], '', $_SERVER['REQUEST_URI']));
		$page->setBaseUrl('/');
		if (count($base) > 1)
		{
			$page->setBaseUrl($base[0] . '/');
		}
		$page->setResponseCode(404);

		$apps = new apps();
		//Search apps and execute them if necessary
		foreach ($apps->getApps() as $app => $appconf)
		{
			$appUri = 'apps/' . $app . '/';
			//If the App should run from one domain only
			if ((isset($appconf['domain']) && $page->getDomain() == $appconf['domain']) || !isset($appconf['domain']) || (isset($appconf['domain']) && $appconf['domain'] === ''))
			{
				//If we have an alias which equals the current url, execute it
				if (isset($appconf['alias']))
				{
					if (array_key_exists($page->getUrl(), $appconf['alias']))
					{
						require $appUri . $appconf['alias'][$page->getUrl()];
					}
				}

				//If we have a type
				if (isset($appconf['type']))
				{
					//Page for (more or less) dynamic content
					if ($appconf['type'] == 'page')
					{
						//If we have a base_url_file and the current url equals base_url, execute base_url_file
						if (isset($appconf['base_url_file']))
						{
							if ($appconf['base_url'] == $page->getUrl())
							{
								require $appUri . $appconf['base_url_file'];
							}
						}

						//if we have a base_url and a base_file which exists and the current baseUrl equals base_url, execute base_file
						if (isset($appconf['base_url']) && file_exists($appUri . '/' . $appconf['base_file']))
						{
							if ($appconf['base_url'] == $page->getBaseUrl())
							{
								require $appUri . $appconf['base_file'];
							}
						}
					}

					//Static
					if ($appconf['type'] == 'static' && isset($appconf['base_file']) && file_exists($appUri . '/' . $appconf['base_file']))
					{
						require $appUri . $appconf['base_file'];
					}
				}
			}

			$appconf = [];
		}

		if ($page->getResponseCode() == 404)
		{
			$page->setTitle('404');
			$page->setContent(file_get_contents('inc/System/404.txt'));
		}

		//Build Copyright
		$founded = date('Y', filemtime('inc/config.yml'));
		$copy = $founded;
		if ($founded != date('Y'))
		{
			$copy = $founded . ' - ' . date('Y');
		}
		$page->assign('copyright', $copy);

		//Finally render everything
		http_response_code($page->getResponseCode());
		$page->assign($MCONF['tpl_title'], $page->getTitle() . ' | ' . $MCONF['title']);
		$page->assign($MCONF['tpl_content'], $page->getContent());
		$page->assign($MCONF['tpl_webUri'], $MCONF['web_uri']);
		$page->assign('template', $page->getTemplate());
		$page->display($MCONF['template']);
	}
} else
{
	header('Location: admin/install.php');
}