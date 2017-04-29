<?php
$confirmationRequierd = false;
$iniFile = 'confirm.ini';
if(strpos($_SERVER['SCRIPT_FILENAME'], '/apps/') === false)
{
	$iniFile = '../SimplePages/backend/confirm.ini';
}

if(file_exists($iniFile))
{
	$config = parse_ini_file($iniFile);
	//print_r($config);exit;
	$confirmationRequierd = $config['confirmationRequierd'];
	$confirmationUser = $config['confirmationUser'];

	$db->setCol('system_admins');
	$db->data['id'] = $confirmationUser;
	$db->get();
	$confirmationUserMail = $db->data[0]['mail'];
}