
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

    var select = document.getElementById('type');
    var vselected = select.options[select.selectedIndex].value;

    xoopsGetElementById('caption').value = xoopsGetElementById('caption_def[' + vselected +  ']').value;
    xoopsGetElementById('placeholder').value = xoopsGetElementById('placeholder_def[' + vselected +  ']').value;
    if (xoopsGetElementById('required_def[' + vselected +  ']').value > 0) {
        xoopsGetElementById('required1').checked = true;
    } else {
        xoopsGetElementById('required2').checked = true;
    }
    if (xoopsGetElementById('print_def[' + vselected +  ']').value > 0) {
        xoopsGetElementById('print1').checked = true;
    } else {
        xoopsGetElementById('print2').checked = true;
    }
    if (xoopsGetElementById('display_desc[' + vselected +  ']').value > 0) {
        xoopsGetElementById('desc').removeAttribute("disabled");
    } else {
        xoopsGetElementById('desc').setAttribute("disabled", "disabled");
    }
    if (xoopsGetElementById('display_values[' + vselected +  ']').value > 0) {
        xoopsGetElementById('values').removeAttribute("disabled");
    } else {
        xoopsGetElementById('values').setAttribute("disabled", "disabled");
    }
    if (xoopsGetElementById('display_placeholder[' + vselected +  ']').value > 0) {
        xoopsGetElementById('placeholder').removeAttribute("disabled");
    } else {
        xoopsGetElementById('placeholder').setAttribute("disabled", "disabled");
    }

}

function preselectAccFields() {

    var select = document.getElementById('type');
    var vselected = select.options[select.selectedIndex].value;
    var color = "#d4d5d6";

    switch(vselected) {
        case 5: // gmail
        case '5':
        case 4: // smtp
        case '4':
        case 3: // pop
        case '3':
            xoopsGetElementById('username').removeAttribute("disabled");
            xoopsGetElementById('username').style.backgroundColor = "";
            xoopsGetElementById('password').removeAttribute("disabled");
            xoopsGetElementById('password').style.backgroundColor = "";
            xoopsGetElementById('server_in').removeAttribute("disabled");
            xoopsGetElementById('server_in').style.backgroundColor = "";
            xoopsGetElementById('port_in').removeAttribute("disabled");
            xoopsGetElementById('port_in').style.backgroundColor = "";
            xoopsGetElementById('securetype_in').removeAttribute("disabled");
            xoopsGetElementById('securetype_in').style.backgroundColor = "";
            xoopsGetElementById('server_out').removeAttribute("disabled");
            xoopsGetElementById('server_out').style.backgroundColor = "";
            xoopsGetElementById('port_out').removeAttribute("disabled");
            xoopsGetElementById('port_out').style.backgroundColor = "";
            xoopsGetElementById('securetype_out').removeAttribute("disabled");
            xoopsGetElementById('securetype_out').style.backgroundColor = "";
            break;
        case 2: // sendmail
        case '2':
            xoopsGetElementById('username').removeAttribute("disabled");
            xoopsGetElementById('username').style.backgroundColor = "";
            xoopsGetElementById('password').removeAttribute("disabled");
            xoopsGetElementById('password').style.backgroundColor = "";
            xoopsGetElementById('server_in').setAttribute("disabled", "disabled");
            xoopsGetElementById('server_in').style.backgroundColor = color;
            xoopsGetElementById('port_in').setAttribute("disabled", "disabled");
            xoopsGetElementById('port_in').style.backgroundColor = color;
            xoopsGetElementById('securetype_in').setAttribute("disabled", "disabled");
            xoopsGetElementById('securetype_in').style.backgroundColor = color
            xoopsGetElementById('server_out').removeAttribute("disabled");
            xoopsGetElementById('server_out').style.backgroundColor = "";
            xoopsGetElementById('port_out').removeAttribute("disabled");
            xoopsGetElementById('port_out').style.backgroundColor = "";
            xoopsGetElementById('securetype_out').removeAttribute("disabled");
            xoopsGetElementById('securetype_out').style.backgroundColor = "";
            break;
        default:
            xoopsGetElementById('username').setAttribute("disabled", "disabled");
            xoopsGetElementById('username').style.backgroundColor = color;
            xoopsGetElementById('password').setAttribute("disabled", "disabled");
            xoopsGetElementById('password').style.backgroundColor = color;
            xoopsGetElementById('server_in').setAttribute("disabled", "disabled");
            xoopsGetElementById('server_in').style.backgroundColor = color;
            xoopsGetElementById('port_in').setAttribute("disabled", "disabled");
            xoopsGetElementById('port_in').style.backgroundColor = color;
            xoopsGetElementById('securetype_in').setAttribute("disabled", "disabled");
            xoopsGetElementById('securetype_in').style.backgroundColor = color;
            xoopsGetElementById('server_out').setAttribute("disabled", "disabled");
            xoopsGetElementById('server_out').style.backgroundColor = color;
            xoopsGetElementById('port_out').setAttribute("disabled", "disabled");
            xoopsGetElementById('port_out').style.backgroundColor = color;
            xoopsGetElementById('securetype_out').setAttribute("disabled", "disabled");
            xoopsGetElementById('securetype_out').style.backgroundColor = color;

            break;
    }
}
