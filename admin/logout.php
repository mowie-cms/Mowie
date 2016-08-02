<?php
session_name('adminsession');session_start();
require_once '../inc/config.php';
require_once '../inc/libs/functions.php';

$db->setCol('system_loggedin');
$db->data['token'] = $_SESSION['token'];
$db->delete();

session_destroy();
header('Location: '.$MCONF['web_uri'].'admin/?msg=3');
