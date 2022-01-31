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
    $eventsHandler = $helper->getHandler('Events');
    $categoriesHandler = $helper->getHandler('Categories');

    \xoops_loadLanguage('main', 'wgevents');

    //default params
    $year     = (int)\date('Y');
    $month    = (int)\date('n');
    $lastday  = (int)\date('t', \strtotime($month . '/1/' . $year));
    $dayStart = \mktime(0, 0, 0, $month, 1, $year);
    $dayEnd   = \mktime(23, 59, 59, $month, $lastday, $year);

    $filterFrom = $dayStart;
    $filterTo   = $dayEnd;
    $filterCat     = 0;
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
    $categories = $categoriesHandler->getCategoriesCollection();
    // get events of period
    $events = $eventsHandler->getEvents(0, 0, $filterFrom, $filterTo, $filterCat, $sortBy, $orderBy);

    $eventsCount = \count($events);
    if ($eventsCount > 0) {
        $calendar->setDate($filterFrom);
        $GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
        foreach($events as $event) {
            $badgeStyle = 'border:1px solid ' . $categories[$event['catid']]['bordercolor'] . '!important;';
            $badgeStyle .= 'background-color:' . $categories[$event['catid']]['bgcolor'] . '!important;';
            $badgeStyle .= 'border-radius:50% !important;';
            $eventLink = '<a href="' . \WGEVENTS_URL . '/events.php?op=show&amp;id=' . $event['id'] .'">';
            $eventLink .= '<span class="badge" style="' . $badgeStyle . '">&nbsp;&nbsp;</span></a>';
            $calendar->addDailyHtml($eventLink, $event['datefrom'], $event['dateto']);
        }
    }
    $GLOBALS['xoopsTpl']->assign('events_calendar', $calendar->render());
    $GLOBALS['xoopsTpl']->assign('events_calendar_header', '');

    if($limit > 0) {
        $crEvents = new \CriteriaCompo();
        $crEvents->add(new \Criteria('status', Constants::STATUS_SUBMITTED, '>'));
        $crEvents->add(new \Criteria('datefrom', \time(), '>'));
        $crEvents->setStart();
        $crEvents->setLimit($limit);
        $crEvents->setSort('datefrom');
        $crEvents->setOrder('ASC');

        if ($eventsCount > 0) {
            $eventsAll = $eventsHandler->getAll($crEvents);
            $eventsList = [];
            // Get All Events
            foreach (\array_keys($eventsAll) as $i) {
                $eventsList[$i] = $eventsAll[$i]->getValuesEvents();
            }
            $GLOBALS['xoopsTpl']->assign('events_list', $eventsList);
            unset($eventsList);
        }
    }

    $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL . '/');

    $block = ['dummy']; //create dummy return in order to show block
    return $block;

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

    $crEvents = new \CriteriaCompo();
    $crEvents->add(new \Criteria('id', 0, '!='));
    $crEvents->setSort('id');
    $crEvents->setOrder('ASC');

    /**
     * If you want to filter your results by e.g. a category used in yourevents
     * then you can activate the following code, but you have to change it according your category
     */
    /*
    $helper = Helper::getInstance();
    $eventsHandler = $helper->getHandler('Events');
    $eventsAll = $eventsHandler->getAll($crEvents);
    unset($crEvents);
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
