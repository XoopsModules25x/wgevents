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
            <th class="center"><{$smarty.const._MA_WGEVENTS_QUESTIONS_CURR}></th>
            <th class="center sorter-false filter-false"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
        </tr>
        </thead>
        <tbody>
        <{foreach item=event from=$events_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$event.name}></td>
                <td class='center'><{$event.questions}></td>
                <td class="center ">
                    <{if $event.questions|default:0 > 0}>
                        <a href="question.php?op=list&amp;evid=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._MA_WGEVENTS_DETAILS}>"><img src="<{xoModuleIcons16 view.png}>" alt="<{$smarty.const._MA_WGEVENTS_DETAILS}> events" ></a>
                    <{/if}>
                </td>
            </tr>
            <{/foreach}>
        </tbody>
    </table>
    <div class="clear">&nbsp;</div>
    <br><br>
<{/if}>

<{if $questions_list|default:''}>
    <{include file='db:admin_pagertop.tpl' }>

    <table id="sortTable" class="tablesorter-<{$tablesorter_theme}>" cellspacing="1" cellpadding="0"  width="100%">
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
                <th class="center width5 sorter-false filter-false"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $questionCount|default:''}>
        <tbody>
            <{foreach item=question from=$questions_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$question.id}></td>
                <td class='center'><{$question.eventname}></td>
                <td class='center'><{$question.type_text}></td>
                <td class='center'><{$question.caption}></td>
                <td class='center'><{$question.desc_short}></td>
                <td class='center'><{$question.value_list}></td>
                <td class='center'><{$question.placeholder}></td>
                <td class='center'><{$question.required_text}></td>
                <td class='center'><{$question.print_text}></td>
                <td class='center'><{$question.weight}></td>
                <td class='center'><{$question.datecreated_text}></td>
                <td class='center'><{$question.submitter_text}></td>
                <td class="center  width5">
                    <a href="question.php?op=edit&amp;id=<{$question.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> questions" ></a>
                    <a href="question.php?op=clone&amp;id_source=<{$question.id}>" title="<{$smarty.const._CLONE}>"><img src="<{xoModuleIcons16 editcopy.png}>" alt="<{$smarty.const._CLONE}> questions" ></a>
                    <a href="question.php?op=delete&amp;id=<{$question.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> questions" ></a>
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
