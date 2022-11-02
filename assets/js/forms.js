
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

$(document).ready(function(){

    var addButton = $('.btn_fee_add'); //Add button selector
    var wrapper = $('#wrapper_fee'); //Input field wrapper
    var fieldHTML = document.getElementById("hidden_group").innerHTML; //New input field html

    //Once add button is clicked
    $(addButton).click(function(){
        $(wrapper).append(fieldHTML); //Add field html
    });

    //Once remove button is clicked
    $(wrapper).on('click', '.btn_fee_remove', function(e){
        e.preventDefault();
        $(this).parent('span').remove(); //Remove field html
    });
});

function removeFieldFee(){
    $(this).parent('span').remove(); //Remove field html
}

function toogleRegistrationOpts() {

    $( "#registeropttray" ).toggle( "slow", function() {});
    $( "#continue_questions" ).toggle( "slow", function() {});
    
}

function toogleAllday() {

    var cb_allday = document.getElementById('allday1');
    if (cb_allday.checked) {
        xoopsGetElementById('datefrom[time]').setAttribute("disabled", "disabled");
        xoopsGetElementById('dateto[time]').setAttribute("disabled", "disabled");
    } else {
        xoopsGetElementById('datefrom[time]').removeAttribute("disabled");
        xoopsGetElementById('dateto[time]').removeAttribute("disabled");
    }

}

function toogleAllCats() {

    var cb_all = document.getElementById('all_cats1');
    var checkboxes = document.getElementsByName('filter_cats[]');
    if (cb_all.checked) {
        for (var i in checkboxes){
            checkboxes[i].checked = true;
        }
    } else {
        for (var i in checkboxes){
            checkboxes[i].checked = false;
        }
    }

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
