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

require_once __DIR__ . '/common.php';

// ---------------- Admin Main ----------------
\define('_MI_WGEVENTS_NAME', 'wgEvents');
\define('_MI_WGEVENTS_DESC', 'This module is for managing events and registrations for them');
// ---------------- Admin Menu ----------------
\define('_MI_WGEVENTS_ADMENU1', 'Dashboard');
\define('_MI_WGEVENTS_ADMENU2', 'Events');
\define('_MI_WGEVENTS_ADMENU3', 'Questions');
\define('_MI_WGEVENTS_ADMENU4', 'Answers');
\define('_MI_WGEVENTS_ADMENU5', 'Registrations');
\define('_MI_WGEVENTS_ADMENU6', 'Textblocks');
\define('_MI_WGEVENTS_ADMENU7', 'Categories');
\define('_MI_WGEVENTS_ADMENU8', 'Fields');
\define('_MI_WGEVENTS_ADMENU9', 'Maintenances');
\define('_MI_WGEVENTS_ADMENU10', 'Permission');
\define('_MI_WGEVENTS_ADMENU11', 'Registration History');
\define('_MI_WGEVENTS_ADMENU12', 'Answer History');
\define('_MI_WGEVENTS_ADMENU13', 'Logs');
\define('_MI_WGEVENTS_ADMENU14', 'Email Accounts');
\define('_MI_WGEVENTS_ADMENU15', 'Tasks');
\define('_MI_WGEVENTS_ADMENU20', 'Clone');
\define('_MI_WGEVENTS_ADMENU21', 'Feedback');
\define('_MI_WGEVENTS_ADMENU22', 'Import');
\define('_MI_WGEVENTS_ABOUT', 'About');
// ---------------- Admin Nav ----------------
// Submenu
\define('_MI_WGEVENTS_SMNAME1', 'Index page');
\define('_MI_WGEVENTS_SMNAME2', 'Events');
\define('_MI_WGEVENTS_SMNAME3', 'Submit Event');
\define('_MI_WGEVENTS_SMNAME4', 'Registration');
\define('_MI_WGEVENTS_SMNAME5', 'Show my Registrations');
\define('_MI_WGEVENTS_SMNAME6', 'Calendar');
//\define('_MI_WGEVENTS_SMNAME7', 'Submit Answer');
\define('_MI_WGEVENTS_SMNAME8', 'Textblock');
//\define('_MI_WGEVENTS_SMNAME9', 'Submit Textblock');
\define('_MI_WGEVENTS_SMNAME10', 'Show my Events');
\define('_MI_WGEVENTS_SMNAME11', 'Export events');
\define('_MI_WGEVENTS_SMNAME15', 'Search');
// Blocks
//\define('_MI_WGEVENTS_EVENTS_BLOCK', 'Event block');
//\define('_MI_WGEVENTS_EVENTS_BLOCK_DESC', 'Event block description');
//\define('_MI_WGEVENTS_EVENTS_BLOCK_EVENT', 'Event block  EVENT');
//\define('_MI_WGEVENTS_EVENTS_BLOCK_EVENT_DESC', 'Event block  EVENT description');
\define('_MI_WGEVENTS_EVENTS_BLOCK_LAST', 'Event block last');
\define('_MI_WGEVENTS_EVENTS_BLOCK_LAST_DESC', 'Block with the last events');
\define('_MI_WGEVENTS_EVENTS_BLOCK_NEW', 'Event block new');
\define('_MI_WGEVENTS_EVENTS_BLOCK_NEW_DESC', 'Block with new events (last week)');
\define('_MI_WGEVENTS_EVENTS_BLOCK_CAL', 'Mini calendar block');
\define('_MI_WGEVENTS_EVENTS_BLOCK_CAL_DESC', 'Block with mini calendar');
//\define('_MI_WGEVENTS_EVENTS_BLOCK_TOP', 'Event block top');
//\define('_MI_WGEVENTS_EVENTS_BLOCK_TOP_DESC', 'Event block top description');
\define('_MI_WGEVENTS_EVENTS_BLOCK_RANDOM', 'Event block random');
\define('_MI_WGEVENTS_EVENTS_BLOCK_RANDOM_DESC', 'Block with random events');
\define('_MI_WGEVENTS_EVENTS_BLOCK_SPOTLIGHT', 'Event block spotlight');
\define('_MI_WGEVENTS_EVENTS_BLOCK_SPOTLIGHT_DESC', 'Block to spotlight specific event');
\define('_MI_WGEVENTS_EVENTS_BLOCK_COMING', 'Event block coming');
\define('_MI_WGEVENTS_EVENTS_BLOCK_COMING_DESC', 'block with the next coming events');
// Config
//Display
\define('_MI_WGEVENTS_GROUP_DISPLAY', 'Display');
\define('_MI_WGEVENTS_EDITOR_ADMIN', 'Editor admin');
\define('_MI_WGEVENTS_EDITOR_ADMIN_DESC', 'Select the editor which should be used in admin area for text area fields');
\define('_MI_WGEVENTS_EDITOR_USER', 'Editor user');
\define('_MI_WGEVENTS_EDITOR_USER_DESC', 'Select the editor which should be used in user area for text area fields');
\define('_MI_WGEVENTS_ADMIN_MAXCHAR', 'Text max characters admin');
\define('_MI_WGEVENTS_ADMIN_MAXCHAR_DESC', 'Max characters for showing text of a textarea or editor field in admin area');
\define('_MI_WGEVENTS_USER_MAXCHAR', 'Text max characters user');
\define('_MI_WGEVENTS_USER_MAXCHAR_DESC', 'Max characters for showing text of a textarea or editor field in user area');
\define('_MI_WGEVENTS_ADMIN_PAGER', 'Admin pager');
\define('_MI_WGEVENTS_ADMIN_PAGER_DESC', 'Admin per page list');
\define('_MI_WGEVENTS_TABLESORTER_ADMIN', 'TableSorter Theme Admin');
\define('_MI_WGEVENTS_TABLESORTER_ADMIN_DESC', 'Select TableSorter Theme which should be used in admin area');
\define('_MI_WGEVENTS_USER_PAGER', 'User pager');
\define('_MI_WGEVENTS_USER_PAGER_DESC', 'User per page list');
\define('_MI_WGEVENTS_TABLESORTER_USER', 'TableSorter Theme User');
\define('_MI_WGEVENTS_TABLESORTER_USER_DESC', 'Select TableSorter Theme which should be used in user area');
\define('_MI_WGEVENTS_SHOWCOPYRIGHT', 'Show copyright');
\define('_MI_WGEVENTS_SHOWCOPYRIGHT_DESC', 'You can remove the copyright from the wgEvents pages, but a backlinks to www.wedega.com is expected, anywhere on your site');
\define('_MI_WGEVENTS_SHOW_BREADCRUMBS', 'Show breadcrumb navigation');
\define('_MI_WGEVENTS_SHOW_BREADCRUMBS_DESC', 'Show breadcrumb navigation which displays the current page in context within the site structure');
\define('_MI_WGEVENTS_TABLE_TYPE', 'Table Type');
\define('_MI_WGEVENTS_TABLE_TYPE_DESC', 'Table Type is the bootstrap html table');
\define('_MI_WGEVENTS_PANEL_TYPE', 'Panel Type');
\define('_MI_WGEVENTS_PANEL_TYPE_DESC', 'Panel Type is the bootstrap html div');
//Calendar
\define('_MI_WGEVENTS_GROUP_CAL', 'Calendar');
\define('_MI_WGEVENTS_CAL_PAGE', 'Calendar page');
\define('_MI_WGEVENTS_CAL_PAGE_DESC', 'Show separate calendar page with events');
\define('_MI_WGEVENTS_CAL_FIRSTDAY', 'First day in calendar');
\define('_MI_WGEVENTS_CAL_FIRSTDAY_DESC', 'Decide which day in monthly calendar should be the first one');
\define('_MI_WGEVENTS_CAL_SUNDAY', 'Sunday');
\define('_MI_WGEVENTS_CAL_MONDAY', 'Monday');
\define('_MI_WGEVENTS_CAL_TUESDAY', 'Tuesday');
\define('_MI_WGEVENTS_CAL_WEDNESDAY', 'Wednesday');
\define('_MI_WGEVENTS_CAL_THURSDAY', 'Thursday');
\define('_MI_WGEVENTS_CAL_FRIDAY', 'Friday');
\define('_MI_WGEVENTS_CAL_SATURDAY', 'Saturday');
\define('_MI_WGEVENTS_CAL_INDEX', 'Show calendar on index page');
\define('_MI_WGEVENTS_CAL_INDEX_DESC', 'Please define whether a calendar should be displayed on index page');
//Upload options
\define('_MI_WGEVENTS_GROUP_UPLOAD', 'Upload options');
\define('_MI_WGEVENTS_SIZE_MB', 'MB');
\define('_MI_WGEVENTS_MAXSIZE_IMAGE', 'Max size image');
\define('_MI_WGEVENTS_MAXSIZE_IMAGE_DESC', 'Define the max size for uploading images');
\define('_MI_WGEVENTS_MIMETYPES_IMAGE', 'Mime types image');
\define('_MI_WGEVENTS_MIMETYPES_IMAGE_DESC', 'Define the allowed mime types for uploading images');
\define('_MI_WGEVENTS_MAXWIDTH_IMAGE', 'Max width image');
\define('_MI_WGEVENTS_MAXWIDTH_IMAGE_DESC', 'Set the max width to which uploaded images should be scaled (in pixel)<br>0 means, that images keeps the original size. <br>If an image is smaller than maximum value then the image will be not enlarge, it will be save in original width.');
\define('_MI_WGEVENTS_MAXHEIGHT_IMAGE', 'Max height image');
\define('_MI_WGEVENTS_MAXHEIGHT_IMAGE_DESC', 'Set the max height to which uploaded images should be scaled (in pixel)<br>0 means, that images keeps the original size. <br>If an image is smaller than maximum value then the image will be not enlarge, it will be save in original height');
//Formats
\define('_MI_WGEVENTS_GROUP_FORMATS', 'Formats');
\define('_MI_WGEVENTS_SEP_COMMA', 'Comma separator');
\define('_MI_WGEVENTS_SEP_COMMA_DESC', 'Please define comma separator');
\define('_MI_WGEVENTS_SEP_THSD', 'Thousands separator');
\define('_MI_WGEVENTS_SEP_THSD_DESC', 'Please define thousands separator');
\define('_MI_WGEVENTS_EVENT_DAYNAMES', 'Show dayname');
\define('_MI_WGEVENTS_EVENT_DAYNAMES_DESC', 'Please define whether dayname should be shown with dates');
\define('_MI_WGEVENTS_EVENT_DAYNAMES_NONE', 'Do not show');
\define('_MI_WGEVENTS_EVENT_DAYNAMES_SHORT', 'Show short name (e.g. "Sun" "Mon" and so on)');
\define('_MI_WGEVENTS_EVENT_DAYNAMES_SHORTDOT', 'Show short name with dot (e.g. "Sun." "Mon." and so on)');
\define('_MI_WGEVENTS_EVENT_DAYNAMES_SHORTCOMMA', 'Show short name with comma (z.B. "Sun," "Mon," usw.)');
\define('_MI_WGEVENTS_EVENT_DAYNAMES_LONG', 'Show long name (e.g. "Sunday" "Monday" and so on)');
//Features
\define('_MI_WGEVENTS_GROUP_FEATURES', 'Features');
\define('_MI_WGEVENTS_USE_REGISTER', 'Use registration system');
\define('_MI_WGEVENTS_USE_REGISTER_DESC', 'Use module incuded registration system');
\define('_MI_WGEVENTS_USE_HISTORY', 'Use registration history');
\define('_MI_WGEVENTS_USE_HISTORY_DESC', 'Save data of registrations in a history before updating or deleting');
\define('_MI_WGEVENTS_USE_LOGS', 'Create Log');
\define('_MI_WGEVENTS_USE_LOGS_DESC', 'Save tries and results of mail sending in a log table');
\define('_MI_WGEVENTS_USE_WGGALLERY', 'Use wgEvents');
\define('_MI_WGEVENTS_USE_WGGALLERY_DESC', 'Use module wgEvents to link events with galleries');
\define('_MI_WGEVENTS_USE_GMAPS', 'Use Google Maps');
\define('_MI_WGEVENTS_USE_GMAPS_DESC', 'Show events with google maps');
\define('_MI_WGEVENTS_USE_GROUPS', 'Use display per groups');
\define('_MI_WGEVENTS_USE_GROUPS_DESC', 'If yes, then the events will be shown allways the defined groups. If no, all groups can see all events which are online.');
//Google Maps
\define('_MI_WGEVENTS_GROUP_GMAPS', 'Google Maps');
//Google Maps API Key
\define('_MI_WGEVENTS_GMAPS_API', 'Paste your Google Maps API key for your website here');
\define('_MI_WGEVENTS_GMAPS_API_DESC', 'Get keys from <a href="https://developers.google.com/maps/documentation/javascript/get-api-key/">https://developers.google.com/maps/documentation/javascript/get-api-key/</a>
                                        <br>necessary APIs: Geocoding API, Maps JavaScript API, Places API');
\define('_MI_WGEVENTS_GMAPS_HEIGHT', '"Google Map" height (in pixels)');
\define('_MI_WGEVENTS_GMAPS_ENABLECAL', 'Show google map in calendar view');
\define('_MI_WGEVENTS_GMAPS_ENABLECAL_DESC', 'Define whether google map should be shown in calendar view and where');
\define('_MI_WGEVENTS_GMAPS_ENABLEINDEX', 'Show google map on index page');
\define('_MI_WGEVENTS_GMAPS_ENABLEINDEX_DESC', 'Define whether google map should be shown on index page and where');
\define('_MI_WGEVENTS_GMAPS_ENABLEEVENT', 'Show google map in event list view');
\define('_MI_WGEVENTS_GMAPS_ENABLEEVENT_DESC', 'Define whether google map should be shown in list view and where');
\define('_MI_WGEVENTS_GMAPS_POSITION_NONE', 'Do not show');
\define('_MI_WGEVENTS_GMAPS_POSITION_TOP', 'Show Google Map on top');
\define('_MI_WGEVENTS_GMAPS_POSITION_BOTTOM', 'Show Google Map on bottom');
//Index page
\define('_MI_WGEVENTS_GROUP_INDEX', 'Index page');
\define('_MI_WGEVENTS_INDEXHEADER', 'Description on index page');
\define('_MI_WGEVENTS_INDEXHEADER_DESC', 'Please enter your costum module description on index page');
\define('_MI_WGEVENTS_INDEX_DISPLAY_NONE', 'Do not display');
\define('_MI_WGEVENTS_INDEX_DISPLAY_LIST', 'Show as list');
\define('_MI_WGEVENTS_INDEX_DISPLAY_BUTTON', 'Show as button');
\define('_MI_WGEVENTS_INDEX_DISPLAY_FORM', 'Show as form');
\define('_MI_WGEVENTS_INDEX_DISPLAY_BCARDS', 'Use Bootstrap Cards');
\define('_MI_WGEVENTS_INDEX_DISPLAYCATS', 'Display type for categories on index page');
\define('_MI_WGEVENTS_INDEX_DISPLAYCATS_DESC', 'Please define how you want to displayed categories on index page');
\define('_MI_WGEVENTS_INDEX_DISPLAYEVENTS', 'Display type for events on index page');
\define('_MI_WGEVENTS_INDEX_DISPLAYEVENTS_DESC', 'Please define how you want to displayed next events on index page');
\define('_MI_WGEVENTS_GROUP_EVENT', 'Event page');
\define('_MI_WGEVENTS_EVENT_DISPLAYCATS', 'Display type for categories on event page');
\define('_MI_WGEVENTS_EVENT_DISPLAYCATS_DESC', 'Please define how you want to displayed categories on event page');
\define('_MI_WGEVENTS_EVENT_DISPLAYEVENTS', 'Display type for events on event page');
\define('_MI_WGEVENTS_EVENT_DISPLAYEVENTS_DESC', 'Please define how you want to displayed next events on event page');
//Misc
\define('_MI_WGEVENTS_GROUP_MISC', 'Misc');
\define('_MI_WGEVENTS_KEYWORDS', 'Keywords');
\define('_MI_WGEVENTS_KEYWORDS_DESC', 'Insert here the keywords (separate by comma)');
\define('_MI_WGEVENTS_IDPAYPAL', 'Paypal ID');
\define('_MI_WGEVENTS_IDPAYPAL_DESC', 'Insert here your PayPal ID for donations');
\define('_MI_WGEVENTS_ADVERTISE', 'Advertisement Code');
\define('_MI_WGEVENTS_ADVERTISE_DESC', 'Insert here the advertisement code');
\define('_MI_WGEVENTS_MAINTAINEDBY', 'Maintained By');
\define('_MI_WGEVENTS_MAINTAINEDBY_DESC', 'Allow url of support site or community');
\define('_MI_WGEVENTS_BOOKMARKS', 'Social Bookmarks');
\define('_MI_WGEVENTS_BOOKMARKS_DESC', 'Show Social Bookmarks in the single page');
// Global notifications
\define('_MI_WGEVENTS_NOTIFY_GLOBAL', 'Global notification');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_NEW', 'Any new item');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_NEW_CAPTION', 'Notify me about any new item');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_NEW_SUBJECT', 'Notification about new item');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_MODIFY', 'Any modified item');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_MODIFY_CAPTION', 'Notify me about any item modification');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_MODIFY_SUBJECT', 'Notification about modification');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_DELETE', 'Any deleted item');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_DELETE_CAPTION', 'Notify me about any deleted item');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_DELETE_SUBJECT', 'Notification about deleted item');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_APPROVE', 'Any item to approve');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_APPROVE_CAPTION', 'Notify me about any item waiting for approvement');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_APPROVE_SUBJECT', 'Notification about item waiting for approvement');
// Event notifications
\define('_MI_WGEVENTS_NOTIFY_EVENT', 'Event notification');
\define('_MI_WGEVENTS_NOTIFY_EVENT_MODIFY', 'Event modification');
\define('_MI_WGEVENTS_NOTIFY_EVENT_MODIFY_CAPTION', 'Notify me about event modification');
\define('_MI_WGEVENTS_NOTIFY_EVENT_MODIFY_SUBJECT', 'Notification about modification');
\define('_MI_WGEVENTS_NOTIFY_EVENT_DELETE', 'Event deleted');
\define('_MI_WGEVENTS_NOTIFY_EVENT_DELETE_CAPTION', 'Notify me about deleted events');
\define('_MI_WGEVENTS_NOTIFY_EVENT_DELETE_SUBJECT', 'Notification delete event');
\define('_MI_WGEVENTS_NOTIFY_EVENT_APPROVE', 'Event approve');
\define('_MI_WGEVENTS_NOTIFY_EVENT_APPROVE_CAPTION', 'Notify me about events waiting for approvement');
\define('_MI_WGEVENTS_NOTIFY_EVENT_APPROVE_SUBJECT', 'Notification event waiting for approvement');
// Registration notifications
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION', 'Registration notification');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_MODIFY', 'Registration modification');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_MODIFY_CAPTION', 'Notify me about registration modification');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_MODIFY_SUBJECT', 'Notification about modification');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_DELETE', 'Registration deleted');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_DELETE_CAPTION', 'Notify me about deleted registrations');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_DELETE_SUBJECT', 'Notification delete registration');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_APPROVE', 'Registration approve');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_APPROVE_CAPTION', 'Notify me about registrations waiting for approvement');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_APPROVE_SUBJECT', 'Notification registration waiting for approvement');
// Tablesorter
\define('_MI_WGEVENTS_TABLESORTER_SHOW_ALL', 'Show all');
// ---------------- End ----------------
