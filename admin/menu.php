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

$dirname       = \basename(\dirname(__DIR__));
$moduleHandler = \xoops_getHandler('module');
$xoopsModule   = \XoopsModule::getByDirname($dirname);
$moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
$sysPathIcon32 = $moduleInfo->getInfo('sysicons32');

$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU1,
    'link' => 'admin/index.php',
    'icon' => 'assets/icons/32//dashboard.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU2,
    'link' => 'admin/events.php',
    'icon' => 'assets/icons/32/events.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU3,
    'link' => 'admin/questions.php',
    'icon' => 'assets/icons/32/questions.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU5,
    'link' => 'admin/registrations.php',
    'icon' => 'assets/icons/32/registrations.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU4,
    'link' => 'admin/answers.php',
    'icon' => 'assets/icons/32/answers.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU7,
    'link' => 'admin/categories.php',
    'icon' => 'assets/icons/32/categories.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU8,
    'link' => 'admin/fields.php',
    'icon' => 'assets/icons/32/fields.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU10,
    'link' => 'admin/permissions.php',
    'icon' => 'assets/icons/32/permissions.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU13,
    'link' => 'admin/logs.php',
    'icon' => 'assets/icons/32/logs.png',
];
/*
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU11,
    'link' => 'admin/registrationshist.php',
    'icon' => 'assets/icons/32/registrationshist.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU12,
    'link' => 'admin/answershist.php',
    'icon' => 'assets/icons/32/answershist.png',
];
*/
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU14,
    'link' => 'admin/accounts.php',
    'icon' => 'assets/icons/32/accounts.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU9,
    'link' => 'admin/maintenance.php',
    'icon' => 'assets/icons/32/maintenance.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU20,
    'link' => 'admin/clone.php',
    'icon' => 'assets/icons/32/clone.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ADMENU21,
    'link' => 'admin/feedback.php',
    'icon' => 'assets/icons/32/feedback.png',
];
$adminmenu[] = [
    'title' => \_MI_WGEVENTS_ABOUT,
    'link' => 'admin/about.php',
    'icon' => 'assets/icons/32/about.png',
];
