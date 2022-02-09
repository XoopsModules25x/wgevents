<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<{if $logs_list|default:''}>
    <{include file='db:admin_pagertop.tpl' }>

    <table id="sortTable" class="tablesorter-blue" cellspacing="1" cellpadding="0"  width="100%">
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._AM_WGEVENTS_LOG_ID}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_LOG_TEXT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
            </tr>
        </thead>
        <{if $logCount|default:''}>
        <tbody>
            <{foreach item=log from=$logs_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$log.id}></td>
                <td class='center'><{$log.text}></td>
                <td class='center'><{$log.datecreated_text}></td>
                <td class='center'><{$log.submitter_text}></td>
            </tr>
            <{/foreach}>
        </tbody>
        <{/if}>
    </table>
    <div class="clear">&nbsp;</div>
    <{include file='db:admin_pagerbottom.tpl' }>
<{/if}>
<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:wgevents_admin_footer.tpl' }>
