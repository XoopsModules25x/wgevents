<!-- Header -->
<{include file='db:wgevents_header.tpl' }>

<table class='table table-bordered'>
    <thead>
        <tr class='head'>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_ID}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_CATID}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_NAME}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOGO}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_DESC}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_DATETO}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_CONTACT}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_EMAIL}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCATION}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMLAT}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMLON}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMZOOM}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_FEE}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_GROUPS}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_STATUS}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_GALID}></th>
        </tr>
    </thead>
    <tbody>
        <{foreach item=event from=$events_list}>
        <tr class='<{cycle values='odd, even'}>'>
            <td class='center'><{$event.id}></td>
            <td class='center'><{$event.catname}></td>
            <td class='center'><{$event.name}></td>
            <td class='center'><img src="<{$wgevents_upload_url|default:false}>/images/events/<{$event.logo}>" alt="events" style="max-width:100px" ></td>
            <td class='center'><{$event.desc_short}></td>
            <td class='center'><{$event.datefrom_text}></td>
            <td class='center'><{$event.dateto_text}></td>
            <td class='center'><{$event.contact}></td>
            <td class='center'><{$event.email}></td>
            <td class='center'><{$event.location}></td>
            <td class='center'><{$event.locgmlat}></td>
            <td class='center'><{$event.locgmlon}></td>
            <td class='center'><{$event.locgmzoom}></td>
            <td class='center'><{$event.fee}></td>
            <td class='center'><{$event.groups}></td>
            <td class='center'><img src="<{$modPathIcon16}>status<{$event.status}>.png" alt="<{$event.status_text}>" title="<{$event.status_text}>" ></td>
            <td class='center'><{$event.galid}></td>
        </tr>
        <{/foreach}>
    </tbody>
</table>
<!-- Footer -->
<{include file='db:wgevents_footer.tpl' }>
