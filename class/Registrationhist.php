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
 * Class Object Registrationhist
 */
class Registrationhist extends \XoopsObject
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
        $this->initVar('id', \XOBJ_DTYPE_INT);
        $this->initVar('evid', \XOBJ_DTYPE_INT);
        $this->initVar('salutation', \XOBJ_DTYPE_INT);
        $this->initVar('firstname', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('lastname', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('email', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('email_send', \XOBJ_DTYPE_INT);
        $this->initVar('gdpr', \XOBJ_DTYPE_INT);
        $this->initVar('ip', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('status', \XOBJ_DTYPE_INT);
        $this->initVar('financial', \XOBJ_DTYPE_INT);
        $this->initVar('paidamount', \XOBJ_DTYPE_FLOAT);
        $this->initVar('listwait', \XOBJ_DTYPE_INT);
        $this->initVar('datecreated', \XOBJ_DTYPE_INT);
        $this->initVar('submitter', \XOBJ_DTYPE_INT);
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

    /**
     * Get Values
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getValuesRegistrationhists($keys = null, $format = null, $maxDepth = null)
    {
        $helper  = \XoopsModules\Wgevents\Helper::getInstance();
        $ret = $this->getValues($keys, $format, $maxDepth);
        $ret['hist_datecreated_text'] = \formatTimestamp($this->getVar('hist_datecreated'), 'm');
        $ret['hist_submitter_text']   = \XoopsUser::getUnameFromId($this->getVar('hist_submitter'));
        $eventHandler = $helper->getHandler('Event');
        $eventObj = $eventHandler->get($this->getVar('evid'));
        $ret['eventname']        = $eventObj->getVar('name');
        $ret['salutation_text']  = Utility::getSalutationText($this->getVar('salutation'));
        $ret['status_text']      = Utility::getStatusText($this->getVar('status'));
        $ret['financial_text']   = Utility::getFinancialText($this->getVar('financial'));
        $ret['paidamount_text']  = Utility::FloatToString($this->getVar('paidamount'));
        $ret['listwait_text']    = (int)$this->getVar('listwait') > 0 ? \_YES : \_NO;
        $ret['datecreated_text'] = \formatTimestamp($this->getVar('datecreated'), 'm');
        $ret['submitter_text']   = \XoopsUser::getUnameFromId($this->getVar('submitter'));
        return $ret;
    }

}
