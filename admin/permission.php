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
use XoopsModules\Wgevents\Constants;

require __DIR__ . '/header.php';

// Template Index
$templateMain = 'wgevents_admin_permission.tpl';
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('permission.php'));

$op = Request::getCmd('op', 'global');

// Get Form
require_once \XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
\xoops_load('XoopsFormLoader');
$formTitle = \_AM_WGEVENTS_PERMISSIONS_GLOBAL;
$permName = 'wgevents_ac';
$permDesc = \_AM_WGEVENTS_PERMISSIONS_DESC;
$permDesc .= \_AM_WGEVENTS_PERMISSIONS_GLOBAL_DESC;
$permDesc .= \_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_DESC;
$permDesc .= \_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_AUTO_DESC;
$permDesc .= \_AM_WGEVENTS_PERMISSIONS_EVENTS_SUBMIT_DESC;
$permDesc .= \_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_AUTOVERIF_DESC;
$permDesc .= \_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_SUBMIT_DESC;

$globalPerms = [
    Constants::PERM_GLOBAL_APPROVE => \_AM_WGEVENTS_PERMISSIONS_GLOBAL_APPROVE,
    Constants::PERM_GLOBAL_SUBMIT => \_AM_WGEVENTS_PERMISSIONS_GLOBAL_SUBMIT,
    Constants::PERM_GLOBAL_VIEW => \_AM_WGEVENTS_PERMISSIONS_GLOBAL_VIEW,
    Constants::PERM_EVENTS_APPROVE => \_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE,
    Constants::PERM_EVENTS_APPROVE_AUTO => \_AM_WGEVENTS_PERMISSIONS_EVENTS_APPROVE_AUTO,
    Constants::PERM_EVENTS_SUBMIT => \_AM_WGEVENTS_PERMISSIONS_EVENTS_SUBMIT,
    Constants::PERM_EVENTS_VIEW => \_AM_WGEVENTS_PERMISSIONS_EVENTS_VIEW,
    Constants::PERM_REGISTRATIONS_AUTOVERIF => \_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_AUTOVERIF,
    Constants::PERM_REGISTRATIONS_SUBMIT => \_AM_WGEVENTS_PERMISSIONS_REGISTRATIONS_SUBMIT,
];

$moduleId = $xoopsModule->getVar('mid');
$permForm = new \XoopsGroupPermForm($formTitle, $moduleId, $permName, $permDesc, 'admin/permission.php');
$permFound = false;
if ('global' === $op) {
    foreach ($globalPerms as $gPermId => $gPermName) {
        $permForm->addItem($gPermId, $gPermName);
    }
    $infos =
    $GLOBALS['xoopsTpl']->assign('form', $permForm->render());
    $permFound = true;
}
unset($permForm);
if (true !== $permFound) {
    \redirect_header('permission.php', 3, \_AM_WGEVENTS_NO_PERMISSIONS_SET);
    exit();
}
require __DIR__ . '/footer.php';
