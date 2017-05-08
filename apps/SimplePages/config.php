<?php
//langstrings


$_CONF['app_name'] = 'SimplePages';
$_CONF['app_build'] = 2;
$_CONF['app_version'] = 'v0.9 Beta';
$_CONF['base_url'] = '/'; //Basisurl des moduls, wenn es über das Frontend aufgerufen wird
$_CONF['base_file'] = 'front/page.php'; //Datei, die angezeigt wird, wenn die basisurl aufgerufen wird
$_CONF['menu_top'] = '<i class="fa fa-file-text"></i>  '.$lang->get('sp_pages'); //Name des Moduls, wie es im Adminbereich im Hauptmenü auftaucht
$_CONF['menu'] = ['<i class="fa fa-th-list"></i>  '.$lang->get('sp_manage_pages') => 'backend/management.php',
	'<i class="fa fa-lock"></i>  '.$lang->get('sp_manage_permissions') => 'backend/permissions.php',
	'<i class="fa fa-file"></i>  '.$lang->get('sp_create_new') => 'backend/edit.php?new']; //Ein Array mit menüpunkten im adminbereich
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
	$confirmationUser = $config['confirmationUser'];
}
