<?php
session_name('adminsession');
session_start();
require_once '../inc/config.php';

//Language
//$lang = new lang();
$lang->setLangFolder('lang/');

//init Apps
require_once '../inc/apps.php';
$apps = new apps();

require_once '../inc/libs/functions.php';