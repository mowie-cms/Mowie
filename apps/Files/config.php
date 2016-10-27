<?php
$GLOBALS['lang']->set('Dateiverwaltung', 'files_title', 'de');
$GLOBALS['lang']->set('Manage Files', 'files_title', 'en');

$_CONF['app_name'] = 'Files';
$_CONF['app_desc'] = 'Ein Modul zum Anzeigen & Uploaden von Dateien';
$_CONF['menu_top'] = '<i class="fa fa-folder"></i> '.$GLOBALS['lang']->get('files_title');
$_CONF['menu'] = ['menu_top' => 'index.php'];
$_CONF['type'] = 'none';

$_CONF['css'] = ['css/files.css'];