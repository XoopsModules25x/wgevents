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

use XoopsModules\Wgevents;


/**
 * search callback functions
 *
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 * @return array $itemIds
 */
function wgevents_search($queryarray, $andor, $limit, $offset, $userid)
{
    $ret = [];
    $helper = \XoopsModules\Wgevents\Helper::getInstance();
    // search in table events
    // search keywords
    $elementCount = 0;
    $eventsHandler = $helper->getHandler('Events');
    if (\is_array($queryarray)) {
        $elementCount = \count($queryarray);
    }
    if ($elementCount > 0) {
        $crKeywords = new \CriteriaCompo();
        for ($i = 0; $i  <  $elementCount; $i++) {
            $crKeyword = new \CriteriaCompo();
            $crKeyword->add(new \Criteria('ev_name', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
            $crKeyword->add(new \Criteria('ev_desc', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
            $crKeywords->add($crKeyword, $andor);
            unset($crKeyword);
        }
    }
    // search user(s)
    if ($userid && \is_array($userid)) {
        $userid = array_map('\intval', $userid);
        $crUser = new \CriteriaCompo();
        $crUser->add(new \Criteria('ev_submitter', '(' . \implode(',', $userid) . ')', 'IN'), 'OR');
    } elseif (is_numeric($userid) && $userid > 0) {
        $crUser = new \CriteriaCompo();
        $crUser->add(new \Criteria('ev_submitter', $userid), 'OR');
    }
    $crSearch = new \CriteriaCompo();
    if (isset($crKeywords)) {
        $crSearch->add($crKeywords);
    }
    if (isset($crUser)) {
        $crSearch->add($crUser);
    }
    $crSearch->setStart($offset);
    $crSearch->setLimit($limit);
    $crSearch->setSort('ev_datecreated');
    $crSearch->setOrder('DESC');
    $eventsAll = $eventsHandler->getAll($crSearch);
    foreach (\array_keys($eventsAll) as $i) {
        $ret[] = [
            'image'  => 'assets/icons/16/events.png',
            'link'   => 'events.php?op=show&amp;ev_id=' . $eventsAll[$i]->getVar('ev_id'),
            'title'  => $eventsAll[$i]->getVar('ev_name'),
            'time'   => $eventsAll[$i]->getVar('ev_datecreated')
        ];
    }
    unset($crKeywords);
    unset($crKeyword);
    unset($crUser);
    unset($crSearch);








    return $ret;

}
