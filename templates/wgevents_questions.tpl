<{include file='db:wgevents_header.tpl' }>

<{if $regdefaults|default:0 > 0}>
<h3><{$smarty.const._MA_WGEVENTS_QUESTIONS_LIST}>: <{$eventName|default:''}></h3>
<div class='table-responsivexxx'>
    <table class='table table-<{$table_type|default:false}>'>
        <thead>
            <tr class='head'>
                <th style="width:30px;">&nbsp;</th>
                <th><{$smarty.const._MA_WGEVENTS_QUESTION_TYPE}></th>
                <th><{$smarty.const._MA_WGEVENTS_QUESTION_CAPTION}></th>
                <th><{$smarty.const._MA_WGEVENTS_QUESTION_DESC}></th>
                <th><{$smarty.const._MA_WGEVENTS_QUESTION_VALUE}></th>
                <th><{$smarty.const._MA_WGEVENTS_QUESTION_PLACEHOLDER}></th>
                <th><{$smarty.const._MA_WGEVENTS_QUESTION_REQUIRED}></th>
                <th><{$smarty.const._MA_WGEVENTS_QUESTION_PRINT}></th>
                <th style="min-width:200px;">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <{foreach item=regdefaults from=$regdefaults}>
                <tr>
                    <td>&nbsp;</td>
                    <td><{$regdefaults.type_text}></td>
                    <td><{$regdefaults.caption}></td>
                    <td><{$regdefaults.desc}></td>
                    <td><{$regdefaults.value_list}></td>
                    <td><{$regdefaults.placeholder}></td>
                    <td><{$regdefaults.required}></td>
                    <td><{$regdefaults.print}></td>
                    <td style="min-width:200px;">&nbsp;</td>
                </tr>
            <{/foreach}>
        </tbody>
        <tbody id="questions-list">
            <{foreach item=question from=$questions name=question}>
                <{include file='db:wgevents_questions_item.tpl' }>
            <{/foreach}>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="center">
                    <a class='btn btn-success wge-btn' href='events.php?op=show&amp;ev_id=<{$addEvid}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_EVENT}>'><{$smarty.const._MA_WGEVENTS_GOTO_EVENT}></a>
                    <a class='btn btn-primary wge-btn' href='questions.php?op=new&amp;que_evid=<{$addEvid}>' title='<{$smarty.const._MA_WGEVENTS_QUESTION_ADD}>'><{$smarty.const._MA_WGEVENTS_QUESTION_ADD}></a>
                    <a class='btn btn-success wge-btn' href='questions.php?op=test&amp;que_evid=<{$addEvid}>' title='<{$smarty.const._MA_WGEVENTS_QUESTIONS_PREVIEW}>'><{$smarty.const._MA_WGEVENTS_QUESTIONS_PREVIEW}></a>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<{/if}>
<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <{$error|default:false}>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
