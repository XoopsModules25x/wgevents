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
 * @copyright      2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        wgevents
 * @author         wedega - Email:<webmaster@wedega.com> - Website:<https://xoops.wedega.com>
 */

use Xmf\Request;
use XoopsModules\Wgevents\ {
    Constants,
    SimpleCalendar
};

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_calendar.tpl';
require_once \XOOPS_ROOT_PATH . '/header.php';

if (!$permissionsHandler->getPermEventsView()) {
    \redirect_header('index.php?op=list', 3, \_NOPERM);
}

//request
$op            = Request::getCmd('op', 'list');
$filterFrom    = Request::getInt('filterFrom');
$filterTo      = Request::getInt('filterTo');

if (Request::hasVar('gotoMonth')) {
    $month   = Request::getInt('gotoMonth');
    $year    = Request::getInt('gotoYear');
} else {
    //default params
    $year     = (int)\date('Y');
    $month    = (int)\date('n');
}
$lastday  = (int)\date('t', \strtotime($month . '/1/' . $year));
$dayStart = \mktime(0, 0, 0, $month, 1, $year);
$dayEnd   = \mktime(23, 59, 59, $month, $lastday, $year);

//$filterCat     = Request::getInt('filterCat');
$filterSort    = 'datefrom-ASC';
if (0 == $filterFrom || Request::hasVar('gotoMonth')) {
    $filterFrom = (int)$dayStart;
    $filterTo   = (int)$dayEnd;
}

$filterFromPrevM = \mktime(0, 0, 0, (int)\date('n', $filterFrom - 1), 1, (int)\date('Y', $filterFrom - 1));
$filterToPrevM = $filterFrom - 1;
$filterFromNextM = $filterTo + 1;
$filterToNextM =  \mktime(23, 59, 59, (int)\date('n', $filterFromNextM), (int)\date('t', $filterFromNextM), (int)\date('Y', $filterFromNextM));
$filterFromPrevY = \mktime(0, 0, 0, (int)\date('n', $filterFrom), 1, (int)\date('Y', $filterFrom) - 1);
$filterToPrevY = \mktime(23, 59, 59, (int)\date('n', $filterTo), (int)\date('t', $filterTo), (int)\date('Y', $filterTo) - 1);
$filterFromNextY = \mktime(0, 0, 0, (int)\date('n', $filterFrom), 1, (int)\date('Y', $filterFrom) + 1);
$filterToNextY =  \mktime(23, 59, 59, (int)\date('n', $filterTo), (int)\date('t', $filterTo), (int)\date('Y', $filterTo) + 1);

/*calendar nav bar*/
$arrMonth = [
    1 => \_MA_WGEVENTS_CAL_JANUARY,
    2 => \_MA_WGEVENTS_CAL_FEBRUARY,
    3 => \_MA_WGEVENTS_CAL_MARCH,
    4 => \_MA_WGEVENTS_CAL_APRIL,
    5 => \_MA_WGEVENTS_CAL_MAY,
    6 => \_MA_WGEVENTS_CAL_JUNE,
    7 => \_MA_WGEVENTS_CAL_JULY,
    8 => \_MA_WGEVENTS_CAL_AUGUST,
    9 => \_MA_WGEVENTS_CAL_SEPTEMBER,
    10 => \_MA_WGEVENTS_CAL_OCTOBER,
    11 => \_MA_WGEVENTS_CAL_NOVEMBER,
    12 => \_MA_WGEVENTS_CAL_DECEMBER
];
$GLOBALS['xoopsTpl']->assign('monthNav', $arrMonth[\date('n', $filterFrom)]);
$GLOBALS['xoopsTpl']->assign('yearNav', \date('Y', $filterFrom));
$GLOBALS['xoopsTpl']->assign('filterFromPrevM', $filterFromPrevM);
$GLOBALS['xoopsTpl']->assign('filterToPrevM', $filterToPrevM);
$GLOBALS['xoopsTpl']->assign('filterFromNextM', $filterFromNextM);
$GLOBALS['xoopsTpl']->assign('filterToNextM', $filterToNextM);
$GLOBALS['xoopsTpl']->assign('filterFromPrevY', $filterFromPrevY);
$GLOBALS['xoopsTpl']->assign('filterToPrevY', $filterToPrevY);
$GLOBALS['xoopsTpl']->assign('filterFromNextY', $filterFromNextY);
$GLOBALS['xoopsTpl']->assign('filterToNextY', $filterToNextY);
//$otherParams = "op=filter&amp;filterByOwner=$filterByOwner&amp;filterGroup=$filterGroup";
//$GLOBALS['xoopsTpl']->assign('otherParams', $otherParams);

$lengthTitle = 30;


/*
if (Constants::FILTERBY_OWN === $filterByOwner) {
    $op = 'filterOwn';
} else if (Constants::FILTERBY_GROUP === $filterByOwner) {
    $op = 'filterGroup';
} else {
    $op = 'list';
}
*/

$op = 'list';
[$sortBy, $orderBy] = \explode('-', $filterSort);

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);
// Keywords
$keywords = [];
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_CAL_ITEMS];
// Paths
$GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL . '/16/');

$GLOBALS['xoTheme']->addStylesheet(\WGEVENTS_URL . '/class/SimpleCalendar/css/SimpleCalendar.css', null);
$calendar = new SimpleCalendar\SimpleCalendar();
$calendar->setStartOfWeek($helper->getConfig('cal_firstday'));
$calendar->setWeekDayNames([
    \_MA_WGEVENTS_CAL_MIN_SUNDAY,
    \_MA_WGEVENTS_CAL_MIN_MONDAY,
    \_MA_WGEVENTS_CAL_MIN_TUESDAY,
    \_MA_WGEVENTS_CAL_MIN_WEDNESDAY,
    \_MA_WGEVENTS_CAL_MIN_THURSDAY,
    \_MA_WGEVENTS_CAL_MIN_FRIDAY,
    \_MA_WGEVENTS_CAL_MIN_SATURDAY ]);

$uid = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;

$formSimpleCal = new SimpleCalendar\SimpleCalendarforms();
$formFilter = $formSimpleCal->getFormGotoMonth($arrMonth, \date('n', $filterFrom), \date('Y', $filterFrom));
$GLOBALS['xoopsTpl']->assign('formGoto', $formFilter->render());

/*
$filterHandler = new Filterhandler();
$filterHandler->filterByOwner = $filterByOwner;
$filterHandler->filterGroup = $filterGroup;
$filterHandler->filterCat = $filterCat;
$filterHandler->filterSort = $filterSort;
$filterHandler->showLimit = false;
$filterHandler->showSort = false;
$filterHandler->showPeriod = false;

$formFilter = $filterHandler->getFormFilterItems();
$GLOBALS['xoopsTpl']->assign('formFilter', $formFilter->render());
*/
$filtered = false;

$gmapsEnableCal = false;
$gmapsHeight    = false;
$useGMaps       = (bool)$helper->getConfig('use_gmaps');
if ($useGMaps) {
    $gmapsPositionList = (string)$helper->getConfig('gmaps_enablecal');
    $gmapsEnableCal    = ('top' == $gmapsPositionList || 'bottom' == $gmapsPositionList);
    $gmapsHeight       = $helper->getConfig('gmaps_height');
}

// get categories collection
$categories = $categoryHandler->getCollection();
// get events of period
$eventsArr = $eventHandler->getEvents(0, 0, $filterFrom, $filterTo, $sortBy, $orderBy);

$eventsCount = $eventsArr['count'];
if ($eventsCount > 0) {
    $eventsAll = $eventsArr['eventsAll'];
    $eventsMap = [];
    $calendar->setDate($filterFrom);
    $GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
    foreach (\array_keys($eventsAll) as $i) {
        $event = $eventsAll[$i]->getValuesEvents();
        $linkStyle = 'color:' . $categories[$event['catid']]['color'] . '!important;';
        $linkStyle .= 'border:1px solid ' . $categories[$event['catid']]['bordercolor'] . '!important;';
        $linkStyle .= 'background-color:' . $categories[$event['catid']]['bgcolor'] . '!important;';
        $linkStyle .= $categories[$event['catid']]['othercss'];
        $evTitle = \_MA_WGEVENTS_EVENT_NAME . ': ' . $event['name'] . PHP_EOL;
        $evTitle .= \_MA_WGEVENTS_EVENT_DATE . ': ' . $eventHandler->getDateFromToText($event['datefrom'], $event['dateto'], $event['allday']) . PHP_EOL;
        if ($event['location']) {
            $evTitle .= \_MA_WGEVENTS_EVENT_LOCATION . ': ' .$event['location'] . PHP_EOL;
        }
        $eventLink = '<a href="event.php?op=show&amp;id=' . $event['id'] .'" title="' . $evTitle .'">';
        /*
        if ($event['catlogo']) {
            $eventLink .= '<img class="wg-cal-catlogo" src="' . \WGEVENTS_UPLOAD_CATLOGOS_URL . '/' . $event['catlogo'] .'" alt="' . \_MA_WGEVENTS_CATEGORY_LOGO .'" title="' . \_MA_WGEVENTS_CATEGORY_LOGO .'">';
        }
        */
        $eventLink .= '<span class="wg-cal-eventtext">';
        $evName = $event['name'];
        if (\strlen($evName) > $lengthTitle) {
            $evName = \substr($evName, 0, $lengthTitle - 3) . '...';
        }
        $eventLink .= $evName;
        if ($useGMaps && $gmapsEnableCal && (float)$event['locgmlat'] > 0) {
            $eventsMap[$event['id']] = [
                'name' => $evName,
                'location' => $event['location_text_user'],
                'from' => $event['datefrom_text'],
                'url' => 'event.php?op=show&id=' . $event['id'],
                'lat'  => (float)$event['locgmlat'],
                'lon'  => (float)$event['locgmlon']
            ];
        }
        
        $eventLink .= '</span><i class="fa fa-edit wg-cal-icon pull-right" title="' . \_MA_WGEVENTS_CAL_EDITITEM . '"></i></a>';
        $calendar->addDailyHtml($eventLink, $event['datefrom'], $event['dateto'], $linkStyle);
    }
    if ($useGMaps && count($eventsMap) > 0) {
        if ('show' == $op) {
            $GLOBALS['xoopsTpl']->assign('gmapsShow', true);
        } else {
            $GLOBALS['xoopsTpl']->assign('gmapsShowList', true);
            $GLOBALS['xoopsTpl']->assign('gmapsEnableCal', $gmapsEnableCal);
            $GLOBALS['xoopsTpl']->assign('gmapsHeight', $gmapsHeight);
            $GLOBALS['xoopsTpl']->assign('gmapsPositionList', $gmapsPositionList);
        }
        $GLOBALS['xoopsTpl']->assign('api_key', $helper->getConfig('gmaps_api'));
        $GLOBALS['xoopsTpl']->assign('eventsMap', $eventsMap);
    }
}
$calendar->setPermSubmit($permissionsHandler->getPermEventsSubmit());
$GLOBALS['xoopsTpl']->assign('events_calendar', $calendar->render());

// Keywords
wgeventsMetaKeywords($helper->getConfig('keywords') . ', ' . \implode(',', $keywords));
unset($keywords);
// Description
wgeventsMetaDescription(\_MA_WGEVENTS_INDEX_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/index.php');

require __DIR__ . '/footer.php';
