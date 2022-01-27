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
use XoopsModules\Wgevents\Common;

require __DIR__ . '/header.php';
// Get all request values
$op    = Request::getCmd('op', 'list');
$regId = Request::getInt('reg_id');
$evId  = Request::getInt('ev_id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_registrations.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registrations.php'));
        if ($evId > 0) {
            $adminObject->addItemButton(\_AM_WGEVENTS_ADD_REGISTRATION, 'registrations.php?op=new&amp;ev_id=' . $evId);
            $adminObject->addItemButton(\_AM_WGEVENTS_GOTO_FORMSELECT, 'registrations.php', 'list');
            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
            $crRegistrations = new \CriteriaCompo();
            $crRegistrations->add(new \Criteria('reg_evid', $evId));
            $registrationsCount = $registrationsHandler->getCount($crRegistrations);
            $GLOBALS['xoopsTpl']->assign('registrations_count', $registrationsCount);
            $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
            $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
            // Table view registrations
            if ($registrationsCount > 0) {
                $crRegistrations->setSort('reg_id');
                $crRegistrations->setOrder('DESC');
                $crRegistrations->setStart($start);
                $crRegistrations->setLimit($limit);
                $registrationsAll = $registrationsHandler->getAll($crRegistrations);
                foreach (\array_keys($registrationsAll) as $i) {
                    $registration = $registrationsAll[$i]->getValuesRegistrations();
                    $GLOBALS['xoopsTpl']->append('registrations_list', $registration);
                    unset($registration);
                }
                // Display Navigation
                if ($registrationsCount > $limit) {
                    require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                    $pagenav = new \XoopsPageNav($registrationsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
                }
            } else {
                $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_REGISTRATIONS);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('eventsHeader', \sprintf(_AM_WGEVENTS_LIST_REGISTRATIONS_LAST, $limit));
            $eventsCount = $eventsHandler->getCountEvents();
            $GLOBALS['xoopsTpl']->append('events_count', $eventsCount);
            // Table view events
            if ($eventsCount > 0) {
                $eventsAll = $eventsHandler->getAllEvents($start, $limit);
                foreach (\array_keys($eventsAll) as $i) {
                    $event = $eventsAll[$i]->getValuesEvents();
                    $crRegistrations = new \CriteriaCompo();
                    $crRegistrations->add(new \Criteria('reg_evid', $i));
                    $registrationsCount = $registrationsHandler->getCount($crRegistrations);
                    $event['registrations'] = $registrationsCount;
                    $GLOBALS['xoopsTpl']->append('events_list', $event);
                    unset($event);
                }
            }
            $form = $eventsHandler->getFormEventSelect();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_registrations.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registrations.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_REGISTRATIONS, 'registrations.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $registrationsObj = $registrationsHandler->create();
        $registrationsObj->setVar('reg_evid', $evId);
        $form = $registrationsObj->getFormRegistrations();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_registrations.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registrations.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_REGISTRATIONS, 'registrations.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_REGISTRATION, 'registrations.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $regIdSource = Request::getInt('reg_id_source');
        // Get Form
        $registrationsObjSource = $registrationsHandler->get($regIdSource);
        $registrationsObj = $registrationsObjSource->xoopsClone();
        $form = $registrationsObj->getFormRegistrations();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('registrations.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($regId > 0) {
            $registrationsObj = $registrationsHandler->get($regId);
        } else {
            $registrationsObj = $registrationsHandler->create();
        }
        // Set Vars
        $registrationsObj->setVar('reg_evid', Request::getInt('reg_evid'));
        $registrationsObj->setVar('reg_salutation', Request::getInt('reg_salutation'));
        $registrationsObj->setVar('reg_firstname', Request::getString('reg_firstname'));
        $registrationsObj->setVar('reg_lastname', Request::getString('reg_lastname'));
        $registrationsObj->setVar('reg_email', Request::getString('reg_email'));
        $registrationsObj->setVar('reg_email_send', Request::getInt('reg_email_send'));
        $registrationsObj->setVar('reg_gdpr', Request::getInt('reg_gdpr'));
        $registrationsObj->setVar('reg_ip', Request::getString('reg_ip'));
        $registrationsObj->setVar('reg_status', Request::getInt('reg_status'));
        $registrationsObj->setVar('reg_financial', Request::getInt('reg_financial'));
        $registrationsObj->setVar('reg_listwait', Request::getInt('reg_listwait'));
        $registrationDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('reg_datecreated'));
        $registrationsObj->setVar('reg_datecreated', $registrationDatecreatedObj->getTimestamp());
        $registrationsObj->setVar('reg_submitter', Request::getInt('reg_submitter'));
        // Insert Data
        if ($registrationsHandler->insert($registrationsObj)) {
            $newRegId = $registrationsObj->getNewInsertedIdRegistrations();
            $permId = isset($_REQUEST['reg_id']) ? $regId : $newRegId;
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
                \redirect_header('registrations.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $registrationsObj->getHtmlErrors());
        $form = $registrationsObj->getFormRegistrations();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_registrations.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registrations.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_REGISTRATION, 'registrations.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_REGISTRATIONS, 'registrations.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $registrationsObj = $registrationsHandler->get($regId);
        $registrationsObj->setStart = $start;
        $registrationsObj->setLimit = $limit;
        $form = $registrationsObj->getFormRegistrations();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_registrations.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registrations.php'));
        $registrationsObj = $registrationsHandler->get($regId);
        $regEvid = $registrationsObj->getVar('reg_evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('registrations.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($registrationsHandler->delete($registrationsObj)) {
                //delete existing answers
                $answersHandler->cleanupAnswers($regEvid, $regId);
                \redirect_header('registrations.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $registrationsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'reg_id' => $regId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $registrationsObj->getVar('reg_evid')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';