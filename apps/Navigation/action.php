<?php
require_once '../../inc/autoload.php';

$success = false;

if(is_loggedin())
{
	//Save items
	if(isset($_GET['save']))
	{
		if(hasPerm('edit_nav'))
		{
			$db->setCol('nav_nav');
			foreach ($_POST['navID'] as $order => $id)
			{
				$db->data['nav_order'] = $order;
				$success = $db->update(['id' => $id]);
				//echo 'order: '.$order.' | ID: '.$id."\n";
			}
		}
	}

	//Delete Items
	if(isset($_GET['del']))
	{
		if(hasPerm('edit_nav'))
		{
			if (isset($_POST['id']) && is_numeric(intval($_POST['id'])))
			{
				$db->setCol('nav_nav');
				$db->data['id'] = $_POST['id'];
				$success = $db->delete();

				//Find all childs
				$childs = [];
				function findChilds($parent)
				{
					global $db, $childs;
					foreach ($parent as $site)
					{
						$childs[] = $site['id'];
						$db->setCol('nav_nav');
						$db->data['parent'] = $site['id'];
						$db->get();
						$navd = $db->data;
						if (!empty($navd))
						{
							findChilds($navd);
						}
					}
				}

				$db->setCol('nav_nav');
				$db->data['parent'] = $_POST['id'];
				$db->get();
				findChilds($db->data);

				//print_r($childs);
				$db->setCol('nav_nav');
				foreach ($childs as $child)
				{
					$db->data['id'] = $child;
					$success = $db->delete();
				}
			}
		}
	}

	//Create Items
	if(isset($_GET['create']))
	{
		if(hasPerm('edit_nav'))
		{
			if (isset($_POST['title'], $_POST['page'], $_POST['parent']))
			{
				if (is_numeric(intval($_POST['page'])) && is_numeric(intval($_POST['parent'])) && is_string(strval($_POST['title'])))
				{
					$db->setCol('nav_nav');
					$db->data['title'] = $_POST['title'];
					$db->data['page'] = $_POST['page'];
					$db->data['parent'] = $_POST['parent'];
					$success = $db->insert();
				}
			}
		}
	}

	//Update Parents
	if(isset($_GET['update']))
	{
		if(isset($_POST['id'], $_POST['parent']))
		{
			if (is_numeric(intval($_POST['id'])) && is_numeric(intval($_POST['parent'])))
			{
				$db->setCol('nav_nav');
				$db->data['parent'] = $_POST['parent'];
				$success = $db->update(['id' => $_POST['id']]);
			}
		}
	}
}

if($success)
{
	echo 'success';
}