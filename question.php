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
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_question.tpl';
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
        $crQuestion = new \CriteriaCompo();
        $crQuestion->add(new \Criteria('evid', $addEvid));
        $questionsCount = $questionHandler->getCount($crQuestion);
        $GLOBALS['xoopsTpl']->assign('questionsCount', $questionsCount);
        $crQuestion->setSort('weight ASC, id');
        $crQuestion->setOrder('DESC');
        $crQuestion->setStart($start);
        $crQuestion->setLimit($limit);
        $questionsAll = $questionHandler->getAll($crQuestion);
        if ($questionsCount > 0) {
            $questions = [];
            $evName = '';
            $evSubmitter = 0;
            $evStatus = 0;
            // Get All Question
            foreach (\array_keys($questionsAll) as $i) {
                $questions[$i] = $questionsAll[$i]->getValuesQuestions();
                if ('' == $evName) {
                    $eventObj = $eventHandler->get($questionsAll[$i]->getVar('evid'));
                    $evName = $eventObj->getVar('name');
                    $evSubmitter = $eventObj->getVar('submitter');
                    $evStatus = $eventObj->getVar('status');
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
            \redirect_header('question.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $eventObj = $eventHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermQuestionsAdmin($eventObj->getVAr('submitter'), $eventObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        if ($queId > 0) {
            $questionObj = $questionHandler->get($queId);
        } else {
            $questionObj = $questionHandler->create();
        }
        $questionObj->setVar('evid', $addEvid);
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
        $questionObj->setVar('required', Request::getInt('required'));
        $questionObj->setVar('print', Request::getInt('print'));
        $questionObj->setVar('weight', Request::getInt('weight'));
        if (Request::hasVar('datecreated_int')) {
            $questionObj->setVar('datecreated', Request::getInt('datecreated_int'));
        } else {
            $questionDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
            $questionObj->setVar('datecreated', $questionDatecreatedObj->getTimestamp());
        }
        $questionObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($questionHandler->insert($questionObj)) {
            // redirect after insert
            \redirect_header('question.php?op=list&amp;evid=' . $addEvid . '&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
        }
        // Get Form Error
        $GLOBALS['xoopsTpl']->assign('error', $questionObj->getHtmlErrors());
        $form = $questionObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'newset':
        $eventObj = $eventHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermQuestionsAdmin($eventObj->getVAr('submitter'), $eventObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $questionHandler->createQuestionsDefaultset($addEvid);
        \redirect_header('question.php?op=list&amp;evid=' . $addEvid . '&amp;start=' . $start . '&amp;limit=' . $limit, 0, \_MA_WGEVENTS_FORM_OK);
        break;
    case 'new':
        $eventObj = $eventHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermQuestionsAdmin($eventObj->getVAr('submitter'), $eventObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTION_ADD];
        // Form Create
        $questionObj = $questionHandler->create();
        $questionObj->setVar('evid', $addEvid);
        $form = $questionObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'test':
        $eventObj = $eventHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermQuestionsAdmin($eventObj->getVAr('submitter'), $eventObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTION_ADD];
        // Form Create
        $registrationObj = $registrationHandler->create();
        $registrationObj->setVar('evid', $addEvid);
        $form = $registrationObj->getForm('', true);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $eventObj = $eventHandler->get($addEvid);
        // Check permissions
        if (!$permissionsHandler->getPermQuestionsAdmin($eventObj->getVAr('submitter'), $eventObj->getVar('status'))) {
            \redirect_header('index.php?op=list', 3, \_NOPERM);
        }
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/forms.js');
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTION_EDIT];
        // Check params
        if (0 == $queId) {
            \redirect_header('question.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $questionObj = $questionHandler->get($queId);
        $questionObj->start = $start;
        $questionObj->limit = $limit;
        $form = $questionObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTION_CLONE];
        // Request source
        $queIdSource = Request::getInt('id_source');
        // Check params
        if (0 == $queIdSource) {
            \redirect_header('question.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        // Get Form
        $questionObjSource = $questionHandler->get($queIdSource);
        $questionObj = $questionHandler->create();
        $questionObj->setVar('evid', $questionObjSource->getVar('evid'));
        $questionObj->setVar('fdid', $questionObjSource->getVar('fdid'));
        $questionObj->setVar('type', $questionObjSource->getVar('type'));
        $questionObj->setVar('caption', $questionObjSource->getVar('caption'));
        $questionObj->setVar('desc', $questionObjSource->getVar('desc'));
        $questionObj->setVar('values', $questionObjSource->getVar('values'));
        $questionObj->setVar('placeholder', $questionObjSource->getVar('placeholder'));
        $questionObj->setVar('required', $questionObjSource->getVar('required'));
        $questionObj->setVar('print', $questionObjSource->getVar('print'));
        $questionObj->setVar('weight', $questionObjSource->getVar('weight'));
        $form = $questionObj->getForm('question.php?op=save');
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        unset($questionObjSource);
        break;
    case 'delete':
        // Breadcrumbs
        $xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_QUESTION_DELETE];
        // Check params
        if (0 == $queId) {
            \redirect_header('question.php?op=list', 3, \_MA_WGEVENTS_INVALID_PARAM);
        }
        $questionObj = $questionHandler->get($queId);
        $addEvid = $questionObj->getVar('evid');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('question.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($questionHandler->delete($questionObj)) {
                \redirect_header('question.php?list&amp;evid=' . $addEvid, 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $questionObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $queId, 'evid' => $addEvid, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_CONFIRMDELETE_QUESTION, $questionObj->getVar('caption')), \_MA_WGEVENTS_CONFIRMDELETE_TITLE, \_MA_WGEVENTS_CONFIRMDELETE_LABEL);
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'order':
        $order = $_POST['order'];
        for ($i = 0, $iMax = \count($order); $i < $iMax; $i++) {
            $questionObj = $questionHandler->get($order[$i]);
            $questionObj->setVar('weight', $i + 1);
            $questionHandler->insert($questionObj);
        }
        break;
}

// Keywords
wgeventsMetaKeywords($helper->getConfig('keywords') . ', ' . \implode(',', $keywords));
unset($keywords);

// Description
wgeventsMetaDescription(\_MA_WGEVENTS_QUESTIONS_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/question.php');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);

require __DIR__ . '/footer.php';
