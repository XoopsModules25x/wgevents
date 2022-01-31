<{include file='db:wgevents_header.tpl' }>

<{if $error|default:''}>
    <div class="col-12 center wge-error-msg"><{$error|default:false}></div>
<{/if}>

<{if $eventsCount|default:0 > 0}>
    <div class='wge-spacer2 center'><h3 class="center"><{$listDescr|default:''}></h3></div>
    <div class='table-responsive'>
        <table class='table table-<{$table_type|default:false}>'>
            <tbody>
                <{foreach item=event from=$events name=event}>
                    <tr>
                        <td>
                            <div class='panel'>
                                <{if $showList|default:false}>
                                    <{include file='db:wgevents_event_item_list.tpl' }>
                                <{else}>
                                    <{include file='db:wgevents_event_item_details.tpl' }>
                                <{/if}>
                            </div>
                        </td>
                    <tr>
                <{/foreach}>
            </tbody>
            <tfoot><tr><td>&nbsp;</td></tr></tfoot>
        </table>
    </div>
<{/if}>

<{if $showBtnComing|default:false}>
    <div class="row wge-spacer1">
        <span class='col-sm-12 center'><a class='btn btn-primary' href='event.php?op=list&amp;filter=<{$filter}>' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LISTCOMING}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LISTCOMING}></a></span>
    </div>
    <{/if}>
<{if $showBtnPast|default:false}>
    <div class="row wge-spacer1">
        <span class='col-sm-12 center'><a class='btn btn-primary' href='event.php?op=past&amp;filter=<{$filter}>' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LISTPAST}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LISTPAST}></a></span>
    </div>
<{/if}>

<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
