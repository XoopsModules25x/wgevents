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

$op = Request::getCmd('op', 'list');
if ('show' === $op) {
    $GLOBALS['xoopsOption']['template_main'] = 'wgevents_registration_single.tpl';
} else  {
    $GLOBALS['xoopsOption']['template_main'] = 'wgevents_registration.tpl';
}
require_once \XOOPS_ROOT_PATH . '/header.php';

$regId    = Request::getInt('id');
$regEvid  = Request::getInt('evid');
$start    = Request::getInt('start');
$limit    = Request::getInt('limit', $helper->getConfig('userpager'));
$redir    = Request::getString('redir', 'list');
$showinfo = Request::getInt('showinfo');
//$sortBy  = Request::getString('sortby', 'datecreated');
//$orderBy = Request::getString('orderby', 'asc');

$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);
//$GLOBALS['xoopsTpl']->assign('sort_order', $sortBy . '_' . $orderBy);
$GLOBALS['xoopsTpl']->assign('evid', $regEvid);
if (1 === $showinfo) {
    $GLOBALS['xoopsTpl']->assign('warning', \_MA_WGEVENTS_REGISTRATION_INFO_SPAM);
}

if (Request::hasVar('cancel')) {
    $op = 'listeventmy';
}
if (Request::hasVar('exec_contactall_test')) {
    $op = 'exec_contactall_test';
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
// Permission
$permView = $permissionsHandler->getPermRegistrationView();
$GLOBALS['xoopsTpl']->assign('permView', $permView);

$uidCurrent = \is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->uid() : 0;

switch ($op) {
    case 'show':
        $verifKey = Request::getString('verifkey');
        $verifKeyArray  = explode('||', base64_decode($verifKey, true));
        // for testing purposes //$verifKeyArray = [5, 'http://localhost/wgevents/htdocs/modules/wgevents', 1, 'james.morrison@domain.com', 'z4NBI7sYn3'];
        $regId = $verifKeyArray[0];
        $registrationObj = $registrationHandler->get($regId);
        $eventName = $eventHandler->get($registrationObj->getVar('evid'))->getVar('name');
        if ($regId > 0 && \is_object($registrationObj) && \WGEVENTS_URL == (string)$verifKeyArray[1] &&
            (int)$registrationObj->getVar('evid') == (int)$verifKeyArray[2] &&
            (string)$registrationObj->getVar('email') == (string)$verifKeyArray[3] &&
            (string)$registrationObj->getVar('verifkey') == (string)$verifKeyArray[4]) {
                $registration = [];
                // get all detail of this registration
                $registration = $registrationObj->getValuesRegistrations();
                // get event info
                $evId = $registrationObj->getVar('evid');
                $eventObj = $eventHandler->get($evId);
                $event = $eventObj->getValuesEvents();
                $questionsArr = $questionHandler->getQuestionsByEvent($evId);
                $registration['questions'] = $questionsArr;

                // get all answers for this event
                $answers = $answerHandler->getAnswersDetailsByRegistration($regId, $questionsArr);
                foreach ($questionsArr as $key => $value) {
                    $question_answer[$key]['caption'] = $value['caption'];
                    $question_answer[$key]['answer'] = $answers[$key];
                }
                $registration['questions'] = \count($question_answer);
                $registration['question_answer'] = $question_answer;

                $GLOBALS['xoopsTpl']->assign('event', $event);
                $GLOBALS['xoopsTpl']->assign('registration', $registration);
                $GLOBALS['xoopsTpl']->assign('verifKey', $verifKey);
                $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL);
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \sprintf(\_MA_WGEVENTS_MAIL_REG_VERIF_ERROR, $eventName));
        }
    case 'list':
    default:
        break;
    case 'listmy':
        // Check permissions
        if (!$permissionsHandler->getPermRegistrationsSubmit()) {
            \redirect_header('registration.php?op=list', 3, \_NOPERM);
        }
        $GLOBALS['xoopsTpl']->assign('redir', 'listmy');
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_REGISTRATIONS_MYLIST];
        $events = [];
        $registrations = [];
        $regIp = $_SERVER['REMOTE_ADDR'];
        // get all events with my registrations
        $sql = 'SELECT evid, name, ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . '.submitter as ev_submitter, ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . '.status as ev_status ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . ' ';
        $sql .= 'INNER JOIN ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . ' ON ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '.evid = ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . '.id ';
        $sql .= 'WHERE (';
        if ($uidCurrent > 0) {
            $sql .= '(' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '.submitter)=' . $uidCurrent;
        } else {
            $sql .= '(' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '.ip)="' . $regIp . '"';
        }
        $sql .= ') GROUP BY ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '.evid, ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . '.name ';
        $sql .= 'ORDER BY ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . '.datefrom DESC;';
        $result = $GLOBALS['xoopsDB']->query($sql);
        while (list($evId, $evName, $evSubmitter, $evStatus) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            $events[$evId] = [
                'id' => $evId,
                'name' => $evName,
                'submitter' => $evSubmitter,
                'status' => $evStatus
            ];
        }
        foreach ($events as $evId => $event) {
            // get all questions for this event
            $questionsArr = $questionHandler->getQuestionsByEvent($evId);
            $registrations[$evId]['questions'] = $questionsArr;
            $registrations[$evId]['footerCols'] = \count($questionsArr) + 9;
            //get list of existing registrations for current user/current IP
            $registrations[$evId]['event_id'] = $event['id'];
            $registrations[$evId]['event_name'] = $event['name'];
            $permEdit = $permissionsHandler->getPermEventsEdit($event['submitter'], $event['status']) || $uidCurrent == $event['submitter'];
            $registrations[$evId]['permEditEvent'] = $permEdit;
            $registrations[$evId]['details'] = $registrationHandler->getRegistrationDetailsByEvent($evId, $questionsArr);
        }
        if (\count($registrations) > 0) {
            $GLOBALS['xoopsTpl']->assign('registrations', $registrations);
            unset($registrations);
        } else {
            $GLOBALS['xoopsTpl']->assign('warning', \_MA_WGEVENTS_REGISTRATIONS_THEREARENT);
        }
        break;
    case 'listeventmy': // list all registrations of current user of given event
    case 'listeventall': // list all registrations of all users of given event
        // Check params
        if (0 === $regEvid) {
            \redirect_header('index.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Check permissions
        if (!$permissionsHandler->getPermRegistrationsSubmit()) {
            \redirect_header('registration.php?op=list', 3, \_NOPERM);
        }

        $captionList = \_MA_WGEVENTS_REGISTRATIONS_MYLIST;
        $currentUserOnly = true;
        if ('listeventall' === $op) {
            $captionList = \_MA_WGEVENTS_REGISTRATIONS_LIST;
            $currentUserOnly = false;
            $GLOBALS['xoopsTpl']->assign('showSubmitter', true);
        }
        $GLOBALS['xoopsTpl']->assign('captionList', $captionList);
        $GLOBALS['xoopsTpl']->assign('redir', $op);
        $GLOBALS['xoopsTpl']->assign('op', $op);
        $GLOBALS['xoopsTpl']->assign('evid', $regEvid);

        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_REGISTRATION_ADD];
        // get all questions for this event
        $questionsArr = $questionHandler->getQuestionsByEvent($regEvid);

        //get list of existing registrations for current user/current IP
        $eventObj = $eventHandler->get($regEvid);
        $evSubmitter = (int)$eventObj->getVar('submitter');
        $permEdit = $permissionsHandler->getPermEventsEdit($evSubmitter, $eventObj->getVar('status')) || $uidCurrent == $evSubmitter;
    // list all registrations of all users of given event
    // user must have perm to edit event
    if ('listeventall' === $op && $uidCurrent !== $evSubmitter && !$permEdit) {
        \redirect_header('registration.php?op=list', 3, \_NOPERM);
    }
        $event_name = $eventObj->getVar('name');
        $registrations[$regEvid]['event_id'] = $regEvid;
        $registrations[$regEvid]['event_name'] = $event_name;
        $registrations[$regEvid]['permEditEvent'] = $permEdit;
        $registrations[$regEvid]['event_fee'] = $eventObj->getVar('fee');

        $evFee = \json_decode($eventObj->getVar('fee'), true);
        $evFeeArr = [];
        foreach($evFee as $fee) {
            $evFeeArr[] = ['text' => Utility::FloatToString((float)$fee[0]), 'value' => (float)$fee[0]];
        }
        $registrations[$regEvid]['evfees'] = $evFeeArr;
        $registrations[$regEvid]['evfees_count'] = \count($evFeeArr);
        $registrations[$regEvid]['event_register_max'] = $eventObj->getVar('register_max');
        $registrations[$regEvid]['questions'] = $questionsArr;
        $registrations[$regEvid]['footerCols'] = \count($questionsArr) + 9;
        $registrations[$regEvid]['details'] = $registrationHandler->getRegistrationDetailsByEvent($regEvid, $questionsArr, $currentUserOnly);
        if ($registrations) {
            $GLOBALS['xoopsTpl']->assign('registrations', $registrations);
            unset($registrations);
        }
        if ('listeventall' === $op) {
            $GLOBALS['xoopsTpl']->assign('showHandleList', true);
        } else {
            //$permEdit = $permissionsHandler->getPermEventsEdit($evSubmitter, $eventObj->getVar('status'));
            if ($permEdit ||
                (\time() >= $eventObj->getVar('register_from') && \time() <= $eventObj->getVar('register_to'))
                ) {
                // Form Create
                $registrationObj = $registrationHandler->create();
                $registrationObj->setVar('evid', $regEvid);
                $registrationObj->setRedir($redir);
                $form = $registrationObj->getForm();
                $GLOBALS['xoopsTpl']->assign('form', $form->render());
            }
            if (!$permEdit && \time() < $eventObj->getVar('register_from')) {
                $GLOBALS['xoopsTpl']->assign('warning', sprintf(\_MA_WGEVENTS_REGISTRATION_TOEARLY, \formatTimestamp($eventObj->getVar('register_from'), 'm')));
            }
            if (!$permEdit && \time() > $eventObj->getVar('register_to')) {
                $GLOBALS['xoopsTpl']->assign('warning', sprintf(\_MA_WGEVENTS_REGISTRATION_TOLATE, \formatTimestamp($eventObj->getVar('register_to'), 'm')));
            }
        }
        //assign language vars for js calls
        $GLOBALS['xoopsTpl']->assign('js_lang_paid', \_MA_WGEVENTS_REGISTRATION_FINANCIAL_PAID);
        $GLOBALS['xoopsTpl']->assign('js_lang_unpaid', \_MA_WGEVENTS_REGISTRATION_FINANCIAL_UNPAID);
        if (1 === \count($evFeeArr)) {
            $GLOBALS['xoopsTpl']->assign('js_feedefault_value', $evFeeArr[0]['value']);
            $GLOBALS['xoopsTpl']->assign('js_feedefault_text', $evFeeArr[0]['text']);

        }
        $GLOBALS['xoopsTpl']->assign('js_feezero_text', Utility::FloatToString(0));
        $GLOBALS['xoopsTpl']->assign('js_lang_changed', \_MA_WGEVENTS_REGISTRATION_CHANGED);
        $GLOBALS['xoopsTpl']->assign('js_lang_approved', \_MA_WGEVENTS_STATUS_APPROVED);
        $GLOBALS['xoopsTpl']->assign('js_lang_error_save', \_MA_WGEVENTS_ERROR_SAVE);

        // tablesorter
        $GLOBALS['xoopsTpl']->assign('tablesorter', true);
        $GLOBALS['xoopsTpl']->assign('mod_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('tablesorter_allrows', \_AM_WGEVENTS_TABLESORTER_SHOW_ALL);
        $GLOBALS['xoopsTpl']->assign('tablesorter_of', \_AM_WGEVENTS_TABLESORTER_OF);
        $GLOBALS['xoopsTpl']->assign('tablesorter_total', \_AM_WGEVENTS_TABLESORTER_TOTALROWS);
        $GLOBALS['xoopsTpl']->assign('tablesorter_pagesize', $helper->getConfig('userpager'));
        if ('d.m.Y' === \_SHORTDATESTRING) {
            $dateformat = 'ddmmyyyy';
        } else {
            $dateformat = 'mmddyyyy';
        }
        $GLOBALS['xoopsTpl']->assign('tablesorter_dateformat', $dateformat);

        $GLOBALS['xoTheme']->addStylesheet(\WGEVENTS_URL . '/assets/js/tablesorter/css/jquery.tablesorter.pager.min.css');
        $tablesorterTheme = $helper->getConfig('tablesorter_user');
        $GLOBALS['xoTheme']->addStylesheet(\WGEVENTS_URL . '/assets/js/tablesorter/css/theme.' . $tablesorterTheme . '.min.css');
        $GLOBALS['xoopsTpl']->assign('tablesorter_theme', $tablesorterTheme);
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/tablesorter/js/jquery.tablesorter.js');
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/tablesorter/js/jquery.tablesorter.widgets.js');
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/tablesorter/js/extras/jquery.tablesorter.pager.min.js');
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/tablesorter/js/widgets/widget-pager.min.js');
        break;

    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('registration.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // Check params
        if (0 === $regEvid) {
            \redirect_header('index.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $eventObj           = $eventHandler->get($regEvid);
        $evSubmitter        = $eventObj->getVar('submitter');
        $evStatus           = $eventObj->getVar('status');
        $registerForceVerif = (bool)$eventObj->getVar('register_forceverif');

        if ($regId > 0) {
            // Check permissions
            $registrationObj = $registrationHandler->get($regId);
            $permEdit = $permissionsHandler->getPermRegistrationsEdit($registrationObj->getVar('ip'), $registrationObj->getVar('submitter'), $evSubmitter, $evStatus);
            if (!$permEdit) {
                // check for valid verifKey
                $verifKeyEdit = Request::getString('verifkeyEdit');
                $verifKeyArray  = explode('||', base64_decode($verifKeyEdit, true));
                if (\is_object($registrationObj) && \WGEVENTS_URL === (string)$verifKeyArray[1] &&
                    (int)$registrationObj->getVar('evid') === (int)$verifKeyArray[2] &&
                    (string)$registrationObj->getVar('email') === (string)$verifKeyArray[3] &&
                    (string)$registrationObj->getVar('verifkey') === (string)$verifKeyArray[4]) {
                    $permEdit = true;
                }
            }
            if (!$permEdit) {
                \redirect_header('registration.php?op=list', 3, \_NOPERM);
            }
            $registrationObj = $registrationHandler->get($regId);
            $registrationObjOld = $registrationHandler->get($regId);
        } else {
            // Check permissions
            if (!$permissionsHandler->getPermRegistrationsSubmit()) {
                \redirect_header('registration.php?op=list', 3, \_NOPERM);
            }
            $registrationObj = $registrationHandler->create();
        }
        // create item in table registrations
        $answersValueArr = [];
        $answersIdArr = Request::getArray('ans_id');
        $answersTypeArr = Request::getArray('type');
        $registrationObj->setVar('evid', $regEvid);
        $registrationObj->setVar('salutation', Request::getInt('salutation'));
        $registrationObj->setVar('firstname', Request::getString('firstname'));
        $registrationObj->setVar('lastname', Request::getString('lastname'));
        $regEmail = Request::getString('email');
        $registrationObj->setVar('email', $regEmail);
        $registrationObj->setVar('email_send', Request::getInt('email_send'));
        $registrationObj->setVar('gdpr', Request::getInt('gdpr'));
        $registrationObj->setVar('ip', Request::getString('ip'));
        $regVerifkey = ('' === Request::getString('verifkey')) ? xoops_makepass() . xoops_makepass(): Request::getString('verifkey');
        $registrationObj->setVar('verifkey', $regVerifkey);
        $regStatus = Request::getInt('status');
        $registrationObj->setVar('status', $regStatus);
        $registrationObj->setVar('financial', Request::getInt('financial'));
        $regPaidamount = Utility::StringToFloat(Request::getString('paidamount'));
        $registrationObj->setVar('paidamount', $regPaidamount);
        $regListwait = 0;
        if ($regId > 0 || $permissionsHandler->getPermRegistrationsApprove($evSubmitter, $evStatus)) {
            //existing registration or user has perm to approve => take value of form
            $registrationObj->setVar('listwait', Request::getInt('listwait'));
        } else {
            //check number of registrations
            $eventRegisterMax = (int)$eventObj->getVar('register_max');
            if ($eventRegisterMax > 0) {
                $crRegCheck = new \CriteriaCompo();
                $crRegCheck->add(new \Criteria('evid', $regEvid));
                $numberRegCurr = $registrationHandler->getCount($crRegCheck);
                if ($eventRegisterMax <= $numberRegCurr) {
                    $regListwait = 1;
                }
            }
            $registrationObj->setVar('listwait', $regListwait);
        }
        if (Request::hasVar('datecreated_int')) {
            $registrationObj->setVar('datecreated', Request::getInt('datecreated_int'));
        } else {
            $registrationDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
            $registrationObj->setVar('datecreated', $registrationDatecreatedObj->getTimestamp());
        }
        $regSubmitter = Request::getInt('submitter');
        $registrationObj->setVar('submitter', $regSubmitter);
        // Insert Data
        if ($registrationHandler->insert($registrationObj)) {
            $newRegId = $regId > 0 ? $regId : (int)$registrationObj->getNewInsertedId();
            if ($regId > 0) {
                // create copy before deleting
                // get all questions for this event
                $questionsArr = $questionHandler->getQuestionsByEvent($regEvid);
                // get old answers for this questions
                $answersOld = $answerHandler->getAnswersDetailsByRegistration($newRegId, $questionsArr);
                // delete all existing answers
                $answerHandler->cleanupAnswers($regEvid, $regId);
            }
            // get all questions
            if (\count($answersIdArr) > 0) {
                foreach (\array_keys($answersIdArr) as $queId) {
                    $answer = '';
                    if (Request::hasVar('ans_id_' . $queId) && '' !== Request::getString('ans_id_' . $queId)) {
                        switch ($answersTypeArr[$queId]) {
                            case Constants::FIELD_CHECKBOX:
                            case Constants::FIELD_COMBOBOX:
                                $answer = serialize(Request::getArray('ans_id_' . $queId));
                                break;
                            case Constants::FIELD_SELECTBOX: //selectbox expect/gives single value, but stored as array
                                $answer = serialize(Request::getString('ans_id_' . $queId));
                                break;
                            default:
                                $answer = Request::getString('ans_id_' . $queId);
                                break;
                        }
                        $answersValueArr[$queId] = $answer;
                    }
                }
            }

            // create items in table answers
            foreach ($answersValueArr as $key => $answer) {
                if ('' !== (string)$answer) {
                    $answerObj = $answerHandler->create();
                    $answerObj->setVar('regid', $newRegId);
                    $answerObj->setVar('queid', $key);
                    $answerObj->setVar('evid', $regEvid);
                    $answerObj->setVar('text', $answer);
                    $answerObj->setVar('datecreated', \time());
                    $answerObj->setVar('submitter', $regSubmitter);
                    // Insert Data
                    $answerHandler->insert($answerObj);
                }
            }
            // TODO: Handle notification
            // send notifications/confirmation emails
            $infotextReg     = ''; // info text for registered person
            $infotextOrg     = ''; // infotext for organizer
            $previousMail    = '';
            $newRegistration = false;
            // create code for verification and showing single registration
            $codeArr = [
                $newRegId,
                \WGEVENTS_URL,
                $regEvid,
                $regEmail,
                $regVerifkey
            ];
            $code = base64_encode(implode('||', $codeArr));
            if ($regId > 0) {
                // find changes in table registrations
                $infotextReg = $registrationHandler->getRegistrationsCompare($registrationObjOld, $registrationObj);
                if ('' !== $infotextReg) {
                    // create history
                    if ($registrationObjOld->getVar('email') != $registrationObj->getVar('email')) {
                        $previousMail = $registrationObjOld->getVar('email');
                    }
                    $registrationhistHandler->createHistory($registrationObjOld, 'update');
                }
                // find changes in table answers
                if (\is_array($answersOld)) {
                    // get new answers for this questions
                    $answersNew = $answerHandler->getAnswersDetailsByRegistration($newRegId, $questionsArr);
                    $result = $answerHandler->getAnswersCompare($answersOld, $answersNew);
                    if ('' !== $result) {
                        // create history
                        $answerhistHandler->createHistory($regEvid, $regId, 'update');
                    }
                    $infotextReg .= $result;
                }
                $infotextOrg = $infotextReg;
                $singleRegLink = \WGEVENTS_URL . '/registration.php?op=show&verifkey=' . $code;
                $infotextReg .= PHP_EOL . \sprintf(\_MA_WGEVENTS_MAIL_REG_SINGLE, $singleRegLink) . PHP_EOL;
                // other params
                $typeNotify  = Constants::MAIL_REG_NOTIFY_MODIFY;
                $typeConfirm = Constants::MAIL_REG_CONFIRM_MODIFY;
            } else {
                $newRegistration = true;
                if (1 === $regListwait) {
                    // registration was put on a waiting list
                    $infotextReg .= \_MA_WGEVENTS_MAIL_REG_IN_LISTWAIT . PHP_EOL;
                }
                if (Constants::STATUS_SUBMITTED == $regStatus) {
                    // user has no permission for autoverify
                    $verifLink     = \WGEVENTS_URL . '/verification.php?verifkey=' . $code;
                    $infotextReg   .= \sprintf(\_MA_WGEVENTS_MAIL_REG_IN_VERIF, $verifLink) . PHP_EOL;
                }
                if (1 === $regListwait || Constants::STATUS_SUBMITTED == $regStatus) {
                    // registration was put on a waiting list
                    $infotextReg .= \_MA_WGEVENTS_MAIL_REG_IN_FINAL . PHP_EOL;
                }
                $singleRegLink = \WGEVENTS_URL . '/registration.php?op=show&verifkey=' . $code;
                $infotextReg   .= PHP_EOL . \sprintf(\_MA_WGEVENTS_MAIL_REG_SINGLE, $singleRegLink) . PHP_EOL;
                $typeNotify  = Constants::MAIL_REG_NOTIFY_IN;
                $typeConfirm = Constants::MAIL_REG_CONFIRM_IN;
            }
            $showinfo = 0;
            if ($newRegistration || '' !== $infotextReg) {
                $mailsHandler = new MailHandler();
                $mailParams = $mailsHandler->getMailParam($eventObj, $newRegId);
                unset($mailsHandler);
                $registerNotify = (string)$eventObj->getVar('register_notify', 'e');
                if ('' !== $registerNotify) {
                    $mailParams['infotext'] = $infotextOrg;
                    // send notifications to emails of register_notify
                    $notifyEmails = $eventHandler->getRecipientsNotify($registerNotify);
                    if (\count($notifyEmails) > 0) {
                        foreach ($notifyEmails as $recipient) {
                            $taskHandler->createTask($typeNotify, $recipient, json_encode($mailParams));
                        }
                    }
                }
                if (('' !== $regEmail && Request::getInt('email_send') > 0) || ('' !== $previousMail)) {
                    $mailParams['infotext'] = $infotextReg;
                    $showinfo = 1;
                    // send confirmation, if radio is checked
                    // or inform old email in any case if email changed
                    $recipients = [];
                    $recipients[] = $regEmail;
                    if ('' !== $previousMail) {
                        // add old email address if it changed in order to inform old mail address
                        $recipients[] = $previousMail;
                    }
                    foreach ($recipients as $recipient) {
                        $taskHandler->createTask($typeConfirm, $recipient, json_encode($mailParams));
                    }
                }
            }
            // execute mail sending by task handler
            $taskHandler->processTasks();
            // redirect after insert
            \redirect_header('registration.php?op=' . $redir . '&amp;redir=' . $redir . '&amp;evid=' . $regEvid. '&amp;showinfo=' . $showinfo, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $registrationObj->getHtmlErrors());
        $form = $registrationObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_REGISTRATION_EDIT];
        // Check params
        if (0 === $regId) {
            \redirect_header('registration.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $verifKey = Request::getString('verifkey');
        // Check permissions
        $registrationObj = $registrationHandler->get($regId);
        $eventObj = $eventHandler->get($registrationObj->getVar('evid'));
        $permEdit = $permissionsHandler->getPermRegistrationsEdit($registrationObj->getVar('ip'), $registrationObj->getVar('submitter'), $eventObj->getVar('submitter'), $eventObj->getVar('status'));
        if (!$permEdit) {
            // check for valid verifKey
            $verifKeyArray  = explode('||', base64_decode($verifKey, true));
            if ($regId > 0 && \is_object($registrationObj) && \WGEVENTS_URL === (string)$verifKeyArray[1] &&
                (int)$registrationObj->getVar('evid') === (int)$verifKeyArray[2] &&
                (string)$registrationObj->getVar('email') === (string)$verifKeyArray[3] &&
                (string)$registrationObj->getVar('verifkey') === (string)$verifKeyArray[4]) {
                $permEdit = true;
            }
        }
        if (!$permEdit) {
            \redirect_header('registration.php?op=list', 3, \_NOPERM);
        }
        if ('' !== $verifKey) {
            $redir = 'listmy';
        }
        // Get Form
        $registrationObj->setRedir($redir);
        $registrationObj->setVerifkeyEdit($verifKey);
        $registrationObj->setStart = $start;
        $registrationObj->setLimit = $limit;
        $form = $registrationObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'clone':
        echo 'noch nicht programmiert';die;
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_REGISTRATION_CLONE];
        // Check permissions
        if (!$permissionsHandler->getPermGlobalSubmit()) {
            \redirect_header('registration.php?op=list', 3, \_NOPERM);
        }
        // Request source
        $regIdSource = Request::getInt('id_source');
        // Check params
        if (0 === $regIdSource) {
            \redirect_header('registration.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $registrationObjSource = $registrationHandler->get($regIdSource);
        $registrationObj = $registrationObjSource->xoopsClone();
        $form = $registrationObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_REGISTRATION_DELETE];
        // Check params
        if (0 === $regId) {
            \redirect_header('index.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Check permissions
        $registrationObj = $registrationHandler->get($regId);
        if (!\is_object($registrationObj)) {
            \redirect_header('index.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $eventObj = $eventHandler->get($registrationObj->getVar('evid'));

        $mailsHandler = new MailHandler();
        $mailParams = $mailsHandler->getMailParam($eventObj, $regId);
        unset($mailsHandler);

        $mailParams['email'] = $registrationObj->getVar('email');
        if (isset($_REQUEST['ok']) && 1 === (int)$_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('registration.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // create history
            $registrationhistHandler->createHistory($registrationObj, 'delete');
            if ($registrationHandler->delete($registrationObj)) {
                // create history
                $answerhistHandler->createHistory($mailParams['evId'], $regId, 'delete');
                //delete existing answers
                $answerHandler->cleanupAnswers($mailParams['evId'], $regId);
                // TODO:  Event delete notification
                // send notifications/confirmation emails
                $registerNotify = (string)$eventObj->getVar('register_notify', 'e');
                if ('' !== $registerNotify) {
                    // send notifications to emails of register_notify
                    $notifyEmails = $eventHandler->getRecipientsNotify($registerNotify);
                    if (\count($notifyEmails) > 0) {
                        foreach ($notifyEmails as $recipient) {
                            $taskHandler->createTask(Constants::MAIL_REG_NOTIFY_OUT, $recipient, json_encode($mailParams));
                        }
                    }
                }
                // send email in any case if email is available
                if ('' !== (string)$mailParams['regEmail']) {
                    // send confirmation
                    $taskHandler->createTask(Constants::MAIL_REG_CONFIRM_OUT, $mailParams['regEmail'], json_encode($mailParams));
                }
                // execute mail sending by task handler
                $taskHandler->processTasks();
                \redirect_header('registration.php?op=' . $redir . '&amp;redir=' . $redir . '&amp;id=' . $regId . '&amp;evid=' . $regEvid, 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $registrationObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $regId, 'evid' => $regEvid, 'op' => 'delete', 'redir' => $redir],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMDELETE_REGISTRATION, $mailParams['regFirstname'] . ' ' . $mailParams['regLastname']),
                \_MA_WGEVENTS_CONFIRMDELETE_TITLE,
                \_MA_WGEVENTS_CONFIRMDELETE_LABEL
            );
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'change_financial':
        /* function is handled by registration_ajax.php */
        echo 'registration.php: function change_financial is not used anymore';
        die;
    case 'listwait_takeover':
        /* function is handled by registration_ajax.php */
        echo 'registration.php: function listwait_takeover is not used anymore';
        die;
    case 'approve_status':
        /* function is handled by registration_ajax.php */
        echo 'registration.php: function approve_status is not used anymore';
        die;
    case 'contactall':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_CONTACT_ALL];
        // Check params
        if (0 === $regEvid) {
            \redirect_header('registration.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $eventObj = $eventHandler->get($regEvid);
        $form = $eventObj->getFormContactAll($eventObj->getVar('register_sendermail'), $eventObj->getVar('name'));
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'exec_contactall':
    case 'exec_contactall_test':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('registration.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // Check params
        if (0 === $regEvid) {
            \redirect_header('index.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }

        $eventObj = $eventHandler->get($regEvid);
        // Check permissions
        if (!$permissionsHandler->getPermEventsEdit($eventObj->getVar('submitter'), $eventObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $crRegistration = new \CriteriaCompo();
        $crRegistration->add(new \Criteria('evid', $regEvid));
        $numberRegCurr = $registrationHandler->getCount($crRegistration);
        $mailToArr = [];
        if ($numberRegCurr > 0 && 'exec_contactall' === $op) {
            $registrationsAll = $registrationHandler->getAll($crRegistration);
            foreach (\array_keys($registrationsAll) as $i) {
                $mailToArr[$registrationsAll[$i]->getVar('email')] = $registrationsAll[$i]->getVar('email');
            }
        }
        $mailFrom    = Request::getString('mail_from');
        $mailSubject = Request::getString('mail_subject');
        $mailBody    = Request::getText('mail_body');
        $mailCopy    = Request::getInt('mail_copy');
        if (1 ===  $mailCopy) {
            $mailToArr[$mailFrom] = $mailFrom;
        }
        $mailParams = [];
        $mailParams['evId']                  = $regEvid;
        $mailParams['evName']                = $eventObj->getVar('name');
        $mailParams['evDatefrom']            = $eventObj->getVar('datefrom');
        $mailParams['evLocation']            = $eventObj->getVar('location');
        $mailParams['evSubmitter']           = $eventObj->getVar('submitter');
        $mailParams['evStatus']              = $eventObj->getVar('status');
        $mailParams['evRegister_sendermail'] = $eventObj->getVar('register_sendermail');
        $mailParams['evRegister_sendername'] = $eventObj->getVar('register_sendername');
        $mailParams['evRegister_signature']  = $eventObj->getVar('register_signature');
        $mailParams['mailFrom']              = $mailFrom;
        $mailParams['mailSubject']           = $mailSubject;
        $mailParams['mailBody']              = $mailBody;

        foreach ($mailToArr as $mail) {
            $taskHandler->createTask(Constants::MAIL_EVENT_NOTIFY_ALL, $mail, json_encode($mailParams));
        }

        $result = $taskHandler->processTasks();
        $counterDone = (int)$result['done'];
        $counterPending = (int)$result['pending'];

        if ($counterDone > 0 || $counterPending > 0) {
            if ('exec_contactall_test' === $op && $counterDone > 0) {
                $eventObj = $eventHandler->get($regEvid);
                $form = $eventObj->getFormContactAll($mailFrom, $mailSubject, $mailBody, $mailCopy);
                $GLOBALS['xoopsTpl']->assign('warning', \_MA_WGEVENTS_CONTACT_ALL_TEST_SUCCESS);
                $GLOBALS['xoopsTpl']->assign('form', $form->render());
                break;
            }
            // redirect after insert
            $message = '';
            if ($counterDone > 0) {
                $message .= sprintf( \_MA_WGEVENTS_CONTACT_ALL_SUCCESS, $counterDone) . '<br>';
            }
            if ($counterPending > 0) {
                $message .= sprintf( \_MA_WGEVENTS_CONTACT_ALL_PENDING, $counterDone) . '<br>';
            }
            \redirect_header('registration.php?op=listeventall&amp;evid=' . $regEvid, 3, $message);
        } else {
            \redirect_header('index.php?op=list', 3, 'exec_contactall:' . \_MA_WGEVENTS_INVALID_PARAM);
        }

        break;
}

// Keywords
wgeventsMetaKeywords($helper->getConfig('keywords') . ', ' . \implode(',', $keywords));
unset($keywords);

// Description
wgeventsMetaDescription(\_MA_WGEVENTS_REGISTRATIONS_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/registration.php');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);

require __DIR__ . '/footer.php';
