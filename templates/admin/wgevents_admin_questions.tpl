<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<!-- Table overview events -->
<{if $events_count|default:0 > 0}>
    <h3><{$eventsHeader}></h3>
    <table class='table table-bordered' style="max-width:500px">
        <thead>
        <tr class='head'>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_NAME}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTIONS_CURR}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
        </tr>
        </thead>

        <tbody>
        <{foreach item=event from=$events_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$event.name}></td>
                <td class='center'><{$event.questions}></td>
                <td class="center ">
                    <a href="questions.php?op=list&amp;ev_id=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._DETAILS}>"><img src="<{xoModuleIcons16 view.png}>" alt="<{$smarty.const._DETAILS}> events" ></a>
                </td>
            </tr>
            <{/foreach}>
        </tbody>

    </table>
    <div class="clear">&nbsp;</div>
    <br><br>
<{/if}>

<{if $questions_list|default:''}>
    <table class='table table-bordered'>
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTION_ID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTION_EVID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTION_TYPE}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTION_CAPTION}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTION_DESC}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTION_VALUE}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTION_PLACEHOLDER}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTION_REQUIRED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTION_PRINT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_WEIGHT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $questions_count|default:''}>
        <tbody>
            <{foreach item=question from=$questions_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$question.id}></td>
                <td class='center'><{$question.evid}></td>
                <td class='center'><{$question.type_text}></td>
                <td class='center'><{$question.caption}></td>
                <td class='center'><{$question.desc_short}></td>
                <td class='center'><{$question.value}></td>
                <td class='center'><{$question.placeholder}></td>
                <td class='center'><{$question.required}></td>
                <td class='center'><{$question.print}></td>
                <td class='center'><{$question.weight}></td>
                <td class='center'><{$question.datecreated}></td>
                <td class='center'><{$question.submitter}></td>
                <td class="center  width5">
                    <a href="questions.php?op=edit&amp;que_id=<{$question.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> questions" ></a>
                    <a href="questions.php?op=clone&amp;que_id_source=<{$question.id}>" title="<{$smarty.const._CLONE}>"><img src="<{xoModuleIcons16 editcopy.png}>" alt="<{$smarty.const._CLONE}> questions" ></a>
                    <a href="questions.php?op=delete&amp;que_id=<{$question.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> questions" ></a>
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
