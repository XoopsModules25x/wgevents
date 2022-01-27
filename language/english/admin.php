<?php declare(strict_types=1);

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * wgEvents module for xoops
 *
 * @copyright    2021 XOOPS Project (https://xoops.org)
 * @license      GPL 2.0 or later
 * @package      wgevents
 * @since        1.0.0
 * @min_xoops    2.5.11 Beta1
 * @author       Goffy - Wedega - Email:webmaster@wedega.com - Website:https://xoops.wedega.com
 */

require_once __DIR__ . '/common.php';
require_once __DIR__ . '/main.php';

// ---------------- Admin Index ----------------
\define('_AM_WGEVENTS_STATISTICS', 'Statistics');
// There are
\define('_AM_WGEVENTS_THEREARE_EVENTS', "There are <span class='bold'>%s</span> events in the database");
\define('_AM_WGEVENTS_THEREARE_CATEGORIES', "There are <span class='bold'>%s</span> categories in the database");
\define('_AM_WGEVENTS_THEREARE_REGISTRATIONS', "There are <span class='bold'>%s</span> registrations in the database");
\define('_AM_WGEVENTS_THEREARE_ADDITIONALS', "There are <span class='bold'>%s</span> additionals in the database");
\define('_AM_WGEVENTS_THEREARE_ADDTYPES', "There are <span class='bold'>%s</span> types of additionals in the database");
\define('_AM_WGEVENTS_THEREARE_ANSWERS', "There are <span class='bold'>%s</span> answers in the database");
\define('_AM_WGEVENTS_THEREARE_TEXTBLOCKS', "There are <span class='bold'>%s</span> textblocks in the database");
\define('_AM_WGEVENTS_THEREARE_LOGS', "There are <span class='bold'>%s</span> logs in the database");
\define('_AM_WGEVENTS_THEREARE_ACCOUNTS', "There are <span class='bold'>%s</span> Email accounts in the Database");
// ---------------- Admin Files ----------------
// There aren't
\define('_AM_WGEVENTS_THEREARENT_EVENTS', "There aren't events");
\define('_AM_WGEVENTS_THEREARENT_CATEGORIES', "There aren't categories");
\define('_AM_WGEVENTS_THEREARENT_REGISTRATIONS', "There aren't registrations");
\define('_AM_WGEVENTS_THEREARENT_ADDITIONALS', "There aren't additionals");
\define('_AM_WGEVENTS_THEREARENT_ANSWERS', "There aren't answers");
\define('_AM_WGEVENTS_THEREARENT_TEXTBLOCKS', "There aren't textblocks");
\define('_AM_WGEVENTS_THEREARENT_ADDTYPES', "There aren't addtypes");
\define('_AM_WGEVENTS_THEREARENT_LOGS', "There aren't logs");
\define('_AM_WGEVENTS_THEREARENT_ACCOUNTS', 'There are no Email accounts in the Database');
// timezones
\define('_AM_WGEVENTS_TIMEZONES', 'Timezone settings');
// There are
\define('_AM_WGEVENTS_TIMEZONE_PHP', 'PHP default timezone: %s');
\define('_AM_WGEVENTS_TIMEZONE_SERVER', 'XOOPS server timezone: %s');
\define('_AM_WGEVENTS_TIMEZONE_DEFAULT', 'XOOPS default timezone: %s');
\define('_AM_WGEVENTS_TIMEZONE_USER', "Timezone of current user '%s': %s");
// Buttons
\define('_AM_WGEVENTS_ADD_EVENT', 'Add New Event');
\define('_AM_WGEVENTS_ADD_CATEGORY', 'Add New Category');
\define('_AM_WGEVENTS_ADD_REGISTRATION', 'Add New Registration');
\define('_AM_WGEVENTS_ADD_ADDITIONAL', 'Add New Additional');
\define('_AM_WGEVENTS_ADD_ANSWER', 'Add New Answer');
\define('_AM_WGEVENTS_ADD_TEXTBLOCK', 'Add New Textblock');
\define('_AM_WGEVENTS_ADD_ADDTYPE', 'Add New Additional Type');
\define('_AM_WGEVENTS_GOTO_FORMSELECT', 'Back to form select');
// Lists
\define('_AM_WGEVENTS_LIST_EVENTS', 'List of Events');
\define('_AM_WGEVENTS_LIST_CATEGORIES', 'List of Categories');
\define('_AM_WGEVENTS_LIST_REGISTRATIONS', 'List of Registrations');
\define('_AM_WGEVENTS_LIST_ADDITIONALS', 'List of Additionals');
\define('_AM_WGEVENTS_LIST_ANSWERS', 'List of Answers');
\define('_AM_WGEVENTS_LIST_TEXTBLOCKS', 'List of Textblocks');
\define('_AM_WGEVENTS_LIST_ADDTYPES', 'List of Additional Type');
\define('_AM_WGEVENTS_LIST_EVENTS_LAST', 'List of last %s Events');
// ---------------- Admin Classes ----------------
// Category add/edit
\define('_AM_WGEVENTS_CATEGORY_ADD', 'Add Category');
\define('_AM_WGEVENTS_CATEGORY_EDIT', 'Edit Category');
// Elements of Category
\define('_AM_WGEVENTS_CATEGORY_ID', 'Id');
\define('_AM_WGEVENTS_CATEGORY_PID', 'Parent Category');
\define('_AM_WGEVENTS_CATEGORY_NAME', 'Name');
\define('_AM_WGEVENTS_CATEGORY_DESC', 'Description');
\define('_AM_WGEVENTS_CATEGORY_LOGO', 'Logo');
\define('_AM_WGEVENTS_CATEGORY_LOGO_UPLOADS', 'Logo in %s :');
\define('_AM_WGEVENTS_CATEGORY_COLOR', 'Font Color');
\define('_AM_WGEVENTS_CATEGORY_BORDERCOLOR', 'Border Color');
\define('_AM_WGEVENTS_CATEGORY_BGCOLOR', 'Background Color');
\define('_AM_WGEVENTS_CATEGORY_OTHERCSS', 'Other css-styles');
// Addtype add/edit
\define('_AM_WGEVENTS_ADDTYPE_ADD', 'Add Additional Type');
\define('_AM_WGEVENTS_ADDTYPE_EDIT', 'Edit Additional Type');
// Elements of Addtype
\define('_AM_WGEVENTS_ADDTYPE_ID', 'Id');
\define('_AM_WGEVENTS_ADDTYPE_CAPTION', 'Caption');
\define('_AM_WGEVENTS_ADDTYPE_TYPE', 'Type');
\define('_AM_WGEVENTS_ADDTYPE_DESC', 'Description');
\define('_AM_WGEVENTS_ADDTYPE_VALUE', 'Value');
\define('_AM_WGEVENTS_ADDTYPE_PLACEHOLDER', 'Placeholder');
\define('_AM_WGEVENTS_ADDTYPE_REQUIRED', 'Required');
\define('_AM_WGEVENTS_ADDTYPE_DEFAULT', 'Default');
\define('_AM_WGEVENTS_ADDTYPE_DISPLAY_VALUES', "Display field 'Value'");
\define('_AM_WGEVENTS_ADDTYPE_DISPLAY_PLACEHOLDER', "Display field 'Placeholder'");
// Elements of default Addtype
\define('_AM_WGEVENTS_ADDTYPE_CREATE_DEFAULT', 'Create default set');
\define('_AM_WGEVENTS_ADDTYPE_SURE_DELETE', 'Pay attention! All exising additional types will be delete! Are you sure to delete?');
\define('_AM_WGEVENTS_ADDTYPE_ADDRESS', 'Address');
\define('_AM_WGEVENTS_ADDTYPE_ADDRESS_VALUE', 'Please Enter Address');
\define('_AM_WGEVENTS_ADDTYPE_AGE', 'Age');
\define('_AM_WGEVENTS_ADDTYPE_AGE_VALUE', 'Please Enter Your Age');
\define('_AM_WGEVENTS_ADDTYPE_BIRTHDAY', 'Birthday');
\define('_AM_WGEVENTS_ADDTYPE_BIRTHDAY_VALUE', 'Please Enter Birthday');
\define('_AM_WGEVENTS_ADDTYPE_PHONE', 'Phone');
\define('_AM_WGEVENTS_ADDTYPE_PHONE_VALUE', 'Please Enter phone number');
\define('_AM_WGEVENTS_ADDTYPE_POSTAL', 'Postal code');
\define('_AM_WGEVENTS_ADDTYPE_POSTAL_VALUE', 'Please Enter Postal code');
\define('_AM_WGEVENTS_ADDTYPE_CITY', 'City');
\define('_AM_WGEVENTS_ADDTYPE_CITY_VALUE', 'Please Enter City');
\define('_AM_WGEVENTS_ADDTYPE_COUNTRY', 'Country');
\define('_AM_WGEVENTS_ADDTYPE_COUNTRY_VALUE', 'Please Enter Country');
// Log add/edit
\define('_AM_WGEVENTS_ADD_LOG', 'Add Log');
\define('_AM_WGEVENTS_EDIT_LOG', 'Edit Log');
\define('_AM_WGEVENTS_LIST_LOGS', 'List Logs');
\define('_AM_WGEVENTS_DELETE_LOGS', 'Delete all Logs');
// Elements of Log
\define('_AM_WGEVENTS_LOG_ID', 'Id');
\define('_AM_WGEVENTS_LOG_TEXT', 'Text');
//Buttons
\define('_AM_WGEVENTS_ADD_ACCOUNT', 'Add New Email account');
\define('_AM_WGEVENTS_LIST_ACCOUNTS', 'List Email accounts');
// Types of accounts
\define('_AM_WGEVENTS_ACCOUNT_TYPE_PHPMAIL', 'php mail()');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_PHPSENDMAIL', 'php sendmail()');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_POP3', 'pop before smtp');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_SMTP', 'smtp');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_GMAIL', 'gmail');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_NOTREQUIRED', 'Not required');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_NAME', 'My account name');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_YOURNAME', 'John Doe');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_YOUREMAIL', 'name@yourdomain.com');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_USERNAME', 'username');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_PWD', 'password');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_POP3_SERVER_IN', 'pop3.yourdomain.com');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_POP3_PORT_IN', '110');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_POP3_SERVER_OUT', 'mail.yourdomain.com');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_POP3_PORT_OUT', '25');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_SMTP_SERVER_IN', 'imap.yourdomain.com');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_SMTP_PORT_IN', '143');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_SMTP_SERVER_OUT', 'mail.yourdomain.com');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_SMTP_PORT_OUT', '25');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_GMAIL_USERNAME', 'yourusername@gmail.com');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_GMAIL_SERVER_IN', 'imap.gmail.com');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_GMAIL_PORT_IN', '993');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_SECURETYPE_IN', 'tls');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_GMAIL_SERVER_OUT', 'smtp.gmail.com');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_GMAIL_PORT_OUT', '465');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_SECURETYPE_OUT', 'ssl');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_CHECK', 'Check the settings');
// Elements of accounts
\define('_AM_WGEVENTS_ACCOUNT_ADD', 'Add an Email account');
\define('_AM_WGEVENTS_ACCOUNT_EDIT', 'Edit an Email account');
\define('_AM_WGEVENTS_ACCOUNT_DELETE', 'Delete an Email account');
\define('_AM_WGEVENTS_ACCOUNT_ID', 'ID');
\define('_AM_WGEVENTS_ACCOUNT_TYPE', 'Type');
\define('_AM_WGEVENTS_ACCOUNT_NAME', 'Name');
\define('_AM_WGEVENTS_ACCOUNT_YOURNAME', 'Your name');
\define('_AM_WGEVENTS_ACCOUNT_YOURMAIL', 'Your mail');
\define('_AM_WGEVENTS_ACCOUNT_USERNAME', 'Username');
\define('_AM_WGEVENTS_ACCOUNT_PASSWORD', 'Password');
\define('_AM_WGEVENTS_ACCOUNT_INCOMING', 'Incoming');
\define('_AM_WGEVENTS_ACCOUNT_SERVER_IN', 'Server incoming');
\define('_AM_WGEVENTS_ACCOUNT_PORT_IN', 'Port in');
\define('_AM_WGEVENTS_ACCOUNT_SECURETYPE_IN', 'Secure type in');
\define('_AM_WGEVENTS_ACCOUNT_OUTGOING', 'Outgoing');
\define('_AM_WGEVENTS_ACCOUNT_SERVER_OUT', 'Server outgoing');
\define('_AM_WGEVENTS_ACCOUNT_PORT_OUT', 'Port out');
\define('_AM_WGEVENTS_ACCOUNT_SECURETYPE_OUT', 'Secure type out');
\define('_AM_WGEVENTS_ACCOUNT_DEFAULT', 'Default email account');
\define('_AM_WGEVENTS_ACCOUNT_INBOX', 'Mailbox to check for Bounced emails');
\define('_AM_WGEVENTS_ACCOUNT_ERROR_OPEN_MAILBOX', 'Error open mailbox! Please check your settings!');
\define('_AM_WGEVENTS_SAVE_AND_CHECK', 'Save and check settings');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_OK', 'successful  ');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_FAILED', 'failed  ');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_SKIPPED', 'skipped');
\define('_AM_WGEVENTS_ACCOUNT_CHECK', 'Check');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_RESULT', 'Check result');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_INFO', 'Additional info');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_OPEN_MAILBOX', 'Open mailbox ');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_LIST_FOLDERS', 'Read folder list');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_SENDTEST', 'Send test mail');
// maintenance
\define('_AM_WGEVENTS_MAINTENANCE_TYP', 'Type of maintenance');
\define('_AM_WGEVENTS_MAINTENANCE_DESC', 'Description of maintenance');
\define('_AM_WGEVENTS_MAINTENANCE_RESULTS', 'Maintenance results');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_ADDS', 'Check table additionals');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_ADDS_DESC', 'Check the table additionals and search for additionals without link to a valid event');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_ANSWERS', 'Check table answers');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_ANSWERS_DESC', 'Check the table answers and search for answers without link to a valid additional');
\define('_AM_WGEVENTS_MAINTENANCE_CHECKTABLE_SUCCESS', 'Check table successfully finished');
\define('_AM_WGEVENTS_MAINTENANCE_ANON_DATA', 'Anonymize old registration data');
\define('_AM_WGEVENTS_MAINTENANCE_ANON_DATA_DESC', 'In order to fullfill the opbligations of data protection data of events, which will be not needed anymore, has to be deleted.<br>
By this action following will be done:
<ul>
<li>The data first name, last name and email will be anonymized by *</li>
<li>The answers to the questions in the registration form will be deleted</li>
<li>The data of history of registrations and answers will be deleted (if you have used this feature)</li>
</ul>
Please define, until which date all data should be anonymized.');
\define('_AM_WGEVENTS_MAINTENANCE_ANON_DATA_DATELIMIT', 'Anonymize until inclusive:');
\define('_AM_WGEVENTS_MAINTENANCE_ANON_DATA_SUCCESS', 'Cleaning data successfully finished');
\define('_AM_WGEVENTS_MAINTENANCE_BACK', 'Back to maintenance overview');
// General
\define('_AM_WGEVENTS_FORM_UPLOAD', 'Upload file');
\define('_AM_WGEVENTS_FORM_UPLOAD_NEW', 'Upload new file: ');
\define('_AM_WGEVENTS_FORM_UPLOAD_SIZE', 'Max file size: ');
\define('_AM_WGEVENTS_FORM_UPLOAD_SIZE_MB', 'MB');
\define('_AM_WGEVENTS_FORM_UPLOAD_IMG_WIDTH', 'Max image width: ');
\define('_AM_WGEVENTS_FORM_UPLOAD_IMG_HEIGHT', 'Max image height: ');
\define('_AM_WGEVENTS_FORM_IMAGE_PATH', 'Files in %s :');
\define('_AM_WGEVENTS_FORM_EDIT', 'Modification');
\define('_AM_WGEVENTS_FORM_DELETE', 'Clear');
\define('_AM_WGEVENTS_FORM_SURE_DELETE_ALL', 'Delete all data from: %s');
// Clone feature
\define('_AM_WGEVENTS_CLONE', 'Clone');
\define('_AM_WGEVENTS_CLONE_DSC', 'Cloning a module has never been this easy! Just type in the name you want for it and hit submit button!');
\define('_AM_WGEVENTS_CLONE_TITLE', 'Clone %s');
\define('_AM_WGEVENTS_CLONE_NAME', 'Choose a name for the new module');
\define('_AM_WGEVENTS_CLONE_NAME_DSC', 'Do not use special characters! <br>Do not choose an existing module dirname or database table name!');
\define('_AM_WGEVENTS_CLONE_INVALIDNAME', 'ERROR: Invalid module name, please try another one!');
\define('_AM_WGEVENTS_CLONE_EXISTS', 'ERROR: Module name already taken, please try another one!');
\define('_AM_WGEVENTS_CLONE_CONGRAT', 'Congratulations! %s was sucessfully created!<br>You may want to make changes in language files.');
\define('_AM_WGEVENTS_CLONE_IMAGEFAIL', 'Attention, we failed creating the new module logo. Please consider modifying assets/images/logo_module.png manually!');
\define('_AM_WGEVENTS_CLONE_FAIL', 'Sorry, we failed in creating the new clone. Maybe you need to temporally set write permissions (CHMOD 777) to modules folder and try again.');
// General
// ---------------- Admin Permissions ----------------
// Permissions for forms (categories)
\define('_AM_WGEVENTS_PERMISSIONS_APPROVE', 'Permissions to approve');
\define('_AM_WGEVENTS_PERMISSIONS_SUBMIT', 'Permissions to submit');
\define('_AM_WGEVENTS_PERMISSIONS_VIEW', 'Permissions to view');
// Permissions for tab permissions
\define('_AM_WGEVENTS_PERMISSIONS_DESC', 'Permissions you have to read as following:<br>');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL', 'Permissions global');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_DESC', '1) Global permissions should only be used for webmasters<br>');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_VIEW', 'Permissions global to view');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_SUBMIT', 'Permissions global to submit');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_APPROVE', 'Permissions global to approve');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE', 'Permissions to admin events');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_DESC', '2) Permissions to admin events includes:
<ul>
<li>Permissions to approve all events</li>
<li>Permissions to edit all events</li>
<li>Permissions to admin additionals for registrations for all events</li>
<li>Permissions to admin registrations for all events</li>
</ul>
');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_AUTO', 'Permissions to create approved events');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_AUTO_DESC', '3) Permissions to create approved events: Events created by this group are automatically approved<br>');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_SUBMIT', 'Permissions to submit events');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_SUBMIT_DESC', '4) Permissions to submit events includes:
<ul>
<li>Permissions to submit events</li>
<li>Permissions to edit own events</li>
<li>Permissions to admin additionals for registrations for own events</li>
<li>Permissions to admin registrations for own events</li>
<li>Permissions to create and admin own textblocks</li>
</ul>
');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_VIEW', 'Permissions to view events');


//\define('_AM_WGEVENTS_PERMISSIONS_ADDITIONALS_VIEW', 'Permissions to view additionals');
//\define('_AM_WGEVENTS_PERMISSIONS_ADDITIONALS_SUBMIT', 'Permissions to submit additionals');
//\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_VIEW', 'Permissions to view registrations');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_AUTOVERIF', 'Permissions to submit registrations without additional verification');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_AUTOVERIF_DESC', '5) Permissions to submit registrations without additional verification: Registrations, sent by this group, will be automatically verified. There is no additional verification by mail necessary<br>');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_SUBMIT', 'Permissions to submit registrations');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_SUBMIT_DESC', '6)Permissions to submit registrations: Registrations, sent by this group, must be verified with a link, which is sent by mail<br>');
//\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_APPROVE', 'Permissions to approve registrations');
//\define('_AM_WGEVENTS_PERMISSIONS_TEXTBLOCKS_VIEW', 'Permissions to view textblocks');
//\define('_AM_WGEVENTS_PERMISSIONS_TEXTBLOCKS_SUBMIT', 'Permissions to submit textblocks');
/*
\define('_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_VIEW', 'Permissions to view events of this category');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_SUBMIT', 'Permissions to submit events of this category');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_APPROVE', 'Permissions to approve events of this category');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_REGS_VIEW', 'Permissions to view registrations of events of this category');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_REGS_SUBMIT', 'Permissions to submit registrations for events of this category');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_REGS_APPROVE', 'Permissions to approve registrations of events of this category');
*/
\define('_AM_WGEVENTS_NO_PERMISSIONS_SET', 'No permission set');
//Change yes/no
\define('_AM_WGEVENTS_SETON', 'OFF, change to ON');
\define('_AM_WGEVENTS_SETOFF', 'ON, change to OFF');
// ---------------- Admin Others ----------------
\define('_AM_WGEVENTS_ABOUT_MAKE_DONATION', 'Submit');
\define('_AM_WGEVENTS_SUPPORT_FORUM', 'Support Forum');
\define('_AM_WGEVENTS_DONATION_AMOUNT', 'Donation Amount');
\define('_AM_WGEVENTS_MAINTAINEDBY', ' is maintained by ');
// ---------------- End ----------------
