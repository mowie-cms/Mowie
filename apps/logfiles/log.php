<?php
require_once $appUri.'/Log.class.php';

$logFileName = date('Y-m-d');
$headerTitle = 'Logfiles from '.$_SERVER['SERVER_NAME'];
$logMode = 'oneFile';
$counterFile = 'count.counter';
$log = new Logging($MCONF['log_uri'],$logFileName,$headerTitle, $logMode, $counterFile);

$charset = '';
if(isset($_SERVER['HTTP_ACCEPT_CHARSET']))
{
	$charset = $_SERVER['HTTP_ACCEPT_CHARSET'];
}
$logstring = $_SERVER['REMOTE_ADDR'].' - '.$_SERVER['SERVER_NAME'].' - ['.date('d.m.Y:H:i:s').'] '.$_SERVER['SERVER_PROTOCOL'].' '.http_response_code().' "'.$_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI'].'" "'.$_SERVER['HTTP_USER_AGENT'].'" '.$charset.'  '.$_SERVER['HTTP_ACCEPT_LANGUAGE'].' '.$_SERVER['HTTP_CONNECTION'];
$log->logThis($logstring);