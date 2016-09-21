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

//Menu
$lang['sp_pages'] = 'Seiten';
$lang['sp_manage_pages'] = 'Seitenverwaltung';
$lang['sp_manage_permissions'] = 'Berechtigungen verwalten';
$lang['sp_create_new'] = 'Neue Seite erstellen';
$lang['sp_confirm'] = 'Änderungen Freischalten';

$lang['sp_edit_pages_to_edit'] = 'Seiten, die Sie editieren d&uuml;rfen';
$lang['sp_edit_pages_to_confirm'] = 'Seiten zum Freischalten';

//Manage Pages
$lang['sp_page'] = 'Seite';
$lang['sp_last_edit'] = 'Zuletzt berbeitet';
$lang['sp_action'] = 'Aktion';
$lang['sp_edited_by_date'] = 'am %1$s von %2$s';
$lang['sp_never'] = 'noch nie';
$lang['sp_edit'] = 'Bearbeiten';
$lang['sp_delete'] = 'Löschen';
$lang['sp_preview'] = 'Vorschau';
$lang['sp_permissions'] = 'Berechtigungen';
$lang['sp_manage_permission'] = 'Berechtigungen vergeben';

//Status
$lang['sp_status_inactive'] = 'Inaktiv';
$lang['sp_status_active'] = 'Aktiv';
$lang['sp_status_pending_confirmation'] = 'Warte auf Freischaltung';

//Permissions
$lang['sp_user_already_access'] = '%1$s hat schon Zugriff auf diese Seite.';
$lang['sp_grant_permissions_success'] = 'Die neuen Berechtigungen wurden erfolgreich vergeben';
$lang['sp_grant_permissions_fail'] = 'Es ist ein Fehler beim Vergeben der neuen Berechtigungen festgestellt worden.';
$lang['sp_user_nexist'] = 'Der Benutzer existiert nicht.';
$lang['sp_grant_permissions'] = 'Seite für neuen Benutzer freigeben';

//Confirm
$lang['sp_confirm_success'] = 'Die Änderungen wurden erfolgreich freigeschaltet.';
$lang['sp_confirm_fail'] = 'Fehler beim Freischalten der Änderungen.';
$lang['sp_confirm_delete_success'] = 'Die Änderungen wurden erfolgreich gelöscht.';
$lang['sp_confirm_delete_fail'] = 'Fehler beim Löschen der Änderungen.';
$lang['sp_confirm_changed'] = 'Wurde am %1$s von %2$s geändert (Veränderte Felder werden angezeigt)';
$lang['sp_confirm_created'] = 'Wurde am %1$s von %2$s erstellt';
$lang['sp_confirm_confirm'] = 'Freischalten';
$lang['sp_confirm_delete'] = 'Verwerfen';
$lang['sp_confirm_no_changes'] = 'Es wurden keine Änderungen zum Freischalten gefunden.';

//Edit
$lang['sp_edit'] = 'Seite bearbeiten';
$lang['sp_edit_title'] = 'Titel';
$lang['sp_edit_alias'] = 'Alias (URL)';
$lang['sp_edit_content'] = 'Inhalt';
$lang['sp_edit_status'] = 'Status';
$lang['sp_edit_public'] = 'Öffentlich';
$lang['sp_edit_description'] = 'Beschreibung';
$lang['sp_edit_keywords'] = 'Keywords';
$lang['sp_edit_created'] = 'Die Seite "%1$s" wurde erfolgreich erstellt.';
$lang['sp_edit_edited'] = 'Die Seite "%1$s" wurde erfolgreich geändert.';
$lang['sp_edit_edited_need_confirm'] = 'Diese Änderungen werden öffentlich, sobald %1$s sie Freigegeben hat.';
$lang['sp_edit_edit_error'] = 'Fehler beim Ändern der Seite.';
$lang['sp_edit_confirm_subject'] = 'Freischaltung der Seite "%1$s"';
$lang['sp_edit_confirm_message'] = '%1$s hat die Seite "%2$s" ge&auml;ndert. Diese steht jetzt zum Freischalten f&uuml;r dich bereit. <a href="%3$s">Freischalten</a>';
$lang['sp_create_confirm_message'] = '%1$s hat die Seite "%2$s" erstellt. Diese steht jetzt zum Freischalten f&uuml;r dich bereit. <a href="%3$s">Freischalten</a>';
$lang['sp_edit_delete_success'] = 'Die Seite wurde erfolgreich gelöscht.';
$lang['sp_edit_delete'] = '"%1$s" löschen';
$lang['sp_edit_delete_confirm'] = 'Soll die Seite "%1$s" wirklich gelöscht werden?<br/><b>Dieser Vorgang kann nicht rückgängig gemacht werden!</b>';
$lang['sp_edit_page_not_found'] = 'Diese Seite existiert nicht.';
$lang['sp_edit_edited_not_confirmed'] = 'Diese Seite wurde am %1$s von <b>%2$s</b> geändert und noch nicht freigeschaltet.';
$lang['sp_edit_create_new'] = 'Neue Seite erstellen';
$lang['sp_edit_edit_page'] = '"%1$s" bearbeiten';
$lang['sp_edit_last_edited'] = 'Zuletzt bearbeitet von %1$s am %2$s';
$lang['sp_edit_save'] = 'Speichern';
$lang['sp_edit_no_page_defined'] = 'Es ist keine Seite definiert.';