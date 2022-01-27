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
$accId = Request::getInt('acc_id');
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
        $templateMain = 'wgevents_admin_accounts.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('accounts.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ACCOUNTS, 'accounts.php?op=list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $GLOBALS['xoopsTpl']->assign('account_check', true);

        if (0 == $accId) {
            redirect_header('accounts.php', 3, _MA_WGEVENTS_INVALID_PARAM);
        } else {
            $accountObj = $helper->getHandler('Accounts')->get($accId);
        }

        $mailhost = $accountObj->getVar('acc_server_in');
        $port     = $accountObj->getVar('acc_port_in');
        switch ($accountObj->getVar('acc_type')) {
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
        $service_option = $accountObj->getVar('acc_securetype_in');
        $acc_password   = $accountObj->getVar('acc_password');
        $acc_username   = $accountObj->getVar('acc_username');
        $acc_inbox      = $accountObj->getVar('acc_inbox');
        $acc_inbox_ok   = false;
        $acc_hardbox    = $accountObj->getVar('acc_hardbox');
        $acc_hardbox_ok = false;
        $acc_softbox    = $accountObj->getVar('acc_softbox');
        $acc_softbox_ok = false;

        $command = $mailhost . ':' . $port;
        if ('' != $service) {
            $command .= '/' . $service;
        }
        if ('' != $service_option) {
            $command .= '/' . $service_option;
        }

        $checks = [];

        $mbox = @imap_open('{' . $command . '}', $acc_username, $acc_password);
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

                // send test mail
                // read data of account
                $accountObj             = $helper->getHandler('Accounts')->get($accId);
                $account_type           = $accountObj->getVar('acc_type');
                $account_yourname       = $accountObj->getVar('acc_yourname');
                $account_yourmail       = $accountObj->getVar('acc_yourmail');
                $account_username       = $accountObj->getVar('acc_username');
                $account_password       = $accountObj->getVar('acc_password');
                $account_server_out     = $accountObj->getVar('acc_server_out');
                $account_port_out       = $accountObj->getVar('acc_port_out');
                $account_securetype_out = $accountObj->getVar('acc_securetype_out');

                try {
                    if (Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL == $account_type) {
                        $pop = new POP3();
                        $pop->authorise($account_server_out, $account_port_out, 30, $account_username, $account_password, 1);
                    }
                    $xoopsMailer = xoops_getMailer();

                    $xoopsMailer->useMail();

                    $xoopsMailer->CharSet = _CHARSET; //use xoops default character set

                    //if (Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL == $account_type) {
                        //$xoopsMailer->IsSendmail();  Fix Error
                    //}

                    $xoopsMailer->Username = $account_username; // SMTP account username
                    $xoopsMailer->Password = $account_password; // SMTP account password

                    if (Constants::ACCOUNT_TYPE_VAL_POP3 == $account_type) {
                        //xoopsMailer->isSMTP();
                        //$xoopsMailer->SMTPDebug = 2;
                        $xoopsMailer->Host = $account_server_out;
                    }

                    if (Constants::ACCOUNT_TYPE_VAL_SMTP == $account_type
                        || Constants::ACCOUNT_TYPE_VAL_GMAIL == $account_type) {
                        $xoopsMailer->Port = $account_port_out; // set the SMTP port
                        $xoopsMailer->Host = $account_server_out; //sometimes necessary to repeat
                    }

                    if ('' != $account_securetype_out) {
                        $xoopsMailer->SMTPAuth   = true;
                        $xoopsMailer->SMTPSecure = $account_securetype_out; // sets the prefix to the server
                    }
                    $xoopsMailer->setFromEmail($account_yourmail);
                    $xoopsMailer->setFromName($account_yourname);
                    $xoopsMailer->Subject = 'Test account subject';
                    $xoopsMailer->setBody('Test account body');
                    $usermail = $GLOBALS['xoopsUser']->email();
                    $xoopsMailer->setToEmails($usermail);

                    //execute sending
                    if ($xoopsMailer->send()) {
                        $logsHandler->createLog('Result Test send mail to ' . $usermail .': success');
                        $result = \_AM_WGEVENTS_ACCOUNT_CHECK_OK;
                        $resultImg = $imgOK;
                    } else {
                        $logsHandler->createLog('Result Test send mail to ' . $usermail .': failed - ' . $xoopsMailer->getErrors());
                        $result = \_AM_WGEVENTS_ACCOUNT_CHECK_FAILED;
                        $resultImg = $imgFailed;
                    }
                    unset($mail);
                }
                catch (phpmailerException $e) {
                    // IN PROGRESS
                    $logsHandler->createLog(\_AM_WGEVENTS_ACCOUNT_CHECK_FAILED. 'Result Test account: failed -' . $e->errorMessage());
                    $result = \_AM_WGEVENTS_ACCOUNT_CHECK_FAILED;
                    $resultImg = $imgFailed;
                }
                catch (\Exception $e) {
                    // IN PROGRESS
                    $logsHandler->createLog(\_AM_WGEVENTS_ACCOUNT_CHECK_FAILED. 'Result Test account: failed -' . $e->getMessage());
                    $result = \_AM_WGEVENTS_ACCOUNT_CHECK_FAILED;
                    $resultImg = $imgFailed;
                }

                $checks['sendtest']['check'] = _AM_WGEVENTS_ACCOUNT_CHECK_SENDTEST;
                $checks['sendtest']['result'] = $result;
                $checks['sendtest']['result_img'] = $resultImg;
                $checks['sendtest']['info'] = imap_last_error();
            }
            imap_close($mbox);
        }
        $GLOBALS['xoopsTpl']->assign('checks', $checks);
        break;
    case 'list':
    default:
        // Define Stylesheet
        $GLOBALS['xoTheme']->addStylesheet($style, null);
        $templateMain = 'wgevents_admin_accounts.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('accounts.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ACCOUNT, 'accounts.php?op=new');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $accountsCount = $accountsHandler->getCountAccounts();
        $GLOBALS['xoopsTpl']->assign('accounts_count', $accountsCount);
        $GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
        $GLOBALS['xoopsTpl']->assign('wgevents_icons_url_16', \WGEVENTS_ICONS_URL_16);
        $GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
        // Table view accounts
        if ($accountsCount > 0) {
            $accountsAll = $accountsHandler->getAllAccounts($start, $limit);
            foreach (\array_keys($accountsAll) as $i) {
                $account = $accountsAll[$i]->getValuesAccount();
                if (Constants::ACCOUNT_TYPE_VAL_PHP_MAIL != $account['acc_type']
                    && Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL != $account['acc_type']) {
                    $account['show_check'] = true;
                }
                $GLOBALS['xoopsTpl']->append('accounts_list', $account);
                unset($account);
            }
            // Display Navigation
            if ($accountsCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($accountsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_WGEVENTS_THEREARENT_ACCOUNTS);
        }
        break;
    case 'new':
        $templateMain = 'wgevents_admin_accounts.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('accounts.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ACCOUNTS, 'accounts.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Create
        $accountsObj = $accountsHandler->create();
        $form = $accountsObj->getFormAccounts();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('accounts.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($accId > 0) {
            $accountsObj = $accountsHandler->get($accId);
        } else {
            $accountsObj = $accountsHandler->create();
        }
        // Set Vars
        $accountsObj->setVar('acc_type', Request::getInt('acc_type'));
        $accountsObj->setVar('acc_name', Request::getString('acc_name'));
        $accountsObj->setVar('acc_yourname', Request::getString('acc_yourname'));
        $accountsObj->setVar('acc_yourmail', Request::getString('acc_yourmail'));
        $accountsObj->setVar('acc_username', Request::getString('acc_username'));
        $accountsObj->setVar('acc_password', Request::getString('acc_password'));
        $accountsObj->setVar('acc_server_in', Request::getString('acc_server_in'));
        $accountsObj->setVar('acc_port_in', Request::getInt('acc_port_in'));
        $accountsObj->setVar('acc_securetype_in', Request::getString('acc_securetype_in'));
        $accountsObj->setVar('acc_server_out', Request::getString('acc_server_out'));
        $accountsObj->setVar('acc_port_out', Request::getInt('acc_port_out'));
        $accountsObj->setVar('acc_securetype_out', Request::getString('acc_securetype_out'));
        $accountsObj->setVar('acc_default', Request::getInt('acc_default'));
        $accountsObj->setVar('acc_inbox', Request::getString('acc_inbox'));
        $accountsObj->setVar('acc_datecreated', Request::getInt('acc_datecreated'));
        $accountsObj->setVar('acc_submitter', Request::getInt('acc_submitter'));
        // Insert Data
        if ($accountsHandler->insert($accountsObj)) {
            $newAccId = $accId > 0 ? $accId : $accountsObj->getNewInsertedIdAccounts();
            if ('none' === $save_and_check) {
                redirect_header('?op=list&amp;start=' . $start . '&amp;limit=' . $limit, 3, _MA_WGEVENTS_FORM_OK);
            } else {
                redirect_header('accounts.php?op=check_account&acc_id=' . $newAccId . '&amp;start=' . $start . '&amp;limit=' . $limit, 3, _MA_WGEVENTS_FORM_OK);
            }
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $accountsObj->getHtmlErrors());
        $form = $accountsObj->getFormAccounts();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $templateMain = 'wgevents_admin_accounts.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('accounts.php'));
        $adminObject->addItemButton(\_AM_WGEVENTS_ADD_ACCOUNT, 'accounts.php?op=new');
        $adminObject->addItemButton(\_AM_WGEVENTS_LIST_ACCOUNTS, 'accounts.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Get Form
        $accountsObj = $accountsHandler->get($accId);
        $accountsObj->start = $start;
        $accountsObj->limit = $limit;
        $form = $accountsObj->getFormAccounts();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $templateMain = 'wgevents_admin_accounts.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('accounts.php'));
        $accountsObj = $accountsHandler->get($accId);
        $accType = $accountsObj->getVar('acc_type');
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('accounts.php', 3, \implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($accountsHandler->delete($accountsObj)) {
                \redirect_header('accounts.php', 3, \_MA_WGEVENTS_FORM_DELETE_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $accountsObj->getHtmlErrors());
            }
        } else {
            $customConfirm = new Common\Confirm(
                ['ok' => 1, 'acc_id' => $accId, 'start' => $start, 'limit' => $limit, 'op' => 'delete'],
                $_SERVER['REQUEST_URI'],
                \sprintf(\_MA_WGEVENTS_FORM_SURE_DELETE, $accountsObj->getVar('acc_type')));
            $form = $customConfirm->getFormConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}
require __DIR__ . '/footer.php';
