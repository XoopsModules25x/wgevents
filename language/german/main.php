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

require_once __DIR__ . '/admin.php';

// ---------------- Main ----------------
\define('_MA_WGEVENTS_INDEX', 'Übersicht Veranstaltungen');
\define('_MA_WGEVENTS_TITLE', 'Veranstaltungen');
\define('_MA_WGEVENTS_DESC', 'This module is for doing following...');
\define('_MA_WGEVENTS_INDEX_DESC', 'Welcome to the homepage of your new module wgEvents!<br>This description is only visible on the homepage of this module.');
\define('_MA_WGEVENTS_NO_PDF_LIBRARY', 'Libraries TCPDF not there yet, upload them in root/Frameworks');
\define('_MA_WGEVENTS_NO', 'Nein');
\define('_MA_WGEVENTS_DETAILS', 'Details anzeigen');
\define('_MA_WGEVENTS_BROKEN', 'Als defekt melden');
\define('_MA_WGEVENTS_DATECREATED', 'Erstelldatum');
\define('_MA_WGEVENTS_SUBMITTER', 'Einsender');
\define('_MA_WGEVENTS_WEIGHT', 'Reihung');
\define('_MA_WGEVENTS_ACTION', 'Aktion');
\define('_MA_WGEVENTS_INDEX_THEREARE', 'Es gibt %s Veranstaltungen');
\define('_MA_WGEVENTS_INDEX_THEREARENT_EVENTS', 'Es gibt keine Veranstaltungen');
\define('_MA_WGEVENTS_INDEX_THEREARENT_CATS', 'Es gibt keine Kategorien');
\define('_MA_WGEVENTS_INDEX_LATEST_LIST', 'Letzte Veranstaltungen');
\define('_MA_WGEVENTS_READMORE', 'Mehr lesen');
\define('_MA_WGEVENTS_READLESS', 'Weniger lesen');
// Status
\define('_MA_WGEVENTS_STATUS', 'Status');
\define('_MA_WGEVENTS_STATUS_NONE', 'Kein Status');
\define('_MA_WGEVENTS_STATUS_OFFLINE', 'Offline');
\define('_MA_WGEVENTS_STATUS_ONLINE', 'Online');
\define('_MA_WGEVENTS_STATUS_SUBMITTED', 'Eingesendet');
\define('_MA_WGEVENTS_STATUS_VERIFIED', 'Verifiziert');
\define('_MA_WGEVENTS_STATUS_APPROVED', 'Bestätigt');
\define('_MA_WGEVENTS_STATUS_LOCKED', 'Gesperrt');
\define('_MA_WGEVENTS_STATUS_CANCELED', 'Abgesagt');
// ---------------- Contents ----------------
// Event
\define('_MA_WGEVENTS_EVENT', 'Veranstaltung');
\define('_MA_WGEVENTS_EVENT_DETAILS', 'Details der Veranstaltung');
\define('_MA_WGEVENTS_EVENT_ADD', 'Veranstaltung hinzufügen');
\define('_MA_WGEVENTS_EVENT_EDIT', 'Veranstaltung bearbeiten');
\define('_MA_WGEVENTS_EVENT_DELETE', 'Veranstaltung löschen');
\define('_MA_WGEVENTS_EVENT_DELETE_ERR', 'Das Löschen der Veranstaltung ist nicht mehr zulässig, da bereits Anmeldungen vorliegen!<br>Bitte zuerst die bestehenden Anmeldungen löschen');
\define('_MA_WGEVENTS_EVENT_CLONE', 'Veranstaltung klonen');
\define('_MA_WGEVENTS_EVENT_CANCEL', 'Veranstaltung absagen');
\define('_MA_WGEVENTS_EVENT_SELECT', 'Veranstaltung auswählen');
\define('_MA_WGEVENTS_EVENTS', 'Veranstaltungen');
\define('_MA_WGEVENTS_EVENTS_LIST', 'Liste der Veranstaltungen');
\define('_MA_WGEVENTS_EVENTS_TITLE', 'Veranstaltungen Titel');
\define('_MA_WGEVENTS_EVENTS_DESC', 'Veranstaltungen Beschreibung');
\define('_MA_WGEVENTS_EVENTS_LISTPAST', 'Vergangene Veranstaltungen');
\define('_MA_WGEVENTS_EVENTS_LISTCOMING', 'Kommende Veranstaltungen');
// Caption of Event
\define('_MA_WGEVENTS_EVENT_ID', 'Id');
\define('_MA_WGEVENTS_EVENT_CATID', 'Kategorie Id');
\define('_MA_WGEVENTS_EVENT_NAME', 'Name');
\define('_MA_WGEVENTS_EVENT_LOGO', 'Logo');
\define('_MA_WGEVENTS_EVENT_LOGO_UPLOADS', 'Logos in deinem Verzeichnis:');
\define('_MA_WGEVENTS_EVENT_DESC', 'Beschreibung');
\define('_MA_WGEVENTS_EVENT_DATEFROM', 'Datum von');
\define('_MA_WGEVENTS_EVENT_DATETO', 'Datum bis');
\define('_MA_WGEVENTS_EVENT_CONTACT', 'Kontakt');
\define('_MA_WGEVENTS_EVENT_EMAIL', 'E-Mail');
\define('_MA_WGEVENTS_EVENT_EMAIL_SENDTO', 'E-Mail an der Veranstalter senden');
\define('_MA_WGEVENTS_EVENT_EMAIL_SENDREQUEST', 'Anfrage zur Veranstaltung: ');
\define('_MA_WGEVENTS_EVENT_LOCATION', 'Ort');
\define('_MA_WGEVENTS_EVENT_LOCGMLAT', 'Ort Latitude');
\define('_MA_WGEVENTS_EVENT_LOCGMLON', 'Ort Longitude');
\define('_MA_WGEVENTS_EVENT_LOCGMZOOM', 'Zoom-Faktor');

\define('_MA_WGEVENTS_EVENT_FEE', 'Gebühr');
\define('_MA_WGEVENTS_EVENT_REGISTER_USE', 'Anmeldesystem verwenden');
\define('_MA_WGEVENTS_EVENT_REGISTER_FROM', 'Anmeldung vom');
\define('_MA_WGEVENTS_EVENT_REGISTER_TO', 'Anmeldung bis');
\define('_MA_WGEVENTS_EVENT_REGISTER_MAX', 'Maximale Teilnehmeranzahl');
\define('_MA_WGEVENTS_EVENT_REGISTER_MAX_DESC', '0 bedeutet kein Limit');
\define('_MA_WGEVENTS_EVENT_REGISTER_MAX_UNLIMITED', 'unlimitiert');
\define('_MA_WGEVENTS_EVENT_REGISTER_LISTWAIT', 'Wartelisten verwenden');
\define('_MA_WGEVENTS_EVENT_REGISTER_LISTWAIT_DESC', 'Wenn Du diese Funkion verwendest, dann werden angemeldete Personen nach Erreichen der Maximalteilnehmerzahl automatisch auf eine Warteliste gesetzt');
\define('_MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT', 'Automatische Annahme');
\define('_MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT_DESC', 'Wenn Du diese Funkion verwendest, dann werden alle Anmeldungen automatisch als bestätigt angenommen. Bei Nein muss jede Anmeldung separat bestätigt werden.');
\define('_MA_WGEVENTS_EVENT_REGISTER_NOTIFY', 'Benachrichtigung');
\define('_MA_WGEVENTS_EVENT_REGISTER_NOTIFY_DESC', 'Bitte die E-Mail-Adresse(n) eingeben, welche über neue Anmeldungen und Änderungen informiert werden sollen.&#013;&#010;Bitte für jede E-Mail-Adrresse eine neue Zeile verwenden.');
\define('_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL', 'Mail-Adresse Absender');
\define('_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL_DESC', 'Mail-Adresse Absender für Bestätigungsmails');
\define('_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL_ERR', 'Im Falle der Verwendung der Registrierung muss die Mail-Adresse Absender für Bestätigungsmails befüllt sein!');
\define('_MA_WGEVENTS_EVENT_REGISTER_SENDERNAME', 'Name Absender');
\define('_MA_WGEVENTS_EVENT_REGISTER_SENDERNAME_DESC', 'Anzuzeigender Name des Absenders für Bestätigungsmails');
\define('_MA_WGEVENTS_EVENT_REGISTER_SIGNATURE', 'Signatur');
\define('_MA_WGEVENTS_EVENT_REGISTER_SIGNATURE_DESC', 'Bitte Signatur eingeben, die für Bestätigungsmails verwendet werden soll');
\define('_MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF', 'Mailbestätigung verlangen');
\define('_MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF_DESC', 'Wenn Du diese Option auswählst, dann muss bei der Anmeldung eine E-Mail-Adresse eingegeben werden und der Anmelder erhält eine Mail mit Bestätigungscode, der zurückgesendet werden muss.');
\define('_MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF_INFO', 'Der Veranstalter verlangt eine Mailbestätigung, daher ist die Angabe einer E-Mail-Adresse zwingend erforderlich. Du erhälst nach jeder Anmeldung eine E-Mail mit einem Link, mit dem Du Deine Anmeldung bestätigen musst.');
\define('_MA_WGEVENTS_EVENT_GALID', 'Gallerie Id');
\define('_MA_WGEVENTS_EVENT_GM_SHOW', 'Ort auf Karte anzeigen');
\define('_MA_WGEVENTS_EVENT_GM_GETCOORDS', 'Koordinaten ermitteln');
\define('_MA_WGEVENTS_EVENT_GM_APPLYCOORDS', 'Koordinaten übernehmen');
// Category
\define('_MA_WGEVENTS_CATEGORY_LOGO', 'Logo');
\define('_MA_WGEVENTS_CATEGORY_NOEVENTS', 'Keine Veranstaltungen verfügbar');
\define('_MA_WGEVENTS_CATEGORY_EVENT', '1 Veranstaltung');
\define('_MA_WGEVENTS_CATEGORY_EVENTS', '%s Veranstaltungen');
// Registration
\define('_MA_WGEVENTS_REGISTRATION', 'Anmeldung');
\define('_MA_WGEVENTS_REGISTRATION_DETAILS', 'Details Anmeldung');
\define('_MA_WGEVENTS_REGISTRATION_ADD', 'Anmeldung hinzufügen');
\define('_MA_WGEVENTS_REGISTRATION_EDIT', 'Anmeldung bearbeiten');
\define('_MA_WGEVENTS_REGISTRATION_DELETE', 'Anmeldung löschen');
\define('_MA_WGEVENTS_REGISTRATION_CLONE', 'Anmeldung duplizieren');
\define('_MA_WGEVENTS_REGISTRATION_GOTO', 'Zur Anmeldung');
\define('_MA_WGEVENTS_REGISTRATIONS', 'Anmeldungen');
\define('_MA_WGEVENTS_REGISTRATIONS_LIST', 'Liste der Anmeldungen');
\define('_MA_WGEVENTS_REGISTRATIONS_TITLE', 'Anmeldungstitel');
\define('_MA_WGEVENTS_REGISTRATIONS_DESC', 'Anmeldungsbeschreibung');
\define('_MA_WGEVENTS_REGISTRATIONS_MYLIST', 'Liste meiner Anmeldungen');
\define('_MA_WGEVENTS_REGISTRATIONS_THEREARENT', 'Es sind keine Anmeldungen für den aktuellen Benutzer bzw. zu der aktuellen IP-Adresse vorhanden');
\define('_MA_WGEVENTS_REGISTRATIONS_CURR', 'Anmeldungen derzeit');
\define('_MA_WGEVENTS_REGISTRATIONS_NBCURR_0', 'Derzeit liegen keine Anmeldungen vor');
\define('_MA_WGEVENTS_REGISTRATIONS_NBCURR', '%s von %s verfügbaren Plätzen bereits belegt');
\define('_MA_WGEVENTS_REGISTRATIONS_NBCURR_INDEX', '%s von %s belegt');
\define('_MA_WGEVENTS_REGISTRATIONS_FULL', 'ausgebucht');
\define('_MA_WGEVENTS_REGISTRATIONS_FULL_LISTWAIT', 'Anmeldung für Warteliste möglich');
\define('_MA_WGEVENTS_REGISTRATION_TOEARLY', 'Entschuldigung, aber die Anmeldung ist erst ab %s möglich');
\define('_MA_WGEVENTS_REGISTRATION_TOLATE', 'Entschuldigung, aber die Anmeldung ist seit %s nicht mehr möglich');
// Caption of Registration
\define('_MA_WGEVENTS_REGISTRATION_ID', 'Id');
\define('_MA_WGEVENTS_REGISTRATION_EVID', 'Veranstaltung');
\define('_MA_WGEVENTS_REGISTRATION_SALUTATION', 'Anrede');
\define('_MA_WGEVENTS_REGISTRATION_SALUTATION_MEN', 'Herr');
\define('_MA_WGEVENTS_REGISTRATION_SALUTATION_WOMEN', 'Frau');
\define('_MA_WGEVENTS_REGISTRATION_FIRSTNAME', 'Vorname');
\define('_MA_WGEVENTS_REGISTRATION_FIRSTNAME_PLACEHOLDER', 'Bitte Vorname eingeben');
\define('_MA_WGEVENTS_REGISTRATION_LASTNAME', 'Familienname');
\define('_MA_WGEVENTS_REGISTRATION_LASTNAME_PLACEHOLDER', 'Bitte Familienname eingeben');
\define('_MA_WGEVENTS_REGISTRATION_EMAIL', 'E-Mail');
\define('_MA_WGEVENTS_REGISTRATION_EMAIL_PLACEHOLDER', 'Bitte E-Mail eingeben');
\define('_MA_WGEVENTS_REGISTRATION_EMAIL_CONFIRM', 'Bestätigung oder Informationen über Änderungen per Mail senden?');
\define('_MA_WGEVENTS_REGISTRATION_GDPR', 'Datenschutz');
\define('_MA_WGEVENTS_REGISTRATION_GDPR_VALUE', 'Die Daten werden nur zum Zwecke der Abwicklung der Veranstaltung gespeichert.
Die Daten werden nicht an Dritte weitergegeben.
Die Daten werden ein halbes Jahr nach der Veranstaltung gelöscht.
Während der Veranstaltung wird fotografiert, und die Bilder können auch in unseren öffentlichen Medien verwendet werden.
Eine Teilnahme an der Veranstaltung ohne Zustimmung zu diesen Bestimmungen ist leider nicht möglich.
');
\define('_MA_WGEVENTS_REGISTRATION_IP', 'Ip-Adresse');
\define('_MA_WGEVENTS_REGISTRATION_FINANCIAL', 'Zahlungsstatus');
\define('_MA_WGEVENTS_REGISTRATION_FINANCIAL_UNPAID', 'Unbezahlt');
\define('_MA_WGEVENTS_REGISTRATION_FINANCIAL_PAID', 'Bezahlt');
\define('_MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_0','Status auf unbezahlt ändern');
\define('_MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_1','Status auf bezahlt ändern');
\define('_MA_WGEVENTS_REGISTRATION_LISTWAIT', 'Warteliste');
\define('_MA_WGEVENTS_REGISTRATION_LISTWAIT_TAKEOVER', 'Von Warteliste übernehmen');
\define('_MA_WGEVENTS_REGISTRATION_LISTWAIT_Y', 'Auf Warteliste');
\define('_MA_WGEVENTS_REGISTRATION_LISTWAIT_N', 'Auf endgültiger Teilnehmerliste');
\define('_MA_WGEVENTS_REGISTRATION_VERIFKEY', 'Verifizierungsschlüssel');
// Question
\define('_MA_WGEVENTS_QUESTION', 'Fragen');
\define('_MA_WGEVENTS_QUESTION_ADD', 'Frage hinzufügen');
\define('_MA_WGEVENTS_QUESTION_EDIT', 'Frage bearbeiten');
\define('_MA_WGEVENTS_QUESTION_DELETE', 'Frage löschen');
\define('_MA_WGEVENTS_QUESTION_CLONE', 'Frage duplizieren');
\define('_MA_WGEVENTS_QUESTIONS', 'Fragen für Anmeldung');
\define('_MA_WGEVENTS_QUESTIONS_LIST', 'Liste der Fragen');
\define('_MA_WGEVENTS_QUESTIONS_TITLE', 'Frage Titel');
\define('_MA_WGEVENTS_QUESTIONS_DESC', 'Frage Beschreibung');
\define('_MA_WGEVENTS_QUESTIONS_CREATE', 'Fragen erstellen');
\define('_MA_WGEVENTS_QUESTIONS_PREVIEW', 'Vorschau Anmeldeformular anzeigen');
\define('_MA_WGEVENTS_QUESTIONS_CURR', 'Anzahl der aktuellen Fragen');
// Caption of Question
\define('_MA_WGEVENTS_QUESTION_ID', 'Id');
\define('_MA_WGEVENTS_QUESTION_EVID', 'Veranstaltung');
\define('_MA_WGEVENTS_QUESTION_TYPE', 'Typ');
\define('_MA_WGEVENTS_QUESTION_CAPTION', 'Beschriftung');
\define('_MA_WGEVENTS_QUESTION_CAPTION_DESC', 'Die Beschriftung wird als Feldbezeichnung und als Spaltenüberschrift bei den Ausgabetabellen verwendet');
\define('_MA_WGEVENTS_QUESTION_DESC', 'Beschreibung');
\define('_MA_WGEVENTS_QUESTION_DESC_DESC', 'Die Beschreibung wird beim Feld als Erläuterung angezeigt');
\define('_MA_WGEVENTS_QUESTION_VALUE', 'Werte');
\define('_MA_WGEVENTS_QUESTION_PLACEHOLDER', 'Platzhalter');
\define('_MA_WGEVENTS_QUESTION_PLACEHOLDER_DESC', 'Platzhalter werden in Texteingabefelder als Info angezeigt');
\define('_MA_WGEVENTS_QUESTION_VALUE_DESC', 'Bitte die zulässigen Werte für das ausgewählte Formularfeld eingeben. Bei Auswahl- und Kombinationsfeldern für jede Option eine neue Zeile verwenden');
\define('_MA_WGEVENTS_QUESTION_REQUIRED', 'Erforderlich');
\define('_MA_WGEVENTS_QUESTION_REQUIRED_DESC', 'Wenn erforderlich auf Ja gesetzt wird, dann muss dieses Feld vom Anmelder befüllt werden');
\define('_MA_WGEVENTS_QUESTION_PRINT', 'Drucken');
\define('_MA_WGEVENTS_QUESTION_PRINT_DESC', 'Dieses Feld in den Listen anzeigen/drucken?<br>Nein bedeutet, dass dieses Feld nur bei der Anmeldemaske angezeigt wird.');
// Answer
\define('_MA_WGEVENTS_ANSWER', 'Antwort');
\define('_MA_WGEVENTS_ANSWER_ADD', 'Antwort hinzufügen');
\define('_MA_WGEVENTS_ANSWER_EDIT', 'Antwort bearbeiten');
\define('_MA_WGEVENTS_ANSWER_DELETE', 'Antwort löschen');
\define('_MA_WGEVENTS_ANSWER_CLONE', 'Antwort duplizieren');
\define('_MA_WGEVENTS_ANSWERS', 'Antworten');
\define('_MA_WGEVENTS_ANSWERS_LIST', 'Liste der Antworten');
\define('_MA_WGEVENTS_ANSWERS_TITLE', 'Antwort Titel');
\define('_MA_WGEVENTS_ANSWERS_DESC', 'Antworten Beschreibung');
\define('_MA_WGEVENTS_ANSWERS_CURR', 'Anzahl der aktuellen Antworten');
// Caption of Answer
\define('_MA_WGEVENTS_ANSWER_ID', 'Id');
\define('_MA_WGEVENTS_ANSWER_EVID', 'Veranstaltung Id');
\define('_MA_WGEVENTS_ANSWER_REGID', 'Anmeldung Id');
\define('_MA_WGEVENTS_ANSWER_QUEID', 'Frage Id');
\define('_MA_WGEVENTS_ANSWER_TEXT', 'Text');
// Textblock
\define('_MA_WGEVENTS_TEXTBLOCK', 'Textblock');
\define('_MA_WGEVENTS_TEXTBLOCK_ADD', 'Textblock hinzufügen');
\define('_MA_WGEVENTS_TEXTBLOCK_EDIT', 'Textblock bearbeiten');
\define('_MA_WGEVENTS_TEXTBLOCK_DELETE', 'Textblock löschen');
\define('_MA_WGEVENTS_TEXTBLOCK_CLONE', 'Textblock duplizieren');
\define('_MA_WGEVENTS_TEXTBLOCKS', 'Textblöcke');
\define('_MA_WGEVENTS_TEXTBLOCKS_LIST', 'Liste der Textblöcke');
\define('_MA_WGEVENTS_TEXTBLOCKS_TITLE', 'Textblöcke Titel');
\define('_MA_WGEVENTS_TEXTBLOCKS_DESC', 'Textblöcke Beschreibung');
\define('_MA_WGEVENTS_TEXTBLOCKS_THEREARENT', 'Derzeit sind keine Textblöcke verfügbar');
// Caption of Textblock
\define('_MA_WGEVENTS_TEXTBLOCK_ID', 'Id');
\define('_MA_WGEVENTS_TEXTBLOCK_CATID', 'Kategorie');
\define('_MA_WGEVENTS_TEXTBLOCK_NAME', 'Name');
\define('_MA_WGEVENTS_TEXTBLOCK_TEXT', 'Text');
\define('_MA_WGEVENTS_TEXTBLOCK_CLASS', 'Klasse');
\define('_MA_WGEVENTS_TEXTBLOCK_CLASS_PRIVATE', 'Privat');
\define('_MA_WGEVENTS_TEXTBLOCK_CLASS_PUBLIC', 'Öffentlich');
// Elements of Addtype
\define('_MA_WGEVENTS_FIELD_NONE', 'None');
\define('_MA_WGEVENTS_FIELD_LABEL', 'Infofeld');
\define('_MA_WGEVENTS_FIELD_TEXTBOX', 'Textfeld');
\define('_MA_WGEVENTS_FIELD_TEXTAREA', 'Mehrzeiliges Textfeld');
\define('_MA_WGEVENTS_FIELD_SELECTBOX', 'Dropdownliste');
\define('_MA_WGEVENTS_FIELD_COMBOBOX', 'Kombinationsfeld');
\define('_MA_WGEVENTS_FIELD_CHECKBOX', 'Kontrollkästchen');
\define('_MA_WGEVENTS_FIELD_RADIO', 'Optionsfeld');
\define('_MA_WGEVENTS_FIELD_RADIOYN', 'Optionsfeld Ja/Nein');
\define('_MA_WGEVENTS_FIELD_DATE', 'Datumsfeld');
\define('_MA_WGEVENTS_FIELD_DATETIME', 'Datums-/Uhrzeitfeld');
\define('_MA_WGEVENTS_FIELD_NAME', 'Textfeld Name');
\define('_MA_WGEVENTS_FIELD_EMAIL', 'Textfeld E-Mail');
\define('_MA_WGEVENTS_FIELD_COUNTRY', 'Dropdownliste Land');
\define('_MA_WGEVENTS_FIELD_TEXTBLOCK', 'Textblock');
\define('_MA_WGEVENTS_FIELD_TEXTEDITOR', 'Text Editor');
// Submit
\define('_MA_WGEVENTS_SAVE', 'Speichern');
\define('_MA_WGEVENTS_EXEC', 'Ausführen');
\define('_MA_WGEVENTS_CONTINUE_QUESTIONY', 'Speichern und mit Anmeldeformular fortsetzen');
\define('_MA_WGEVENTS_GOTO_REGISTRATION', 'Zur Anmeldung');
\define('_MA_WGEVENTS_GOTO_QUESTIONS', 'Anmeldeformular bearbeiten');
\define('_MA_WGEVENTS_GOTO_EVENT', 'Zur Veranstaltung');
\define('_MA_WGEVENTS_GOTO_EVENTSLIST', 'Zur Veranstaltungsliste');
\define('_MA_WGEVENTS_OUTPUT_EXCEL', 'Ausgabe in Excel');
// Form
\define('_MA_WGEVENTS_FORM_OK', 'Erfolgreich gespeichert');
\define('_MA_WGEVENTS_FORM_DELETE_OK', 'Erfolgreich gelöscht');
\define('_MA_WGEVENTS_FORM_CANCEL_OK', 'Erfolgreich storniert');
\define('_MA_WGEVENTS_FORM_SURE_DELETE', "Willst du wirklich löschen: <b><span style='color : Red;'>%s </span></b>");
\define('_MA_WGEVENTS_FORM_SURE_RENEW', "Willst du wirklich aktualisieren: <b><span style='color : Red;'>%s </span></b>");
\define('_MA_WGEVENTS_INVALID_PARAM', 'Ungültiger Parameter');
\define('_MA_WGEVENTS_CONFIRMDELETE_TITLE', 'Löschen bestätigen');
\define('_MA_WGEVENTS_CONFIRMDELETE_LABEL', 'Willst du wirklich löschen:');
\define('_MA_WGEVENTS_CONFIRMDELETE_REGISTRATION', "Anmeldung für: <b><span style='color : Red;'>%s</span></b>");
\define('_MA_WGEVENTS_CONFIRMDELETE_TEXTBLOCK', "Textblock: <b><span style='color : Red;'>%s</span></b>");
\define('_MA_WGEVENTS_CONFIRMDELETE_EVENT', "Veranstaltung: <b><span style='color : Red;'>%s</span></b>");
\define('_MA_WGEVENTS_CONFIRMDELETE_QUESTION', "Zusatz-Anmeldefelder: <b><span style='color : Red;'>%s</span></b>");
\define('_MA_WGEVENTS_CONFIRMCANCEL_TITLE', 'Veranstaltung stornieren');
\define('_MA_WGEVENTS_CONFIRMCANCEL_LABEL', 'Willst du wirklich stornieren:');
\define('_MA_WGEVENTS_CONFIRMCANCEL_EVENT', "Achtung: Auch alle Anmeldungen werden automatisch storniert! Willst Du <b><span style='color : Red;'>%s</span></b> endgültig stornieren?");
// From Contact
\define('_MA_WGEVENTS_CONTACT_ALL', 'Alle Teilnehmer kontaktieren');
\define('_MA_WGEVENTS_CONTACT_MAILFROM', 'Absender');
\define('_MA_WGEVENTS_CONTACT_MAILTO', 'Empfänger');
\define('_MA_WGEVENTS_CONTACT_MAILCOPY', 'Kopie an mich senden');
\define('_MA_WGEVENTS_CONTACT_MAILSUBJECT', 'Betreff');
\define('_MA_WGEVENTS_CONTACT_ALL_MAILSUBJECT_TEXT', 'Information zu: %s');
\define('_MA_WGEVENTS_CONTACT_MAILBODY', 'Nachricht');
\define('_MA_WGEVENTS_CONTACT_ALL_SUCCESS', 'Mail an alle Teilnehmer erfolgreich versendet');
\define('_MA_WGEVENTS_CONTACT_ALL_ERROR', 'Beim Versenden der Mail an alle Teilnehmer ist leider ein Fehler aufgetreten');
// calendar
\define('_MA_WGEVENTS_CAL_ITEMS', 'Kalender Einträge');
\define('_MA_WGEVENTS_CAL_EDITITEM', 'Eintrag bearbeiten');
\define('_MA_WGEVENTS_CAL_ADDITEM', 'Eintrag hinzufügen');
//navbar
\define('_MA_WGEVENTS_CAL_PREVMONTH', 'Vorheriges Monat');
\define('_MA_WGEVENTS_CAL_NEXTMONTH', 'Nächster Monat');
\define('_MA_WGEVENTS_CAL_PREVYEAR', 'Vorheriges Jahr');
\define('_MA_WGEVENTS_CAL_NEXTYEAR', 'Nächstes Jahr');
//days
\define('_MA_WGEVENTS_CAL_MIN_SUNDAY', 'So');
\define('_MA_WGEVENTS_CAL_MIN_MONDAY', 'Mo');
\define('_MA_WGEVENTS_CAL_MIN_TUESDAY', 'Di');
\define('_MA_WGEVENTS_CAL_MIN_WEDNESDAY', 'Mi');
\define('_MA_WGEVENTS_CAL_MIN_THURSDAY', 'Do');
\define('_MA_WGEVENTS_CAL_MIN_FRIDAY', 'Fr');
\define('_MA_WGEVENTS_CAL_MIN_SATURDAY', 'Sa');
\define('_MA_WGEVENTS_CAL_SUNDAY', 'Sonntag');
\define('_MA_WGEVENTS_CAL_MONDAY', 'Montag');
\define('_MA_WGEVENTS_CAL_TUESDAY', 'Dienstag');
\define('_MA_WGEVENTS_CAL_WEDNESDAY', 'Mittwoch');
\define('_MA_WGEVENTS_CAL_THURSDAY', 'Donnerstag');
\define('_MA_WGEVENTS_CAL_FRIDAY', 'Freitag');
\define('_MA_WGEVENTS_CAL_SATURDAY', 'Samstag');
\define('_MA_WGEVENTS_CAL_JANUARY', 'Januar');
\define('_MA_WGEVENTS_CAL_FEBRUARY', 'Februar');
\define('_MA_WGEVENTS_CAL_MARCH', 'März');
\define('_MA_WGEVENTS_CAL_APRIL', 'April');
\define('_MA_WGEVENTS_CAL_MAY', 'Mai');
\define('_MA_WGEVENTS_CAL_JUNE', 'Juni');
\define('_MA_WGEVENTS_CAL_JULY', 'Juli');
\define('_MA_WGEVENTS_CAL_AUGUST', 'August');
\define('_MA_WGEVENTS_CAL_SEPTEMBER', 'September');
\define('_MA_WGEVENTS_CAL_OCTOBER', 'Oktober');
\define('_MA_WGEVENTS_CAL_NOVEMBER', 'November');
\define('_MA_WGEVENTS_CAL_DECEMBER', 'Dezember');
// mail confirmation/notification
\define('_MA_WGEVENTS_MAIL_REG_IN_SUBJECT', 'Benachrichtigung über Anmeldung');
\define('_MA_WGEVENTS_MAIL_REG_OUT_SUBJECT', 'Benachrichtigung über Abmeldung');
\define('_MA_WGEVENTS_MAIL_REG_MODIFY_SUBJECT', 'Benachrichtigung über Änderung Anmeldung');
\define('_MA_WGEVENTS_MAIL_REG_MODIFICATION', 'Änderung %s: "%s" auf "%s"');
\define('_MA_WGEVENTS_MAIL_REG_MODIFICATION_DEL', 'Änderung: "%s" gelöscht');
\define('_MA_WGEVENTS_MAIL_REG_ALL_SUBJECT', 'Benachrichtigung zu Veranstaltung');
\define('_MA_WGEVENTS_MAIL_EVENT_NOTIFY_MODIFY_SUBJECT', 'Benachrichtigung über Änderung einer Veranstaltung');
\define('_MA_WGEVENTS_MAIL_REG_IN_VERIF', 'Zur Bestätigung Deiner Anmeldung klicke bitte auf folgenden Link: %s');
\define('_MA_WGEVENTS_MAIL_REG_IN_FINAL', 'Über die Bestätigung Deiner Anmeldung wirst Du durch den Veranstalter noch separat informiert.');
\define('_MA_WGEVENTS_MAIL_REG_IN_LISTWAIT', 'Deine Anmeldung wurde zur Warteliste hinzugefügt.');
\define('_MA_WGEVENTS_MAIL_REG_VERIF_ERROR', "Bei der Bestätigung der Anmeldung für die Veranstaltung '%s' ist leider ein Fehler aufgetreten. Bitte wende Dich an den Veranstalter.");
\define('_MA_WGEVENTS_MAIL_REG_VERIF_SUCCESS', "Die Anmeldung zur Veranstaltung '%s' wurde erfolgreich bestätigt");
\define('_MA_WGEVENTS_MAIL_REG_VERIF_INFO', 'Bestätigung der Anmeldung');
// ---------------- Print ----------------
\define('_MA_WGEVENTS_PRINT', 'Drucken');
// Admin link
\define('_MA_WGEVENTS_ADMIN', 'Administration');
// ---------------- End ----------------
