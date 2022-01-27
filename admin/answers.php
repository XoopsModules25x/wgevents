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
$ansId = Request::getInt('ans_id');
$evId  = Request::getInt('ev_id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_answers.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answers.php'));
        if ($evId > 0) {
            $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ANSWER, 'answers.php?op=new&amp;ev_id=' . $evId);
            $adminObject->addItemButton(\_AM_WGEVENTS_GOTO_FORMSELECT, 'answers.php', 'list');
            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
            $answersCount = $answersHandler->getCountAnswers();
            $answersAll = $answersHandler->getAllAnswers($start, $limit);
            $GLOBALS['xoopsTpl']->assign('answers_count', $answersCount);
            $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
            $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
            // Table view answers
            if ($answersCount > 0) {
                foreach (\array_keys($answersAll) as $i) {
                    $answer = $answersAll[$i]->getValuesAnswers();
                    $registrationsObj = $registrationsHandler->get($answer['ans_regid']);
                    $answer['regname'] = $registrationsObj->getVar('reg_firstname') . ' ' . $registrationsObj->getVar('reg_lastname');
                    $GLOBALS['xoopsTpl']->append('answers_list', $answer);
                    unset($answer);
                }
                // Display Navigation
                if ($answersCount > $limit) {
                    require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                    $pagenav = new \XoopsPageNav($answersCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
                }
            } else {
                $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_ANSWERS);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('eventsHeader', \sprintf(_AM_WGEVENTS_LIST_EVENTS_LAST, $limit));
            $eventsCount = $eventsHandler->getCountEvents();
            $GLOBALS['xoopsTpl']->append('events_count', $eventsCount);
            // Table view events
            if ($eventsCount > 0) {
                $eventsAll = $eventsHandler->getAllEvents($start, $limit);
                foreach (\array_keys($eventsAll) as $i) {
                    $event = $eventsAll[$i]->getValuesEvents();
                    $crAnswers = new \CriteriaCompo();
                    $crAnswers->add(new \Criteria('ans_evid', $i));
                    $answersCount = $answersHandler->getCount($crAnswers);
                    $event['answers'] = $answersCount;
                    $GLOBALS['xoopsTpl']->append('events_list', $event);
                    unset($event);
                }
            }
            $form = $eventsHandler->getFormEventSelect();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_answers.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answers.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ANSWERS, 'answers.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $answersObj = $answersHandler->create();
        $answersObj->setVar('ans_evid', $evId);
        $form = $answersObj->getFormAnswers();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_answers.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answers.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ANSWERS, 'answers.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ANSWER, 'answers.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $ansIdSource = Request::getInt('ans_id_source');
        // Get Form
        $answersObjSource = $answersHandler->get($ansIdSource);
        $answersObj = $answersObjSource->xoopsClone();
        $form = $answersObj->getFormAnswers();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('answers.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($ansId > 0) {
            $answersObj = $answersHandler->get($ansId);
        } else {
            $answersObj = $answersHandler->create();
        }
        // Set Vars
        $answersObj->setVar('ans_regid', Request::getInt('ans_regid'));
        $answersObj->setVar('ans_queid', Request::getInt('ans_queid'));
        $answersObj->setVar('ans_evid', Request::getInt('ans_evid'));
        $answersObj->setVar('ans_text', Request::getString('ans_text'));
        $answerDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('ans_datecreated'));
        $answersObj->setVar('ans_datecreated', $answerDatecreatedObj->getTimestamp());
        $answersObj->setVar('ans_submitter', Request::getInt('ans_submitter'));
        // Insert Data
        if ($answersHandler->insert($answersObj)) {
                \redirect_header('answers.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $answersObj->getHtmlErrors());
        $form = $answersObj->getFormAnswers();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_answers.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answers.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ANSWER, 'answers.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ANSWERS, 'answers.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $answersObj = $answersHandler->get($ansId);
        $answersObj->start = $start;
        $answersObj->limit = $limit;
        $form = $answersObj->getFormAnswers();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_answers.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answers.php'));
        $answersObj = $answersHandler->get($ansId);
        $ansEvid = $answersObj->getVar('ans_evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('answers.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($answersHandler->delete($answersObj)) {
                \redirect_header('answers.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $answersObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'ans_id' => $ansId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $answersObj->getVar('ans_evid')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
