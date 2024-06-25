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
require_once __DIR__ . '/main.php';

// ---------------- Admin Index ----------------
\define('_AM_WGEVENTS_STATISTICS', 'Statistiken');
// There are
\define('_AM_WGEVENTS_THEREARE_EVENTS', "Es gibt <span class='bold'>%s</span> Veranstaltungen in der Datenbank");
\define('_AM_WGEVENTS_THEREARE_CATEGORIES', "Es gibt <span class='bold'>%s</span> Kategorien in der Datenbank");
\define('_AM_WGEVENTS_THEREARE_REGISTRATIONS', "Es gibt <span class='bold'>%s</span> Anmeldungen in der Datenbank");
\define('_AM_WGEVENTS_THEREARE_REGISTRATIONHISTS', "Es gibt <span class='bold'>%s</span> historische Anmeldungen in der Datenbank");
\define('_AM_WGEVENTS_THEREARE_QUESTIONS', "Es gibt <span class='bold'>%s</span> Fragen in der Datenbank");
\define('_AM_WGEVENTS_THEREARE_FIELDS', "Es gibt <span class='bold'>%s</span> Felder in der Datenbank");
\define('_AM_WGEVENTS_THEREARE_ANSWERS', "Es gibt <span class='bold'>%s</span> Antworten in der Datenbank");
\define('_AM_WGEVENTS_THEREARE_ANSWERHISTS', "Es gibt <span class='bold'>%s</span> historische Antworten in der Datenbank");
\define('_AM_WGEVENTS_THEREARE_TEXTBLOCKS', "Es gibt <span class='bold'>%s</span> Textblöcke in der Datenbank");
\define('_AM_WGEVENTS_THEREARE_LOGS', "Es gibt <span class='bold'>%s</span> Log-Einträge in der Datenbank");
\define('_AM_WGEVENTS_THEREARE_ACCOUNTS', "Es sind <span class='bold'>%s</span> E-Mail-Konten vorhanden");
\define('_AM_WGEVENTS_THEREARE_TASKS', "Es sind <span class='bold'>%s</span> Aufgaben vorhanden");
// ---------------- Admin Files ----------------
// There aren't
\define('_AM_WGEVENTS_THEREARENT_EVENTS', 'Es gibt keine Veranstaltungen');
\define('_AM_WGEVENTS_THEREARENT_CATEGORIES', 'Es gibt keine Kategorien');
\define('_AM_WGEVENTS_THEREARENT_REGISTRATIONS', 'Es gibt keine Anmeldungen');
\define('_AM_WGEVENTS_THEREARENT_REGISTRATIONHISTS', 'Es gibt keine historischen Anmeldungen');
\define('_AM_WGEVENTS_THEREARENT_QUESTIONS', 'Es gibt keine Fragen');
\define('_AM_WGEVENTS_THEREARENT_ANSWERS', 'Es gibt keine Antworten');
\define('_AM_WGEVENTS_THEREARENT_ANSWERHISTS', 'Es gibt keine historischen Antworten');
\define('_AM_WGEVENTS_THEREARENT_TEXTBLOCKS', 'Es gibt keine Textblöcke');
\define('_AM_WGEVENTS_THEREARENT_FIELDS', 'Es gibt keine Felder');
\define('_AM_WGEVENTS_THEREARENT_LOGS', 'Es gibt keine Log-Einträge');
\define('_AM_WGEVENTS_THEREARENT_ACCOUNTS', 'Es ist keine E-Mail-Konten vorhanden.');
\define('_AM_WGEVENTS_THEREARENT_ACCOUNTS_DESC', 'Es ist kein primäres E-Mail-Konten vorhanden. Die Standardeinstellungen E-Mails vom XOOPS Core werden daher verwendet.');
\define('_AM_WGEVENTS_THEREARENT_TASKS', 'Es ist keine Aufgaben vorhanden.');
// timezones
\define('_AM_WGEVENTS_TIMEZONES', 'Einstellungen Zeitzonen');
// There are
\define('_AM_WGEVENTS_TIMEZONE_PHP', 'PHP Standard-Zeitzone: %s');
\define('_AM_WGEVENTS_TIMEZONE_SERVER', 'XOOPS Server-Zeitzone: %s');
\define('_AM_WGEVENTS_TIMEZONE_DEFAULT', 'XOOPS Standard-Zeitzone: %s');
\define('_AM_WGEVENTS_TIMEZONE_USER', "Zeitzone aktueller Benutzer '%s': %s");
// Buttons
\define('_AM_WGEVENTS_ADD_EVENT', 'Neue Veranstaltung hinzufügen');
\define('_AM_WGEVENTS_ADD_CATEGORY', 'Neue Kategorie hinzufügen');
\define('_AM_WGEVENTS_ADD_REGISTRATION', 'Neue Anmeldung hinzufügen');
\define('_AM_WGEVENTS_ADD_QUESTION', 'Neue Zusatzinfo hinzufügen');
\define('_AM_WGEVENTS_ADD_ANSWER', 'Neue Antwort hinzufügen');
\define('_AM_WGEVENTS_ADD_TEXTBLOCK', 'Neuen Textblock hinzufügen');
\define('_AM_WGEVENTS_ADD_FIELD', 'Neue Art Zusatzinfos hinzufügen');
\define('_AM_WGEVENTS_GOTO_FORMSELECT', 'Zurück zur Auswahl');
// Lists
\define('_AM_WGEVENTS_LIST_EVENTS', 'Liste der Veranstaltungen');
\define('_AM_WGEVENTS_LIST_CATEGORIES', 'Liste der Kategorien');
\define('_AM_WGEVENTS_LIST_REGISTRATIONS', 'Liste der Anmeldungen');
\define('_AM_WGEVENTS_LIST_QUESTIONS', 'Liste der Zusatzinfos');
\define('_AM_WGEVENTS_LIST_ANSWERS', 'Liste der Antworten');
\define('_AM_WGEVENTS_LIST_TEXTBLOCKS', 'Liste der Textblöcke');
\define('_AM_WGEVENTS_LIST_FIELDS', 'Liste der Arten Zusatzinfos');
\define('_AM_WGEVENTS_LIST_EVENTS_LAST', 'Liste der letzten %s Veranstaltungen');
// ---------------- Admin Classes ----------------
\define('_AM_WGEVENTS_HIST_ID', 'History Id');
\define('_AM_WGEVENTS_HIST_INFO', 'Info');
\define('_AM_WGEVENTS_HIST_DATECREATED', 'Datum Historie');
\define('_AM_WGEVENTS_HIST_SUBMITTER', 'Einsender Historie');
\define('_AM_WGEVENTS_REGISTRATIONHISTS_CURR', 'Historische Anmeldungen derzeit');
// Category add/edit
\define('_AM_WGEVENTS_CATEGORY_ADD', 'Kategorie Hinzufügen');
\define('_AM_WGEVENTS_CATEGORY_EDIT', 'Kategorie bearbeiten');
// Elements of Category
\define('_AM_WGEVENTS_CATEGORY_ID', 'Id');
\define('_AM_WGEVENTS_CATEGORY_IDENTIFIER', 'Präfix Eindeutige Kennung');
\define('_AM_WGEVENTS_CATEGORY_IDENTIFIER_DESC', 'Präfix für eindeutige Kennung angeben. Wenn du keine eindeutige Kennung verwenden möchtest dann bitte frei lassen.');
\define('_AM_WGEVENTS_CATEGORY_PID', 'übergeordnete Kategorie');
\define('_AM_WGEVENTS_CATEGORY_NAME', 'Name');
\define('_AM_WGEVENTS_CATEGORY_DESC', 'Beschreibung');
\define('_AM_WGEVENTS_CATEGORY_LOGO', 'Logo');
\define('_AM_WGEVENTS_CATEGORY_LOGO_UPLOADS', 'Logo in %s :');
\define('_AM_WGEVENTS_CATEGORY_IMAGE', 'Bild');
\define('_AM_WGEVENTS_CATEGORY_IMAGE_UPLOADS', 'Bilder in %s :');
\define('_AM_WGEVENTS_CATEGORY_COLOR', 'Schriftfarbe');
\define('_AM_WGEVENTS_CATEGORY_BORDERCOLOR', 'Rahmenfarbe');
\define('_AM_WGEVENTS_CATEGORY_BGCOLOR', 'Hintergrundfarbe');
\define('_AM_WGEVENTS_CATEGORY_OTHERCSS', 'Weitere css-Styles');
\define('_AM_WGEVENTS_CATEGORY_TYPE', 'Type of category');
\define('_AM_WGEVENTS_CATEGORY_TYPE_BOTH', 'Als Haupt- und Unterkategorie verwenden');
\define('_AM_WGEVENTS_CATEGORY_TYPE_MAIN', 'Nur als Hauptkategorie verwenden');
\define('_AM_WGEVENTS_CATEGORY_TYPE_SUB', 'Nur als Unterkategorie verwenden');
// Addtype add/edit
\define('_AM_WGEVENTS_FIELD_ADD', 'Art Zusatzinfo hinzufügen');
\define('_AM_WGEVENTS_FIELD_EDIT', 'Art Zusatzinfo bearbeiten');
// Elements of Addtype
\define('_AM_WGEVENTS_FIELD_ID', 'Id');
\define('_AM_WGEVENTS_FIELD_CAPTION', 'Beschriftung');
\define('_AM_WGEVENTS_FIELD_TYPE', 'Typ');
\define('_AM_WGEVENTS_FIELD_DESC', 'Beschreibung');
\define('_AM_WGEVENTS_FIELD_VALUE', 'Wert');
\define('_AM_WGEVENTS_FIELD_PLACEHOLDER', 'Platzhalter');
\define('_AM_WGEVENTS_FIELD_REQUIRED', 'Erforderlich');
\define('_AM_WGEVENTS_FIELD_DEFAULT', 'Standard');
\define('_AM_WGEVENTS_FIELD_DISPLAY_DESC', "Feld 'Beschreibung' anzeigen");
\define('_AM_WGEVENTS_FIELD_DISPLAY_VALUES', "Feld 'Wert' anzeigen");
\define('_AM_WGEVENTS_FIELD_DISPLAY_PLACEHOLDER', "Feld 'Platzhalter' anzeigen");
// Elements of default Addtype
\define('_AM_WGEVENTS_FIELD_CREATE_DEFAULT', 'Standardset erstellen');
\define('_AM_WGEVENTS_FIELD_SURE_DELETE', 'Achtung! Alle derzeit existierenden Arten Zusatzinfos werden gelöscht! Willst du wirklich fortsetzen?');
\define('_AM_WGEVENTS_FIELD_ADDRESS', 'Adresse');
\define('_AM_WGEVENTS_FIELD_ADDRESS_VALUE', 'Bitte Adresse eingeben');
\define('_AM_WGEVENTS_FIELD_AGE', 'Alter');
\define('_AM_WGEVENTS_FIELD_AGE_VALUE', 'Bitte Alter eingeben');
\define('_AM_WGEVENTS_FIELD_BIRTHDAY', 'Geburtstag');
\define('_AM_WGEVENTS_FIELD_BIRTHDAY_VALUE', 'Bitte Geburtstag eingeben');
\define('_AM_WGEVENTS_FIELD_PHONE', 'Telefonnummer');
\define('_AM_WGEVENTS_FIELD_PHONE_VALUE', 'Bitte Telefonnummer eingeben');
\define('_AM_WGEVENTS_FIELD_POSTAL', 'Postleitzahl');
\define('_AM_WGEVENTS_FIELD_POSTAL_VALUE', 'Bitte Postleitzahl eingeben');
\define('_AM_WGEVENTS_FIELD_CITY', 'Ort');
\define('_AM_WGEVENTS_FIELD_CITY_VALUE', 'Bitte Ort eingeben');
\define('_AM_WGEVENTS_FIELD_COUNTRY', 'Land');
\define('_AM_WGEVENTS_FIELD_COUNTRY_VALUE', 'Bitte Land eingeben');
// Log add/edit
\define('_AM_WGEVENTS_ADD_LOG', 'Log hinzufügen');
\define('_AM_WGEVENTS_EDIT_LOG', 'Log bearbeiten');
\define('_AM_WGEVENTS_LIST_LOGS', 'Liste der Log');
\define('_AM_WGEVENTS_DELETE_LOGS', 'Alle Log löschen');
// Elements of Log
\define('_AM_WGEVENTS_LOG_ID', 'Id');
\define('_AM_WGEVENTS_LOG_TEXT', 'Text');
//Buttons
\define('_AM_WGEVENTS_ADD_ACCOUNT', 'Sender-Konto hinzufügen');
\define('_AM_WGEVENTS_LIST_ACCOUNTS', 'Sender-Konten auflisten');
// Types of accounts
\define('_AM_WGEVENTS_ACCOUNT_TYPE_PHPMAIL', 'php mail()');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_PHPSENDMAIL', 'php sendmail()');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_POP3', 'POP3');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_SMTP', 'Imap');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_GMAIL', 'Gmail');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_NOTREQUIRED', 'Nicht erforderlich');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_NAME', 'Mein Kontoname');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_YOURNAME', 'Max Mustermann');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_YOUREMAIL', 'mustermann@yourdomain.com');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_USERNAME', 'Benutzername');
\define('_AM_WGEVENTS_ACCOUNT_TYPE_PWD', 'Passwort');
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
\define('_AM_WGEVENTS_ACCOUNT_TYPE_CHECK', 'Überprüfung der Einstellungen');
// Elements of accounts
\define('_AM_WGEVENTS_ACCOUNT_ADD', 'E-Mail-Konto hinzufügen');
\define('_AM_WGEVENTS_ACCOUNT_EDIT', 'E-Mail-Konto bearbeiten');
\define('_AM_WGEVENTS_ACCOUNT_DELETE', 'E-Mail-Konto löschen');
\define('_AM_WGEVENTS_ACCOUNT_ID', 'Id');
\define('_AM_WGEVENTS_ACCOUNT_TYPE', 'Typ');
\define('_AM_WGEVENTS_ACCOUNT_NAME', 'Name');
\define('_AM_WGEVENTS_ACCOUNT_YOURNAME', 'Anzeigename');
\define('_AM_WGEVENTS_ACCOUNT_YOURMAIL', 'E-Mail-Adresse');
\define('_AM_WGEVENTS_ACCOUNT_USERNAME', 'Benutzername');
\define('_AM_WGEVENTS_ACCOUNT_PASSWORD', 'Passwort');
\define('_AM_WGEVENTS_ACCOUNT_INCOMING', 'Eingangsserver');
\define('_AM_WGEVENTS_ACCOUNT_SERVER_IN', 'Mailserver');
\define('_AM_WGEVENTS_ACCOUNT_PORT_IN', 'Port');
\define('_AM_WGEVENTS_ACCOUNT_SECURETYPE_IN', 'Sicherheitstyp');
\define('_AM_WGEVENTS_ACCOUNT_OUTGOING', 'Ausgangsserver');
\define('_AM_WGEVENTS_ACCOUNT_SERVER_OUT', 'Mailserver');
\define('_AM_WGEVENTS_ACCOUNT_PORT_OUT', 'Port');
\define('_AM_WGEVENTS_ACCOUNT_SECURETYPE_OUT', 'Sicherheitstyp');
\define('_AM_WGEVENTS_ACCOUNT_LIMIT_HOUR', 'Limit Mails pro Stunde');
\define('_AM_WGEVENTS_ACCOUNT_LIMIT_HOUR_DESC', 'Definiere das Limit an Mails, die pro Stunde gesendet werden sollen (0 bedeutet kein Limit)');
\define('_AM_WGEVENTS_ACCOUNT_PRIMARY', 'Primäres E-Mail-Konto');
\define('_AM_WGEVENTS_ACCOUNT_ERROR_OPEN_MAILBOX', 'Fehler beim Öffnen der Mailbox! Bitte Einstellungen überprüfen!');
\define('_AM_WGEVENTS_SAVE_AND_CHECK', 'Speichern und Einstellungen überprüfen');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_OK', 'erfolgreich  ');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_FAILED', 'fehlgeschlagen  ');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_SKIPPED', 'übersprungen ');
\define('_AM_WGEVENTS_ACCOUNT_CHECK', 'Check');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_RESULT', 'Ergebnis Überprüfung');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_INFO', 'Zusätzliche Infos');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_OPEN_MAILBOX', 'Öffnen Mailbox ');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_LIST_FOLDERS', 'Verzeichnisliste einlesen');
\define('_AM_WGEVENTS_ACCOUNT_CHECK_SENDTEST', 'Senden Test-Email');
// maintenance
\define('_AM_WGEVENTS_MAINTENANCE_TYP', 'Art der Wartung');
\define('_AM_WGEVENTS_MAINTENANCE_DESC', 'Beschreibung der Wartung');
\define('_AM_WGEVENTS_MAINTENANCE_RESULTS', 'Wartungsergebnis');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_QUE', 'Überprüfung Tabelle Fragen');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_QUE_DESC', 'Überprüfung der Tabelle Fragen und Suche nach Einträgen ohne Verweis auf eine gültige Veranstaltung');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_ANSWERS', 'Überprüfung Tabelle Antworten');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_ANSWERS_DESC', 'Überprüfung der Tabelle Antworten/Antworten Historie und Suche nach Einträgen mit Verweis auf eine nicht mehr existierende Frage oder mit Verweis auf eine nicht mehr existierende Anmeldung');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_REG', 'Überprüfung Tabelle Anmeldungen');
\define('_AM_WGEVENTS_MAINTENANCE_INVALID_REG_DESC', 'Überprüfung der Tabelle Anmeldungen und Suche nach Einträgen ohne Verweis auf eine gültige Veranstaltung');
\define('_AM_WGEVENTS_MAINTENANCE_CHECKTABLE_SUCCESS', 'Überprüfung der Tabelle erfolgreich beendet');
\define('_AM_WGEVENTS_MAINTENANCE_ANON_DATA', 'Anonymisieren alte Registrierungsdaten');
\define('_AM_WGEVENTS_MAINTENANCE_ANON_DATA_DESC', 'Zur Wahrung der Vorgaben der Datenschutzbestimmungen sind Daten zu Veranstaltungen, die nicht mehr benötigt werden, auch wieder zu löschen.<br>
Durch diese Aktion wird folgendes durchgeführt:
<ul>
<li>Die Daten Vorname, Familienname und Email werden mit * anonymisiert</li>
<li>Die Antworten zu den Fragen aus dem Anmeldeformularen werden gelöscht</li>
<li>Die Datenhistorie zu den Anmeldungen und Antworten wird gelöscht (sofern diese Funktion verwendet wurde)</li>
</ul>
Bitte definiere das Datum, bis zu dem alle Daten anonymisiert werden sollen.');
\define('_AM_WGEVENTS_MAINTENANCE_ANON_DATA_DATELIMIT', 'Bereinigen bis einschließlich:');
\define('_AM_WGEVENTS_MAINTENANCE_ANON_DATA_SUCCESS', 'Bereinigung der Daten erfolgreich beendet');
\define('_AM_WGEVENTS_MAINTENANCE_BACK', 'Zurück zur Wartungsübersicht');
// General
\define('_AM_WGEVENTS_FORM_UPLOAD', 'Datei hochladen');
\define('_AM_WGEVENTS_FORM_UPLOAD_NEW', 'Neue Datei hochladen: ');
\define('_AM_WGEVENTS_FORM_UPLOAD_SIZE', 'Maximale Dateigröße: ');
\define('_AM_WGEVENTS_FORM_UPLOAD_SIZE_MB', 'MB');
\define('_AM_WGEVENTS_FORM_UPLOAD_IMG_WIDTH', 'Maximale Bildbreite: ');
\define('_AM_WGEVENTS_FORM_UPLOAD_IMG_HEIGHT', 'Maximale Bildhöhe: ');
\define('_AM_WGEVENTS_FORM_IMAGE_PATH', 'Dateien in %s :');
\define('_AM_WGEVENTS_FORM_EDIT', 'Bearbeitung');
\define('_AM_WGEVENTS_FORM_DELETE', 'Löschen');
\define('_AM_WGEVENTS_FORM_SURE_DELETE_ALL', 'Alle Daten löschen aus: %s');
// Clone feature
\define('_AM_WGEVENTS_CLONE', 'Klonen');
\define('_AM_WGEVENTS_CLONE_DSC', 'Ein Modul zu klonen war noch nie so einfach! Geben Sie einfach den Namen den Sie wollen und Knopf drücken!');
\define('_AM_WGEVENTS_CLONE_TITLE', 'Klone %s');
\define('_AM_WGEVENTS_CLONE_NAME', 'Wählen Sie einen Namen für das neue Modul');
\define('_AM_WGEVENTS_CLONE_NAME_DSC', 'Verwenden Sie keine Sonderzeichen ! <br> Wählen Sie bitte kein vorhandenes Modul Modul Verzeichnisname  oder Datenbank-Tabellenname!');
\define('_AM_WGEVENTS_CLONE_INVALIDNAME', 'FEHLER: Ungültige Modulnamen, bitte versuchen Sie einen anderen!');
\define('_AM_WGEVENTS_CLONE_EXISTS', 'FEHLER: Modulnamen bereits benutzt, bitte versuchen Sie einen anderen!');
\define('_AM_WGEVENTS_CLONE_CONGRAT', 'Herzliche Glückwünsche! %s wurde erfolgreich erstellt! <br /> Sie können Änderungen in Sprachdateien machen.');
\define('_AM_WGEVENTS_CLONE_IMAGEFAIL', 'Achtung, wir haben es nicht geschafft, das neue Modul-Logo zu erstellen. Bitte beachten Sie assets / images / logo_module.png manuell zu modifizieren!');
\define('_AM_WGEVENTS_CLONE_FAIL', 'Leider konnten wir den neuen Klon nicht erstellen . Vielleicht müssen Sie die Schreibrechte von \'modules\' Verzeichnis auf  (CHMOD 777) festlegen und neu versuchen.');
// General
// ---------------- Admin Permission ----------------
// Permission for forms (categories)
\define('_AM_WGEVENTS_PERMISSIONS_APPROVE', 'Berechtigung zur Freigabe');
\define('_AM_WGEVENTS_PERMISSIONS_SUBMIT', 'Berechtigung zum Senden');
\define('_AM_WGEVENTS_PERMISSIONS_VIEW', 'Berechtigung zum Ansehen');
// Permission for tab permissions
\define('_AM_WGEVENTS_PERMISSIONS_DESC', 'Die Berechtigung sind wie folgt zu verstehen:<br>');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL', 'Globale Berechtigung');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_DESC', '1) Globale Berechtigungen sollten nur an Webmaster vergeben werden<br>');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_VIEW', 'Globale Berechtigung zum Ansehen');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_SUBMIT', 'Globale Berechtigung zum Senden');
\define('_AM_WGEVENTS_PERMISSIONS_GLOBAL_APPROVE', 'Globale Berechtigung zur Freigabe');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE', 'Berechtigung zum Verwalten von Veranstaltungen');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_DESC', '2) Berechtigung zum Verwalten von Veranstaltungen beinhaltet:
<ul>
<li>Berechtigung zur Freigabe aller Veranstaltungen</li>
<li>Berechtigung zum Bearbeiten aller Veranstaltungen</li>
<li>Berechtigung zur Verwaltung der Zusatzinfos zu Anmeldungen für alle Veranstaltungen</li>
<li>Berechtigung zur Verwaltung aller Anmeldungen für alle Veranstaltungen</li>
</ul>
');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_AUTO', 'Berechtigung zur Erstellung freigegebener Veranstaltungen');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_AUTO_DESC', '3) Berechtigung zur Erstellung freigegebener Veranstaltungen: Veranstaltungen, die durch diese Gruppe erstellt werden, werden automatisch freigegeben<br>');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_SUBMIT', 'Berechtigung zum Senden Veranstaltungen');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_SUBMIT_DESC', '4) Berechtigung zum Senden von Veranstaltungen beinhaltet:
<ul>
<li>Berechtigung zum Erstellen von Veranstaltungen</li>
<li>Berechtigung zum Bearbeiten der eigenen Veranstaltungen</li>
<li>Berechtigung zur Verwaltung der Zusatzinfos zu Anmeldungen für eigene Veranstaltungen</li>
<li>Berechtigung zur Verwaltung der Anmeldungen für eigene Veranstaltungen</li>
<li>Berechtigung zur Erstellung und Verwaltung eigener Textblöcke</li>
</ul>
');
\define('_AM_WGEVENTS_PERMISSIONS_EVENTS_VIEW', 'Berechtigung zum Ansehen Veranstaltungen');
//\define('_AM_WGEVENTS_PERMISSIONS_QUESTIONS_VIEW', 'Berechtigung zum Ansehen Zusatzinfos');
//\define('_AM_WGEVENTS_PERMISSIONS_QUESTIONS_SUBMIT', 'Berechtigung zum Senden Zusatzinfos');
//\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_VIEW', 'Berechtigung zum Ansehen Anmeldungen');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_AUTOVERIF', 'Berechtigung zum Senden Anmeldungen ohne zusätzliche Verifizierung');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_AUTOVERIF_DESC', '5) Berechtigung zum Senden Anmeldungen ohne zusätzliche Verifizierung: Anmeldungen, die durch diese Gruppe eingesendet werden, gelten automatisch als verifiziert. Ein zusätzliche Bestätigung per Mail ist nicht erforderlich<br>');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_SUBMIT', 'Berechtigung zum Senden Anmeldungen');
\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_SUBMIT_DESC', '6) Berechtigung zum Senden Anmeldungen: Anmeldungen, die durch diese Gruppe eingesendet werden, müssen zusätzliche über einen Bestätigungslink, der per Mail zugeschickt wird, bestätigt werden<br>');
//\define('_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_APPROVE', 'Berechtigung zur Freigabe von Anmeldungen');
//\define('_AM_WGEVENTS_PERMISSIONS_TEXTBLOCKS_VIEW', 'Berechtigung zum Ansehen Textblöcke');
//\define('_AM_WGEVENTS_PERMISSIONS_TEXTBLOCKS_SUBMIT', 'Berechtigung zum Senden Textblöcke');
/*
\define('_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_VIEW', 'Berechtigung zum Ansehen Veranstaltungen dieser Kategorie');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_SUBMIT', 'Berechtigung zum Senden Veranstaltungen dieser Kategorie');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_EVENTS_APPROVE', 'Berechtigung zur Freigabe Veranstaltungen dieser Kategorie');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_REGS_VIEW', 'Berechtigung zum Ansehen der Registrierungen von Veranstaltungen dieser Kategorie');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_REGS_SUBMIT', 'Berechtigung zum Senden von Registrierungen von Veranstaltungen dieser Kategorie');
\define('_AM_WGEVENTS_PERMISSIONS_CAT_REGS_APPROVE', 'Berechtigung zur Freigabe von Registrierungen von Veranstaltungen dieser Kategorie');
*/
\define('_AM_WGEVENTS_NO_PERMISSIONS_SET', 'Keine Berechtigungen vorhanden');
//Change yes/no
\define('_AM_WGEVENTS_SETON', 'AUS, auf AN ändern');
\define('_AM_WGEVENTS_SETOFF', 'AN, auf AUS ändern');
//tablesorter
\define('_AM_WGEVENTS_TABLESORTER_SHOW_ALL', 'Alle anzeigen');
\define('_AM_WGEVENTS_TABLESORTER_OF', 'von');
\define('_AM_WGEVENTS_TABLESORTER_TOTALROWS', 'Reihen gesamt');
// ---------------- Admin Others ----------------
\define('_AM_WGEVENTS_ABOUT_MAKE_DONATION', 'Senden');
\define('_AM_WGEVENTS_SUPPORT_FORUM', 'Support Forum');
\define('_AM_WGEVENTS_DONATION_AMOUNT', 'Spendenbetrag');
\define('_AM_WGEVENTS_MAINTAINEDBY', ' wird unterstützt von ');
// Task add/edit
\define('_AM_WGEVENTS_ADD_TASK', 'Aufgabe hinzufügen');
\define('_AM_WGEVENTS_EDIT_TASK', 'Aufgabe bearbeiten');
\define('_AM_WGEVENTS_LIST_TASKS', 'Liste der Aufgaben');
\define('_AM_WGEVENTS_DELETE_TASKS_DONE', 'Erledigte Aufgaben löschen');
\define('_AM_WGEVENTS_DELETE_TASKS_PENDING', 'Wartende Aufgaben löschen');
// Elements of Task
\define('_AM_WGEVENTS_TASK_ID', 'Id');
\define('_AM_WGEVENTS_TASK_TYPE', 'Typ');
\define('_AM_WGEVENTS_TASK_PARAMS', 'Parameter');
\define('_AM_WGEVENTS_TASK_RECIPIENT', 'Empfänger');
\define('_AM_WGEVENTS_TASK_DATEDONE', 'Datum erledigt');
// Import
\define('_AM_WGEVENTS_IMPORT_MODULES', 'Verfügbare Importroutinen');
\define('_AM_WGEVENTS_IMPORT_NOTINSTALLED', 'Modul ist nicht installiert');
\define('_AM_WGEVENTS_IMPORT_SHOWFORM', 'Formular anzeigen');
\define('_AM_WGEVENTS_IMPORT_ATTENTION', 'Achtung');
\define('_AM_WGEVENTS_IMPORT_EXEC', 'Import ausführen');
\define('_AM_WGEVENTS_IMPORT_RESULT', 'Ergebnisse Import');
\define('_AM_WGEVENTS_IMPORT_RESULT_CATS', 'Importierte Kategorien');
\define('_AM_WGEVENTS_IMPORT_RESULT_EVENTS', 'Importierte Events');
\define('_AM_WGEVENTS_IMPORT_RESULT_OF', '%s von %s');
\define('_AM_WGEVENTS_IMPORT_DELETE', 'Alle existierenden Daten werden gelöscht');
\define('_AM_WGEVENTS_IMPORT_NORECCUR', 'Wiederkehrende Events werden derzeit nicht importiert');
\define('_AM_WGEVENTS_IMPORT_NOPERM', 'Berechtigungen werden derzeit nicht importiert');
\define('_AM_WGEVENTS_IMPORT_DATEFROM', 'Importiere Events von');
\define('_AM_WGEVENTS_IMPORT_DATETO', 'Importiere Events bis');
\define('_AM_WGEVENTS_IMPORT_CATS', 'Importiere Kategorien');
\define('_AM_WGEVENTS_IMPORT_APCAL', 'Importiere APCal');
\define('_AM_WGEVENTS_IMPORT_EXTCAL', 'Importiere ExtCal');
\define('_AM_WGEVENTS_IMPORT_DELETE_ORIGIN', 'Möchtest Du die Einträge nach einem erfolgreichen Import aus der Quelltabelle löschen');
//1.0.7
\define('_AM_WGEVENTS_THEREARE_TASKS_PENDING', "Es sind <span class='bold'>%s</span> wartende Aufgaben vorhanden");
\define('_AM_WGEVENTS_THEREARE_TASKS_PROCESSING', "Es sind <span class='bold'>%s</span> Aufgaben zur Verarbeitung vorhanden");
\define('_AM_WGEVENTS_THEREARE_TASKS_DONE', "Es sind <span class='bold'>%s</span> erledigte Aufgaben vorhanden");
