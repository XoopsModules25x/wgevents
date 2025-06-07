<?php

namespace XoopsModules\Wgevents;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author       Goffy - XOOPS Development Team
 */
//\defined('\XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Modulemenu
 */
class Modulemenu
{

    /** function to create an array for XOOPS main menu
     *
     * @return array
     */
    public function getMenuitemsDefault()
    {

        $moduleDirName = \basename(\dirname(__DIR__));
        $pathname      = \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/';
        $urlModule     = '';

        require_once $pathname . 'include/common.php';
        $helper = \XoopsModules\Wgevents\Helper::getInstance();
        //load necessary language files from this module
        $helper->loadLanguage('modinfo');

        require_once \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';
        $helper = Helper::getInstance();
        $permissionsHandler = $helper->getHandler('Permission');

        $items = [];
        $items[] = [
            'name' => \_MI_WGEVENTS_SMNAME1,
            'url'  => $urlModule . 'index.php',
        ];
        // Sub events
        $items[] = [
            'name' => \_MI_WGEVENTS_SMNAME2,
            'url'  => $urlModule . 'event.php',
        ];
        if ($permissionsHandler->getPermEventsSubmit()) {
            // Sub Submit
            $items[] = [
                'name' => \_MI_WGEVENTS_SMNAME10,
                'url' => $urlModule . 'event.php?op=list&amp;filter=me',
            ];
        }
        if ($permissionsHandler->getPermEventsSubmit()) {
            // Sub Submit
            $items[] = [
                'name' => \_MI_WGEVENTS_SMNAME3,
                'url' => $urlModule . 'event.php?op=new',
            ];
        }
        if ($permissionsHandler->getPermEventsSubmit()) {
            // Sub Submit
            $items[] = [
                'name' => \_MI_WGEVENTS_SMNAME8,
                'url' => $urlModule . 'textblock.php?op=list',
            ];
        }
        if ($permissionsHandler->getPermRegistrationsSubmit()) {
            $items[] = [
                'name' => \_MI_WGEVENTS_SMNAME5,
                'url'  => $urlModule . 'registration.php?op=listmy',
            ];
        }
        if ($helper->getConfig('cal_page')) {
            // calendar
            $items[] = [
                'name' => \_MI_WGEVENTS_SMNAME6,
                'url' => $urlModule . 'calendar.php',
            ];
        }
        // export
        $items[] = [
            'name' => \_MI_WGEVENTS_SMNAME11,
            'url' => $urlModule . 'export.php?op=list&amp;new=1',
        ];

        return $items;
    }


    /** function to create a list of sublinks
     *
     * @return array
     */
    public function getMenuitemsSbadmin5()
    {
        $moduleDirName = \basename(\dirname(__DIR__));
        $pathname      = \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/';
        $urlModule     = \XOOPS_URL . '/modules/' . $moduleDirName . '/';

        require_once $pathname . 'include/common.php';
        $helper = \XoopsModules\Wgevents\Helper::getInstance();

        //load necessary language files from this module
/*        $helper->loadLanguage('common');
        $helper->loadLanguage('main');*/
        $helper->loadLanguage('modinfo');

        // start creation of link list as array
        $permissionsHandler = $helper->getHandler('Permission');

        $requestUri = $_SERVER['REQUEST_URI'];
        /*read navbar items related to perms of current user*/
        $nav_items1 = [];
        $nav_items1[] = [
            'highlight' => \strpos($requestUri, $moduleDirName . '/index.php') > 0,
            'url' => $urlModule . 'index.php',
            'icon' => '<i class="fa fa-tachometer fa-fw fa-lg"></i>',
            'name' => \_MI_WGEVENTS_SMNAME1,
            'sublinks' => []
        ];
        // Sub events
        $nav_items1[] = [
            'highlight' => \strpos($requestUri, $moduleDirName . '/event.php') > 0 && 0 === (int)\strpos($requestUri, $moduleDirName . '/event.php?op='),
            'url' => $urlModule . 'event.php',
            'icon' => '<i class="fa fa-file fa-fw fa-lg"></i>',
            'name' => \_MI_WGEVENTS_SMNAME2,
            'sublinks' => []
        ];
        if ($permissionsHandler->getPermEventsSubmit()) {
            // Sub Submit
            $nav_items1[] = [
                'highlight' => \strpos($requestUri, $moduleDirName . '/event.php?op=list') > 0,
                'url' => $urlModule . 'event.php?op=list&amp;filter=me',
                'icon' => '<i class="fa fa-user fa-fw fa-lg"></i>',
                'name' => \_MI_WGEVENTS_SMNAME10,
                'sublinks' => []
            ];
        }
        if ($permissionsHandler->getPermEventsSubmit()) {
            // Sub Submit
            $nav_items1[] = [
                'highlight' => \strpos($requestUri, $moduleDirName . '/event.php?op=new') > 0,
                'url' => $urlModule . 'event.php?op=new',
                'icon' => '<i class="fa fa-file-circle-plus fa-fw fa-lg"></i>',
                'name' => \_MI_WGEVENTS_SMNAME3,
                'sublinks' => []
            ];
        }
        if ($permissionsHandler->getPermEventsSubmit()) {
            // Sub Submit
            $nav_items1[] = [
                'highlight' => \strpos($requestUri, $moduleDirName . '/textblock.php') > 0,
                'url' => $urlModule . 'textblock.php?op=list',
                'icon' => '<i class="fa fa-file-lines fa-fw fa-lg"></i>',
                'name' => \_MI_WGEVENTS_SMNAME8,
                'sublinks' => []
            ];
        }
        if ($permissionsHandler->getPermRegistrationsSubmit()) {
            $nav_items1[] = [
                'highlight' => \strpos($requestUri, $moduleDirName . '/registration.php') > 0,
                'url' => $urlModule . 'registration.php?op=listmy',
                'icon' => '<i class="fa fa-user fa-fw fa-lg"></i>',
                'name' => \_MI_WGEVENTS_SMNAME5,
                'sublinks' => []
            ];
        }
        if ($helper->getConfig('cal_page')) {
            // calendar
            $nav_items1[] = [
                'highlight' => \strpos($requestUri, $moduleDirName . '/calendar.php') > 0,
                'url' => $urlModule . 'calendar.php',
                'icon' => '<i class="fa fa-calendar fa-fw fa-lg"></i>',
                'name' => \_MI_WGEVENTS_SMNAME6,
                'sublinks' => []
            ];
        }
        // export
        $nav_items1[] = [
            'highlight' => \strpos($requestUri, $moduleDirName . '/export.php') > 0,
            'url' => $urlModule . 'export.php?op=list&amp;new=1',
            'icon' => '<i class="fa fa-download fa-fw fa-lg"></i>',
            'name' => \_MI_WGEVENTS_SMNAME11,
            'sublinks' => []
        ];

        return $nav_items1;
    }


}
