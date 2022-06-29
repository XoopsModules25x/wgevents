<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<{if $tasks_list|default:''}>
    <{include file='db:tablesorter_pagertop.tpl' }>
    <table id="sortTable" class="tablesorter-<{$tablesorter_theme}>">
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._AM_WGEVENTS_TASK_ID}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_TASK_TYPE}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_TASK_PARAMS}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_TASK_RECIPIENT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_STATUS}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_TASK_DATEDONE}></th>
                <th class="center width5 sorter-false filter-false"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $tasks_count|default:''}>
        <tbody>
            <{foreach item=task from=$tasks_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$task.id}></td>
                <td class='center'>(<{$task.type}>) <{$task.type_text}></td>
                <td class='center'><{$task.params_short}></td>
                <td class='center'><{$task.recipient}></td>
                <td class='center'><{$task.datecreated_text}></td>
                <td class='center'><{$task.submitter_text}></td>
                <td class='center'><img src="<{$modPathIcon16}>status<{$task.status}>.png" alt="<{$task.status_text}>" title="<{$task.status_text}>" ></td>
                <td class='center'><{$task.datedone_text}></td>
                <td class="center  width5">
                    <a href="task.php?op=edit&amp;id=<{$task.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> tasks" ></a>
                    <a href="task.php?op=delete&amp;id=<{$task.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> tasks" ></a>
                </td>
            </tr>
            <{/foreach}>
        </tbody>
        <{/if}>
    </table>
    <div class="clear">&nbsp;</div>
    <{include file='db:tablesorter_pagerbottom.tpl' }>
<{/if}>
<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:wgevents_admin_footer.tpl' }>
