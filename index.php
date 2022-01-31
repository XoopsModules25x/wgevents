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

use Xmf\Request;
use XoopsModules\Wgevents;
use XoopsModules\Wgevents\Constants;

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_index.tpl';
require_once \XOOPS_ROOT_PATH . '/header.php';

// Permission
if (!$permissionsHandler->getPermEventsView()) {
    $GLOBALS['xoopsTpl']->assign('errorPerm', _NOPERM);
    require __DIR__ . '/footer.php';
    exit;
}

$op    = Request::getCmd('op', 'coming');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('userpager'));

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);
// Keywords
$keywords = [];
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_INDEX];
// Paths
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', \XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL . '/');
$GLOBALS['xoopsTpl']->assign('wgevents_icons_url_32', \WGEVENTS_ICONS_URL_32);
$GLOBALS['xoopsTpl']->assign('index_header', $helper->getConfig('index_header'));

$crEvents = new \CriteriaCompo();
if ('coming' == $op) {
    $crEvents->add(new \Criteria('datefrom', \time(), '>='));
    $crEvents->setSort('datefrom');
    $crEvents->setOrder('ASC');
    $GLOBALS['xoopsTpl']->assign('showBtnPast', true);
    $GLOBALS['xoopsTpl']->assign('listDescr', \_MA_WGEVENTS_EVENTS_LISTCOMING);
    $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENTS_LISTCOMING];
} else {
    $crEvents->add(new \Criteria('datefrom', \time(), '<'));
    $crEvents->setSort('datefrom');
    $crEvents->setOrder('DESC');
    $GLOBALS['xoopsTpl']->assign('showBtnComing', true);
    $GLOBALS['xoopsTpl']->assign('listDescr', \_MA_WGEVENTS_EVENTS_LISTPAST);
    $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENTS_LISTPAST];
}
$eventsCount = $eventsHandler->getCount($crEvents);
$GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
$crEvents->setStart($start);
$crEvents->setLimit($limit);

$eventsAll = $eventsHandler->getAll($crEvents);
if ($eventsCount > 0) {
    $events = [];
    $evName = '';
    // Get All Event
    foreach (\array_keys($eventsAll) as $i) {
        $events[$i] = $eventsAll[$i]->getValuesEvents();
        $events[$i]['locked'] = (Constants::STATUS_LOCKED == $events[$i]['status']);
        $events[$i]['canceled'] = (Constants::STATUS_CANCELED == $events[$i]['status']);

        $crRegistrations = new \CriteriaCompo();
        $crRegistrations->add(new \Criteria('evid', $i));
        $numberRegCurr = $registrationsHandler->getCount($crRegistrations);
        //$events[$i]['nb_registrations'] = $numberRegCurr;
        $registerMax = (int)$events[$i]['register_max'];
        if ($registerMax > 0) {
            $events[$i]['regmax'] = $registerMax;
            $proportion = $numberRegCurr / $registerMax;
            if ($proportion >= 1) {
                $events[$i]['regcurrent'] = \_MA_WGEVENTS_REGISTRATIONS_FULL;
            } else {
                $events[$i]['regcurrent'] = \sprintf(\_MA_WGEVENTS_REGISTRATIONS_NBFROM_INDEX, $numberRegCurr, $registerMax);
            }
            $events[$i]['regcurrent_text'] = $events[$i]['regcurrent'];
            $events[$i]['regcurrent_tip'] = true;
            if ($proportion < 0.5) {
                $events[$i]['regcurrentstate'] = 'success';
                $events[$i]['regcurrent'] = '';
            } elseif ($proportion < 0.75) {
                $events[$i]['regcurrentstate'] = 'success';
            } elseif ($proportion < 1) {
                $events[$i]['regcurrentstate'] = 'warning';
            } else {
                $events[$i]['regcurrentstate'] = 'danger';
                $events[$i]['regcurrent_tip'] = false;
            }
            $events[$i]['regpercentage'] = (int)($proportion * 100);
        }
        $evName = $eventsAll[$i]->getVar('name');
        $keywords[$i] = $evName;
    }
    $GLOBALS['xoopsTpl']->assign('events', $events);
    unset($events);
    // Display Navigation
    if ($eventsCount > $limit) {
        require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($eventsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
        $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
    }
}

// Keywords
wgeventsMetaKeywords($helper->getConfig('keywords') . ', ' . \implode(',', $keywords));
unset($keywords);
// Description
wgeventsMetaDescription(\_MA_WGEVENTS_INDEX_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/index.php');
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', \XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
require __DIR__ . '/footer.php';
