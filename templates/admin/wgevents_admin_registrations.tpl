<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<!-- Table overview events -->
<{if $events_count|default:0 > 0}>
    <h3><{$eventsHeader}></h3>
    <table class='table table-bordered' style="max-width:500px">
        <thead>
        <tr class='head'>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_NAME}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATIONS_CURR}></th>
            <th class="center"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
        </tr>
        </thead>

        <tbody>
        <{foreach item=event from=$events_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$event.name}></td>
                <td class='center'><{$event.registrations}></td>
                <td class="center ">
                    <a href="registrations.php?op=list&amp;ev_id=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._DETAILS}>"><img src="<{xoModuleIcons16 view.png}>" alt="<{$smarty.const._DETAILS}> events" ></a>
                </td>
            </tr>
            <{/foreach}>
        </tbody>

    </table>
    <div class="clear">&nbsp;</div>
    <br><br>
<{/if}>

<{if $registrations_list|default:''}>
    <!-- Table Registrations -->
    <table class='table table-bordered'>
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_ID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_EVID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_SALUTATION}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_FIRSTNAME}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_LASTNAME}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_EMAIL}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_IP}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_STATUS}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $registrations_count|default:''}>
        <tbody>
            <{foreach item=registration from=$registrations_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$registration.id}></td>
                <td class='center'><{$registration.evid}></td>
                <td class='center'><{$registration.salutation_text|default:''}></td>
                <td class='center'><{$registration.firstname|default:''}></td>
                <td class='center'><{$registration.lastname|default:''}></td>
                <td class='center'><{$registration.email|default:''}></td>
                <td class='center'><{$registration.ip|default:''}></td>
                <td class='center'><{$registration.status_text}></td>
                <td class='center'><{$registration.financial_text}></td>
                <td class='center'><{$registration.listwait}></td>
                <td class='center'><{$registration.datecreated}></td>
                <td class='center'><{$registration.submitter}></td>
                <td class="center  width5">
                    <a href="registrations.php?op=edit&amp;reg_id=<{$registration.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> registrations" ></a>
                    <a href="registrations.php?op=clone&amp;reg_id_source=<{$registration.id}>" title="<{$smarty.const._CLONE}>"><img src="<{xoModuleIcons16 editcopy.png}>" alt="<{$smarty.const._CLONE}> registrations" ></a>
                    <a href="registrations.php?op=delete&amp;reg_id=<{$registration.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> registrations" ></a>
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
