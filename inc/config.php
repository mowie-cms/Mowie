<?php
header('Charset: utf-8');
header('Generator: Mowie_CMS');
date_default_timezone_set('Europe/Berlin');

//Parse Config
$config = [];

require_once 'libs/YAML/autoload.php';
use Symfony\Component\Yaml\Yaml;
$config = Yaml::parse(file_get_contents('config.yml', FILE_USE_INCLUDE_PATH));
if(file_exists(empty($config)))
{
	echo 'Error finding config file.';
	exit;
}

//DB Config
$MCONF['db_host'] = $config['Database']['db_host'];
$MCONF['db_name'] = $config['Database']['db_name'];
$MCONF['db_usr'] = $config['Database']['db_usr'];
$MCONF['db_pw'] = $config['Database']['db_pw'];
$MCONF['db_prefix'] = $config['Database']['db_prefix'];

require_once 'libs/db-mysql.php';
$db = new db($MCONF['db_host'], $MCONF['db_name'], $MCONF['db_usr'], $MCONF['db_pw'], $MCONF['db_prefix']);

//General
$MCONF['web_uri'] = $config['General']['web_uri'];
$MCONF['home_uri'] = $config['General']['home_uri'];
$MCONF['phpmyadmin'] = $config['General']['phpmyadmin'];
$MCONF['title'] = file_get_contents($MCONF['web_uri'].$config['General']['title']);
$MCONF['log_uri'] = $config['General']['log_uri'];
$MCONF['tinymce_css'] = $MCONF['web_uri'].$config['General']['tinymce_css'];

//Templateing
$MCONF['template'] = $config['Templating']['template'];
$MCONF['tpl_title'] = $config['Templating']['tpl_title'];
$MCONF['tpl_content'] = $config['Templating']['tpl_content'];
$MCONF['tpl_webUri'] = $config['Templating']['tpl_webUri'];

//Versioning
$MCONF['version'] = $config['Versioning']['version'];
$MCONF['version_num'] = $config['Versioning']['version_num'];
$MCONF['update_uri'] = $config['Versioning']['update_uri'];

//Mailer
$MCONF['smtp'] = $config['Mail']['smtp'];
if($MCONF['smtp'] === true)
{
	$MCONF['smtp_host'] = $config['Mail']['host'];
	$MCONF['smtp_user'] = $config['Mail']['username'];
	$MCONF['smtp_pass'] = $config['Mail']['password'];
	$MCONF['smtp_secure'] = $config['Mail']['secure'];
	$MCONF['smtp_port'] = $config['Mail']['port'];
}

require_once 'libs/lang.class.php';

$lang = new lang();
