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

use XoopsModules\Wgevents\Common;

require_once \dirname(__DIR__) . '/preloads/autoloader.php';
require __DIR__ . '/header.php';

// Template Index
$templateMain = 'wgevents_admin_index.tpl';

// Count elements
$countEvents = $eventHandler->getCount();
$countQuestions = $questionHandler->getCount();
$countAnswers = $answerHandler->getCount();
$countRegistrations = $registrationHandler->getCount();
$countCategories = $categoryHandler->getCount();
$countFields = $fieldHandler->getCount();
$countTextblocks = $textblockHandler->getCount();
$countLogs = $logHandler->getCount();

// InfoBox Statistics
$adminObject->addInfoBox(\_AM_WGEVENTS_STATISTICS);
// Info elements
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_THEREARE_EVENTS . '</label>', $countEvents));
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_THEREARE_QUESTIONS . '</label>', $countQuestions));
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_THEREARE_ANSWERS . '</label>', $countAnswers));
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_THEREARE_REGISTRATIONS . '</label>', $countRegistrations));
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_THEREARE_CATEGORIES . '</label>', $countCategories));
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_THEREARE_FIELDS . '</label>', $countFields));
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_THEREARE_TEXTBLOCKS . '</label>', $countTextblocks));
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_THEREARE_LOGS . '</label>', $countLogs));

$timezones = XoopsLists::getTimeZoneList();
$adminObject->addInfoBox(\_AM_WGEVENTS_TIMEZONES);
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_TIMEZONE_PHP . '</label>', date_default_timezone_get()));
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_TIMEZONE_SERVER . '</label>', $timezones[$GLOBALS['xoopsConfig']['server_TZ']]));
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_TIMEZONE_DEFAULT . '</label>', $timezones[$GLOBALS['xoopsConfig']['default_TZ']]));
$adminObject->addInfoBoxLine(\sprintf( '<label>' . \_AM_WGEVENTS_TIMEZONE_USER . '</label>', $GLOBALS['xoopsUser']->uname(), $timezones[(float)$GLOBALS['xoopsUser']->getVar('timezone_offset')]));

// Upload Folders
$configurator = new Common\Configurator();
if ($configurator->uploadFolders && \is_array($configurator->uploadFolders)) {
    foreach (\array_keys($configurator->uploadFolders) as $i) {
        $folder[] = $configurator->uploadFolders[$i];
    }
}
// Uploads Folders Created
foreach (\array_keys($folder) as $i) {
    $adminObject->addConfigBoxLine($folder[$i], 'folder');
    $adminObject->addConfigBoxLine([$folder[$i], '777'], 'chmod');
}

// Render Index
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('index.php'));
// Test Data
if ($helper->getConfig('displaySampleButton')) {
    \xoops_loadLanguage('admin/modulesadmin', 'system');
    require_once \dirname(__DIR__) . '/testdata/index.php';
    $adminObject->addItemButton(\constant('CO_' . $moduleDirNameUpper . '_ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load');
    $adminObject->addItemButton(\constant('CO_' . $moduleDirNameUpper . '_SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save');
//    $adminObject->addItemButton(\constant('CO_' . $moduleDirNameUpper . '_EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');
    $adminObject->displayButton('left');
}
$GLOBALS['xoopsTpl']->assign('index', $adminObject->displayIndex());
// End Test Data
require __DIR__ . '/footer.php';
