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
    MailsHandler,
    Utility
};

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_registrations.tpl';
require_once \XOOPS_ROOT_PATH . '/header.php';

$op      = Request::getCmd('op', 'list');
$regId   = Request::getInt('id');
$regEvid = Request::getInt('evid');
$start   = Request::getInt('start');
$limit   = Request::getInt('limit', $helper->getConfig('userpager'));
$redir   = Request::getString('redir', 'list');

$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

if (Request::hasVar('cancel')) {
    $op = 'listmyevent';
}

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);
// Paths
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', \XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);
// Keywords
$keywords = [];
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_INDEX, 'link' => 'index.php'];
// Permissions
$permView = $permissionsHandler->getPermRegistrationView();
$GLOBALS['xoopsTpl']->assign('permView', $permView);

switch ($op) {
    case 'show':
    case 'list':
    default:

        break;
    case 'listmy':
        // Check permissions
        if (!$permissionsHandler->getPermRegistrationsSubmit()) {
            \redirect_header('registrations.php?op=list', 3, \_NOPERM);
        }
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_REGISTRATIONS_MYLIST];
        $events = [];
        $registrations = [];
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        $regIp = $_SERVER['REMOTE_ADDR'];
        // get all events with my registrations
        $sql = 'SELECT evid, name ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_registrations') . ' ';
        $sql .= 'INNER JOIN ' . $GLOBALS['xoopsDB']->prefix('wgevents_events') . ' ON ' . $GLOBALS['xoopsDB']->prefix('wgevents_registrations') . '.evid = ' . $GLOBALS['xoopsDB']->prefix('wgevents_events') . '.id ';
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_registrations') . '.ip)="' . $regIp . '")) ';
        $sql .= 'OR (((' . $GLOBALS['xoopsDB']->prefix('wgevents_registrations') . '.submitter)=' . $uidCurrent . ')) ';
        $sql .= 'GROUP BY ' . $GLOBALS['xoopsDB']->prefix('wgevents_registrations') . '.evid, ' . $GLOBALS['xoopsDB']->prefix('wgevents_events') . '.name ';
        $sql .= 'ORDER BY ' . $GLOBALS['xoopsDB']->prefix('wgevents_events') . '.datefrom DESC;';
        $result = $GLOBALS['xoopsDB']->query($sql);
        while (list($evId, $evName) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            $events[$evId] = [
                'id' => $evId,
                'name' => $evName
            ];
        }
        foreach ($events as $evId => $event) {
            // get all questions for this event
            $GLOBALS['xoopsTpl']->assign('redir', 'listmy');
            // get all questions for this event
            $questionsArr = $questionsHandler->getQuestionsByEvent($evId);
            $registrations[$evId]['questions'] = $questionsArr;
            $registrations[$evId]['footerCols'] = \count($questionsArr) + 9;
            //get list of existing registrations for current user/current IP
            $registrations[$evId]['event_name'] = $event['name'];
            $registrations[$evId]['details'] = $registrationsHandler->getRegistrationDetailsByEvent($evId, $questionsArr);
        }
        if (\count($registrations) > 0) {
            $GLOBALS['xoopsTpl']->assign('registrations', $registrations);
            unset($registrations);
        }
        break;
    case 'listmyevent':
    case 'listeventall':
        // Check params
        if (0 == $regEvid) {
            \redirect_header('index.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Check permissions
        if (!$permissionsHandler->getPermRegistrationsSubmit()) {
            \redirect_header('registrations.php?op=list', 3, \_NOPERM);
        }

        $captionList = \_MA_WGEVENTS_REGISTRATIONS_MYLIST;
        $currentUserOnly = true;
        if ('listeventall' == $op) {
            $captionList = \_MA_WGEVENTS_REGISTRATIONS_LIST;
            $currentUserOnly = false;
            $GLOBALS['xoopsTpl']->assign('showSubmitter', true);
        }
        $GLOBALS['xoopsTpl']->assign('captionList', $captionList);
        $GLOBALS['xoopsTpl']->assign('redir', $redir);
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_REGISTRATION_ADD];
        // get all questions for this event
        $questionsArr = $questionsHandler->getQuestionsByEvent($regEvid);

        //get list of existing registrations for current user/current IP
        $eventsObj = $eventsHandler->get($regEvid);
        $event_name = $eventsObj->getVar('name');
        $registrations[$regEvid]['id'] = $regEvid;
        $registrations[$regEvid]['event_name'] = $event_name;
        $registrations[$regEvid]['event_fee'] = $eventsObj->getVar('fee');
        $registrations[$regEvid]['event_register_max'] = $eventsObj->getVar('register_max');
        $registrations[$regEvid]['questions'] = $questionsArr;
        $registrations[$regEvid]['footerCols'] = \count($questionsArr) + 9;
        $registrations[$regEvid]['details'] = $registrationsHandler->getRegistrationDetailsByEvent($regEvid, $questionsArr, $currentUserOnly);
        if ($registrations) {
            $GLOBALS['xoopsTpl']->assign('registrations', $registrations);
            unset($registrations);
        }
        if ('listeventall' == $op) {
            $GLOBALS['xoopsTpl']->assign('showHandleList', true);
        } else {
            $permEdit = $permissionsHandler->getPermEventsEdit($eventsObj->getVar('submitter'), $eventsObj->getVar('status'));
            if ($permEdit ||
                (\time() >= $eventsObj->getVar('register_from') && \time() <= $eventsObj->getVar('register_to'))
                ) {
                // Form Create
                $registrationsObj = $registrationsHandler->create();
                $registrationsObj->setVar('evid', $regEvid);
                $registrationsObj->setRedir($redir);
                $form = $registrationsObj->getFormRegistrations();
                $GLOBALS['xoopsTpl']->assign('form', $form->render());
            }
            if (\time() < $eventsObj->getVar('register_from')) {
                $GLOBALS['xoopsTpl']->assign('warning_period', sprintf(\_MA_WGEVENTS_REGISTRATION_TOEARLY, \formatTimestamp($eventsObj->getVar('register_from'), 'm')));
            }
            if (\time() > $eventsObj->getVar('register_to')) {
                $GLOBALS['xoopsTpl']->assign('warning_period', sprintf(\_MA_WGEVENTS_REGISTRATION_TOLATE, \formatTimestamp($eventsObj->getVar('register_to'), 'm')));
            }
        }
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('registrations.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $regEvid = Request::getInt('evid');
        $eventsObj = $eventsHandler->get($regEvid);
        $evSubmitter = $eventsObj->getVar('submitter');
        $evStatus = $eventsObj->getVar('status');
        if ($regId > 0) {
            // Check permissions
            $registrationsObj = $registrationsHandler->get($regId);
            if (!$permissionsHandler->getPermRegistrationsEdit(
                    $registrationsObj->getVar('ip'),
                    $registrationsObj->getVar('submitter'),
                    $evSubmitter,
                    $evStatus,
                )) {
                    \redirect_header('registrations.php?op=list', 3, \_NOPERM);
            }
            $registrationsObj = $registrationsHandler->get($regId);
            $registrationsObjOld = $registrationsHandler->get($regId);
        } else {
            // Check permissions
            if (!$permissionsHandler->getPermRegistrationsSubmit()) {
                \redirect_header('registrations.php?op=list', 3, \_NOPERM);
            }
            $registrationsObj = $registrationsHandler->create();
        }
        // create item in table registrations
        $answersValueArr = [];
        $answersTypeArr = [];
        $answersValueArr = Request::getArray('id');
        $answersTypeArr = Request::getArray('type');
        $registrationsObj->setVar('evid', $regEvid);
        $registrationsObj->setVar('salutation', Request::getInt('salutation'));
        $registrationsObj->setVar('firstname', Request::getString('firstname'));
        $registrationsObj->setVar('lastname', Request::getString('lastname'));
        $regEmail = Request::getString('email');
        $registrationsObj->setVar('email', $regEmail);
        $registrationsObj->setVar('email_send', Request::getInt('email_send'));
        $registrationsObj->setVar('gdpr', Request::getInt('gdpr'));
        $registrationsObj->setVar('ip', Request::getString('ip'));
        $regVerifkey = ('' === Request::getString('verifkey')) ? xoops_makepass() : Request::getString('verifkey');
        $registrationsObj->setVar('verifkey', $regVerifkey);
        $regStatus = Request::getInt('status');
        $registrationsObj->setVar('status', $regStatus);
        $registrationsObj->setVar('financial', Request::getInt('financial'));
        $regListwait = 0;
        if ($regId > 0 || $permissionsHandler->getPermRegistrationsApprove($evSubmitter, $evStatus)) {
            //existing registration or user has perm to approve => take value of form
            $registrationsObj->setVar('listwait', Request::getInt('listwait'));
        } else {
            //check number of registrations
            $eventRegisterMax = (int)$eventsObj->getVar('register_max');
            if ($eventRegisterMax > 0) {
                $crRegCheck = new \CriteriaCompo();
                $crRegCheck->add(new \Criteria('evid', $regEvid));
                $numberRegCurr = $registrationsHandler->getCount($crRegCheck);
                if ($eventRegisterMax <= $numberRegCurr) {
                    $regListwait = 1;
                }
            }
            $registrationsObj->setVar('listwait', $regListwait);
        }
        if (Request::hasVar('datecreated_int')) {
            $registrationsObj->setVar('datecreated', Request::getInt('datecreated_int'));
        } else {
            $registrationDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
            $registrationsObj->setVar('datecreated', $registrationDatecreatedObj->getTimestamp());
        }
        $regSubmitter = Request::getInt('submitter');
        $registrationsObj->setVar('submitter', $regSubmitter);
        // Insert Data
        if ($registrationsHandler->insert($registrationsObj)) {
            $newRegId = $regId > 0 ? $regId : $registrationsObj->getNewInsertedIdRegistrations();
            if ($regId > 0) {
                // create copy before deleting
                // get all questions for this event
                $questionsArr = $questionsHandler->getQuestionsByEvent($regEvid);
                // get old answers for this questions
                $answersOld = $answersHandler->getAnswersDetailsByRegistration($newRegId, $questionsArr);
                // delete all existing answers
                $answersHandler->cleanupAnswers($regEvid, $regId);
            }
            // create items in table answers
            foreach ($answersValueArr as $key => $value) {
                $answer = '';
                switch ($answersTypeArr[$key]) {
                    case Constants::FIELD_CHECKBOX:
                        $answer = 1;
                        break;
                    default:
                        $answer = $value;
                        break;
                }
                if ('' != $answer) {
                    $answersObj = $answersHandler->create();
                    $answersObj->setVar('regid', $newRegId);
                    $answersObj->setVar('queid', $key);
                    $answersObj->setVar('evid', $regEvid);
                    $answersObj->setVar('text', $answer);
                    $answersObj->setVar('datecreated', \time());
                    $answersObj->setVar('submitter', $regSubmitter);
                    // Insert Data
                    $answersHandler->insert($answersObj);
                }
            }
            // Handle notification
            /*
            $regEvid = $registrationsObj->getVar('evid');
            $tags = [];
            $tags['ITEM_NAME'] = $regEvid;
            $tags['ITEM_URL']  = \XOOPS_URL . '/modules/wgevents/registrations.php?op=show&id=' . $regId;
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
            $infotext = '';
            $newRegistration = false;
            if ($regId > 0) {
                // find changes in table registrations
                $infotext = $registrationsHandler->getRegistrationsCompare($registrationsObjOld, $registrationsObj);
                if ('' != $infotext) {
                    // create history
                    $registrationshistHandler->createHistory($registrationsObjOld, 'update');
                }
                // find changes in table answers
                if (\is_array($answersOld)) {
                    // get new answers for this questions
                    $answersNew = $answersHandler->getAnswersDetailsByRegistration($newRegId, $questionsArr);
                    $result = $answersHandler->getAnswersCompare($answersOld, $answersNew);
                    if ('' != $result) {
                        // create history
                        $answershistHandler->createHistory($regEvid, $regId, 'update');
                    }
                    $infotext .= $result;
                }
                // other params
                $typeNotify  = Constants::MAIL_REG_NOTIFY_MODIFY;
                $typeConfirm = Constants::MAIL_REG_CONFIRM_MODIFY;
            } else {
                $newRegistration = true;
                if (1 == $regListwait) {
                    // registration was put on a waiting list
                    $infotext .= \_MA_WGEVENTS_MAIL_REG_IN_LISTWAIT . PHP_EOL;
                }
                if (Constants::STATUS_SUBMITTED == $regStatus) {
                    // user has no perm for autoverify
                    $verif = [
                        WGEVENTS_URL,
                        $regEvid,
                        $regEmail,
                        $regVerifkey
                    ];
                    $verifCode = base64_encode(implode('||', $verif));
                    $verifLink = WGEVENTS_URL . '/verification.php?actkey=' . $verifCode;
                    $infotext .= \sprintf(\_MA_WGEVENTS_MAIL_REG_IN_VERIF, $verifLink) . PHP_EOL;
                }
                if (1 == $regListwait || Constants::STATUS_SUBMITTED == $regStatus) {
                    // registration was put on a waiting list
                    $infotext .= \_MA_WGEVENTS_MAIL_REG_IN_FINAL . PHP_EOL;
                }
                $typeNotify  = Constants::MAIL_REG_NOTIFY_IN;
                $typeConfirm = Constants::MAIL_REG_CONFIRM_IN;
            }
            if ($newRegistration || '' != $infotext) {
                $registerNotify = (string)$eventsObj->getVar('register_notify', 'e');
                if ('' != $registerNotify) {
                    // send notifications to emails of register_notify
                    $notifyEmails   = preg_split("/\r\n|\n|\r/", $registerNotify);
                    $mailsHandler = new MailsHandler();
                    $mailsHandler->setInfo($infotext);
                    $mailsHandler->setNotifyEmails($notifyEmails);
                    $mailsHandler->executeReg($newRegId, $typeNotify);
                    unset($mailsHandler);
                }
                if ('' != $regEmail && Request::getInt('email_send') > 0) {
                    // send confirmation, if radio is checked
                    $mailsHandler = new MailsHandler();
                    $mailsHandler->setInfo($infotext);
                    $mailsHandler->setNotifyEmails($regEmail);
                    $mailsHandler->executeReg($newRegId, $typeConfirm);
                    unset($mailsHandler);
                }
            }
            // redirect after insert
            \redirect_header('registrations.php?op=' . $redir . '&amp;redir=' . $redir . '&amp;evid=' . $regEvid, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $registrationsObj->getHtmlErrors());
        $form = $registrationsObj->getFormRegistrations();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_REGISTRATION_EDIT];
        // Check params
        if (0 == $regId) {
            \redirect_header('registrations.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Check permissions
        $registrationsObj = $registrationsHandler->get($regId);
        $eventsObj = $eventsHandler->get($registrationsObj->getVar('evid'));
        if (!$permissionsHandler->getPermRegistrationsEdit(
                $registrationsObj->getVar('ip'),
                $registrationsObj->getVar('submitter'),
                $eventsObj->getVar('submitter'),
                $eventsObj->getVar('status'),
            )) {
                \redirect_header('registrations.php?op=list', 3, \_NOPERM);
        }
        // Get Form
        $registrationsObj = $registrationsHandler->get($regId);
        $registrationsObj->setRedir($redir);
        $registrationsObj->setStart = $start;
        $registrationsObj->setLimit = $limit;
        $form = $registrationsObj->getFormRegistrations();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'clone':
        echo 'noch nicht programmiert';die;
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_REGISTRATION_CLONE];
        // Check permissions
        if (!$permissionsHandler->getPermGlobalSubmit()) {
            \redirect_header('registrations.php?op=list', 3, \_NOPERM);
        }
        // Request source
        $regIdSource = Request::getInt('id_source');
        // Check params
        if (0 == $regIdSource) {
            \redirect_header('registrations.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $registrationsObjSource = $registrationsHandler->get($regIdSource);
        $registrationsObj = $registrationsObjSource->xoopsClone();
        $form = $registrationsObj->getFormRegistrations();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_REGISTRATION_DELETE];
        // Check params
        if (0 == $regId) {
            \redirect_header('index.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Check permissions
        $registrationsObj = $registrationsHandler->get($regId);
        $eventsObj = $eventsHandler->get($registrationsObj->getVar('evid'));
        if (!$permissionsHandler->getPermRegistrationsEdit(
                $registrationsObj->getVar('ip'),
                $registrationsObj->getVar('submitter'),
                $eventsObj->getVar('submitter'),
                $eventsObj->getVar('status'),
            )) {
                \redirect_header('registrations.php?op=list', 3, \_NOPERM);
        }
        $registrationsObj = $registrationsHandler->get($regId);
        $regParams['evid'] = $registrationsObj->getVar('evid');
        $regParams['firstname'] = $registrationsObj->getVar('firstname');
        $regParams['lastname'] = $registrationsObj->getVar('lastname');
        $regName = \trim($regParams['firstname'] . ' ' . $regParams['lastname']);
        $regParams['email'] = $registrationsObj->getVar('email');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('registrations.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // create history
            $registrationshistHandler->createHistory($registrationsObj, 'delete');
            if ($registrationsHandler->delete($registrationsObj)) {
                // create history
                $answershistHandler->createHistory($regParams['evid'], $regId, 'delete');
                //delete existing answers
                $answersHandler->cleanupAnswers($regParams['evid'], $regId);
                // Event delete notification
                /*
                $tags = [];
                $tags['ITEM_NAME'] = $regEvid;
                $notificationHandler = \xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'global_delete', $tags);
                $notificationHandler->triggerEvent('registrations', $regId, 'registration_delete', $tags);
                */
                // send notifications/confirmation emails
                $eventsObj = $eventsHandler->get($regParams['evid']);
                $typeNotify  = Constants::MAIL_REG_NOTIFY_OUT;
                $typeConfirm = Constants::MAIL_REG_CONFIRM_OUT;
                $registerNotify = (string)$eventsObj->getVar('register_notify', 'e');
                if ('' != $registerNotify) {
                    // send notifications to emails of register_notify
                    $notifyEmails   = preg_split("/\r\n|\n|\r/", $registerNotify);
                    $mailsHandler = new MailsHandler();
                    $mailsHandler->setNotifyEmails($notifyEmails);
                    $mailsHandler->executeRegDelete($regParams, $typeNotify);
                    unset($mailsHandler);
                }
                $regEmail = $regParams['email'];
                if ('' !=  $regEmail) {
                    // send confirmation, if radio is checked
                    $mailsHandler = new MailsHandler();
                    $mailsHandler->setNotifyEmails($regEmail);
                    $mailsHandler->executeRegDelete($regParams, $typeConfirm);
                    unset($mailsHandler);
                }

                \redirect_header('registrations.php?op=' . $redir . '&amp;redir=' . $redir . '&amp;id=' . $regId . '&amp;evid=' . $regEvid, 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $registrationsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $regId, 'evid' => $regEvid, 'op' => 'delete', 'redir' => $redir],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMDELETE_REGISTRATION, $regName),
                \_MA_WGEVENTS_CONFIRMDELETE_TITLE,
                \_MA_WGEVENTS_CONFIRMDELETE_LABEL
            );
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'change_financial':
        if (0 == $evId) {
            \redirect_header('registrations.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $eventsObj = $eventsHandler->get($evId);
        if (!$permissionsHandler->getPermRegistrationsApprove($eventsObj->getVar('submitter'), $eventsObj->getVar('status'))) {
            \redirect_header('registrations.php?op=list', 3, \_NOPERM);
        }
        if ($regId > 0) {
            // Check permissions
            $registrationsObj = $registrationsHandler->get($regId);
            $registrationsObjOld = $registrationsHandler->get($regId);
        } else {
            \redirect_header('registrations.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $registrationsObj->setVar('financial', Request::getInt('changeto'));
        // Insert Data
        if ($registrationsHandler->insert($registrationsObj)) {
            // create history
            $registrationshistHandler->createHistory($registrationsObjOld, 'update');
            $newRegId = $regId > 0 ? $regId : $registrationsObj->getNewInsertedIdRegistrations();
            // Handle notification
            /*
            $regEvid = $registrationsObj->getVar('evid');
            $tags = [];
            $tags['ITEM_NAME'] = $regEvid;
            $tags['ITEM_URL']  = \XOOPS_URL . '/modules/wgevents/registrations.php?op=show&id=' . $regId;
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
            $regEvid = $registrationsObj->getVar('evid');
            $eventsObj = $eventsHandler->get($regEvid);
            // find changes in table registrations
            $infotext = $registrationsHandler->getRegistrationsCompare($registrationsObjOld, $registrationsObj);
            $typeNotify  = Constants::MAIL_REG_NOTIFY_MODIFY;
            $typeConfirm = Constants::MAIL_REG_CONFIRM_MODIFY;
            $registerNotify = (string)$eventsObj->getVar('register_notify', 'e');
            if ('' != $registerNotify) {
                // send notifications to emails of register_notify
                $notifyEmails   = preg_split("/\r\n|\n|\r/", $registerNotify);
                $mailsHandler = new MailsHandler();
                $mailsHandler->setInfo($infotext);
                $mailsHandler->setNotifyEmails($notifyEmails);
                $mailsHandler->executeReg($newRegId, $typeNotify);
                unset($mailsHandler);
            }
            $regEmail = $registrationsObj->getVar('email');
            if ('' != $regEmail) {
                // send confirmation, if radio is checked
                $mailsHandler = new MailsHandler();
                $mailsHandler->setInfo($infotext);
                $mailsHandler->setNotifyEmails($regEmail);
                $mailsHandler->executeReg($newRegId, $typeConfirm);
                unset($mailsHandler);
            }
            // redirect after insert
            \redirect_header('registrations.php?op=' . $redir . '&amp;redir=' . $redir . '&amp;evid=' . $regEvid, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $registrationsObj->getHtmlErrors());
        $form = $registrationsObj->getFormRegistrations();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'listwait_takeover':
        if (0 == $evId) {
            \redirect_header('registrations.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        if (!$permissionsHandler->getPermRegistrationsApprove($eventsObj->getVar('submitter'), $eventsObj->getVar('status'))) {
            \redirect_header('registrations.php?op=list', 3, \_NOPERM);
        }
        if ($regId > 0) {
            // Check permissions
            $registrationsObj = $registrationsHandler->get($regId);
            // create history
            $registrationshistHandler->createHistory($registrationsObj, 'update');
        } else {
            \redirect_header('registrations.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $registrationsObj->setVar('listwait', 0);
        // Insert Data
        if ($registrationsHandler->insert($registrationsObj)) {
            $newRegId = $regId > 0 ? $regId : $registrationsObj->getNewInsertedIdRegistrations();
            // Handle notification
            /*
            $regEvid = $registrationsObj->getVar('evid');
            $tags = [];
            $tags['ITEM_NAME'] = $regEvid;
            $tags['ITEM_URL']  = \XOOPS_URL . '/modules/wgevents/registrations.php?op=show&id=' . $regId;
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
            $regEvid = $registrationsObj->getVar('evid');
            $eventsObj = $eventsHandler->get($regEvid);
            // find changes in table registrations
            $infotext = $registrationsHandler->getRegistrationsCompare($registrationsObjOld, $registrationsObj);

            $typeNotify  = Constants::MAIL_REG_NOTIFY_MODIFY;
            $typeConfirm = Constants::MAIL_REG_CONFIRM_MODIFY;
            $registerNotify = (string)$eventsObj->getVar('register_notify', 'e');
            if ('' != $registerNotify) {
                // send notifications to emails of register_notify
                $notifyEmails   = preg_split("/\r\n|\n|\r/", $registerNotify);
                $mailsHandler = new MailsHandler();
                $mailsHandler->setInfo($infotext);
                $mailsHandler->setNotifyEmails($notifyEmails);
                $mailsHandler->executeReg($newRegId, $typeNotify);
                unset($mailsHandler);
            }
            $regEmail = $registrationsObj->getVar('email');
            if ('' != $regEmail) {
                // send confirmation, if radio is checked
                $mailsHandler = new MailsHandler();
                $mailsHandler->setInfo($infotext);
                $mailsHandler->setNotifyEmails($regEmail);
                $mailsHandler->executeReg($newRegId, $typeConfirm);
                unset($mailsHandler);
            }
            // redirect after insert
            \redirect_header('registrations.php?op=' . $redir . '&amp;redir=' . $redir . '&amp;evid=' . $regEvid, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $registrationsObj->getHtmlErrors());
        $form = $registrationsObj->getFormRegistrations();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
}

// Keywords
wgeventsMetaKeywords($helper->getConfig('keywords') . ', ' . \implode(',', $keywords));
unset($keywords);

// Description
wgeventsMetaDescription(\_MA_WGEVENTS_REGISTRATIONS_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/registrations.php');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);

require __DIR__ . '/footer.php';
