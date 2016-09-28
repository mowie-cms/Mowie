<?php
session_name('adminsession');
session_start();

//Richtigen Pfad rausfinden
$path = '';
$pos = strpos($_SERVER['REQUEST_URI'], 'apps/');
//echo str_replace(substr($_SERVER['REQUEST_URI'], 0, $pos).'apps/', '', $_SERVER['REQUEST_URI']);
$rel = explode('/', str_replace(substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 0, $pos).'apps/', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
$count = count($rel);
$count = $count -1;

$i = 1;
while($i<$count)
{
	$path .= '../';
	$i++;
}

require_once $path.'../../inc/config.php';

//Language
$lang->setLangFolder( $path.'../../admin/lang/');
if(file_exists($path.'lang/') && is_dir($path.'lang/'))
{
	$lang->setLangFolder( $path.'lang/');
}

//init Apps
require_once $path.'../../inc/apps.php';
$apps = new apps();

//Require appsConfig
require_once $path.'config.php';

require_once $path.'../../inc/libs/functions.php';
