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
use XoopsModules\Wgevents\ {
    Constants,
    Common,
    Utility
};

require __DIR__ . '/header.php';
// Get all request values
$op    = Request::getCmd('op', 'list');
$regId = Request::getInt('id');
$evId  = Request::getInt('evid');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

$moduleDirName = \basename(\dirname(__DIR__));

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_registration.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registration.php'));
        if ($evId > 0) {
            $adminObject->addItemButton(\_AM_WGEVENTS_ADD_REGISTRATION, 'registration.php?op=new&amp;evid=' . $evId);
            $adminObject->addItemButton(\_AM_WGEVENTS_GOTO_FORMSELECT, 'registration.php', 'list');
            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
            $crRegistration = new \CriteriaCompo();
            $crRegistration->add(new \Criteria('evid', $evId));
            $registrationCount = $registrationHandler->getCount($crRegistration);
            $GLOBALS['xoopsTpl']->assign('registrationCount', $registrationCount);
            $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
            $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
            // Table view registrations
            if ($registrationCount > 0) {
                $crRegistration->setSort('id');
                $crRegistration->setOrder('DESC');
                //$crRegistration->setStart($start);
                //$crRegistration->setLimit($limit);
                $registrationAll = $registrationHandler->getAll($crRegistration);
                foreach (\array_keys($registrationAll) as $i) {
                    $registration = $registrationAll[$i]->getValuesRegistrations();
                    $GLOBALS['xoopsTpl']->append('registrations_list', $registration);
                    unset($registration);
                }
                /*
                // Display Navigation
                if ($registrationCount > $limit) {
                    require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                    $pagenav = new \XoopsPageNav($registrationCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
                }*/
            } else {
                $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_REGISTRATIONS);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('eventsHeader', \sprintf(_AM_WGEVENTS_LIST_EVENTS_LAST, $limit));
            $eventCount = $eventHandler->getCountEvents();
            $GLOBALS['xoopsTpl']->append('eventCount', $eventCount);
            // Table view events
            if ($eventCount > 0) {
                $eventAll = $eventHandler->getAllEvents($start, $limit);
                foreach (\array_keys($eventAll) as $i) {
                    $event = $eventAll[$i]->getValuesEvents();
                    $crRegistration = new \CriteriaCompo();
                    $crRegistration->add(new \Criteria('evid', $i));
                    $registrationCount = $registrationHandler->getCount($crRegistration);
                    $event['registrations'] = $registrationCount;
                    $GLOBALS['xoopsTpl']->append('events_list', $event);
                    unset($event);
                }
            }
        }
        break;
    /*
    case 'delete':
        $templateMain = 'wgevents_admin_registration.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registration.php'));
        $registrationObj = $registrationHandler->get($regId);
        $regEvid = $registrationObj->getVar('evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('registration.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($registrationHandler->delete($registrationObj)) {
                //delete existing answers
                $answerHandler->cleanupAnswers($regEvid, $regId);
                \redirect_header('registration.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $registrationObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $regId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $registrationObj->getVar('evid')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    */
}
require __DIR__ . '/footer.php';
