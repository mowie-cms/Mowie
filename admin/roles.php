<?php
require_once '../inc/autoload_adm.php';
printHeader($lang->get('admins_groups'));

if (hasPerm('manage_groups'))
{
	//Mitglieder einer Gruppe anzeigen
	if (isset($_GET['members']))
	{
		$db->setCol('system_admins');
		if (isset($_POST['submit']))
		{
			$db->data['lvl'] = $_GET['members'];
			if ($db->update(['id' => $_POST['user']]))
			{
				echo msg('success', $lang->get('admins_roles_added_success').' <a href="roles.php?members=' . $_GET['members'] . '">'.$lang->get('back').'</a>');
				stream_message('{user} added a group.', 2);
			} else
			{
				echo msg('fail', $lang->get('admins_roles_added_fail').' <a href="roles.php?members=' . $_GET['members'] . '">'.$lang->get('back').'</a>');
			}
		} elseif (isset($_GET['del']))
		{
			if ($_GET['members'] == 1)
			{
				echo msg(null, $lang->get('admins_roles_delete_error').' <a href="roles.php?members=' . $_GET['members'] . '">'.$lang->get('back').'</a>');
			} else
			{
				if (isset($_POST['del']))
				{
					$db->setCol('system_roles');
					if ($db->delete(['id' => $_GET['members']]))
					{
						echo msg('success', $lang->get('admins_roles_delete_success').' <a href="roles.php">'.$lang->get('back').'</a>');
						stream_message('{user} deleted a group.', 2);
					} else
					{
						echo msg('fail', $lang->get('admins_roles_delete_fail').' <a href="roles.php">'.$lang->get('back').'</a>');
					}
				} else
				{
					?>
					<div class="main" style="text-align: center">
						<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
							<p><?php echo $lang->get('admins_roles_delete_confirm');?></p>
							<input type="submit" name="del" value="<?php echo $lang->get('general_yes');?>"/>
							<a href="roles.php?members=<?php echo $_GET['members']; ?>" class="button btn_del"><?php echo $lang->get('general_no');?></a>
						</form>
					</div>
					<?php
				}
			}
		} elseif (isset($_GET['deluser']))
		{
			if (isset($_POST['del']))
			{
				$db->setCol('system_admins');
				$db->data['lvl'] = 0;
				if ($db->update(['id' => $_GET['deluser']]))
				{
					echo msg('success', $lang->get('admins_roles_user_delete_success').' <a href="roles.php?members=' . $_GET['members'] . '">'.$lang->get('back').'</a>');
					stream_message('{user} deleted an user.', 2);
				} else
				{
					echo msg('fail', $lang->get('admins_roles_user_delete_fail').' <a href="roles.php?members=' . $_GET['members'] . '">'.$lang->get('back').'</a>');
				}
			} else
			{
				?>
				<div class="main" style="text-align: center">
					<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
						<p><?php echo $lang->get('admins_roles_user_delete_confirm');?></p>
						<input type="submit" name="del" value="<?php echo $lang->get('general_yes');?>"/>
						<a href="roles.php?members=<?php echo $_GET['members']; ?>" class="button btn_del"><?php echo $lang->get('general_no');?></a>
					</form>
				</div>
				<?php
			}
		} else
		{
			?>
			<div class="main">
				<h2><?php echo $lang->get('admins_roles_members');?></h2>
				<table>
					<tr>
						<th><?php echo $lang->get('admins_id');?></th>
						<th><?php echo $lang->get('admins_username');?></th>
						<th><?php echo $lang->get('admins_mail');?></th>
						<th>&nbsp;</th>
					</tr>
					<?php
					$i = 0;
					$users = [];
					$db->get();
					foreach ($db->data as $user)
					{
						if ($user['lvl'] == $_GET['members'])
						{
							?>
							<tr>
								<td><?php echo $user['id']; ?></td>
								<td><?php echo $user['username']; ?></td>
								<td><?php if ($user['mail'] === '')
									{
										echo '<i>'.$lang->get('admins_not_set').'</i>';
									} else
									{
										?>
										<a href="mail.php?an=<?php echo $user['mail']; ?>"
										   title="<?php printf($lang->get('admins_write_mail'), $user['username']);?>"><?php echo $user['mail']; ?></a>
										<?php
									}
									?></td>
								<td><a href="user_settings.php?uid=<?php echo $user['id']; ?>"><?php echo $lang->get('settings');?></a> |
									<a href="roles.php?members=<?php echo $_GET['members'] . '&deluser=' . $user['id']; ?>"><?php echo $lang->get('admins_roles_member_remove');?></a></td>
							</tr>
							<?php
							$i++;
						} else
						{
							$users[$user['id']] = $user['username'];
						}
					}

					if ($i == 0) echo '<p>'.$lang->get('admins_roles_no_members_yet').'</p>';
					?>
				</table>
				<?php
				if (count($users) == 0)
				{
					echo '<p>'.$lang->get('admins_roles_already_all_members').'</p>';
				} else
				{
					?>
					<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
						<?php echo $lang->get('admins_roles_add_user');?>:
						<select name="user">
							<?php
							foreach ($users as $userid => $username)
							{
								echo '<option value="' . $userid . '">' . $username . '</option>';
							}
							?>
						</select>
						<input type="submit" name="submit" value="<?php echo $lang->get('admins_roles_add_user');?>"/>
					</form>
					<?php
				}

				if ($_GET['members'] != 1) echo '<a href="roles.php?members=' . $_GET['members'] . '&del" class="button btn_del"><i class="fa fa-bin"></i> '.$lang->get('admins_roles_delete_group').'</a>';
				?>
			</div>
			<?php
		}
	}//Neue Gruppe erstellen
	elseif (isset($_GET['new']))
	{
		if (isset($_POST['submit']))
		{
			$db->setCol('system_roles');
			$db->data['name'] = $_POST['group_name'];
			if ($db->insert())
			{
				echo msg('success', $lang->get('admins_roles_create_group_success').' <a href="roles.php">'.$lang->get('back').'</a>');
				stream_message('{user} created a new group.', 2);
			} else
			{
				echo msg('fail', $lang->get('admins_roles_create_group_fail').' <a href="roles.php">'.$lang->get('back').'</a>');
			}
		} else
		{
			?>
			<div class="main">
				<h2><?php echo $lang->get('admins_roles_create_group');?></h2>
				<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
					<input type="text" name="group_name" placeholder="<?php echo $lang->get('admins_roles_group_name');?>"/><br/>
					<input type="submit" name="submit" value="<?php echo $lang->get('admins_roles_create_group');?>"/>
				</form>
			</div>
			<?php
		}
	}//Ansonsten Übersicht
	else
	{
		?>
		<div class="main">
			<table>
				<tr>
					<th><?php echo $lang->get('admins_roles_level');?></th>
					<th><?php echo $lang->get('admins_roles_name');?></th>
					<th>&nbsp;</th>
				</tr>
				<?php
				$db->setCol('system_roles');
				$db->get();
				foreach ($db->data as $role)
				{
					?>
					<tr>
						<td><?php echo $role['id']; ?></td>
						<td><?php echo $role['name']; ?></td>
						<td><a href="roles.php?members=<?php echo $role['id']; ?>"><?php echo $lang->get('admins_roles_members');?></a></td>
					</tr>
					<?php
				}
				?>
			</table>
			<p><a class="button" href="roles.php?new"><i class="fa fa-plus"></i> <?php echo $lang->get('admins_roles_create_group');?></a></p>
		</div>
		<?php
	}
} else
{
	echo msg('info', $lang->get('missing_permission'));
}
require_once '../inc/footer.php';
