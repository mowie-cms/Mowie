<?php
/*
 * Mowie Language Class
 *
 * -----------------
 * LANGUAGE: German
 * -----------------
 */
$lang = [];
$lang['__Lang__'] = 'German (Deutsch)';
$lang['__LangCode__'] = 'de';
$lang['__Countrycode__'] = 'de_DE';

//login
$lang['username'] = 'Benutzername';
$lang['password'] = 'Passwort';
$lang['2fa_code'] = 'Verifizierungscode';
$lang['login'] = 'Einloggen';
$lang['all_fields'] = 'Bitte alle Felder ausfüllen.';
$lang['error_2fa'] = 'Fehler bei der Anmeldung in zwei Schritten.';
$lang['wrong_username_or_pass'] = 'Benutzername oder Passwort falsch.';
$lang['wrong_pass'] = 'Falsches Passwort';
$lang['404_not_found'] = 'Die Seite wurde nicht gefunden.';
$lang['error_occured'] = 'Es ist ein Fehler aufgetreten.';

//Reset Password
$lang['reset_pass_title'] = 'Passwort zurücksetzen';
$lang['reset_pass_lost'] = 'Passwort vergessen?';
$lang['reset_pass_msg'] = 'Bitte geben Sie ihre Email-Adresse ein. Sie erhalten dann eine Emailadresse mit weiteren Instruktionen.';
$lang['reset_pass_mail'] = 'Email-Adresse';
$lang['reset_pass_button'] = 'Neues Passwort anfordern';
$lang['reset_pass_success'] = 'Wir haben ihnen eine Email mit weiteren Instruktionen geschickt.';
$lang['reset_pass_nomail'] = 'Diese Emailadresse ist nicht vorhanden. Bitte überprüfen sie die Emailadresse auf Tippfehler und probieren es erneut.';
$lang['reset_pass_error'] = 'Es trat ein Fehler auf.';
$lang['reset_pass_mail_title'] = 'Passwort auf %1$s zurücksetzen';
$lang['reset_pass_mail_message'] = "Hallo %1\$s,\n\n um ihr Passwort zurückzusetzen, klicken sie auf den folgenden Link:\n %2\$s \n\n Falls der Link nicht funktioneren sollte, kopieren sie ihn in die Adresszeile des Browsers.\n [Bitte antworten Sie nicht auf diese Email, da sie automatisch generiert wurde.]";
$lang['reset_pass_link_not_available'] = 'Dieser Account wurde nicht gefunden.';
$lang['reset_pass_reset'] = 'Passwort zurücksetzen';
$lang['reset_pass_reset_success'] = 'Das Passwort wurde erfolgreich geändert. Sie können sich jetzt <a href="index.php">Einloggen</a>';
$lang['reset_pass_reset_fail'] = 'Es trat ein Fehler beim Ändern des Passworts auf.';
$lang['reset_pass_reset_wrong_id'] = 'Die ID ist falsch.';

//Dashboard
$lang['delete_config_success'] = 'Die installationsdatei wurde erfolgreich gelöscht.';
$lang['os'] = 'Betriebssystem';
$lang['server_software'] = 'Server-Software';
$lang['php_version'] = 'PHP-Version';
$lang['mysql_version'] = 'Mysql-Version';
$lang['system_time'] = 'System-Zeit';
$lang['logfiles'] = 'Logfiles';
$lang['manage_pages'] = 'Seitenverwaltung';
$lang['manage_contents'] = 'Inhalte verwalten';
$lang['manage_files'] = 'Dateiverwaltung';
$lang['back_dashboard'] = 'Zurück zum Dashboard';
$lang['confirm'] = 'Bestätigen';
$lang['date'] = 'Datum';
$lang['ip'] = 'IP';
$lang['user_agent'] = 'User-Agent';
$lang['never'] = 'noch nie';

//General Admin
$lang['admin_title'] = 'Admin-Bereich';
$lang['settings'] = 'Einstellungen';
$lang['logout'] = 'Ausloggen';
$lang['main_page'] = 'Zur Hauptseite';
$lang['dashboard_title'] = 'Herzlich Willkommen im Admin-Bereich';
$lang['dashboard'] = 'Dashboard';
$lang['missing_permission'] = 'Fehlende Berechtigung';
$lang['back'] = 'Zurück';
$lang['general_yes'] = 'Ja';
$lang['general_no'] = 'Nein';
$lang['general_active'] = 'Aktiviert';
$lang['general_inactive'] = 'Nicht aktiviert';
$lang['general_activate'] = 'Aktivieren';
$lang['general_deactivate'] = 'Deaktivieren';
$lang['general_save_changes'] = 'Änderungen speichern';
$lang['general_needs_other_app'] = 'Diese App benötgt die andere App "%1$s" um ordnungsgemäß zu funktionieren.';
$lang['general_needs_other_version'] = 'Diese App benötigt mindestens Mowie in Version %1$s.';
$lang['general_needs_other_php'] = 'Diese App benötigt mindestens PHP in Version %1$s.';

//General Config
$lang['general_config'] = 'Systemkonfiguration';
$lang['general_website_title'] = 'Titel der Webseite';
$lang['general_construction_mode'] = 'Baustellenzustand';
$lang['general_end_construction_mode'] = 'Baustellenzustand aufheben';
$lang['general_start_construction_mode'] = 'Seite in Baustellenzustand versetzen';
$lang['general_edit_message'] = 'Meldung bearbeiten';
$lang['general_database'] = 'Datenbank';
$lang['general_create_backup'] = 'Datenbank Backup erstellen';

//Legitimation
$lang['legitimate_title'] = 'Legitimierung benötigt';
$lang['legitimate_text'] = 'Dieser Vorgang benötigt eine Passwortbestätigung.';
$lang['legitimate_confirm'] = 'Bestätigen';
$lang['legitimate_abort'] = 'Abbrechen';
$lang['legitimate_error'] = 'Beim Legitimieren ist ein Fehler aufgetreten.';
$lang['legitimate_fail'] = 'Falsches Passwort.';

/*
 * Manage Admins
 */

//General
$lang['admins_title'] = 'Administratoren';
$lang['admins_list'] = 'Benutzerliste';
$lang['admins_groups'] = 'Benutzergruppen';
$lang['admins_permissions'] = 'Berechtigungen';
$lang['admins_group'] = 'Gruppe';
$lang['admins_create_new'] = 'Neuen Benutzer anlegen';
//Admin List
$lang['admins_id'] = 'ID';
$lang['admins_users'] = 'Benutzer';
$lang['admins_username'] = 'Name';
$lang['admins_mail'] = 'Email-Adresse';
$lang['admins_not_set'] = 'Nicht angegeben';
$lang['admins_write_mail'] = 'Eine Email an %1$s schreiben';
//Admin Roles
$lang['admins_roles_added_success'] = 'Der Nutzer wurde erfolgreich der Gruppe hinzugefügt.';
$lang['admins_roles_added_fail'] = 'Fehler beim Hinzufügen des Nutzers.';
$lang['admins_roles_delete_group'] = 'Gruppe löschen';
$lang['admins_roles_delete_error'] = 'Diese Gruppe kann nicht gelöscht werden.';
$lang['admins_roles_delete_success'] = 'Die Gruppe wurde erfolgreich gelöscht.';
$lang['admins_roles_delete_fail'] = 'Fehler beim Löschen der Gruppe.';
$lang['admins_roles_delete_confirm'] = 'Möchten Sie die Gruppe wirklich löschen? <b>Diese Aktion kann nicht Rückgängig gemacht werden!</b>';
$lang['admins_roles_user_delete_success'] = 'Der Nutzer wurde erfolgreich aus der Gruppe entfernt.';
$lang['admins_roles_user_delete_fail'] = 'Fehler beim Entfernen des Nutzers.';
$lang['admins_roles_user_delete_confirm'] = 'Möchten Sie den Nutzer wirklich aus der Gruppe entfernen? Er wird daurch alle ihm zugewiesenen Rechte verlieren! <br/><b>Diese Aktion kann nicht Rückgängig gemacht werden!</b>';
$lang['admins_roles_members'] = 'Gruppenmitglieder';
$lang['admins_roles_member_remove'] = 'Nutzer Entfernen';
$lang['admins_roles_no_members_yet'] = 'Diese Gruppe hat noch keine Mitglieder.';
$lang['admins_roles_already_all_members'] = 'Es sind alle Nutzer Mitglieder dieser Gruppe.';
$lang['admins_roles_add_user'] = 'Nutzer hinzufügen';
$lang['admins_roles_create_group_success'] = 'Die neue Gruppe wurde erfolgreich angelegt.';
$lang['admins_roles_create_group_fail'] = 'Fehler beim Anlegen der neuen Gruppe.';
$lang['admins_roles_create_group'] = 'Neue Gruppe anlegen';
$lang['admins_roles_group_name'] = 'Gruppenname';
$lang['admins_roles_group'] = 'Gruppe';
$lang['admins_roles_level'] = 'Level';
$lang['admins_roles_name'] = 'Name';
//Admin Permissions
$lang['admins_perms_set_success'] = 'Die neuen Berechtigungen wurden erfolgreich vergeben.';
$lang['admins_perms_set_fail'] = 'Fehler beim Vergeben der neuen Berechtigungen.';
$lang['admins_perms_critical'] = 'Vergeben Sie diese Berechtigung nur an Vertrauenswürdige Personen, um Sicherheitsproblemen vorzubeugen';
$lang['admins_perms_save'] = 'Berechtigungen ändern';
//Create New User
$lang['admins_cn_success'] = '"%1$s" wurde erfolgreich neuer Nutzer registriert.';
$lang['admins_cn_fail'] = 'Fehler beim Erstellen des neuen Benutzers.';
$lang['admins_cn_name_already_in_use'] = 'Benutzername Schon vergeben.';
$lang['admins_cn_pw_not_match'] = 'Die beiden Passwörtzer stimmen nicht überein.';
$lang['admins_cn_username'] = 'Gewünschter Benutzername';
$lang['admins_cn_password'] = 'Passwort';
$lang['admins_cn_password_again'] = 'Passwort erneut eingeben';
$lang['admins_cn_create'] = 'Benutzer erstellen';
$lang['admins_cn_missing_inputs'] = 'Bitte alle Felder ausfüllen.';

//User Settings
$lang['user_settings_title'] = 'Benutzereinstellungen';
$lang['user_settings_pw_not_empty'] = 'Das Passwort darf nicht Leer sein!';
$lang['user_settings_pw_change_success'] = 'Das Passwort wurde erfolgreich ge&auml;ndert.';
$lang['user_settings_pw_change_fail'] = 'Fehler beim Ä;ndern des Passworts.';
$lang['user_settings_pw_not_match'] = 'Die beiden Passwörter stimmen nicht überein.';
$lang['user_settings_new_pass'] = 'Neues Passwort eingeben';
$lang['user_settings_new_pass_confirm'] = 'Neues Passwort bestätigen';
$lang['user_settings_enter_current_pass'] = 'Geben Sie ihr aktuelles Passwort ein';
$lang['user_settings_error_logout_all_devices'] = 'Fehler beim Ausloggen auf allen Geräten.';
$lang['user_settings_current_sessions'] = 'Zurzeit offene Anmeldungen';
$lang['user_settings_current_session'] = 'Aktuelle Anmeldung';
$lang['user_settings_current_sessions_logout_all'] = 'Überall abmelden';
$lang['user_settings_2fa'] = 'Anmeldung in zwei Schritten';
$lang['user_settings_2fa_deactivate'] = 'Anmeldung in zwei Schritten deaktivieren';
$lang['user_settings_2fa_deactivate_success'] = 'Die Anmeldung in zwei Schritten wurde erfolgreich deaktiviert.';
$lang['user_settings_2fa_deactivate_fail'] = 'Fehler beim Deaktivieren der Anmeldung in zwei Schritten.';
$lang['user_settings_2fa_deactivate_confirm'] = 'Sind sie sicher, die Anmeldung in zwei Schritten wirklich zu deaktivieren?';
$lang['user_settings_2fa_activate'] = 'Anmeldung in zwei Schritten aktivieren';
$lang['user_settings_2fa_activate_success'] = 'Die Anmeldung in zwei Schritten wurde erfolgreich eingerichtet.';
$lang['user_settings_2fa_activate_fail'] = 'Fehler beim Einrichten der Anmeldung in zwei Schritten.';
$lang['user_settings_2fa_activate_wrong_code'] = 'Falscher Code.';
$lang['user_settings_2fa_activate_import_code'] = 'Importieren Sie diese Information in ihre Google-Authenticator-Anwendung (oder andere TOTP-Anwendung), indem Sie den unten bereitgestellten QR-Code verwenden oder den Code manuell eingeben.';
$lang['user_settings_2fa_key'] = 'Schlüssel';
$lang['user_settings_2fa_confirm_code'] = 'Nach einer einmaligen Überprüfung des Codes wird die Anmeldung in zwei Schritten für diesen Account aktiviert';
$lang['user_settings_2fa_enter_code'] = 'Code eingeben...';
$lang['user_settings_2fa_test'] = 'Überprüfen';
$lang['user_settings_settings_success'] = 'Die Änderungen des Nutzers wurden erfolgreich gespeichert.';
$lang['user_settings_settings_fail'] = 'Fehler beim Speichern der Änderungen des Nutzers.';
$lang['user_settings_settings_pass'] = 'Passwort ändern';
$lang['user_settings_last_login'] = 'Letzter Login';
$lang['user_settings_show_current_sessions'] = 'Offene Anmeldungen anzeigen';
$lang['user_settings_log_level'] = 'Log-Level für Benachrichtigung';
$lang['user_settings_log_level_1'] = 'Nur wichtiges';
$lang['user_settings_log_level_2'] = 'System-Mitteilungen';
$lang['user_settings_log_level_3'] = 'Änderungen';
$lang['user_settings_log_level_4'] = 'Generelle Mitteilungen';
$lang['user_settings_log_level_fail'] = 'Fehler beim Speichern des Log-Levels.';
$lang['user_settings_log_level_success'] = 'Die Änderungen des Log-Levels wurden erfolgreich gespeichert.';
$lang['user_settings_none'] = 'Keine. <a href="%1$s">Ändern</a>';

//Mail
$lang['mail_write'] = 'Email schreiben';
$lang['mail_success'] = 'Die Email an "%1$s" wurde erfolgreich abgeschickt.';
$lang['mail_write_to'] = 'Eine Email an %1$s Schreiben';
$lang['mail_fail'] = 'Fehler beim Schreiben der Email';
$lang['mail_subject'] = 'Betreff';
$lang['mail_message'] = 'Nachricht';
$lang['mail_send'] = 'Email abschicken';

//Action
$lang['action_backup_fail'] = 'Fehler beim Erstellen des Backups.';
$lang['action_edit_content'] = 'Seite bearbeiten';
$lang['action_construction_message_success'] = 'Die Meldung wurde erfolgreich ge&auml;ndert.';
$lang['action_try_again_later'] = 'Fehler. Bitte versuchen Sie es später noch einmal.';
$lang['action_construction_message_edit'] = 'Baustellen-Meldung bearbeiten';
$lang['action_construction_success'] = 'Die Webseite wurde erfolgreich in den Baustellenzustand versetzt.';
$lang['action_construction_error'] = 'Beim Versetzen in den Baustellenmodus trat ein Fehler auf.';
$lang['action_construction_confirm'] = 'Wollen Sie die Webseite wirklich in den Baustellenzustand verstzten?';
$lang['action_construction_removed_success'] = 'Der Baustellenzustand wurde erfolgreich aufgehoben.';
$lang['action_construction_removed_error'] = 'Beim Aufheben des Baustellenzustandes ist ein Fehler aufgetreten.';
$lang['action_construction_remove'] = 'Wollen Sie den Baustellenzustand wirklich aufheben?';
$lang['action_change_page_title_success'] = 'Die Änderungen des Seitentitels wurden erfolgreich gespeichert.';

//Update
$lang['update_title'] = 'Update';
$lang['update_showChangelog'] = 'Changelog anzeigen';
$lang['update_item_succss'] = '"%1$s" wurde erfolgreich upgedatet.';
$lang['update_item_fail'] = 'Fehler beim Updaten von "%1$s"';
$lang['update_succss'] = 'Mowie CMS wurde erfolgreich upgedatet.';
$lang['update_app_succss'] = '"%1$s" wurde erfolgreich upgedatet.';
$lang['update_fail'] = 'Fehler beim Updaten.';
$lang['update_config_fail'] = 'Beim Bearbeiten der Configdatei ist ein Fehler aufgetreten.';
$lang['update_fail_unzip'] = 'Fehler beim Entpacken des Updates.';
$lang['update_wrong_hash'] = 'Die Heruntergeladene Datei ist vermutlich falsch.';
$lang['update_fail_copy'] = 'Fehler beim Herunterladen des Updates. <b>Hinweis:</b> Der Nutzer, unter welchem der Webserver läuft, muss im Verzeichnis /admin Schreibrechte haben!';
$lang['update_version'] = 'Version';
$lang['update_version_current'] = 'Installierte Version';
$lang['update_new_version'] = 'Neue Version verfügbar!';
$lang['update_version_current_new'] = 'Die installierte Version ist aktuell.';
$lang['update_app_update_available'] = 'App-Update - Neue Version für "%1$s" verfügbar: %2$s';
$lang['update_log'] = '{user} hat das System geupdated.';
$lang['update_folder_not_writeable'] = 'Der Updateordner ist nicht schreibbar. Bitte stelle sicher, dass der Webserver Schreibrechte im Updateordner hat.';
$lang['update_create_backup_error'] = 'Beim Erstellen eines Backups ist ein Fehler aufgetreten.';
$lang['update_cleanup_error'] = 'Beim Aufräumen ist ein Fehler aufgetreten.';
$lang['update_checking'] = 'Überprüfe auf Updates...';

//Stream Messages
$lang['stream_saved_settings'] = '{user} hat seinen Benutzername/Email-Adresse geändert.';
$lang['stream_logged_out'] = '{user} hat sich ausgeloggt.';
$lang['stream_logged_in'] = '{user} hat sich eingeloggt.';
$lang['stream_pass_changed'] = '{user}\'s passwort wurde geändert.';
$lang['stream_db_backup'] = '{user} hat ein Datenbank-Backup gemacht.';
$lang['stream_construction_mode'] = '{user} hat die Seite in Baustellenmodus versetzt.';
$lang['stream_construction_mode_message'] = '{user} hat die Baustellenmodus-Nachricht geändert.';
$lang['stream_construction_mode_delete'] = '{user} hat den Baustellenmodus beendet.';
$lang['stream_edited_page_title'] = '{user} hat den Seitentitel geändert.';
$lang['stream_created_user'] = '{user} hat den neuen Nutzer "{extra}" erstellt.';
$lang['stream_edited_permissions'] = '{user} hat die Berechtigungen bearbeitet.';
$lang['stream_added_to_group'] = '{user} hat einen Nutzer zur Gruppe "{extra}" hinzugefügt.';
$lang['stream_added_group'] = '{user} hat die Gruppe "{extra}" erstellt.';
$lang['stream_deleted_group'] = '{user} hat die Gruppe "{extra}" gelöscht.';
$lang['stream_deleted_user'] = '{user} hat einen Nutzer gelöscht.';
$lang['stream_created_group'] = '{user} hat eine neue Gruppe erstellt.';
$lang['stream_system_update'] = '{user} hat das System geupdated.';
$lang['stream_app_update'] = '{user} hat die App "{extra}" geupdated.';

//Permissions
$lang['system_perm_view_dashboard_name'] = 'Relevante Informationen auf dem Dashboard ansehen.';
$lang['system_perm_view_dashboard_description'] = '';
$lang['system_perm_manage_system_name'] = 'Systemeinstellungen vornehmen.';
$lang['system_perm_manage_system_description'] = '';
$lang['system_perm_construction_name'] = 'Baustellenzustand ändern';
$lang['system_perm_construction_description'] = 'Im Baustellenzustand wird Anstelle der Seite ein Text angezeigt.';
$lang['system_perm_update_name'] = 'Systemupdate durchführen';
$lang['system_perm_update_description'] = 'Nach neuen Versionen des Systems suchen und updaten.';
$lang['system_perm_db_dump_name'] = 'Datenbanbackup herunterladen';
$lang['system_perm_db_dump_description'] = 'Ein komplettes Backup der Datenbank herunterladen.';
$lang['system_perm_manage_admins_name'] = 'Administratoren verwalten';
$lang['system_perm_manage_admins_description'] = '';
$lang['system_perm_manage_groups_name'] = 'Admingruppen verwalten';
$lang['system_perm_manage_groups_description'] = '';
$lang['system_perm_edit_permissions_name'] = 'Adminberechtigungen verwalten';
$lang['system_perm_edit_permissions_description'] = '';