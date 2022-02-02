<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<{if $events_list|default:''}>
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
                <{if $use_gm|default:''}>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMLAT}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMLON}></th>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMZOOM}></th>
                <{/if}>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_FEE}></th>
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
                <th class="center"><{$smarty.const._MA_WGEVENTS_STATUS}></th>
                <{if $use_wggallery|default:''}>
                    <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_GALID}></th>
                <{/if}>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $eventCount|default:''}>
        <tbody>
            <{foreach item=event from=$events_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$event.id}></td>
                <td class='center'><{$event.catname}></td>
                <td class='center'><{$event.name}></td>
                <td class='center'><img src="<{$wgevents_upload_eventlogos_url_uid|default:false}>/<{$event.logo}>" alt="events" style="max-width:100px" ></td>
                <td class='center'><{$event.desc_short_admin}></td>
                <td class='center'><{$event.datefrom_text}></td>
                <td class='center'><{$event.dateto_text}></td>
                <td class='center'><{$event.contact}></td>
                <td class='center'><{$event.email}></td>
                <td class='center'><{$event.location}></td>
                <{if $use_gm|default:''}>
                    <td class='center'><{$event.locgmlat}></td>
                    <td class='center'><{$event.locgmlon}></td>
                    <td class='center'><{$event.locgmzoom}></td>
                <{/if}>
                <td class='center'><{$event.fee_text}></td>
                <{if $use_register|default:''}>
                    <td class='center'><{$event.register_use_text}></td>
                    <td class='center'><{$event.register_from_text}></td>
                    <td class='center'><{$event.register_to_text}></td>
                    <td class='center'><{$event.register_max}></td>
                    <td class='center'><{$event.register_listwait_text}></td>
                    <td class='center'><{$event.register_autoaccept_text}></td>
                    <td class='center'><{$event.register_notify_text}></td>
                    <td class='center'><{$event.register_forceverif_text}></td>
                <{/if}>
                <td class='center'><img src="<{$modPathIcon16}>status<{$event.status}>.png" alt="<{$event.status_text}>" title="<{$event.status_text}>" ></td>
                <{if $use_wggallery|default:''}>
                    <td class='center'><{$event.galid}></td>
                <{/if}>
                <td class='center'><{$event.datecreated_text}></td>
                <td class='center'><{$event.submitter_text}></td>
                <td class="center  width5">
                    <a href="event.php?op=edit&amp;id=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> events" ></a>
                    <a href="event.php?op=clone&amp;id_source=<{$event.id}>" title="<{$smarty.const._CLONE}>"><img src="<{xoModuleIcons16 editcopy.png}>" alt="<{$smarty.const._CLONE}> events" ></a>
                    <a href="event.php?op=delete&amp;id=<{$event.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> events" ></a>
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
