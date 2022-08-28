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

$op       = Request::getCmd('op', 'list');
$regId    = Request::getInt('id');
$regEvid  = Request::getInt('evid');
$feeValue = Request::getFloat('feevalue');

$uidCurrent = \is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->uid() : 0;

XoopsLoad::load('xoopslogger');
$xoopsLogger = XoopsLogger::getInstance();
$xoopsLogger->activated = false;

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
            $registrationObj->setVar('paidamount', $feeValue);
        } else {
            $registrationObj->setVar('paidamount', 0);
        }
        // Insert Data
        if ($registrationHandler->insert($registrationObj)) {
            // create history
            $registrationhistHandler->createHistory($registrationObjOld, 'update');
            // TODO: Handle notification

            $mailsHandler = new MailHandler();
            $mailParams = $mailsHandler->getMailParam($eventObj, $regId);
            unset($mailsHandler);
            // find changes in table registrations
            $mailParams['infotext'] = $registrationHandler->getRegistrationsCompare($registrationObjOld, $registrationObj);

            // send notifications/confirmation emails
            $registerNotify = (string)$eventObj->getVar('register_notify', 'e');
            if ('' != $registerNotify) {
                // send notifications to emails of register_notify
                $notifyEmails = $eventHandler->getRecipientsNotify($registerNotify);
                if (\count($notifyEmails) > 0) {
                    foreach ($notifyEmails as $recipient) {
                        $taskHandler->createTask(Constants::MAIL_REG_NOTIFY_MODIFY, $recipient, json_encode($mailParams));
                    }
                }
            }
            $regEmail = $registrationObj->getVar('email');
            if ('' != $regEmail) {
                $taskHandler->createTask(Constants::MAIL_REG_CONFIRM_MODIFY, $regEmail, json_encode($mailParams));
            }
            // excetue mail sending by task handler
            $taskHandler->processTasks();
            header('Content-Type: application/json');
            echo json_encode(['status'=>'success','message'=>'no errors']);
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
            // TODO: Handle notification

            $mailsHandler = new MailHandler();
            $mailParams = $mailsHandler->getMailParam($eventObj, $regId);
            unset($mailsHandler);
            // find changes in table registrations
            $mailParams['infotext'] = $registrationHandler->getRegistrationsCompare($registrationObjOld, $registrationObj);

            // send notifications/confirmation emails
            $registerNotify = (string)$eventObj->getVar('register_notify', 'e');
            if ('' != $registerNotify) {
                // send notifications to emails of register_notify
                $notifyEmails = $eventHandler->getRecipientsNotify($registerNotify);
                if (\count($notifyEmails) > 0) {
                    foreach ($notifyEmails as $recipient) {
                        $taskHandler->createTask(Constants::MAIL_REG_NOTIFY_MODIFY, $recipient, json_encode($mailParams));
                    }
                }
            }
            $regEmail = $registrationObj->getVar('email');
            if ('' != $regEmail) {
                $taskHandler->createTask(Constants::MAIL_REG_CONFIRM_MODIFY, $regEmail, json_encode($mailParams));
            }
            // excetue mail sending by task handler
            $taskHandler->processTasks();
            header('Content-Type: application/json');
            echo json_encode(['status'=>'success','message'=>'no errors']);
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
            // TODO: Handle notification

            $mailsHandler = new MailHandler();
            $mailParams = $mailsHandler->getMailParam($eventObj, $regId);
            unset($mailsHandler);
            // find changes in table registrations
            $mailParams['infotext'] = $registrationHandler->getRegistrationsCompare($registrationObjOld, $registrationObj);

            // send notifications/confirmation emails
            $registerNotify = (string)$eventObj->getVar('register_notify', 'e');
            if ('' != $registerNotify) {
                // send notifications to emails of register_notify
                $notifyEmails = $eventHandler->getRecipientsNotify($registerNotify);
                if (\count($notifyEmails) > 0) {
                    foreach ($notifyEmails as $recipient) {
                        $taskHandler->createTask(Constants::MAIL_REG_NOTIFY_MODIFY, $recipient, json_encode($mailParams));
                    }
                }
            }
            $regEmail = $registrationObj->getVar('email');
            if ('' != $regEmail) {
                $taskHandler->createTask(Constants::MAIL_REG_CONFIRM_MODIFY, $regEmail, json_encode($mailParams));
            }
            // execute mail sending by task handler
            $taskHandler->processTasks();
            header('Content-Type: application/json');
            echo json_encode(['status'=>'success','message'=>'no errors']);
        }
        break;
}

