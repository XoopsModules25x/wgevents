<table class='table table-<{$table_type|default:false}>'>
    <thead>
        <tr class='head'>
            <th>&nbsp;</th>
            <th class='center'><{$smarty.const._MB_WGEVENTS_EVENT_NAME}></th>
            <th class='center'><{$smarty.const._MB_WGEVENTS_EVENT_LOGO}></th>
        </tr>
    </thead>
    <{if count($block)}>
    <tbody>
        <{foreach item=event from=$block}>
        <tr class='<{cycle values="odd, even"}>'>
            <td class='center'><{$event.id}></td>
            <td class='center'><{$event.name}></td>
            <td class='center'>
                <{if $event.logoExist|default:false}>
                    <img class="img-responsive img-fluid wge-event-block-img" src="<{$wgevents_upload_eventlogos_url|default:false}>/<{$event.submitter}>/<{$event.logo}>" alt="events" >
                <{/if}>
            </td>
            <td class='center'><a href='<{$wgevents_url|default:false}>/event.php?op=show&amp;id=<{$event.id}>' title='<{$smarty.const._MB_WGEVENTS_EVENT_GOTO}>'><{$smarty.const._MB_WGEVENTS_EVENT_GOTO}></a></td>
        </tr>
        <{/foreach}>
    </tbody>
    <{/if}>
    <tfoot><tr><td>&nbsp;</td></tr></tfoot>
</table>
