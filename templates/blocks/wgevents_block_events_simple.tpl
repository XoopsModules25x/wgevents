<{if count($block)}>
    <{foreach item=event from=$block}>
        <div class='row wge-block-row'>
            <div class='col-xs-4 col-4 center'><img class="img-responsive img-fluid" src="<{$wgevents_upload_eventlogos_url|default:false}>/<{$event.submitter}>/<{$event.logo}>" alt="<{$event.name}>" ></div>
            <div class='col-xs-8 col-8 left'>
                <p><{$event.name}></p>
                <p><{$event.datefrom_text}></p>
                <p><a href='<{$wgevents_url|default:false}>/event.php?op=show&amp;id=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_EVENT_DETAILS}>'><{$smarty.const._MA_WGEVENTS_EVENT_DETAILS}></a></p>
            </div>
        </div>
    <{/foreach}>
<{/if}>
<div class="col-xs-12 col-12 center"><a class="btn btn-success" href='<{$wgevents_url|default:false}>/event.php?op=list' title='<{$smarty.const._MB_WGEVENTS_EVENT_SHOWMORE}>'><{$smarty.const._MB_WGEVENTS_EVENT_SHOWMORE}></a></div>
