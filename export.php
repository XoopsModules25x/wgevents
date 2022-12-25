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

use Xmf\Request;
use XoopsModules\Wgevents\ {
    Export\Simplexlsxgen,
    Export\Ics
};

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_export.tpl';
require_once \XOOPS_ROOT_PATH . '/header.php';

// Permission
if (!$permissionsHandler->getPermEventsView()) {
    \redirect_header('index.php', 0);
}

$op         = Request::getCmd('op', 'list');
$start      = Request::getInt('start');
$limit      = Request::getInt('limit', $helper->getConfig('userpager'));
$filterCats = Request::getArray('filter_cats');
$chkEvents  = Request::getArray('chk_event');

if (Request::hasVar('datefrom')) {
    // data from filter form:
    $eventDatefromArr = Request::getArray('datefrom');
    $eventDatefromObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $eventDatefromArr['date']);
    $eventDatefromObj->setTime(0, 0);
    $dateFrom = $eventDatefromObj->getTimestamp() + (int)$eventDatefromArr['time'];
    $eventDatetoArr = Request::getArray('dateto');
    $eventDatetoObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $eventDatetoArr['date']);
    $eventDatetoObj->setTime(0, 0);
    $dateTo = $eventDatetoObj->getTimestamp() + (int)$eventDatetoArr['time'];
} elseif (Request::hasVar('export_datefrom')) {
    // data from other sources
    $dateFrom = Request::getInt('export_datefrom');
    $dateTo   = Request::getInt('export_dateto');
} else {
    // new load
    $dateFrom = \time();
    $dateTo   = \time() + 365*24*60*60;
}
$GLOBALS['xoopsTpl']->assign('dateFrom', $dateFrom);
$GLOBALS['xoopsTpl']->assign('dateTo', $dateTo);
if (Request::hasVar('export_limit')) {
    // data from filter form:
    $limit = Request::getInt('export_limit');
}
$GLOBALS['xoopsTpl']->assign('limit', $limit);

// get type of output
$outType = 'none';
if (Request::hasVar('export_excel')) {
    $outType = 'xlsx';
}
if (Request::hasVar('export_ics')) {
    $outType = 'ics';
}
$new = Request::hasVar('new');

$data = [];
if ('xlsx' == $outType) {
    //add field names
    $data[0] = [\_MA_WGEVENTS_EVENT_CATID, \_MA_WGEVENTS_EVENT_NAME, \_MA_WGEVENTS_EVENT_DESC, \_MA_WGEVENTS_EVENT_DATEFROM, \_MA_WGEVENTS_EVENT_DATETO, \_MA_WGEVENTS_EVENT_LOCATION];
}
$filename = '';
if ('xlsx' == $outType || 'ics' == $outType) {
    $filename = \date('Ymd_H_i_s_') . \_MA_WGEVENTS_EVENTS . '.' . $outType;
}

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);
// Paths
$GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
// JS
$GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
// Keywords
$keywords = [];
$keywords[] = \_MA_WGEVENTS_EVENTS_EXPORT;
$keywords[] = \_MA_WGEVENTS_OUTPUT_EXCEL;
$keywords[] = \_MA_WGEVENTS_OUTPUT_ICS;
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_INDEX, 'link' => 'index.php'];

//preferences
$eventDisplayCats = (string)$helper->getConfig('event_displaycats');
$GLOBALS['xoopsTpl']->assign('event_displaycats', $eventDisplayCats);

// get form for filtering events
$formFilter = $eventHandler->getFormFilterExport($limit, $dateFrom, $dateTo, $eventDisplayCats, $filterCats);
$GLOBALS['xoopsTpl']->assign('formFilter', $formFilter->render());

// get events
$eventsArr = $eventHandler->getEvents($start, $limit, $dateFrom, $dateTo, '', '', $op, 0, '', $filterCats);

$eventsCount = $eventsArr['count'];
$GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
if ($eventsCount > 0) {
    $eventsAll = $eventsArr['eventsAll'];
    $events    = [];
    // Get All Event
    foreach (\array_keys($eventsAll) as $i) {
        $events[$i] = $eventsAll[$i]->getValuesEvents();
        if ($new) {
            // preselect all events after new filter result
            $events[$i]['checked'] = true;
        }
        if (\in_array($i, $chkEvents)) {
            $events[$i]['checked'] = true;
            if ('xlsx' == $outType) {
                // prepare data for ouput as excel
                $data[$i] = [
                    $events[$i]['catname'],
                    $events[$i]['name'],
                    $events[$i]['desc_text'],
                    $events[$i]['datefrom_text'],
                    $events[$i]['dateto_text'],
                    $events[$i]['location_text']
                ];
            }
            if ('ics' == $outType) {
                // prepare data for ouput as ics
                $data[$i] = $eventsAll[$i];
            }
        }
    }
    // add data for displaying filtered events
    $GLOBALS['xoopsTpl']->assign('events_list', $events);

    // output of data
    if ('xlsx' == $outType) {
        $xlsx = Simplexlsxgen\SimpleXLSXGen::fromArray($data);
        $xlsx->downloadAs($filename);
    }
    if ('ics' == $outType) {
        $icsText = '';
        $exportICS = new Ics\ExportICS();
        $exportICS->setEvents($data);
        $icsText .= $exportICS->createIcsHeader();
        $icsText .= $exportICS->createIcsEvent();
        $icsText .= $exportICS->createIcsFooter();
        $exportICS->downloadAsIcs($filename, $icsText);
    }

    unset($events);
    // Display Navigation
    if ($eventsCount > $limit) {
        $urlCats = \implode(',', $filterCats);
        require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
        $params = '&amp;datefrom_val=' . $dateFrom . '&amp;dateto_val=' . $dateTo . '&amp;new=1&amp;cats=' . $urlCats;
        $pagenav = new \XoopsPageNav($eventsCount, $limit, $start, 'start', 'op=list&amp;limit=' . $limit . $params);
        $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
    }
    $GLOBALS['xoopsTpl']->assign('start', $start);
    $GLOBALS['xoopsTpl']->assign('limit', $limit);

    $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', \_MA_WGEVENTS_EVENTS_EXPORT . ' - ' . $GLOBALS['xoopsModule']->getVar('name'));

} else {
    if (\count($filterCats) > 0) {
        $GLOBALS['xoopsTpl']->assign('noEventsReason', \_MA_WGEVENTS_INDEX_THEREARENT_EVENTS_FILTER);
    } else {
        $GLOBALS['xoopsTpl']->assign('noEventsReason', \_MA_WGEVENTS_INDEX_THEREARENT_EVENTS);
    }
}

// Keywords
wgeventsMetaKeywords($helper->getConfig('keywords') . ', ' . \implode(',', $keywords));
unset($keywords);

// Description
wgeventsMetaDescription(\_MA_WGEVENTS_EVENTS_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/event.php');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);

require __DIR__ . '/footer.php';
