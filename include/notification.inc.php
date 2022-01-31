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

/**
 * comment callback functions
 *
 * @param  $category
 * @param  $item_id
 * @return array item|null
 */
function wgevents_notify_iteminfo($category, $item_id)
{
    global $xoopsDB;

    if (!\defined('WGEVENTS_URL')) {
        \define('WGEVENTS_URL', \XOOPS_URL . '/modules/wgevents');
    }

    switch ($category) {
        case 'global':
            $item['name'] = '';
            $item['url']  = '';
            return $item;
        case 'events':
            $sql          = 'SELECT name FROM ' . $xoopsDB->prefix('wgevents_event') . ' WHERE id = '. $item_id;
            $result       = $xoopsDB->query($sql);
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['name'];
            $item['url']  = \WGEVENTS_URL . '/event.php?id=' . $item_id;
            return $item;
        case 'registrations':
            $sql          = 'SELECT evid FROM ' . $xoopsDB->prefix('wgevents_registrations') . ' WHERE id = '. $item_id;
            $result       = $xoopsDB->query($sql);
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['evid'];
            $item['url']  = \WGEVENTS_URL . '/registration.php?id=' . $item_id;
            return $item;
    }
    return null;
}
