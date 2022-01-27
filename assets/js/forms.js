
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * wgSimpleAcc module for xoops
 *
 * @copyright      2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        wgsimpleacc
 * @since          1.0
 * @min_xoops      2.5.10
 * @author         Goffy - XOOPS Development Team - Email:<webmaster@wedega.com> - Website:<https://xoops.wedega.com>
 */

function toogleRegistrationOpts() {

    $( "#registeropttray" ).toggle( "slow", function() {});
    $( "#continue_additionals" ).toggle( "slow", function() {});
    
}

function fillInAdditionals() {

    var select = document.getElementById('add_type');
    var vselected = select.options[select.selectedIndex].value;

    xoopsGetElementById('add_caption').value = xoopsGetElementById('add_caption_def[' + vselected +  ']').value;
    xoopsGetElementById('add_placeholder').value = xoopsGetElementById('add_placeholder_def[' + vselected +  ']').value;
    if (xoopsGetElementById('add_required_def[' + vselected +  ']').value > 0) {
        xoopsGetElementById('add_required1').checked = true;
    } else {
        xoopsGetElementById('add_required2').checked = true;
    }
    if (xoopsGetElementById('add_print_def[' + vselected +  ']').value > 0) {
        xoopsGetElementById('add_print1').checked = true;
    } else {
        xoopsGetElementById('add_print2').checked = true;
    }

    if (xoopsGetElementById('add_display_values[' + vselected +  ']').value > 0) {
        xoopsGetElementById('add_values').removeAttribute("disabled");
    } else {
        xoopsGetElementById('add_values').setAttribute("disabled", "disabled");
    }
    if (xoopsGetElementById('add_display_placeholder[' + vselected +  ']').value > 0) {
        xoopsGetElementById('add_placeholder').removeAttribute("disabled");
    } else {
        xoopsGetElementById('add_placeholder').setAttribute("disabled", "disabled");
    }

}
