<?php
$sidebar = '';
$db->setCol('sidebar_sidebar');
$db->get();

if(isset($db->data[0]))
{
	if(isset($db->data[0]['active']) && $db->data[0]['active'] == 1)
	{
		$sidebar = '<div class="sidebar">' . $db->data[0]['content'] . '</div>';
	}
}

$page->assign('sidebar', $sidebar);