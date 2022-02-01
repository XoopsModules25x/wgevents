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
$accId = Request::getInt('id');
$save_and_check = Request::getString('save_and_check', 'none');
$start = Request::getInt('start');
$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

switch ($op) {
    case 'check_account':
        $imgFailed = WGEVENTS_ICONS_URL_16 . '/0.png';
        $imgOK = WGEVENTS_ICONS_URL_16 . '/1.png';
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_account.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('account.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ACCOUNTS, 'account.php?op=list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $GLOBALS['xoopsTpl']->assign('account_check', true);

        if (0 == $accId) {
            redirect_header('account.php', 3, _MA_WGEVENTS_INVALID_PARAM);
        } else {
            $accountObj = $helper->getHandler('Account')->get($accId);
        }

        $account_server_in = $accountObj->getVar('server_in');
        $account_port_in   = $accountObj->getVar('port_in');
        $account_type      = $accountObj->getVar('type');
        $logDetails = 'account_type:' . $account_type;
        switch ($account_type) {
            case Constants::ACCOUNT_TYPE_VAL_POP3:
                $service = 'pop3';
                break;
            case Constants::ACCOUNT_TYPE_VAL_SMTP:
            case Constants::ACCOUNT_TYPE_VAL_GMAIL:
                $service = 'imap';
                break;
            case 'default':
            default:
                $service = '';
                break;
        }
        $account_securetype_in = $accountObj->getVar('securetype_in');
        $account_password      = $accountObj->getVar('password');
        $account_username      = $accountObj->getVar('username');

        $command = $account_server_in . ':' . $account_port_in;
        if ('' != $service) {
            $command .= '/' . $service;
        }
        if ('' != $account_securetype_in) {
            $command .= '/' . $account_securetype_in;
        }
        $logDetails .= '<br>command:' . $command;

        $checks = [];

        $mbox = @imap_open('{' . $command . '}', $account_username, $account_password);
        if (false === $mbox) {
            $checks['openmailbox']['check'] = \_AM_WGEVENTS_ACCOUNT_CHECK_OPEN_MAILBOX;
            $checks['openmailbox']['result'] = \_AM_WGEVENTS_ACCOUNT_CHECK_FAILED;
            $checks['openmailbox']['result_img'] = $imgFailed;
            $checks['openmailbox']['info'] = imap_last_error();
        } else {
            $checks['openmailbox']['check'] = \_AM_WGEVENTS_ACCOUNT_CHECK_OPEN_MAILBOX;
            $checks['openmailbox']['result'] = \_AM_WGEVENTS_ACCOUNT_CHECK_OK;
            $checks['openmailbox']['result_img'] = $imgOK;

            $folders = imap_list($mbox, '{' . $command . '}', '*');
            if (false === $folders) {
                $checks['listfolder']['check'] = \_AM_WGEVENTS_ACCOUNT_CHECK_LIST_FOLDERS;
                $checks['listfolder']['result'] = \_AM_WGEVENTS_ACCOUNT_CHECK_FAILED;
                $checks['listfolder']['result_img'] = $imgFailed;
                $checks['listfolder']['info'] = imap_last_error();
            } else {
                $checks['listfolder']['check'] = \_AM_WGEVENTS_ACCOUNT_CHECK_LIST_FOLDERS;
                $checks['listfolder']['result'] = \_AM_WGEVENTS_ACCOUNT_CHECK_OK;
                $checks['listfolder']['result_img'] = $imgOK;
                $checks['listfolder']['info'] = \implode('<br>', $folders);

                imap_close($mbox);

                // send test mail
                // read data of account
                $account_yourname       = $accountObj->getVar('yourname');
                $account_yourmail       = $accountObj->getVar('yourmail');
                $account_server_out     = $accountObj->getVar('server_out');
                $account_port_out       = $accountObj->getVar('port_out');
                $account_securetype_out = $accountObj->getVar('securetype_out');

                try {
                    if ($account_type == Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL) {
                        $pop = new POP3();
                        $pop->authorise($account_server_out, $account_port_out, 30, $account_username, $account_password, 1);
                    }
                    $xoopsMailer = xoops_getMailer();
                    $logDetails .= '<br>xoopsMailer is_object:' . \is_object($xoopsMailer);

                    //$xoopsMailer->useMail();

                    $xoopsMailer->CharSet = _CHARSET; //use xoops default character set

                    //if (Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL == $account_type) {
                        //$xoopsMailer->IsSendmail();  Fix Error
                    //}

                    $xoopsMailer->Username = $account_username; // SMTP account username
                    $logDetails .= '<br>account_username:' . $account_username;
                    $xoopsMailer->Password = $account_password; // SMTP account password
                    $logDetails .= '<br>account_password:' . $account_password;

                    if (Constants::ACCOUNT_TYPE_VAL_POP3 == $account_type) {
                        //xoopsMailer->isSMTP();
                        //$xoopsMailer->SMTPDebug = 2;
                        $xoopsMailer->Host = $account_server_out;
                    }

                    if (Constants::ACCOUNT_TYPE_VAL_SMTP == $account_type
                        || Constants::ACCOUNT_TYPE_VAL_GMAIL == $account_type) {
                        $xoopsMailer->Port = $account_port_out; // set the SMTP port
                        $logDetails .= '<br>account_port_out:' . $account_port_out;
                        $xoopsMailer->Host = $account_server_out; //sometimes necessary to repeat
                        $logDetails .= '<br>account_server_out:' . $account_server_out;
                    }

                    if ('' != $account_securetype_out) {
                        $xoopsMailer->SMTPAuth   = true;
                        $xoopsMailer->SMTPSecure = $account_securetype_out; // sets the prefix to the server
                        $logDetails .= '<br>account_securetype_out:' . $account_securetype_out;
                    }
                    $xoopsMailer->setFromEmail($account_yourmail);
                    $xoopsMailer->setFromName($account_yourname);
                    $logDetails .= '<br>from:' . $account_yourmail . ' ' . $account_yourname;
                    $xoopsMailer->Subject = 'Test account subject';
                    $xoopsMailer->setBody('Test account body: ' . \XOOPS_URL);
                    $usermail = $GLOBALS['xoopsUser']->email();
                    $xoopsMailer->setToEmails($usermail);
                    $logDetails .= '<br>setToEmails:' . $usermail;
                    $logHandler->createLog($logDetails);

                    //execute sending
                    if ($xoopsMailer->send()) {
                        $export = var_export($xoopsMailer, TRUE);
                        $export = \preg_replace("/\n/", '<br>', $export);
                        $logHandler->createLog('Result Test send mail to ' . $usermail .': success' . '<br>' . $export);
                        $result = \_AM_WGEVENTS_ACCOUNT_CHECK_OK;
                        $resultImg = $imgOK;
                    } else {
                        $export = var_export($xoopsMailer, TRUE);
                        $export = \preg_replace("/\n/", '<br>', $export);
                        $logHandler->createLog('Result Test send mail to ' . $usermail .': failed - ' . $xoopsMailer->getErrors() . '<br>' . $export);
                        $result = \_AM_WGEVENTS_ACCOUNT_CHECK_FAILED . '<br>' . $xoopsMailer->getErrors();
                        $resultImg = $imgFailed;
                    }
                    unset($mail);
                }
                catch (phpmailerException $e) {
                    // IN PROGRESS
                    $logHandler->createLog(\_AM_WGEVENTS_ACCOUNT_CHECK_FAILED. 'Result Test account: phpmailerException -' . $e->errorMessage());
                    $result = \_AM_WGEVENTS_ACCOUNT_CHECK_FAILED . '<br>' . imap_last_error() . $e->errorMessage();
                    $resultImg = $imgFailed;
                }
                catch (\Exception $e) {
                    // IN PROGRESS
                    $logHandler->createLog(\_AM_WGEVENTS_ACCOUNT_CHECK_FAILED. 'Result Test account: Exception -' . $e->getMessage());
                    $result = \_AM_WGEVENTS_ACCOUNT_CHECK_FAILED . '<br>' . imap_last_error() . $e->getMessage();
                    $resultImg = $imgFailed;
                }

                $checks['sendtest']['check'] = _AM_WGEVENTS_ACCOUNT_CHECK_SENDTEST;
                $checks['sendtest']['result'] = $result;
                $checks['sendtest']['result_img'] = $resultImg;
                $checks['sendtest']['info'] = $result;
            }
            imap_close($mbox);
        }
        $GLOBALS['xoopsTpl']->assign('checks', $checks);
        break;
    case 'list':
    default:
        $crAccount = new \CriteriaCompo();
        $crAccount->add(new \Criteria('primary', 1));
        $primaryCount = $accountHandler->getCount($crAccount);
        if (0 == $primaryCount) {
            $GLOBALS['xoopsTpl']->assign('info', \_AM_WGEVENTS_THEREARENT_ACCOUNTS_DESC);
        }
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_account.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('account.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ACCOUNT, 'account.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $accountCount = $accountHandler->getCountAccounts();
        $GLOBALS['xoopsTpl']->assign('accountCount', $accountCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        // Table view accounts
        if ($accountCount > 0) {
            $accountAll = $accountHandler->getAllAccounts($start, $limit);
            foreach (\array_keys($accountAll) as $i) {
                $account = $accountAll[$i]->getValuesAccount();
                if (Constants::ACCOUNT_TYPE_VAL_PHP_MAIL != $account['type']
                    && Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL != $account['type']) {
                    $account['show_check'] = true;
                }
                $GLOBALS['xoopsTpl']->append('accounts_list', $account);
                unset($account);
            }
            // Display Navigation
            if ($accountCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($accountCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_account.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('account.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ACCOUNTS, 'account.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $accountObj = $accountHandler->create();
        $form = $accountObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('account.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($accId > 0) {
            $accountObj = $accountHandler->get($accId);
        } else {
            $accountObj = $accountHandler->create();
        }
        // Set Vars
        $accountObj->setVar('type', Request::getInt('type'));
        $accountObj->setVar('name', Request::getString('name'));
        $accountObj->setVar('yourname', Request::getString('yourname'));
        $accountObj->setVar('yourmail', Request::getString('yourmail'));
        $accountObj->setVar('username', Request::getString('username'));
        $accountObj->setVar('password', Request::getString('password'));
        $accountObj->setVar('server_in', Request::getString('server_in'));
        $accountObj->setVar('port_in', Request::getInt('port_in'));
        $accountObj->setVar('securetype_in', Request::getString('securetype_in'));
        $accountObj->setVar('server_out', Request::getString('server_out'));
        $accountObj->setVar('port_out', Request::getInt('port_out'));
        $accountObj->setVar('securetype_out', Request::getString('securetype_out'));
        $accountObj->setVar('primary', Request::getInt('primary'));
        $accountObj->setVar('datecreated', Request::getInt('datecreated'));
        $accountObj->setVar('submitter', Request::getInt('submitter'));
        // Insert Data
        if ($accountHandler->insert($accountObj)) {
            $newAccId = $accId > 0 ? $accId : $accountObj->getNewInsertedId();
            if ('none' === $save_and_check) {
                redirect_header('?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 3, _MA_WGEVENTS_FORM_OK);
            } else {
                redirect_header('account.php?op=check_account&id=' . $newAccId . '&amp;start=' . $start . '&amp;limit=' . $limit, 3, _MA_WGEVENTS_FORM_OK);
            }
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $accountObj->getHtmlErrors());
        $form = $accountObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_account.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('account.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ACCOUNT, 'account.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ACCOUNTS, 'account.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $accountObj = $accountHandler->get($accId);
        $accountObj->start = $start;
        $accountObj->limit = $limit;
        $form = $accountObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_account.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('account.php'));
        $accountObj = $accountHandler->get($accId);
        $accType = $accountObj->getVar('type');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('account.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($accountHandler->delete($accountObj)) {
                \redirect_header('account.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $accountObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'id' => $accId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $accountObj->getVar('name')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'change_yn':
        if ($accId > 0) {
            if (Request::getInt('value') > 0) {
                // reset all to false
                $crAccount = new \CriteriaCompo();
                $crAccount->add(new \Criteria('primary', 1));
                $accountHandler->updateAll('primary', 0, $crAccount,true);
            }
            $accountObj = $accountHandler->get($accId);
            $accountObj->setVar(Request::getString('field'), Request::getInt('value'));
            // Insert Data
            if ($accountHandler->insert($accountObj, true)) {
                \redirect_header('account.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 2, \_MA_WGEVENTS_FORM_OK);
            }
        }
        break;
}
require __DIR__ . '/footer.php';
