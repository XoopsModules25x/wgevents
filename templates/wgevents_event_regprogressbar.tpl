<!-- progress bar with percentage inline -->
<{if $event_regprocessbar|default:0 == 1}>
    <style>
        .progress {
            margin-left:5px;
            height: 40px;
            max-width: 300px;
        }
        .wge-progress {
            margin-bottom:0;
        }
        .wge-progress-text {
            margin-top:10px
        }
    </style>
    <{if $event.regmax|default:false && $event.nb_registrations|default:0 > 0}>
        <div class="progress wge-progress" <{if $event.regcurrent_tip|default:false}>title="<{$event.regcurrent_text}>"<{/if}>>
            <div <{if $event.regcurrent_tip|default:false}>title="<{$event.regcurrent_text}>"<{/if}> class="progress-bar progress-bar-<{$event.regcurrentstate|default:''}> " role="progressbar" aria-valuenow="<{$event.regpercentage|default:100}>"
                aria-valuemin="0" aria-valuemax="100" style="width:<{$event.regpercentage}>%">
                <p class="wge-progress-text"><{$event.regpercentage}>%</p>
                </div>
        </div>
        <{if $event.regpercentage|default:0 >= 100 && $event.register_listwait|default:0 == 1}><p class="wge-reg-list-full"><{$smarty.const._MA_WGEVENTS_REGISTRATIONS_FULL_LISTWAIT}></p><{/if}>
    <{/if}>
<{/if}>

<!-- progress bar with text below bar -->
<{if $event_regprocessbar|default:0 == 2}>
    <style>
        .progress {
            margin-left:5px;
            height: 20px;
            max-width: 300px;
        }
        .wge-progress {
            margin-bottom:0;
            height: 20px;
        }
        .wge-progress-text {
            margin-top:10px
        }
    </style>
    <{if $event.regmax|default:false && $event.nb_registrations|default:0 > 0}>
        <div class="progress wge-progress" <{if $event.regcurrent_tip|default:false}>title="<{$event.regcurrent_text}>"<{/if}>>
            <div <{if $event.regcurrent_tip|default:false}>title="<{$event.regcurrent_text}>"<{/if}> class="progress-bar progress-bar-<{$event.regcurrentstate|default:''}> " role="progressbar" aria-valuenow="<{$event.regpercentage|default:100}>"
                aria-valuemin="0" aria-valuemax="100" style="width:<{$event.regpercentage}>%">
            </div>
        </div>
        <div class="wge-progress center"><{$event.regcurrent}></div>
        <{if $event.regpercentage|default:0 >= 100 && $event.register_listwait|default:0 == 1}><p class="wge-reg-list-full"><{$smarty.const._MA_WGEVENTS_REGISTRATIONS_FULL_LISTWAIT}></p><{/if}>
    <{/if}>
<{/if}>

