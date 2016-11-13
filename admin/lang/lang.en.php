<?php
/*
 * Mowie Language Class
 *
 * -----------------
 * LANGUAGE: English
 * -----------------
 */
$lang = [];
$lang['__Lang__'] = 'English (English)';
$lang['__LangCode__'] = 'en';
$lang['__Countrycode__'] = 'en';

//login
$lang['username'] = 'Username';
$lang['password'] = 'Password';
$lang['2fa_code'] = 'Authentificationcode';
$lang['login'] = 'Login';
$lang['all_fields'] = 'Please fill in all fields';
$lang['error_2fa'] = 'Error with 2-Step Verification authentication';
$lang['wrong_username_or_pass'] = 'Wrong username or password.';
$lang['wrong_pass'] = 'Wrong password';
$lang['404_not_found'] = 'The page you requested was not found.';

//Reset Password
$lang['reset_pass_title'] = 'Reset Password';
$lang['reset_pass_lost'] = 'Lost Password?';
$lang['reset_pass_msg'] = 'Please enter your Email-Address. You\'ll get an email with further instructions.';
$lang['reset_pass_mail'] = 'Email-Adress';
$lang['reset_pass_button'] = 'Request New Password';
$lang['reset_pass_success'] = 'We successfully sent you an Email with further instructions.';
$lang['reset_pass_nomail'] = 'This Email-Address doesn\'t exist. Please check it for typos and try again.';
$lang['reset_pass_error'] = 'An error occured.';
$lang['reset_pass_mail_title'] = 'Reset your password on %1$s';
$lang['reset_pass_mail_message'] = "Hi %1\$s,\n\n to reset your password, click on this link:\n %2\$s \n\n If the link doesn't work, copy and paste the URL in your webbrowser.\n [This is an auto-generated mesage, please do not replay to this email.]";
$lang['reset_pass_link_not_available'] = 'The account was not found.';
$lang['reset_pass_reset'] = 'Reset Password';
$lang['reset_pass_reset_success'] = 'The password was resetted successfully. You can now <a href="index.php">login</a>.';
$lang['reset_pass_reset_fail'] = 'An Error occured while resetting yout password.';
$lang['reset_pass_reset_wrong_id'] = 'Wrong ID.';

//Dashboard
$lang['delete_config_success'] = 'The Install-file was deleted successfully';
$lang['os'] = 'Operating System';
$lang['server_software'] = 'Server-Software';
$lang['php_version'] = 'PHP-Version';
$lang['mysql_version'] = 'Mysql-Version';
$lang['system_time'] = 'System-Time';
$lang['logfiles'] = 'Logfiles';
$lang['manage_pages'] = 'Manage Pages';
$lang['manage_contents'] = 'Manage Contents';
$lang['manage_files'] = 'Manage Files';
$lang['back_dashboard'] = 'Back to Dashboard';
$lang['confirm'] = 'Confirm';
$lang['date'] = 'Date';
$lang['ip'] = 'IP';
$lang['user_agent'] = 'User-Agent';
$lang['never'] = 'never';

//General Admin
$lang['admin_title'] = 'Admin';
$lang['settings'] = 'Settings';
$lang['logout'] = 'Logout';
$lang['main_page'] = 'Mainpage';
$lang['dashboard_title'] = 'Welcome to the dashboard';
$lang['dashboard'] = 'Dashboard';
$lang['missing_permission'] = 'Missing Permission';
$lang['back'] = 'Back';
$lang['general_yes'] = 'Yes';
$lang['general_no'] = 'No';
$lang['general_active'] = 'Active';
$lang['general_inactive'] = 'Inactive';
$lang['general_activate'] = 'Activate';
$lang['general_deactivate'] = 'Disable';
$lang['general_save_changes'] = 'Save changes';

//General Config
$lang['general_config'] = 'Systemconfiguration';
$lang['general_website_title'] = 'Website Title';
$lang['general_construction_mode'] = 'Construction Mode';
$lang['general_end_construction_mode'] = 'End Construction Mode';
$lang['general_start_construction_mode'] = 'Start Construction Mode';
$lang['general_edit_message'] = 'Edit Construction Message';
$lang['general_version'] = 'Version';
$lang['general_database'] = 'Database';
$lang['general_create_backup'] = 'Create Database Backup';
$lang['general_go_phpmyadmin'] = 'phpmyadmin';

/*
 * Manage Admins
 */

//General
$lang['admins_title'] = 'Administrators';
$lang['admins_list'] = 'Userlist';
$lang['admins_groups'] = 'Usergroups';
$lang['admins_permissions'] = 'Permissions';
$lang['admins_create_new'] = 'Create New User';
//Admin List
$lang['admins_id'] = 'ID';
$lang['admins_users'] = 'Users';
$lang['admins_username'] = 'Name';
$lang['admins_mail'] = 'Email-Adress';
$lang['admins_not_set'] = 'not set';
$lang['admins_write_mail'] = 'Write an email to %1$s';
//Admin Roles
$lang['admins_roles_added_success'] = 'The user was added to the group successfully.';
$lang['admins_roles_added_fail'] = 'An error occured while adding the user to the group.';
$lang['admins_roles_delete_group'] = 'Delete Group';
$lang['admins_roles_delete_error'] = 'This Group cannot be deleted.';
$lang['admins_roles_delete_success'] = 'The Group was deleted successfully.';
$lang['admins_roles_delete_fail'] = 'An error occured while deleting the group.';
$lang['admins_roles_delete_confirm'] = 'Are you sure to delete this group? <b>You cannot undo this!</b>';
$lang['admins_roles_user_delete_success'] = 'The user was removed from the group successfully.';
$lang['admins_roles_user_delete_fail'] = 'An error occured while removing the user from the group.';
$lang['admins_roles_user_delete_confirm'] = 'Are you sure to remove the user from this group? It will loose all of its rights! <br/><b>You cannot undo this!</b>';
$lang['admins_roles_members'] = 'Group Members';
$lang['admins_roles_member_remove'] = 'Remove User';
$lang['admins_roles_no_members_yet'] = 'This group doesn\'t have any members yet.';
$lang['admins_roles_already_all_members'] = 'All users are a member of this group.';
$lang['admins_roles_add_user'] = 'Add User';
$lang['admins_roles_create_group_success'] = 'The new group was created successfully.';
$lang['admins_roles_create_group_fail'] = 'An error occured while creating the new group.';
$lang['admins_roles_create_group'] = 'Create New Group';
$lang['admins_roles_group_name'] = 'Group Name';
$lang['admins_roles_group'] = 'Group';
$lang['admins_roles_level'] = 'Level';
$lang['admins_roles_name'] = 'Name';
//Admin Permissions
$lang['admins_perms_set_success'] = 'Granted new permissions successfully.';
$lang['admins_perms_set_fail'] = 'An error occured while granting new permissions.';
$lang['admins_perms_critical'] = 'Grant this permission to trusted users only, to avoid security issues';
$lang['admins_perms_save'] = 'Save Permissions';
//Create New User
$lang['admins_cn_success'] = '"%1$s" was registered successfully as a new user.';
$lang['admins_cn_fail'] = 'An error occured while creating the new user.';
$lang['admins_cn_name_already_in_use'] = 'This username already exists.';
$lang['admins_cn_pw_not_match'] = 'Passwords do not match.';
$lang['admins_cn_username'] = 'Username';
$lang['admins_cn_password'] = 'Password';
$lang['admins_cn_password_again'] = 'Enter password again';
$lang['admins_cn_create'] = 'Create User';
$lang['admins_cn_missing_inputs'] = 'Please fill in all fields.';

//User Settings
$lang['user_settings_title'] = 'User Settings';
$lang['user_settings_pw_not_empty'] = 'Password cannot be empty!';
$lang['user_settings_pw_change_success'] = 'Password was changed successfully.';
$lang['user_settings_pw_change_fail'] = 'An error occured while changing password.';
$lang['user_settings_pw_not_match'] = 'Passwords do not match.';
$lang['user_settings_new_pass'] = 'Enter new password';
$lang['user_settings_new_pass_confirm'] = 'Re-enter new passwort';
$lang['user_settings_enter_current_pass'] = 'Enter your current password';
$lang['user_settings_error_logout_all_devices'] = 'An error occured while logging out on all devices.';
$lang['user_settings_current_sessions'] = 'Current Sessions';
$lang['user_settings_current_session'] = 'Current Session';
$lang['user_settings_current_sessions_logout_all'] = 'Logout on all devices';
$lang['user_settings_2fa'] = '2-Step Verification authentication';
$lang['user_settings_2fa_deactivate'] = 'Disable 2-Step Verification authentication';
$lang['user_settings_2fa_deactivate_success'] = '2-Step Verification authentication was successfully disabled.';
$lang['user_settings_2fa_deactivate_fail'] = 'An error occured while disabling 2-Step Verification authentication.';
$lang['user_settings_2fa_deactivate_confirm'] = 'Are you sure you want to disable 2-Step Verification authentication?';
$lang['user_settings_2fa_activate'] = 'Enable 2-Step Verification Authentication';
$lang['user_settings_2fa_activate_success'] = '2-Step Verification authentication was enabled successfully.';
$lang['user_settings_2fa_activate_fail'] = 'An error occured while enabling 2-Step Verification authentication.';
$lang['user_settings_2fa_activate_wrong_code'] = 'Wrong code.';
$lang['user_settings_2fa_activate_import_code'] = 'Import this info into your Google Authenticator client (or other TOTP client) using the provided QR code below or by entering the code manually.';
$lang['user_settings_2fa_key'] = 'Secret';
$lang['user_settings_2fa_confirm_code'] = 'After a one-time verification, 2-Step Verification will be activated for this account.';
$lang['user_settings_2fa_enter_code'] = 'Enter Secret...';
$lang['user_settings_2fa_test'] = 'Verify';
$lang['user_settings_settings_success'] = 'The user-settings were saved successfully.';
$lang['user_settings_settings_fail'] = 'An error occured while saving user-settings.';
$lang['user_settings_settings_pass'] = 'Update Password';
$lang['user_settings_last_login'] = 'Last Login';
$lang['user_settings_show_current_sessions'] = 'Show Current Sessions';
$lang['user_settings_log_level'] = 'Log-Level';
$lang['user_settings_log_level_1'] = 'Important Only';
$lang['user_settings_log_level_2'] = 'System-Messages';
$lang['user_settings_log_level_3'] = 'Edits';
$lang['user_settings_log_level_4'] = 'General Messages';
$lang['user_settings_log_level_fail'] = 'An error occured while saving Log-Levels.';
$lang['user_settings_log_level_success'] = 'The Log-Levels were saved successfully.';

//Mail
$lang['mail_write'] = 'Write Email';
$lang['mail_success'] = 'The Email to "%1$s" was sent successfully';
$lang['mail_write_to'] = 'Write an Email to %1$s';
$lang['mail_fail'] = 'An error occured while sending the Email.';
$lang['mail_subject'] = 'Subject';
$lang['mail_message'] = 'Message';
$lang['mail_send'] = 'Send Email';

//Action
$lang['action_backup_fail'] = 'An error occured while creating a backup.';
$lang['action_edit_content'] = 'Edit Page';
$lang['action_construction_message_success'] = 'The construction-message was edited successfully.';
$lang['action_try_again_later'] = 'Error. Please try again later.';
$lang['action_construction_message_edit'] = 'Edit Construction Message';
$lang['action_construction_success'] = 'The website was set to construction mode successfully.';
$lang['action_construction_confirm'] = 'Are you sure you want to enable construction mode?';
$lang['action_construction_removed_success'] = 'Construction Mode was successfully disabled.';
$lang['action_construction_remove'] = 'Are you sure you want to disable construction mode?';
$lang['action_change_page_title_success'] = 'Page Title was successfully edited.';


//Update
$lang['update_title'] = 'Update';
$lang['update_showChangelog'] = 'Show Changelog';
$lang['update_item_succss'] = '"%1$s" was successfully updated.';
$lang['update_item_fail'] = 'An error occured while updating "%1$s"';
$lang['update_succss'] = 'Mowie CMS was updated successfully.';
$lang['update_app_succss'] = '"%1$s" was updated successfully.';
$lang['update_fail'] = 'An error occured while updating.';
$lang['update_config_fail'] = 'An error occured while updating the config file.';
$lang['update_fail_unzip'] = 'An error occured while unpacking the update.';
$lang['update_md5_fake'] = 'The downloaded file has a wrong checksum.';
$lang['update_fail_copy'] = 'An error occured while downloading the update. <b>Hint:</b> The webserver needs writing permissions in the folder /admin!';
$lang['update_version'] = 'Version';
$lang['update_version_current'] = 'Installed Version';
$lang['update_new_version'] = 'New Version Available!';
$lang['update_version_current_new'] = 'Installed Version is up-to-date';
$lang['update_app_update_available'] = 'App-Update - New Version for "%1$s" available: %2$s';
$lang['update_log'] = '{user} updated the System.';