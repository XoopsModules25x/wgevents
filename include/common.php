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
if (!\defined('XOOPS_ICONS32_PATH')) {
    \define('XOOPS_ICONS32_PATH', \XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
}
if (!\defined('XOOPS_ICONS32_URL')) {
    \define('XOOPS_ICONS32_URL', \XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
}
\define('WGEVENTS_DIRNAME', 'wgevents');
\define('WGEVENTS_PATH', \XOOPS_ROOT_PATH . '/modules/' . \WGEVENTS_DIRNAME);
\define('WGEVENTS_URL', \XOOPS_URL . '/modules/' . \WGEVENTS_DIRNAME);
\define('WGEVENTS_ICONS_PATH', \WGEVENTS_PATH . '/assets/icons');
\define('WGEVENTS_ICONS_URL', \WGEVENTS_URL . '/assets/icons');
\define('WGEVENTS_ICONS_URL_16', \WGEVENTS_URL . '/assets/icons/16');
\define('WGEVENTS_ICONS_URL_24', \WGEVENTS_URL . '/assets/icons/24');
\define('WGEVENTS_ICONS_URL_32', \WGEVENTS_URL . '/assets/icons/32');
\define('WGEVENTS_IMAGE_PATH', \WGEVENTS_PATH . '/assets/images');
\define('WGEVENTS_IMAGE_URL', \WGEVENTS_URL . '/assets/images');
\define('WGEVENTS_UPLOAD_PATH', \XOOPS_UPLOAD_PATH . '/' . \WGEVENTS_DIRNAME);
\define('WGEVENTS_UPLOAD_URL', \XOOPS_UPLOAD_URL . '/' . \WGEVENTS_DIRNAME);
\define('WGEVENTS_UPLOAD_EVENTLOGOS_PATH', \WGEVENTS_UPLOAD_PATH . '/events/logos');
\define('WGEVENTS_UPLOAD_EVENTLOGOS_URL', \WGEVENTS_UPLOAD_URL . '/events/logos');
\define('WGEVENTS_UPLOAD_CATLOGOS_PATH', \WGEVENTS_UPLOAD_PATH . '/categories/logos');
\define('WGEVENTS_UPLOAD_CATLOGOS_URL', \WGEVENTS_UPLOAD_URL . '/categories/logos');
\define('WGEVENTS_UPLOAD_CATIMAGES_PATH', \WGEVENTS_UPLOAD_PATH . '/categories/images');
\define('WGEVENTS_UPLOAD_CATIMAGES_URL', \WGEVENTS_UPLOAD_URL . '/categories/images');
\define('WGEVENTS_ADMIN', \WGEVENTS_URL . '/admin/index.php');
$localLogo = \WGEVENTS_IMAGE_URL . '/wedega_logo.png';
// Module Information
$copyright = "<a href='https://xoops.wedega.com' title='XOOPS Project on Wedega' target='_blank'><img src='" . $localLogo . "' alt='XOOPS Project on Wedega' ></a>";
require_once \XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
require_once \WGEVENTS_PATH . '/include/functions.php';
