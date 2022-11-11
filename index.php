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

$op         = Request::getCmd('op', 'coming');
$start      = Request::getInt('start');
$limit      = Request::getInt('limit', (int)$helper->getConfig('userpager'));
$catId      = Request::getInt('cat_id');
$filterCats = Request::getArray('filter_cats');
if ($catId > 0 && 0 == \count($filterCats)) {
    $filterCats[] = $catId;
}
$urlCats = Request::getString('cats');
if (0 == \count($filterCats) && '' != $urlCats) {
    $filterCats = \explode(',', $urlCats);
}

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);
// Keywords
$keywords = [];
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_INDEX];
// Paths
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', \XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_icons_url_32', \WGEVENTS_ICONS_URL_32);
// JS
$GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
$GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/expander/jquery.expander.min.js');

//preferences
$GLOBALS['xoopsTpl']->assign('index_header', $helper->getConfig('index_header'));
$GLOBALS['xoopsTpl']->assign('user_maxchar', $helper->getConfig('user_maxchar'));
$indexDisplayCats = (string)$helper->getConfig('index_displaycats');
$GLOBALS['xoopsTpl']->assign('index_displaycats', $indexDisplayCats);
$indexDisplayEvents = (string)$helper->getConfig('index_displayevents');
$GLOBALS['xoopsTpl']->assign('index_displayevents', $indexDisplayEvents);
$useGroups = (bool)$helper->getConfig('use_groups');
$gmapsEnableEvent = false;
$gmapsHeight      = false;
$useGMaps         = (bool)$helper->getConfig('use_gmaps');
if ($useGMaps) {
    $gmapsPositionList = (string)$helper->getConfig('gmaps_enableindex');
    $gmapsEnableEvent  = ('top' == $gmapsPositionList || 'bottom' == $gmapsPositionList);
    $gmapsHeight       = $helper->getConfig('gmaps_height');
}

//misc
$GLOBALS['xoopsTpl']->assign('categoryCurrent', $catId);
$catName = '';

$uidCurrent  = 0;
$userIsAdmin = false;
if (\is_object($GLOBALS['xoopsUser'])) {
    $uidCurrent  = $GLOBALS['xoopsUser']->uid();
    $userIsAdmin = $GLOBALS['xoopsUser']->isAdmin();
}

if ('none' != $indexDisplayCats) {
    $GLOBALS['xoopsTpl']->assign('wgevents_upload_catlogos_url', \WGEVENTS_UPLOAD_CATLOGOS_URL);
    $categories = $categoryHandler->getCategoriesForFilter($indexDisplayCats, $filterCats, $op, $useGroups, '');
    $GLOBALS['xoopsTpl']->assign('categories', $categories);
}

$indexDisplayEvents = (string)$helper->getConfig('index_displayevents');
$GLOBALS['xoopsTpl']->assign('index_displayevents', $indexDisplayEvents);

if ('none' != $indexDisplayEvents) {
    $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL);
    $listDescr = '';
    if ('past' == $op) {
        $GLOBALS['xoopsTpl']->assign('showBtnComing', true);
        $listDescr = \_MA_WGEVENTS_EVENTS_LISTPAST;
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENTS_LISTPAST];
    } else {
        $GLOBALS['xoopsTpl']->assign('showBtnPast', true);
        $listDescr = \_MA_WGEVENTS_EVENTS_LISTCOMING;
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENTS_LISTCOMING];
    }
    /* $catId only for buttons*/
    /*
    if ($catId > 0) {
        //$crEvent->add(new \Criteria('catid', $catId));
        $listDescr .= ' - ' . $catName;
    }*/

    $GLOBALS['xoopsTpl']->assign('listDescr', $listDescr);

    // get events
    $eventsArr = $eventHandler->getEvents($start, $limit, \time(), 0, '', '', $op, 0, '', $filterCats);

    $eventsCount = $eventsArr['count'];

    $GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
    if ($eventsCount > 0) {
        $eventsAll = $eventsArr['eventsAll'];
        $events = [];
        $eventsMap = [];
        $evName = '';
        // Get All Event
        foreach (\array_keys($eventsAll) as $i) {
            $events[$i] = $eventsAll[$i]->getValuesEvents();
            $events[$i]['locked'] = (Constants::STATUS_LOCKED == $events[$i]['status']);
            $events[$i]['canceled'] = (Constants::STATUS_CANCELED == $events[$i]['status']);
            $permEdit = $permissionsHandler->getPermEventsEdit($events[$i]['submitter'], $events[$i]['status']) || $uidCurrent == $events[$i]['submitter'];
            $events[$i]['permEdit'] = $permEdit;

            $crRegistration = new \CriteriaCompo();
            $crRegistration->add(new \Criteria('evid', $i));
            $numberRegCurr = $registrationHandler->getCount($crRegistration);
            $events[$i]['nb_registrations'] = $numberRegCurr;
            $registerMax = (int)$events[$i]['register_max'];
            if ($registerMax > 0) {
                $events[$i]['regmax'] = $registerMax;
                $proportion = $numberRegCurr / $registerMax;
                if ($proportion >= 1) {
                    $events[$i]['regcurrent'] = \_MA_WGEVENTS_REGISTRATIONS_FULL;
                } else {
                    $events[$i]['regcurrent'] = \sprintf(\_MA_WGEVENTS_REGISTRATIONS_NBCURR_INDEX, $numberRegCurr, $registerMax);
                }
                $events[$i]['regcurrent_text'] = $events[$i]['regcurrent'];
                $events[$i]['regcurrent_tip'] = true;
                if ($proportion < 0.75) {
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
            if ($useGMaps && $gmapsEnableEvent && (float)$eventsAll[$i]->getVar('locgmlat') > 0) {
                $eventsMap[$i] = [
                    'name' => $evName,
                    'location' => $events[$i]['location_text_user'],
                    'from' => $events[$i]['datefrom_text'],
                    'url' => 'event.php?op=show&id=' . $i,
                    'lat'  => (float)$eventsAll[$i]->getVar('locgmlat'),
                    'lon'  => (float)$eventsAll[$i]->getVar('locgmlon')
                ];
            }
        }
        $GLOBALS['xoopsTpl']->assign('events', $events);
        if ('show' == $op && $useGMaps) {
            $GLOBALS['xoopsTpl']->assign('gmapsShow', true);
        }
        if ($useGMaps && count($eventsMap) > 0) {
            $GLOBALS['xoopsTpl']->assign('gmapsShowList', true);
            $GLOBALS['xoopsTpl']->assign('gmapsEnableEvent', $gmapsEnableEvent);
            $GLOBALS['xoopsTpl']->assign('gmapsHeight', $gmapsHeight);
            $GLOBALS['xoopsTpl']->assign('gmapsPositionList', $gmapsPositionList);
            $GLOBALS['xoopsTpl']->assign('api_key', $helper->getConfig('gmaps_api'));
            $GLOBALS['xoopsTpl']->assign('eventsMap', $eventsMap);
        }
        unset($events, $eventMaps);
        // Display Navigation
        if ($eventsCount > $limit) {
            $urlCats = \implode(',', $filterCats);
            require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav = new \XoopsPageNav($eventsCount, $limit, $start, 'start', 'op=list&limit=' . $limit . '&cat_id=' . $catId . '&amp;cats=' . $urlCats);
            $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
        }
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
