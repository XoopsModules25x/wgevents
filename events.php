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
    Utility,
    Common,
    MailsHandler
};

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_events.tpl';
require_once \XOOPS_ROOT_PATH . '/header.php';

// Permissions
if (!$permissionsHandler->getPermEventsView()) {
    \redirect_header('index.php', 0);
}
$permSubmit = $permissionsHandler->getPermEventsSubmit();
$GLOBALS['xoopsTpl']->assign('permSubmit', $permSubmit);
$permApprove = $permissionsHandler->getPermEventsApprove();
$GLOBALS['xoopsTpl']->assign('permApprove', $permApprove);
$permRegister = $permissionsHandler->getPermRegistrationsSubmit();
$GLOBALS['xoopsTpl']->assign('permRegister', $permRegister);

$op     = Request::getCmd('op', 'list');
$evId   = Request::getInt('ev_id');
$filter = Request::getString('filter');
$start  = Request::getInt('start');
$limit  = Request::getInt('limit', $helper->getConfig('userpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);
// Paths
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', \XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
// Keywords
$keywords = [];
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_INDEX, 'link' => 'index.php'];

$GLOBALS['xoopsTpl']->assign('showItem', $evId > 0);

$uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;

switch ($op) {
    case 'show':
    case 'list':
    case 'past':
    default:
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL . '/');
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_24', \WGEVENTS_ICONS_URL_24 . '/');
        $GLOBALS['xoopsTpl']->assign('filter', $filter);

        switch($op) {
            case 'show':
            default:
                break;
            case 'list':
                // get events from the past
                $crEventsArchieve = new \CriteriaCompo();
                $crEventsArchieve->add(new \Criteria('ev_datefrom', \time(), '<'));
                $eventsCountArchieve = $eventsHandler->getCount($crEventsArchieve);
                unset($crEventsArchieve);
                $GLOBALS['xoopsTpl']->assign('showBtnPast', $eventsCountArchieve > 0);
                $GLOBALS['xoopsTpl']->assign('listDescr', \_MA_WGEVENTS_EVENTS_LISTCOMING);
                break;
            case 'past':
                $GLOBALS['xoopsTpl']->assign('showBtnComing', true);
                $GLOBALS['xoopsTpl']->assign('listDescr', \_MA_WGEVENTS_EVENTS_LISTPAST);
                break;
        }

        $crEvents = new \CriteriaCompo();
        if ($evId > 0) {
            $crEvents->add(new \Criteria('ev_id', $evId));
            $GLOBALS['xoopsTpl']->assign('showList', false);
            $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_DETAILS];
        } else {
            if ('me' == $filter && $uidCurrent > 0) {
                $crEvents->add(new \Criteria('ev_submitter', $uidCurrent));
            }
            $GLOBALS['xoopsTpl']->assign('showList', true);
            $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENTS_LIST];
        }
        $eventsCount = $eventsHandler->getCount($crEvents);
        $GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
        if ('past' == $op) {
            // list events before now
            $crEvents->add(new \Criteria('ev_datefrom', \time(), '<'));
            $crEvents->setSort('ev_datefrom');
            $crEvents->setOrder('DESC');
        } else {
            $crEvents->add(new \Criteria('ev_datefrom', \time(), '>='));
            $crEvents->setSort('ev_datefrom');
            $crEvents->setOrder('ASC');
        }

        if (0 === $evId) {
            $crEvents->setStart($start);
            $crEvents->setLimit($limit);
        }
        if ($eventsCount > 0) {
            $eventsAll = $eventsHandler->getAll($crEvents);
            $events = [];
            $evName = '';
            // Get All Events
            foreach (\array_keys($eventsAll) as $i) {
                $events[$i] = $eventsAll[$i]->getValuesEvents();
                $permEdit = $permissionsHandler->getPermEventsEdit($eventsAll[$i]->getVar('ev_submitter'), $eventsAll[$i]->getVar('ev_status')) || $uidCurrent == $eventsAll[$i]->getVar('ev_submitter');
                $events[$i]['permEdit'] = $permEdit;
                $crQuestions = new \CriteriaCompo();
                $crQuestions->add(new \Criteria('que_evid', $i));
                $events[$i]['nb_questions'] = $questionsHandler->getCount($crQuestions);
                $crRegistrations = new \CriteriaCompo();
                $crRegistrations->add(new \Criteria('reg_evid', $i));
                $numberRegCurr = $registrationsHandler->getCount($crRegistrations);
                $events[$i]['nb_registrations'] = $numberRegCurr;
                $registerMax = (int)$events[$i]['register_max'];
                if ($registerMax > 0) {
                    $proportion = $numberRegCurr / $registerMax;
                    if ($proportion >= 1) {
                        $events[$i]['regcurrent'] = \_MA_WGEVENTS_REGISTRATIONS_FULL;
                        if ($events[$i]['ev_register_listwait'] > 0) {
                            $events[$i]['regListwait'] = \_MA_WGEVENTS_REGISTRATIONS_FULL_LISTWAIT;
                        }
                    } else {
                        $events[$i]['regcurrent'] = \sprintf(\_MA_WGEVENTS_REGISTRATIONS_NBFROM, $numberRegCurr, $registerMax);
                    }
                    if ($proportion < 0.8) {
                        $events[$i]['regcurrentstate'] = 'success';
                    } elseif ($proportion < 1) {
                        $events[$i]['regcurrentstate'] = 'warning';
                    } else {
                        $events[$i]['regcurrentstate'] = 'danger';
                    }
                } else {
                    if ('show' == $op) {
                        $events[$i]['regcurrent'] = $numberRegCurr;
                    }
                }
                $events[$i]['regenabled'] = $permEdit || (\time() >= $events[$i]['ev_register_from'] && \time() <= $events[$i]['ev_register_to']);
                $events[$i]['locked'] = (Constants::STATUS_LOCKED == $events[$i]['ev_status']);
                $events[$i]['canceled'] = (Constants::STATUS_CANCELED == $events[$i]['ev_status']);
                $evName = $eventsAll[$i]->getVar('ev_name');
                $keywords[$i] = $evName;
            }
            $GLOBALS['xoopsTpl']->assign('events', $events);
            unset($events);
            // Display Navigation
            if ($eventsCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($eventsCount, $limit, $start, 'start', 'op=list&filter=' . $filter . '&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
            $GLOBALS['xoopsTpl']->assign('table_type', $helper->getConfig('table_type'));

            if ('show' == $op && '' != $evName) {
                $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', \strip_tags($evName . ' - ' . $GLOBALS['xoopsModule']->getVar('name')));
            }
        }
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('events.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // Check permissions
        if (!$permissionsHandler->getPermEventsSubmit()) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        if ($evId > 0) {
            $eventsObj = $eventsHandler->get($evId);
            $eventsObjOld = $eventsHandler->get($evId);
            // Check permissions
            if (!$permissionsHandler->getPermEventsEdit($eventsObj->getVar('ev_submitter'), $eventsObj->getVar('ev_status'))) {
                \redirect_header('index.php?op=list', 3, \_NOPERM);
            }
        } else {
            $eventsObj = $eventsHandler->create();
        }

        $continueAddtionals = Request::hasVar('continue_questions');

        $uploaderErrors = '';
        $eventsObj->setVar('ev_catid', Request::getInt('ev_catid'));
        $eventsObj->setVar('ev_name', Request::getString('ev_name'));
        // Set Var ev_logo
        require_once \XOOPS_ROOT_PATH . '/class/uploader.php';
        $filename       = $_FILES['ev_logo']['name'];
        $imgMimetype    = $_FILES['ev_logo']['type'];
        $imgNameDef     = Request::getString('ev_name');
        $uploadPath = \WGEVENTS_UPLOAD_EVENTLOGOS_PATH . '/' . $uidCurrent . '/';
        $uploader = new \XoopsMediaUploader($uploadPath,
                                                    $helper->getConfig('mimetypes_image'), 
                                                    $helper->getConfig('maxsize_image'), null, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $extension = \preg_replace('/^.+\.([^.]+)$/sU', '', $filename);
            $imgName = \str_replace(' ', '', $imgNameDef) . '.' . $extension;
            $uploader->setPrefix($imgName);
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if ($uploader->upload()) {
                $savedFilename = $uploader->getSavedFileName();
                $maxwidth  = (int)$helper->getConfig('maxwidth_image');
                $maxheight = (int)$helper->getConfig('maxheight_image');
                if ($maxwidth > 0 && $maxheight > 0) {
                    // Resize image
                    $imgHandler                = new Wgevents\Common\Resizer();
                    $imgHandler->sourceFile    = $uploadPath . $savedFilename;
                    $imgHandler->endFile       = $uploadPath . $savedFilename;
                    $imgHandler->imageMimetype = $imgMimetype;
                    $imgHandler->maxWidth      = $maxwidth;
                    $imgHandler->maxHeight     = $maxheight;
                    $result                    = $imgHandler->resizeImage();
                }
                $eventsObj->setVar('ev_logo', $savedFilename);
            } else {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
        } else {
            if ($filename > '') {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
            $eventsObj->setVar('ev_logo', Request::getString('ev_logo'));
        }
        $eventsObj->setVar('ev_desc', Request::getText('ev_desc'));
        $eventDatefromArr = Request::getArray('ev_datefrom');
        $eventDatefromObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $eventDatefromArr['date']);
        $eventDatefromObj->setTime(0, 0);
        $eventDatefrom = $eventDatefromObj->getTimestamp() + (int)$eventDatefromArr['time'];
        $eventsObj->setVar('ev_datefrom', $eventDatefrom);
        $eventDatetoArr = Request::getArray('ev_dateto');
        $eventDatetoObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $eventDatetoArr['date']);
        $eventDatetoObj->setTime(0, 0);
        $eventDateto = $eventDatetoObj->getTimestamp() + (int)$eventDatetoArr['time'];
        $eventsObj->setVar('ev_dateto', $eventDateto);
        $eventsObj->setVar('ev_contact', Request::getString('ev_contact'));
        $eventsObj->setVar('ev_email', Request::getString('ev_email'));
        $eventsObj->setVar('ev_location', Request::getString('ev_location'));
        $eventsObj->setVar('ev_locgmlat', Request::getFloat('ev_locgmlat'));
        $eventsObj->setVar('ev_locgmlon', Request::getFloat('ev_locgmlon'));
        $eventsObj->setVar('ev_locgmzoom', Request::getInt('ev_locgmzoom'));
        $evFee = Utility::StringToFloat(Request::getString('ev_fee'));
        $eventsObj->setVar('ev_fee', $evFee);
        $evRegisterUse = Request::getInt('ev_register_use');
        $eventsObj->setVar('ev_register_use', $evRegisterUse);
        if ($evRegisterUse) {
            $evRegisterfromArr = Request::getArray('ev_register_from');
            $evRegisterfromObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $evRegisterfromArr['date']);
            $evRegisterfromObj->setTime(0, 0);
            $evRegisterfrom = $evRegisterfromObj->getTimestamp() + (int)$evRegisterfromArr['time'];
            $eventsObj->setVar('ev_register_from', $evRegisterfrom);
            $evRegistertoArr = Request::getArray('ev_register_to');
            $evRegistertoObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $evRegistertoArr['date']);
            $evRegistertoObj->setTime(0, 0);
            $evRegisterto = $evRegistertoObj->getTimestamp() + (int)$evRegistertoArr['time'];
            $eventsObj->setVar('ev_register_to', $evRegisterto);
            $eventsObj->setVar('ev_register_max', Request::getInt('ev_register_max'));
            $eventsObj->setVar('ev_register_listwait', Request::getInt('ev_register_listwait'));
            $eventsObj->setVar('ev_register_autoaccept', Request::getInt('ev_register_autoaccept'));
            $eventsObj->setVar('ev_register_notify', Request::getString('ev_register_notify'));
            $evRegisterSendermail = Request::getString('ev_register_sendermail');
            if ('' == $evRegisterSendermail) {
                // Get Form Error
                $GLOBALS['xoopsTpl']->assign('error', \_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL_ERR);
                $form = $eventsObj->getFormEvents();
                $GLOBALS['xoopsTpl']->assign('form', $form->render());
                break;
            }
            $eventsObj->setVar('ev_register_sendermail', $evRegisterSendermail);
            $eventsObj->setVar('ev_register_sendername', Request::getString('ev_register_sendername'));
            $eventsObj->setVar('ev_register_signature', Request::getString('ev_register_signature'));
        } else {
            //reset previous values
            $eventsObj->setVar('ev_register_to', 0);
            $eventsObj->setVar('ev_register_max', 0);
            $eventsObj->setVar('ev_register_listwait', 0);
            $eventsObj->setVar('ev_register_autoaccept', 0);
            $eventsObj->setVar('ev_register_notify', '');
            $eventsObj->setVar('ev_register_sendermail', '');
            $eventsObj->setVar('ev_register_sendername', '');
            $eventsObj->setVar('ev_register_signature', '');
        }
        $eventsObj->setVar('ev_status', Request::getInt('ev_status'));
        $eventsObj->setVar('ev_galid', Request::getInt('ev_galid'));
        if (Request::hasVar('ev_datecreated_int')) {
            $eventsObj->setVar('ev_datecreated', Request::getInt('ev_datecreated_int'));
        } else {
            $eventDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('ev_datecreated'));
            $eventsObj->setVar('ev_datecreated', $eventDatecreatedObj->getTimestamp());
        }
        $eventsObj->setVar('ev_submitter', Request::getInt('ev_submitter'));
        // Insert Data
        if ($eventsHandler->insert($eventsObj)) {
            $newEvId = $evId > 0 ? $evId : $eventsObj->getNewInsertedIdEvents();
            // Handle notification
            /*
            $evName = $eventsObj->getVar('ev_name');
            $evStatus = $eventsObj->getVar('ev_status');
            $tags = [];
            $tags['ITEM_NAME'] = $evName;
            $tags['ITEM_URL']  = \XOOPS_URL . '/modules/wgevents/events.php?op=show&ev_id=' . $evId;
            $notificationHandler = \xoops_getHandler('notification');
            if (Constants::STATUS_SUBMITTED == $evStatus) {
                // Event approve notification
                $notificationHandler->triggerEvent('global', 0, 'global_approve', $tags);
                $notificationHandler->triggerEvent('events', $newEvId, 'event_approve', $tags);
            } else {
                if ($evId > 0) {
                    // Event modify notification
                    $notificationHandler->triggerEvent('global', 0, 'global_modify', $tags);
                    $notificationHandler->triggerEvent('events', $newEvId, 'event_modify', $tags);
                } else {
                    // Event new notification
                    $notificationHandler->triggerEvent('global', 0, 'global_new', $tags);
                }
            }
            */

            // check whether there are important changes of the event
            // if yes then send notifications to all participants
            if ($evId > 0) {
                // find changes in table events
                $infotext = $eventsHandler->getEventsCompare($eventsObjOld, $eventsObj);
                if ('' != $infotext) {
                    $typeConfirm = Constants::MAIL_EVENT_NOTIFY_MODIFY;
                    $crRegistrations = new \CriteriaCompo();
                    $crRegistrations->add(new \Criteria('reg_evid', $evId));
                    $registrationsCount = $registrationsHandler->getCount($crRegistrations);
                    if ($registrationsCount > 0) {
                        $registrationsAll = $registrationsHandler->getAll($crRegistrations);
                        foreach (\array_keys($registrationsAll) as $regId) {
                            // Event delete notification
                            /*
                            $tags = [];
                            $tags['ITEM_NAME'] = $regEvid;
                            $notificationHandler = \xoops_getHandler('notification');
                            $notificationHandler->triggerEvent('global', 0, 'global_delete', $tags);
                            $notificationHandler->triggerEvent('registrations', $regId, 'registration_delete', $tags);
                            */
                            // send notifications emails, only to participants
                            $regEmail = (string)$registrationsAll[$regId]->getVar('reg_email');
                            if ('' != $regEmail) {
                                // send confirmation
                                $mailsHandler = new MailsHandler();
                                $mailsHandler->setInfo($infotext);
                                $mailsHandler->setNotifyEmails($regEmail);
                                $mailsHandler->executeReg($regId, $typeConfirm);
                                unset($mailsHandler);
                            }
                        }
                    }
                }
            }
            // redirect after insert
            if ('' !== $uploaderErrors) {
                \redirect_header('events.php?op=edit&ev_id=' . $newEvId, 5, $uploaderErrors);
            } else {
                if ($evRegisterUse) {
                    // check whether there are already question infos
                    $crQuestions = new \CriteriaCompo();
                    $crQuestions->add(new \Criteria('que_evid', $newEvId));
                    if ($evId > 0) {
                        \redirect_header('events.php?op=show&amp;ev_id=' . $evId . '&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
                    } else {
                        if ($questionsHandler->getCount($crQuestions) > 0) {
                            // set of questions already existing
                            \redirect_header('questions.php?op=list&amp;que_evid=' . $newEvId, 2, \_MA_WGEVENTS_FORM_OK);
                        } else {
                            // redirect to questions.php in order to add default set of questions
                            \redirect_header('questions.php?op=newset&amp;que_evid=' . $newEvId, 0, \_MA_WGEVENTS_FORM_OK);
                        }
                    }
                } else {
                    if ($evId > 0) {
                        $registrationsHandler->cleanupRegistrations($evId);
                        $questionsHandler->cleanupQuestions($evId);
                        $answersHandler->cleanupAnswers($evId);
                    }
                    \redirect_header('events.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
                }
            }
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $eventsObj->getHtmlErrors());
        $form = $eventsObj->getFormEvents();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'new':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_ADD];
        // Check permissions
        if (!$permissionsHandler->getPermEventsSubmit()) {
            \redirect_header('events.php?op=list', 3, \_NOPERM);
        }
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Form Create
        $eventsObj = $eventsHandler->create();
        $eventDate = Request::getInt('eventDate', \time());
        $eventsObj->setVar('ev_datefrom', $eventDate);
        $eventsObj->setVar('ev_dateto', $eventDate);
        $form = $eventsObj->getFormEvents();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_EDIT];
        // Check params
        if (0 == $evId) {
            \redirect_header('events.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $eventsObj = $eventsHandler->get($evId);
        // Check permissions
        if (!$permissionsHandler->getPermEventsEdit($eventsObj->getVar('ev_submitter'), $eventsObj->getVar('ev_status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');

        // Get Form
        $eventsObj = $eventsHandler->get($evId);
        $eventsObj->start = $start;
        $eventsObj->limit = $limit;
        $form = $eventsObj->getFormEvents();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        // Request source
        $evIdSource = Request::getInt('ev_id_source');





        \redirect_header('events.php?op=show&ev_id=' . $evIdSource, 3, 'Funktion noch nicht fertig!');






        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_CLONE];
        // Check permissions
        if (!$permissionsHandler->getPermEventsSubmit()) {
            \redirect_header('events.php?op=list', 3, \_NOPERM);
        }

        // Check params
        if (0 == $evIdSource) {
            \redirect_header('events.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $eventsObjSource = $eventsHandler->get($evIdSource);
        $eventsObj = $eventsObjSource->xoopsClone();
        $form = $eventsObj->getFormEvents();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_DELETE];
        // Check permissions
        if (!$permissionsHandler->getPermEventsSubmit()) {
            \redirect_header('events.php?op=list', 3, \_NOPERM);
        }
        // Check params
        if (0 == $evId) {
            \redirect_header('events.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $crRegistrations = new \CriteriaCompo();
        $crRegistrations->add(new \Criteria('reg_evid', $evId));
        $registrationsCount = $registrationsHandler->getCount($crRegistrations);
        if ($registrationsCount > 0) {
            \redirect_header('events.php?op=list', 3, \_MA_WGEVENTS_EVENT_DELETE_ERR);
        }
        $eventsObj = $eventsHandler->get($evId);
        $evName = $eventsObj->getVar('ev_name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('events.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $registrationsHandler->cleanupRegistrations($evId);
            $questionsHandler->cleanupQuestions($evId);
            $answersHandler->cleanupAnswers($evId);
            if ($eventsHandler->delete($eventsObj)) {
                // Event delete notification
                /*
                $tags = [];
                $tags['ITEM_NAME'] = $evName;
                $notificationHandler = \xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'global_delete', $tags);
                $notificationHandler->triggerEvent('events', $evId, 'event_delete', $tags);
                */
                \redirect_header('events.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $eventsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'ev_id' => $evId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMDELETE_EVENT, $eventsObj->getVar('ev_name')), \_MA_WGEVENTS_CONFIRMDELETE_TITLE, \_MA_WGEVENTS_CONFIRMDELETE_LABEL);
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'cancel':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_CANCEL];
        // Check permissions
        if (!$permissionsHandler->getPermEventsSubmit()) {
            \redirect_header('events.php?op=list', 3, \_NOPERM);
        }
        // Check params
        if (0 == $evId) {
            \redirect_header('events.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $eventsObj = $eventsHandler->get($evId);
        $evName = $eventsObj->getVar('ev_name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('events.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $eventsObj->setVar('ev_status', Constants::STATUS_CANCELED);
            $eventsObj->setVar('ev_datecreated', \time());
            $eventsObj->setVar('ev_submitter', $uidCurrent);
            // delete all questions/registrations/answers
            $registrationsHandler->cleanupRegistrations($evId);
            $questionsHandler->cleanupQuestions($evId);
            $answersHandler->cleanupAnswers($evId);
            // Insert Data
            if ($eventsHandler->insert($eventsObj)) {
                // Event delete notification
                /*
                $tags = [];
                $tags['ITEM_NAME'] = $evName;
                $notificationHandler = \xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'global_delete', $tags);
                $notificationHandler->triggerEvent('events', $evId, 'event_delete', $tags);
                */
                \redirect_header('events.php', 3, \_MA_WGEVENTS_FORM_CANCEL_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $eventsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'ev_id' => $evId, 'start' => $start, 'limit' => $limit, 'op' => 'cancel'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMCANCEL_EVENT, $eventsObj->getVar('ev_name')), \_MA_WGEVENTS_CONFIRMCANCEL_TITLE, \_MA_WGEVENTS_CONFIRMCANCEL_LABEL);
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}

// Keywords
wgeventsMetaKeywords($helper->getConfig('keywords') . ', ' . \implode(',', $keywords));
unset($keywords);

// Description
wgeventsMetaDescription(\_MA_WGEVENTS_EVENTS_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/events.php');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);

require __DIR__ . '/footer.php';
