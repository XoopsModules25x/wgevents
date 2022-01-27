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
                <th class="center width5"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $logs_count|default:''}>
        <tbody>
            <{foreach item=log from=$logs_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$log.id}></td>
                <td class='center'><{$log.text}></td>
                <td class='center'><{$log.datecreated}></td>
                <td class='center'><{$log.submitter}></td>
                <td class="center  width5">
                    <a href="logs.php?op=edit&amp;log_id=<{$log.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> logs" ></a>
                    <a href="logs.php?op=clone&amp;log_id_source=<{$log.id}>" title="<{$smarty.const._CLONE}>"><img src="<{xoModuleIcons16 editcopy.png}>" alt="<{$smarty.const._CLONE}> logs" ></a>
                    <a href="logs.php?op=delete&amp;log_id=<{$log.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> logs" ></a>
                </td>
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
