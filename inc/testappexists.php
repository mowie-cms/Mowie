<?php
require_once 'libs/lang.class.php';
$lang = new lang();

require_once 'apps.php';
$apps = new apps(2);

//print_r($apps->getApps());

var_dump($apps->appExists('SsadasimplePages'));