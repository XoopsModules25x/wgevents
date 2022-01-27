<i id='evId_<{$event.ev_id}>'></i>
<div class='panel-heading wge-heading'><{$event.name}></div>
<div class='panel-body'>
    <div class="row wge-spacer1">
        <div class="col-xs-12 col-sm-5">
            <img class="img-responsive img-fluid" src='<{$wgevents_upload_eventlogos_url|default:false}><{$event.ev_submitter}>/<{$event.logo}>' alt='<{$event.name}>' >
        </div>
        <div class="col-xs-12 col-sm-6 wge-panel-details1"><{$event.desc}></div>
    </div>
    <div class="row wge-spacer1">
        <div class="col-xs-12 col-sm-3"><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}>: </div>
        <div class="col-xs-12 col-sm-9"><{$event.datefrom}></div>
        <div class="col-xs-12 col-sm-3"><{$smarty.const._MA_WGEVENTS_EVENT_DATETO}>: </div>
        <div class="col-xs-12 col-sm-9"><{$event.dateto}></div>
        <{if $event.contact|default:false}>
            <div class="col-xs-12 col-sm-3"><{$smarty.const._MA_WGEVENTS_EVENT_CONTACT}>: </div>
            <div class="col-xs-12 col-sm-9"><{$event.contact}></div>
        <{/if}>
        <{if $event.email|default:false}>
            <div class="col-xs-12 col-sm-3"><{$smarty.const._MA_WGEVENTS_EVENT_EMAIL}>: </div>
            <div class="col-xs-12 col-sm-9"><{$event.email}></div>
        <{/if}>
        <{if $event.location|default:false}>
            <div class="col-xs-12 col-sm-3"><{$smarty.const._MA_WGEVENTS_EVENT_LOCATION}>: </div>
            <div class="col-xs-12 col-sm-9"><{$event.location}></div>
        <{/if}>
        <{if $event.fee|default:false}>
            <div class="col-xs-12 col-sm-3"><{$smarty.const._MA_WGEVENTS_EVENT_FEE}>: </div>
            <div class="col-xs-12 col-sm-9"><{$event.fee_text}></div>
        <{/if}>
        <{if $event.permEdit|default:false && $event.status|default:false}>
        <div class="col-xs-12 col-sm-3"><{$smarty.const._MA_WGEVENTS_STATUS}>: </div>
        <div class="col-xs-12 col-sm-9"><{$event.status_text}></div>
        <{/if}>
    </div>
    <{if $event.ev_register_use|default:''}>
        <div class="row wge-spacer1">
            <div class="col-12"><{$smarty.const._MA_WGEVENTS_REGISTRATION_DETAILS}></div>
            <div class="col-xs-12 col-sm-5"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_FROM}>: </div>
            <div class="col-xs-12 col-sm-7"><{$event.register_from}></div>
            <div class="col-xs-12 col-sm-5"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_TO}>: </div>
            <div class="col-xs-12 col-sm-7"><{$event.register_to}></div>
            <div class="col-xs-12 col-sm-5"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_MAX}>: </div>
            <div class="col-xs-12 col-sm-7"><{$event.register_max_text}></div>
            <{if $event.permEdit|default:false}>
                <div class="col-xs-12 col-sm-5"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_LISTWAIT}>: </div>
                <div class="col-xs-12 col-sm-7"><{$event.register_listwait}></div>
                <div class="col-xs-12 col-sm-5"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT}>: </div>
                <div class="col-xs-12 col-sm-7"><{$event.register_autoaccept}></div>
                <div class="col-xs-12 col-sm-5"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_NOTIFY}>: </div>
                <div class="col-xs-12 col-sm-7"><{$event.register_notify_user}></div>
            <{/if}>
        </div>

            <div class="row wge-spacer1">
                <div class="col-xs-12 col-sm-3"><{$smarty.const._MA_WGEVENTS_REGISTRATIONS_CURR}>: </div>
                <div class="col-xs-12 col-sm-4">
                    <{if $event.regcurrentstate|default:''}><span class="badge badge-<{$event.regcurrentstate}> wge-badge"><{/if}>
                        <{$event.regcurrent|default:''}>
                    <{if $event.regcurrentstate|default:''}></span><{/if}>
                    <{if $event.regListwait|default:''}><p class="wge-p-small"><{$event.regListwait}></p><{/if}>
                </div>
                <div class="col-xs-12 col-sm-5 right">
                    <{if $event.permEdit|default:false && $event.nb_registrations|default:0 > 0}>
                        <a class='btn btn-success wge-btn' href='registrations.php?op=listeventall&amp;redir=listeventall&amp;reg_evid=<{$event.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_REGISTRATIONS_LIST}>'><{$smarty.const._MA_WGEVENTS_REGISTRATIONS_LIST}></a>
                    <{/if}>
                </div>
            </div>
        <{if $event.permEdit|default:false || $permApprove|default:false}>
            <div class="row">
                <div class="col-xs-12 col-sm-3"><{$smarty.const._MA_WGEVENTS_QUESTIONS}>: </div>
                <div class="col-xs-12 col-sm-4"><{$event.nb_questions}></div>
                <div class="col-xs-12 col-sm-5 right">
                    <{if $event.nb_questions|default:0 > 0 || $event.id|default:0 > 0 }>
                        <a class='btn btn-success wge-btn' href='questions.php?op=list&amp;que_evid=<{$event.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_QUESTIONS}>'><{$smarty.const._MA_WGEVENTS_GOTO_QUESTIONS}></a>
                    <{else}>
                        <a class='btn btn-success wge-btn' href='questions.php?op=newset&amp;que_evid=<{$event.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_QUESTIONS}>'><{$smarty.const._MA_WGEVENTS_GOTO_QUESTIONS}></a>
                    <{/if}>
                </div>
            </div>
        <{/if}>
    <{/if}>
</div>
<div class='panel-foot'>
    <div class='col-sm-12 right'>
        <{if $showItem|default:false}>
            <a class='btn btn-success right wge-btn' href='events.php?op=list&amp;start=<{$start}>&amp;limit=<{$limit}>#evId_<{$event.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LIST}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LIST}></a>
        <{else}>
            <a class='btn btn-success right wge-btn' href='events.php?op=show&amp;ev_id=<{$event.ev_id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._MA_WGEVENTS_DETAILS}>'><{$smarty.const._MA_WGEVENTS_DETAILS}></a>
        <{/if}>
        <{if $event.ev_register_use|default:'' &&  $permRegister|default:false}>
            <a class='btn btn-primary right wge-btn <{if !$event.regenabled|default:false}>disabled<{/if}>' href='registrations.php?op=listmyevent&amp;redir=listmyevent&amp;reg_evid=<{$event.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_REGISTRATION}>'><{$smarty.const._MA_WGEVENTS_GOTO_REGISTRATION}></a>
        <{/if}>
        <{if $event.permEdit|default:false || $permApprove|default:false}>
            <br><a class='btn btn-primary right wge-btn' href='events.php?op=edit&amp;ev_id=<{$event.ev_id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._EDIT}>'><{$smarty.const._EDIT}></a>
            <a class='btn btn-primary right wge-btn' href='events.php?op=clone&amp;ev_id_source=<{$event.ev_id}>' title='<{$smarty.const._CLONE}>'><{$smarty.const._CLONE}></a>
            <br><a class='btn btn-danger right wge-btn' href='events.php?op=cancel&amp;ev_id=<{$event.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_EVENT_CANCEL}>'><{$smarty.const._MA_WGEVENTS_EVENT_CANCEL}></a>
            <a class='btn btn-danger right wge-btn' href='events.php?op=delete&amp;ev_id=<{$event.ev_id}>' title='<{$smarty.const._DELETE}>'><{$smarty.const._DELETE}></a>
        <{/if}>
    </div>
</div>
