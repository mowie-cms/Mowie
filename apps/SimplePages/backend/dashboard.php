<?php
if (hasPerm('view_dashboard'))
{
	$lang->set('Seiten zum Freischalten', 'sp_pages_confirm', 'de');
	$lang->set('Seiten, die Sie editieren d&uuml;rfen', 'sp_edit_pages_to_edit', 'de');

	$lang->set('Seiten zum Freischalten', 'sp_pages_confirm', 'en');
	$lang->set('Seiten, die Sie editieren d&uuml;rfen', 'sp_edit_pages_to_edit', 'en');

	//If pages are available for confirmation, show them
	$config = parse_ini_file('../apps/SimplePages/backend/confirm.ini');
	if ($_SESSION['userid'] == $config['confirmationUser'])
	{
		$db->setCol('simplePages_pages_confirm');
		$db->get();
		if(isset($db->data[0]))
		{
			echo '<h2>'.$lang->get('sp_edit_pages_to_confirm').':</h2>';

			foreach($db->data as $pages)
			{
				echo '<a href="../apps/SimplePages/backend/confirm.php?page='.$pages['page_id'].'">'.$pages['title'].'</a><br/>';
			}
		}
	}

	//Show all pages the user can edit
	echo '<h2>'.$lang->get('sp_edit_pages_to_edit'). ':</h2>';
	$pages = [];
	$db->setCol('simplePages_pages');
	$db->get();
	foreach($db->data as $page)
	{
		$pages[$page['id']] = $page['title'];
	}

	if (hasPerm('admin_manage'))
	{
		foreach ($pages as $id => $title)
		{
			echo '<a href="' . $MCONF['web_uri'] . 'apps/SimplePages/backend/edit.php?id=' . $id . '"><i class="fa fa-pencil"></i>  ' . $title . '</a><br/>';
		}
	}
	else
	{
		$hasPerms = [];
		$db->setCol('simplePages_permissions');
		$db->data['user'] = $_SESSION['userid'];
		$db->get();
		foreach ($db->data as $item)
		{
			$hasPerms[] = $item['page'];
		}

		foreach ($pages as $id => $title)
		{
			if(in_array($id, $hasPerms)) echo '<a href="' . $MCONF['web_uri'] . 'apps/SimplePages/backend/edit.php?id=' . $id . '"><i class="fa fa-pencil"></i>  ' . $title . '</a><br/>';
		}
	}
}