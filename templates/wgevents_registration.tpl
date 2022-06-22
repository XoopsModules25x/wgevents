<{include file='db:wgevents_header.tpl' }>

<{if $registrationsCount|default:0 > 0}>
    <{if $tablesorter|default:false}><{include file='db:tablesorter_pagertop.tpl' }><{/if}>

    <{foreach item=registration from=$registrations}>
        <div class="wge-eventheader">
            <h3><{if $captionList|default:false}><{$captionList}>: <{/if}><{$registration.event_name|default:''}></h3>
        </div>
        <div class='table-responsive'>
            <{if $tablesorter|default:false}>
                <table id="sortTable" class="tablesorter-<{$tablesorter_theme}>">
            <{else}>
                <table class='table table-<{$table_type|default:false}>'>
            <{/if}>
                <thead>
                    <tr class='head wge-reg-list-head'>
                        <th class="filter-false">&nbsp;</th>
                        <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_SALUTATION}></th>
                        <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_FIRSTNAME}></th>
                        <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_LASTNAME}></th>
                        <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_EMAIL}></th>
                        <{foreach item=question from=$registration.questions}>
                            <th><{$question.caption|default:'false'}></th>
                        <{/foreach}>
                        <th><{$smarty.const._MA_WGEVENTS_STATUS}></th>
                        <{if $registration.event_fee|default:0 > 0}>
                            <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL}></th>
                            <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_PAIDAMOUNT}></th>
                        <{/if}>
                        <{if $showSubmitter|default:false}>
                            <th><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                        <{/if}>
                        <th><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                        <th class="sorter-false filter-false" style="min-width:300px;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <{foreach item=regdetails from=$registration.details name=regdetails}>
                        <{include file='db:wgevents_registration_item.tpl' }>
                    <{/foreach}>
                </tbody>
                <tfoot></tfoot>
            </table>
            <div class="col-12 center">
                <a class='btn btn-success wge-btn' href='event.php?op=show&amp;id=<{$registration.event_id}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_EVENT}>'><{$smarty.const._MA_WGEVENTS_GOTO_EVENT}></a>
                <{if $registration.permEditEvent|default:''}>
                    <a class='btn btn-primary wge-btn' href='output.php?op=reg_all&amp;output_type=xlsx&amp;id=<{$registration.event_id}>&amp;redir=<{$redir}>' title='<{$smarty.const._MA_WGEVENTS_OUTPUT_EXCEL}>'><{$smarty.const._MA_WGEVENTS_OUTPUT_EXCEL}></a>
                    <a class='btn btn-primary wge-btn' href='registration.php?op=contactall&amp;evid=<{$registration.event_id}>' title='<{$smarty.const._MA_WGEVENTS_CONTACT_ALL}>'><{$smarty.const._MA_WGEVENTS_CONTACT_ALL}></a>
                <{/if}>
            </div>
        </div>
    <{/foreach}>

    <{if $tablesorter|default:false}><{include file='db:tablesorter_pagerbottom.tpl' }><{/if}>
<{/if}>

<{if $warning|default:''}>
    <div class="alert alert-warning"><{$warning}></div>
<{/if}>

<{if $form|default:''}>
    <div class="wge-form-registration"><{$form|default:false}></div>
<{/if}>
<{if $error|default:''}>
    <{$error|default:false}>
<{/if}>

<!-- spinner for ajax calls -->
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<style>
    #overlay{
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height:100%;
        display: none;
    }
    .cv-spinner {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px #ddd solid;
        border-top: 4px #2e93e6 solid;
        border-radius: 50%;
        animation: sp-anime 0.8s infinite linear;
    }
    @keyframes sp-anime {
        100% {
            transform: rotate(360deg);
        }
    }
    .is-hide{
        display:none;
    }
</style>

<{include file='db:wgevents_footer.tpl' }>


<script>
    function change_financial($regid, $evid, $change_to) {
        //update data with ajax call of registration_ajax.php
        $.ajax({
            beforeSend: function() {
                $("#overlay").fadeIn(300);
            }, //Show spinner
            complete: function() {
                setTimeout(function(){
                    $("#overlay").fadeOut(300);},500);
            }, //Hide spinner
            url: 'registration_ajax.php',
            dataType: 'json',
            type: "POST",
            data: {op: "change_financial", changeto: $change_to, id: $regid, evid: $evid},
            success: function (response) {
                //update current list
                document.getElementById("paidamount_" + $regid).innerHTML = "<{$js_feedefault|default:'?'}>";
                if ($change_to == 1) {
                    //change status to paid
                    document.getElementById("financial_" + $regid).innerHTML = "<{$js_lang_paid|default:'paid'}>";
                    document.getElementById("paidamount_" + $regid).innerHTML = "<{$js_feedefault|default:'?'}>";
                    document.getElementById("btn_change_financial_0_" + $regid).classList.remove('hidden');
                    document.getElementById("btn_change_financial_1_" + $regid).classList.add('hidden');
                } else {
                    document.getElementById("financial_" + $regid).innerHTML = "<{$js_lang_unpaid|default:'unpaid'}>";
                    document.getElementById("paidamount_" + $regid).innerHTML = "<{$js_feezero|default:'?'}>";
                    document.getElementById("btn_change_financial_0_" + $regid).classList.add('hidden');
                    document.getElementById("btn_change_financial_1_" + $regid).classList.remove('hidden');
                }
                //alert("<{$js_lang_changed|default:'changed'}>");
                //alert(response);
            },
            error: function (response) {
                alert(response);
            }
        });
    }

    function listwait_takeover($regid, $evid) {
        //update data with ajax call of registration_ajax.php
        $.ajax({
            beforeSend: function() {
                $("#overlay").fadeIn(300);
            }, //Show spinner
            complete: function() {
                setTimeout(function(){
                    $("#overlay").fadeOut(300);},500);
            }, //Hide spinner
            url: 'registration_ajax.php',
            dataType: 'text',
            type: "POST",
            data: {op: "listwait_takeover", id: $regid, evid: $evid},
            success: function (response) {
                //update current list
                document.getElementById("lbl_listwait_" + $regid).classList.add('hidden');
                document.getElementById("btn_listwait_" + $regid).classList.add('hidden');
                //alert("<{$js_lang_changed|default:'changed'}>");
            },
            error: function (response) {
                alert(response);
            }
        });
    }
    function approve_status($regid, $evid) {
        //update data with ajax call of registration_ajax.php
        $.ajax({
            beforeSend: function() {
                $("#overlay").fadeIn(300);
            }, //Show spinner
            complete: function() {
                setTimeout(function(){
                    $("#overlay").fadeOut(300);},500);
            }, //Hide spinner
            url: 'registration_ajax.php',
            dataType: 'text',
            type: "POST",
            data: {op: "approve_status", id: $regid, evid: $evid},
            success: function (response) {
                //update current list
                var element =  document.getElementById('lbl_listwait_' + $regid);
                if (typeof(element) != 'undefined' && element != null)
                {
                    document.getElementById("lbl_listwait_" + $regid).classList.add('hidden');
                    document.getElementById("btn_listwait_" + $regid).classList.add('hidden');
                }
                document.getElementById("lbl_status_" + $regid).innerHTML = "<{$js_lang_approved|default:'approved'}>";
                document.getElementById("btn_approve_status_" + $regid).classList.add('hidden');
                //alert("<{$js_lang_changed|default:'changed'}>");
            },
            error: function (response) {
                alert(response);
            }
        });
    }

</script>
