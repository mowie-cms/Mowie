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
				echo '<a href="edit.php?new" class="button"><span class="icon">&#xe924;</span>&nbsp;  '.$lang->get('sp_create_new').'</a></p><p>';
			}
			?></p>
		<table>
			<thead>
			<tr>
				<th><?php echo $lang->get('sp_page');?></th>
				<th><?php echo $lang->get('sp_last_edit');?></th>
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
					echo '<td><a href="edit.php?id=' . $pageitem['id'] . '"><span class="icon">&#xe905;</span>  '.$lang->get('sp_edit').'</a> | <a href="edit.php?id=' . $pageitem['id'] . '&del"><span class="icon">&#xe9ac;</span>  '.$lang->get('sp_delete').'</a> | <a href="' . $MCONF['web_uri'] . $pageitem['alias'] . '" target="_blank"><span class="icon">&#xea7d;</span>  '.$lang->get('sp_preview').'</a></td>';
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
						echo '<td><a href="edit.php?id=' . $pageitem['id'] . '"><span class="icon">&#xe905;</span>  '.$lang->get('sp_edit').'</a> | <a href="edit.php?id=' . $pageitem['id'] . '&del"><span class="icon">&#xe9ac;</span>  '.$lang->get('sp_delete').'</a> | <a href="' . $MCONF['web_uri'] . $pageitem['alias'] . '" target="_blank"><span class="icon">&#xea7d;</span>  '.$lang->get('sp_preview').'</a></td>';
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