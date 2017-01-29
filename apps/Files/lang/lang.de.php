<?php
/*
 * Mowie Language Class
 *
 * -----------------
 * LANGUAGE: German
 * Files app
 * -----------------
 */
$lang = [];
$lang['__Lang__'] = 'German (Deutsch)';
$lang['__LangCode__'] = 'de';
$lang['__Countrycode__'] = 'de_DE';

$lang['files_title'] = 'Dateien';
$lang['files_drag_drop_area'] = 'Dateien hierher ziehen und loslassen, um sie in den aktuellen Order Hochzuladen';
$lang['files_upload'] = 'Hochladen';
$lang['files_create_dir'] = 'Neuen Ordner erstellen';
$lang['files_delete_current_dir'] = 'Aktuellen Ordner löschen';

$lang['files_filename'] = 'Dateiname';
$lang['files_last_modified'] = 'Änderungsdatum';
$lang['files_filetype'] = 'Typ';
$lang['files_filesize'] = 'Größe';
$lang['files_folder_not_found'] = 'Der Ordner wurde nicht gefunden.';
$lang['files_error'] = 'Ein Fehler ist aufgetreten.';
$lang['files_reload'] = '<a href="">Seite neu laden</a> und erneut versuchen';
$lang['files_url'] = 'URL';
$lang['files_download'] = 'Herunterladen';
$lang['files_delete'] = 'Löschen';
$lang['files_abort'] = 'Abbrechen';
$lang['files_enter_name'] = 'Namen eingeben...';
$lang['files_creating_new_folder'] = 'Erstelle neuen Ordner...';
$lang['files_create_dir_success'] = 'Der neue Ordner wurde erfolgreich erstellt.';
$lang['files_create_dir_fail'] = 'Fehler beim Erstellen des Ordners.';
$lang['files_delete_file'] = 'löschen';
$lang['files_delete_dir_confirm'] = 'Ordner wirklich löschen? Dies wird den gesamten Ordner mit Inhalt löschen! <b>Dieser Vorgang kann nicht rückgängig gemacht werden!</b>';
$lang['files_delete_file_confirm'] = 'Datei wirklich löschen? <b>Dieser Vorgang kann nicht rückgängig gemacht werden!</b>';
$lang['files_deleting_folder'] = 'Lösche Ordner...';
$lang['files_deleting_file'] = 'Lösche Datei...';
$lang['files_delete_dir_success'] = 'Der Ordner wurde erfolgreich gelöscht.';
$lang['files_delete_file_success'] = 'Die Datei wurde erfolgreich gelöscht.';
$lang['files_delete_error'] = 'Fehler beim Löschen der Datei.';
$lang['files_too_large_file'] = 'Die ausgewählte Datei ist zu groß!';
$lang['files_processing'] = 'Datei wird verarbeitet...';
$lang['files_upload_finished'] = 'Upload abgeschlossen.';
$lang['files_upload_failed'] = 'Upload fehlgeschlagen.';
$lang['files_folder_empty'] = 'Dieser Ordner ist leer. Lade Dateien hoch, um in zu füllen!';

//Stream Messages
$lang['files_stream_deleted_folder'] = '{user} hat den Ordner "{extra}" gelöscht.';
$lang['files_stream_deleted_file'] = '{user} hat die Datei "{extra}" gelöscht.';
$lang['files_stream_uploaded'] = '{user} hat die Datei "{extra}" hochgeladen.';
$lang['files_stream_created_folder'] = '{user} hat den Ordner "{extra}" erstellt.';

//Permissions
$lang['file_perm_manage_files_name'] = 'Dateien verwalten';
$lang['file_perm_manage_files_description'] = 'Dateien ansehen & verwalten';
$lang['file_perm_upload_name'] = 'Dateien hochladen';
$lang['file_perm_create_folder_name'] = 'Neuen Ordner erstellen';
$lang['file_perm_delete_name'] = 'Dateien löschen';