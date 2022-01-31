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
use XoopsModules\Wgevents\{
    Constants,
    Common
};

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_questions.tpl';
require_once \XOOPS_ROOT_PATH . '/header.php';

$op      = Request::getCmd('op', 'list');
$queId   = Request::getInt('id');
$addEvid = Request::getInt('evid');
$start   = Request::getInt('start');
$limit   = Request::getInt('limit', $helper->getConfig('userpager'));
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

$GLOBALS['xoopsTpl']->assign('addEvid', $addEvid);

switch ($op) {
    case 'show':
    case 'list':
    default:
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/jquery-ui.min.js');
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/sortables.js');
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTIONS_LIST];
        // get default fields
        $regdefaults = [];
        $regdefaults[] = [
            'type_text' => \_MA_WGEVENTS_FIELD_TEXTBOX,
            'caption' => \_MA_WGEVENTS_REGISTRATION_FIRSTNAME,
            'value_list' => '',
            'placeholder' => \_MA_WGEVENTS_REGISTRATION_FIRSTNAME_PLACEHOLDER,
            'required' => \_YES,
            'print' => \_YES
        ];
        $regdefaults[] = [
            'type_text' => \_MA_WGEVENTS_FIELD_TEXTBOX,
            'caption' => \_MA_WGEVENTS_REGISTRATION_LASTNAME,
            'value_list' => '',
            'placeholder' => \_MA_WGEVENTS_REGISTRATION_LASTNAME_PLACEHOLDER,
            'required' => \_YES,
            'print' => \_YES
        ];
        $regdefaults[] = [
            'type_text' => \_MA_WGEVENTS_FIELD_TEXTBOX,
            'caption' => \_MA_WGEVENTS_REGISTRATION_EMAIL,
            'value_list' => '',
            'placeholder' => \_MA_WGEVENTS_REGISTRATION_EMAIL_PLACEHOLDER,
            'required' => \_YES,
            'print' => \_YES
        ];
        $GLOBALS['xoopsTpl']->assign('regdefaults', $regdefaults);

        // get question fields
        $crQuestions = new \CriteriaCompo();
        $crQuestions->add(new \Criteria('evid', $addEvid));
        $questionsCount = $questionsHandler->getCount($crQuestions);
        $GLOBALS['xoopsTpl']->assign('questionsCount', $questionsCount);
        $crQuestions->setSort('weight ASC, id');
        $crQuestions->setOrder('DESC');
        $crQuestions->setStart($start);
        $crQuestions->setLimit($limit);
        $questionsAll = $questionsHandler->getAll($crQuestions);
        if ($questionsCount > 0) {
            $questions = [];
            $evName = '';
            $evSubmitter = 0;
            $evStatus = 0;
            // Get All Questions
            foreach (\array_keys($questionsAll) as $i) {
                $questions[$i] = $questionsAll[$i]->getValuesQuestions();
                if ('' == $evName) {
                    $eventsObj = $eventsHandler->get($questionsAll[$i]->getVar('evid'));
                    $evName = $eventsObj->getVar('name');
                    $evSubmitter = $eventsObj->getVar('submitter');
                    $evStatus = $eventsObj->getVar('status');
                    $keywords[$i] = $evName;
                }
            }
            $GLOBALS['xoopsTpl']->assign('questions', $questions);
            unset($questions);
            // Display Navigation
            if ($questionsCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($questionsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
            $GLOBALS['xoopsTpl']->assign('eventName', $evName);
            $permEdit = $permissionsHandler->getPermQuestionsAdmin($evSubmitter, $evStatus);
            $GLOBALS['xoopsTpl']->assign('permEdit', $permEdit);
            $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);

            $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', \strip_tags($evName . ' - ' . $GLOBALS['xoopsModule']->getVar('name')));

        }
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('questions.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $eventsObj = $eventsHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermQuestionsAdmin($eventsObj->getVAr('submitter'), $eventsObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        if ($queId > 0) {
            $questionsObj = $questionsHandler->get($queId);
        } else {
            $questionsObj = $questionsHandler->create();
        }
        $questionsObj->setVar('evid', $addEvid);
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
        $questionsObj->setVar('required', Request::getInt('required'));
        $questionsObj->setVar('print', Request::getInt('print'));
        $questionsObj->setVar('weight', Request::getInt('weight'));
        if (Request::hasVar('datecreated_int')) {
            $questionsObj->setVar('datecreated', Request::getInt('datecreated_int'));
        } else {
            $questionDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
            $questionsObj->setVar('datecreated', $questionDatecreatedObj->getTimestamp());
        }
        $questionsObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($questionsHandler->insert($questionsObj)) {
            // redirect after insert
            \redirect_header('questions.php?op=list&amp;evid=' . $addEvid . '&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $questionsObj->getHtmlErrors());
        $form = $questionsObj->getFormQuestions();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'newset':
        $eventsObj = $eventsHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermQuestionsAdmin($eventsObj->getVAr('submitter'), $eventsObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $questionsHandler->createQuestionsDefaultset($addEvid);
        \redirect_header('questions.php?op=list&amp;evid=' . $addEvid . '&amp;start=' . $start . '&amp;limit=' . $limit, 0, \_MA_WGEVENTS_FORM_OK);
        break;
    case 'new':
        $eventsObj = $eventsHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermQuestionsAdmin($eventsObj->getVAr('submitter'), $eventsObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTION_ADD];
        // Form Create
        $questionsObj = $questionsHandler->create();
        $questionsObj->setVar('evid', $addEvid);
        $form = $questionsObj->getFormQuestions();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'test':
        $eventsObj = $eventsHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermQuestionsAdmin($eventsObj->getVAr('submitter'), $eventsObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTION_ADD];
        // Form Create
        $registrationsObj = $registrationsHandler->create();
        $registrationsObj->setVar('evid', $addEvid);
        $form = $registrationsObj->getFormRegistrations('', true);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $eventsObj = $eventsHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermQuestionsAdmin($eventsObj->getVAr('submitter'), $eventsObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTION_EDIT];
        // Check params
        if (0 == $queId) {
            \redirect_header('questions.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $questionsObj = $questionsHandler->get($queId);
        $questionsObj->start = $start;
        $questionsObj->limit = $limit;
        $form = $questionsObj->getFormQuestions();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTION_CLONE];
        // Request source
        $queIdSource = Request::getInt('id_source');
        // Check params
        if (0 == $queIdSource) {
            \redirect_header('questions.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $questionsObjSource = $questionsHandler->get($queIdSource);
        $questionsObj = $questionsHandler->create();
        $questionsObj->setVar('evid', $questionsObjSource->getVar('evid'));
        $questionsObj->setVar('fdid', $questionsObjSource->getVar('fdid'));
        $questionsObj->setVar('type', $questionsObjSource->getVar('type'));
        $questionsObj->setVar('caption', $questionsObjSource->getVar('caption'));
        $questionsObj->setVar('desc', $questionsObjSource->getVar('desc'));
        $questionsObj->setVar('values', $questionsObjSource->getVar('values'));
        $questionsObj->setVar('placeholder', $questionsObjSource->getVar('placeholder'));
        $questionsObj->setVar('required', $questionsObjSource->getVar('required'));
        $questionsObj->setVar('print', $questionsObjSource->getVar('print'));
        $questionsObj->setVar('weight', $questionsObjSource->getVar('weight'));
        $form = $questionsObj->getFormQuestions('questions.php?op=save');
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        unset($questionsObjSource);
        break;
    case 'delete':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTION_DELETE];
        // Check params
        if (0 == $queId) {
            \redirect_header('questions.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $questionsObj = $questionsHandler->get($queId);
        $addEvid = $questionsObj->getVar('evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('questions.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($questionsHandler->delete($questionsObj)) {
                \redirect_header('questions.php?list&amp;evid=' . $addEvid, 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $questionsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $queId, 'evid' => $addEvid, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMDELETE_QUESTION, $questionsObj->getVar('caption')), \_MA_WGEVENTS_CONFIRMDELETE_TITLE, \_MA_WGEVENTS_CONFIRMDELETE_LABEL);
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'order':
        $order = $_POST['order'];
        for ($i = 0, $iMax = \count($order); $i < $iMax; $i++) {
            $questionsObj = $questionsHandler->get($order[$i]);
            $questionsObj->setVar('weight', $i + 1);
            $questionsHandler->insert($questionsObj);
        }
        break;
}

// Keywords
wgeventsMetaKeywords($helper->getConfig('keywords') . ', ' . \implode(',', $keywords));
unset($keywords);

// Description
wgeventsMetaDescription(\_MA_WGEVENTS_QUESTIONS_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/questions.php');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);

require __DIR__ . '/footer.php';
