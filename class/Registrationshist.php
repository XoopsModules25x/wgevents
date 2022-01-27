<?php declare(strict_types=1);


namespace XoopsModules\Wgevents;

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

use XoopsModules\Wgevents;
use XoopsModules\Wgevents\Forms\FormelementsHandler;

\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Registrationshist
 */
class Registrationshist extends \XoopsObject
{
    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        $this->initVar('hist_id', \XOBJ_DTYPE_INT);
        $this->initVar('hist_info', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('hist_datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('hist_submitter', \XOBJ_DTYPE_INT);
        $this->initVar('reg_id', \XOBJ_DTYPE_INT);
        $this->initVar('reg_evid', \XOBJ_DTYPE_INT);
        $this->initVar('reg_salutation', \XOBJ_DTYPE_INT);
        $this->initVar('reg_firstname', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('reg_lastname', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('reg_email', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('reg_email_send', \XOBJ_DTYPE_INT);
        $this->initVar('reg_gdpr', \XOBJ_DTYPE_INT);
        $this->initVar('reg_ip', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('reg_status', \XOBJ_DTYPE_INT);
        $this->initVar('reg_financial', \XOBJ_DTYPE_INT);
        $this->initVar('reg_listwait', \XOBJ_DTYPE_INT);
        $this->initVar('reg_datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('reg_submitter', \XOBJ_DTYPE_INT);
    }

    /**
     * @static function &getInstance
     *
     * @param null
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
    }

}
