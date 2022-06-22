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

$moduleDirName = \basename(\dirname(__DIR__));

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

switch ($op) {
    case 'list':
    default:
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_event.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('event.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_EVENT, 'event.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $eventCount = $eventHandler->getCountEvents();
        $eventAll = $eventHandler->getAllEvents();
        $GLOBALS['xoopsTpl']->assign('eventCount', $eventCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_eventlogos_url', \WGEVENTS_UPLOAD_EVENTLOGOS_URL . '/');
        $GLOBALS['xoopsTpl']->assign('use_gmaps', $helper->getConfig('use_gmaps'));
        $GLOBALS['xoopsTpl']->assign('use_wggallery', $helper->getConfig('use_wggallery'));
        $GLOBALS['xoopsTpl']->assign('use_register', $helper->getConfig('use_register'));
        // Table view events
        if ($eventCount > 0) {
            foreach (\array_keys($eventAll) as $i) {
                $event = $eventAll[$i]->getValuesEvents();
                $GLOBALS['xoopsTpl']->append('events_list', $event);
                unset($event);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_EVENTS);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_event.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('event.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_EVENTS, 'event.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Form Create
        $eventObj = $eventHandler->create();
        $form = $eventObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_event.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('event.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_EVENTS, 'event.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_EVENT, 'event.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $evIdSource = Request::getInt('id_source');
        // Get Form
        $eventObjSource = $eventHandler->get($evIdSource);
        $eventObj = $eventObjSource->xoopsClone();
        $form = $eventObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        $continueAddtionals = Request::hasVar('continue_questions');
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('event.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
        if ($evId > 0) {
            $eventObj = $eventHandler->get($evId);
        } else {
            $eventObj = $eventHandler->create();
        }
        // Set Vars
        $uploaderErrors = '';
        $eventObj->setVar('catid', Request::getInt('catid'));
        $eventObj->setVar('name', Request::getString('name'));
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
                $eventObj->setVar('logo', $savedFilename);
            } else {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
        } else {
            if ('' != $filename) {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
            $filename = Request::getString('logo');
            if ('' != $filename) {
                $eventObj->setVar('logo', $filename);
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
        $eventObj->setVar('dateto', $eventDateto);
        $eventObj->setVar('contact', Request::getString('contact'));
        $eventObj->setVar('email', Request::getString('email'));
        $eventObj->setVar('location', Request::getString('location'));
        $eventObj->setVar('locgmlat', Request::getFloat('locgmlat'));
        $eventObj->setVar('locgmlon', Request::getFloat('locgmlon'));
        $eventObj->setVar('locgmzoom', Request::getInt('locgmzoom'));
        $evFee = Utility::StringToFloat(Request::getString('fee'));
        $eventObj->setVar('fee', $evFee);
        $eventObj->setVar('paymentinfo', Request::getText('paymentinfo'));
        $eventObj->setVar('register_use', Request::getInt('register_use'));
        if ($helper->getConfig('use_register')) {
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
                $eventObj->setVar('register_max', Request::getInt('register_max'));
                $eventObj->setVar('register_listwait', Request::getInt('register_listwait'));
                $eventObj->setVar('register_autoaccept', Request::getInt('register_autoaccept'));
                $eventObj->setVar('register_notify', Request::getString('register_notify'));
                $eventObj->setVar('register_sendermail', Request::getString('register_sendermail'));
                $eventObj->setVar('register_sendername', Request::getString('register_sendername'));
                $eventObj->setVar('register_signature', Request::getString('register_signature'));
            } else if ($evId > 0) {
                //reset previous values
                $eventObj->setVar('register_to', 0);
                $eventObj->setVar('register_max', 0);
                $eventObj->setVar('register_listwait', 0);
                $eventObj->setVar('register_autoaccept', 0);
                $eventObj->setVar('register_notify', '');
                $eventObj->setVar('register_sendermail', '');
                $eventObj->setVar('register_sendername', '');
                $eventObj->setVar('register_signature', '');
                $registrationHandler->cleanupRegistrations($evId);
                $questionHandler->cleanupQuestions($evId);
                $answerHandler->cleanupAnswers($evId);
            }
        }
        $eventObj->setVar('status', Request::getInt('status'));
        $eventObj->setVar('galid', Request::getInt('galid'));
        $eventObj->setVar('identifier', Request::getString('identifier'));
        $eventDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $eventObj->setVar('datecreated', $eventDatecreatedObj->getTimestamp());
        $eventObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($eventHandler->insert($eventObj)) {
            $newEvId = $eventObj->getNewInsertedId();
            if ('' !== $uploaderErrors) {
                \redirect_header('event.php?op=edit&id=' . $evId, 5, $uploaderErrors);
            } else if ($continueAddtionals) {
                \redirect_header('question.php?op=edit&amp;evid=' . $newEvId, 2, \_MA_WGEVENTS_FORM_OK);
            } else {
                \redirect_header('event.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
            }
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $eventObj->getHtmlErrors());
        $form = $eventObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_event.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('event.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_EVENT, 'event.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_EVENTS, 'event.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Get Form
        $eventObj = $eventHandler->get($evId);
        $eventObj->start = $start;
        $eventObj->limit = $limit;
        $form = $eventObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_event.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('event.php'));
        $eventObj = $eventHandler->get($evId);
        $evName = $eventObj->getVar('name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('event.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($eventHandler->delete($eventObj)) {
                \redirect_header('event.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $eventObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $evId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $eventObj->getVar('name')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
