<?php
require_once '../../../inc/autoload.php';
printHeader($lang->get('sp_manage_permissions'));

if (hasPerm('grant_permissions'))
{
	//Users
	$db->setCol('system_admins');
	$db->get();
	$users_2 = $db->data;
	$user = [];
	foreach ($users_2 as $user_2)
	{
		$user[$user_2['id']] = ['username' => $user_2['username'], 'lvl' => $user_2['lvl']];
	}

	if (isset($_POST['submit']))
	{
		if (isset($user[$_POST['user']]))
		{
			/*if ($user[$_POST['user']]['lvl'] === 'super')
			{
				echo msg(null, $user[$_POST['user']]['username'] . $lang->get('sp_').' ist ein Superuser, daher hat er Zugriff auf alle Seiten.');
			}
			else
			{*/
				$db->setCol('simplePages_permissions');
				$db->data['page'] = $_POST['page'];
				$db->data['user'] = $_POST['user'];
				$db->get();

				if (isset($db->data[0]['id']))
				{
					echo msg(null, sprintf($lang->get('sp_user_already_access'), $user[$_POST['user']]['username']));
				}
				else
				{
					$db->data['page'] = $_POST['page'];
					$db->data['user'] = $_POST['user'];
					$db->data['lastedit'] = time();
					if ($db->insert())
					{
						echo msg('success', $lang->get('sp_grant_permissions_success'));
						stream_message('{user} granted permissions to "{extra}".', 3, getUserByID($_POST['user']). ' ('.$_POST['user'].')');
					} else
					{
						echo msg('fail', $lang->get('sp_grant_permissions_fail'));
					}
				}
			//}
		} else
		{
			echo msg(null, $lang->get('sp_user_nexist'));
		}
	} else
	{
		?>
		<div class="main">
		<table>
			<tr>
				<th><?php echo $lang->get('sp_page');?></th>
				<th><?php echo $lang->get('sp_preview');?></th>
				<th><?php echo $lang->get('sp_permissions');?></th>
				<th><?php echo $lang->get('sp_manage_permission');?></th>
			</tr>
			<?php
			//Pages
			$db->setCol('simplePages_pages');
			$db->get();
			foreach ($db->data as $data)
			{
				echo '<tr><td><a href="edit.php?id=' . $data['id'] . '">' . $data['title'] . '</a></td><td><a href="' . $MCONF['web_uri'] . $data['alias'] . '" target="_blank">' . $data['title'] . ' <i class="fa fa-external-link"></i></a></td><td>';

				$user_count = 1;
				$db->setCol('simplePages_permissions');
				$db->data['page'] = $data['id'];
				$db->get();
				$perms = $db->data;
				foreach ($perms as $perm)
				{
					if ($user_count > 1)
					{
						echo ', ' . $user[$perm['user']]['username'];
					} else
					{
						echo $user[$perm['user']]['username'];
					}
					$user_count++;
				}
				echo '</td>';
				?>
				<td>
					<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post" style="padding:0;margin:0;">
						<input type="hidden" name="page" value="<?php echo $data['id']; ?>"/>
						<select name="user" style="margin:0;">
							<?php
							foreach ($user as $id => $name)
							{
								echo '<option value="' . $id . '">' . $name['username'] . '</option>';
							}
							?>
						</select>
						<input type="submit" value="<?php echo $lang->get('sp_grant_permissions');?>" name="submit"
							   style="margin:0;"/>
					</form>
				</td>
				<?php
				echo '</tr>';
			}
			?>
		</table>
		<?php
	}
} else
{
	echo msg('info', $lang->get('missing_permission').' {back}');
}
?>
	</div>
<?php
require_once '../../../inc/footer.php';
?>