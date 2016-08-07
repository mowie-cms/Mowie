<?php
session_name('adminsession');
session_start();
require_once '../inc/config.php';

if(isset($_GET['set']))
{
	$_SESSION['lang'] = $_GET['set'];
	echo '1';
}