<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<{if $events_list|default:''}>
    <{include file='db:tablesorter_pagertop.tpl' }>

    <table id="sortTable" class="tablesorter-<{$tablesorter_theme}>" cellspacing="1" cellpadding="0"  width="100%">
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_ID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_CATID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_IDENTIFIER}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_NAME}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOGO}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_DESC}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_DATETO}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_CONTACT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_EMAIL}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_URL}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCATION}></th>
                <{if $use_gmaps|default:''}>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMLAT}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMLON}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMZOOM}></th>
                <{/if}>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_FEE}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_PAYMENTINFO}></th>
                <{if $use_register|default:''}>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_USE}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_FROM}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_TO}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_MAX}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_LISTWAIT}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_NOTIFY}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF}></th>
                <{/if}>
                <{if $use_wggallery|default:''}>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_GALID}></th>
                <{/if}>
                <{if $use_groups|default:''}>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_GROUPS}></th>
                <{/if}>
                <th class="center"><{$smarty.const._MA_WGEVENTS_STATUS}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5 sorter-false filter-false"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $eventCount|default:''}>
        <tbody>
            <{foreach item=event from=$events_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$event.id}></td>
                <td class='center'><{$event.catname}></td>
                <td class='center'><{$event.identifier}></td>
                <td class='center'><{$event.name}></td>
                <td class='center'><img src="<{$wgevents_upload_eventlogos_url|default:false}>/<{$event.submitter}>/<{$event.logo}>" alt="events" style="max-width:100px" ></td>
                <td class='center'><{$event.desc_short_admin|default:''}></td>
                <td class='center'><{$event.datefrom_text|default:''}></td>
                <td class='center'><{$event.dateto_text|default:''}></td>
                <td class='center'><{$event.contact|default:''}></td>
                <td class='center'><{$event.email|default:''}></td>
                <td class='center'><{$event.url|default:''}></td>
                <td class='center'><{$event.location_text_user|default:''}></td>
                <{if $use_gmaps|default:''}>
                    <td class='center'><{$event.locgmlat}></td>
                    <td class='center'><{$event.locgmlon}></td>
                    <td class='center'><{$event.locgmzoom}></td>
                <{/if}>
                <td class='center'><{$event.fee_text|default:''}></td>
                <td class='center'><{$event.paymentinfo_text|default:''}></td>
                <{if $use_register|default:''}>
                    <td class='center'><{$event.register_use_text}></td>
                    <td class='center'><{$event.register_from_text}></td>
                    <td class='center'><{$event.register_to_text}></td>
                    <td class='center'><{$event.register_max}></td>
                    <td class='center'><{$event.register_listwait_text}></td>
                    <td class='center'><{$event.register_autoaccept_text}></td>
                    <td class='center'><{$event.register_notify_text|default:''}></td>
                    <td class='center'><{$event.register_forceverif_text}></td>
                <{/if}>
                <{if $use_wggallery|default:''}>
                    <td class='center'><{$event.galid}></td>
                <{/if}>
                <{if $use_groups|default:''}>
                    <td class='center'><{$event.groups}><br><{$event.groups_text}></td>
                <{/if}>
                <td class='center'><img src="<{$modPathIcon16}>status<{$event.status}>.png" alt="<{$event.status_text}>" title="<{$event.status_text}>" ></td>
                <td class='center'><{$event.datecreated_text}></td>
                <td class='center'><{$event.submitter_text}></td>
                <td class="center  width5">
                    <a href="event.php?op=edit&amp;id=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 'edit.png'}>" alt="<{$smarty.const._EDIT}> events" ></a>
                    <a href="event.php?op=delete&amp;id=<{$event.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 'delete.png'}>" alt="<{$smarty.const._DELETE}> events" ></a>
                </td>
            </tr>
            <{/foreach}>
        </tbody>
        <{/if}>
    </table>
    <div class="clear">&nbsp;</div>
    <{include file='db:tablesorter_pagerbottom.tpl' }>
<{/if}>
<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:wgevents_admin_footer.tpl' }>
