<?php
//langstrings
$GLOBALS['lang']->set('Seiten', 'sp_pages', 'de');
$GLOBALS['lang']->set('Seitenverwaltung', 'sp_manage_pages', 'de');
$GLOBALS['lang']->set('Berechtigungen verwalten', 'sp_manage_permissions', 'de');
$GLOBALS['lang']->set('Neue Seite erstellen', 'sp_create_new', 'de');

$GLOBALS['lang']->set('Pages', 'sp_pages', 'en');
$GLOBALS['lang']->set('Manage Pages', 'sp_manage_pages', 'en');
$GLOBALS['lang']->set('Manage Permissions', 'sp_manage_permissions', 'en');
$GLOBALS['lang']->set('Create New Page', 'sp_create_new', 'en');

$_CONF['app_name'] = 'SimplePages';
$_CONF['app_desc'] = 'Wird benutzt, um Einfache statische Seitn zu erzeugen und zu verwalten.';
$_CONF['base_url'] = '/'; //Basisurl des moduls, wenn es über das Frontend aufgerufen wird
$_CONF['base_file'] = 'front/page.php'; //Datei, die angezeigt wird, wenn die basisurl aufgerufen wird
$_CONF['menu_top'] = '<i class="fa fa-file-text"></i>  '.$GLOBALS['lang']->get('sp_pages'); //Name des Moduls, wie es im Adminbereich im Hauptmenü auftaucht
$_CONF['menu'] = ['<i class="fa fa-th-list"></i>  '.$GLOBALS['lang']->get('sp_manage_pages') => 'backend/management.php',
	'<i class="fa fa-lock"></i>  '.$GLOBALS['lang']->get('sp_manage_permissions') => 'backend/permissions.php',
	'<i class="fa fa-file"></i>  '.$GLOBALS['lang']->get('sp_create_new') => 'backend/edit.php?new']; //Ein Array mit menüpunkten im adminbereich
$_CONF['dashboard'] = 'backend/dashboard.php';
$_CONF['type'] = 'page';
$_CONF['install'] = 'install.php';

$confirmationRequierd = false;
$iniFile = 'confirm.ini';
if(strpos($_SERVER['SCRIPT_FILENAME'], '/apps/') === false)
{
	$iniFile = '../SimplePages/backend/confirm.ini';
}

if(file_exists($iniFile))
{
	$config = parse_ini_file($iniFile);
	$confirmationRequierd = $config['confirmationRequierd'];
	//$confirmationUserMail = $config['confirmationUserMail'];
	$confirmationUser = $config['confirmationUser'];

	//Get the user's email
	/*$db->setCol('system_admins');
	$db->data['id'] = $confirmationUser;
	$db->get();
	if(isset($db->data[0]))	$confirmationUserMail = $db->data[0]['mail'];*/
}

//print_r($config);