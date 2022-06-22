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
    case 'new':
        $templateMain = 'wgevents_admin_registration.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registration.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_REGISTRATIONS, 'registration.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $registrationObj = $registrationHandler->create();
        $registrationObj->setVar('evid', $evId);
        $form = $registrationObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_registration.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registration.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_REGISTRATIONS, 'registration.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_REGISTRATION, 'registration.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $regIdSource = Request::getInt('id_source');
        // Get Form
        $registrationObjSource = $registrationHandler->get($regIdSource);
        $registrationObj = $registrationObjSource->xoopsClone();
        $form = $registrationObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('registration.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($regId > 0) {
            $registrationObj = $registrationHandler->get($regId);
        } else {
            $registrationObj = $registrationHandler->create();
        }
        // Set Vars
        $registrationObj->setVar('evid', Request::getInt('evid'));
        $registrationObj->setVar('salutation', Request::getInt('salutation'));
        $registrationObj->setVar('firstname', Request::getString('firstname'));
        $registrationObj->setVar('lastname', Request::getString('lastname'));
        $registrationObj->setVar('email', Request::getString('email'));
        $registrationObj->setVar('email_send', Request::getInt('email_send'));
        $registrationObj->setVar('gdpr', Request::getInt('gdpr'));
        $registrationObj->setVar('ip', Request::getString('ip'));
        $registrationObj->setVar('status', Request::getInt('status'));
        $registrationObj->setVar('financial', Request::getInt('financial'));
        $regPaidamount = Utility::StringToFloat(Request::getString('paidamount'));
        $registrationObj->setVar('paidamount', $regPaidamount);
        $registrationObj->setVar('listwait', Request::getInt('listwait'));
        $registrationDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $registrationObj->setVar('datecreated', $registrationDatecreatedObj->getTimestamp());
        $registrationObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($registrationHandler->insert($registrationObj)) {
            $newRegId = $registrationObj->getNewInsertedId();
            $permId = isset($_REQUEST['id']) ? $regId : $newRegId;
            $grouppermHandler = \xoops_getHandler('groupperm');
            $mid = $GLOBALS['xoopsModule']->getVar('mid');
            // Permission to view_registrations
            $grouppermHandler->deleteByModule($mid, 'wgevents_view_registrations', $permId);
            if (isset($_POST['groups_view_registrations'])) {
                foreach ($_POST['groups_view_registrations'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_view_registrations', $permId, $onegroupId, $mid);
                }
            }
            // Permission to submit_registrations
            $grouppermHandler->deleteByModule($mid, 'wgevents_submit_registrations', $permId);
            if (isset($_POST['groups_submit_registrations'])) {
                foreach ($_POST['groups_submit_registrations'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_submit_registrations', $permId, $onegroupId, $mid);
                }
            }
            // Permission to approve_registrations
            $grouppermHandler->deleteByModule($mid, 'wgevents_approve_registrations', $permId);
            if (isset($_POST['groups_approve_registrations'])) {
                foreach ($_POST['groups_approve_registrations'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_approve_registrations', $permId, $onegroupId, $mid);
                }
            }
                \redirect_header('registration.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $registrationObj->getHtmlErrors());
        $form = $registrationObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_registration.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registration.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_REGISTRATION, 'registration.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_REGISTRATIONS, 'registration.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $registrationObj = $registrationHandler->get($regId);
        $registrationObj->setStart = $start;
        $registrationObj->setLimit = $limit;
        $form = $registrationObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
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
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $registrationObj->getVar('firstname'). ' ' . $registrationObj->getVar('lastname')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
