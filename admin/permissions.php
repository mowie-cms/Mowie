<?php
require_once '../inc/autoload_adm.php';
printHeader($lang->get('admins_permissions'));

if (hasPerm('edit_permissions'))
{
	$db->setCol('system_roles');
	if (isset($_POST['smbt']))
	{
		//Perms finden und in en Array tun
		$permArr = [];
		foreach ($_POST as $perm => $val)
		{
			if ($perm != 'smbt' && $val == 'true')
			{
				$perm_full = $perm;
				$perm = explode('_', $perm);
				$permArr[$perm[0]][$perm[1]][] = str_replace($perm[0].'_'.$perm[1].'_', '', $perm_full);
			}
		}

		//print_r($permArr);
		foreach ($permArr as $lvl => $perms)
		{
			$db->data['permissions'] = json_encode($perms);
			if ($db->update(['id' => $lvl]))
			{
				echo msg('success', $lang->get('admins_perms_set_success').' {back}');
				stream_message('{user} edited permissions.', 3);
			}
			else
			{
				echo msg('fail', $lang->get('admins_perms_set_fail').' {back}');
			}
		}
	} else
	{
		echo '<div class="main"><form action="'.$_SERVER['REQUEST_URI'].'" method="post"><input type="hidden" name="askPW" value="askPW">';
		//Admin Groups
		$db->get();
		$role_names = [];
		$role_perms = [];
		$roles_descr = '';
		foreach ($db->data as $role_name)
		{
			$role_names[$role_name['id']] = $role_name['name'];
			$roles_descr .= '<th class="permdesc">' . $role_name['name'] . '</th>';

			//Already set permissions
			$role_perms[$role_name['id']] = [];
			$perms = [];
			if ($role_name['permissions'] != '') $perms = json_decode($role_name['permissions'], true);
			//print_r($perms);

			foreach ($perms as $apps => $perm)
			{
				foreach ($perm as $item)
				{
					$role_perms[$role_name['id']][] = $apps . '.' . $item;
				}
			}
		}

		//print_r($role_perms);

		//Get All
		$permsTotal = [];
		$permsTotal['System'] = json_decode(file_get_contents('permissions.json'), true);
		$permsTotal['System'] = $permsTotal['System']['permissions'];

		$apps = new apps();
		$appUri = '../apps/';
		foreach ($apps->getApps() as $app => $appconf)
		{
			if (file_exists($appUri . '/' . $app . '/permissions.json'))
			{
				require $appUri . '/' . $app . '/config.php';

				$lang->setLangFolder( $appUri  . $app .'/lang/');

				$permsTotal[$_CONF['app_name']] = json_decode(file_get_contents($appUri . '/' . $app . '/permissions.json'), true);
				$permsTotal[$_CONF['app_name']] = $permsTotal[$_CONF['app_name']]['permissions'];
			}
		}

		//print_r($permsTotal);


		foreach ($permsTotal as $title => $perms)
		{
			echo '<table class="permissions"><tr><th>' . $title . '</th>' . $roles_descr . '</tr>';

			foreach ($perms as $perm)
			{
				$critical = '';
				if ($perm['critical'] == true) $critical = '<span data-toggle="tooltip" class="critical fa fa-warning" title="'.$lang->get('admins_perms_critical').'"></span>';

				echo '<tr data-toggle="tooltip" title="' . $lang->get($perm['description']) . '"><td>' . $critical . $lang->get($perm['name']) . '</td>';
				foreach ($role_names as $lvl => $name)
				{
					$disable = '';
					if ($lvl == 1) $disable = ' disabled="disabled"';

					$checked = '';
					if ($lvl == 1) $checked = ' checked="checked"';
					if (in_array($title . '.' . $perm['key'], $role_perms[$lvl])) $checked = ' checked="checked"';

					$id = $lvl . '.' . $title . '.' . $perm['key'];
					echo '<td class="permdesc"><input type="checkbox" name="' . $id . '" id="' . $id . '" ' . $disable . $checked . ' value="true"/><label for="' . $id . '"><i></i></label></td>';
					//title="'.print_r($role_perms[$lvl], true).$title . '.' . $perm['key'].'"
				}

				echo '</tr>';
			}

			echo '</table>';

		}

		echo '<input type="submit" name="smbt" value="'.$lang->get('admins_perms_save').'"/> </form></div>';
	}
} else
{
	echo msg('info', $lang->get('missing_permission'));
}
require_once '../inc/footer.php';
