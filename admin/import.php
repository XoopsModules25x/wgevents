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
use XoopsModules\Wgevents\{
    Constants,
    Utility,
    Common\Resizer
};

require __DIR__ . '/header.php';
$templateMain = 'wgevents_admin_import.tpl';
$moduleDirName = \basename(\dirname(__DIR__));

$adminObject = \Xmf\Module\Admin::getInstance();

// It recovered the value of argument op in URL$
$op   = Request::getString('op', 'list');
$cats = Request::getBool('cats');
if (Request::getString('datefrom')) {
    $dateFromObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datefrom'));
    $dateFrom    = $dateFromObj->getTimestamp();
    $dateToObj   = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('dateto'));
    $dateTo      = $dateToObj->getTimestamp();
}
$uidCurrent = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;

$GLOBALS['xoopsTpl']->assign('wgevents_icons_url_32', \WGEVENTS_ICONS_URL_32);

$modulesList = [];

switch ($op) {
    case 'list':
    default:
        $module_handler = xoops_getHandler('module');
        $installed_mods = $module_handler->getObjects();
        $modsArray = [];
        foreach ($installed_mods as $module) {
            $modsArray[] = $module->getInfo('dirname');
            unset($module);
        }
        $modulesList[] = ['name' => \_AM_WGEVENTS_IMPORT_APCAL, 'op' => 'apcal', 'installed' => in_array('apcal', $modsArray)];
        $modulesList[] = ['name' => \_AM_WGEVENTS_IMPORT_EXTCAL, 'op' => 'extcal', 'installed' => in_array('extcal', $modsArray)];
        //TODO:
        //$modulesList[] = ['name' => \_AM_WGEVENTS_IMPORT_EGUIDE, 'op' => 'eguide', 'installed' => in_array('eguide', $modsArray)]];
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('import.php'));
        $GLOBALS['xoopsTpl']->assign('modules_list', $modulesList);
        break;
    case 'apcal':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('import.php'));
        // Form Create
        $form = $importHandler->getFormApcal();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'apcal_exec':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('index.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $catsCount    = 0;
        $catsImported = 0;
        if ($cats) {
            // delete all existing data
            $categoryHandler->deleteAll();
            $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('wgevents_category') . ' AUTO_INCREMENT = 1');
            // read categories
            $result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('apcal_cat'));
            while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
                $catsCount++;
                $categoryObj = $categoryHandler->create();
                // Set Vars
                $categoryObj->setVar('id', $row['cid']);
                $categoryObj->setVar('pid', $row['pid']);
                $categoryObj->setVar('name', $row['cat_title']);
                $categoryObj->setVar('desc', $row['cat_desc']);
                $categoryObj->setVar('logo', 'blank.gif');
                $categoryObj->setVar('color', '#000000');
                $categoryObj->setVar('bordercolor', $row['color']);
                $categoryObj->setVar('bgcolor', $row['color']);
                $categoryObj->setVar('othercss', $row['cat_style']);
                $categoryObj->setVar('identifier', 'cat' . $row['cid'] . '_');
                $categoryObj->setVar('status', Constants::STATUS_ONLINE);
                $categoryObj->setVar('weight', $row['weight']);
                $categoryObj->setVar('datecreated', time());
                $categoryObj->setVar('submitter', $uidCurrent);
                // Insert Data
                if ($categoryHandler->insert($categoryObj)) {
                    $catsImported++;
                }
            }
        }
        unset($result);

        $eventsCount    = 0;
        $eventsImported = 0;
        $deleteArr      = [];
        $delSource      = Request::getBool('del_source');
        // delete all existing data
        $eventHandler->deleteAll();
        $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . ' AUTO_INCREMENT = 1');
        // read events
        $result  = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('apcal_event') . ' WHERE start<=' . $dateTo . ' AND end>=' . $dateFrom . ' ORDER BY start ASC');
        while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
            $eventsCount++;
            $eventObj = $eventHandler->create();
            $eventObj->setVar('id', $row['id']);
            $eventObj->setVar('catid', $row['mainCategory']);
            $eventObj->setVar('name', $row['summary']);
            $eventObj->setVar('desc', $row['description']);
            $logo = 'blank.gif';
            $imgTarget = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/events/logos/' . (int)$row['uid'];
            if (!\file_exists($imgTarget)) {
                // create folder if not existing
                Utility::createFolder($imgTarget);
                \chmod($imgTarget, 0777);
                $fileBlank = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/events/logos/blank.gif';
                $dest = $imgTarget . '/blank.gif';
                Utility::copyFile($fileBlank, $dest);
            }
            $resultPics  = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('apcal_pictures') . ' WHERE event_id=' . $row['id'] . ' AND main_pic=1');
            while ($rowPic = $GLOBALS['xoopsDB']->fetchArray($resultPics)) {
                // copy image into wgevents upload folder
                $imgSource = XOOPS_ROOT_PATH . '/uploads/apcal/' . $rowPic['picture'];
                $imgTarget .= '/' . $rowPic['picture'];
                if (!\file_exists($imgTarget)) {
                    \copy($imgSource, $imgTarget);
                    $maxwidth = (int)$helper->getConfig('maxwidth_image');
                    $maxheight = (int)$helper->getConfig('maxheight_image');
                    if ($maxwidth > 0 && $maxheight > 0) {
                        // Resize image
                        $imgHandler = new Resizer();
                        $imgHandler->sourceFile = $imgTarget;
                        $imgHandler->endFile = $imgTarget;
                        $imgHandler->imageMimetype = mime_content_type($imgTarget);
                        $imgHandler->maxWidth = $maxwidth;
                        $imgHandler->maxHeight = $maxheight;
                        $result = $imgHandler->resizeImage();
                    }
                }
                $logo = $rowPic['picture'];
            }
            $eventObj->setVar('logo', $logo);
            $eventObj->setVar('datefrom', $row['start']);
            $eventObj->setVar('dateto', $row['end']);
            $eventObj->setVar('contact', $row['contact']);
            $eventObj->setVar('email', $row['email']);
            $eventObj->setVar('location', $row['location']);
            $eventObj->setVar('url', $row['url']);
            $eventObj->setVar('locgmlat', $row['gmlat']);
            $eventObj->setVar('locgmlon', $row['gmlong']);
            $eventObj->setVar('locgmzoom', $row['gmzoom']);
            $eventObj->setVar('fee', 0);
            $eventObj->setVar('paymentinfo', '');
            $eventObj->setVar('register_use', 0);
            $eventObj->setVar('register_to', 0);
            $eventObj->setVar('register_max', 0);
            $eventObj->setVar('register_listwait', 0);
            $eventObj->setVar('register_autoaccept', 0);
            $eventObj->setVar('register_notify', '');
            $eventObj->setVar('register_sendermail', '');
            $eventObj->setVar('register_sendername', '');
            $eventObj->setVar('register_signature', '');
            $eventObj->setVar('register_forceverif', 0);
            $eventObj->setVar('status', Constants::STATUS_APPROVED);
            $eventObj->setVar('galid', 0);
            $eventObj->setVar('groups', '0');
            $eventObj->setVar('identifier', 'cat' . $row['mainCategory'] . '_' . (int)$row['id']);
            $eventObj->setVar('datecreated', time());
            $eventObj->setVar('submitter', (int)$row['uid']);
            // Insert Data
            if ($eventHandler->insert($eventObj)) {
                if ($delSource) {
                    $deleteArr[] = (int)$row['id'];
                }
                $eventsImported++;
            }
        }
        if ($delSource && \count($deleteArr) > 0) {
            foreach ($deleteArr as $idSource) {
                $GLOBALS['xoopsDB']->queryF('DELETE FROM `' . $GLOBALS['xoopsDB']->prefix('apcal_event') . '` WHERE `' . $GLOBALS['xoopsDB']->prefix('apcal_event') . '`.`id` = ' . $idSource);
            }
        }
        $modulesList[] = ['name' => \_AM_WGEVENTS_IMPORT_APCAL, 'catsResult' => \sprintf(_AM_WGEVENTS_IMPORT_RESULT_OF, $catsImported, $catsCount), 'eventsResult'=> \sprintf(_AM_WGEVENTS_IMPORT_RESULT_OF, $eventsImported, $eventsCount)];
        $GLOBALS['xoopsTpl']->assign('modules_list', $modulesList);
        $GLOBALS['xoopsTpl']->assign('showData', true);
        break;
    case 'extcal':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('import.php'));
        // Form Create
        $form = $importHandler->getFormExtcal();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'extcal_exec':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('index.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $catsCount    = 0;
        $catsImported = 0;
        if ($cats) {
            // delete all existing data
            $categoryHandler->deleteAll();
            $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('wgevents_category') . ' AUTO_INCREMENT = 1');
            // read categories
            $result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('extcal_cat'));
            while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
                $catsCount++;
                $categoryObj = $categoryHandler->create();
                // Set Vars
                $categoryObj->setVar('id', $row['cat_id']);
                $categoryObj->setVar('pid', 0);
                $categoryObj->setVar('name', $row['cat_name']);
                $categoryObj->setVar('desc', $row['cat_desc']);
                $logo = 'blank.gif';
                $imgSource = XOOPS_ROOT_PATH . '/modules/extcal/assets/css/images/' . $row['cat_icone'];
                if (\is_file($imgSource)) {
                    // copy image into wgevents upload folder, if not existing
                    $imgTarget = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/categories/logos/' . $row['cat_icone'];
                    if (!\file_exists($imgTarget)) {
                        \copy($imgSource, $imgTarget);
                        $maxwidth = (int)$helper->getConfig('maxwidth_image');
                        $maxheight = (int)$helper->getConfig('maxheight_image');
                        if ($maxwidth > 0 && $maxheight > 0) {
                            // Resize image
                            $imgHandler = new Resizer();
                            $imgHandler->sourceFile = $imgTarget;
                            $imgHandler->endFile = $imgTarget;
                            $imgHandler->imageMimetype = mime_content_type($imgTarget);
                            $imgHandler->maxWidth = $maxwidth;
                            $imgHandler->maxHeight = $maxheight;
                            $result = $imgHandler->resizeImage();
                        }
                    }
                    $logo = $row['cat_icone'];
                }
                $categoryObj->setVar('logo', $logo);
                $categoryObj->setVar('color', '#000000');
                $categoryObj->setVar('bordercolor', '#' . $row['cat_color']);
                $categoryObj->setVar('bgcolor', '#' . $row['cat_color']);
                $categoryObj->setVar('othercss', '');
                $categoryObj->setVar('identifier', 'cat' . $row['cat_id'] . '_');
                $categoryObj->setVar('status', Constants::STATUS_ONLINE);
                $categoryObj->setVar('weight', $row['cat_weight']);
                $categoryObj->setVar('datecreated', time());
                $categoryObj->setVar('submitter', $uidCurrent);
                // Insert Data
                if ($categoryHandler->insert($categoryObj)) {
                    $catsImported++;
                }
            }
        }
        unset($result);

        $eventsCount    = 0;
        $eventsImported = 0;
        $deleteArr      = [];
        $delSource      = Request::getBool('del_source');
        // delete all existing data
        $eventHandler->deleteAll();
        $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . ' AUTO_INCREMENT = 1');
        // read events
        $result  = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('extcal_event') . ' WHERE event_start<=' . $dateTo . ' AND event_end>=' . $dateFrom . ' ORDER BY event_start ASC');
        while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
            $eventsCount++;
            $eventObj = $eventHandler->create();
            $eventObj->setVar('id', $row['event_id']);
            $eventObj->setVar('catid', $row['cat_id']);
            $eventObj->setVar('name', $row['event_title']);
            $eventObj->setVar('desc', $row['event_desc']);
            $logo = 'blank.gif';
            $imgTarget = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/events/logos/' . (int)$row['event_submitter'];
            if (!\file_exists($imgTarget)) {
                // create folder if not existing
                Utility::createFolder($imgTarget);
                \chmod($imgTarget, 0777);
                $fileBlank = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/events/logos/blank.gif';
                $dest = $imgTarget . '/blank.gif';
                Utility::copyFile($fileBlank, $dest);
            }
            $imgSource = XOOPS_ROOT_PATH . '/modules/extcal/assets/css/images/' . $row['event_icone'];
            if (\is_file($imgSource)) {
                // copy image into wgevents upload folder
                $imgTarget .= '/' . $row['event_icone'];
                if (!\file_exists($imgTarget)) {
                    \copy($imgSource, $imgTarget);
                    $maxwidth = (int)$helper->getConfig('maxwidth_image');
                    $maxheight = (int)$helper->getConfig('maxheight_image');
                    if ($maxwidth > 0 && $maxheight > 0) {
                        // Resize image
                        $imgHandler = new Resizer();
                        $imgHandler->sourceFile = $imgTarget;
                        $imgHandler->endFile = $imgTarget;
                        $imgHandler->imageMimetype = mime_content_type($imgTarget);
                        $imgHandler->maxWidth = $maxwidth;
                        $imgHandler->maxHeight = $maxheight;
                        $result = $imgHandler->resizeImage();
                    }
                }
                $logo = $row['event_icone'];
            }
            $eventObj->setVar('logo', $logo);
            $eventObj->setVar('datefrom', $row['event_start']);
            $eventObj->setVar('dateto', $row['event_end']);
            $eventObj->setVar('contact', $row['event_contact']);
            $eventObj->setVar('email', $row['event_email']);
            //TODO
            $location = '';
            $resultLoc  = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('extcal_location') . ' WHERE id=' . $row['event_location']);
            while ($rowLoc = $GLOBALS['xoopsDB']->fetchArray($resultLoc)) {
                $location .= $rowLoc['nom'] . PHP_EOL;
                if ('' != $rowLoc['adresse']) {
                    $location .= $rowLoc['adresse'] . PHP_EOL;
                }
                if ('' != $rowLoc['adresse2']) {
                    $location .= $rowLoc['adresse2'] . PHP_EOL;
                }
                if ('' != $rowLoc['cp']) {
                    $location .= $rowLoc['cp'] . PHP_EOL;
                }
                if ('' != $rowLoc['ville']) {
                    $location .= $rowLoc['ville'] . PHP_EOL;
                }
                if ('' != $rowLoc['tel_fixe']) {
                    $location .= $rowLoc['tel_fixe'] . PHP_EOL;
                }
                if ('' != $rowLoc['tel_portable']) {
                    $location .= $rowLoc['tel_portable'] . PHP_EOL;
                }
                if ('' != $rowLoc['mail']) {
                    $location .= $rowLoc['mail'] . PHP_EOL;
                }
                if ('' != $rowLoc['site']) {
                    $location .= $rowLoc['site'] . PHP_EOL;
                }
            }
            $eventObj->setVar('location', $location);
            $eventObj->setVar('url', $row['event_url']);
            $eventObj->setVar('locgmlat', 0);
            $eventObj->setVar('locgmlon', 0);
            $eventObj->setVar('locgmzoom', 0);
            $eventObj->setVar('fee', $row['event_price']);
            $eventObj->setVar('paymentinfo', '');
            $eventObj->setVar('register_use', 0);
            $eventObj->setVar('register_to', 0);
            $eventObj->setVar('register_max', 0);
            $eventObj->setVar('register_listwait', 0);
            $eventObj->setVar('register_autoaccept', 0);
            $eventObj->setVar('register_notify', '');
            $eventObj->setVar('register_sendermail', '');
            $eventObj->setVar('register_sendername', '');
            $eventObj->setVar('register_signature', '');
            $eventObj->setVar('register_forceverif', 0);
            $eventObj->setVar('status', Constants::STATUS_APPROVED);
            $eventObj->setVar('galid', 0);
            $eventObj->setVar('groups', '0');
            $eventObj->setVar('identifier', 'cat' . $row['cat_id'] . '_' . (int)$row['event_id']);
            $eventObj->setVar('datecreated', $row['event_submitdate']);
            $eventObj->setVar('submitter', (int)$row['event_submitter']);
            // Insert Data
            if ($eventHandler->insert($eventObj)) {
                if ($delSource) {
                    $deleteArr[] = (int)$row['event_id'];
                }
                $eventsImported++;
            }
        }
        if ($delSource && \count($deleteArr) > 0) {
            foreach ($deleteArr as $idSource) {
                $GLOBALS['xoopsDB']->queryF('DELETE FROM `' . $GLOBALS['xoopsDB']->prefix('extcal_event') . '` WHERE `' . $GLOBALS['xoopsDB']->prefix('extcal_event') . '`.`event_id` = ' . $idSource);
            }
        }
        $modulesList[] = ['name' => \_AM_WGEVENTS_IMPORT_EXTCAL, 'catsResult' => \sprintf(_AM_WGEVENTS_IMPORT_RESULT_OF, $catsImported, $catsCount), 'eventsResult'=> \sprintf(_AM_WGEVENTS_IMPORT_RESULT_OF, $eventsImported, $eventsCount)];
        $GLOBALS['xoopsTpl']->assign('modules_list', $modulesList);
        $GLOBALS['xoopsTpl']->assign('showData', true);
        break;
}
require __DIR__ . '/footer.php';
