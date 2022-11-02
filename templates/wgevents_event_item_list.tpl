<div id='evId_<{$event.id}>' class='wge-panel'>
    <div class='panel-heading center wge-eventheader'>
        <h4>
            <span><{$event.name}></span>
            <{if $event.identifier|default:false}><span class="wge-identifier"><{$event.identifier}></span><{/if}>
            <{if $event.catlogo|default:false}>
                <span class="pull-right wge-event-catlogo-cont">
                    <img class="wge-event-catlogo" src='<{$wgevents_upload_catlogos_url|default:false}><{$event.catlogo}>' alt='<{$event.catname}>' title='<{$event.catname}>'>
                    <{foreach item=subcat from=$event.subcats_arr name=subcats}>
                        <img class="wge-event-catlogo" src='<{$wgevents_upload_catlogos_url|default:false}><{$subcat.logo}>' alt='<{$subcat.name}>' title='<{$subcat.name}>'>
                    <{/foreach}>
                </span>
            <{/if}>
        </h4>
    </div>
    <div class='panel-body'>
        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <img class="img-responsive img-fluid" src='<{$wgevents_upload_eventlogos_url|default:false}><{$event.submitter}>/<{$event.logo}>' alt='<{$event.name_clean|default:''}>' title='<{$event.name_clean|default:''}>' >
            </div>
            <div class="col-xs-12 col-sm-6 wge-panel-details1 expander"><{$event.desc_text}></div>
            <{if $event.allday_single|default:false}>
                <div class="col-xs-12 col-sm-4 wge-panel-details2 right">
                    <p><{$event.datefrom_text}></p>
                </div>
            <{else}>
                <div class="col-xs-12 col-sm-4 wge-panel-details2 right">
                    <p><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}>: <{$event.datefrom_text}><br>
                        <{$smarty.const._MA_WGEVENTS_EVENT_DATETO}>: <{$event.dateto_text}></p>
                </div>
            <{/if}>
        </div>
    </div>
    <div class='panel-foot row'>
        <div class='col-sm-5'>
            <{if $event.regmax|default:false && $event.nb_registrations|default:0 > 0}>
                <div class="progress wge-progress" <{if $event.regcurrent_tip|default:false}>title="<{$event.regcurrent_text}>"<{/if}>>
                    <div <{if $event.regcurrent_tip|default:false}>title="<{$event.regcurrent_text}>"<{/if}> class="progress-bar progress-bar-<{$event.regcurrentstate|default:''}> " role="progressbar" aria-valuenow="<{$event.regpercentage|default:100}>"
                        aria-valuemin="0" aria-valuemax="100" style="width:<{$event.regpercentage}>%">
                        <span class="wge-progress-text"><{$event.regcurrent}></span>
                    </div>
                </div>
                <{if $event.regpercentage|default:0 >= 100 && $event.register_listwait|default:0 == 1}><p class="wge-reg-list-full"><{$smarty.const._MA_WGEVENTS_REGISTRATIONS_FULL_LISTWAIT}></p><{/if}>
            <{/if}>
        </div>
        <div class='col-sm-7 right'>
            <{if $event.locked|default:false}>
            <span class="badge badge-danger wge-badge-index"><{$smarty.const._MA_WGEVENTS_STATUS_LOCKED}></span>
            <{/if}>
            <{if $event.canceled|default:false}>
            <span class="badge badge-danger wge-badge-index"><{$smarty.const._MA_WGEVENTS_STATUS_CANCELED}></span>
            <{/if}>
            <span class='col-sm-12'>
                <{if $showItem|default:''}>
                    <a class='btn btn-success right wge-btn' href='event.php?op=list&amp;filter=<{$filter}>&amp;start=<{$start}>&amp;limit=<{$limit}>#evId_<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LIST}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LIST}></a>
                <{else}>
                    <a class='btn btn-success right wge-btn' href='event.php?op=show&amp;id=<{$event.id}>&amp;filter=<{$filter}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._MA_WGEVENTS_DETAILS}>'><{$smarty.const._MA_WGEVENTS_DETAILS}></a>
                <{/if}>
                <{if $event.register_use|default:0 > 0}>
                    <a class='btn btn-primary wge-btn' href='registration.php?op=listeventmy&amp;redir=listeventmy&amp;evid=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_GOTO}>'><{$smarty.const._MA_WGEVENTS_REGISTRATION_GOTO}></a>
                <{/if}>
                <{if $event.permEdit|default:''}>
                    <a class='btn btn-primary right wge-btn' href='event.php?op=edit&amp;id=<{$event.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._EDIT}>'><{$smarty.const._EDIT}></a>
                    <a class='btn btn-primary right wge-btn' href='event.php?op=clone&amp;id_source=<{$event.id}>' title='<{$smarty.const._CLONE}>'><{$smarty.const._CLONE}></a>
                    <a class='btn btn-danger right wge-btn' href='event.php?op=delete&amp;id=<{$event.id}>' title='<{$smarty.const._DELETE}>'><{$smarty.const._DELETE}></a>
                <{/if}>
            </span>
        </div>
    </div>
</div>
