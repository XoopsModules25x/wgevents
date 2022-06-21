<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<!-- Table overview events -->
<{if $eventCount|default:0 > 0}>
    <h3><{$eventsHeader}></h3>
    <{include file='db:admin_pagertop.tpl' }>

    <table id="sortTable" class="tablesorter-<{$tablesorter_theme}>" cellspacing="1" cellpadding="0"  width="100%">
        <thead>
        <tr class='head'>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_NAME}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWERS_CURR}></th>
            <th class="center sorter-false filter-false"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
        </tr>
        </thead>
        <tbody>
        <{foreach item=event from=$events_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$event.name}></td>
                <td class='center'><{$event.answerhists}></td>
                <td class="center ">
                    <{if $event.answerhists|default:0 > 0}>
                        <a href="answerhist.php?op=list&amp;evid=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._MA_WGEVENTS_DETAILS}>"><img src="<{xoModuleIcons16 view.png}>" alt="<{$smarty.const._MA_WGEVENTS_DETAILS}> events" ></a>
                    <{/if}>
                </td>
            </tr>
            <{/foreach}>
        </tbody>
    </table>
    <div class="clear">&nbsp;</div>
    <br><br>
<{/if}>

<{if $answerhists_list|default:''}>
    <{include file='db:admin_pagertop.tpl' }>

    <table id="sortTable" class="tablesorter-<{$tablesorter_theme}>" cellspacing="1" cellpadding="0"  width="100%">
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._MA_WGEVENTS_HIST_ID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_HIST_INFO}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_HIST_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_HIST_SUBMITTER}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWER_ID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWER_EVID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWER_REGID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWER_QUEID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWER_TEXT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5 sorter-false filter-false"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $answerhistCount|default:''}>
        <tbody>
            <{foreach item=answer from=$answerhists_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$answer.hist_id}></td>
                <td class='center'><{$answer.hist_info}></td>
                <td class='center'><{$answer.hist_datecreated_text}></td>
                <td class='center'><{$answer.hist_submitter_text}></td>
                <td class='center'><{$answer.id}></td>
                <td class='center'><{$answer.eventname}></td>
                <td class='center'><{$answer.regname}> (<{$answer.regid}>)</td>
                <td class='center'><{$answer.quecaption}></td>
                <td class='center'><{$answer.text}></td>
                <td class='center'><{$answer.datecreated_text}></td>
                <td class='center'><{$answer.submitter_text}></td>
                <td class="center  width5">
                    <a href="answerhist.php?op=delete&amp;id=<{$answer.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> answers" ></a>
                </td>
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
