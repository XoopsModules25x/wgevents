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

// ---------------- Admin Main ----------------
\define('_MI_WGEVENTS_NAME', 'wgEvents');
\define('_MI_WGEVENTS_DESC', 'Dieses Modul dient zur Verwaltung von Veranstaltungen und den Anmeldungen dafür');
// ---------------- Admin Menu ----------------
\define('_MI_WGEVENTS_ADMENU1', 'Übersicht');
\define('_MI_WGEVENTS_ADMENU2', 'Veranstaltungen');
\define('_MI_WGEVENTS_ADMENU3', 'Fragen');
\define('_MI_WGEVENTS_ADMENU4', 'Antworten');
\define('_MI_WGEVENTS_ADMENU5', 'Anmeldungen');
\define('_MI_WGEVENTS_ADMENU6', 'Textblöcke');
\define('_MI_WGEVENTS_ADMENU7', 'Kategorien');
\define('_MI_WGEVENTS_ADMENU8', 'Felder');
\define('_MI_WGEVENTS_ADMENU9', 'Wartungen');
\define('_MI_WGEVENTS_ADMENU10', 'Berechtigungen');
\define('_MI_WGEVENTS_ADMENU11', 'Anmeldungen Historie');
\define('_MI_WGEVENTS_ADMENU12', 'Antworten Historie');
\define('_MI_WGEVENTS_ADMENU13', 'Logs');
\define('_MI_WGEVENTS_ADMENU14', 'E-Mail-Konten');
\define('_MI_WGEVENTS_ADMENU20', 'Klonen');
\define('_MI_WGEVENTS_ADMENU21', 'Feedback');
\define('_MI_WGEVENTS_ABOUT', 'Über');
// ---------------- Admin Nav ----------------
\define('_MI_WGEVENTS_ADMIN_PAGER', 'Listen Admin');
\define('_MI_WGEVENTS_ADMIN_PAGER_DESC', 'Anzahl Einträge in Listen im Adminbereich');
// User
\define('_MI_WGEVENTS_USER_PAGER', 'Listen User');
\define('_MI_WGEVENTS_USER_PAGER_DESC', 'Anzahl Einträge in Listen im Userbereich');
// Submenu
\define('_MI_WGEVENTS_SMNAME1', 'Übersicht');
\define('_MI_WGEVENTS_SMNAME2', 'Veranstaltungen');
\define('_MI_WGEVENTS_SMNAME3', 'Veranstaltung erstellen');
\define('_MI_WGEVENTS_SMNAME4', 'Anmeldungen');
\define('_MI_WGEVENTS_SMNAME5', 'Meine Anmeldungen');
\define('_MI_WGEVENTS_SMNAME6', 'Kalender');
//\define('_MI_WGEVENTS_SMNAME7', 'Submit Antworten');
\define('_MI_WGEVENTS_SMNAME8', 'Textblöcke');
//\define('_MI_WGEVENTS_SMNAME9', 'Submit Textblöcke');
\define('_MI_WGEVENTS_SMNAME10', 'Meine Veranstaltungen');
\define('_MI_WGEVENTS_SMNAME15', 'Suche');
// Blocks
//\define('_MI_WGEVENTS_EVENTS_BLOCK', 'Block Veranstaltungen');
//\define('_MI_WGEVENTS_EVENTS_BLOCK_DESC', 'Veranstaltungsblock Beschreibung');
//\define('_MI_WGEVENTS_EVENTS_BLOCK_EVENT', 'Veranstaltungen block  EVENT');
//\define('_MI_WGEVENTS_EVENTS_BLOCK_EVENT_DESC', 'Veranstaltungen block  EVENT description');
\define('_MI_WGEVENTS_EVENTS_BLOCK_LAST', 'Letzte Veranstaltungen');
\define('_MI_WGEVENTS_EVENTS_BLOCK_LAST_DESC', 'Anzeige der letzten Veranstaltungen');
\define('_MI_WGEVENTS_EVENTS_BLOCK_NEW', 'Neue Veranstaltungen');
\define('_MI_WGEVENTS_EVENTS_BLOCK_NEW_DESC', 'Anzeige der neuesten Veranstaltungen (letzte Woche)');
\define('_MI_WGEVENTS_EVENTS_BLOCK_CAL', 'Minikalender');
\define('_MI_WGEVENTS_EVENTS_BLOCK_CAL_DESC', 'Block mit Minikalender');
//\define('_MI_WGEVENTS_EVENTS_BLOCK_TOP_DESC', 'Veranstaltungen block top description');
\define('_MI_WGEVENTS_EVENTS_BLOCK_RANDOM', 'Zufällige Veranstaltungen');
\define('_MI_WGEVENTS_EVENTS_BLOCK_RANDOM_DESC', 'Anzeige von zufälligen Veranstaltungen');
\define('_MI_WGEVENTS_EVENTS_BLOCK_SPOTLIGHT', 'Bestimmte Veranstaltungen');
\define('_MI_WGEVENTS_EVENTS_BLOCK_SPOTLIGHT_DESC', 'Anzeige der bestimmten Veranstaltungen');
// Config
\define('_MI_WGEVENTS_EDITOR_ADMIN', 'Editor Admin');
\define('_MI_WGEVENTS_EDITOR_ADMIN_DESC', 'Bitte den zu verwendenden Editor für den Admin-Bereich wählen');
\define('_MI_WGEVENTS_EDITOR_USER', 'Editor User');
\define('_MI_WGEVENTS_EDITOR_USER_DESC', 'Bitte den zu verwendenden Editor für den User-Bereich wählen');
\define('_MI_WGEVENTS_ADMIN_MAXCHAR', 'Maximale Zeichen Text Adminbereich');
\define('_MI_WGEVENTS_ADMIN_MAXCHAR_DESC', 'Maximale Anzahl an Zeichen für die Anzeige von Texten in Listen im Admin-Bereich');
\define('_MI_WGEVENTS_USER_MAXCHAR', 'Maximale Zeichen Text Benutzerbereich');
\define('_MI_WGEVENTS_USER_MAXCHAR_DESC', 'Maximale Anzahl an Zeichen für die Anzeige von Texten in Listen im Benutzerbereich');
\define('_MI_WGEVENTS_KEYWORDS', 'Schlüsselworter');
\define('_MI_WGEVENTS_KEYWORDS_DESC', 'Bitte Schlüsselwörter angeben (getrennt durch ein Komma)');
\define('_MI_WGEVENTS_SIZE_MB', 'MB');
\define('_MI_WGEVENTS_MAXSIZE_IMAGE', 'Maximale Größe Bilddatei');
\define('_MI_WGEVENTS_MAXSIZE_IMAGE_DESC', 'Bitte die für den Upload von Dateien zulässigen maximale Dateigröße definieren');
\define('_MI_WGEVENTS_MIMETYPES_IMAGE', 'Zulässige Dateierweiterungen');
\define('_MI_WGEVENTS_MIMETYPES_IMAGE_DESC', 'Bitte die für den Upload von Bildern zulässigen Dateierweiterungen definieren');
\define('_MI_WGEVENTS_MAXWIDTH_IMAGE', 'Maximale Breite für große Bilder');
\define('_MI_WGEVENTS_MAXWIDTH_IMAGE_DESC', 'Definieren Sie die maximale Breite, auf die die hochgeladenen Bilder automatisch verkleinert werden sollen (in pixel)<br>0 bedeutet, dass Bilder die Originalgröße behalten. <br>Wenn ein Bild kleiner ist als die angegebenen Maximalwerte, so wird das Bild nicht vergrößert, sondern es wird in Originalgröße abgespeichert');
\define('_MI_WGEVENTS_MAXHEIGHT_IMAGE', 'Maximale Höhe für große Bilder');
\define('_MI_WGEVENTS_MAXHEIGHT_IMAGE_DESC', 'Definieren Sie die maximale Höhe, auf die die hochgeladenen Bilder automatisch verkleinert werden sollen (in pixel)<br>0 bedeutet, dass Bilder die Originalgröße behalten. <br>Wenn ein Bild kleiner ist als die angegebenen Maximalwerte, so wird das Bild nicht vergrößert, sondern es wird in Originalgröße abgespeichert');
\define('_MI_WGEVENTS_TABLE_TYPE', 'Table Type');
\define('_MI_WGEVENTS_TABLE_TYPE_DESC', 'Table Type is the bootstrap html table');
\define('_MI_WGEVENTS_PANEL_TYPE', 'Panel Type');
\define('_MI_WGEVENTS_PANEL_TYPE_DESC', 'Panel Type is the bootstrap html div');
\define('_MI_WGEVENTS_IDPAYPAL', 'Paypal ID');
\define('_MI_WGEVENTS_IDPAYPAL_DESC', 'Insert here your PayPal ID for donations');
\define('_MI_WGEVENTS_SHOW_BREADCRUMBS', 'Brotkrumen-Navigation (breadcrumbs) anzeigen');
\define('_MI_WGEVENTS_SHOW_BREADCRUMBS_DESC', 'Eine Brotkrumen-Navigation zeigt den aktuellen Seitenstand innerhalb der Websitestruktur');
\define('_MI_WGEVENTS_SHOWCOPYRIGHT', 'Copyright anzeigen');
\define('_MI_WGEVENTS_SHOWCOPYRIGHT_DESC', 'Sie können das Copyright bei der wgSimpleAcc-Ansicht entfernen, jedoch wird ersucht, an einer beliebigen Stelle einen Backlink auf www.wedega.com anzubringen');
\define('_MI_WGEVENTS_ADVERTISE', 'Code Werbung');
\define('_MI_WGEVENTS_ADVERTISE_DESC', 'Bitte Code für Werbungen eingeben');
\define('_MI_WGEVENTS_MAINTAINEDBY', 'Unterstützt durch');
\define('_MI_WGEVENTS_MAINTAINEDBY_DESC', 'Bitte Url für Support oder zur Community angeben');
\define('_MI_WGEVENTS_BOOKMARKS', 'Social Bookmarks');
\define('_MI_WGEVENTS_BOOKMARKS_DESC', 'Show Social Bookmarks in the single page');
\define('_MI_WGEVENTS_USE_GM', 'Google Maps verwenden');
\define('_MI_WGEVENTS_USE_GM_DESC', 'Zeigt Veranstaltungen mit Google Maps');
\define('_MI_WGEVENTS_USE_REGISTER', 'Anmeldung verwenden');
\define('_MI_WGEVENTS_USE_REGISTER_DESC', 'Das modulinterne Anmeldungssystem verwenden');
\define('_MI_WGEVENTS_USE_HISTORY', 'Anmeldungshistorie verwenden');
\define('_MI_WGEVENTS_USE_HISTORY_DESC', 'Daten der Anmeldung vor den Löschen oder Aktualisieren in einer Historietabelle speichern');
\define('_MI_WGEVENTS_USE_LOGS', 'Log erstellen');
\define('_MI_WGEVENTS_USE_LOGS_DESC', 'Versuche und Ergebnisse vom Mailversand in Log-Datei speichern');
\define('_MI_WGEVENTS_USE_WGGALLERY', 'wgGallery verwenden');
\define('_MI_WGEVENTS_USE_WGGALLERY_DESC', 'wgGallery zum Verlinken von Veranstaltungen und Bildergallerien verwenden');
\define('_MI_WGEVENTS_SEP_COMMA', 'Komma-Zeichen');
\define('_MI_WGEVENTS_SEP_COMMA_DESC', 'Bitte das Zeichen für Komma definieren');
\define('_MI_WGEVENTS_SEP_THSD', 'Tausender-Trennzeichen');
\define('_MI_WGEVENTS_SEP_THSD_DESC', 'Bitte das Trennzeichen für Tausender definieren');
\define('_MI_WGEVENTS_INDEXHEADER', 'Index Kopfzeile');
\define('_MI_WGEVENTS_INDEXHEADER_DESC', 'Diesen Text als Überschrift in der Indexseite anzeigen');
\define('_MI_WGEVENTS_CAL_INDEX', 'Kalendar auf Index-Seite');
\define('_MI_WGEVENTS_CAL_INDEX_DESC', 'Bitte definieren, ob auf der Indexseite ein Veranstaltungskalender angezeigt werden soll');
\define('_MI_WGEVENTS_GROUP_MISC', 'Verschiedenes');
\define('_MI_WGEVENTS_GROUP_DISPLAY', 'Anzeige');
\define('_MI_WGEVENTS_GROUP_FEATURES', 'Features');
\define('_MI_WGEVENTS_GROUP_FORMATS', 'Formate');
\define('_MI_WGEVENTS_GROUP_UPLOAD', 'Optionen für Uploads');
\define('_MI_WGEVENTS_GROUP_INDEX', 'Index-Seite');
\define('_MI_WGEVENTS_GROUP_CAL', 'Kalender');
\define('_MI_WGEVENTS_CAL_PAGE', 'Kalenderseite');
\define('_MI_WGEVENTS_CAL_PAGE_DESC', 'Eigene Seite Kalender mit den Veranstaltungen anzeigen');
\define('_MI_WGEVENTS_CAL_FIRSTDAY', 'Erster Tag im Kalender');
\define('_MI_WGEVENTS_CAL_FIRSTDAY_DESC', 'Entscheiden Sie welcher Tag im Monatskalender als erster Tag angezeigt werden soll.');
\define('_MI_WGEVENTS_CAL_SUNDAY', 'Sonntag');
\define('_MI_WGEVENTS_CAL_MONDAY', 'Montag');
\define('_MI_WGEVENTS_CAL_TUESDAY', 'Dienstag');
\define('_MI_WGEVENTS_CAL_WEDNESDAY', 'Mittwoch');
\define('_MI_WGEVENTS_CAL_THURSDAY', 'Donnerstag');
\define('_MI_WGEVENTS_CAL_FRIDAY', 'Freitag');
\define('_MI_WGEVENTS_CAL_SATURDAY', 'Samstag');
// Global notifications
\define('_MI_WGEVENTS_NOTIFY_GLOBAL', 'Globale Benachrichtigungen');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_NEW', 'Alle neuen Einträge');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_NEW_CAPTION', 'Benachrichtige mich über alle neuen Einträge');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_NEW_SUBJECT', 'Benachrichtigung über neuen Eintrag');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_MODIFY', 'Alle Änderungen Einträge');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_MODIFY_CAPTION', 'Benachrichtige mich über alle Änderungen von Einträgen');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_MODIFY_SUBJECT', 'Benachrichtigung über Änderung Eintrag');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_DELETE', 'Alle neuen Einträg');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_DELETE_CAPTION', 'Benachrichtige mich über alle Löschungen von Einträgen');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_DELETE_SUBJECT', 'Benachrichtigung über Löschung Eintrag');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_APPROVE', 'Alle auf Freigabe wartende Einträge');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_APPROVE_CAPTION', 'Benachrichtige mich über alle auf Freigabe wartende Einträge');
\define('_MI_WGEVENTS_NOTIFY_GLOBAL_APPROVE_SUBJECT', 'Benachrichtigung über auf Freigabe wartenden Eintrag');
// event notifications
\define('_MI_WGEVENTS_NOTIFY_EVENT', 'Benachrichtigung Veranstaltung');
\define('_MI_WGEVENTS_NOTIFY_EVENT_MODIFY', 'Änderung Veranstaltung');
\define('_MI_WGEVENTS_NOTIFY_EVENT_MODIFY_CAPTION', 'Benachrichtige mich über die Änderung der Veranstaltung');
\define('_MI_WGEVENTS_NOTIFY_EVENT_MODIFY_SUBJECT', 'Benachrichtigung über Änderung Veranstaltung');
\define('_MI_WGEVENTS_NOTIFY_EVENT_DELETE', 'Löschung Veranstaltung');
\define('_MI_WGEVENTS_NOTIFY_EVENT_DELETE_CAPTION', 'Benachrichtige mich über die Löschung der Veranstaltung');
\define('_MI_WGEVENTS_NOTIFY_EVENT_DELETE_SUBJECT', 'Benachrichtigung Löschung Veranstaltung');
\define('_MI_WGEVENTS_NOTIFY_EVENT_APPROVE', 'Freigabe Veranstaltung');
\define('_MI_WGEVENTS_NOTIFY_EVENT_APPROVE_CAPTION', 'Benachrichtige mich über auf Freigabe wartende Veranstaltungen');
\define('_MI_WGEVENTS_NOTIFY_EVENT_APPROVE_SUBJECT', 'Benachrichtigung Veranstaltung wartet auf Freigabe');
// registration notifications
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION', 'Benachrichtigung Anmeldung');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_MODIFY', 'Anmeldung Änderung');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_MODIFY_CAPTION', 'Benachrichtige mich über die Änderung der Anmeldung');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_MODIFY_SUBJECT', 'Benachrichtigung über Änderung Anmeldung');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_DELETE', 'Löschung Anmeldung');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_DELETE_CAPTION', 'Benachrichtige mich über die Löschung der Anmeldung');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_DELETE_SUBJECT', 'Benachrichtigung Löschung Anmeldung');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_APPROVE', 'Freigabe Anmeldung');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_APPROVE_CAPTION', 'Benachrichtige mich über auf Freigabe wartende Anmeldung');
\define('_MI_WGEVENTS_NOTIFY_REGISTRATION_APPROVE_SUBJECT', 'Benachrichtigung Anmeldung wartet auf Freigabe');
// tablesorter
\define('_MI_WGEVENTS_TABLESORTER_SHOW_ALL', 'Alle anzeigen');
// ---------------- End ----------------
