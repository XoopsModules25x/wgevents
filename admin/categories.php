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
use XoopsModules\Wgevents\Common;

require __DIR__ . '/header.php';
// Get all request values
$op    = Request::getCmd('op', 'list');
$catId = Request::getInt('id');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'list':
    default:
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/jquery-ui.min.js');
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/sortables.js');
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_categories.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_CATEGORY, 'categories.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $categoriesCount = $categoriesHandler->getCountCategories();
        $categoriesAll = $categoriesHandler->getAllCategories($start, $limit);
        $GLOBALS['xoopsTpl']->assign('categories_count', $categoriesCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);
        // Table view categories
        if ($categoriesCount > 0) {
            foreach (\array_keys($categoriesAll) as $i) {
                $category = $categoriesAll[$i]->getValuesCategories();
                $GLOBALS['xoopsTpl']->append('categories_list', $category);
                unset($category);
            }
            // Display Navigation
            if ($categoriesCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($categoriesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_CATEGORIES);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_categories.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_CATEGORIES, 'categories.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $categoriesObj = $categoriesHandler->create();
        $form = $categoriesObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'clone':
        $templateMain = 'wgevents_admin_categories.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_CATEGORIES, 'categories.php', 'list');
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_CATEGORY, 'categories.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Request source
        $catIdSource = Request::getInt('id_source');
        // Get Form
        $categoriesObjSource = $categoriesHandler->get($catIdSource);
        $categoriesObj = $categoriesObjSource->xoopsClone();
        $form = $categoriesObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('categories.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($catId > 0) {
            $categoriesObj = $categoriesHandler->get($catId);
        } else {
            $categoriesObj = $categoriesHandler->create();
        }
        // Set Vars
        $uploaderErrors = '';
        $categoriesObj->setVar('pid', Request::getInt('pid'));
        $categoriesObj->setVar('name', Request::getString('name'));
        $categoriesObj->setVar('desc', Request::getText('desc'));
        // Set Var cat_logo
        require_once \XOOPS_ROOT_PATH . '/class/uploader.php';
        $filename       = $_FILES['logo']['name'];
        $imgMimetype    = $_FILES['logo']['type'];
        $imgNameDef     = Request::getString('name');

        $uploader = new \XoopsMediaUploader(\WGEVENTS_UPLOAD_CATLOGOS_PATH . '/',
                                                    $helper->getConfig('mimetypes_image'), 
                                                    $helper->getConfig('maxsize_image'), null, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $extension = \preg_replace('/^.+\.([^.]+)$/sU', '', $filename);
            $imgName = \str_replace(' ', '', $imgNameDef) . '.' . $extension;
            $uploader->setPrefix($imgName);
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if ($uploader->upload()) {
                $savedFilename = $uploader->getSavedFileName();
                $maxwidth  = (int)$helper->getConfig('maxwidth_image');
                $maxheight = (int)$helper->getConfig('maxheight_image');
                if ($maxwidth > 0 && $maxheight > 0) {
                    // Resize image
                    $imgHandler                = new Wgevents\Common\Resizer();
                    $imgHandler->sourceFile    = \WGEVENTS_UPLOAD_CATLOGOS_PATH . '/' . $savedFilename;
                    $imgHandler->endFile       = \WGEVENTS_UPLOAD_CATLOGOS_PATH . '/' . $savedFilename;
                    $imgHandler->imageMimetype = $imgMimetype;
                    $imgHandler->maxWidth      = $maxwidth;
                    $imgHandler->maxHeight     = $maxheight;
                    $result                    = $imgHandler->resizeImage();
                }
                $categoriesObj->setVar('logo', $savedFilename);
            } else {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
        } else {
            if ($filename > '') {
                $uploaderErrors .= '<br>' . $uploader->getErrors();
            }
            $categoriesObj->setVar('logo', Request::getString('logo'));
        }
        $categoriesObj->setVar('color', Request::getString('color'));
        $categoriesObj->setVar('bordercolor', Request::getString('bordercolor'));
        $categoriesObj->setVar('bgcolor', Request::getString('bgcolor'));
        $categoriesObj->setVar('othercss', Request::getString('othercss'));
        $categoriesObj->setVar('status', Request::getInt('status'));
        $categoriesObj->setVar('weight', Request::getInt('weight'));
        $categoryDatecreatedObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datecreated'));
        $categoriesObj->setVar('datecreated', $categoryDatecreatedObj->getTimestamp());
        $categoriesObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($categoriesHandler->insert($categoriesObj)) {
            $newCatId = $categoriesObj->getNewInsertedIdCategories();
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
                \redirect_header('categories.php?op=edit&id=' . $catId, 5, $uploaderErrors);
            } else {
                \redirect_header('categories.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
            }
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $categoriesObj->getHtmlErrors());
        $form = $categoriesObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_categories.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_CATEGORY, 'categories.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_CATEGORIES, 'categories.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $categoriesObj = $categoriesHandler->get($catId);
        $categoriesObj->start = $start;
        $categoriesObj->limit = $limit;
        $form = $categoriesObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_categories.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
        $categoriesObj = $categoriesHandler->get($catId);
        $catName = $categoriesObj->getVar('name');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('categories.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($categoriesHandler->delete($categoriesObj)) {
                \redirect_header('categories.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $categoriesObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $catId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $categoriesObj->getVar('name')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'order':
        $order = $_POST['order'];
        for ($i = 0, $iMax = \count($order); $i < $iMax; $i++) {
            $categoriesObj = $categoriesHandler->get($order[$i]);
            $categoriesObj->setVar('weight', $i + 1);
            $categoriesHandler->insert($categoriesObj);
        }
        break;
}
require __DIR__ . '/footer.php';
