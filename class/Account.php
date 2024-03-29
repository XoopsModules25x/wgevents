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
 * Class Account
 */
class Account extends \XoopsObject
{
    public $helper;
    public $db;

    //Constructor

    public function __construct()
    {
        $this->initVar('id', XOBJ_DTYPE_INT);
        $this->initVar('type', XOBJ_DTYPE_INT, Constants::ACCOUNT_TYPE_VAL_PHP_MAIL);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('yourname', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('yourmail', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('username', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('password', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('server_in', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('port_in', XOBJ_DTYPE_INT, null, false, 100);
        $this->initVar('securetype_in', XOBJ_DTYPE_TXTBOX, null, false, 20);
        $this->initVar('server_out', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('port_out', XOBJ_DTYPE_INT, null, false, 100);
        $this->initVar('securetype_out', XOBJ_DTYPE_TXTBOX);
        $this->initVar('limit_hour', XOBJ_DTYPE_INT);
        $this->initVar('primary', XOBJ_DTYPE_INT); // boolean
        $this->initVar('submitter', XOBJ_DTYPE_INT);
        $this->initVar('datecreated', XOBJ_DTYPE_INT, time());
    }

    /**
     * @param bool $action
     *
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        global $xoopsDB;

        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $title = $this->isNew() ? \_AM_WGEVENTS_ACCOUNT_ADD : \_AM_WGEVENTS_ACCOUNT_EDIT;

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new \XoopsThemeForm($title, 'accounts_form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $default = $this->getVar('type');

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
        // Form Select $type
        $type = $this->isNew() ? 1 : $this->getVar('type');
        $accstd_select = new \XoopsFormSelect(\_AM_WGEVENTS_ACCOUNT_TYPE, 'type', $type);
        $accstd_select->setExtra(" onchange='preselectAccFields()' ");
        $accstd_select->addOption(Constants::ACCOUNT_TYPE_VAL_PHP_MAIL, \_AM_WGEVENTS_ACCOUNT_TYPE_PHPMAIL);
        $accstd_select->addOption(Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL, \_AM_WGEVENTS_ACCOUNT_TYPE_PHPSENDMAIL);
        $accstd_select->addOption(Constants::ACCOUNT_TYPE_VAL_POP3, \_AM_WGEVENTS_ACCOUNT_TYPE_POP3);
        $accstd_select->addOption(Constants::ACCOUNT_TYPE_VAL_SMTP, \_AM_WGEVENTS_ACCOUNT_TYPE_SMTP);
        $accstd_select->addOption(Constants::ACCOUNT_TYPE_VAL_GMAIL, \_AM_WGEVENTS_ACCOUNT_TYPE_GMAIL);
        $form->addElement($accstd_select);
        // Form Text $name
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_ACCOUNT_NAME, 'name', 50, 255, $this->getVar('name')));
        // Form Text $yourname
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_ACCOUNT_YOURNAME, 'yourname', 50, 255, $this->getVar('yourname')));
        // Form Text $yourmail
        $form->addElement(new \XoopsFormText(\_AM_WGEVENTS_ACCOUNT_YOURMAIL, 'yourmail', 50, 255, $this->getVar('yourmail')));
        // Form RadioYN $primary
        $primary = $this->isNew() ? 0 : $this->getVar('primary');
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_ACCOUNT_PRIMARY, 'primary', $primary, _YES, _NO));
        // Form Text $username
        $username = new \XoopsFormText(\_AM_WGEVENTS_ACCOUNT_USERNAME, 'username', 50, 255, $this->getVar('username'));
        if ($dis_acc_userpass) {
            $username->setExtra(' disabled="disabled" style="background-color:#d4d5d6"');
        }
        $form->addElement($username);
        // Form Text $password
        $password = new \XoopsFormText(\_AM_WGEVENTS_ACCOUNT_PASSWORD, 'password', 50, 255, $this->getVar('password'));
        if ($dis_acc_userpass) {
            $password->setExtra(' disabled="disabled" style="background-color:#d4d5d6"');
        }
        $form->addElement($password);

        // Form Tray Incoming
        $incomming_tray = new \XoopsFormElementTray(\_AM_WGEVENTS_ACCOUNT_INCOMING, '');
        // Form Tray Incoming - Server in
        $serverIn = new \XoopsFormText(\_AM_WGEVENTS_ACCOUNT_SERVER_IN, 'server_in', 50, 255, $this->getVar('server_in'));
        if ($dis_acc_server_in) {
            $serverIn->setExtra(' disabled="disabled" style="background-color:#d4d5d6"');
        }
        $incomming_tray->addElement($serverIn);
        // Form Tray Incoming - Port in
        $portIn = new \XoopsFormText('<br>' . \_AM_WGEVENTS_ACCOUNT_PORT_IN, 'port_in', 50, 255, $this->getVar('port_in'));
        if ($dis_acc_server_in) {
            $portIn->setExtra(' disabled="disabled" style="background-color:#d4d5d6"');
        }
        $incomming_tray->addElement($portIn);
        // Form Tray Incoming - Secure type in
        $formfield_securetype_in = new \XoopsFormSelect('<br>' . \_AM_WGEVENTS_ACCOUNT_SECURETYPE_IN, 'securetype_in', $this->getVar('securetype_in'));
        $formfield_securetype_in->addOption('');
        $formfield_securetype_in->addOption('notls', 'NOTLS / STARTTLS');
        $formfield_securetype_in->addOption('ssl', 'SSL');
        $formfield_securetype_in->addOption('tls', 'TLS');
        if ($dis_acc_server_in) {
            $formfield_securetype_in->setExtra(' disabled="disabled" style="background-color:#d4d5d6"');
        }
        $incomming_tray->addElement($formfield_securetype_in);
        $form->addElement($incomming_tray);

        // Form Tray Outgoing
        $outcomming_tray = new \XoopsFormElementTray(\_AM_WGEVENTS_ACCOUNT_OUTGOING, '');
        // Form Tray Outgoing - Server out
        $serverOut = new \XoopsFormText(\_AM_WGEVENTS_ACCOUNT_SERVER_OUT, 'server_out', 50, 255, $this->getVar('server_out'));
        if ($dis_acc_server_out) {
            $serverOut->setExtra(' disabled="disabled" style="background-color:#d4d5d6"');
        }
        $outcomming_tray->addElement($serverOut);
        // Form Tray Outgoing - Port out
        $portOut = new \XoopsFormText('<br>' . \_AM_WGEVENTS_ACCOUNT_PORT_OUT, 'port_out', 50, 255, $this->getVar('port_out'));
        if ($dis_acc_server_out) {
            $portOut->setExtra(' disabled="disabled" style="background-color:#d4d5d6"');
        }
        $outcomming_tray->addElement($portOut);
        // Form Tray Outgoing - Secure type out
        $formfield_securetype_out = new \XoopsFormSelect('<br>' . \_AM_WGEVENTS_ACCOUNT_SECURETYPE_OUT, 'securetype_out', $this->getVar('securetype_out'));
        $formfield_securetype_out->addOption('');
        $formfield_securetype_out->addOption('notls', 'NOTLS / STARTTLS');
        $formfield_securetype_out->addOption('ssl', 'SSL');
        $formfield_securetype_out->addOption('tls', 'TLS');
        if ($dis_acc_server_out) {
            $formfield_securetype_out->setExtra(' disabled="disabled" style="background-color:#d4d5d6"');
        }
        $outcomming_tray->addElement($formfield_securetype_out);
        $form->addElement($outcomming_tray);
        // Form Text $limitHour
        $limitHour = $this->isNew() ? 0 : $this->getVar('limit_hour');
        $limitHourText = new \XoopsFormText(\_AM_WGEVENTS_ACCOUNT_LIMIT_HOUR, 'limit_hour', 50, 255, $limitHour);
        $limitHourText->setDescription(\_AM_WGEVENTS_ACCOUNT_LIMIT_HOUR_DESC);
        $form->addElement($limitHourText);
        //
        $time = $this->isNew() ? time() : $this->getVar('datecreated');
        $form->addElement(new \XoopsFormHidden('submitter', $GLOBALS['xoopsUser']->uid()));
        $form->addElement(new \XoopsFormHidden('datecreated', time()));
        $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_SUBMITTER, $GLOBALS['xoopsUser']->uname()));
        $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_DATECREATED, formatTimestamp($time, 's')));

        // Buttons
        $buttonTray = new \XoopsFormElementTray(' ', '&nbsp;&nbsp;');
        $buttonTray->addElement(new \XoopsFormHidden('op', 'save'));
        $buttonTray->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));
        //$button_check = new \XoopsFormButton('', 'save_and_check', \_AM_WGEVENTS_SAVE_AND_CHECK, 'submit');
        //$buttonTray->addElement($button_check);
        $form->addElement($buttonTray);

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
        $acc_types = [
            Constants::ACCOUNT_TYPE_VAL_PHP_MAIL     => \_AM_WGEVENTS_ACCOUNT_TYPE_PHPMAIL,
            Constants::ACCOUNT_TYPE_VAL_PHP_SENDMAIL => \_AM_WGEVENTS_ACCOUNT_TYPE_PHPSENDMAIL,
            Constants::ACCOUNT_TYPE_VAL_POP3         => \_AM_WGEVENTS_ACCOUNT_TYPE_POP3,
            Constants::ACCOUNT_TYPE_VAL_SMTP         => \_AM_WGEVENTS_ACCOUNT_TYPE_SMTP,
            Constants::ACCOUNT_TYPE_VAL_GMAIL        => \_AM_WGEVENTS_ACCOUNT_TYPE_GMAIL,
        ];
        $ret['type_text']        = $acc_types[$this->getVar('type')];
        $ret['primary_text']     = $this->getVar('primary') == 1 ? _YES : _NO;
        $ret['datecreated_text'] = formatTimestamp($this->getVar('datecreated'), 's');
        $ret['submitter_text']   = \XoopsUser::getUnameFromId($this->getVar('submitter'));
        return $ret;
    }

    /**
     * The new inserted $Id
     * @return inserted id
     */
    public function getNewInsertedId()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }
}
