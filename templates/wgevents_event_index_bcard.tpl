<div class="col-xs-12 col-sm-6 card">
    <img class="card-img-top" src='<{$wgevents_upload_eventlogos_url|default:false}><{$event.submitter}>/<{$event.logo}>' alt='<{$event.name}>''>
    <div class="card-body">
        <h5 class="card-title"><{$event.name}></h5>
        <{if $event.allday_single|default:false}>
            <p class="card-text"><{$event.datefrom_text}></p>
        <{else}>
            <p class="card-text"><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}>: <{$event.datefrom_text}></p>
            <p class="card-text"><{$smarty.const._MA_WGEVENTS_EVENT_DATETO}>: <{$event.dateto_text}></p>
        <{/if}>
    </div>
    <div class="card-body">
        <a class='btn btn-success wge-btn' href='event.php?op=show&amp;id=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_DETAILS}>'><{$smarty.const._MA_WGEVENTS_DETAILS}></a>
    </div>
</div>

