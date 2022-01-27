<?php

namespace XoopsModules\Wgevents;

/**
 * ****************************************************************************
 *  - A Project by Developers TEAM For Xoops - ( https://xoops.org )
 * ****************************************************************************
 *  WGEVENTS - MODULE FOR XOOPS
 *  Copyright (c) 2007 - 2012
 *  Goffy ( wedega.com )
 *
 *  You may not change or alter any portion of this comment or credits
 *  of supporting developers from this source code or any supporting
 *  source code which is considered copyrighted (c) material of the
 *  original comment or credit authors.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  ---------------------------------------------------------------------------
 * @copyright  Goffy ( wedega.com )
 * @license    GPL 2.0
 * @package    wgevents
 * @author     Goffy ( webmaster@wedega.com )
 *
 * ****************************************************************************
 */

//use XoopsModules\Wgevents;

require_once dirname(__DIR__) . '/include/common.php';

/**
 * Class Accounts
 */
class Accounts extends \XoopsObject
{
    public $helper = null;
    public $db;

    //Constructor

    public function __construct()
    {
        $this->initVar('acc_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('acc_type', XOBJ_DTYPE_INT, Constants::ACCOUNT_TYPE_VAL_PHP_MAIL, false);
        $this->initVar('acc_name', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('acc_yourname', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('acc_yourmail', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('acc_username', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('acc_password', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('acc_server_in', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('acc_port_in', XOBJ_DTYPE_INT, null, false, 100);
        $this->initVar('acc_securetype_in', XOBJ_DTYPE_TXTBOX, null, false, 20);
        $this->initVar('acc_server_out', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('acc_port_out', XOBJ_DTYPE_INT, null, false, 100);
        $this->initVar('acc_securetype_out', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('acc_inbox', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('acc_default', XOBJ_DTYPE_INT, null, false); // boolean
        $this->initVar('acc_submitter', XOBJ_DTYPE_INT, null, false);
        $this->initVar('acc_datecreated', XOBJ_DTYPE_INT, time(), false);
    }

    /**
     * @param bool $action
     *
     * @return \XoopsThemeForm
     */
    public function getFormAccounts($action = false)
    {
        global $xoopsDB;

        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $title = $this->isNew() ? sprintf(_AM_WGEVENTS_ACCOUNT_ADD) : sprintf(_AM_WGEVENTS_ACCOUNT_EDIT);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new \XoopsThemeForm($title, 'accounts_form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $default = $this->getVar('acc_type');

        switch ($default) {
            case Constants::ACCOUNT_TYPE_VAL_PHP_MAIL:
            default:
                $dis_acc_userpass     = true;
                $dis_acc_server_in    = true;
                $dis_acc_server_out   = true;
                $dis_acc_button_check = true;
                break;
            case Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL:
                $dis_acc_userpass     = false;
                $dis_acc_server_in    = true;
                $dis_acc_server_out   = false;
                $dis_acc_button_check = true;
                break;
            case Constants::ACCOUNT_TYPE_VAL_POP3:
            case Constants::ACCOUNT_TYPE_VAL_SMTP:
            case Constants::ACCOUNT_TYPE_VAL_GMAIL:
                $dis_acc_userpass     = false;
                $dis_acc_server_in    = false;
                $dis_acc_server_out   = false;
                $dis_acc_button_check = false;
                break;
        }

        $accstd_select = new \XoopsFormSelect(_AM_WGEVENTS_ACCOUNT_TYPE, 'acc_type', $this->getVar('acc_type'));
        $accstd_select->setExtra('onchange="document.forms.accounts_form.submit()"');
        $accstd_select->addOption(Constants::ACCOUNT_TYPE_VAL_PHP_MAIL, _AM_WGEVENTS_ACCOUNT_TYPE_PHPMAIL);
        $accstd_select->addOption(Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL, _AM_WGEVENTS_ACCOUNT_TYPE_PHPSENDMAIL);
        $accstd_select->addOption(Constants::ACCOUNT_TYPE_VAL_POP3, _AM_WGEVENTS_ACCOUNT_TYPE_POP3);
        $accstd_select->addOption(Constants::ACCOUNT_TYPE_VAL_SMTP, _AM_WGEVENTS_ACCOUNT_TYPE_SMTP);
        $accstd_select->addOption(Constants::ACCOUNT_TYPE_VAL_GMAIL, _AM_WGEVENTS_ACCOUNT_TYPE_GMAIL);
        $form->addElement($accstd_select, true);

        $form->addElement(new \XoopsFormText(_AM_WGEVENTS_ACCOUNT_NAME, 'acc_name', 50, 255, $this->getVar('acc_name')), true);
        $form->addElement(new \XoopsFormText(_AM_WGEVENTS_ACCOUNT_YOURNAME, 'acc_yourname', 50, 255, $this->getVar('acc_yourname')), true);
        $form->addElement(new \XoopsFormText(_AM_WGEVENTS_ACCOUNT_YOURMAIL, 'acc_yourmail', 50, 255, $this->getVar('acc_yourmail')), true);

        $form->addElement(new \XoopsFormRadioYN(_AM_WGEVENTS_ACCOUNT_DEFAULT, 'acc_default', $this->getVar('acc_default'), _YES, _NO), false);

        if (false === $dis_acc_userpass) {
            $form->addElement(new \XoopsFormText(_AM_WGEVENTS_ACCOUNT_USERNAME, 'acc_username', 50, 255, $this->getVar('acc_username')), true);
            $form->addElement(new \XoopsFormText(_AM_WGEVENTS_ACCOUNT_PASSWORD, 'acc_password', 50, 255, $this->getVar('acc_password')), true);
        }

        if (false === $dis_acc_server_in) {
            $incomming_tray = new \XoopsFormElementTray(_AM_WGEVENTS_ACCOUNT_INCOMING, '');
            $incomming_tray->addElement(new \XoopsFormText(_AM_WGEVENTS_ACCOUNT_SERVER_IN, 'acc_server_in', 50, 255, $this->getVar('acc_server_in')));
            $incomming_tray->addElement(new \XoopsFormText('<br>' . _AM_WGEVENTS_ACCOUNT_PORT_IN, 'acc_port_in', 50, 255, $this->getVar('acc_port_in')));
            $formfield_securetype_in = new \XoopsFormSelect('<br>' . _AM_WGEVENTS_ACCOUNT_SECURETYPE_IN, 'acc_securetype_in', $this->getVar('acc_securetype_in'));
            $formfield_securetype_in->addOption('');
            $formfield_securetype_in->addOption('notls', 'NOTLS / STARTTLS');
            $formfield_securetype_in->addOption('ssl', 'SSL');
            $formfield_securetype_in->addOption('tls', 'TLS');
            $incomming_tray->addElement($formfield_securetype_in);
            $form->addElement($incomming_tray);
        }

        if (false === $dis_acc_server_out) {
            $outcomming_tray = new \XoopsFormElementTray(_AM_WGEVENTS_ACCOUNT_OUTGOING, '');
            $outcomming_tray->addElement(new \XoopsFormText(_AM_WGEVENTS_ACCOUNT_SERVER_OUT, 'acc_server_out', 50, 255, $this->getVar('acc_server_out')));
            $outcomming_tray->addElement(new \XoopsFormText('<br>' . _AM_WGEVENTS_ACCOUNT_PORT_OUT, 'acc_port_out', 50, 255, $this->getVar('acc_port_out')));
            $formfield_securetype_out = new \XoopsFormSelect('<br>' . _AM_WGEVENTS_ACCOUNT_SECURETYPE_OUT, 'acc_securetype_out', $this->getVar('acc_securetype_out'));
            $formfield_securetype_out->addOption('');
            $formfield_securetype_out->addOption('notls', 'NOTLS / STARTTLS');
            $formfield_securetype_out->addOption('ssl', 'SSL');
            $formfield_securetype_out->addOption('tls', 'TLS');
            $outcomming_tray->addElement($formfield_securetype_out);
            $form->addElement($outcomming_tray);
        }
        $time = $this->isNew() ? time() : $this->getVar('acc_datecreated');
        $form->addElement(new \XoopsFormHidden('acc_submitter', $GLOBALS['xoopsUser']->uid()));
        $form->addElement(new \XoopsFormHidden('acc_datecreated', time()));

        $form->addElement(new \XoopsFormLabel(_MA_WGEVENTS_SUBMITTER, $GLOBALS['xoopsUser']->uname()));
        $form->addElement(new \XoopsFormLabel(_MA_WGEVENTS_DATECREATED, formatTimestamp($time, 's')));

        $buttonTray = new \XoopsFormElementTray(' ', '&nbsp;&nbsp;');
        $buttonTray->addElement(new \XoopsFormHidden('op', 'save'));
        $buttonTray->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
        if (false === $dis_acc_button_check) {
            $button_check = new \XoopsFormButton('', 'save_and_check', _AM_WGEVENTS_SAVE_AND_CHECK, 'submit');
            $buttonTray->addElement($button_check);
        }
        $form->addElement($buttonTray, false);

        return $form;
    }

    /**
     * Get Values
     * @param null $keys
     * @param string|null $format
     * @param int|null $maxDepth
     * @return array
     */
    public function getValuesAccount($keys = null, $format = null, $maxDepth = null)
    {
        $ret = $this->getValues($keys, $format, $maxDepth);
        $ret['id'] = $this->getVar('acc_id');
        $acc_types = [
            Constants::ACCOUNT_TYPE_VAL_PHP_MAIL     => _AM_WGEVENTS_ACCOUNT_TYPE_PHPMAIL,
            Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL => _AM_WGEVENTS_ACCOUNT_TYPE_PHPSENDMAIL,
            Constants::ACCOUNT_TYPE_VAL_POP3         => _AM_WGEVENTS_ACCOUNT_TYPE_POP3,
            Constants::ACCOUNT_TYPE_VAL_SMTP         => _AM_WGEVENTS_ACCOUNT_TYPE_SMTP,
            Constants::ACCOUNT_TYPE_VAL_GMAIL        => _AM_WGEVENTS_ACCOUNT_TYPE_GMAIL,
        ];
        $ret['type']           = $this->getVar('acc_type');
        $ret['type_text']      = $acc_types[$this->getVar('acc_type')];
        $ret['name']           = $this->getVar('acc_name');
        $ret['yourname']       = $this->getVar('acc_yourname');
        $ret['yourmail']       = $this->getVar('acc_yourmail');
        $ret['username']       = $this->getVar('acc_username');
        $ret['password']       = $this->getVar('acc_password');
        $ret['server_in']      = $this->getVar('acc_server_in');
        $ret['port_in']        = $this->getVar('acc_port_in');
        $ret['securetype_in']  = $this->getVar('acc_securetype_in');
        $ret['server_out']     = $this->getVar('acc_server_out');
        $ret['port_out']       = $this->getVar('acc_port_out');
        $ret['securetype_out'] = $this->getVar('acc_securetype_out');
        $ret['use_bmh']        = $this->getVar('acc_use_bmh');
        $ret['inbox']          = $this->getVar('acc_inbox');
        $ret['hardbox']        = $this->getVar('acc_hardbox');
        $ret['movehard']       = $this->getVar('acc_movehard');
        $ret['softbox']        = $this->getVar('acc_softbox');
        $ret['movesoft']       = $this->getVar('acc_movesoft');
        $ret['default']        = $this->getVar('acc_default');
        $ret['default_text']   = $this->getVar('acc_default') == 1 ? _YES : _NO;
        $ret['datecreated']    = formatTimestamp($this->getVar('acc_datecreated'), 's');
        $ret['submitter']      = \XoopsUser::getUnameFromId($this->getVar('acc_submitter'));
        return $ret;
    }

    /**
     * The new inserted $Id
     * @return inserted id
     */
    public function getNewInsertedIdAccounts()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }
}
