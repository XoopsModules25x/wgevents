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
    Common,
    Utility
};

require __DIR__ . '/header.php';
// Get all request values
$op    = Request::getCmd('op', 'list');
$regId = Request::getInt('id');
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
        $templateMain = 'wgevents_admin_registrationhist.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registrationhist.php'));
        if ($evId > 0) {
            $crRegistrationhist = new \CriteriaCompo();
            $crRegistrationhist->add(new \Criteria('evid', $evId));
            $registrationhistCount = $registrationhistHandler->getCount($crRegistrationhist);
            $GLOBALS['xoopsTpl']->assign('registrationhistCount', $registrationhistCount);
            $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
            $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
            // Table view registrationhists
            if ($registrationhistCount > 0) {
                $crRegistrationhist->setSort('id');
                $crRegistrationhist->setOrder('DESC');
                $registrationhistAll = $registrationhistHandler->getAll($crRegistrationhist);
                foreach (\array_keys($registrationhistAll) as $i) {
                    $registrationhist = $registrationhistAll[$i]->getValuesRegistrationhists();
                    $GLOBALS['xoopsTpl']->append('registrationhists_list', $registrationhist);
                    unset($registrationhist);
                }
            } else {
                $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_REGISTRATIONHISTS);
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
                    $crRegistrationhist = new \CriteriaCompo();
                    $crRegistrationhist->add(new \Criteria('evid', $i));
                    $registrationhistCount = $registrationhistHandler->getCount($crRegistrationhist);
                    $event['registrationhists'] = $registrationhistCount;
                    $GLOBALS['xoopsTpl']->append('events_list', $event);
                    unset($event);
                }
            }
        }
        break;
    /*
    case 'delete':
        $templateMain = 'wgevents_admin_registrationhist.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('registrationhist.php'));
        $registrationhistObj = $registrationhistHandler->get($regId);
        $regEvid = $registrationhistObj->getVar('evid');
        if (isset($_REQUEST['ok']) && 1 === (int)$_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('registrationhist.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($registrationhistHandler->delete($registrationhistObj)) {
                //delete existing answers
                $answerHandler->cleanupAnswers($regEvid, $regId);
                \redirect_header('registrationhist.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $registrationhistObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $regId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $registrationhistObj->getVar('evid')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    */
}
require __DIR__ . '/footer.php';
