<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<{if $logs_list|default:''}>
    <table class='table table-bordered'>
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._AM_WGEVENTS_LOG_ID}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_LOG_TEXT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
            </tr>
        </thead>
        <{if $logs_count|default:''}>
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
    <{if $pagenav|default:''}>
        <div class="xo-pagenav floatright"><{$pagenav|default:false}></div>
        <div class="clear spacer"></div>
    <{/if}>
<{/if}>
<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:wgevents_admin_footer.tpl' }>
