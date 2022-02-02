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

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_verification.tpl';
require_once \XOOPS_ROOT_PATH . '/header.php';

// Permission
if (!$permissionsHandler->getPermEventsView()) {
    $GLOBALS['xoopsTpl']->assign('errorPerm', _NOPERM);
    require __DIR__ . '/footer.php';
    exit;
}

$op       = Request::getCmd('op', 'verif');
$verifKey = Request::getString('verifkey');

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet($style, null);

// Breadcrumbs
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_MAIL_REG_VERIF_INFO];

$verifKeyArray  = explode('||', base64_decode($verifKey, true));
$regId = $verifKeyArray[0];
$registrationObj = $registrationHandler->get($regId);
$eventName = $eventHandler->get($registrationObj->getVar('evid'))->getVar('name');
if ($regId > 0 && \is_object($registrationObj)) {
    if (WGEVENTS_URL == (string)$verifKeyArray[1] &&
        (int)$registrationObj->getVar('evid') == (int)$verifKeyArray[2] &&
        (string)$registrationObj->getVar('email') == (string)$verifKeyArray[3] &&
        (string)$registrationObj->getVar('verifkey') == (string)$verifKeyArray[4]
    ) {
        $registrationhistHandler->createHistory($registrationObj, 'update');
        $registrationObj->setVar('status', Constants::STATUS_VERIFIED);
        $registrationObj->setVar('datecreated', \time());
        if ($registrationHandler->insert($registrationObj)) {
            $GLOBALS['xoopsTpl']->assign('verif_success', \sprintf(\_MA_WGEVENTS_MAIL_REG_VERIF_SUCCESS, $eventName));
        } else {
            $GLOBALS['xoopsTpl']->assign('verif_error', \sprintf(\_MA_WGEVENTS_MAIL_REG_VERIF_ERROR, $eventName));
        }
    } else {
        $GLOBALS['xoopsTpl']->assign('verif_error', \sprintf(\_MA_WGEVENTS_MAIL_REG_VERIF_ERROR, $eventName));
    }
} else {
    $GLOBALS['xoopsTpl']->assign('verif_error', \sprintf(\_MA_WGEVENTS_MAIL_REG_VERIF_ERROR, $eventName));
}

// Description
wgeventsMetaDescription(\_MA_WGEVENTS_INDEX_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', \WGEVENTS_URL.'/index.php');
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', \XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
require __DIR__ . '/footer.php';
