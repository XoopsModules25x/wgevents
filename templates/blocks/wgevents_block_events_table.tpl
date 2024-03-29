<table class='table table-<{$table_type|default:false}>'>
    <thead>
        <tr class='head'>
            <th><{$smarty.const._MA_WGEVENTS_EVENT_LOGO}></th>
            <th class='center'><{$smarty.const._MA_WGEVENTS_EVENT_NAME}></th>
            <th class='center'><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}></th>
            <th></th>
        </tr>
    </thead>
    <{if count($block)}>
    <tbody>
        <{foreach item=event from=$block}>
        <tr class='<{cycle values="odd, even"}>'>
            <td class='center'>
                <{if $event.logoExist|default:false}>
                    <img class="img-responsive img-fluid wge-event-block-img" src="<{$wgevents_upload_eventlogos_url|default:false}>/<{$event.submitter}>/<{$event.logo}>" alt="<{$event.name}>" >
                <{/if}>
            </td>
            <td class='center'><{$event.name}></td>
            <td class='center'><{$event.datefrom_text}></td>
            <td class='center'><a href='<{$wgevents_url|default:false}>/event.php?op=show&amp;id=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_EVENT_DETAILS}>'><{$smarty.const._MA_WGEVENTS_EVENT_DETAILS}></a></td>
        </tr>
        <{/foreach}>
    </tbody>
    <{/if}>
    <tfoot><tr><td colspan="4" class="center"><a class="btn btn-success" href='<{$wgevents_url|default:false}>/event.php?op=list' title='<{$smarty.const._MB_WGEVENTS_EVENT_SHOWMORE}>'><{$smarty.const._MB_WGEVENTS_EVENT_SHOWMORE}></a></td></tr></tfoot>
</table>
