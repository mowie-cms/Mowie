<?php
$navTree = '';
//Show
function buildNav($nav)
{
	global $navTree, $db;
	$navTree .= '<ul>';
	foreach ($nav as $site)
	{
		//Get the Page URL
		$pageUrl = '#';
		$db->setCol('simplePages_pages');
		$db->data['id'] = $site['page'];
		$db->get();
		if(!empty($db->data)) $pageUrl = $db->data[0]['alias'];

		//Get the page title
		$title = $site['title'];
		if($title == '') $title = $db->data[0]['title'];

		$navTree .= '<li><a href="'.$GLOBALS['MCONF']['web_uri'].$pageUrl.'">'.$title.'</a>';

		//Look for childs
		$db->setCol('nav_nav');
		$db->data['parent'] = $site['id'];
		$db->get(null, null, 'nav_order');
		$navd = $db->data;
		//If this site has any childs, build the navtree for them
		if (!empty($navd))
		{
			buildNav($navd);
		}
		$navTree .= '</li>';
	}
	$navTree .= '</ul>';
}

//Create the Navigation, start with all top-level pages
$db->setCol('nav_nav');
$db->data['parent'] = 0;
$db->get(null, null, 'nav_order');
buildNav($db->data);

$page->assign('navTree', $navTree);