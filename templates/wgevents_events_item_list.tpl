<i id='evId_<{$event.ev_id}>'></i>
<div class='panel-heading center wge-eventheader'><h4><{$event.name}></h4></div>
<div class='panel-body'>
    <div class="row">
        <div class="col-xs-12 col-sm-2">
            <img class="img-responsive img-fluid" src='<{$wgevents_upload_eventlogos_url|default:false}><{$event.ev_submitter}>/<{$event.logo}>' alt='<{$event.name}>' >
        </div>
        <div class="col-xs-12 col-sm-6 wge-panel-details1"><{$event.desc}></div>
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
<div class='panel-foot'>
    <div class='col-sm-12 right'>
        <{if $event.regcurrentstate|default:''}><span class="badge badge-<{$event.regcurrentstate}> wge-badge-index"><{/if}>
            <{$event.regcurrent|default:''}>
        <{if $event.regcurrentstate|default:''}></span><{/if}>
        <{if $event.locked|default:false}>
        <span class="badge badge-danger wge-badge-index"><{$smarty.const._MA_WGEVENTS_STATUS_LOCKED}></span>
        <{/if}>
        <{if $event.canceled|default:false}>
        <span class="badge badge-danger wge-badge-index"><{$smarty.const._MA_WGEVENTS_STATUS_CANCELED}></span>
        <{/if}>
        <{if $showItem|default:''}>
            <a class='btn btn-success right wge-btn' href='events.php?op=list&amp;filter=<{$filter}>&amp;start=<{$start}>&amp;limit=<{$limit}>#evId_<{$event.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LIST}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LIST}></a>
        <{else}>
            <a class='btn btn-success right wge-btn' href='events.php?op=show&amp;ev_id=<{$event.ev_id}>&amp;filter=<{$filter}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._MA_WGEVENTS_DETAILS}>'><{$smarty.const._MA_WGEVENTS_DETAILS}></a>
        <{/if}>
        <{if $event.ev_register_use|default:0 > 0}>
            <a class='btn btn-primary wge-btn' href='registrations.php?op=listmyevent&amp;redir=listmyevent&amp;reg_evid=<{$event.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_GOTO}>'><{$smarty.const._MA_WGEVENTS_REGISTRATION_GOTO}></a>
        <{/if}>
        <{if $permEdit|default:''}>
            <a class='btn btn-success right wge-btn' href='events.php?op=edit&amp;ev_id=<{$event.ev_id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._EDIT}>'><{$smarty.const._EDIT}></a>
            <a class='btn btn-primary right wge-btn' href='events.php?op=clone&amp;ev_id_source=<{$event.ev_id}>' title='<{$smarty.const._CLONE}>'><{$smarty.const._CLONE}></a>
            <a class='btn btn-danger right wge-btn' href='events.php?op=delete&amp;ev_id=<{$event.ev_id}>' title='<{$smarty.const._DELETE}>'><{$smarty.const._DELETE}></a>
        <{/if}>
    </div>
</div>
