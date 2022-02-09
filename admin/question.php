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


$moduleDirName = \basename(\dirname(__DIR__));


$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);
$xoTheme->addStylesheet($helper->url('assets/js/tablesorter/css/theme.blue.css'));

switch ($op) {
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_question.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('question.php'));
        if ($evId > 0) {
            $adminObject->addItemButton(\_AM_WGEVENTS_ADD_QUESTION, 'question.php?op=new&amp;id=' . $evId);
            $adminObject->addItemButton(\_AM_WGEVENTS_GOTO_FORMSELECT, 'question.php', 'list');
            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
            $crQuestion = new \CriteriaCompo();
            $crQuestion->add(new \Criteria('evid', $evId));
            $questionCount = $questionHandler->getCount($crQuestion);
            $GLOBALS['xoopsTpl']->assign('questionCount', $questionCount);
            $crQuestion->setSort('weight ASC, id');
            $crQuestion->setOrder('DESC');
            $crQuestion->setStart($start);
            $crQuestion->setLimit($limit);
            $GLOBALS['xoopsTpl']->assign('questionCount', $questionCount);
            $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
            $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
            // Table view questions
            if ($questionCount > 0) {
                $questionAll = $questionHandler->getAll($crQuestion);
                foreach (\array_keys($questionAll) as $i) {
                    $question = $questionAll[$i]->getValuesQuestions();
                    $GLOBALS['xoopsTpl']->append('questions_list', $question);
                    unset($question);
                }
                // Display Navigation
                if ($questionCount > $limit) {
                    require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                    $pagenav = new \XoopsPageNav($questionCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
                }
            } else {
                $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_QUESTIONS);
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
                    $crQuestion = new \CriteriaCompo();
                    $crQuestion->add(new \Criteria('evid', $i));
                    $questionCount = $questionHandler->getCount($crQuestion);
                    $event['questions'] = $questionCount;
                    $GLOBALS['xoopsTpl']->append('events_list', $event);
                    unset($event);
                }
            }
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_question.tpl';
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('question.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_QUESTIONS, 'question.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $questionObj = $questionHandler->create();
        $questionObj->setVar('evid', $evId);
        $form = $questionObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_question.tpl';
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('question.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_QUESTIONS, 'question.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_QUESTION, 'question.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $queIdSource = Request::getInt('id_source');
        // Get Form
        $questionObjSource = $questionHandler->get($queIdSource);
        $questionObj = $questionObjSource->xoopsClone();
        $form = $questionObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('question.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($queId > 0) {
            $questionObj = $questionHandler->get($queId);
        } else {
            $questionObj = $questionHandler->create();
        }
        // Set Vars
        $questionObj->setVar('evid', Request::getInt('evid'));
        $queType = Request::getInt('type');
        $questionObj->setVar('fdid', $queType);
        $fieldObj = $fieldHandler->get($queType);
        $questionObj->setVar('type', $fieldObj->getVar('type'));
        $questionObj->setVar('caption', Request::getString('caption'));
        $questionObj->setVar('desc', Request::getText('desc'));
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
        $questionObj->setVar('values', $queValuesText);
        $questionObj->setVar('placeholder', Request::getString('placeholder'));
        $questionObj->setVar('weight', Request::getInt('weight'));
        $questionObj->setVar('required', Request::getInt('required'));
        $questionObj->setVar('print', Request::getInt('print'));
        $questionDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $questionObj->setVar('datecreated', $questionDatecreatedObj->getTimestamp());
        $questionObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($questionHandler->insert($questionObj)) {
            \redirect_header('question.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $questionObj->getHtmlErrors());
        $form = $questionObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_question.tpl';
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('question.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_QUESTION, 'question.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_QUESTIONS, 'question.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $questionObj = $questionHandler->get($queId);
        $questionObj->start = $start;
        $questionObj->limit = $limit;
        $form = $questionObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_question.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('question.php'));
        $questionObj = $questionHandler->get($queId);
        $addEvid = $questionObj->getVar('evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('question.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($questionHandler->delete($questionObj)) {
                \redirect_header('question.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $questionObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $queId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $questionObj->getVar('evid')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
