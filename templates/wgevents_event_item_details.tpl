<i id='evId_<{$event.id}>'></i>
<div class='panel-heading center wge-eventheader'><h4><span><{$event.name}></span></h4></div>
<div class='panel-body'>
    <div class="row wge-row1">
        <div class="col-xs-12 col-sm-5">
            <img class="img-responsive img-fluid" src='<{$wgevents_upload_eventlogos_url|default:false}><{$event.submitter}>/<{$event.logo}>' alt='<{$event.name}>' >
        </div>
        <div class="col-xs-12 col-sm-6 wge-panel-details1"><{$event.desc_text}></div>
    </div>
    <div class="wge-row1">
        <div class="row">
            <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}>: </div>
            <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.datefrom_text}></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_DATETO}>: </div>
            <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.dateto_text}></div>
        </div>
        <{if $event.contact|default:false}>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_CONTACT}>: </div>
                <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.contact_text_user}></div>
            </div>
        <{/if}>
        <{if $event.email|default:false}>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_EMAIL}>: </div>
                <div class="col-xs-12 col-sm-9 col-lg-10">
                    <{$event.email}>
                    <a class="btn btn-primary" href="mailto:<{$event.email}>?subject=<{$smarty.const._MA_WGEVENTS_EVENT_EMAIL_SENDREQUEST}><{$event.name}>"><{$smarty.const._MA_WGEVENTS_EVENT_EMAIL_SENDTO}></a>
                </div>
            </div>
        <{/if}>
        <{if $event.location|default:false}>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_LOCATION}>: </div>
                <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.location}></div>
            </div>
        <{/if}>
        <{if $event.fee|default:false}>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_FEE}>: </div>
                <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.fee_text}></div>
            </div>
        <{/if}>
        <{if $event.permEdit|default:false && $event.status|default:false}>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_STATUS}>: </div>
                <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.status_text}></div>
            </div>
        <{/if}>
    </div>
    <{if $event.register_use|default:''}>
        <div class="wge-row1">
            <div class="row wge-row2">
                <div class="col-12"><{$smarty.const._MA_WGEVENTS_REGISTRATION_DETAILS}></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_FROM}>: </div>
                <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.register_from_text}></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_TO}>: </div>
                <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.register_to_text}></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_MAX}>: </div>
                <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.register_max_text}></div>
            </div>

            <{if $event.permEdit|default:false}>
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_LISTWAIT}>: </div>
                    <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.register_listwait_text}></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_AUTOACCEPT}>: </div>
                    <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.register_autoaccept_text}></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_NOTIFY}>: </div>
                    <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.register_notify_user}></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_EVENT_REGISTER_FORCEVERIF}>: </div>
                    <div class="col-xs-12 col-sm-9 col-lg-10"><{$event.register_forceverif_text}></div>
                </div>
            <{/if}>
        </div>

        <div class="row wge-row1">
            <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_REGISTRATIONS_CURR}>: </div>
            <div class="col-xs-12 col-sm-4 col-lg-4">
                <{if $event.regcurrentstate|default:''}><span class="badge badge-<{$event.regcurrentstate}> wge-badge"><{/if}>
                    <{$event.regcurrent|default:''}>
                <{if $event.regcurrentstate|default:''}></span><{/if}>
                <{if $event.regListwait|default:''}><p class="wge-p-small"><{$event.regListwait}></p><{/if}>
            </div>
            <div class="col-xs-12 col-sm-5 col-lg-6 right">
                <{if $event.permEdit|default:false && $event.nb_registrations|default:0 > 0}>
                    <a class='btn btn-success wge-btn' href='registration.php?op=listeventall&amp;redir=listeventall&amp;evid=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_REGISTRATIONS_LIST}>'><{$smarty.const._MA_WGEVENTS_REGISTRATIONS_LIST}></a>
                    <a class='btn btn-primary wge-btn' href='output.php?op=reg_all&amp;output_type=xlsx&amp;id=<{$event.id}>&amp;redir=<{$redir}>' title='<{$smarty.const._MA_WGEVENTS_OUTPUT_EXCEL}>'><{$smarty.const._MA_WGEVENTS_OUTPUT_EXCEL}></a>
                <{/if}>
            </div>
        </div>
        <{if $event.permEdit|default:false || $permApprove|default:false}>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-lg-2"><{$smarty.const._MA_WGEVENTS_QUESTIONS}>: </div>
                <div class="col-xs-12 col-sm-4 col-lg-4"><{$event.nb_questions}></div>
                <div class="col-xs-12 col-sm-5 col-lg-6 right">
                    <{if $event.nb_questions|default:0 > 0 || $event.id|default:0 > 0 }>
                        <a class='btn btn-primary wge-btn' href='question.php?op=list&amp;evid=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_QUESTIONS}>'><{$smarty.const._MA_WGEVENTS_GOTO_QUESTIONS}></a>
                    <{else}>
                        <a class='btn btn-success wge-btn' href='question.php?op=newset&amp;evid=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_QUESTIONS}>'><{$smarty.const._MA_WGEVENTS_GOTO_QUESTIONS}></a>
                    <{/if}>
                </div>
            </div>
        <{/if}>
    <{/if}>
</div>
<div class='panel-foot'>
    <div class='col-sm-12 right'>
        <{if $showItem|default:false}>
            <a class='btn btn-success right wge-btn' href='event.php?op=list&amp;start=<{$start}>&amp;limit=<{$limit}>#evId_<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LIST}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LIST}></a>
        <{else}>
            <a class='btn btn-success right wge-btn' href='event.php?op=show&amp;id=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._MA_WGEVENTS_DETAILS}>'><{$smarty.const._MA_WGEVENTS_DETAILS}></a>
        <{/if}>
        <{if $event.register_use|default:'' &&  $permRegister|default:false}>
            <a class='btn btn-primary right wge-btn <{if !$event.regenabled|default:false}>disabled<{/if}>' href='registration.php?op=listmyevent&amp;redir=listmyevent&amp;evid=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_REGISTRATION}>'><{$smarty.const._MA_WGEVENTS_GOTO_REGISTRATION}></a>
        <{/if}>
        <{if $event.permEdit|default:false || $permApprove|default:false}>
            <br><a class='btn btn-primary right wge-btn' href='event.php?op=edit&amp;id=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._EDIT}>'><{$smarty.const._EDIT}></a>
            <a class='btn btn-primary right wge-btn' href='event.php?op=clone&amp;id_source=<{$event.id}>' title='<{$smarty.const._CLONE}>'><{$smarty.const._CLONE}></a>
            <br><a class='btn btn-danger right wge-btn' href='event.php?op=cancel&amp;id=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_EVENT_CANCEL}>'><{$smarty.const._MA_WGEVENTS_EVENT_CANCEL}></a>
            <a class='btn btn-danger right wge-btn' href='event.php?op=delete&amp;id=<{$event.id}>' title='<{$smarty.const._DELETE}>'><{$smarty.const._DELETE}></a>
        <{/if}>
    </div>
</div>
