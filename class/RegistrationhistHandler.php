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
 * Class Object Handler Registration History
 */
class RegistrationhistHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wgevents_registration_hist', Registrationhist::class, 'hist_id', 'id');
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
     * @param int $id field id
     * @param $fields
     * @return \XoopsObject|null reference to the {@link Get} object
     */
    public function get($id = null, $fields = null)
    {
        return parent::get($id, $fields);
    }

    /**
     * @public function to create history of given dataset
     * @param \XoopsObject $registrationObj
     * @param string $info
     * @return bool
     */
    public function createHistory(\XoopsObject $registrationObj, string $info)
    {
        $helper = \XoopsModules\Wgevents\Helper::getInstance();
        if ($helper->getConfig('use_history')) {
            $submitter = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;

            $registrationhistHandler = $helper->getHandler('Registrationhist');
            $registrationshistObj = $registrationhistHandler->create();
            $registrationshistObj->setVar('hist_info', $info);
            $registrationshistObj->setVar('hist_datecreated', \time());
            $registrationshistObj->setVar('hist_submitter', $submitter);
            $vars = $registrationObj->getVars();
            foreach (\array_keys($vars) as $var) {
                $registrationshistObj->setVar($var, $registrationObj->getVar($var));
            }
            $registrationhistHandler->insert($registrationshistObj);

            return true;
        }

        return false;
    }

}
