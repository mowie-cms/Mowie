<?php
//langstrings
$GLOBALS['lang']->set('Navigation', 'nav_title', 'de');
$GLOBALS['lang']->set('Navigation', 'nav_title', 'en');

$_CONF['app_name'] = 'Navigation';
$_CONF['app_build'] = 1;
$_CONF['app_version'] = 'v0.9 Beta';
$_CONF['base_file'] = 'nav.php';
$_CONF['type'] = 'static';

$_CONF['menu_top'] = '<i class="fa fa-bars"></i>  '.$GLOBALS['lang']->get('nav_title');
$_CONF['menu'] = ['menu_top' => 'index.php'];

$_CONF['css'] = ['css/nav.css'];