<?php
require_once '../../../inc/autoload.php';
printHeader($lang->get('sp_manage_pages'));
if (hasPerm('manage_pages'))
{
	?>
	<div class="main">
		<p><?php
			if (hasPerm('create_new'))
			{
				echo '<a href="edit.php?new" class="button"><i class="fa fa-file-o"></i>&nbsp;  '.$lang->get('sp_create_new').'</a></p><p>';
			}
			?></p>
		<table>
			<thead>
			<tr>
				<th><?php echo $lang->get('sp_page');?></th>
				<th><?php echo $lang->get('sp_last_edit');?></th>
				<th><?php echo $lang->get('sp_edit_status');?></th>
				<th><?php echo $lang->get('sp_action');?></th>
			</tr>
			</thead>
			<tbody>
			<?php
			$pages = [];
			$db->setCol('simplePages_pages');
			$db->get();
			foreach($db->data as $page)
			{
				$pages[$page['id']] = $page;
			}

			if (hasPerm('admin_manage'))//If the User has the persmission to edit every page
			{
				$status = [$lang->get('sp_status_inactive'), $lang->get('sp_status_active'), $lang->get('sp_status_pending_confirmation')];
				foreach ($pages as $id => $pageitem)
				{
					echo '<tr>';
					echo '<td>' . $pageitem['title'] . '</td>';
					if ($pageitem['lastedit'] != 0)
					{
						echo '<td>'.sprintf($lang->get('sp_edited_by_date'), date('d.m.Y H:i:s', $pageitem['lastedit']), getUserByID($pageitem['user'])) . '</td>';
					}
					else
					{
						echo '<td>'.$lang->get('sp_never').'</td>';
					}
					echo '<td>'.$status[$pageitem['status']].'</td>';
					echo '<td><a href="edit.php?id=' . $pageitem['id'] . '"><i class="fa fa-pencil"></i>  '.$lang->get('sp_edit').'</a> | <a href="edit.php?id=' . $pageitem['id'] . '&del"><i class="fa fa-trash-o"></i>  '.$lang->get('sp_delete').'</a> | <a href="' . $MCONF['web_uri'] . $pageitem['alias'] . '" target="_blank"><i class="fa fa-external-link"></i>  '.$lang->get('sp_preview').'</a></td>';
					echo '</tr>';
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

				foreach ($pages as $id => $pageitem)
				{
					if(in_array($id, $hasPerms))
					{
						echo '<tr>';
						echo '<td>' . $pageitem['title'] . '</td>';
						if ($pageitem['lastedit'] != 0)
						{
							echo '<td>'.sprintf($lang->get('sp_edited_by_date'), date('d.m.Y H:i:s', $pageitem['lastedit']), getUserByID($pageitem['user'])) . '</td>';
						}
						else
						{
							echo '<td>'.$lang->get('sp_never').'</td>';
						}
						echo '<td><a href="edit.php?id=' . $pageitem['id'] . '"><i class="fa fa-pencil"></i>  '.$lang->get('sp_edit').'</a> | <a href="edit.php?id=' . $pageitem['id'] . '&del"><i class="fa fa-trash-o"></i>  '.$lang->get('sp_delete').'</a> | <a href="' . $MCONF['web_uri'] . $pageitem['alias'] . '" target="_blank"><i class="fa fa-external-link"></i>  '.$lang->get('sp_preview').'</a></td>';
						echo '</tr>';
					}
				}
			}
			?>
			</tbody>
		</table>
	</div>
	<?php
}
else
{
	echo msg('info', $lang->get('missing_permission').' {back}');
}
require_once '../../../inc/footer.php';
?>