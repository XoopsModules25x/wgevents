<?php
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
 * @copyright      module for xoops
 * @license        GPL 2.0 or later
 * @package        wgevents
 * @since          1.0
 * @min_xoops      2.5.11
 * @author         Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 */

use Xmf\Request;
use XoopsModules\Wgevents;
use XoopsModules\Wgevents\{
    Constants,
    Forms
};

require __DIR__ . '/header.php';

$op = Request::getString('op', 'list');

$GLOBALS['xoopsTpl']->assign('wgevents_icon_url_16', \WGEVENTS_ICONS_URL . '16/');

\xoops_load('XoopsFormLoader');
// create form for data anonymization
$formGdpr = new Forms\FormInline('', 'form', '', 'post', true);
$formGdpr->setExtra('enctype="multipart/form-data"');
// suggest 6 months before now
$regDatelimit = strtotime(date("Y-m-t", \time() - (6 * 30 * 24 * 60 * 60 + 5)));
$formGdpr->addElement(new \XoopsFormTextDateSelect(\_AM_WGEVENTS_MAINTENANCE_ANON_DATA_DATELIMIT, 'reg_datelimit', '', $regDatelimit));
$formGdpr->addElement(new \XoopsFormButton('', 'submit', \_MA_WGEVENTS_EXEC, 'submit'));
$formGdpr->addElement(new \XoopsFormHidden('op', 'anon_data_exec'));
$GLOBALS['xoopsTpl']->assign('formGdpr', $formGdpr->render());

//$maintainance_dui_desc = \str_replace('%p', \WGEVENTS_UPLOAD_IMAGE_PATH, \_AM_WGEVENTS_MAINTENANCE_DELETE_UNUSED_DESC);


switch ($op) {

    case 'invalid_adds_exec':
        $errors = [];
        $templateMain = 'wgevents_admin_maintenance.tpl';
        $err_text     = '';

        $sql = 'DELETE ' . $GLOBALS['xoopsDB']->prefix('wgevents_questions') . '.* ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_questions') . ' LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('wgevents_events') . ' ON ' . $GLOBALS['xoopsDB']->prefix('wgevents_questions') . '.que_evid = ' . $GLOBALS['xoopsDB']->prefix('wgevents_events') . '.ev_id ';
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_events') . '.ev_id) Is Null))';
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            $errors[] = $GLOBALS['xoopsDB']->error();
        }
        if (\count($errors) > 0) {
            foreach ($errors as $error) {
                $err_text .= '<br>' . $error;
            }
        }
        $GLOBALS['xoopsTpl']->assign('result_success', \_AM_WGEVENTS_MAINTENANCE_CHECKTABLE_SUCCESS);
        $GLOBALS['xoopsTpl']->assign('result_error', $err_text);
        $GLOBALS['xoopsTpl']->assign('invalid_adds_show', true);
        $GLOBALS['xoopsTpl']->assign('show_result', true);
        break;
    case 'invalid_answers_exec':
        $errors = [];
        $templateMain = 'wgevents_admin_maintenance.tpl';
        $err_text     = '';

        $sql = 'DELETE ' . $GLOBALS['xoopsDB']->prefix('wgevents_answers') . '.* ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_answers') . ' LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('wgevents_questions') . ' ON ' . $GLOBALS['xoopsDB']->prefix('wgevents_answers') . '.ans_queid = ' . $GLOBALS['xoopsDB']->prefix('wgevents_questions') . '.que_id ';
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_questions') . '.que_id) Is Null));';
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            $errors[] = $GLOBALS['xoopsDB']->error();
        }
        if (\count($errors) > 0) {
            foreach ($errors as $error) {
                $err_text .= '<br>' . $error;
            }
        }
        $GLOBALS['xoopsTpl']->assign('result_success', \_AM_WGEVENTS_MAINTENANCE_CHECKTABLE_SUCCESS);
        $GLOBALS['xoopsTpl']->assign('result_error', $err_text);
        $GLOBALS['xoopsTpl']->assign('invalid_adds_show', true);
        $GLOBALS['xoopsTpl']->assign('show_result', true);
        break;
    case 'anon_data_exec':
        $errors = [];
        $templateMain = 'wgevents_admin_maintenance.tpl';
        $err_text     = '';

        $dateLimitObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('reg_datelimit'));
        $dateLimit = date('Y-m-d', $dateLimitObj->getTimestamp());
        $sql = 'DELETE ' . $GLOBALS['xoopsDB']->prefix('wgevents_registrations_hist') . '.* ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_registrations_hist') . ' WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_registrations_hist') . ".hist_datecreated)<='" . $dateLimit . "'))";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            $errors[] = $GLOBALS['xoopsDB']->error();
        }
        if (\count($errors) > 0) {
            foreach ($errors as $error) {
                $err_text .= '<br>' . $error;
            }
        }
        $GLOBALS['xoopsTpl']->assign('result_success', \_AM_WGEVENTS_MAINTENANCE_ANON_DATA_SUCCESS);
        $GLOBALS['xoopsTpl']->assign('result_error', $err_text);
        $GLOBALS['xoopsTpl']->assign('anon_data_show', true);
        $GLOBALS['xoopsTpl']->assign('show_result', true);
        break;
    case 'list':
    default:
        $templateMain = 'wgevents_admin_maintenance.tpl';

        $GLOBALS['xoopsTpl']->assign('invalid_adds_show', true);
        $GLOBALS['xoopsTpl']->assign('invalid_answers_show', true);
        $GLOBALS['xoopsTpl']->assign('anon_data_show', true);

        break;
}

/**
 * @param $val
 * @return float|int
 */
function returnCleanBytes($val)
{
    switch (mb_substr($val, -1)) {
        case 'K':
        case 'k':
            return (int)$val * 1024;
        case 'M':
        case 'm':
            return (int)$val * 1048576;
        case 'G':
        case 'g':
            return (int)$val * 1073741824;
        default:
            return $val;
    }
}

/**
 * get unused images of given directory
 * @param  $unused
 * @param  $directory
 * @return bool
 */
function getUnusedImages(&$unused, $directory)
{
    // Get instance of module
    $helper        = \XoopsModules\Wgevents\Helper::getInstance();
    $imagesHandler = $helper->getHandler('Images');
    $albumsHandler = $helper->getHandler('Albums');

    if (\is_dir($directory)) {
        $handle = \opendir($directory);
        if ($handle) {
            while (false !== ($entry = \readdir($handle))) {
                switch ($entry) {
                    case 'blank.gif':
                    case 'index.html':
                    case 'noimage.png':
                    case '..':
                    case '.':
                        break;
                    case 'default':
                    default:
                        if (\WGEVENTS_UPLOAD_IMAGE_PATH . '/temp' === $directory) {
                            $unused[] = ['name' => $entry, 'path' => $directory . '/' . $entry];
                        } else {
                            $crImages = new \CriteriaCompo();
                            $crImages->add(new \Criteria('img_name', $entry));
                            $crImages->add(new \Criteria('img_namelarge', $entry), 'OR');
                            $crImages->add(new \Criteria('img_nameorig', $entry), 'OR');
                            $imagesCount = $imagesHandler->getCount($crImages);
                            $crAlbums    = new \CriteriaCompo();
                            $crAlbums->add(new \Criteria('alb_image', $entry));
                            $imagesCount += $albumsHandler->getCount($crAlbums);
                            if (0 == $imagesCount) {
                                $unused[] = ['name' => $entry, 'path' => $directory . '/' . $entry];
                            }
                            unset($crImages);
                            unset($crAlbums);
                        }
                        break;
                }
            }
            \closedir($handle);
        } else {
            return false;
        }
    } else {
        return false;
    }

    return true;
}

/**
 * get size of given directory
 * @param  $path
 * @return int
 */
function wgg_foldersize($path)
{
    $total_size = 0;
    $files      = \scandir($path);

    foreach ($files as $t) {
        if (\is_dir(\rtrim($path, '/') . '/' . $t)) {
            if ('.' != $t && '..' != $t) {
                $size = wgg_foldersize(\rtrim($path, '/') . '/' . $t);

                $total_size += $size;
            }
        } else {
            $size       = filesize(\rtrim($path, '/') . '/' . $t);
            $total_size += $size;
        }
    }

    return $total_size;
}

/**
 * format size
 * @param  $size
 * @return string
 */
function wgg_format_size($size)
{
    $mod   = 1024;
    $units = \explode(' ', 'B KB MB GB TB PB');
    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    return \round($size, 2) . ' ' . $units[$i];
}

require __DIR__ . '/footer.php';
