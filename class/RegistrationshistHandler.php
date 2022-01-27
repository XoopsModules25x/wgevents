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
use XoopsModules\Wgevents\Helper;

/**
 * Class Object Handler Registrations History
 */
class RegistrationshistHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_registrations_hist', Registrationshist::class, 'hist_id', 'reg_id');
    }

    /**
     * @param bool $isNew
     *
     * @return object
     */
    public function create($isNew = true)
    {
        return parent::create($isNew);
    }

    /**
     * retrieve a field
     *
     * @param int $i field id
     * @param null fields
     * @return \XoopsObject|null reference to the {@link Get} object
     */
    public function get($i = null, $fields = null)
    {
        return parent::get($i, $fields);
    }

    /**
     * @public function to create history of given dataset
     * @param object $registrationsObj
     * @param string $info
     * @return bool
     */
    public function createHistory(object $registrationsObj, string $info)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();
        if ($helper->getConfig('use_register_hist')) {
            $submitter = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;

            $registrationshistHandler = $helper->getHandler('Registrationshist');
            $registrationshistObj = $registrationshistHandler->create();
            $registrationshistObj->setVar('hist_info', $info);
            $registrationshistObj->setVar('hist_datecreated', \time());
            $registrationshistObj->setVar('hist_submitter', $submitter);
            $vars = $registrationsObj->getVars();
            foreach (\array_keys($vars) as $var) {
                $registrationshistObj->setVar($var, $registrationsObj->getVar($var));
            }
            $registrationshistHandler->insert($registrationshistObj);

            return true;
        } else {
            return false;
        }
    }

}