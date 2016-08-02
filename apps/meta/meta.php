<?php
$meta = '';
$db->setCol('meta_meta');
$db->get();

foreach($db->data as $data)
{
	$meta .= '    <meta name="' . $data['name'] . '" content="' . $data['content'] . '">' . "\n";
}

$page->assign('meta', $meta, true);