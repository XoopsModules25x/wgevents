<{if count($block)}>
    <{foreach item=event from=$block}>
        <div class='row wge-block-row'>
            <div class='col-xs-4 col-4 center'>
                <{if $event.logoExist|default:false}>
                    <img class="img-responsive img-fluid wge-event-block-img" src="<{$wgevents_upload_eventlogos_url|default:false}>/<{$event.submitter}>/<{$event.logo}>" alt="<{$event.name}>" >
                <{/if}>
            </div>
            <div class='col-xs-8 col-8 left'>
                <h4><{$event.name}></h4>
                <p class="wge-block-date"><{$event.datefrom_text|default:''}></p>
                <p class="wge-block-desc"><{$event.desc_short_user|default:''}></p>
                <p><a class="wge-block-btn-show" href='<{$wgevents_url|default:false}>/event.php?op=show&amp;id=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_EVENT_DETAILS}>'><{$smarty.const._MA_WGEVENTS_EVENT_DETAILS}></a></p>
            </div>
        </div>
    <{/foreach}>
<{/if}>
<div class="col-xs-12 col-12 center wge-block-footer"><a class="btn btn-primary" href='<{$wgevents_url|default:false}>/event.php?op=list' title='<{$smarty.const._MB_WGEVENTS_EVENT_SHOWMORE}>'><{$smarty.const._MB_WGEVENTS_EVENT_SHOWMORE}></a></div>
