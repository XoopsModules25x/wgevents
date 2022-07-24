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
require \dirname(__DIR__, 2) . '/mainfile.php';
require __DIR__ . '/include/common.php';
$moduleDirName = \basename(__DIR__);
// Breadcrumbs
$xoBreadcrumbs = [];
// Get instance of module
$helper = \XoopsModules\Wgevents\Helper::getInstance();
$eventHandler            = $helper->getHandler('Event');
$categoryHandler         = $helper->getHandler('Category');
$registrationHandler     = $helper->getHandler('Registration');
$registrationhistHandler = $helper->getHandler('Registrationhist');
$questionHandler         = $helper->getHandler('Question');
$answerHandler           = $helper->getHandler('Answer');
$answerhistHandler       = $helper->getHandler('Answerhist');
$textblockHandler        = $helper->getHandler('Textblock');
$fieldHandler            = $helper->getHandler('Field');
$permissionsHandler      = $helper->getHandler('Permission');
$taskHandler             = $helper->getHandler('Task');
// 
$myts = MyTextSanitizer::getInstance();
// Default Css Style
$style = \WGEVENTS_URL . '/assets/css/style.css';
// Smarty Default
$sysPathIcon16   = $GLOBALS['xoopsModule']->getInfo('sysicons16');
$sysPathIcon32   = $GLOBALS['xoopsModule']->getInfo('sysicons32');
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
$modPathIcon16   = $GLOBALS['xoopsModule']->getInfo('modicons16');
$modPathIcon32   = $GLOBALS['xoopsModule']->getInfo('modicons16');
// Load Languages
\xoops_loadLanguage('main');
\xoops_loadLanguage('modinfo');
