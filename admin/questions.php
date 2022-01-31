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
$queId = Request::getInt('id');
$evId  = Request::getInt('evid');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_questions.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
        if ($evId > 0) {
            $adminObject->addItemButton(\_AM_WGEVENTS_ADD_QUESTION, 'questions.php?op=new&amp;id=' . $evId);
            $adminObject->addItemButton(\_AM_WGEVENTS_GOTO_FORMSELECT, 'questions.php', 'list');
            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
            $crQuestions = new \CriteriaCompo();
            $crQuestions->add(new \Criteria('evid', $evId));
            $questionsCount = $questionsHandler->getCount($crQuestions);
            $GLOBALS['xoopsTpl']->assign('questionsCount', $questionsCount);
            $crQuestions->setSort('weight ASC, id');
            $crQuestions->setOrder('DESC');
            $crQuestions->setStart($start);
            $crQuestions->setLimit($limit);
            $GLOBALS['xoopsTpl']->assign('questions_count', $questionsCount);
            $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
            $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
            // Table view questions
            if ($questionsCount > 0) {
                $questionsAll = $questionsHandler->getAll($crQuestions);
                foreach (\array_keys($questionsAll) as $i) {
                    $question = $questionsAll[$i]->getValuesQuestions();
                    $GLOBALS['xoopsTpl']->append('questions_list', $question);
                    unset($question);
                }
                // Display Navigation
                if ($questionsCount > $limit) {
                    require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                    $pagenav = new \XoopsPageNav($questionsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
                }
            } else {
                $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_QUESTIONS);
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
                    $crQuestions = new \CriteriaCompo();
                    $crQuestions->add(new \Criteria('evid', $i));
                    $questionsCount = $questionsHandler->getCount($crQuestions);
                    $event['questions'] = $questionsCount;
                    $GLOBALS['xoopsTpl']->append('events_list', $event);
                    unset($event);
                }
            }


            $form = $eventsHandler->getFormEventSelect();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_questions.tpl';
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_QUESTIONS, 'questions.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $questionsObj = $questionsHandler->create();
        $questionsObj->setVar('evid', $evId);
        $form = $questionsObj->getFormQuestions();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_questions.tpl';
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_QUESTIONS, 'questions.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_QUESTION, 'questions.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $queIdSource = Request::getInt('id_source');
        // Get Form
        $questionsObjSource = $questionsHandler->get($queIdSource);
        $questionsObj = $questionsObjSource->xoopsClone();
        $form = $questionsObj->getFormQuestions();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('questions.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($queId > 0) {
            $questionsObj = $questionsHandler->get($queId);
        } else {
            $questionsObj = $questionsHandler->create();
        }
        // Set Vars
        $questionsObj->setVar('evid', Request::getInt('evid'));
        $queType = Request::getInt('type');
        $questionsObj->setVar('fdid', $queType);
        $fieldsObj = $fieldsHandler->get($queType);
        $questionsObj->setVar('type', $fieldsObj->getVar('type'));
        $questionsObj->setVar('caption', Request::getString('caption'));
        $questionsObj->setVar('desc', Request::getText('desc'));
        $queValuesText = '';
        $queValues = Request::getString('values');
        if ('' != $queValues) {
            if (Constants::FIELD_COMBOBOX == $queType || Constants::FIELD_SELECTBOX == $queType || Constants::FIELD_RADIO == $queType) {
                $queValuesText = \serialize(\preg_split('/\r\n|\r|\n/', $queValues));
            } else {
                $tmpArr = [$queValues];
                $queValuesText = \serialize($tmpArr);
            }
        }
        $questionsObj->setVar('values', $queValuesText);
        $questionsObj->setVar('placeholder', Request::getString('placeholder'));
        $questionsObj->setVar('weight', Request::getInt('weight'));
        $questionsObj->setVar('required', Request::getInt('required'));
        $questionsObj->setVar('print', Request::getInt('print'));
        $questionDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $questionsObj->setVar('datecreated', $questionDatecreatedObj->getTimestamp());
        $questionsObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($questionsHandler->insert($questionsObj)) {
            \redirect_header('questions.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $questionsObj->getHtmlErrors());
        $form = $questionsObj->getFormQuestions();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_questions.tpl';
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_QUESTION, 'questions.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_QUESTIONS, 'questions.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $questionsObj = $questionsHandler->get($queId);
        $questionsObj->start = $start;
        $questionsObj->limit = $limit;
        $form = $questionsObj->getFormQuestions();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_questions.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('questions.php'));
        $questionsObj = $questionsHandler->get($queId);
        $addEvid = $questionsObj->getVar('evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('questions.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($questionsHandler->delete($questionsObj)) {
                \redirect_header('questions.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $questionsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $queId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $questionsObj->getVar('evid')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
