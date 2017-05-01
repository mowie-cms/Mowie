<?php
require_once '../../inc/autoload.php';
printHeader($lang->get('nav_title'));

//Get all pages and build a dropdown list
$pages = '';
$db->setCol('simplePages_pages');
$db->get();
foreach ($db->data as $page)
{
	$pages .= '<option value="' . $page['id'] . '">' . $page['title'] . '</option>';
}

//Get all parents and build a dropdown list
$parents = '<option value="0">--</option>';
$db->setCol('nav_nav');
$db->get();
$parentdata = $db->data;
foreach ($parentdata as $parent)
{
	//If we don't have a parent from the Nav itself, take the page's title
	if ($parent['title'] === '')
	{
		$db->setCol('simplePages_pages');
		$db->data['id'] = $parent['page'];
		$db->get();
		if (isset($db->data[0]))
		{
			$parents .= '<option value="' . $parent['id'] . '">' . $db->data[0]['title'] . '</option>';
		}
	} else
	{
		$parents .= '<option value="' . $parent['id'] . '">' . $parent['title'] . '</option>';
	}
}

//Show
function buildNav($nav, $lvl = 0)
{
	global $db;
	foreach ($nav as $site)
	{
		//Reset level
		if ($site['parent'] == 0)
		{
			$lvl = 0;
		}

		echo '<div class="row" id="navID_' . $site['id'] . '">';
		$pageUrl = '#';
		$db->setCol('simplePages_pages');
		$db->data['id'] = $site['page'];
		$db->get();

		$title = $site['title'];
		if ($title == '') $title = $db->data[0]['title'];

		echo '<div class="col"><i class="fa fa-bars" aria-hidden="true" style="color: #ccc;"></i>&nbsp;&nbsp;&nbsp;&nbsp;';
		for ($i = 1; $i <= $lvl; $i++)
		{
			echo '&nbsp;&nbsp;&nbsp;';
		}

		$title = $site['title'];
		if ($title == '') $title = $db->data[0]['title'];

		echo $title . '</div><div class="col"><a href="../SimplePages/backend/edit.php?id=' . $site['page'] . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . $db->data[0]['title'] . '</a></div>
		<div class="col"><select id="parentChange_' . $site['id'] . '" data-nav-id="' . $site['id'] . '" onchange="update(\'' . $site['id'] . '\')">' . str_replace('value="' . $site['parent'] . '"', 'value="' . $site['parent'] . '" selected', $GLOBALS['parents']) . '</select></div>
		<div class="col"><a onclick="del(' . $site['id'] . ');" class="del" title="' . $GLOBALS['lang']->get('nav_delete') . '"><i class="fa fa-trash-o" aria-hidden="true"></i></a></div>';

		echo "</div>\n";

		//Look for childs
		$db->setCol('nav_nav');
		$db->data['parent'] = $site['id'];
		$db->get(null, null, 'nav_order');
		$navd = $db->data;
		if (!empty($navd))
		{
			$lvl++;
			buildNav($navd, $lvl);
		}
	}
}

/*
 * TODO:
 *  * mglkt zum "neuzuweisen" der Parents -> Dropdown
 */

if (hasPerm('edit_nav'))
{
	?>
    <div class="main">
        <p><i class="fa fa-info-circle"></i> <?php echo $lang->get('nav_drag') ?></p>
        <p><a onclick="createItem();" class="button"><i class="fa fa-plus"
                                                        aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $lang->get('nav_create'); ?>
            </a></p>
        <div id="sortable" class="pseudo-table">
            <div class="row top" id="top">
                <div class="col"><?php echo $lang->get('nav_pageTitle') ?></div>
                <div class="col"><?php echo $lang->get('nav_page') ?></div>
                <div class="col"><?php echo $lang->get('nav_parent') ?></div>
                <div class="col"><?php echo $lang->get('nav_action') ?></div>
            </div>
			<?php
			$db->setCol('nav_nav');
			$db->data['parent'] = 0;
			$db->get(null, null, 'nav_order');
			buildNav($db->data);
			?>
        </div>
        <div id="extra"></div>
    </div>

    <script src="js/jquery-ui.js"></script>
    <script src="js/nav.js"></script>
    <script>
        //Create Dropdowns
        var parents = '<?php echo $parents;?>';
        var pages = '<?php echo $pages;?>';
        //Make Language Strings available in JS
        var lang = {
            nav_saved_success: '<?php echo $lang->get('nav_saved_success') ?>',
            nav_saved_fail: '<?php echo $lang->get('nav_saved_fail') ?>',
            nav_delete: '<?php echo $lang->get('nav_delete') ?>',
            nav_delete_confirm: '<?php echo $lang->get('nav_delete_confirm') ?>',
            nav_delete_confirm_yes: '<?php echo $lang->get('nav_delete_confirm_yes') ?>',
            nav_delete_confirm_abort: '<?php echo $lang->get('nav_delete_confirm_abort') ?>',
            nav_deleted_success: '<?php echo $lang->get('nav_deleted_success') ?>',
            nav_deleted_fail: '<?php echo $lang->get('nav_deleted_fail') ?>',
            nav_create: '<?php echo $lang->get('nav_create') ?>',
            nav_create_title: '<?php echo $lang->get('nav_create_title') ?>',
            nav_create_page: '<?php echo $lang->get('nav_create_page') ?>',
            nav_create_parents: '<?php echo $lang->get('nav_create_parents') ?>',
            nav_create_create: '<?php echo $lang->get('nav_create_create') ?>',
            nav_create_abort: '<?php echo $lang->get('nav_create_abort') ?>',
            nav_create_success: '<?php echo $lang->get('nav_create_success') ?>',
            nav_create_fail: '<?php echo $lang->get('nav_create_fail') ?>',
            nav_update_success: '<?php echo $lang->get('nav_update_success') ?>',
            nav_update_fail: '<?php echo $lang->get('nav_update_fail') ?>',
            not_found: '<?php echo $lang->get('404_not_found') ?>'
        };
    </script>
	<?php
} else
{
	echo msg('info', 'missing_permission');
}
require_once '../../inc/footer.php';