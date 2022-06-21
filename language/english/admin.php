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
\define('_AM_WGEVENTS_THEREARE_REGISTRATIONHISTS', "There are <span class='bold'>%s</span> historic registrations in the database");
\define('_AM_WGEVENTS_THEREARE_QUESTIONS', "There are <span class='bold'>%s</span> questions in the database");
\define('_AM_WGEVENTS_THEREARE_FIELDS', "There are <span class='bold'>%s</span> types of questions in the database");
\define('_AM_WGEVENTS_THEREARE_ANSWERS', "There are <span class='bold'>%s</span> answers in the database");
\define('_AM_WGEVENTS_THEREARE_ANSWERHISTS', "There are <span class='bold'>%s</span> historic answers in the database");
\define('_AM_WGEVENTS_THEREARE_TEXTBLOCKS', "There are <span class='bold'>%s</span> textblocks in the database");
\define('_AM_WGEVENTS_THEREARE_LOGS', "There are <span class='bold'>%s</span> logs in the database");
\define('_AM_WGEVENTS_THEREARE_ACCOUNTS', "There are <span class='bold'>%s</span> email accounts in the Database");
// ---------------- Admin Files ----------------
// There aren't
\define('_AM_WGEVENTS_THEREARENT_EVENTS', "There aren't events");
\define('_AM_WGEVENTS_THEREARENT_CATEGORIES', "There aren't categories");
\define('_AM_WGEVENTS_THEREARENT_REGISTRATIONS', "There aren't registrations");
\define('_AM_WGEVENTS_THEREARENT_REGISTRATIONHISTS', "There aren't historic registrations");
\define('_AM_WGEVENTS_THEREARENT_QUESTIONS', "There aren't questions");
\define('_AM_WGEVENTS_THEREARENT_ANSWERS', "There aren't answers");
\define('_AM_WGEVENTS_THEREARENT_ANSWERHISTS', "There aren't historic answers");
\define('_AM_WGEVENTS_THEREARENT_TEXTBLOCKS', "There aren't textblocks");
\define('_AM_WGEVENTS_THEREARENT_FIELDS', "There aren't fields");
\define('_AM_WGEVENTS_THEREARENT_LOGS', "There aren't logs");
\define('_AM_WGEVENTS_THEREARENT_ACCOUNTS', 'There are no email accounts in the Database');
\define('_AM_WGEVENTS_THEREARENT_ACCOUNTS_DESC', 'There are no primary email accounts in the Database. The default email settings of XOOPS Core will be used for sending mail notifications.');
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
\define('_AM_WGEVENTS_ADD_QUESTION', 'Add New Question');
\define('_AM_WGEVENTS_ADD_ANSWER', 'Add New Answer');
\define('_AM_WGEVENTS_ADD_TEXTBLOCK', 'Add New Textblock');
\define('_AM_WGEVENTS_ADD_FIELD', 'Add New Question Type');
\define('_AM_WGEVENTS_GOTO_FORMSELECT', 'Back to form select');
// Lists
\define('_AM_WGEVENTS_LIST_EVENTS', 'List of Event');
\define('_AM_WGEVENTS_LIST_CATEGORIES', 'List of Category');
\define('_AM_WGEVENTS_LIST_REGISTRATIONS', 'List of Registration');
\define('_AM_WGEVENTS_LIST_QUESTIONS', 'List of Question');
\define('_AM_WGEVENTS_LIST_ANSWERS', 'List of Answer');
\define('_AM_WGEVENTS_LIST_TEXTBLOCKS', 'List of Textblock');
\define('_AM_WGEVENTS_LIST_FIELDS', 'List of Question Type');
\define('_AM_WGEVENTS_LIST_EVENTS_LAST', 'List of last %s Event');
// ---------------- Admin Classes ----------------
\define('_MA_WGEVENTS_HIST_ID', 'History Id');
\define('_MA_WGEVENTS_HIST_INFO', 'Info');
\define('_MA_WGEVENTS_HIST_DATECREATED', 'History date created');
\define('_MA_WGEVENTS_HIST_SUBMITTER', 'History submitter');
\define('_MA_WGEVENTS_REGISTRATIONHISTS_CURR', 'Historic registration currently');
// Category add/edit
\define('_AM_WGEVENTS_CATEGORY_ADD', 'Add Category');
\define('_AM_WGEVENTS_CATEGORY_EDIT', 'Edit Category');
// Elements of Category
\define('_AM_WGEVENTS_CATEGORY_ID', 'Id');
\define('_AM_WGEVENTS_CATEGORY_IDENTIFIER', 'Prefix Unique Identifier');
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
\define('_AM_WGEVENTS_FIELD_ADD', 'Add Question Type');
\define('_AM_WGEVENTS_FIELD_EDIT', 'Edit Question Type');
// Elements of Addtype
\define('_AM_WGEVENTS_FIELD_ID', 'Id');
\define('_AM_WGEVENTS_FIELD_CAPTION', 'Caption');
\define('_AM_WGEVENTS_FIELD_TYPE', 'Type');
\define('_AM_WGEVENTS_FIELD_DESC', 'Description');
\define('_AM_WGEVENTS_FIELD_VALUE', 'Value');
\define('_AM_WGEVENTS_FIELD_PLACEHOLDER', 'Placeholder');
\define('_AM_WGEVENTS_FIELD_REQUIRED', 'Required');
\define('_AM_WGEVENTS_FIELD_DEFAULT', 'Default');
\define('_AM_WGEVENTS_FIELD_DISPLAY_DESC', "Display field 'Description'");
\define('_AM_WGEVENTS_FIELD_DISPLAY_VALUES', "Display field 'Value'");
\define('_AM_WGEVENTS_FIELD_DISPLAY_PLACEHOLDER', "Display field 'Placeholder'");
// Elements of default Addtype
\define('_AM_WGEVENTS_FIELD_CREATE_DEFAULT', 'Create default set');
\define('_AM_WGEVENTS_FIELD_SURE_DELETE', 'Pay attention! All exising question types will be delete! Are you sure to delete?');
\define('_AM_WGEVENTS_FIELD_ADDRESS', 'Address');
\define('_AM_WGEVENTS_FIELD_ADDRESS_VALUE', 'Please Enter Address');
\define('_AM_WGEVENTS_FIELD_AGE', 'Age');
\define('_AM_WGEVENTS_FIELD_AGE_VALUE', 'Please Enter Your Age');
\define('_AM_WGEVENTS_FIELD_BIRTHDAY', 'Birthday');
\define('_AM_WGEVENTS_FIELD_BIRTHDAY_VALUE', 'Please Enter Birthday');
\define('_AM_WGEVENTS_FIELD_PHONE', 'Phone');
\define('_AM_WGEVENTS_FIELD_PHONE_VALUE', 'Please Enter phone number');
\define('_AM_WGEVENTS_FIELD_POSTAL', 'Postal code');
\define('_AM_WGEVENTS_FIELD_POSTAL_VALUE', 'Please Enter Postal code');
\define('_AM_WGEVENTS_FIELD_CITY', 'City');
\define('_AM_WGEVENTS_FIELD_CITY_VALUE', 'Please Enter City');
\define('_AM_WGEVENTS_FIELD_COUNTRY', 'Country');
\define('_AM_WGEVENTS_FIELD_COUNTRY_VALUE', 'Please Enter Country');
// Log add/edit
\define('_AM_WGEVENTS_ADD_LOG', 'Add Log');
\define('_AM_WGEVENTS_EDIT_LOG', 'Edit Log');
\define('_AM_WGEVENTS_LIST_LOGS', 'List Log');
\define('_AM_WGEVENTS_DELETE_LOGS', 'Delete all Log');
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
\define('_AM_WGEVENTS_ACCOUNT_PRIMARY', 'Primary email account');
\define('_AM_WGEVENTS_ACCOUNT_ERROR_OPEN_MAILBOX', 'Error open mailbox! Please check your settings!');
\define('_AM_WGEVENTS_SAVE_AND_CHECK', 'Save and check settings');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_OK', 'successful  ');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_FAILED', 'failed  ');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_SKIPPED', 'skipped');
\define('_AM_WGEVENTS_ACCOUNT_CHECK', 'Check');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_RESULT', 'Check result');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_INFO', 'Question info');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_OPEN_MAILBOX', 'Open mailbox ');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_LIST_FOLDERS', 'Read folder list');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_SENDTEST', 'Send test mail');
// maintenance
\define('_AM_WGEVENTS_MAINTENANCE_TYP', 'Type of maintenance');
\define('_AM_WGEVENTS_MAINTENANCE_DESC', 'Description of maintenance');
\define('_AM_WGEVENTS_MAINTENANCE_RESULTS', 'Maintenance results');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_QUE', 'Check table questions');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_QUE_DESC', 'Check the table questions and search for questions without link to a valid event');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_ANSWERS', 'Check table answers');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_ANSWERS_DESC', 'Check the table answers and search for answers without link to a valid question');
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
// ---------------- Admin Permission ----------------
// Permission for forms (categories)
\define('_AM_WGEVENTS_PERMISSIONS_APPROVE', 'Permission to approve');
\define('_AM_WGEVENTS_PERMISSIONS_SUBMIT', 'Permission to submit');
\define('_AM_WGEVENTS_PERMISSIONS_VIEW', 'Permission to view');
// Permission for tab permissions
\define('_AM_WGEVENTS_PERMISSIONS_DESC', 'Permission you have to read as following:<br>');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL', 'Permission global');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_DESC', '1) Global permissions should only be used for webmasters<br>');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_VIEW', 'Permission global to view');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_SUBMIT', 'Permission global to submit');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_APPROVE', 'Permission global to approve');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE', 'Permission to admin events');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_DESC', '2) Permission to admin events includes:
<ul>
<li>Permission to approve all events</li>
<li>Permission to edit all events</li>
<li>Permission to admin questions for registrations for all events</li>
<li>Permission to admin registrations for all events</li>
</ul>
');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_AUTO', 'Permission to create approved events');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_AUTO_DESC', '3) Permission to create approved events: Event created by this group are automatically approved<br>');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_SUBMIT', 'Permission to submit events');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_SUBMIT_DESC', '4) Permission to submit events includes:
<ul>
<li>Permission to submit events</li>
<li>Permission to edit own events</li>
<li>Permission to admin questions for registrations for own events</li>
<li>Permission to admin registrations for own events</li>
<li>Permission to create and admin own textblocks</li>
</ul>
');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_VIEW', 'Permission to view events');
//\define('_AM_WGEVENTS_PERMISSIONS_QUESTIONS_VIEW', 'Permission to view questions');
//\define('_AM_WGEVENTS_PERMISSIONS_QUESTIONS_SUBMIT', 'Permission to submit questions');
//\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_VIEW', 'Permission to view registrations');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_AUTOVERIF', 'Permission to submit registrations without question verification');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_AUTOVERIF_DESC', '5) Permission to submit registrations without question verification: Registration, sent by this group, will be automatically verified. There is no question verification by mail necessary<br>');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_SUBMIT', 'Permission to submit registrations');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_SUBMIT_DESC', '6)Permission to submit registrations: Registration, sent by this group, must be verified with a link, which is sent by mail<br>');
//\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_APPROVE', 'Permission to approve registrations');
//\define('_AM_WGEVENTS_PERMISSIONS_TEXTBLOCKS_VIEW', 'Permission to view textblocks');
//\define('_AM_WGEVENTS_PERMISSIONS_TEXTBLOCKS_SUBMIT', 'Permission to submit textblocks');
/*
\define('_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_VIEW', 'Permission to view events of this category');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_SUBMIT', 'Permission to submit events of this category');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_APPROVE', 'Permission to approve events of this category');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_REGS_VIEW', 'Permission to view registrations of events of this category');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_REGS_SUBMIT', 'Permission to submit registrations for events of this category');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_REGS_APPROVE', 'Permission to approve registrations of events of this category');
*/
\define('_AM_WGEVENTS_NO_PERMISSIONS_SET', 'No permission set');
//Change yes/no
\define('_AM_WGEVENTS_SETON', 'OFF, change to ON');
\define('_AM_WGEVENTS_SETOFF', 'ON, change to OFF');
//tablesorter
\define('_AM_WGEVENTS_TABLESORTER_SHOW_ALL', 'Show all');
\define('_AM_WGEVENTS_TABLESORTER_OF', 'of');
\define('_AM_WGEVENTS_TABLESORTER_TOTALROWS', 'total rows');
// ---------------- Admin Others ----------------
\define('_AM_WGEVENTS_ABOUT_MAKE_DONATION', 'Submit');
\define('_AM_WGEVENTS_SUPPORT_FORUM', 'Support Forum');
\define('_AM_WGEVENTS_DONATION_AMOUNT', 'Donation Amount');
\define('_AM_WGEVENTS_MAINTAINEDBY', ' is maintained by ');
// ---------------- End ----------------
