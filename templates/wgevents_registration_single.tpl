<div class="panel wge-event-single-panel">
    <h3 class="wge-event-single-header"><{$smarty.const._MA_WGEVENTS_REGISTRATION_DETAILS}></h3>
    <div class="row">
        <{if $event.logoExist|default:false}>
            <div class="col-xs-3">
                <img class="img-responsive img-fluid" src='<{$wgevents_upload_eventlogos_url|default:false}>/<{$event.submitter}>/<{$event.logo}>' alt='<{$event.name_clean|default:''}>' title='<{$event.name_clean|default:''}>' >
            </div>
            <div class="col-xs-9">
        <{else}>
            <div class="col-xs-12">
        <{/if}>
            <p class="wge-event-single-p"><{$smarty.const._MA_WGEVENTS_REGISTRATION_EVID}>: <{$event.name|default:''}></p>
            <p class="wge-event-single-p"><{$smarty.const._MA_WGEVENTS_EVENT_DATE}>: <{$event.datefromto_text|default:''}></p>
            <p class="wge-event-single-p"><{$smarty.const._MA_WGEVENTS_EVENT_LOCATION}>: <{$event.location_text_user|default:''}></p>
        </div>
    </div>
    <{if $registration.salutation|default:0 > 0}>
        <div class="row">
            <div class="col-xs-3"><{$smarty.const._MA_WGEVENTS_REGISTRATION_SALUTATION}></div>
            <div class="col-xs-9"><{$registration.salutation_text|default:''}></div>
        </div>
    <{/if}>
    <div class="row">
        <div class="col-xs-3"><{$smarty.const._MA_WGEVENTS_REGISTRATION_FIRSTNAME}></div>
        <div class="col-xs-9"><{$registration.firstname|default:''}></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><{$smarty.const._MA_WGEVENTS_REGISTRATION_LASTNAME}></div>
        <div class="col-xs-9"><{$registration.lastname|default:''}></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><{$smarty.const._MA_WGEVENTS_REGISTRATION_EMAIL}></div>
        <div class="col-xs-9"><{$registration.email|default:''}></div>
    </div>
    <{if $registration.questions|default:0 > 0}>
        <div class="row">
            <div class="col-xs-3"><{$smarty.const._MA_WGEVENTS_QUESTIONS}></div>
            <div class="col-xs-9">
                <{foreach item=qa from=$registration.question_answer}>
                    <{$qa.caption|default:''}>: <{$qa.answer|default:''}></br>
                <{/foreach}>
            </div>
        </div>
    <{/if}>
    <div class="row">
        <div class="col-xs-3"><{$smarty.const._MA_WGEVENTS_STATUS}></div>
        <div class="col-xs-9"><{$registration.status_text|default:''}></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL}></div>
        <div class="col-xs-9"><{$registration.financial_text|default:''}></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><{$smarty.const._MA_WGEVENTS_REGISTRATION_PAIDAMOUNT}></div>
        <div class="col-xs-9"><{$registration.paidamount_text|default:''}></div>
    </div>
    <{if $event.register_listwait|default:0 > 0}>
        <div class="row">
            <div class="col-xs-3"><{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT}></div>
            <div class="col-xs-9"><{if $registration.listwait|default:0 > 0}><{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT_Y}><{else}><{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT_N}><{/if}></div>
        </div>
    <{/if}>
    <div class="row">
        <div class="col-xs-3"><{$smarty.const._MA_WGEVENTS_REGISTRATION_DATECREATED}></div>
        <div class="col-xs-9"><{$registration.datecreated_text|default:''}></div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <a class='btn btn-success wge-btn pull-right' href='registration.php?op=edit&amp;id=<{$registration.id}>&amp;verifkey=<{$verifKey}>' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_EDIT}>'><{$smarty.const._MA_WGEVENTS_REGISTRATION_EDIT}></a>
            <a class='btn btn-success wge-btn pull-right' href='event.php?op=show&amp;id=<{$event.id}>' title='<{$smarty.const._MA_WGEVENTS_EVENT_DETAILS}>'><{$smarty.const._MA_WGEVENTS_EVENT_DETAILS}></a>
        </div>
    </div>
</div>
<{if $error|default:''}>
    <{$error|default:false}>
<{/if}>
