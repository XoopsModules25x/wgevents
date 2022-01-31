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
    MailHandler
};

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_events.tpl';
require_once \XOOPS_ROOT_PATH . '/header.php';

// Permission
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
$evId   = Request::getInt('id');
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
                $crEventsArchieve->add(new \Criteria('datefrom', \time(), '<'));
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
            $crEvents->add(new \Criteria('id', $evId));
            $GLOBALS['xoopsTpl']->assign('showList', false);
            $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_DETAILS];
        } else {
            if ('me' == $filter && $uidCurrent > 0) {
                $crEvents->add(new \Criteria('submitter', $uidCurrent));
            }
            $GLOBALS['xoopsTpl']->assign('showList', true);
            $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENTS_LIST];
        }
        $eventsCount = $eventsHandler->getCount($crEvents);
        $GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
        if ('past' == $op) {
            // list events before now
            $crEvents->add(new \Criteria('datefrom', \time(), '<'));
            $crEvents->setSort('datefrom');
            $crEvents->setOrder('DESC');
        } else {
            $crEvents->add(new \Criteria('datefrom', \time(), '>='));
            $crEvents->setSort('datefrom');
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
            // Get All Event
            foreach (\array_keys($eventsAll) as $i) {
                $events[$i] = $eventsAll[$i]->getValuesEvents();
                $permEdit = $permissionsHandler->getPermEventsEdit($eventsAll[$i]->getVar('submitter'), $eventsAll[$i]->getVar('status')) || $uidCurrent == $eventsAll[$i]->getVar('submitter');
                $events[$i]['permEdit'] = $permEdit;
                $crQuestions = new \CriteriaCompo();
                $crQuestions->add(new \Criteria('evid', $i));
                $events[$i]['nb_questions'] = $questionsHandler->getCount($crQuestions);
                $crRegistrations = new \CriteriaCompo();
                $crRegistrations->add(new \Criteria('evid', $i));
                $numberRegCurr = $registrationsHandler->getCount($crRegistrations);
                $events[$i]['nb_registrations'] = $numberRegCurr;
                $registerMax = (int)$events[$i]['register_max'];
                if ($registerMax > 0) {
                    $proportion = $numberRegCurr / $registerMax;
                    if ($proportion >= 1) {
                        $events[$i]['regcurrent'] = \_MA_WGEVENTS_REGISTRATIONS_FULL;
                        if ($events[$i]['register_listwait'] > 0) {
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
                $events[$i]['regenabled'] = $permEdit || (\time() >= $events[$i]['register_from'] && \time() <= $events[$i]['register_to']);
                $events[$i]['locked'] = (Constants::STATUS_LOCKED == $events[$i]['status']);
                $events[$i]['canceled'] = (Constants::STATUS_CANCELED == $events[$i]['status']);
                $evName = $eventsAll[$i]->getVar('name');
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
            if (!$permissionsHandler->getPermEventsEdit($eventsObj->getVar('submitter'), $eventsObj->getVar('status'))) {
                \redirect_header('index.php?op=list', 3, \_NOPERM);
            }
        } else {
            $eventsObj = $eventsHandler->create();
        }

        $continueAddtionals = Request::hasVar('continue_questions');

        $uploaderErrors = '';
        $eventsObj->setVar('catid', Request::getInt('catid'));
        $eventsObj->setVar('name', Request::getString('name'));
        // Set Var logo
        require_once \XOOPS_ROOT_PATH . '/class/uploader.php';
        $filename       = $_FILES['logo']['name'];
        $imgMimetype    = $_FILES['logo']['type'];
        $imgNameDef     = Request::getString('name');
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
                $eventsObj->setVar('logo', $savedFilename);
            } else {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
        } else {
            if ($filename > '') {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
            $eventsObj->setVar('logo', Request::getString('logo'));
        }
        $eventsObj->setVar('desc', Request::getText('desc'));
        $eventDatefromArr = Request::getArray('datefrom');
        $eventDatefromObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $eventDatefromArr['date']);
        $eventDatefromObj->setTime(0, 0);
        $eventDatefrom = $eventDatefromObj->getTimestamp() + (int)$eventDatefromArr['time'];
        $eventsObj->setVar('datefrom', $eventDatefrom);
        $eventDatetoArr = Request::getArray('dateto');
        $eventDatetoObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $eventDatetoArr['date']);
        $eventDatetoObj->setTime(0, 0);
        $eventDateto = $eventDatetoObj->getTimestamp() + (int)$eventDatetoArr['time'];
        $eventsObj->setVar('dateto', $eventDateto);
        $eventsObj->setVar('contact', Request::getString('contact'));
        $eventsObj->setVar('email', Request::getString('email'));
        $eventsObj->setVar('location', Request::getString('location'));
        $eventsObj->setVar('locgmlat', Request::getFloat('locgmlat'));
        $eventsObj->setVar('locgmlon', Request::getFloat('locgmlon'));
        $eventsObj->setVar('locgmzoom', Request::getInt('locgmzoom'));
        $evFee = Utility::StringToFloat(Request::getString('fee'));
        $eventsObj->setVar('fee', $evFee);
        $evRegisterUse = Request::getInt('register_use');
        $eventsObj->setVar('register_use', $evRegisterUse);
        if ($evRegisterUse) {
            $evRegisterfromArr = Request::getArray('register_from');
            $evRegisterfromObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $evRegisterfromArr['date']);
            $evRegisterfromObj->setTime(0, 0);
            $evRegisterfrom = $evRegisterfromObj->getTimestamp() + (int)$evRegisterfromArr['time'];
            $eventsObj->setVar('register_from', $evRegisterfrom);
            $evRegistertoArr = Request::getArray('register_to');
            $evRegistertoObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $evRegistertoArr['date']);
            $evRegistertoObj->setTime(0, 0);
            $evRegisterto = $evRegistertoObj->getTimestamp() + (int)$evRegistertoArr['time'];
            $eventsObj->setVar('register_to', $evRegisterto);
            $eventsObj->setVar('register_max', Request::getInt('register_max'));
            $eventsObj->setVar('register_listwait', Request::getInt('register_listwait'));
            $eventsObj->setVar('register_autoaccept', Request::getInt('register_autoaccept'));
            $eventsObj->setVar('register_notify', Request::getString('register_notify'));
            $evRegisterSendermail = Request::getString('register_sendermail');
            if ('' == $evRegisterSendermail) {
                // Get Form Error
                $GLOBALS['xoopsTpl']->assign('error', \_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL_ERR);
                $form = $eventsObj->getForm();
                $GLOBALS['xoopsTpl']->assign('form', $form->render());
                break;
            }
            $eventsObj->setVar('register_sendermail', $evRegisterSendermail);
            $eventsObj->setVar('register_sendername', Request::getString('register_sendername'));
            $eventsObj->setVar('register_signature', Request::getString('register_signature'));
        } else {
            //reset previous values
            $eventsObj->setVar('register_to', 0);
            $eventsObj->setVar('register_max', 0);
            $eventsObj->setVar('register_listwait', 0);
            $eventsObj->setVar('register_autoaccept', 0);
            $eventsObj->setVar('register_notify', '');
            $eventsObj->setVar('register_sendermail', '');
            $eventsObj->setVar('register_sendername', '');
            $eventsObj->setVar('register_signature', '');
        }
        $eventsObj->setVar('status', Request::getInt('status'));
        $eventsObj->setVar('galid', Request::getInt('galid'));
        if (Request::hasVar('datecreated_int')) {
            $eventsObj->setVar('datecreated', Request::getInt('datecreated_int'));
        } else {
            $eventDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
            $eventsObj->setVar('datecreated', $eventDatecreatedObj->getTimestamp());
        }
        $eventsObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($eventsHandler->insert($eventsObj)) {
            $newEvId = $evId > 0 ? $evId : $eventsObj->getNewInsertedId();
            // Handle notification
            /*
            $evName = $eventsObj->getVar('name');
            $evStatus = $eventsObj->getVar('status');
            $tags = [];
            $tags['ITEM_NAME'] = $evName;
            $tags['ITEM_URL']  = \XOOPS_URL . '/modules/wgevents/events.php?op=show&id=' . $evId;
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
                    $crRegistrations->add(new \Criteria('evid', $evId));
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
                            $regEmail = (string)$registrationsAll[$regId]->getVar('email');
                            if ('' != $regEmail) {
                                // send confirmation
                                $mailsHandler = new MailHandler();
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
                \redirect_header('events.php?op=edit&id=' . $newEvId, 5, $uploaderErrors);
            } else {
                if ($evRegisterUse) {
                    // check whether there are already question infos
                    $crQuestions = new \CriteriaCompo();
                    $crQuestions->add(new \Criteria('evid', $newEvId));
                    if ($evId > 0) {
                        \redirect_header('events.php?op=show&amp;id=' . $evId . '&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
                    } else {
                        if ($questionsHandler->getCount($crQuestions) > 0) {
                            // set of questions already existing
                            \redirect_header('questions.php?op=list&amp;evid=' . $newEvId, 2, \_MA_WGEVENTS_FORM_OK);
                        } else {
                            // redirect to questions.php in order to add default set of questions
                            \redirect_header('questions.php?op=newset&amp;evid=' . $newEvId, 0, \_MA_WGEVENTS_FORM_OK);
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
        $form = $eventsObj->getForm();
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
        $eventsObj->setVar('datefrom', $eventDate);
        $eventsObj->setVar('dateto', $eventDate);
        $form = $eventsObj->getForm();
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
        if (!$permissionsHandler->getPermEventsEdit($eventsObj->getVar('submitter'), $eventsObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');

        // Get Form
        $eventsObj = $eventsHandler->get($evId);
        $eventsObj->start = $start;
        $eventsObj->limit = $limit;
        $form = $eventsObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        // Request source
        $evIdSource = Request::getInt('id_source');





        \redirect_header('events.php?op=show&id=' . $evIdSource, 3, 'Funktion noch nicht fertig!');






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
        $form = $eventsObj->getForm();
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
        $crRegistrations->add(new \Criteria('evid', $evId));
        $registrationsCount = $registrationsHandler->getCount($crRegistrations);
        if ($registrationsCount > 0) {
            \redirect_header('events.php?op=list', 3, \_MA_WGEVENTS_EVENT_DELETE_ERR);
        }
        $eventsObj = $eventsHandler->get($evId);
        $evName = $eventsObj->getVar('name');
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
                ['ok' => 1, 'id' => $evId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMDELETE_EVENT, $eventsObj->getVar('name')), \_MA_WGEVENTS_CONFIRMDELETE_TITLE, \_MA_WGEVENTS_CONFIRMDELETE_LABEL);
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
        $evName = $eventsObj->getVar('name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('events.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $eventsObj->setVar('status', Constants::STATUS_CANCELED);
            $eventsObj->setVar('datecreated', \time());
            $eventsObj->setVar('submitter', $uidCurrent);
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
                ['ok' => 1, 'id' => $evId, 'start' => $start, 'limit' => $limit, 'op' => 'cancel'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMCANCEL_EVENT, $eventsObj->getVar('name')), \_MA_WGEVENTS_CONFIRMCANCEL_TITLE, \_MA_WGEVENTS_CONFIRMCANCEL_LABEL);
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
