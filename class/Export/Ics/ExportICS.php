<?php declare(strict_types=1);

namespace XoopsModules\Wgevents\Export\Ics;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * ICS export for XOOPS
 *
 * @copyright    2021 XOOPS Project (https://xoops.org)
 * @license      GPL 2.0 or later
 * @package      wgevents
 * @author       Goffy - Wedega - Email:webmaster@wedega.com - Website:https://xoops.wedega.com
 */


/**
 * Class Object Handler ICS
 */
class ExportICS {

    /**
     * @var XoopsObject
     */
    private $events = null;

    /**
     * @var array
     */
    private $timezone = [];

    /**
     * Constructor
     *
     */
    public function __construct()
    {
    }

    /**
     * @param $eventsAll
     * @return void
     */
    public function setEvents ($eventsAll) {
        $this->events = $eventsAll;
    }

    /**
     * download ics file based on given name and content
     * @param $filename
     * @param $filecontent
     * @return bool
     */
    public function downloadAsIcs($filename, $filecontent ) {
        $fileres = \fopen('php://memory','wb');
        if (!$fileres) {
            return false;
        }

        if (!\fwrite($fileres, $filecontent)) {
            \fclose($fileres);
            return false;
        }

        $filesize = \ftell($fileres);

        \header ( 'Content-Type: text/calendar' );
        \header ( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        \header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T' , \time() ));
        \header('Content-Length: ' . $filesize);

        while(\ob_get_level() ) {
            \ob_end_clean();
        }
        \fseek($fileres,0);
        \fpassthru( $fileres );

        \fclose($fileres);

        return true;
    }

    /**
     * function to generate ics file header
     * @return false|string
     */
    public function createIcsHeader () {

        if (!$this->setTimeZone()) {
            echo 'invalid timezone';
            return false;
        }

        \date_default_timezone_set($this->timezone['name']);

        $year            = (int)\date('Y');
        $startSummertime = \date_format(\date_create('last Sunday of March '.$year.' 02:00'), 'Ymd') . 'T020000';
        $endSummertime   = \date_format(\date_create('last Sunday of October '.$year.' 03:00'), 'Ymd') . 'T030000';

        $headerLine = 'BEGIN:VCALENDAR' . PHP_EOL;
        $headerLine .= 'PRODID:wedega.com' . PHP_EOL;
        $headerLine .= 'VERSION:1.0' . PHP_EOL;
        $headerLine .= 'METHOD:PUBLISH' . PHP_EOL;
        $headerLine .= 'X-MS-OLK-FORCEINSPECTOROPEN:TRUE' . PHP_EOL;
        $headerLine .= 'BEGIN:VTIMEZONE' . PHP_EOL;
        $headerLine .= 'TZID:' . $this->timezone['name'] . PHP_EOL;
        $headerLine .= 'BEGIN:STANDARD' . PHP_EOL;
        $headerLine .= 'DTSTART:' . $endSummertime . PHP_EOL;
        $headerLine .= 'RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10' . PHP_EOL;
        $headerLine .= 'TZOFFSETFROM:' . $this->timezone['standard']['tzoffsetfrom'] . PHP_EOL;
        $headerLine .= 'TZOFFSETTO:' . $this->timezone['standard']['tzoffsetto'] . PHP_EOL;
        $headerLine .= 'END:STANDARD' . PHP_EOL;
        $headerLine .= 'BEGIN:DAYLIGHT' . PHP_EOL;
        $headerLine .= 'DTSTART:' . $startSummertime . PHP_EOL;
        $headerLine .= 'RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=3' . PHP_EOL;
        $headerLine .= 'TZOFFSETFROM:' . $this->timezone['daylight']['tzoffsetfrom'] . PHP_EOL;
        $headerLine .= 'TZOFFSETTO:' . $this->timezone['daylight']['tzoffsetto'] . PHP_EOL;
        $headerLine .= 'END:DAYLIGHT' . PHP_EOL;
        $headerLine .= 'END:VTIMEZONE' . PHP_EOL;

        return $headerLine;
    }


    /**
     * function to generate ics events
     * @return string
     */
    public function createIcsEvent() {

        $eventLine = '';
        foreach ($this->events as $event) {
            $eventLine .= 'BEGIN:VEVENT' . PHP_EOL;
            $eventLine .= 'CLASS:PUBLIC' . PHP_EOL;
            $eventLine .= 'CREATED:' . \date('Ymd\THis\Z', $event->getVar('datecreated')) . PHP_EOL;
            $eventLine .= 'DTEND;TZID="' . $this->timezone['name'] . '":' . \date('Ymd\THis', $event->getVar('dateto')) . PHP_EOL;
            $eventLine .= 'DTSTAMP:20220912T091145Z' . PHP_EOL;
            $eventLine .= 'DTSTART;TZID="' . $this->timezone['name'] . '":' . \date('Ymd\THis', $event->getVar('datefrom')) . PHP_EOL;
            //$eventLine .= 'LAST-MODIFIED:20220912T091145Z' . PHP_EOL;
            $eventLine .= 'LAST-MODIFIED:' . \date('Ymd\THis\Z', $event->getVar('datecreated')) . PHP_EOL;
            $evLocation = $event->getVar('location');
            if (\strlen($evLocation) > 0) {
                $eventLine .= 'LOCATION:' . $this->cleanUpText($evLocation) . PHP_EOL;
            }
            $eventLine .= 'PRIORITY:5' . PHP_EOL;
            $eventLine .= 'SEQUENCE:0' . PHP_EOL;
            $evName = $event->getVar('name');
            if (\strlen($evName) > 0) {
                $eventLine .= 'SUMMARY;LANGUAGE=de-at:' . $this->cleanUpText($evName) . PHP_EOL;
            }
            $evDesc = $event->getVar('desc');
            if (\strlen($evDesc) > 0) {
                $eventLine .= 'DESCRIPTION;LANGUAGE=de-at:' . $this->cleanUpText($evDesc) . PHP_EOL;
            }
            $eventLine .= 'TRANSP:OPAQUE' . PHP_EOL;
            $eventLine .= 'X-MICROSOFT-CDO-BUSYSTATUS:BUSY' . PHP_EOL;
            $eventLine .= 'X-MICROSOFT-CDO-IMPORTANCE:1' . PHP_EOL;
            $eventLine .= 'X-MICROSOFT-DISALLOW-COUNTER:FALSE' . PHP_EOL;
            $eventLine .= 'X-MS-OLK-AUTOFILLLOCATION:FALSE' . PHP_EOL;
            $eventLine .= 'X-MS-OLK-AUTOSTARTCHECK:FALSE' . PHP_EOL;
            $eventLine .= 'X-MS-OLK-CONFTYPE:0' . PHP_EOL;
            $eventLine .= 'END:VEVENT' . PHP_EOL;
        }
        
        return $eventLine;

    }

    /**
     * function to generate ics file footer
     * @return string
     */
    public function createIcsFooter () {

        return 'END:VCALENDAR';

    }

    /**
     * function to get timezone name and timezone offset depending on $GLOBALS['xoopsConfig']['server_TZ']
     * @return bool
     */
    private function setTimeZone () {

        $timezoneList = [];
        $timezoneList['-12'] = [
            'name' => 'Pacific/Kwajalein',
            'standard' => ['tzoffsetfrom' => '-1200', 'tzoffsetto' => '-1300'],
            'daylight' => ['tzoffsetfrom' => '-1300', 'tzoffsetto' => '-1200']
        ];
        $timezoneList['-11'] = [
            'name' => 'Pacific/Pago_Pago',
            'standard' => ['tzoffsetfrom' => '-1100', 'tzoffsetto' => '-1200'],
            'daylight' => ['tzoffsetfrom' => '-1200', 'tzoffsetto' => '-1100']
        ];
        $timezoneList['-10'] = [
            'name' => 'Pacific/Honolulu',
            'standard' => ['tzoffsetfrom' => '-1000', 'tzoffsetto' => '-1100'],
            'daylight' => ['tzoffsetfrom' => '-1100', 'tzoffsetto' => '-1000']
        ];
        $timezoneList['-9'] = [
            'name' => 'America/Anchorage',
            'standard' => ['tzoffsetfrom' => '-0900', 'tzoffsetto' => '-1000'],
            'daylight' => ['tzoffsetfrom' => '-1000', 'tzoffsetto' => '-0900']
        ];
        $timezoneList['-8'] = [
            'name' => 'America/Los_Angeles',
            'standard' => ['tzoffsetfrom' => '-0800', 'tzoffsetto' => '-0900'],
            'daylight' => ['tzoffsetfrom' => '-0900', 'tzoffsetto' => '-0800']
        ];
        $timezoneList['-7'] = [
            'name' => 'America/Denver',
            'standard' => ['tzoffsetfrom' => '-0700', 'tzoffsetto' => '-0800'],
            'daylight' => ['tzoffsetfrom' => '-0800', 'tzoffsetto' => '-0700']
        ];
        $timezoneList['-6'] = [
            'name' => 'America/Mexico_City',
            'standard' => ['tzoffsetfrom' => '-0600', 'tzoffsetto' => '-0700'],
            'daylight' => ['tzoffsetfrom' => '-0700', 'tzoffsetto' => '-0600']
        ];
        $timezoneList['-5'] = [
            'name' => 'America/New_York',
            'standard' => ['tzoffsetfrom' => '-0500', 'tzoffsetto' => '-0600'],
            'daylight' => ['tzoffsetfrom' => '-0600', 'tzoffsetto' => '-0500']
        ];
        $timezoneList['-4'] = [
            'name' => 'America/Caracas',
            'standard' => ['tzoffsetfrom' => '-0400', 'tzoffsetto' => '-0500'],
            'daylight' => ['tzoffsetfrom' => '-0500', 'tzoffsetto' => '-0400']
        ];
        $timezoneList['-3.5'] = [
            'name' => 'America/St_Johns',
            'standard' => ['tzoffsetfrom' => '-0300', 'tzoffsetto' => '-0400'],
            'daylight' => ['tzoffsetfrom' => '-0400', 'tzoffsetto' => '-0300']
        ];
        $timezoneList['-3'] = [
            'name' => 'America/Buenos_Aires',
            'standard' => ['tzoffsetfrom' => '-0200', 'tzoffsetto' => '-0300'],
            'daylight' => ['tzoffsetfrom' => '-0300', 'tzoffsetto' => '-0200']
        ];
        $timezoneList['-2'] = [
            'name' => 'Atlantic/South_Georgia',
            'standard' => ['tzoffsetfrom' => '-0100', 'tzoffsetto' => '-0200'],
            'daylight' => ['tzoffsetfrom' => '-0200', 'tzoffsetto' => '-0100']
        ];
        $timezoneList['-1'] = [
            'name' => 'Atlantic/Azores',
            'standard' => ['tzoffsetfrom' => '0000', 'tzoffsetto' => '-0100'],
            'daylight' => ['tzoffsetfrom' => '-0100', 'tzoffsetto' => '0000']
        ];
        $timezoneList['0'] = [
            'name' => 'Europe/London',
            'standard' => ['tzoffsetfrom' => '+0100', 'tzoffsetto' => '0000'],
            'daylight' => ['tzoffsetfrom' => '0000', 'tzoffsetto' => '+0100']
        ];
        $timezoneList['1'] = [
            'name' => 'Europe/Berlin',
            'standard' => ['tzoffsetfrom' => '+0200', 'tzoffsetto' => '+0100'],
            'daylight' => ['tzoffsetfrom' => '+0100', 'tzoffsetto' => '+0200']];
        $timezoneList['2'] = [
            'name' => 'Europe/Athens',
            'standard' => ['tzoffsetfrom' => '+0300', 'tzoffsetto' => '+0200'],
            'daylight' => ['tzoffsetfrom' => '+0200', 'tzoffsetto' => '+0300']
        ];
        $timezoneList['3'] = [
            'name' => 'Europe/Moscow',
            'standard' => ['tzoffsetfrom' => '+0400', 'tzoffsetto' => '+0300'],
            'daylight' => ['tzoffsetfrom' => '+0300', 'tzoffsetto' => '+0400']
        ];
        $timezoneList['3.5'] = [
            'name' => 'Asia/Tehran',
            'standard' => ['tzoffsetfrom' => '+0450', 'tzoffsetto' => '+0350'],
            'daylight' => ['tzoffsetfrom' => '+0350', 'tzoffsetto' => '+0450']
        ];
        $timezoneList['4'] = [
            'name' => 'Asia/Baku',
            'standard' => ['tzoffsetfrom' => '+0500', 'tzoffsetto' => '+0400'],
            'daylight' => ['tzoffsetfrom' => '+0400', 'tzoffsetto' => '+0500']
        ];
        $timezoneList['4.5'] = [
            'name' => 'Asia/Kabul',
            'standard' => ['tzoffsetfrom' => '+0550', 'tzoffsetto' => '+0450'],
            'daylight' => ['tzoffsetfrom' => '+0450', 'tzoffsetto' => '+0550']
        ];
        $timezoneList['5'] = [
            'name' => 'Asia/Tashkent',
            'standard' => ['tzoffsetfrom' => '+0600', 'tzoffsetto' => '+0500'],
            'daylight' => ['tzoffsetfrom' => '+0500', 'tzoffsetto' => '+0600']
        ];
        $timezoneList['5.5'] = [
            'name' => 'Asia/Calcutta',
            'standard' => ['tzoffsetfrom' => '+0700', 'tzoffsetto' => '+0600'],
            'daylight' => ['tzoffsetfrom' => '+0600', 'tzoffsetto' => '+0700']
        ];
        $timezoneList['6'] = [
            'name' => 'Asia/Dhaka',
            'standard' => ['tzoffsetfrom' => '+0750', 'tzoffsetto' => '+0650'],
            'daylight' => ['tzoffsetfrom' => '+0650', 'tzoffsetto' => '+0750']
        ];
        $timezoneList['7'] = [
            'name' => 'Asia/Bangkok',
            'standard' => ['tzoffsetfrom' => '+0800', 'tzoffsetto' => '+0700'],
            'daylight' => ['tzoffsetfrom' => '+0700', 'tzoffsetto' => '+0800']
        ];
        $timezoneList['8'] = [
            'name' => 'Asia/Singapore',
            'standard' => ['tzoffsetfrom' => '+0900', 'tzoffsetto' => '+0800'],
            'daylight' => ['tzoffsetfrom' => '+0800', 'tzoffsetto' => '+0900']
        ];
        $timezoneList['9'] = [
            'name' => 'Asia/Tokyo',
            'standard' => ['tzoffsetfrom' => '+1000', 'tzoffsetto' => '+0900'],
            'daylight' => ['tzoffsetfrom' => '+0900', 'tzoffsetto' => '+1000']
        ];
        $timezoneList['9.5'] = [
            'name' => 'Australia/Adelaide',
            'standard' => ['tzoffsetfrom' => '+1050', 'tzoffsetto' => '+0950'],
            'daylight' => ['tzoffsetfrom' => '+0950', 'tzoffsetto' => '+1050']
        ];
        $timezoneList['10'] = [
            'name' => 'Australia/Sydney Australia/Melbourne',
            'standard' => ['tzoffsetfrom' => '+1100', 'tzoffsetto' => '+1000'],
            'daylight' => ['tzoffsetfrom' => '+1000', 'tzoffsetto' => '+1100']
        ];
        $timezoneList['11'] = [
            'name' => 'Asia/Magadan',
            'standard' => ['tzoffsetfrom' => '+1200', 'tzoffsetto' => '+1100'],
            'daylight' => ['tzoffsetfrom' => '+1100', 'tzoffsetto' => '+1200']
        ];
        $timezoneList['12'] = [
            'name' => 'Pacific/Fiji',
            'standard' => ['tzoffsetfrom' => '+1300', 'tzoffsetto' => '+1200'],
            'daylight' => ['tzoffsetfrom' => '+1200', 'tzoffsetto' => '+1300']
        ];

        $this->timezone = $timezoneList[$GLOBALS['xoopsConfig']['server_TZ']];
        return true;

    }

    /**
     * function to clean the given text in order to avoid errors when importing the ics file into a calendar tools like outlook, thunderbird and so on
     * @param $text
     * @return string
     */
    private function cleanUpText ($text) {

        $textclean = \str_replace ( "\\n", "\n", $text );
        $textclean = \str_replace ( "\\'", "'", $textclean );
        $textclean = \str_replace ( "\\\"", "\"", $textclean );
        $textclean = \str_replace ( "'", "\\'", $textclean );
        $textclean = \str_replace ( '=0D=0A=', '<br />', $textclean );
        $textclean = \str_replace ( '=0D=0A', '', $textclean );
        $textclean = \strip_tags($textclean, '<br><p><li>');
        $textclean = \preg_replace ('/<[^>]*>/', ' ', $textclean);

        $textclean = \html_entity_decode($textclean, ENT_QUOTES | ENT_COMPAT , 'UTF-8');
        $textclean = \html_entity_decode($textclean, ENT_HTML5, 'UTF-8');
        $textclean = \html_entity_decode($textclean);

        return \htmlspecialchars_decode($textclean);
    }
}
