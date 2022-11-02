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
$limit      = Request::getInt('limit', $helper->getConfig('userpager'));
$catId      = Request::getInt('cat_id');
$filterCats = Request::getArray('filter_cats');

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
    $GLOBALS['xoopsTpl']->assign('wgevents_upload_catlogos_url', \WGEVENTS_UPLOAD_CATLOGOS_URL . '/');
    $crCategory = new \CriteriaCompo();
    $crCategory->setSort('weight');
    $crCategory->setOrder('ASC');
    $categoriesCount = $categoryHandler->getCount($crCategory);
    $GLOBALS['xoopsTpl']->assign('categoriesCount', $categoriesCount);
    if ($categoriesCount > 0) {
        if ('form' == $indexDisplayCats) {
            $formCatsCb = $categoryHandler->getFormCatsCb($filterCats, $start, $limit, $op);
            $GLOBALS['xoopsTpl']->assign('formCatsCb', $formCatsCb->render());
        } else {
            //$crCategory->setStart($start);
            //$crCategory->setLimit($limit);
            $categoriesAll = $categoryHandler->getAll($crCategory);
            $categories = [];
            if ('button' === $indexDisplayCats) {
                $categories[0] = ['id' => 0, 'logo' => 'blank.gif', 'name' => 'alle', 'eventsCount' => 0];
            }
            $evName = '';
            // Get All Event
            foreach (\array_keys($categoriesAll) as $i) {
                $categories[$i] = $categoriesAll[$i]->getValuesCategories();
                $keywords[$i] = $categories[$i]['name'];
                if ($i == $catId) {
                    $catName = $categories[$i]['name'];
                }
                $crEvent = new \CriteriaCompo();
                $crEvent->add(new \Criteria('catid',$i));
                if ($useGroups) {
                    // current user
                    // - must have perm to see event or
                    // - must be event owner
                    // - is admin
                    if (!$userIsAdmin) {
                        $crEventGroup = new \CriteriaCompo();
                        $crEventGroup->add(new \Criteria('groups', '%00000%', 'LIKE')); //all users
                        if ($uidCurrent > 0) {
                            // Get groups
                            $memberHandler = \xoops_getHandler('member');
                            $xoopsGroups = $memberHandler->getGroupsByUser($uidCurrent);
                            foreach ($xoopsGroups as $group) {
                                $crEventGroup->add(new \Criteria('groups', '%' . substr('00000' . $group, -5) . '%', 'LIKE'), 'OR');
                            }
                        }
                        $crEventGroup->add(new \Criteria('submitter', $uidCurrent), 'OR');
                        $crEvent->add($crEventGroup);
                        unset($crEventGroup);
                    }
                }
                $eventsCount = $eventHandler->getCount($crEvent);
                $nbEventsText = \_MA_WGEVENTS_CATEGORY_NOEVENTS;
                if ($eventsCount > 0) {
                    if ($eventsCount > 1) {
                        $nbEventsText = \sprintf(\_MA_WGEVENTS_CATEGORY_EVENTS, $eventsCount);
                    } else {
                        $nbEventsText = \_MA_WGEVENTS_CATEGORY_EVENT;
                    }
                }
                $categories[$i]['nbeventsText'] = $nbEventsText;
                $categories[$i]['eventsCount'] = $eventsCount;
            }
            $GLOBALS['xoopsTpl']->assign('categories', $categories);
        }
    }
}

$indexDisplayEvents = (string)$helper->getConfig('index_displayevents');
$GLOBALS['xoopsTpl']->assign('index_displayevents', $indexDisplayEvents);

if ('none' != $indexDisplayEvents) {
    $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL . '/');
    $crEvent = new \CriteriaCompo();
    $listDescr = '';
    if ('past' == $op) {
        $crEvent->add(new \Criteria('datefrom', \time(), '<'));
        $crEvent->setSort('datefrom');
        $crEvent->setOrder('DESC');
        $GLOBALS['xoopsTpl']->assign('showBtnComing', true);
        $listDescr = \_MA_WGEVENTS_EVENTS_LISTPAST;
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENTS_LISTPAST];
    } else {
        $crEvent->add(new \Criteria('datefrom', \time(), '>='));
        $crEvent->setSort('datefrom');
        $crEvent->setOrder('ASC');
        $GLOBALS['xoopsTpl']->assign('showBtnPast', true);
        $listDescr = \_MA_WGEVENTS_EVENTS_LISTCOMING;
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENTS_LISTCOMING];
    }
    if ($catId > 0) {
        $crEvent->add(new \Criteria('catid', $catId));
        $listDescr .= ' - ' . $catName;
    }
    if (\count($filterCats) > 0) {
        $crEventCats = new \CriteriaCompo();
        $crEventCats->add(new \Criteria('catid', '(' . \implode(',', $filterCats) . ')', 'IN'));
        foreach ($filterCats as $filterCat) {
            $crEventCats->add(new \Criteria('subcats', '%"' . $filterCat . '"%', 'LIKE'), 'OR');
        }
        $crEvent->add($crEventCats);
    }

    if ($useGroups) {
        // current user
        // - must have perm to see event or
        // - must be event owner
        // - is admin
        if (!$userIsAdmin) {
            $crEventGroup = new \CriteriaCompo();
            $crEventGroup->add(new \Criteria('groups', '%00000%', 'LIKE')); //all users
            if ($uidCurrent > 0) {
                // Get groups
                $memberHandler = \xoops_getHandler('member');
                $xoopsGroups = $memberHandler->getGroupsByUser($uidCurrent);
                foreach ($xoopsGroups as $group) {
                    $crEventGroup->add(new \Criteria('groups', '%' . substr('00000' . $group, -5) . '%', 'LIKE'), 'OR');
                }
            }
            $crEventGroup->add(new \Criteria('submitter', $uidCurrent), 'OR');
            $crEvent->add($crEventGroup);
            unset($crEventGroup);
        }
    }
    $GLOBALS['xoopsTpl']->assign('listDescr', $listDescr);
    $eventsCount = $eventHandler->getCount($crEvent);
    $GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
    if ($eventsCount > 0) {
        $crEvent->setStart($start);
        $crEvent->setLimit($limit);
        $eventsAll = $eventHandler->getAll($crEvent);
        $events = [];
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
        }
        $GLOBALS['xoopsTpl']->assign('events', $events);
        unset($events);
        // Display Navigation
        if ($eventsCount > $limit) {
            require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav = new \XoopsPageNav($eventsCount, $limit, $start, 'start', 'op=list&limit=' . $limit . '&cat_id=' . $catId);
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
