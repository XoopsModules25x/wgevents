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
 * @author       Goffy - Wedega - Email:webmaster@wedega.com - Website:https://xoops.wedega.com
 */

require_once __DIR__ . '/admin.php';

// ---------------- Main ----------------
\define('_MA_WGEVENTS_INDEX', 'Overview wgEvents');
\define('_MA_WGEVENTS_TITLE', 'wgEvents');
\define('_MA_WGEVENTS_DESC', 'This module is for doing following...');
\define('_MA_WGEVENTS_INDEX_DESC', 'Welcome to the homepage of your new module wgEvents!<br>This description is only visible on the homepage of this module.');
\define('_MA_WGEVENTS_NO_PDF_LIBRARY', 'Libraries TCPDF not there yet, upload them in root/Frameworks');
\define('_MA_WGEVENTS_NO', 'No');
\define('_MA_WGEVENTS_BROKEN', 'Notify broken');
\define('_MA_WGEVENTS_DATECREATED', 'Datecreated');
\define('_MA_WGEVENTS_SUBMITTER', 'Submitter');
\define('_MA_WGEVENTS_WEIGHT', 'Weight');
\define('_MA_WGEVENTS_ACTION', 'Action');
\define('_MA_WGEVENTS_INDEX_THEREARE', 'There are %s Events');
\define('_MA_WGEVENTS_INDEX_THEREARENT_EVENTS', 'There are no events');
\define('_MA_WGEVENTS_INDEX_THEREARENT_EVENTS_FILTER', 'There are no events for current filters');
\define('_MA_WGEVENTS_INDEX_THEREARENT_CATS', 'There are no categories');
\define('_MA_WGEVENTS_INDEX_LATEST_LIST', 'Last Events');
// ---------------- Buttons ----------------
\define('_MA_WGEVENTS_DETAILS', 'Show details');
\define('_MA_WGEVENTS_PRINT', 'Print');
\define('_MA_WGEVENTS_READMORE', 'Read more');
\define('_MA_WGEVENTS_READLESS', 'Read less');
\define('_MA_WGEVENTS_SEND_ALL', 'Send to all');
\define('_MA_WGEVENTS_APPLY_FILTER', 'Apply filter');
\define('_MA_WGEVENTS_SELECT_ALL', '(De)select all');
// Status
\define('_MA_WGEVENTS_STATUS', 'Status');
\define('_MA_WGEVENTS_STATUS_NONE', 'No status');
\define('_MA_WGEVENTS_STATUS_OFFLINE', 'Offline');
\define('_MA_WGEVENTS_STATUS_ONLINE', 'Online');
\define('_MA_WGEVENTS_STATUS_SUBMITTED', 'Submitted');
\define('_MA_WGEVENTS_STATUS_VERIFIED', 'Verified');
\define('_MA_WGEVENTS_STATUS_APPROVED', 'Approved');
\define('_MA_WGEVENTS_STATUS_LOCKED', 'Locked');
\define('_MA_WGEVENTS_STATUS_CANCELED', 'Canceled');
\define('_MA_WGEVENTS_STATUS_PENDING', 'Pending');
\define('_MA_WGEVENTS_STATUS_PROCESSING', 'Processing');
\define('_MA_WGEVENTS_STATUS_DONE', 'Done');
// ---------------- Contents ----------------
// Event
\define('_MA_WGEVENTS_EVENT', 'Event');
\define('_MA_WGEVENTS_EVENT_DETAILS', 'Details of Event');
\define('_MA_WGEVENTS_EVENT_ADD', 'Add Event');
\define('_MA_WGEVENTS_EVENT_EDIT', 'Edit Event');
\define('_MA_WGEVENTS_EVENT_DELETE', 'Delete Event');
\define('_MA_WGEVENTS_EVENT_DELETE_ERR', 'Deleting of event is not possible anymore, as there are already registrations existing!<br>Please delete existing registrations first');
\define('_MA_WGEVENTS_EVENT_CLONE', 'Clone Event');
\define('_MA_WGEVENTS_EVENT_CANCEL', 'Cancel Event');
\define('_MA_WGEVENTS_EVENT_SELECT', 'Select Event');
\define('_MA_WGEVENTS_EVENTS', 'Events');
\define('_MA_WGEVENTS_EVENTS_LIST', 'List of Events');
\define('_MA_WGEVENTS_EVENTS_TITLE', 'Events title');
\define('_MA_WGEVENTS_EVENTS_DESC', 'Events description');
\define('_MA_WGEVENTS_EVENTS_LISTCOMING', 'Coming Events');
\define('_MA_WGEVENTS_EVENTS_LISTPAST', 'Past Events');
\define('_MA_WGEVENTS_EVENTS_EXPORT', 'Export events');
\define('_MA_WGEVENTS_EVENTS_FILTER_NB', 'Number of events');
// Caption of Event
\define('_MA_WGEVENTS_EVENT_ID', 'Id');
\define('_MA_WGEVENTS_EVENT_IDENTIFIER', 'Unique Event Identifier');
\define('_MA_WGEVENTS_EVENT_CATID', 'Primary category');
\define('_MA_WGEVENTS_EVENT_SUBCATS', 'Sub categories');
\define('_MA_WGEVENTS_EVENT_NAME', 'Name');
\define('_MA_WGEVENTS_EVENT_LOGO', 'Logo');
\define('_MA_WGEVENTS_EVENT_LOGO_UPLOADS', 'Logos in your directory:');
\define('_MA_WGEVENTS_EVENT_DESC', 'Description');
\define('_MA_WGEVENTS_EVENT_DATE', 'Date');
\define('_MA_WGEVENTS_EVENT_DATEFROM', 'Date from');
\define('_MA_WGEVENTS_EVENT_DATETO', 'Date to');
\define('_MA_WGEVENTS_EVENT_ALLDAY', 'Allday');
\define('_MA_WGEVENTS_EVENT_TODAY', 'Today');
\define('_MA_WGEVENTS_EVENT_DATEUNTIL', 'until');
\define('_MA_WGEVENTS_EVENT_CONTACT', 'Contact');
\define('_MA_WGEVENTS_EVENT_EMAIL', 'Email');
\define('_MA_WGEVENTS_EVENT_EMAIL_SENDTO', 'Send mail to organizer of event');
\define('_MA_WGEVENTS_EVENT_EMAIL_SENDREQUEST', 'Request for event: ');
\define('_MA_WGEVENTS_EVENT_LOCATION', 'Location');
\define('_MA_WGEVENTS_EVENT_LOCGMLAT', 'Location Latitude');
\define('_MA_WGEVENTS_EVENT_LOCGMLON', 'Location Longitude');
\define('_MA_WGEVENTS_EVENT_LOCGMZOOM', 'Zoom factor');
\define('_MA_WGEVENTS_EVENT_FEE', 'Fee');
\define('_MA_WGEVENTS_EVENT_FEE_VAL_PH', 'Please enter amount');
\define('_MA_WGEVENTS_EVENT_FEE_DESC_PH', 'Enter fee description (e.g. childs 0 - 6 years)');
\define('_MA_WGEVENTS_EVENT_PAYMENTINFO', 'Payment Info');
\define('_MA_WGEVENTS_EVENT_REGISTER_USE', 'Use Registration');
\define('_MA_WGEVENTS_EVENT_REGISTER_FROM', 'Registration from');
\define('_MA_WGEVENTS_EVENT_REGISTER_TO', 'Registration to');
\define('_MA_WGEVENTS_EVENT_REGISTER_MAX', 'Max participant');
\define('_MA_WGEVENTS_EVENT_REGISTER_MAX_DESC', '0 means no limit');
\define('_MA_WGEVENTS_EVENT_REGISTER_MAX_UNLIMITED', 'unlimited');
\define('_MA_WGEVENTS_EVENT_REGISTER_LISTWAIT', 'Use waiting list');
\define('_MA_WGEVENTS_EVENT_REGISTER_LISTWAIT_DESC', 'If you are using this feature then registered person will be put on a waiting list if max number of allowed participants is reached');
\define('_MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT', 'Autoaccept');
\define('_MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT_DESC', 'If you are using this feature then all registrations will be approve automatically. If No you have to confirm separately each registration.');
\define('_MA_WGEVENTS_EVENT_REGISTER_NOTIFY', 'Notify');
\define('_MA_WGEVENTS_EVENT_REGISTER_NOTIFY_DESC', 'Please enter emails, which should be informed about new registrations or updates.&#013;&#010;Use for each email a new line.');
\define('_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL', 'Sender mail address');
\define('_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL_DESC', 'Mail address of sender for confirmation mails');
\define('_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL_ERR', 'In case of registration use the mail address of sender for confirmation mails must be filled in!');
\define('_MA_WGEVENTS_EVENT_REGISTER_SENDERNAME', 'Sender name');
\define('_MA_WGEVENTS_EVENT_REGISTER_SENDERNAME_DESC', 'Shown name of sender for confirmation mails');
\define('_MA_WGEVENTS_EVENT_REGISTER_SIGNATURE', 'Signature');
\define('_MA_WGEVENTS_EVENT_REGISTER_SIGNATURE_DESC', 'Enter the signature, which should be used for confirmation mails');
\define('_MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF', 'Request mail verification');
\define('_MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF_DESC', 'If you choose this option the registrar must enter an email address and he get an email with verification code, which he has to send back.');
\define('_MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF_INFO', 'The organizer request mail verification, therefore the input of an email address is mandatory. You will get an email with verification link which you have to confirm.');
\define('_MA_WGEVENTS_EVENT_GALID', 'Gallery Id');
\define('_MA_WGEVENTS_EVENT_GM_SHOW', 'Show location on map');
\define('_MA_WGEVENTS_EVENT_GM_GETCOORDS', 'Get location coordinates');
\define('_MA_WGEVENTS_EVENT_GM_APPLYCOORDS', 'Apply coordinates');
\define('_MA_WGEVENTS_EVENT_INFORM_MODIF', 'Inform participants');
\define('_MA_WGEVENTS_EVENT_INFORM_MODIF_DESC', 'Should participants be informed about the changes by mail?');
\define('_MA_WGEVENTS_EVENT_URL', 'Website');
\define('_MA_WGEVENTS_EVENT_GROUPS', 'Display for groups');
\define('_MA_WGEVENTS_EVENT_GROUPS_DESC', 'Define which groups should be able to see this event');
\define('_MA_WGEVENTS_EVENT_GROUPS_ALL', 'All groups');
\define('_MA_WGEVENTS_EVENT_CLONE_QUESTION', 'Clone also existing questions');
\define('_MA_WGEVENTS_EVENT_DATE_ERROR1', "The date 'Date to' was smaller than 'Date from' and was adapted therefore!");
\define('_MA_WGEVENTS_EVENT_DATE_ERROR2', "The date 'Registration to' was smaller than 'Registration from' and was adapted therefor!");
// Categories
\define('_MA_WGEVENTS_CATEGORY_LOGO', 'Logo');
\define('_MA_WGEVENTS_CATEGORY_NOEVENTS', 'No events available');
\define('_MA_WGEVENTS_CATEGORY_EVENT', '1 event');
\define('_MA_WGEVENTS_CATEGORY_EVENTS', '%s events');
\define('_MA_WGEVENTS_CATEGORY_FILTER', 'Filter by categories:');
// Registration
\define('_MA_WGEVENTS_REGISTRATION', 'Registration');
\define('_MA_WGEVENTS_REGISTRATION_DETAILS', 'Registration details');
\define('_MA_WGEVENTS_REGISTRATION_ADD', 'Add Registration');
\define('_MA_WGEVENTS_REGISTRATION_EDIT', 'Edit Registration');
\define('_MA_WGEVENTS_REGISTRATION_DELETE', 'Delete Registration');
\define('_MA_WGEVENTS_REGISTRATION_CLONE', 'Clone Registration');
\define('_MA_WGEVENTS_REGISTRATION_GOTO', 'Goto Registration');
\define('_MA_WGEVENTS_REGISTRATIONS', 'Registrations');
\define('_MA_WGEVENTS_REGISTRATIONS_LIST', 'List of Registrations');
\define('_MA_WGEVENTS_REGISTRATIONS_TITLE', 'Registrations title');
\define('_MA_WGEVENTS_REGISTRATIONS_DESC', 'Registrations description');
\define('_MA_WGEVENTS_REGISTRATIONS_MYLIST', 'List of my registrations');
\define('_MA_WGEVENTS_REGISTRATIONS_THEREARENT', 'There are no registrations available for current user respectively for current IP-Address');
\define('_MA_WGEVENTS_REGISTRATIONS_CURR', 'Registration currently');
\define('_MA_WGEVENTS_REGISTRATIONS_NBCURR_0', 'Currently there are no registrations');
\define('_MA_WGEVENTS_REGISTRATIONS_NBCURR', '%s of %s available places already booked');
\define('_MA_WGEVENTS_REGISTRATIONS_NBCURR_INDEX', '%s of %s booked');
\define('_MA_WGEVENTS_REGISTRATIONS_FULL', 'fully booked');
\define('_MA_WGEVENTS_REGISTRATIONS_FULL_LISTWAIT', 'Registration on waiting list possible');
\define('_MA_WGEVENTS_REGISTRATION_TOEARLY', 'Excuse me, but registration is possilbe from %s');
\define('_MA_WGEVENTS_REGISTRATION_TOLATE', 'Excuse me, but registration is not allowed anymore since %s');
// Caption of Registration
\define('_MA_WGEVENTS_REGISTRATION_ID', 'Id');
\define('_MA_WGEVENTS_REGISTRATION_EVID', 'Event');
\define('_MA_WGEVENTS_REGISTRATION_SALUTATION', 'Salutation');
\define('_MA_WGEVENTS_REGISTRATION_SALUTATION_MEN', 'Mister');
\define('_MA_WGEVENTS_REGISTRATION_SALUTATION_WOMEN', 'Miss');
\define('_MA_WGEVENTS_REGISTRATION_FIRSTNAME', 'Firstname');
\define('_MA_WGEVENTS_REGISTRATION_FIRSTNAME_PLACEHOLDER', 'Enter Firstname');
\define('_MA_WGEVENTS_REGISTRATION_LASTNAME', 'Lastname');
\define('_MA_WGEVENTS_REGISTRATION_LASTNAME_PLACEHOLDER', 'Enter Lastname');
\define('_MA_WGEVENTS_REGISTRATION_EMAIL', 'Email');
\define('_MA_WGEVENTS_REGISTRATION_EMAIL_PLACEHOLDER', 'Enter Email Address');
\define('_MA_WGEVENTS_REGISTRATION_EMAIL_CONFIRM', 'Send confirmation mail or notifications about changes?');
\define('_MA_WGEVENTS_REGISTRATION_GDPR', 'General data protection');
\define('_MA_WGEVENTS_REGISTRATION_GDPR_VALUE', 'The data will be stored only for managing the event.
The data will be not forwarded to other person.
The data will be deleted half a year after the event.
During the event we will take pictures, which can be used in public media.
Participation without agreement to this conditions is not possible.
');
\define('_MA_WGEVENTS_REGISTRATION_IP', 'Ip');
\define('_MA_WGEVENTS_REGISTRATION_FINANCIAL', 'Financial state');
\define('_MA_WGEVENTS_REGISTRATION_FINANCIAL_UNPAID', 'Unpaid');
\define('_MA_WGEVENTS_REGISTRATION_FINANCIAL_PAID', 'Paid');
\define('_MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_0','Change status to unpaid');
\define('_MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_1','Change status to paid');
\define('_MA_WGEVENTS_REGISTRATION_PAIDAMOUNT', 'Paid amount');
\define('_MA_WGEVENTS_REGISTRATION_LISTWAIT', 'Waiting list');
\define('_MA_WGEVENTS_REGISTRATION_LISTWAIT_TAKEOVER', 'Take over from waiting list');
\define('_MA_WGEVENTS_REGISTRATION_LISTWAIT_Y', 'On waiting list');
\define('_MA_WGEVENTS_REGISTRATION_LISTWAIT_N', 'On final list of participants');
\define('_MA_WGEVENTS_REGISTRATION_VERIFKEY', 'Verification key');
\define('_MA_WGEVENTS_REGISTRATION_CONFIRM', 'Confirm participation');
\define('_MA_WGEVENTS_REGISTRATION_CHANGED','Registration data successfully changed');
\define('_MA_WGEVENTS_REGISTRATION_DATECREATED','Registration date');
// Question
\define('_MA_WGEVENTS_QUESTION', 'Question');
\define('_MA_WGEVENTS_QUESTION_ADD', 'Add Question');
\define('_MA_WGEVENTS_QUESTION_EDIT', 'Edit Question');
\define('_MA_WGEVENTS_QUESTION_DELETE', 'Delete Question');
\define('_MA_WGEVENTS_QUESTION_CLONE', 'Clone Question');
\define('_MA_WGEVENTS_QUESTIONS', 'Questions');
\define('_MA_WGEVENTS_QUESTIONS_LIST', 'List of Questions');
\define('_MA_WGEVENTS_QUESTIONS_TITLE', 'Questions title');
\define('_MA_WGEVENTS_QUESTIONS_DESC', 'Questions description');
\define('_MA_WGEVENTS_QUESTIONS_CREATE', 'Create Questions');
\define('_MA_WGEVENTS_QUESTIONS_PREVIEW', 'Show preview of registration form');
\define('_MA_WGEVENTS_QUESTIONS_CURR', 'Number of current Questions');
// Caption of Question
\define('_MA_WGEVENTS_QUESTION_ID', 'Id');
\define('_MA_WGEVENTS_QUESTION_EVID', 'Event');
\define('_MA_WGEVENTS_QUESTION_TYPE', 'Type');
\define('_MA_WGEVENTS_QUESTION_CAPTION', 'Caption');
\define('_MA_WGEVENTS_QUESTION_CAPTION_DESC', 'The caption will be shown as field caption and used also as column header in output lists');
\define('_MA_WGEVENTS_QUESTION_DESC', 'Description');
\define('_MA_WGEVENTS_QUESTION_DESC_DESC', 'The description will be shown beside the fields as question information');
\define('_MA_WGEVENTS_QUESTION_VALUE', 'Values');
\define('_MA_WGEVENTS_QUESTION_PLACEHOLDER', 'Placeholder');
\define('_MA_WGEVENTS_QUESTION_PLACEHOLDER_DESC', 'Placeholder will be shown as info in textboxes');
\define('_MA_WGEVENTS_QUESTION_VALUE_DESC', 'Please enter allowed values for selected field type. For radio or selectbox use new line for each options');
\define('_MA_WGEVENTS_QUESTION_REQUIRED', 'Required Value');
\define('_MA_WGEVENTS_QUESTION_REQUIRED_DESC', 'If you set Required to yes, then user must fill in this field');
\define('_MA_WGEVENTS_QUESTION_PRINT', 'Print');
\define('_MA_WGEVENTS_QUESTION_PRINT_DESC', 'Show/print this field in lists?<br>No means, that this field will be shown only in registration form.');
// Answer
\define('_MA_WGEVENTS_ANSWER', 'Answer');
\define('_MA_WGEVENTS_ANSWER_ADD', 'Add Answer');
\define('_MA_WGEVENTS_ANSWER_EDIT', 'Edit Answer');
\define('_MA_WGEVENTS_ANSWER_DELETE', 'Delete Answer');
\define('_MA_WGEVENTS_ANSWER_CLONE', 'Clone Answer');
\define('_MA_WGEVENTS_ANSWERS', 'Answers');
\define('_MA_WGEVENTS_ANSWERS_LIST', 'List of Answers');
\define('_MA_WGEVENTS_ANSWERS_TITLE', 'Answers title');
\define('_MA_WGEVENTS_ANSWERS_DESC', 'Answers description');
\define('_MA_WGEVENTS_ANSWERS_CURR', 'Number of current answers');
// Caption of Answer
\define('_MA_WGEVENTS_ANSWER_ID', 'Id');
\define('_MA_WGEVENTS_ANSWER_EVID', 'Event Id');
\define('_MA_WGEVENTS_ANSWER_REGID', 'Registration Id');
\define('_MA_WGEVENTS_ANSWER_QUEID', 'Question Id');
\define('_MA_WGEVENTS_ANSWER_TEXT', 'Text');
// Textblock
\define('_MA_WGEVENTS_TEXTBLOCK', 'Textblock');
\define('_MA_WGEVENTS_TEXTBLOCK_ADD', 'Add Textblock');
\define('_MA_WGEVENTS_TEXTBLOCK_EDIT', 'Edit Textblock');
\define('_MA_WGEVENTS_TEXTBLOCK_DELETE', 'Delete Textblock');
\define('_MA_WGEVENTS_TEXTBLOCK_CLONE', 'Clone Textblock');
\define('_MA_WGEVENTS_TEXTBLOCKS', 'Textblocks');
\define('_MA_WGEVENTS_TEXTBLOCKS_LIST', 'List of Textblocks');
\define('_MA_WGEVENTS_TEXTBLOCKS_TITLE', 'Textblocks title');
\define('_MA_WGEVENTS_TEXTBLOCKS_DESC', 'Textblocks description');
\define('_MA_WGEVENTS_TEXTBLOCKS_THEREARENT', 'There are no textblocks available at the moment');
// Caption of Textblock
\define('_MA_WGEVENTS_TEXTBLOCK_ID', 'Id');
\define('_MA_WGEVENTS_TEXTBLOCK_CATID', 'Category');
\define('_MA_WGEVENTS_TEXTBLOCK_NAME', 'Name');
\define('_MA_WGEVENTS_TEXTBLOCK_TEXT', 'Text');
\define('_MA_WGEVENTS_TEXTBLOCK_CLASS', 'Class');
\define('_MA_WGEVENTS_TEXTBLOCK_CLASS_PRIVATE', 'Private');
\define('_MA_WGEVENTS_TEXTBLOCK_CLASS_PUBLIC', 'Public');
// Elements of Addtype
\define('_MA_WGEVENTS_FIELD_NONE', 'None');
\define('_MA_WGEVENTS_FIELD_LABEL', 'Label');
\define('_MA_WGEVENTS_FIELD_TEXTBOX', 'Textbox');
\define('_MA_WGEVENTS_FIELD_TEXTAREA', 'Multiline Textarea field');
\define('_MA_WGEVENTS_FIELD_SELECTBOX', 'Dropdown');
\define('_MA_WGEVENTS_FIELD_COMBOBOX', 'Combobox');
\define('_MA_WGEVENTS_FIELD_CHECKBOX', 'Checkbox');
\define('_MA_WGEVENTS_FIELD_RADIO', 'Radio');
\define('_MA_WGEVENTS_FIELD_RADIOYN', 'Radio yes/no');
\define('_MA_WGEVENTS_FIELD_DATE', 'Date field');
\define('_MA_WGEVENTS_FIELD_DATETIME', 'Date/Time field');
\define('_MA_WGEVENTS_FIELD_NAME', 'Textbox Name');
\define('_MA_WGEVENTS_FIELD_EMAIL', 'Textbox Email');
\define('_MA_WGEVENTS_FIELD_COUNTRY', 'Dropdown Country');
\define('_MA_WGEVENTS_FIELD_TEXTBLOCK', 'Textblock');
\define('_MA_WGEVENTS_FIELD_TEXTEDITOR', 'Texteditor');
// Submit
\define('_MA_WGEVENTS_SAVE', 'Save');
\define('_MA_WGEVENTS_EXEC', 'Execute');
\define('_MA_WGEVENTS_CONTINUE_QUESTIONY', 'Save and continue with question informations');
\define('_MA_WGEVENTS_GOTO_REGISTRATION', 'Goto registration');
\define('_MA_WGEVENTS_GOTO_QUESTIONS', 'Goto questions');
\define('_MA_WGEVENTS_GOTO_EVENT', 'Goto event');
\define('_MA_WGEVENTS_GOTO_EVENTSLIST', 'Goto eventlist');
\define('_MA_WGEVENTS_OUTPUT_EXCEL', 'Output to Excel');
\define('_MA_WGEVENTS_OUTPUT_ICS', 'Output as ICS');
\define('_MA_WGEVENTS_ERROR_SAVE', 'An error occurred when saving the data');
// Form
\define('_MA_WGEVENTS_FORM_OK', 'Successfully saved');
\define('_MA_WGEVENTS_FORM_DELETE_OK', 'Successfully deleted');
\define('_MA_WGEVENTS_FORM_CANCEL_OK', 'Successfully canceled');
\define('_MA_WGEVENTS_FORM_SURE_DELETE', "Are you sure to delete: <b><span style='color : Red;'>%s </span></b>");
\define('_MA_WGEVENTS_FORM_SURE_RENEW', "Are you sure to update: <b><span style='color : Red;'>%s </span></b>");
\define('_MA_WGEVENTS_INVALID_PARAM', 'Invalid parameter');
\define('_MA_WGEVENTS_CONFIRMDELETE_TITLE', 'Confirm delete data');
\define('_MA_WGEVENTS_CONFIRMDELETE_LABEL', 'Are you sure to delete:');
\define('_MA_WGEVENTS_CONFIRMDELETE_REGISTRATION', "Registration for: <b><span style='color : Red;'>%s</span></b>");
\define('_MA_WGEVENTS_CONFIRMDELETE_TEXTBLOCK', "Textblock: <b><span style='color : Red;'>%s</span></b>");
\define('_MA_WGEVENTS_CONFIRMDELETE_EVENT', "Event: <b><span style='color : Red;'>%s</span></b>");
\define('_MA_WGEVENTS_CONFIRMDELETE_QUESTION', "Question: <b><span style='color : Red;'>%s</span></b>");
\define('_MA_WGEVENTS_CONFIRMCANCEL_TITLE', 'Cancel event');
\define('_MA_WGEVENTS_CONFIRMCANCEL_LABEL', 'Du you really want to cancel:');
\define('_MA_WGEVENTS_CONFIRMCANCEL_EVENT', "Attention: also all registrations will be canceled automatically! Do youi really want to cancel <b><span style='color : Red;'>%s</span></b> finally?");
// From Contact
\define('_MA_WGEVENTS_CONTACT_ALL', 'Contact all participants');
\define('_MA_WGEVENTS_CONTACT_MAILFROM', 'Sender');
\define('_MA_WGEVENTS_CONTACT_MAILTO', 'Recipients');
\define('_MA_WGEVENTS_CONTACT_MAILCOPY', 'Send copy to me');
\define('_MA_WGEVENTS_CONTACT_MAILSUBJECT', 'Subject');
\define('_MA_WGEVENTS_CONTACT_ALL_MAILSUBJECT_TEXT', 'Information for: %s');
\define('_MA_WGEVENTS_CONTACT_MAILBODY', 'Notification text');
\define('_MA_WGEVENTS_CONTACT_ALL_SUCCESS', 'Sending mail to %s participants successful');
\define('_MA_WGEVENTS_CONTACT_ALL_PENDING', 'Sending mail to %s participants are pending and will be sent soon');
\define('_MA_WGEVENTS_CONTACT_ALL_ERROR', 'Unfortunately an error occurred during sending mail to all participants');
\define('_MA_WGEVENTS_CONTACT_ALL_TEST', 'Sending test mail');
\define('_MA_WGEVENTS_CONTACT_ALL_TEST_BTN', 'Sending mail only to current sender for testing');
\define('_MA_WGEVENTS_CONTACT_ALL_TEST_SUCCESS', 'Sending test mail successful');
// calendar
\define('_MA_WGEVENTS_CAL_ITEMS', 'Items Calendar');
\define('_MA_WGEVENTS_CAL_EDITITEM', 'Edit Item');
\define('_MA_WGEVENTS_CAL_ADDITEM', 'Add Item');
//navbar
\define('_MA_WGEVENTS_CAL_PREVMONTH', 'Previous Month');
\define('_MA_WGEVENTS_CAL_NEXTMONTH', 'Next Month');
\define('_MA_WGEVENTS_CAL_PREVYEAR', 'Previous Year');
\define('_MA_WGEVENTS_CAL_NEXTYEAR', 'Next Year');
\define('_MA_WGEVENTS_CAL_GOTO', 'Switch month/year');
\define('_MA_WGEVENTS_CAL_SHOW', 'Show');
//days
\define('_MA_WGEVENTS_CAL_MIN_SUNDAY', 'Sun');
\define('_MA_WGEVENTS_CAL_MIN_MONDAY', 'Mon');
\define('_MA_WGEVENTS_CAL_MIN_TUESDAY', 'Tue');
\define('_MA_WGEVENTS_CAL_MIN_WEDNESDAY', 'Wed');
\define('_MA_WGEVENTS_CAL_MIN_THURSDAY', 'Thu');
\define('_MA_WGEVENTS_CAL_MIN_FRIDAY', 'Fri');
\define('_MA_WGEVENTS_CAL_MIN_SATURDAY', 'Sat');
\define('_MA_WGEVENTS_CAL_SUNDAY', 'Sunday');
\define('_MA_WGEVENTS_CAL_MONDAY', 'Monday');
\define('_MA_WGEVENTS_CAL_TUESDAY', 'Tuesday');
\define('_MA_WGEVENTS_CAL_WEDNESDAY', 'Wednesday');
\define('_MA_WGEVENTS_CAL_THURSDAY', 'Thursday');
\define('_MA_WGEVENTS_CAL_FRIDAY', 'Friday');
\define('_MA_WGEVENTS_CAL_SATURDAY', 'Saturday');
\define('_MA_WGEVENTS_CAL_JANUARY', 'January');
\define('_MA_WGEVENTS_CAL_FEBRUARY', 'February');
\define('_MA_WGEVENTS_CAL_MARCH', 'March');
\define('_MA_WGEVENTS_CAL_APRIL', 'April');
\define('_MA_WGEVENTS_CAL_MAY', 'May');
\define('_MA_WGEVENTS_CAL_JUNE', 'June');
\define('_MA_WGEVENTS_CAL_JULY', 'July');
\define('_MA_WGEVENTS_CAL_AUGUST', 'August');
\define('_MA_WGEVENTS_CAL_SEPTEMBER', 'September');
\define('_MA_WGEVENTS_CAL_OCTOBER', 'October');
\define('_MA_WGEVENTS_CAL_NOVEMBER', 'November');
\define('_MA_WGEVENTS_CAL_DECEMBER', 'December');
// mail confirmation/notification
\define('_MA_WGEVENTS_MAIL_REG_IN_SUBJECT', 'Notification about registration');
\define('_MA_WGEVENTS_MAIL_REG_OUT_SUBJECT', 'Notification about deregistration');
\define('_MA_WGEVENTS_MAIL_REG_MODIFY_SUBJECT', 'Notification about registration modification');
\define('_MA_WGEVENTS_MAIL_REG_MODIFICATION', 'Modification %s: "%s" to "%s"');
\define('_MA_WGEVENTS_MAIL_REG_MODIFICATION_VAL', 'Modified value');
\define('_MA_WGEVENTS_MAIL_REG_MODIFICATION_FROM', 'from');
\define('_MA_WGEVENTS_MAIL_REG_MODIFICATION_TO', 'to');
\define('_MA_WGEVENTS_MAIL_REG_MODIFICATION_DELETED', 'Value deleted');
\define('_MA_WGEVENTS_MAIL_REG_MODIFICATION_DEL', 'Modification: "%s" deleted');
\define('_MA_WGEVENTS_MAIL_REG_ALL_SUBJECT', 'Notification about event');
\define('_MA_WGEVENTS_MAIL_EVENT_NOTIFY_MODIFY_SUBJECT', 'Notification about modification of an event');
\define('_MA_WGEVENTS_MAIL_REG_IN_VERIF', 'For verification of your registration please click on following link: %s');
\define('_MA_WGEVENTS_MAIL_REG_IN_FINAL', 'About final confirmation you will be informed separately by the organizer.');
\define('_MA_WGEVENTS_MAIL_REG_IN_LISTWAIT', 'Your registration was put on a waiting list.');
\define('_MA_WGEVENTS_MAIL_REG_VERIF_ERROR', "Sorry, but an error occurred in the verification process for event '%s'. Please contact the organizer of the event.");
\define('_MA_WGEVENTS_MAIL_REG_VERIF_SUCCESS', "The registration for the event '%s' was verified successfully");
\define('_MA_WGEVENTS_MAIL_REG_VERIF_INFO', 'Verification of registration');
\define('_MA_WGEVENTS_MAIL_REG_SINGLE', 'For showing your registration please click on following link: %s');
// Admin link
\define('_MA_WGEVENTS_ADMIN', 'Admin');
// ---------------- End ----------------
// image editor
\define('_MA_WGEVENTS_IMG_EDITOR', 'Image editor');
\define('_MA_WGEVENTS_IMG_EDITOR_CREATE', 'Create image');
\define('_MA_WGEVENTS_IMG_EDITOR_APPLY', 'Apply');
\define('_MA_WGEVENTS_IMG_EDITOR_IMAGE_EDIT', 'Edit album image');
\define('_MA_WGEVENTS_IMG_EDITOR_CURRENT', 'Current');
\define('_MA_WGEVENTS_IMG_EDITOR_USE_EXISTING', 'Use existing image');
\define('_MA_WGEVENTS_IMG_EDITOR_GRID', 'Create an image grid');
\define('_MA_WGEVENTS_IMG_EDITOR_GRID4', 'Use 4 images');
\define('_MA_WGEVENTS_IMG_EDITOR_GRID6', 'Use 6 images');
\define('_MA_WGEVENTS_IMG_EDITOR_GRID_SRC1', 'Select image 1');
\define('_MA_WGEVENTS_IMG_EDITOR_GRID_SRC2', 'Select image 2');
\define('_MA_WGEVENTS_IMG_EDITOR_GRID_SRC3', 'Select image 3');
\define('_MA_WGEVENTS_IMG_EDITOR_GRID_SRC4', 'Select image 4');
\define('_MA_WGEVENTS_IMG_EDITOR_GRID_SRC5', 'Select image 5');
\define('_MA_WGEVENTS_IMG_EDITOR_GRID_SRC6', 'Select image 6');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP', 'Crop image');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_MOVE', 'Move');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_ZOOMIN', 'Zoom in');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_ZOOMOUT', 'Zomm out');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_LEFT', 'Move left');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_RIGHT', 'Move right');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_UP', 'Move up');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_DOWN', 'Move down');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_ROTATE_LEFT', 'Rotate left');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_ROTATE_RIGHT', 'Rotate right');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_FLIP_HORIZONTAL', 'Flip horizontal');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_FLIP_VERTICAL', 'Flip vertical');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_ASPECTRATIO', 'Aspect ratio');
\define('_MA_WGEVENTS_IMG_EDITOR_CROP_ASPECTRATIO_FREE', 'Free');
\define('_MA_WGEVENTS_IMG_EDITOR_CURRENT2', 'Source of current image');
\define('_MA_WGEVENTS_IMG_EDITOR_RESXY', 'Resolution');
\define('_MA_WGEVENTS_IMG_EDITOR_UPLOAD', 'Conditions for uploading images');
\define('_MA_WGEVENTS_IMG_EDITOR_RESIZE', 'Resize image automatically');
\define('_MA_WGEVENTS_IMG_EDITOR_RESIZE_DESC', 'Resize image automatically to default dimension (width max. %w px / height max. %h px): ');
\define('_MA_WGEVENTS_FORM_ERROR_INVALID_ID', 'Invalid ID');
\define('_MA_WGEVENTS_FORM_UPLOAD_IMG', 'Upload image');
\define('_MA_WGEVENTS_IMG_MAXSIZE', 'Maximum image size');
\define('_MA_WGEVENTS_IMG_MIMETYPES', 'Allowed mimetypes');
\define('_MA_WGEVENTS_SIZE_MB', 'MB');
