<style>
    .wge-export-table {
        width:100%;
        margin-top:50px;
    }
    .wge-export-checkbox {
        padding:0 20px;
    }
</style>

<{include file='db:wgevents_header.tpl' }>

<{if $formFilter|default:''}>
    <{$formFilter|default:false}>
<{/if}>

<{if $error|default:''}>
    <div class="col-12 center wge-error-msg"><{$error|default:false}></div>
<{/if}>

<{if $eventsCount|default:0 > 0}>
    <form class="" name="formListExport" id="formListExport" action="export.php" method="post" enctype="multipart/form-data">
        <table id="tableExportEvents" class="wge-export-table table table-<{$table_type|default:false}>">
            <thead>
            <tr class='head wge-export-thead-tr'>
                <th class="center">&nbsp;</th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_CATID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_NAME}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOGO}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_DESC}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_DATETO}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_EVENT_LOCATION}></th>
            </tr>
            </thead>
            <tbody>
            <{foreach item=event from=$events_list}>
                <tr class='wge-export-tbody-tr <{cycle values='odd, even'}>'>
                    <td class='center wge-export-checkbox'>
                        <input type="checkbox" name="chk_event[]" id="chk_event<{$event.id}>" title="<{$event.name}>" value="<{$event.id}>" <{if $event.checked|default:false}> checked="checked"<{/if}>>
                    </td>
                    <td class='left'><{$event.catname}></td>
                    <td class='left'><{$event.name}></td>
                    <td class='center'><img src="<{$wgevents_upload_eventlogos_url|default:false}>/<{$event.submitter}>/<{$event.logo}>" alt="events" style="max-height:50px" ></td>
                    <td class='left'><{$event.desc_short_admin|default:''}></td>
                    <td class='center'><{$event.datefrom_text|default:''}></td>
                    <td class='center'><{$event.dateto_text|default:''}></td>
                    <td class='left'><{$event.location_text_user|default:''}></td>
                </tr>
                <{/foreach}>
            </tbody>
            <tfoot>
                <tr class='wge-export-tfoot-tr <{cycle values='odd, even'}>'>
                    <td class='center wge-export-checkbox'>
                        <input type="checkbox" name="all_events" id="all_events" title="all_events" value="1" checked="" onclick="toggleAllEvents()">
                    </td>
                    <td colspan="2" class='left'><{$smarty.const._MA_WGEVENTS_SELECT_ALL}></td>
                    <td colspan="5" class='right'>
                        <input type="submit" onclick="submit();" class="btn btn-default" name="export_excel" id="export_excel" value="<{$smarty.const._MA_WGEVENTS_OUTPUT_EXCEL}>" title="<{$smarty.const._MA_WGEVENTS_OUTPUT_EXCEL}>">
                        <input type="submit" onclick="submit();" class="btn btn-default" name="export_ics" id="export_ics" value="<{$smarty.const._MA_WGEVENTS_OUTPUT_ICS}>" title="<{$smarty.const._MA_WGEVENTS_OUTPUT_ICS}>">
                    </td>
                </tr>
            </tfoot>
        </table>
        <input type="hidden" name="op" id="op" value="<{$op|default:list}>">
        <input type="hidden" name="export_datefrom" id="export_datefrom" value="<{$dateFrom|default:0}>">
        <input type="hidden" name="export_dateto" id="export_dateto" value="<{$dateTo|default:0}>">
        <input type="hidden" name="export_limit" id="export_limit" value="<{$limit|default:0}>">
    </form>
    <div class="clear">&nbsp;</div>
<{/if}>

<{if $noEventsReason|default:false}>
    <div class="col-12 center wge-error-msg"><{$noEventsReason}></div>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
