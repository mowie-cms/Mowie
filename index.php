<?php
//Check if installed
if (file_exists('inc/config.yml'))
{
    //Require Libs
    require_once 'inc/libs/Smarty/Smarty.class.php';
	require_once 'inc/config.php';
	require_once 'inc/libs/functions.php';
    require_once 'inc/page.php';

    //Under Construction?
	if (file_exists('inc/System/construction.txt'))
	{
		echo file_get_contents('inc/System/construction.txt');
	}
	else
	{
		//Page
		$page = new page();
        $page->caching = false;
        $page->error_reporting = E_ALL & ~E_NOTICE;

        //Set Url
		$page->setUrl(str_replace($MCONF['home_uri'], '', $_SERVER['REQUEST_URI']));
		$base = explode('/', str_replace($MCONF['home_uri'], '', $_SERVER['REQUEST_URI']));
		$page->setBaseUrl('/');
		if(count($base)>1)
		{
			$page->setBaseUrl($base[0].'/');
		}
		$page->setResponseCode(404);

		//Search apps and execute them if necessary
		$appdir = 'apps/';
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
						if(isset($_CONF['type']))//typ vorhanden?
						{
							if($_CONF['type'] == 'page')//Seite, die inhalte ausgibt
							{
								if(isset($_CONF['base_url_file']))
								{
									if($_CONF['base_url'] == $page->getUrl())
									{
										require $appUri.'/' . $_CONF['base_url_file'];
									}
								}
								if(isset($_CONF['base_url']) && file_exists($appUri.'/' . $_CONF['base_file']))
								{
									if($_CONF['base_url'] == $page->getBaseUrl())
									{
										require $appUri.'/' . $_CONF['base_file'];
									}
								}
							}
							if($_CONF['type'] == 'static' && isset($_CONF['base_file']) && file_exists($appUri.'/'.$_CONF['base_file']))
							{
								require $appUri.'/'.$_CONF['base_file'];
							}
						}
						$_CONF = [];
					}
				}
			}
			closedir($handle);
		}

		if($page->getResponseCode() == 404)
		{
			$page->setTitle('404');
			$page->setContent(file_get_contents('inc/System/404.txt'));
		}

		//Copyright bauen
		$founded = date('Y', filemtime('inc/config.php'));
		$copy = $founded;
		if($founded != date('Y'))
		{
			$copy = $founded.' - '.date('Y');
		}
		$page->assign('copyright', $copy);

		// dat ganze ausgeben
		http_response_code($page->getresponseCode());
		$page->assign($MCONF['tpl_title'], $page->getTitle(). ' | ' . $MCONF['title']);
		$page->assign($MCONF['tpl_content'], $page->getContent());
		$page->assign($MCONF['tpl_webUri'], $MCONF['web_uri']);
		$page->display($MCONF['template']);
	}
}
else
{
	header('Location: admin/install.php');
}