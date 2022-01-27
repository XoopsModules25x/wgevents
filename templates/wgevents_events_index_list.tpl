<div class='panel-heading center wge-eventheader'><h4><{$event.name}></h4></div>
<div class='panel-body'>
    <div class="row">
        <div class="col-xs-12 col-sm-2">
            <img class="img-responsive img-fluid" src='<{$wgevents_upload_eventlogos_url|default:false}><{$event.ev_submitter}>/<{$event.logo}>' alt='<{$event.name}>' >
        </div>
        <div class="col-xs-12 col-sm-6 wge-panel-details1"><{$event.desc_short_user}></div>
        <div class="col-xs-12 col-sm-4 wge-panel-details2">
            <div class="row">
                <div class="col-5"><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}>: </div>
                <div class="col-7 right"><{$event.datefrom}></div>
            </div>
            <div class="row">
                <div class="col-5"><{$smarty.const._MA_WGEVENTS_EVENT_DATETO}>: </div>
                <div class="col-7 right"><{$event.dateto}></div>
            </div>
        </div>
    </div>
</div>
<div class='panel-foot row right'>
    <div class="col-sm-5">
        <{if $event.regmax|default:false}>
            <div class="progress wge-progress" <{if $event.regcurrent_tip|default:false}>title="<{$event.regcurrent_text}>"<{/if}>>
                <div <{if $event.regcurrent_tip|default:false}>title="<{$event.regcurrent_text}>"<{/if}> class="progress-bar progress-bar-<{$event.regcurrentstate|default:''}> " role="progressbar" aria-valuenow="<{$event.regpercentage|default:100}>"
                     aria-valuemin="0" aria-valuemax="100" style="width:<{$event.regpercentage}>%">
                    <span class="wge-progress-text"><{$event.regcurrent}></span>
                </div>
            </div>
        <{/if}>
    </div>
    <div class="col-sm-7 right">
        <{if $event.locked|default:false}>
        <span class="badge badge-danger wge-badge-index"><{$smarty.const._MA_WGEVENTS_STATUS_LOCKED}></span>
        <{/if}>
        <{if $event.canceled|default:false}>
        <span class="badge badge-danger wge-badge-index"><{$smarty.const._MA_WGEVENTS_STATUS_CANCELED}></span>
        <{/if}>
        <span class='col-sm-12'>
            <a class='btn btn-primary wge-btn' href='events.php?op=show&amp;ev_id=<{$event.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_DETAILS}>'><{$smarty.const._MA_WGEVENTS_DETAILS}></a>
            <{if $event.ev_register_use|default:0 > 0}>
                <a class='btn btn-primary wge-btn' href='registrations.php?op=listmyevent&amp;redir=listmyevent&amp;reg_evid=<{$event.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_GOTO}>'><{$smarty.const._MA_WGEVENTS_REGISTRATION_GOTO}></a>
            <{/if}>
        </span>
    </div>
</div>
