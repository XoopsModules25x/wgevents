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

use XoopsModules\Wgevents;
use XoopsModules\Wgevents\{
    Helper,
    Constants,
    SimpleCalendar
};

require_once \XOOPS_ROOT_PATH . '/modules/wgevents/include/common.php';

/**
 * Function show block
 * @param  $options
 * @return array
 * @throws Exception
 * @throws Exception
 */
function b_wgevents_calendar_show($options)
{
    $block       = [];
    $typeBlock   = $options[0];
    $limit       = $options[1];
    \array_shift($options);
    \array_shift($options);

    $helper      = Helper::getInstance();
    $eventHandler = $helper->getHandler('Event');
    $categoryHandler = $helper->getHandler('Category');

    \xoops_loadLanguage('main', 'wgevents');

    //default params
    $year     = (int)\date('Y');
    $month    = (int)\date('n');
    $lastday  = (int)\date('t', \strtotime($month . '/1/' . $year));
    $dayStart = \mktime(0, 0, 0, $month, 1, $year);
    $dayEnd   = \mktime(23, 59, 59, $month, $lastday, $year);

    $filterFrom = $dayStart;
    $filterTo   = $dayEnd;
    $filterSort    = 'datefrom-ASC';
    [$sortBy, $orderBy] = \explode('-', $filterSort);

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


    $GLOBALS['xoTheme']->addStylesheet(\WGEVENTS_URL . '/class/SimpleCalendar/css/SimpleCalendarMini.css', null);
    $calendar = new SimpleCalendar\SimpleCalendarMini();
    $calendar->setStartOfWeek($helper->getConfig('cal_firstday'));
    $calendar->setWeekDayNames([
        \_MA_WGEVENTS_CAL_MIN_SUNDAY,
        \_MA_WGEVENTS_CAL_MIN_MONDAY,
        \_MA_WGEVENTS_CAL_MIN_TUESDAY,
        \_MA_WGEVENTS_CAL_MIN_WEDNESDAY,
        \_MA_WGEVENTS_CAL_MIN_THURSDAY,
        \_MA_WGEVENTS_CAL_MIN_FRIDAY,
        \_MA_WGEVENTS_CAL_MIN_SATURDAY ]);

    // get categories collection
    $categories = $categoryHandler->getCollection();
    // get events of period
    $eventsArr = $eventHandler->getEvents(0, 0, $filterFrom, $filterTo, $sortBy, $orderBy);

    $eventsCount = $eventsArr['count'];
    if ($eventsCount > 0) {
        $calendar->setDate($filterFrom);
        $GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
        foreach($eventsArr['eventsAll'] as $event) {
            $badgeStyle = 'border:1px solid ' . $categories[$event->getVar('catid')]['bordercolor'] . '!important;';
            $badgeStyle .= 'background-color:' . $categories[$event->getVar('catid')]['bgcolor'] . '!important;';
            $badgeStyle .= 'border-radius:50% !important;';
            $evTitle = \_MA_WGEVENTS_EVENT_NAME . ': ' . $event->getVar('name') . PHP_EOL;
            $evTitle .= \_MA_WGEVENTS_EVENT_DATE . ': ' . $eventHandler->getDateFromToText($event->getVar('datefrom'), $event->getVar('dateto'), $event->getVar('allday')) . PHP_EOL;
            if ($event->getVar('location')) {
                $evTitle .= \_MA_WGEVENTS_EVENT_LOCATION . ': ' . $event->getVar('location') . PHP_EOL;
            }
            $eventLink = '<a href="' . \WGEVENTS_URL . '/event.php?op=show&amp;id=' . $event->getVar('id') .'" title="' . $evTitle .'">';
            $eventLink .= '<span class="badge" style="' . $badgeStyle . '">&nbsp;&nbsp;</span></a>';
            $calendar->addDailyHtml($eventLink, $event->getVar('datefrom'), $event->getVar('dateto'));
        }
    }
    $GLOBALS['xoopsTpl']->assign('events_calendar', $calendar->render());
    $GLOBALS['xoopsTpl']->assign('events_calendar_header', '');

    if($limit > 0) {
        $crEvent = new \CriteriaCompo();
        $crEvent->add(new \Criteria('status', Constants::STATUS_SUBMITTED, '>'));
        $crEvent->add(new \Criteria('datefrom', \time(), '>'));
        $crEvent->setStart();
        $crEvent->setLimit($limit);
        $crEvent->setSort('datefrom');
        $crEvent->setOrder('ASC');

        if ($eventsCount > 0) {
            $eventsAll = $eventHandler->getAll($crEvent);
            $eventsList = [];
            // Get All Event
            foreach (\array_keys($eventsAll) as $i) {
                $eventsList[$i] = $eventsAll[$i]->getValuesEvents();
            }
            $GLOBALS['xoopsTpl']->assign('events_list', $eventsList);
            unset($eventsList);
        }
    }

    $GLOBALS['xoopsTpl']->assign('wgevents_upload_catlogos_url', \WGEVENTS_UPLOAD_CATLOGOS_URL);
    $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL);
    $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL . '/');

    //create dummy return in order to show block
    return ['dummy'];

}

/**
 * Function edit block
 * @param  $options
 * @return string
 */
function b_wgevents_calendar_edit($options)
{
    $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
    $form = "<input type='hidden' name='options[0]' value='".$options[0]."' >";
    $form .= \_MB_WGEVENTS_CAL_DISPLAY_DESC . "<br>";
    $form .= \_MB_WGEVENTS_CAL_DISPLAY . " : <input type='text' name='options[1]' size='5' maxlength='255' value='" . $options[1] . "' >&nbsp;<br>";
    \array_shift($options);
    \array_shift($options);

    $crEvent = new \CriteriaCompo();
    $crEvent->add(new \Criteria('id', 0, '!='));
    $crEvent->setSort('id');
    $crEvent->setOrder('ASC');

    /**
     * If you want to filter your results by e.g. a category used in yourevents
     * then you can activate the following code, but you have to change it according your category
     */
    /*
    $helper = Helper::getInstance();
    $eventHandler = $helper->getHandler('Event');
    $eventsAll = $eventHandler->getAll($crEvent);
    unset($crEvent);
    $form .= \_MB_WGEVENTS_EVENTS_TO_DISPLAY . "<br><select name='options[]' multiple='multiple' size='5'>";
    $form .= "<option value='0' " . (!\in_array(0, $options) && !\in_array('0', $options) ? '' : "selected='selected'") . '>' . \_MB_WGEVENTS_ALL_EVENTS . '</option>';
    foreach (\array_keys($eventsAll) as $i) {
        $id = $eventsAll[$i]->getVar('id');
        $form .= "<option value='" . $id . "' " . (!\in_array($id, $options) ? '' : "selected='selected'") . '>' . $eventsAll[$i]->getVar('name') . '</option>';
    }
    $form .= '</select>';
    */

    return $form;

}
