<?php
require_once '../inc/autoload_adm.php';
printHeader($lang->get('admins_list'));
if(hasPerm('manage_admins'))
{
?>
<div class="main">
	<table>
		<tr>
			<th><?php echo $lang->get('admins_id');?></th>
			<th><?php echo $lang->get('admins_users');?></th>
			<th><?php echo $lang->get('admins_permissions');?></th>
			<th><?php echo $lang->get('admins_mail');?></th>
			<th></th>
		</tr>
		<?php
		$db->setCol('system_admins');
		$db->get();
		foreach ($db->data as $user)
		{
			?>
			<tr>
				<td><?php echo $user['id']; ?></td>
				<td><?php echo $user['username']; ?></td>
				<td><?php echo $user['lvl']; ?></td>
				<td><?php if ($user['mail'] === '')
					{
						echo '<i>'.$lang->get('admins_not_set').'</i>';
					} else
					{
						?>
						<a href="mail.php?to=<?php echo $user['mail']; ?>"
						   title="<?php printf($lang->get('admins_write_mail'), $user['username']);?>"><?php echo $user['mail']; ?></a>
						<?php
					}
					?></td>
				<td><a href="user_settings.php?uid=<?php echo $user['id']; ?>"><?php echo $lang->get('settings');?></a></td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
	}
	else
	{
		echo msg('info', $lang->get('missing_permission'));
	}
	?>
</div>
<?php
require_once '../inc/footer.php';
?>
