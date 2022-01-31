<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<!-- Table overview events -->
<{if $events_count|default:0 > 0}>
    <h3><{$eventsHeader}></h3>
    <table class='table table-bordered' style="max-width:500px">
        <thead>
        <tr class='head'>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_NAME}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWERS_CURR}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
        </tr>
        </thead>

        <tbody>
        <{foreach item=event from=$events_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$event.name}></td>
                <td class='center'><{$event.answers}></td>
                <td class="center ">
                    <a href="answers.php?op=list&amp;evid=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._MA_WGEVENTS_DETAILS}>"><img src="<{xoModuleIcons16 view.png}>" alt="<{$smarty.const._MA_WGEVENTS_DETAILS}> events" ></a>
                </td>
            </tr>
            <{/foreach}>
        </tbody>

    </table>
    <div class="clear">&nbsp;</div>
    <br><br>
<{/if}>

<{if $answers_list|default:''}>
    <table class='table table-bordered'>
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWER_ID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWER_EVID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWER_REGID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWER_ADDID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ANSWER_TEXT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $answers_count|default:''}>
        <tbody>
            <{foreach item=answer from=$answers_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$answer.id}></td>
                <td class='center'><{$answer.eventname}></td>
                <td class='center'><{$answer.regname}> (<{$answer.regid}>)</td>
                <td class='center'><{$answer.quecaption}></td>
                <td class='center'><{$answer.text}></td>
                <td class='center'><{$answer.datecreated_text}></td>
                <td class='center'><{$answer.submitter_text}></td>
                <td class="center  width5">
                    <a href="answers.php?op=edit&amp;id=<{$answer.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> answers" ></a>
                    <a href="answers.php?op=clone&amp;id_source=<{$answer.id}>" title="<{$smarty.const._CLONE}>"><img src="<{xoModuleIcons16 editcopy.png}>" alt="<{$smarty.const._CLONE}> answers" ></a>
                    <a href="answers.php?op=delete&amp;id=<{$answer.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> answers" ></a>
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
