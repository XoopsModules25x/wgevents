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
 * @author       Goffy - Wedega - Email:webmaster@wedega.com - Website:https://xoops.wedega.com
 */

if (isset($templateMain)) {
    $GLOBALS['xoopsTpl']->assign('maintainedby', $helper->getConfig('maintainedby'));
    $GLOBALS['xoopsTpl']->display("db:$templateMain");
}

$xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
//$xoTheme->addScript('browse.php?Frameworks/jquery/plugins/jquery.tablesorter.js');
$xoTheme->addScript($helper->url('assets/js/tablesorter/js/jquery.tablesorter.js'));
$xoTheme->addScript($helper->url('assets/js/tablesorter/js/jquery.tablesorter.widgets.js'));
$xoTheme->addScript($helper->url('assets/js/tablesorter/js/extras/jquery.tablesorter.pager.min.js'));
$xoTheme->addScript($helper->url('assets/js/tablesorter/js/widgets/widget-pager.min.js'));

//$xoTheme->addScript($helper->url('assets/js/tablesorter/functions.js'));

xoops_cp_footer();
