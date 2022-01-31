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
    Common
};

require __DIR__ . '/header.php';
// Get all request values
$op    = Request::getCmd('op', 'list');
$evId  = Request::getInt('id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_events.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('events.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_EVENT, 'events.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $eventsCount = $eventsHandler->getCountEvents();
        $eventsAll = $eventsHandler->getAllEvents($start, $limit);
        $GLOBALS['xoopsTpl']->assign('events_count', $eventsCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url_uid', \WGEVENTS_UPLOAD_EVENTLOGOS_URL . '/' . $uidCurrent . '/');
        $GLOBALS['xoopsTpl']->assign('use_gm', $helper->getConfig('use_gm'));
        $GLOBALS['xoopsTpl']->assign('use_wggallery', $helper->getConfig('use_wggallery'));
        $GLOBALS['xoopsTpl']->assign('use_register', $helper->getConfig('use_register'));
        // Table view events
        if ($eventsCount > 0) {
            foreach (\array_keys($eventsAll) as $i) {
                $event = $eventsAll[$i]->getValuesEvents();
                $GLOBALS['xoopsTpl']->append('events_list', $event);
                unset($event);
            }
            // Display Navigation
            if ($eventsCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($eventsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_EVENTS);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_events.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('events.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_EVENTS, 'events.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Form Create
        $eventsObj = $eventsHandler->create();
        $form = $eventsObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_events.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('events.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_EVENTS, 'events.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_EVENT, 'events.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $evIdSource = Request::getInt('id_source');
        // Get Form
        $eventsObjSource = $eventsHandler->get($evIdSource);
        $eventsObj = $eventsObjSource->xoopsClone();
        $form = $eventsObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        $continueAddtionals = Request::hasVar('continue_questions');
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('events.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        if ($evId > 0) {
            $eventsObj = $eventsHandler->get($evId);
        } else {
            $eventsObj = $eventsHandler->create();
        }
        // Set Vars
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
        $eventsObj->setVar('register_use', Request::getInt('register_use'));
        if ($helper->getConfig('use_register')) {
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
                $eventsObj->setVar('register_sendermail', Request::getString('register_sendermail'));
                $eventsObj->setVar('register_sendername', Request::getString('register_sendername'));
                $eventsObj->setVar('register_signature', Request::getString('register_signature'));
            } else {
                if ($evId > 0) {
                    //reset previous values
                    $eventsObj->setVar('register_to', 0);
                    $eventsObj->setVar('register_max', 0);
                    $eventsObj->setVar('register_listwait', 0);
                    $eventsObj->setVar('register_autoaccept', 0);
                    $eventsObj->setVar('register_notify', '');
                    $eventsObj->setVar('register_sendermail', '');
                    $eventsObj->setVar('register_sendername', '');
                    $eventsObj->setVar('register_signature', '');
                    $registrationsHandler->cleanupRegistrations($evId);
                    $questionsHandler->cleanupQuestions($evId);
                    $answersHandler->cleanupAnswers($evId);
                }
            }
        }
        $eventsObj->setVar('status', Request::getInt('status'));
        $eventsObj->setVar('galid', Request::getInt('galid'));
        $eventDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $eventsObj->setVar('datecreated', $eventDatecreatedObj->getTimestamp());
        $eventsObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($eventsHandler->insert($eventsObj)) {
            $newEvId = $eventsObj->getNewInsertedId();
            if ('' !== $uploaderErrors) {
                \redirect_header('events.php?op=edit&id=' . $evId, 5, $uploaderErrors);
            } else {
                if ($continueAddtionals) {
                    \redirect_header('questions.php?op=edit&amp;evid=' . $newEvId, 2, \_MA_WGEVENTS_FORM_OK);
                } else {
                    \redirect_header('events.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
                }
            }
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $eventsObj->getHtmlErrors());
        $form = $eventsObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_events.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('events.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_EVENT, 'events.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_EVENTS, 'events.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Get Form
        $eventsObj = $eventsHandler->get($evId);
        $eventsObj->start = $start;
        $eventsObj->limit = $limit;
        $form = $eventsObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_events.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('events.php'));
        $eventsObj = $eventsHandler->get($evId);
        $evName = $eventsObj->getVar('name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('events.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($eventsHandler->delete($eventsObj)) {
                \redirect_header('events.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $eventsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $evId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $eventsObj->getVar('name')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
