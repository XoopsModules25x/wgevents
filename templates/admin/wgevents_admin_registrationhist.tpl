<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<!-- Table overview events -->
<{if $eventCount|default:0 > 0}>
    <h3><{$eventsHeader}></h3>
    <{include file='db:admin_pagertop.tpl' }>

    <table id="sortTable" class="tablesorter-<{$tablesorter_theme}>" cellspacing="1" cellpadding="0"  width="100%">
        <thead>
        <tr class='head'>
            <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_NAME}></th>
            <th class="center"><{$smarty.const._AM_WGEVENTS_REGISTRATIONHISTS_CURR}></th>
            <th class="center sorter-false filter-false"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
        </tr>
        </thead>
        <tbody>
        <{foreach item=event from=$events_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$event.name}></td>
                <td class='center'><{$event.registrationhists}></td>
                <td class="center ">
                    <{if $event.registrationhists|default:0 > 0}>
                        <a href="registrationhist.php?op=list&amp;evid=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._MA_WGEVENTS_DETAILS}>"><img src="<{xoModuleIcons16 view.png}>" alt="<{$smarty.const._MA_WGEVENTS_DETAILS}> events" ></a>
                    <{/if}>
                </td>
            </tr>
            <{/foreach}>
        </tbody>
    </table>
    <div class="clear">&nbsp;</div>
    <br><br>
<{/if}>

<{if $registrationhists_list|default:''}>
    <{include file='db:admin_pagertop.tpl' }>

    <table id="sortTable" class="tablesorter-<{$tablesorter_theme}>" cellspacing="1" cellpadding="0"  width="100%">
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._AM_WGEVENTS_HIST_ID}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_HIST_INFO}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_HIST_DATECREATED}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_HIST_SUBMITTER}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_ID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_EVID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_SALUTATION}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_FIRSTNAME}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_LASTNAME}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_EMAIL}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_IP}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_STATUS}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_PAIDAMOUNT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5 sorter-false filter-false"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $registrationhistCount|default:''}>
        <tbody>
            <{foreach item=registration from=$registrationhists_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$registration.hist_id}></td>
                <td class='center'><{$registration.hist_info}></td>
                <td class='center'><{$registration.hist_datecreated_text}></td>
                <td class='center'><{$registration.hist_submitter_text}></td>
                <td class='center'><{$registration.id}></td>
                <td class='center'><{$registration.eventname}></td>
                <td class='center'><{$registration.salutation_text|default:''}></td>
                <td class='center'><{$registration.firstname|default:''}></td>
                <td class='center'><{$registration.lastname|default:''}></td>
                <td class='center'><{$registration.email|default:''}></td>
                <td class='center'><{$registration.ip|default:''}></td>
                <td class='center'><{$registration.status_text}></td>
                <td class='center'><{$registration.financial_text}></td>
                <td class='center'><{$registration.paidamount_text}></td>
                <td class='center'><{$registration.listwait_text}></td>
                <td class='center'><{$registration.datecreated_text}></td>
                <td class='center'><{$registration.submitter_text}></td>
                <td class="center width5 sorter-false filter-false">
                    <a href="registrationhist.php?op=delete&amp;id=<{$registration.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> registrationhists" ></a>
                </td>
            </tr>
            <{/foreach}>
        </tbody>
        <{/if}>
    </table>
    <div class="clear">&nbsp;</div>
    <{include file='db:admin_pagerbottom.tpl' }>
<{/if}>
<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:wgevents_admin_footer.tpl' }>
