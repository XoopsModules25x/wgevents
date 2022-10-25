<{include file='db:wgevents_header.tpl' }>

<h3><{$smarty.const._MA_WGEVENTS_CAL_ITEMS}></h3>

<{if $formFilter|default:''}><{$formFilter}><{/if}>

<{if $gmapsShowList|default:false && $gmapsEnableCal|default:false && $gmapsPositionList|default:'none' == 'top'}>
    <div class="row wge-row1">
        <span class='col-sm-12 center'><{include file='db:wgevents_gmaps_show.tpl' }></span>
    </div>
<{/if}>

<{if $events_calendar|default:''}>
    <div class="row wg-cal-navbar">
        <div class="col-12 center">
            <a class="wg-cal-navbar-link" href="calendar.php?filterFrom=<{$filterFromPrevY}>&amp;filterTo=<{$filterToPrevY}>&amp;<{$otherParams|default:''}>"><i class="fa fa-angle-double-left" title="<{$smarty.const._MA_WGEVENTS_CAL_PREVYEAR}>"></i></a>
            <a class="wg-cal-navbar-link" href="calendar.php?filterFrom=<{$filterFromPrevM}>&amp;filterTo=<{$filterToPrevM}>&amp;<{$otherParams|default:''}>"><i class="fa fa-angle-left" title="<{$smarty.const._MA_WGEVENTS_CAL_PREVMONTH}>"></i></a>
            <span class="wg-cal-navbar-month"><{$monthNav|default:''}> <{$yearNav|default:''}></span>
            <a class="wg-cal-navbar-link" href="calendar.php?filterFrom=<{$filterFromNextM}>&amp;filterTo=<{$filterToNextM}>&amp;<{$otherParams|default:''}>"><i class="fa fa-angle-right" title="<{$smarty.const._MA_WGEVENTS_CAL_NEXTMONTH}>"></i></a>
            <a class="wg-cal-navbar-link" href="calendar.php?filterFrom=<{$filterFromNextY}>&amp;filterTo=<{$filterToNextY}>&amp;<{$otherParams|default:''}>"><i class="fa fa-angle-double-right" title="<{$smarty.const._MA_WGEVENTS_CAL_NEXTYEAR}>"></i></a>
        </div>
    </div>
    <div class="row">
        <div class="col-12"><{$events_calendar}></div>
    </div>
<{/if}>

<{if $formGoto|default:''}>
    <div class="col-12 center wge-form-goto">
        <{$formGoto}>
    </div>
<{/if}>

<{if $gmapsShowList|default:false && $gmapsEnableCal|default:false && $gmapsPositionList|default:'none' == 'bottom'}>
    <div class="row wge-row1">
        <span class='col-sm-12 center'><{include file='db:wgevents_gmaps_show.tpl' }></span>
    </div>
<{/if}>

<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <{$error|default:false}>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
