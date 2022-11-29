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
use XoopsModules\Wgevents\ {
    Constants,
    Utility,
    Common,
    MailHandler
};

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_event.tpl';
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
$catId  = Request::getInt('catid');
$filterCats = Request::getArray('filter_cats');
if ($catId > 0 && 'save' !== $op) {
    $filterCats[$catId] = $catId;
}
$urlCats = Request::getString('cats');
if (0 == \count($filterCats) && '' != $urlCats) {
    $filterCats = \explode(',', $urlCats);
}
$GLOBALS['xoopsTpl']->assign('urlCats', \implode(',', $filterCats));

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);
// Paths
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', \XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
// JS
$GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
// Keywords
$keywords = [];
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_INDEX, 'link' => 'index.php'];

//preferences
$gmapsEnableEvent = false;
$gmapsHeight      = false;
$useGMaps         = (bool)$helper->getConfig('use_gmaps');
if ($useGMaps) {
    $gmapsPositionList = (string)$helper->getConfig('gmaps_enableevent');
    $gmapsEnableEvent  = ('top' == $gmapsPositionList || 'bottom' == $gmapsPositionList);
    $gmapsHeight       = $helper->getConfig('gmaps_height');
}
$eventDisplayCats = (string)$helper->getConfig('event_displaycats');
$GLOBALS['xoopsTpl']->assign('event_displaycats', $eventDisplayCats);

//misc
$uidCurrent  = 0;
$userIsAdmin = false;
if (\is_object($GLOBALS['xoopsUser'])) {
    $uidCurrent  = $GLOBALS['xoopsUser']->uid();
    $userIsAdmin = $GLOBALS['xoopsUser']->isAdmin();
}

$showItem = ($evId > 0);
$GLOBALS['xoopsTpl']->assign('showItem', $showItem);

switch ($op) {
    case 'show':
    case 'list':
    case 'past':
    default:
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_catlogos_url', \WGEVENTS_UPLOAD_CATLOGOS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_24', \WGEVENTS_ICONS_URL_24 . '/');
        $GLOBALS['xoopsTpl']->assign('filter', $filter);

        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/expander/jquery.expander.min.js');
        $GLOBALS['xoopsTpl']->assign('user_maxchar', $helper->getConfig('user_maxchar'));
        $useGroups = (bool)$helper->getConfig('use_groups');
        $GLOBALS['xoopsTpl']->assign('use_groups', $useGroups);

        // get categories if they should be displayed
        if ('none' != $eventDisplayCats && !$showItem) {
            $categories = $categoryHandler->getCategoriesForFilter($eventDisplayCats, $filterCats, $op, $useGroups, $filter);
            $GLOBALS['xoopsTpl']->assign('categories', $categories);
        }

        // determine whether there are
        // - past events if coming are shown
        // - coming events if past are shown
        switch($op) {
            case 'show':
            default:
                $listDescr = '';
                break;
            case 'list':
                // get events from the past
                $crEventArchieve = new \CriteriaCompo();
                $crEventArchieve->add(new \Criteria('datefrom', \time(), '<'));
                $eventsCountArchieve = $eventHandler->getCount($crEventArchieve);
                unset($crEventArchieve);
                $GLOBALS['xoopsTpl']->assign('showBtnPast', $eventsCountArchieve > 0);
                $listDescr = \_MA_WGEVENTS_EVENTS_LISTCOMING;
                if ('form' == $eventDisplayCats) {
                    $formCatsCb = $categoryHandler->getFormCatsCb($filterCats, $op, $filter);
                    $GLOBALS['xoopsTpl']->assign('formCatsCb', $formCatsCb->render());
                }
                break;
            case 'past':
                $GLOBALS['xoopsTpl']->assign('showBtnComing', true);
                $listDescr = \_MA_WGEVENTS_EVENTS_LISTPAST;
                if ('form' == $eventDisplayCats) {
                    $formCatsCb = $categoryHandler->getFormCatsCb($filterCats, $op, $filter);
                    $GLOBALS['xoopsTpl']->assign('formCatsCb', $formCatsCb->render());
                }
                break;
        }
        $GLOBALS['xoopsTpl']->assign('listDescr', $listDescr);

        //misc for displaying events
        $crEvent = new \CriteriaCompo();
        if ($showItem) {
            $GLOBALS['xoopsTpl']->assign('showList', false);
            $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_DETAILS];
        } else {
            $GLOBALS['xoopsTpl']->assign('showList', true);
            $xoBreadcrumbs[] = ['title' => $listDescr];
        }

        // get events
        $eventsArr = $eventHandler->getEvents($start, $limit, \time(), 0, '', '', $op, $evId, $filter, $filterCats);

        $eventsCount = $eventsArr['count'];
        $GLOBALS['xoopsTpl']->assign('eventsCount', $eventsCount);
        if ($eventsCount > 0) {
            $eventsAll = $eventsArr['eventsAll'];
            $events    = [];
            $eventsMap = [];
            $evName    = '';
            // Get All Event
            foreach (\array_keys($eventsAll) as $i) {
                $events[$i] = $eventsAll[$i]->getValuesEvents();

                $permEdit = $permissionsHandler->getPermEventsEdit($eventsAll[$i]->getVar('submitter'), $eventsAll[$i]->getVar('status')) || $uidCurrent == $eventsAll[$i]->getVar('submitter');
                $events[$i]['permEdit'] = $permEdit;
                $crQuestion = new \CriteriaCompo();
                $crQuestion->add(new \Criteria('evid', $i));
                $events[$i]['nb_questions'] = $questionHandler->getCount($crQuestion);
                $crRegistration = new \CriteriaCompo();
                $crRegistration->add(new \Criteria('evid', $i));
                $numberRegCurr = $registrationHandler->getCount($crRegistration);
                $events[$i]['nb_registrations'] = $numberRegCurr;
                $registerMax = (int)$events[$i]['register_max'];
                $events[$i]['regmax'] = $registerMax;
                if ($registerMax > 0) {
                    $events[$i]['regmax'] = $registerMax;
                    $proportion = $numberRegCurr / $registerMax;
                    if ($proportion >= 1) {
                        $events[$i]['regcurrent'] = \_MA_WGEVENTS_REGISTRATIONS_FULL;
                    } else if (0 == $numberRegCurr) {
                        $events[$i]['regcurrent'] = \_MA_WGEVENTS_REGISTRATIONS_NBCURR_0;
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
                } else if ('show' == $op) {
                    $events[$i]['regcurrent'] = $numberRegCurr;
                }
                $events[$i]['regenabled'] = $permEdit || (\time() >= $events[$i]['register_from'] && \time() <= $events[$i]['register_to']);
                $events[$i]['locked'] = (Constants::STATUS_LOCKED == $events[$i]['status']);
                $events[$i]['canceled'] = (Constants::STATUS_CANCELED == $events[$i]['status']);
                $evName = $eventsAll[$i]->getVar('name');
                if ($useGMaps && $gmapsEnableEvent && (float)$eventsAll[$i]->getVar('locgmlat') > 0) {
                    $eventsMap[$i] = [
                        'name' => $evName,
                        'location' => $events[$i]['location_text_user'],
                        'from' => $events[$i]['datefrom_text'],
                        'url' => 'event.php?op=show&id=' . $i,
                        'lat'  => (float)$eventsAll[$i]->getVar('locgmlat'),
                        'lon'  => (float)$eventsAll[$i]->getVar('locgmlon')
                    ];
                }
                $keywords[$i] = $evName;
            }
            $GLOBALS['xoopsTpl']->assign('events', $events);
            if ('show' == $op && $useGMaps) {
                $GLOBALS['xoopsTpl']->assign('gmapsShow', true);
            }
            if ($useGMaps && count($eventsMap) > 0) {
                $GLOBALS['xoopsTpl']->assign('gmapsShowList', true);
                $GLOBALS['xoopsTpl']->assign('gmapsEnableEvent', $gmapsEnableEvent);
                $GLOBALS['xoopsTpl']->assign('gmapsHeight', $gmapsHeight);
                $GLOBALS['xoopsTpl']->assign('gmapsPositionList', $gmapsPositionList);
                $GLOBALS['xoopsTpl']->assign('api_key', $helper->getConfig('gmaps_api'));
                $GLOBALS['xoopsTpl']->assign('eventsMap', $eventsMap);
            }
            unset($events, $eventMaps);
            // Display Navigation
            if ($eventsCount > $limit) {
                $urlCats = \implode(',', $filterCats);
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($eventsCount, $limit, $start, 'start', 'op=list&amp;filter=' . $filter . '&amp;limit=' . $limit . '&amp;cats=' . $urlCats);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
                /*
                $params = [];
                $formPageNavCounter = $eventHandler->getFormPageNavCounter($params);
                $GLOBALS['xoopsTpl']->assign('formPageNavCounter', $formPageNavCounter->render());
                */
            }
            $GLOBALS['xoopsTpl']->assign('start', $start);
            $GLOBALS['xoopsTpl']->assign('limit', $limit);

            if ('show' == $op && '' != $evName) {
                $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', \strip_tags($evName . ' - ' . $GLOBALS['xoopsModule']->getVar('name')));
            }
        } else {
            if (\count($filterCats) > 0) {
                $GLOBALS['xoopsTpl']->assign('noEventsReason', \_MA_WGEVENTS_INDEX_THEREARENT_EVENTS_FILTER);
            } else {
                $GLOBALS['xoopsTpl']->assign('noEventsReason', \_MA_WGEVENTS_INDEX_THEREARENT_EVENTS);
            }
        }

        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('event.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // Check permissions
        if (!$permissionsHandler->getPermEventsSubmit()) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        if ($evId > 0) {
            $eventObj = $eventHandler->get($evId);
            $eventObjOld = $eventHandler->get($evId);
            // Check permissions
            if (!$permissionsHandler->getPermEventsEdit($eventObj->getVar('submitter'), $eventObj->getVar('status'))) {
                \redirect_header('index.php?op=list', 3, \_NOPERM);
            }
        } else {
            $eventObj = $eventHandler->create();
        }

        $uploaderErrors = '';
        $dateErrors = '';
        $catId = Request::getInt('catid');
        $evSubmitter = Request::getInt('submitter');

        $eventObj->setVar('catid', $catId);
        $subCats = \serialize(Request::getArray('subcats'));
        $eventObj->setVar('subcats', $subCats);
        $eventObj->setVar('name', Request::getString('name'));
        // Set Var logo
        require_once \XOOPS_ROOT_PATH . '/class/uploader.php';
        $filename    = $_FILES['logo']['name'];
        $imgMimetype = $_FILES['logo']['type'];
        $imgNameDef  = Request::getString('name');
        $uploadPath  = \WGEVENTS_UPLOAD_EVENTLOGOS_PATH . '/' . $uidCurrent . '/';
        $uploader    = new \XoopsMediaUploader($uploadPath,
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
                $filename = $savedFilename;
                $eventObj->setVar('logo', $savedFilename);
            } else {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
        } else {
            if ($filename > '') {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
            $filename = Request::getString('logo');
            if ('' != $filename) {
                $eventObj->setVar('logo', $filename);
            }
        }
        if ($evSubmitter !== $uidCurrent) {
            //if someone creates/save event for someone else then copy logo
            $submitterDirectory = \WGEVENTS_UPLOAD_EVENTLOGOS_PATH . '/' . $evSubmitter;
            if (!\file_exists($submitterDirectory)) {
                Utility::createFolder($submitterDirectory);
                \chmod($submitterDirectory, 0777);
                $source = \WGEVENTS_PATH . '/assets/images/blank.gif';
                $dest = $submitterDirectory . '/blank.gif';
                Utility::copyFile($source, $dest);
            }
            $dest = \WGEVENTS_UPLOAD_EVENTLOGOS_PATH . '/' . $evSubmitter . '/' . $filename;
            if (!\file_exists($dest)) {
                Utility::copyFile($uploadPath . $filename, $dest);
            }
        }
        $eventObj->setVar('desc', Request::getText('desc'));
        $eventDatefromArr = Request::getArray('datefrom');
        $eventDatefromObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $eventDatefromArr['date']);
        $eventDatefromObj->setTime(0, 0);
        $eventDatefrom = $eventDatefromObj->getTimestamp() + (int)$eventDatefromArr['time'];
        $eventObj->setVar('datefrom', $eventDatefrom);
        $eventDatetoArr = Request::getArray('dateto');
        $eventDatetoObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $eventDatetoArr['date']);
        $eventDatetoObj->setTime(0, 0);
        $eventDateto = $eventDatetoObj->getTimestamp() + (int)$eventDatetoArr['time'];
        if ($eventDateto < $eventDatefrom) {
            $eventDateto = $eventDatefrom;
            $dateErrors .= '<br>' . \_MA_WGEVENTS_EVENT_DATE_ERROR1;
        }
        $eventObj->setVar('dateto', $eventDateto);
        $eventObj->setVar('allday', Request::getInt('allday'));
        $eventObj->setVar('contact', Request::getString('contact'));
        $eventObj->setVar('email', Request::getString('email'));
        $eventObj->setVar('url', Request::getString('url'));
        $eventObj->setVar('location', Request::getString('location'));
        $eventObj->setVar('locgmlat', Request::getFloat('locgmlat'));
        $eventObj->setVar('locgmlon', Request::getFloat('locgmlon'));
        $eventObj->setVar('locgmzoom', Request::getInt('locgmzoom'));
        $evFeeAmountArr = Request::getArray('fee');
        $evFeeDescArr = Request::getArray('feedesc');
        $evFeeArr = [];
        foreach ($evFeeAmountArr as $key => $evFeeAmount) {
            $evFeeArr[] = [Utility::StringToFloat($evFeeAmount), $evFeeDescArr[$key]];
        }
        // remove last array item, as this is from hidden group
        \array_pop($evFeeArr);
        $evFee = \json_encode($evFeeArr);
        $eventObj->setVar('fee', $evFee);
        $eventObj->setVar('paymentinfo', Request::getText('paymentinfo'));
        $evRegisterUse = Request::getInt('register_use');
        $eventObj->setVar('register_use', $evRegisterUse);
        if ($evRegisterUse) {
            $evRegisterfromArr = Request::getArray('register_from');
            $evRegisterfromObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $evRegisterfromArr['date']);
            $evRegisterfromObj->setTime(0, 0);
            $evRegisterfrom = $evRegisterfromObj->getTimestamp() + (int)$evRegisterfromArr['time'];
            $eventObj->setVar('register_from', $evRegisterfrom);
            $evRegistertoArr = Request::getArray('register_to');
            $evRegistertoObj = \DateTime::createFromFormat(\_SHORTDATESTRING, $evRegistertoArr['date']);
            $evRegistertoObj->setTime(0, 0);
            $evRegisterto = $evRegistertoObj->getTimestamp() + (int)$evRegistertoArr['time'];
            $eventObj->setVar('register_to', $evRegisterto);
            if ($evRegisterto < $evRegisterfrom) {
                $evRegisterto = $evRegisterfrom;
                $dateErrors .= '<br>' . \_MA_WGEVENTS_EVENT_DATE_ERROR2;
            }
            $eventObj->setVar('register_max', Request::getInt('register_max'));
            $eventObj->setVar('register_listwait', Request::getInt('register_listwait'));
            $eventObj->setVar('register_autoaccept', Request::getInt('register_autoaccept'));
            $eventObj->setVar('register_notify', Request::getString('register_notify'));
            $eventObj->setVar('register_forceverif', Request::getInt('register_forceverif'));
            $evRegisterSendermail = Request::getString('register_sendermail');
            if ('' == $evRegisterSendermail) {
                // Get Form Error
                $GLOBALS['xoopsTpl']->assign('error', \_MA_WGEVENTS_EVENT_REGISTER_SENDERMAIL_ERR);
                $form = $eventObj->getForm();
                $GLOBALS['xoopsTpl']->assign('form', $form->render());
                break;
            }
            $eventObj->setVar('register_sendermail', $evRegisterSendermail);
            $eventObj->setVar('register_sendername', Request::getString('register_sendername'));
            $eventObj->setVar('register_signature', Request::getString('register_signature'));
        } else {
            //reset previous values
            $eventObj->setVar('register_to', 0);
            $eventObj->setVar('register_max', 0);
            $eventObj->setVar('register_listwait', 0);
            $eventObj->setVar('register_autoaccept', 0);
            $eventObj->setVar('register_notify', '');
            $eventObj->setVar('register_sendermail', '');
            $eventObj->setVar('register_sendername', '');
            $eventObj->setVar('register_signature', '');
            $eventObj->setVar('register_forceverif', 0);
        }
        $eventObj->setVar('status', Request::getInt('status'));
        $eventObj->setVar('galid', Request::getInt('galid'));
        $arrGroups = Request::getArray('groups');
        if (in_array('00000', $arrGroups)) {
            $eventObj->setVar('groups', '00000');
        } else {
            $eventObj->setVar('groups', implode("|", $arrGroups));
        }
        if (Request::hasVar('datecreated_int')) {
            $eventObj->setVar('datecreated', Request::getInt('datecreated_int'));
        } else {
            $eventDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
            $eventObj->setVar('datecreated', $eventDatecreatedObj->getTimestamp());
        }
        $eventObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($eventHandler->insert($eventObj)) {
            $newEvId = $evId > 0 ? $evId : $eventObj->getNewInsertedId();
            // create unique identifier if new event and identifier exists
            if (0 == $evId) {
                $categoryObj = $categoryHandler->get($catId);
                $identifier = (string)$categoryObj->getVar('identifier');
                if ('' !== $identifier){
                    if (\substr($identifier, -1) !== '_') {
                        $identifier .= '_';
                    }
                    $identifier .= $eventDatefromObj->format('Y') . '_';
                    $crEvent = new \CriteriaCompo();
                    $crEvent->add(new \Criteria('identifier', $identifier . '%', 'LIKE'));
                    $eventsCount = $eventHandler->getCount($crEvent) + 1;
                    $eventIdentifierObj = $eventHandler->get($newEvId);
                    $eventIdentifierObj->setVar('identifier', $identifier . ($eventsCount));
                    $eventHandler->insert($eventIdentifierObj);
                    unset($eventIdentifierObj);
                }
            } else {
                $eventIdentifierObj = $eventHandler->get($evId);
                $eventIdentifierObj->setVar('identifier', Request::getString('identifier'));
                $eventHandler->insert($eventIdentifierObj);
                unset($eventIdentifierObj);
            }

            // Handle notification
            /*
            $evName = $eventObj->getVar('name');
            $evStatus = $eventObj->getVar('status');
            $tags = [];
            $tags['ITEM_NAME'] = $evName;
            $tags['ITEM_URL']  = \XOOPS_URL . '/modules/wgevents/event.php?op=show&id=' . $evId;
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
                $infotext = $eventHandler->getEventsCompare($eventObjOld, $eventObj);
                if ('' != $infotext) {
                    $typeConfirm = Constants::MAIL_EVENT_NOTIFY_MODIFY;
                    $crRegistration = new \CriteriaCompo();
                    $crRegistration->add(new \Criteria('evid', $evId));
                    $registrationsCount = $registrationHandler->getCount($crRegistration);
                    if ($registrationsCount > 0) {
                        $registrationsAll = $registrationHandler->getAll($crRegistration);
                        foreach (\array_keys($registrationsAll) as $regId) {
                            // Event delete notification
                            /*
                            $tags = [];
                            $tags['ITEM_NAME'] = $regEvid;
                            $notificationHandler = \xoops_getHandler('notification');
                            $notificationHandler->triggerEvent('global', 0, 'global_delete', $tags);
                            $notificationHandler->triggerEvent('registrations', $regId, 'registration_delete', $tags);
                            */
                            $informModif = Request::getBool('informModif');
                            if ($informModif) {
                                // send notifications emails, only to participants
                                $regEmail = (string)$registrationsAll[$regId]->getVar('email');
                                if ('' != $regEmail) {
                                    // send confirmation
                                    $mailsHandler = new MailHandler();
                                    $mailParams = $mailsHandler->getMailParam($evId, $regId);
                                    $mailParams['infotext'] = $infotext;
                                    $mailParams['recipients'] = $regEmail;
                                    $mailsHandler->setParams($mailParams);
                                    $mailsHandler->setType($typeConfirm);
                                    $mailsHandler->setHtml(true);
                                    $mailsHandler->executeReg();
                                    unset($mailsHandler);
                                }
                            }
                        }
                    }
                }
            }
            // redirect after insert
            if ('' !== $uploaderErrors) {
                \redirect_header('event.php?op=edit&id=' . $newEvId, 5, $uploaderErrors);
            } else if ($evRegisterUse) {
                // clone questions, if event is clone of other event
                $idEvSource = Request::getInt('id_source');
                $cloneQuestions = Request::getBool('clone_question');
                if ($cloneQuestions) {
                    $crQuestion = new \CriteriaCompo();
                    $crQuestion->add(new \Criteria('evid', $idEvSource));
                    $questionsCount = $questionHandler->getCount($crQuestion);
                    if ($questionsCount > 0) {
                        $questionsAllSource = $questionHandler->getAll($crQuestion);
                        foreach ($questionsAllSource as $questionObjSource) {
                            $questionObjNew = $questionHandler->create();
                            $vars = $questionObjSource->getVars();
                            foreach (\array_keys($vars) as $var) {
                                switch ($var) {
                                    case 'id':
                                        break;
                                    case 'evid':
                                        $questionObjNew->setVar('evid', $newEvId);
                                        break;
                                    case 'datecreated':
                                        $questionObjNew->setVar('datecreated', \time());
                                        break;
                                    case 'submitter':
                                        $questionObjNew->setVar('submitter', $uidCurrent);
                                        break;
                                    case '':
                                    default:
                                        $questionObjNew->setVar($var, $questionObjSource->getVar($var));
                                        break;
                                }
                            }
                            $questionHandler->insert($questionObjNew);
                        }
                    }
                }
                $textFormOK = \_MA_WGEVENTS_FORM_OK . $dateErrors;
                if (Request::hasVar('continue_questions')) {
                    \redirect_header('question.php?op=list&amp;evid=' . $newEvId . '&amp;start=' . $start . '&amp;limit=' . $limit. '&amp;cats=' . $urlCats, 2, $textFormOK);
                }
                if ($evId > 0 || !$cloneQuestions) {
                    \redirect_header('event.php?op=show&amp;id=' . $evId . '&amp;start=' . $start . '&amp;limit=' . $limit. '&amp;cats=' . $urlCats, 2, $textFormOK);
                } else {
                    // check whether there are already question infos
                    $crQuestion = new \CriteriaCompo();
                    $crQuestion->add(new \Criteria('evid', $newEvId));
                    if ($questionHandler->getCount($crQuestion) > 0) {
                        // set of questions already existing
                        \redirect_header('question.php?op=list&amp;evid=' . $newEvId, 2, $textFormOK);
                    } else {
                        // redirect to question.php in order to add default set of questions
                        \redirect_header('question.php?op=newset&amp;evid=' . $newEvId, 0, $textFormOK);
                    }
                }
            } else {
                if ($evId > 0) {
                    $registrationHandler->cleanupRegistrations($evId);
                    $questionHandler->cleanupQuestions($evId);
                    $answerHandler->cleanupAnswers($evId);
                }
                \redirect_header('event.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit. '&amp;cats=' . $urlCats, 2, \_MA_WGEVENTS_FORM_OK . $dateErrors);
            }
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $eventObj->getHtmlErrors());
        $form = $eventObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'new':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_ADD];
        // Check permissions
        if (!$permissionsHandler->getPermEventsSubmit()) {
            \redirect_header('event.php?op=list', 3, \_NOPERM);
        }
        if ($useGMaps) {
            $GLOBALS['xoopsTpl']->assign('gmapsModalSave', $permissionsHandler->getPermEventsSubmit());
            $GLOBALS['xoopsTpl']->assign('gmapsModal', true);
            $GLOBALS['xoopsTpl']->assign('api_key', $helper->getConfig('gmaps_api'));
        }
        // Form Create
        $eventObj = $eventHandler->create();
        $eventDate = Request::getInt('eventDate', \time());
        $eventObj->setVar('datefrom', $eventDate);
        $eventObj->setVar('dateto', $eventDate);
        $eventObj->start = $start;
        $eventObj->limit = $limit;
        $form = $eventObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_EDIT];
        // Check params
        if (0 == $evId) {
            \redirect_header('event.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $eventObj = $eventHandler->get($evId);
        // Check permissions
        $permEdit = !$permissionsHandler->getPermEventsEdit($eventObj->getVar('submitter'), $eventObj->getVar('status'));
        if ($permEdit) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        if ($useGMaps) {
            $GLOBALS['xoopsTpl']->assign('gmapsModalSave', $permEdit);
            $GLOBALS['xoopsTpl']->assign('gmapsModal', true);
            $GLOBALS['xoopsTpl']->assign('api_key', $helper->getConfig('gmaps_api'));
        }
        // Get Form
        $eventObj = $eventHandler->get($evId);
        $eventObj->start = $start;
        $eventObj->limit = $limit;
        $eventObj->cats  = $urlCats;
        $form = $eventObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        // Check permissions
        if (!$permissionsHandler->getPermEventsSubmit()) {
            \redirect_header('event.php?op=list', 3, \_NOPERM);
        }

        // Request source
        $evIdSource = Request::getInt('id_source');
        // Check params
        if (0 == $evIdSource) {
            \redirect_header('event.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }

        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_CLONE];

        if ($useGMaps) {
            $GLOBALS['xoopsTpl']->assign('gmapsModalSave', $permissionsHandler->getPermEventsSubmit());
            $GLOBALS['xoopsTpl']->assign('gmapsModal', true);
            $GLOBALS['xoopsTpl']->assign('api_key', $helper->getConfig('gmaps_api'));
        }

        // Get Form
        $eventObjSource = $eventHandler->get($evIdSource);
        $eventObjClone  = $eventHandler->create();
        $vars = $eventObjSource->getVars();
        foreach (\array_keys($vars) as $var) {
            $eventObjClone->setVar($var, $eventObjSource->getVar($var));
        }
        $eventObjClone->idSource = $evIdSource;
        $form = $eventObjClone->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_DELETE];
        // Check permissions
        if (!$permissionsHandler->getPermEventsSubmit()) {
            \redirect_header('event.php?op=list', 3, \_NOPERM);
        }
        // Check params
        if (0 == $evId) {
            \redirect_header('event.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $crRegistration = new \CriteriaCompo();
        $crRegistration->add(new \Criteria('evid', $evId));
        $registrationsCount = $registrationHandler->getCount($crRegistration);
        if ($registrationsCount > 0) {
            \redirect_header('event.php?op=list', 3, \_MA_WGEVENTS_EVENT_DELETE_ERR);
        }
        $eventObj = $eventHandler->get($evId);
        $evName = $eventObj->getVar('name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('event.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $registrationHandler->cleanupRegistrations($evId);
            $questionHandler->cleanupQuestions($evId);
            $answerHandler->cleanupAnswers($evId);
            if ($eventHandler->delete($eventObj)) {
                // Event delete notification
                /*
                $tags = [];
                $tags['ITEM_NAME'] = $evName;
                $notificationHandler = \xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'global_delete', $tags);
                $notificationHandler->triggerEvent('events', $evId, 'event_delete', $tags);
                */
                \redirect_header('event.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $eventObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $evId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMDELETE_EVENT, $eventObj->getVar('name')), \_MA_WGEVENTS_CONFIRMDELETE_TITLE, \_MA_WGEVENTS_CONFIRMDELETE_LABEL);
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'cancel':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_EVENT_CANCEL];
        // Check permissions
        if (!$permissionsHandler->getPermEventsSubmit()) {
            \redirect_header('event.php?op=list', 3, \_NOPERM);
        }
        // Check params
        if (0 == $evId) {
            \redirect_header('event.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $eventObj = $eventHandler->get($evId);
        $evName = $eventObj->getVar('name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('event.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $eventObj->setVar('status', Constants::STATUS_CANCELED);
            $eventObj->setVar('datecreated', \time());
            $eventObj->setVar('submitter', $uidCurrent);
            // delete all questions/registrations/answers
            $registrationHandler->cleanupRegistrations($evId);
            $questionHandler->cleanupQuestions($evId);
            $answerHandler->cleanupAnswers($evId);
            // Insert Data
            if ($eventHandler->insert($eventObj)) {
                // Event delete notification
                /*
                $tags = [];
                $tags['ITEM_NAME'] = $evName;
                $notificationHandler = \xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'global_delete', $tags);
                $notificationHandler->triggerEvent('events', $evId, 'event_delete', $tags);
                */
                \redirect_header('event.php', 3, \_MA_WGEVENTS_FORM_CANCEL_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $eventObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $evId, 'start' => $start, 'limit' => $limit, 'op' => 'cancel'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMCANCEL_EVENT, $eventObj->getVar('name')), \_MA_WGEVENTS_CONFIRMCANCEL_TITLE, \_MA_WGEVENTS_CONFIRMCANCEL_LABEL);
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
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/event.php');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);

require __DIR__ . '/footer.php';
