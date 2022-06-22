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
        $templateMain = 'wgevents_admin_answerhist.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('answerhists.php'));
        if ($evId > 0) {
            // get all questions for this event
            $questionsArr = $questionHandler->getQuestionsByEvent($evId);
            //get answers
            $crAnswerhists = new \CriteriaCompo();
            $crAnswerhists->add(new \Criteria('evid', $evId));
            $answerhistCount = $answerhistHandler->getCount($crAnswerhists);
            $GLOBALS['xoopsTpl']->assign('answerhistCount', $answerhistCount);
            $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
            $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
            // Table view answerhistss
            if ($answerhistCount > 0) {
                $answerhistsAll = $answerhistHandler->getAll($crAnswerhists);
                foreach (\array_keys($answerhistsAll) as $i) {
                    $answerhists = $answerhistsAll[$i]->getValuesAnswerhists($questionsArr);
                    $registrationObj = $registrationHandler->get($answerhists['regid']);
                    $regname = 'deleted registration';
                    if (is_object($registrationObj)) {
                        $regname = $registrationObj->getVar('firstname') . ' ' . $registrationObj->getVar('lastname');
                    }
                    $answerhists['regname'] = $regname;
                    $GLOBALS['xoopsTpl']->append('answerhists_list', $answerhists);
                    unset($answerhists);
                }
            } else {
                $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_ANSWERHISTS);
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
                    $crAnswerhists = new \CriteriaCompo();
                    $crAnswerhists->add(new \Criteria('evid', $i));
                    $answerhistCount = $answerhistHandler->getCount($crAnswerhists);
                    $event['answerhists'] = $answerhistCount;
                    $GLOBALS['xoopsTpl']->append('events_list', $event);
                    unset($event);
                }
            }
        }
        break;
    /*
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
    */
}
require __DIR__ . '/footer.php';
