<?php
session_name('adminsession');
session_start();
require_once '../vendor/autoload.php';
require_once '../inc/config.php';

if(isset($_GET['set']))
{
	setcookie ('lang', $_GET['set'], time()+5184000, '/');
	echo '1';
}