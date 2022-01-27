
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
    $( "#continue_questions" ).toggle( "slow", function() {});
    
}

function fillInQuestions() {

    var select = document.getElementById('que_type');
    var vselected = select.options[select.selectedIndex].value;

    xoopsGetElementById('que_caption').value = xoopsGetElementById('que_caption_def[' + vselected +  ']').value;
    xoopsGetElementById('que_placeholder').value = xoopsGetElementById('que_placeholder_def[' + vselected +  ']').value;
    if (xoopsGetElementById('que_required_def[' + vselected +  ']').value > 0) {
        xoopsGetElementById('que_required1').checked = true;
    } else {
        xoopsGetElementById('que_required2').checked = true;
    }
    if (xoopsGetElementById('que_print_def[' + vselected +  ']').value > 0) {
        xoopsGetElementById('que_print1').checked = true;
    } else {
        xoopsGetElementById('que_print2').checked = true;
    }

    if (xoopsGetElementById('add_display_values[' + vselected +  ']').value > 0) {
        xoopsGetElementById('que_values').removeAttribute("disabled");
    } else {
        xoopsGetElementById('que_values').setAttribute("disabled", "disabled");
    }
    if (xoopsGetElementById('add_display_placeholder[' + vselected +  ']').value > 0) {
        xoopsGetElementById('que_placeholder').removeAttribute("disabled");
    } else {
        xoopsGetElementById('que_placeholder').setAttribute("disabled", "disabled");
    }

}
