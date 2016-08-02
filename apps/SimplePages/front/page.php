<?php
$db->setCol('simplePages_pages');
$db->data['alias'] = urldecode($page->getUrl());
$db->get();

if(isset($db->data[0]['title']))
{
	if($db->data[0]['status'] == 1)
	{
		$page->setResponseCode(200);
		$page->setTitle($db->data[0]['title']);
		$meta = '';

		//gucken ob das was auszuführren ist
		$pos = strpos($db->data[0]['content'], 'EXEC ');
		if ($pos !== false)
		{
			$file = str_replace('EXEC ', '', $db->data[0]['content']);
			if (file_exists($file))
			{
				require_once $file;
			} else
			{
				$page->setContent($db->data[0]['content']);

				//Meta
				if(isset($db->data[0]['meta_description']) && $db->data[0]['meta_description'] != '') $meta .= '    <meta name="description" content="' . $db->data[0]['meta_description'] . '">' . "\n";
				if(isset($db->data[0]['meta_keywords']) && $db->data[0]['meta_keywords'] != '') $meta .= '    <meta name="keywords" content="' . $db->data[0]['meta_keywords'] . '">' . "\n";
			}
		}
		else
		{
			$page->setContent($db->data[0]['content']);

			//Meta
			if(isset($db->data[0]['meta_description']) && $db->data[0]['meta_description'] != '') $meta .= '    <meta name="description" content="' . $db->data[0]['meta_description'] . '">' . "\n";
			if(isset($db->data[0]['meta_keywords']) && $db->data[0]['meta_keywords'] != '') $meta .= '    <meta name="keywords" content="' . $db->data[0]['meta_keywords'] . '">' . "\n";
		}

		$page->assign('meta', $meta, true);
	}
}