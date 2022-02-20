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
 * @since          1.0
 * @min_xoops      2.5.9
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

//default params
$year     = (int)\date('Y');
$month    = (int)\date('n');
$lastday  = (int)\date('t', \strtotime($month . '/1/' . $year));
$dayStart = \mktime(0, 0, 0, $month, 1, $year);
$dayEnd   = \mktime(23, 59, 59, $month, $lastday, $year);

//request
$op            = Request::getCmd('op', 'list');
$filterFrom    = Request::getInt('filterFrom', 0);
$filterTo      = Request::getInt('filterTo', 0);
$filterCat     = Request::getInt('filterCat', 0);
$filterSort    = 'datefrom-ASC';
if (0 == $filterFrom) {
    $filterFrom = $dayStart;
    $filterTo   = $dayEnd;
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

$lengthTitle = 15;


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
$events = [];
/*
switch ($op) {
    case 'list':
    default:

        break;

    case 'filterOwn':
        //$GLOBALS['xoopsTpl']->assign('resultTitle', \_MA_WGEVENTS_FILTER_RESULT);
        if ($uid > 0) {
            $events = $eventHandler->getItems($uid, 0, 0, $filterFrom, $filterTo, false, false, 0, $filterCat, $sortBy, $orderBy);
        }
        $filtered = true;
        break;
    case 'filterGroup':
        //$GLOBALS['xoopsTpl']->assign('resultTitle', \_MA_WGEVENTS_FILTER_RESULT);
        if ($permissionsHandler->getPermItemsGroupView()) {
            if (Constants::FILTER_TYPEALL == $filterGroup) {
                $events = $eventHandler->getItems(0, 0, 0, $filterFrom, $filterTo, true, false, 0, $filterCat, $sortBy, $orderBy);
            } else {
                $events = $eventHandler->getItems(0, 0, 0, $filterFrom, $filterTo, false, false, $filterGroup, $filterCat, $sortBy, $orderBy);
            }
        }
        $filtered = true;
        break;
}
*/

// get categories collection
$categories = $categoryHandler->getCollection();
// get events of period
$events = $eventHandler->getEvents(0, 0, $filterFrom, $filterTo, $filterCat, $sortBy, $orderBy);

$eventsCount = \count($events);
if ($eventsCount > 0) {
    $calendar->setDate($filterFrom);
    $GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
    foreach($events as $event) {
        $linkStyle = 'color:' . $categories[$event['catid']]['color'] . '!important;';
        $linkStyle .= 'border:1px solid ' . $categories[$event['catid']]['bordercolor'] . '!important;';
        $linkStyle .= 'background-color:' . $categories[$event['catid']]['bgcolor'] . '!important;';
        $linkStyle .= $categories[$event['catid']]['othercss'];

        $eventLink = '<a href="event.php?op=show&amp;id=' . $event['id'] .'">';
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
        $eventLink .= '</span><i class="fa fa-edit wg-cal-icon pull-right" title="' . \_MA_WGEVENTS_CAL_EDITITEM . '"></i></a>';
        $calendar->addDailyHtml($eventLink, $event['datefrom'], $event['dateto'], $linkStyle);
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
