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
use XoopsModules\Wgevents\{
    Constants,
    Common,
    MailHandler,
    Utility
};

require __DIR__ . '/header.php';
require_once \XOOPS_ROOT_PATH . '/header.php';

$op      = Request::getCmd('op', 'list');
$regId   = Request::getInt('id');
$regEvid = Request::getInt('evid');
$regEvid=0;

$uidCurrent = \is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->uid() : 0;

switch ($op) {
    case 'show':
    case 'list':
    default:
        break;
    case 'change_financial':
        if (0 == $regEvid) {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_MA_WGEVENTS_INVALID_PARAM]);
            break;
        }
        $eventObj = $eventHandler->get($regEvid);
        if (!is_object($eventObj)) {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_MA_WGEVENTS_INVALID_PARAM]);
            break;
        }
        if (!$permissionsHandler->getPermRegistrationsApprove($eventObj->getVar('submitter'), $eventObj->getVar('status'))) {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_NOPERM]);
            break;
        }
        if ($regId > 0) {
            // Check permissions
            $registrationObj = $registrationHandler->get($regId);
            $registrationObjOld = $registrationHandler->get($regId);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_MA_WGEVENTS_INVALID_PARAM]);
            break;
        }
        $regFinancial = Request::getInt('changeto');
        $registrationObj->setVar('financial', $regFinancial);
        if (Constants::FINANCIAL_PAID == $regFinancial) {
            $registrationObj->setVar('paidamount', $eventObj->getVar('fee'));
        } else {
            $registrationObj->setVar('paidamount', 0);
        }
        // Insert Data
        if ($registrationHandler->insert($registrationObj)) {
            // create history
            $registrationhistHandler->createHistory($registrationObjOld, 'update');
            // Handle notification
            /*
            $regEvid = $registrationObj->getVar('evid');
            $tags = [];
            $tags['ITEM_NAME'] = $regEvid;
            $tags['ITEM_URL']  = \XOOPS_URL . '/modules/wgevents/registration.php?op=show&id=' . $regId;
            $notificationHandler = \xoops_getHandler('notification');
            if ($regId > 0) {
                // Event modify notification
                $notificationHandler->triggerEvent('global', 0, 'global_modify', $tags);
                $notificationHandler->triggerEvent('registrations', $newRegId, 'registration_modify', $tags);
            } else {
                // Event new notification
                $notificationHandler->triggerEvent('global', 0, 'global_new', $tags);
            }
            */
            // send notifications/confirmation emails
            $regEvid = $registrationObj->getVar('evid');
            $eventObj = $eventHandler->get($regEvid);
            // find changes in table registrations
            $infotext = $registrationHandler->getRegistrationsCompare($registrationObjOld, $registrationObj);
            $typeNotify  = Constants::MAIL_REG_NOTIFY_MODIFY;
            $typeConfirm = Constants::MAIL_REG_CONFIRM_MODIFY;
            $registerNotify = (string)$eventObj->getVar('register_notify', 'e');
            if ('' != $registerNotify) {
                // send notifications to emails of register_notify
                $notifyEmails = $eventHandler->getRecipientsNotify($registerNotify);
                if (\count($notifyEmails) > 0) {
                    $mailsHandler = new MailHandler();
                    $mailParams   = $mailsHandler->getMailParam($regEvid, $regId);
                    $mailParams['infotext'] = $infotext;
                    $mailParams['recipients'] = $notifyEmails;
                    $mailsHandler->setParams($mailParams);
                    $mailsHandler->setType($typeNotify);
                    $mailsHandler->executeReg();
                    unset($mailsHandler);
                }
            }
            $regEmail = $registrationObj->getVar('email');
            if ('' != $regEmail) {
                // send confirmation, if radio is checked
                $mailsHandler = new MailHandler();
                $mailParams = $mailsHandler->getMailParam($regEvid, $regId);
                $mailParams['infotext'] = $infotext;
                $mailParams['recipients'] = $mailParams['regEmail'];
                $mailsHandler->setParams($mailParams);
                $mailsHandler->setType($typeConfirm);
                $mailsHandler->executeReg();
                unset($mailsHandler);
            }
        }
        break;
    case 'listwait_takeover':
        if (0 == $regEvid) {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_MA_WGEVENTS_INVALID_PARAM]);
            break;
        }
        $eventObj = $eventHandler->get($regEvid);
        if (!is_object($eventObj)) {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_MA_WGEVENTS_INVALID_PARAM]);
            break;
        }
        if (!$permissionsHandler->getPermRegistrationsApprove($eventObj->getVar('submitter'), $eventObj->getVar('status'))) {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_NOPERM]);
            break;
        }
        if ($regId > 0) {
            // create two objects of current registration
            $registrationObj = $registrationHandler->get($regId);
            $registrationObjOld = $registrationHandler->get($regId);
            // create history
            $registrationhistHandler->createHistory($registrationObj, 'update');
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_MA_WGEVENTS_INVALID_PARAM]);
            break;
        }
        $registrationObj->setVar('listwait', 0);
        // Insert Data
        if ($registrationHandler->insert($registrationObj)) {
            // Handle notification
            /*
            $regEvid = $registrationObj->getVar('evid');
            $tags = [];
            $tags['ITEM_NAME'] = $regEvid;
            $tags['ITEM_URL']  = \XOOPS_URL . '/modules/wgevents/registration.php?op=show&id=' . $regId;
            $notificationHandler = \xoops_getHandler('notification');
            if ($regId > 0) {
                // Event modify notification
                $notificationHandler->triggerEvent('global', 0, 'global_modify', $tags);
                $notificationHandler->triggerEvent('registrations', $newRegId, 'registration_modify', $tags);
            } else {
                // Event new notification
                $notificationHandler->triggerEvent('global', 0, 'global_new', $tags);
            }
            */
            // send notifications/confirmation emails
            $regEvid = $registrationObj->getVar('evid');
            $eventObj = $eventHandler->get($regEvid);
            // find changes in table registrations
            $infotext = $registrationHandler->getRegistrationsCompare($registrationObjOld, $registrationObj);

            $typeNotify  = Constants::MAIL_REG_NOTIFY_MODIFY;
            $typeConfirm = Constants::MAIL_REG_CONFIRM_MODIFY;
            $registerNotify = (string)$eventObj->getVar('register_notify', 'e');
            if ('' != $registerNotify) {
                // send notifications to emails of register_notify
                $notifyEmails = $eventHandler->getRecipientsNotify($registerNotify);
                if (\count($notifyEmails) > 0) {
                    $mailsHandler = new MailHandler();
                    $mailParams   = $mailsHandler->getMailParam($regEvid, $regId);
                    $mailParams['infotext'] = $infotext;
                    $mailParams['recipients'] = $notifyEmails;
                    $mailsHandler->setParams($mailParams);
                    $mailsHandler->setType($typeNotify);
                    $mailsHandler->executeReg();
                    unset($mailsHandler);
                }
            }
            $regEmail = $registrationObj->getVar('email');
            if ('' != $regEmail) {
                // send confirmation, if radio is checked
                $mailsHandler = new MailHandler();
                $mailParams = $mailsHandler->getMailParam($regEvid, $regId);
                $mailParams['infotext'] = $infotext;
                $mailParams['recipients'] = $mailParams['regEmail'];
                $mailsHandler->setParams($mailParams);
                $mailsHandler->setType($typeConfirm);
                $mailsHandler->executeReg();
                unset($mailsHandler);
            }
        }
        break;
    case 'approve_status':
        if (0 == $regEvid) {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_MA_WGEVENTS_INVALID_PARAM]);
            break;
        }
        $eventObj = $eventHandler->get($regEvid);
        if (!is_object($eventObj)) {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_MA_WGEVENTS_INVALID_PARAM]);
            break;
        }
        if (!$permissionsHandler->getPermRegistrationsApprove($eventObj->getVar('submitter'), $eventObj->getVar('status'))) {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_NOPERM]);
            break;
        }
        if ($regId > 0) {
            // Check permissions
            $registrationObj = $registrationHandler->get($regId);
            $registrationObjOld = $registrationHandler->get($regId);
            // create history
            $registrationhistHandler->createHistory($registrationObj, 'update');
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error','message'=>\_MA_WGEVENTS_INVALID_PARAM]);
            break;
        }
        $registrationObj->setVar('listwait', 0);
        $registrationObj->setVar('status', Constants::STATUS_APPROVED);
        // Insert Data
        if ($registrationHandler->insert($registrationObj)) {
            // Handle notification
            /*
            $regEvid = $registrationObj->getVar('evid');
            $tags = [];
            $tags['ITEM_NAME'] = $regEvid;
            $tags['ITEM_URL']  = \XOOPS_URL . '/modules/wgevents/registration.php?op=show&id=' . $regId;
            $notificationHandler = \xoops_getHandler('notification');
            if ($regId > 0) {
                // Event modify notification
                $notificationHandler->triggerEvent('global', 0, 'global_modify', $tags);
                $notificationHandler->triggerEvent('registrations', $newRegId, 'registration_modify', $tags);
            } else {
                // Event new notification
                $notificationHandler->triggerEvent('global', 0, 'global_new', $tags);
            }
            */
            // send notifications/confirmation emails
            $regEvid = $registrationObj->getVar('evid');
            $eventObj = $eventHandler->get($regEvid);
            // find changes in table registrations
            $infotext = $registrationHandler->getRegistrationsCompare($registrationObjOld, $registrationObj);

            $typeNotify  = Constants::MAIL_REG_NOTIFY_MODIFY;
            $typeConfirm = Constants::MAIL_REG_CONFIRM_MODIFY;
            $registerNotify = (string)$eventObj->getVar('register_notify', 'e');
            if ('' != $registerNotify) {
                // send notifications to emails of register_notify
                $notifyEmails = $eventHandler->getRecipientsNotify($registerNotify);
                if (\count($notifyEmails) > 0) {
                    $mailsHandler = new MailHandler();
                    $mailParams   = $mailsHandler->getMailParam($regEvid, $regId);
                    $mailParams['infotext'] = $infotext;
                    $mailParams['recipients'] = $notifyEmails;
                    $mailsHandler->setParams($mailParams);
                    $mailsHandler->setType($typeNotify);
                    $mailsHandler->executeReg();
                    unset($mailsHandler);
                }
            }
            $regEmail = $registrationObj->getVar('email');
            if ('' != $regEmail) {
                // send confirmation, if radio is checked
                $mailsHandler = new MailHandler();
                $mailParams = $mailsHandler->getMailParam($regEvid, $regId);
                $mailParams['infotext'] = $infotext;
                $mailParams['recipients'] = $mailParams['regEmail'];
                $mailsHandler->setParams($mailParams);
                $mailsHandler->setType($typeConfirm);
                $mailsHandler->executeReg();
                unset($mailsHandler);
            }
            // redirect after insert
            \redirect_header('registration.php?op=' . $redir . '&amp;redir=' . $redir . '&amp;evid=' . $regEvid, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $registrationObj->getHtmlErrors());
        $form = $registrationObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
}

