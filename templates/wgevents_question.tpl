<{include file='db:wgevents_header.tpl' }>

<{if $regdefaults|default:0 > 0}>
<h3><{$smarty.const._MA_WGEVENTS_QUESTIONS_LIST}>: <{$eventName|default:''}></h3>
<div class='table-responsive'>
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
                    <td><{$regdefaults.type_text|default:''}></td>
                    <td><{$regdefaults.caption|default:''}></td>
                    <td><{$regdefaults.desc_text|default:''}></td>
                    <td><{$regdefaults.value_list|default:''}></td>
                    <td><{$regdefaults.placeholder|default:''}></td>
                    <td><{$regdefaults.required_text|default:''}></td>
                    <td><{$regdefaults.print_text|default:''}></td>
                    <td style="min-width:200px;">&nbsp;</td>
                </tr>
            <{/foreach}>
        </tbody>
        <tbody id="questions-list">
            <{foreach item=question from=$questions|default:null name=question}>
                <{include file='db:wgevents_question_item.tpl' }>
            <{/foreach}>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="center">
                    <a class='btn btn-success wge-btn' href='event.php?op=show&amp;id=<{$addEvid}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_EVENT}>'><{$smarty.const._MA_WGEVENTS_GOTO_EVENT}></a>
                    <a class='btn btn-primary wge-btn' href='question.php?op=new&amp;evid=<{$addEvid}>' title='<{$smarty.const._MA_WGEVENTS_QUESTION_ADD}>'><{$smarty.const._MA_WGEVENTS_QUESTION_ADD}></a>
                    <{if $textblocksCount|default:0 > 0}>
                        <a class='btn btn-primary wge-btn' href='question.php?op=add_textblock&amp;evid=<{$addEvid}>' title='<{$smarty.const._MA_WGEVENTS_TEXTBLOCK_ADD}>'><{$smarty.const._MA_WGEVENTS_TEXTBLOCK_ADD}></a>
                    <{/if}>
                    <a class='btn btn-success wge-btn' href='question.php?op=test&amp;evid=<{$addEvid}>' title='<{$smarty.const._MA_WGEVENTS_QUESTIONS_PREVIEW}>'><{$smarty.const._MA_WGEVENTS_QUESTIONS_PREVIEW}></a>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<{/if}>

<{if $textblocksCount|default:0 > 0}>
    <{$formTextblockSelect|default:''}>
<{/if}>

<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <{$error|default:false}>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
