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
use XoopsModules\Wgevents\Common;

require __DIR__ . '/header.php';
// Get all request values
$op    = Request::getCmd('op', 'list');
$catId = Request::getInt('id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

$moduleDirName = \basename(\dirname(__DIR__));

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

switch ($op) {
    case 'list':
    default:
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/jquery-ui.min.js');
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/sortables.js');
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_category.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('category.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_CATEGORY, 'category.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $categoryCount = $categoryHandler->getCountCategories();
        $categoryAll = $categoryHandler->getAllCategories();
        $GLOBALS['xoopsTpl']->assign('categorieCount', $categoryCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);
        // Table view categories
        if ($categoryCount > 0) {
            foreach (\array_keys($categoryAll) as $i) {
                $category = $categoryAll[$i]->getValuesCategories();
                $GLOBALS['xoopsTpl']->append('categories_list', $category);
                unset($category);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_CATEGORIES);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_category.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('category.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_CATEGORIES, 'category.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $categoryObj = $categoryHandler->create();
        $form = $categoryObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_category.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('category.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_CATEGORIES, 'category.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_CATEGORY, 'category.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $catIdSource = Request::getInt('id_source');
        // Get Form
        $categoryObjSource = $categoryHandler->get($catIdSource);
        $categoryObj = $categoryObjSource->xoopsClone();
        $form = $categoryObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('category.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($catId > 0) {
            $categoryObj = $categoryHandler->get($catId);
        } else {
            $categoryObj = $categoryHandler->create();
        }
        // Set Vars
        $uploaderErrors = '';
        $categoryObj->setVar('pid', Request::getInt('pid'));
        $categoryObj->setVar('name', Request::getString('name'));
        $categoryObj->setVar('desc', Request::getText('desc'));
        // Set Var cat_logo
        require_once \XOOPS_ROOT_PATH . '/class/uploader.php';
        $logoname       = $_FILES['logo']['name'];
        $logoMimetype    = $_FILES['logo']['type'];
        $logoNameDef     = Request::getString('name');
        $uploader = new \XoopsMediaUploader(\WGEVENTS_UPLOAD_CATLOGOS_PATH . '/',
                                                    $helper->getConfig('mimetypes_image'), 
                                                    $helper->getConfig('maxsize_image'), null, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $extension = \preg_replace('/^.+\.([^.]+)$/sU', '', $logoname);
            $imgName = \str_replace(' ', '', $logoNameDef) . '.' . $extension;
            $uploader->setPrefix($imgName);
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if ($uploader->upload()) {
                $savedLogoname = $uploader->getSavedFileName();
                $maxwidth  = (int)$helper->getConfig('maxwidth_image');
                $maxheight = (int)$helper->getConfig('maxheight_image');
                if ($maxwidth > 0 && $maxheight > 0) {
                    // Resize image
                    $imgHandler                = new Wgevents\Common\Resizer();
                    $imgHandler->sourceFile    = \WGEVENTS_UPLOAD_CATLOGOS_PATH . '/' . $savedLogoname;
                    $imgHandler->endFile       = \WGEVENTS_UPLOAD_CATLOGOS_PATH . '/' . $savedLogoname;
                    $imgHandler->imageMimetype = $logoMimetype;
                    $imgHandler->maxWidth      = $maxwidth;
                    $imgHandler->maxHeight     = $maxheight;
                    $result                    = $imgHandler->resizeImage();
                }
                $categoryObj->setVar('logo', $savedLogoname);
            } else {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
        } else {
            if ($logoname > '') {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
            $categoryObj->setVar('logo', Request::getString('logo'));
        }
        // Set Var cat_image
        $filename       = $_FILES['image']['name'];
        $imgMimetype    = $_FILES['image']['type'];
        $imgNameDef     = Request::getString('name');
        $uploader = new \XoopsMediaUploader(\WGEVENTS_UPLOAD_CATIMAGES_PATH . '/',
            $helper->getConfig('mimetypes_image'),
            $helper->getConfig('maxsize_image'), null, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][1])) {
            $extension = \preg_replace('/^.+\.([^.]+)$/sU', '', $filename);
            $imgName = \str_replace(' ', '', $imgNameDef) . '.' . $extension;
            $uploader->setPrefix($imgName);
            $uploader->fetchMedia($_POST['xoops_upload_file'][1]);
            if ($uploader->upload()) {
                $savedImagename = $uploader->getSavedFileName();
                $maxwidth  = (int)$helper->getConfig('maxwidth_image');
                $maxheight = (int)$helper->getConfig('maxheight_image');
                if ($maxwidth > 0 && $maxheight > 0) {
                    // Resize image
                    $imgHandler                = new Wgevents\Common\Resizer();
                    $imgHandler->sourceFile    = \WGEVENTS_UPLOAD_CATIMAGES_PATH . '/' . $savedImagename;
                    $imgHandler->endFile       = \WGEVENTS_UPLOAD_CATIMAGES_PATH . '/' . $savedImagename;
                    $imgHandler->imageMimetype = $imgMimetype;
                    $imgHandler->maxWidth      = $maxwidth;
                    $imgHandler->maxHeight     = $maxheight;
                    $result                    = $imgHandler->resizeImage();
                }
                $categoryObj->setVar('image', $savedImagename);
            } else {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
        } else {
            if ($filename > '') {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
            $categoryObj->setVar('image', Request::getString('image'));
        }
        $categoryObj->setVar('color', Request::getString('color'));
        $categoryObj->setVar('bordercolor', Request::getString('bordercolor'));
        $categoryObj->setVar('bgcolor', Request::getString('bgcolor'));
        $categoryObj->setVar('othercss', Request::getString('othercss'));
        $categoryObj->setVar('identifier', Request::getString('identifier'));
        $categoryObj->setVar('type', Request::getInt('type'));
        $categoryObj->setVar('status', Request::getInt('status'));
        $categoryObj->setVar('weight', Request::getInt('weight'));
        $categoryDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $categoryObj->setVar('datecreated', $categoryDatecreatedObj->getTimestamp());
        $categoryObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($categoryHandler->insert($categoryObj)) {
            $newCatId = $categoryObj->getNewInsertedIdCategories();
            $permId = isset($_REQUEST['id']) ? $catId : $newCatId;
            $grouppermHandler = \xoops_getHandler('groupperm');
            $mid = $GLOBALS['xoopsModule']->getVar('mid');
            // Permission to view_cat_events
            $grouppermHandler->deleteByModule($mid, 'wgevents_view_cat_events', $permId);
            if (isset($_POST['groups_view_cat_events'])) {
                foreach ($_POST['groups_view_cat_events'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_view_cat_events', $permId, $onegroupId, $mid);
                }
            }
            // Permission to submit_cat_events
            $grouppermHandler->deleteByModule($mid, 'wgevents_submit_cat_events', $permId);
            if (isset($_POST['groups_submit_cat_events'])) {
                foreach ($_POST['groups_submit_cat_events'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_submit_cat_events', $permId, $onegroupId, $mid);
                }
            }
            // Permission to approve_cat_events
            $grouppermHandler->deleteByModule($mid, 'wgevents_approve_cat_events', $permId);
            if (isset($_POST['groups_approve_cat_events'])) {
                foreach ($_POST['groups_approve_cat_events'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_approve_cat_events', $permId, $onegroupId, $mid);
                }
            }
            // Permission to view_cat_regs
            $grouppermHandler->deleteByModule($mid, 'wgevents_view_cat_regs', $permId);
            if (isset($_POST['groups_view_cat_regs'])) {
                foreach ($_POST['groups_view_cat_regs'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_view_cat_regs', $permId, $onegroupId, $mid);
                }
            }
            // Permission to submit_cat_regs
            $grouppermHandler->deleteByModule($mid, 'wgevents_submit_cat_regs', $permId);
            if (isset($_POST['groups_submit_cat_regs'])) {
                foreach ($_POST['groups_submit_cat_regs'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_submit_cat_regs', $permId, $onegroupId, $mid);
                }
            }
            // Permission to approve_cat_regs
            $grouppermHandler->deleteByModule($mid, 'wgevents_approve_cat_regs', $permId);
            if (isset($_POST['groups_approve_cat_regs'])) {
                foreach ($_POST['groups_approve_cat_regs'] as $onegroupId) {
                    $grouppermHandler->addRight('wgevents_approve_cat_regs', $permId, $onegroupId, $mid);
                }
            }
            if ('' !== $uploaderErrors) {
                \redirect_header('category.php?op=edit&id=' . $catId, 5, $uploaderErrors);
            } else {
                \redirect_header('category.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
            }
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $categoryObj->getHtmlErrors());
        $form = $categoryObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_category.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('category.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_CATEGORY, 'category.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_CATEGORIES, 'category.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $categoryObj = $categoryHandler->get($catId);
        $categoryObj->start = $start;
        $categoryObj->limit = $limit;
        $form = $categoryObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_category.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('category.php'));
        $categoryObj = $categoryHandler->get($catId);
        $catName = $categoryObj->getVar('name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('category.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($categoryHandler->delete($categoryObj)) {
                \redirect_header('category.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $categoryObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $catId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $categoryObj->getVar('name')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'order':
        $order = $_POST['order'];
        for ($i = 0, $iMax = \count($order); $i < $iMax; $i++) {
            $categoryObj = $categoryHandler->get($order[$i]);
            $categoryObj->setVar('weight', $i + 1);
            $categoryHandler->insert($categoryObj);
        }
        break;
}
require __DIR__ . '/footer.php';
