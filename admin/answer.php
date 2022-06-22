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
$ansId = Request::getInt('id');
$evId  = Request::getInt('evid');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

$moduleDirName = \basename(\dirname(__DIR__));

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_answer.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answer.php'));
        if ($evId > 0) {
            $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ANSWER, 'answer.php?op=new&amp;evId=' . $evId);
            $adminObject->addItemButton(\_AM_WGEVENTS_GOTO_FORMSELECT, 'answer.php', 'list');
            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
            $crAnswer = new \CriteriaCompo();
            $crAnswer->add(new \Criteria('evid', $evId));
            $answerCount = $answerHandler->getCount($crAnswer);
            $GLOBALS['xoopsTpl']->assign('answerCount', $answerCount);
            $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
            $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
            // Table view answers
            if ($answerCount > 0) {
                $answerAll = $answerHandler->getAll($crAnswer);
                foreach (\array_keys($answerAll) as $i) {
                    $answer = $answerAll[$i]->getValuesAnswers();
                    $registrationObj = $registrationHandler->get($answer['regid']);
                    $answer['regname'] = $registrationObj->getVar('firstname') . ' ' . $registrationObj->getVar('lastname');
                    $GLOBALS['xoopsTpl']->append('answers_list', $answer);
                    unset($answer);
                }
                /*
                // Display Navigation
                if ($answerCount > $limit) {
                    require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                    $pagenav = new \XoopsPageNav($answerCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
                }*/
            } else {
                $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_ANSWERS);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('eventsHeader', \sprintf(_AM_WGEVENTS_LIST_EVENTS_LAST, $limit));
            $eventCount = $eventHandler->getCountEvents();
            $GLOBALS['xoopsTpl']->append('eventCount', $eventCount);
            // Table view events
            if ($eventCount > 0) {
                $eventAll = $eventHandler->getAllEvents($start, $limit);
                foreach (\array_keys($eventAll) as $i) {
                    $event = $eventAll[$i]->getValuesEvents();
                    $crAnswer = new \CriteriaCompo();
                    $crAnswer->add(new \Criteria('evid', $i));
                    $answerCount = $answerHandler->getCount($crAnswer);
                    $event['answers'] = $answerCount;
                    $GLOBALS['xoopsTpl']->append('events_list', $event);
                    unset($event);
                }
            }
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_answer.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answer.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ANSWERS, 'answer.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $answerObj = $answerHandler->create();
        $answerObj->setVar('evid', $evId);
        $form = $answerObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_answer.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answer.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ANSWERS, 'answer.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ANSWER, 'answer.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $ansIdSource = Request::getInt('id_source');
        // Get Form
        $answerObjSource = $answerHandler->get($ansIdSource);
        $answerObj = $answerObjSource->xoopsClone();
        $form = $answerObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('answer.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($ansId > 0) {
            $answerObj = $answerHandler->get($ansId);
        } else {
            $answerObj = $answerHandler->create();
        }
        // Set Vars
        $answerObj->setVar('regid', Request::getInt('regid'));
        $answerObj->setVar('queid', Request::getInt('queid'));
        $answerObj->setVar('evid', Request::getInt('evid'));
        $answerObj->setVar('text', Request::getString('text'));
        $answerDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $answerObj->setVar('datecreated', $answerDatecreatedObj->getTimestamp());
        $answerObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($answerHandler->insert($answerObj)) {
                \redirect_header('answer.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $answerObj->getHtmlErrors());
        $form = $answerObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_answer.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answer.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ANSWER, 'answer.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ANSWERS, 'answer.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $answerObj = $answerHandler->get($ansId);
        $answerObj->start = $start;
        $answerObj->limit = $limit;
        $form = $answerObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_answer.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answer.php'));
        $answerObj = $answerHandler->get($ansId);
        $ansEvid = $answerObj->getVar('evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('answer.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($answerHandler->delete($answerObj)) {
                \redirect_header('answer.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $answerObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $ansId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $answerObj->getVar('evid')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
