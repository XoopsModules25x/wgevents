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

require \dirname(__DIR__, 3) . '/include/cp_header.php';
require_once \dirname(__DIR__) . '/include/common.php';

$sysPathIcon16   = '../' . $GLOBALS['xoopsModule']->getInfo('sysicons16');
$sysPathIcon32   = '../' . $GLOBALS['xoopsModule']->getInfo('sysicons32');
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
$modPathIcon16   = \WGEVENTS_URL . '/' . $GLOBALS['xoopsModule']->getInfo('modicons16') . '/';
$modPathIcon32   = \WGEVENTS_URL . '/' . $GLOBALS['xoopsModule']->getInfo('modicons32') . '/';

// Get instance of module
$helper = \XoopsModules\Wgevents\Helper::getInstance();
$eventHandler = $helper->getHandler('Event');
$categoryHandler = $helper->getHandler('Category');
$registrationHandler = $helper->getHandler('Registration');
$registrationhistHandler = $helper->getHandler('Registrationhist');
$questionHandler = $helper->getHandler('Question');
$answerHandler = $helper->getHandler('Answer');
$answerhistHandler = $helper->getHandler('Answerhist');
$textblockHandler = $helper->getHandler('Textblock');
$fieldHandler = $helper->getHandler('Field');
$logHandler = $helper->getHandler('Log');
$accountHandler = $helper->getHandler('Account');
$taskHandler = $helper->getHandler('Task');
$importHandler = $helper->getHandler('Import');
$myts = MyTextSanitizer::getInstance();
// 
if (!isset($xoopsTpl) || !\is_object($xoopsTpl)) {
    require_once \XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new \XoopsTpl();
}

// Load languages
\xoops_loadLanguage('admin');
\xoops_loadLanguage('main');
\xoops_loadLanguage('modinfo');

// Local admin menu class
if (\file_exists($GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php'))) {
    require_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');
} else {
    \redirect_header('../../../admin.php', 5, \_AM_MODULEADMIN_MISSING);
}

xoops_cp_header();

// System icons path
$GLOBALS['xoopsTpl']->assign('sysPathIcon16', $sysPathIcon16);
$GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
$GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
$GLOBALS['xoopsTpl']->assign('modPathIcon32', $modPathIcon32);

$adminObject = \Xmf\Module\Admin::getInstance();
$style = \WGEVENTS_URL . '/assets/css/admin/style.css';

// tablesorter
$GLOBALS['xoopsTpl']->assign('tablesorter_allrows', \_AM_WGEVENTS_TABLESORTER_SHOW_ALL);
$GLOBALS['xoopsTpl']->assign('tablesorter_of', \_AM_WGEVENTS_TABLESORTER_OF);
$GLOBALS['xoopsTpl']->assign('tablesorter_total', \_AM_WGEVENTS_TABLESORTER_TOTALROWS);
$GLOBALS['xoopsTpl']->assign('tablesorter_pagesize', $helper->getConfig('adminpager'));
if ('d.m.Y' == _SHORTDATESTRING) {
    $dateformat = 'ddmmyyyy';
} else {
    $dateformat = 'mmddyyyy';
}
$GLOBALS['xoopsTpl']->assign('tablesorter_dateformat', $dateformat);

$xoTheme->addStylesheet($helper->url('assets/js/tablesorter/css/jquery.tablesorter.pager.min.css'));
$tablesorterTheme = $helper->getConfig('tablesorter_admin');
$xoTheme->addStylesheet($helper->url('assets/js/tablesorter/css/theme.' . $tablesorterTheme . '.min.css'));
$GLOBALS['xoopsTpl']->assign('tablesorter_theme', $tablesorterTheme);

