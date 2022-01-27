<style>
    .wge-block-underline {
        border-bottom:1px solid #ccc;
    }
    .wge-block-coming {
        margin-top:20px;
    }
</style>

<div class="row">
    <div class="col-12 center"><span class="wg-cal-navbar-month"><{$monthNav|default:''}> <{$yearNav|default:''}></span></div>
</div>
<div class="row">
    <div class="col-12"><{$events_calendar}></div>
</div>
<{if $events_list|default:false}>
    <h3 class="wge-block-coming center"><{$smarty.const._MB_WGEVENTS_CAL_COMING_EVENTS}></h3>
    <div class="row wge-block-underline">
        <div class="col-xs-12 col-sm-7 center bold"><{$smarty.const._MA_WGEVENTS_EVENT_NAME}></div>
        <div class="col-xs-12 col-sm-5 center bold"><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}></div>
    </div>
    <{foreach item=event from=$events_list}>
        <div class="row wge-block-underline">
            <div class="col-xs-12 col-sm-2">
                <img class="img-responsive img-fluid" src='<{$wgevents_upload_eventlogos_url|default:false}><{$event.ev_submitter}>/<{$event.logo}>' alt='<{$event.name}>' >
            </div>
            <div class="col-xs-12 col-sm-5"><{$event.name}></div>
            <div class="col-xs-12 col-sm-5"><{$event.datefrom}></div>
        </div>
    <{/foreach}>
<{/if}>