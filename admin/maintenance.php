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
$formGdpr->addElement(new \XoopsFormTextDateSelect(\_AM_WGEVENTS_MAINTENANCE_ANON_DATA_DATELIMIT, 'datelimit', '', $regDatelimit));
$formGdpr->addElement(new \XoopsFormButton('', 'submit', \_MA_WGEVENTS_EXEC, 'submit'));
$formGdpr->addElement(new \XoopsFormHidden('op', 'anon_data_exec'));
$GLOBALS['xoopsTpl']->assign('formGdpr', $formGdpr->render());

//$maintainance_dui_desc = \str_replace('%p', \WGEVENTS_UPLOAD_IMAGE_PATH, \_AM_WGEVENTS_MAINTENANCE_DELETE_UNUSED_DESC);


switch ($op) {
    case 'invalid_regs_exec':
        $errors = [];
        $templateMain = 'wgevents_admin_maintenance.tpl';
        $err_text     = '';

        $sql = 'DELETE ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '.* ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . ' LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . ' ON ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '.evid = ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . '.id ';
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . '.id) Is Null))';
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
        $GLOBALS['xoopsTpl']->assign('invalid_regs_show', true);
        $GLOBALS['xoopsTpl']->assign('show_result', true);
        break;
    case 'invalid_adds_exec':
        $errors = [];
        $templateMain = 'wgevents_admin_maintenance.tpl';
        $err_text     = '';

        $sql = 'DELETE ' . $GLOBALS['xoopsDB']->prefix('wgevents_question') . '.* ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_question') . ' LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . ' ON ' . $GLOBALS['xoopsDB']->prefix('wgevents_question') . '.evid = ' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . '.id ';
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_event') . '.id) Is Null))';
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

        $sql = 'DELETE ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer') . '.* ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer') . ' LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('wgevents_question') . ' ON ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer') . '.queid = ' . $GLOBALS['xoopsDB']->prefix('wgevents_question') . '.id ';
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_question') . '.id) Is Null));';
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            $errors[] = $GLOBALS['xoopsDB']->error();
        }
        if (\count($errors) > 0) {
            foreach ($errors as $error) {
                $err_text .= '<br>' . $error;
            }
        }
        $sql = 'DELETE ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer_hist') . '.* ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer_hist') . ' LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('wgevents_question') . ' ON ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer_hist') . '.queid = ' . $GLOBALS['xoopsDB']->prefix('wgevents_question') . '.id ';
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_question') . '.id) Is Null));';
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            $errors[] = $GLOBALS['xoopsDB']->error();
        }
        if (\count($errors) > 0) {
            foreach ($errors as $error) {
                $err_text .= '<br>' . $error;
            }
        }
        $sql = 'DELETE ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer') . '.* ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer') . ' LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . ' ON ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer') . '.regid = ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '.id ';
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '.id) Is Null));';
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            $errors[] = $GLOBALS['xoopsDB']->error();
        }
        if (\count($errors) > 0) {
            foreach ($errors as $error) {
                $err_text .= '<br>' . $error;
            }
        }
        $sql = 'DELETE ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer_hist') . '.* ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer_hist') . ' LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . ' ON ' . $GLOBALS['xoopsDB']->prefix('wgevents_answer_hist') . '.regid = ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '.id ';
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '.id) Is Null));';
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
        $dateLimitObj = \DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('datelimit'));

        $crRegistration = new \CriteriaCompo();
        $crRegistration->add(new \Criteria('datecreated', $dateLimitObj->getTimestamp(), '<='));
        $numberReg = $registrationHandler->getCount($crRegistration);
        if ($numberReg > 0) {
            $registrationsAll = $registrationHandler->getAll($crRegistration);
            foreach (\array_keys($registrationsAll) as $i) {
                $regUpdateObj = $registrationHandler->get($i);
                $regUpdateObj->setVar('salutation', 0);
                $regUpdateObj->setVar('firstname', '*****');
                $regUpdateObj->setVar('lastname', '*****');
                $regUpdateObj->setVar('email', '*@*.*');
                $regUpdateObj->setVar('ip', '*.*.*.*');
                if($registrationHandler->insert($regUpdateObj, true)) {
                    $crAnswer = new \CriteriaCompo();
                    $crAnswer->add(new \Criteria('regid', $i));
                    $answerHandler->deleteAll($crAnswer,true);
                    $answerhistHandler->deleteAll($crAnswer,true);
                }
                unset($regUpdateObj, $crAnswer);
            }
            $registrationhistHandler->deleteAll($crRegistration,true);
        }


        /*
        $sql = 'UPDATE `' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . '` ';
        $sql .= "SET `salutation` = 0, `firstname` = '*****', `lastname` = '*****', `email` = '*@*.*', `ip` = '*.*.*.*' ";
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_registration') . ".datecreated)<='" . $dateLimitObj->getTimestamp() . "'))";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            $errors[] = $GLOBALS['xoopsDB']->error();
        }

        $sql = 'DELETE ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration_hist') . '.* ';
        $sql .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('wgevents_registration_hist') . ' ';
        $sql .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('wgevents_registration_hist') . ".hist_datecreated)<='" . $dateLimitObj->getTimestamp() . "'))";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            $errors[] = $GLOBALS['xoopsDB']->error();
        }
        if (\count($errors) > 0) {
            foreach ($errors as $error) {
                $err_text .= '<br>' . $error;
            }
        }*/
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
        $GLOBALS['xoopsTpl']->assign('invalid_regs_show', true);
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
                            $imageCount = $imagesHandler->getCount($crImages);
                            $crAlbums    = new \CriteriaCompo();
                            $crAlbums->add(new \Criteria('alb_image', $entry));
                            $imageCount += $albumsHandler->getCount($crAlbums);
                            if (0 == $imageCount) {
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
    $files      = \scandir($path, SCANDIR_SORT_NONE);

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
