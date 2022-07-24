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

require_once dirname(__DIR__) . '/include/common.php';

/**
 * Class Import
 */
class ImportHandler
{

    //Constructor
    public function __construct()
    {
    }

    /**
     * @param bool $action
     *
     * @return \XoopsThemeForm
     */
    public function getFormApcal($action = false)
    {

        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new \XoopsThemeForm(\_AM_WGEVENTS_IMPORT_APCAL, 'import_form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form label
        $info = '<ul>
                    <li>' . \_AM_WGEVENTS_IMPORT_DELETE . '</li>
                    <li>' . \_AM_WGEVENTS_IMPORT_NORECCUR . '</li>
                    <li>' . \_AM_WGEVENTS_IMPORT_NOPERM . '</li>
                 </ul>';
        $form->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_IMPORT_ATTENTION, $info));
        // Form Radio Categories
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_IMPORT_CATS, 'cats', 1, _YES, _NO), false);

        $form->addElement(new \XoopsFormTextDateSelect(\_AM_WGEVENTS_IMPORT_DATEFROM, 'datefrom', '', time()));
        $dateTo = date(strtotime(date('d-m-Y 23:59:59', strtotime('+1 year'))));
        $form->addElement(new \XoopsFormTextDateSelect(\_AM_WGEVENTS_IMPORT_DATETO, 'dateto', '', $dateTo));
        // Buttons
        $form->addElement(new \XoopsFormHidden('op', 'apcal_exec'));
        $form->addElement(new \XoopsFormButtonTray('', \_AM_WGEVENTS_IMPORT_EXEC, 'submit', '', false));

        return $form;
    }

    /**
     * @param bool $action
     *
     * @return \XoopsThemeForm
     */
    public function getFormExtcal($action = false)
    {

        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new \XoopsThemeForm(\_AM_WGEVENTS_IMPORT_EXTCAL, 'import_form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form label
        $info = '<ul>
                    <li>' . \_AM_WGEVENTS_IMPORT_DELETE . '</li>
                    <li>' . \_AM_WGEVENTS_IMPORT_NORECCUR . '</li>
                    <li>' . \_AM_WGEVENTS_IMPORT_NOPERM . '</li>
                 </ul>';
        $form->addElement(new \XoopsFormLabel(\_AM_WGEVENTS_IMPORT_ATTENTION, $info));
        // Form Radio Categories
        $form->addElement(new \XoopsFormRadioYN(\_AM_WGEVENTS_IMPORT_CATS, 'cats', 1, _YES, _NO), false);

        $form->addElement(new \XoopsFormTextDateSelect(\_AM_WGEVENTS_IMPORT_DATEFROM, 'datefrom', '', time()));
        $dateTo = date(strtotime(date('d-m-Y 23:59:59', strtotime('+1 year'))));
        $form->addElement(new \XoopsFormTextDateSelect(\_AM_WGEVENTS_IMPORT_DATETO, 'dateto', '', $dateTo));
        // Buttons
        $form->addElement(new \XoopsFormHidden('op', 'extcal_exec'));
        $form->addElement(new \XoopsFormButtonTray('', \_AM_WGEVENTS_IMPORT_EXEC, 'submit', '', false));

        return $form;
    }

}
