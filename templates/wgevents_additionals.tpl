<{include file='db:wgevents_header.tpl' }>

<{if $regdefaults|default:0 > 0}>
<h3><{$smarty.const._MA_WGEVENTS_ADDITIONALS_LIST}>: <{$eventName|default:''}></h3>
<div class='table-responsivexxx'>
    <table class='table table-<{$table_type|default:false}>'>
        <thead>
            <tr class='head'>
                <th style="width:30px;">&nbsp;</th>
                <th><{$smarty.const._MA_WGEVENTS_ADDITIONAL_TYPE}></th>
                <th><{$smarty.const._MA_WGEVENTS_ADDITIONAL_CAPTION}></th>
                <th><{$smarty.const._MA_WGEVENTS_ADDITIONAL_DESC}></th>
                <th><{$smarty.const._MA_WGEVENTS_ADDITIONAL_VALUE}></th>
                <th><{$smarty.const._MA_WGEVENTS_ADDITIONAL_PLACEHOLDER}></th>
                <th><{$smarty.const._MA_WGEVENTS_ADDITIONAL_REQUIRED}></th>
                <th><{$smarty.const._MA_WGEVENTS_ADDITIONAL_PRINT}></th>
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
        <tbody id="additionals-list">
            <{foreach item=additional from=$additionals name=additional}>
                <{include file='db:wgevents_additionals_item.tpl' }>
            <{/foreach}>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="center">
                    <a class='btn btn-success wge-btn' href='events.php?op=show&amp;ev_id=<{$addEvid}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_EVENT}>'><{$smarty.const._MA_WGEVENTS_GOTO_EVENT}></a>
                    <a class='btn btn-primary wge-btn' href='additionals.php?op=new&amp;add_evid=<{$addEvid}>' title='<{$smarty.const._MA_WGEVENTS_ADDITIONAL_ADD}>'><{$smarty.const._MA_WGEVENTS_ADDITIONAL_ADD}></a>
                    <a class='btn btn-success wge-btn' href='additionals.php?op=test&amp;add_evid=<{$addEvid}>' title='<{$smarty.const._MA_WGEVENTS_ADDITIONALS_PREVIEW}>'><{$smarty.const._MA_WGEVENTS_ADDITIONALS_PREVIEW}></a>
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
