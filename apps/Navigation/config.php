<?php
$_CONF['app_name'] = 'Navigation';
$_CONF['app_build'] = 2;
$_CONF['app_version'] = 'v0.91 Beta';
$_CONF['base_file'] = 'nav.php';
$_CONF['type'] = 'static';
$_CONF['install'] = 'install.php';

$_CONF['menu_top'] = '<i class="fa fa-bars"></i>  ' . $lang->get('nav_title');
$_CONF['menu'] = ['menu_top' => 'index.php'];

$_CONF['css'] = ['css/nav.css'];

$_CONF['dependencies'] = ['apps' => ['SimplePages']];