<{include file='db:wgevents_header.tpl' }>

<!-- start display categories -->
<{if $event_displaycats|default:'' != 'none'}>
    <{if $categoriesCount|default:0 > 0}>
        <div class="col-12 wge-filter-cat-<{$event_displaycats}>">
            <{if $event_displaycats|default:'' == 'form'}>
                <{include file="db:wgevents_category_index_form.tpl"}>
            <{else}>
                <!-- Start cat loop -->

                <{foreach item=category from=$categories name=categories}>
                    <{include file="db:wgevents_category_index_$event_displaycats.tpl" category=$category}>
                <{/foreach}>
                <!-- End cat loop -->
            <{/if}>
        </div>
    <{/if}>
<{/if}>
<!-- end display categories -->

<{if $error|default:''}>
    <div class="col-12 center wge-error-msg"><{$error|default:false}></div>
<{/if}>

<{if $gmapsShowList|default:false && $gmapsEnableEvent|default:false && $gmapsPositionList|default:'none' == 'top'}>
    <div class="row wge-row1">
        <span class='col-sm-12 center'><{include file='db:wgevents_gmaps_show.tpl' }></span>
    </div>
<{/if}>

<{if $eventsCount|default:0 > 0}>
    <div class='col-12'>
        <{foreach item=event from=$events name=event}>
            <{if $showList|default:false}>
                <{include file='db:wgevents_event_item_list.tpl' }>
            <{else}>
                <{include file='db:wgevents_event_item_details.tpl' }>
            <{/if}>
        <{/foreach}>
    </div>
<{else}>
    <{if $noEventsReason|default:false}><div class="col-12 center wge-error-msg"><{$noEventsReason}></div><{/if}>
<{/if}>

<{if $gmapsShowList|default:false && $gmapsEnableEvent|default:false && $gmapsPositionList|default:'none' == 'bottom'}>
    <div class="row wge-row1">
        <span class='col-sm-12 center'><{include file='db:wgevents_gmaps_show.tpl' }></span>
    </div>
<{/if}>

<{if $showBtnComing|default:false}>
    <div class="row wge-row1">
        <span class='col-sm-12 center'><a class='btn btn-success' href='event.php?op=list&amp;filter=<{$filter}>' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LISTCOMING}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LISTCOMING}></a></span>
    </div>
<{/if}>

<{if $showBtnPast|default:false}>
    <div class="row wge-row1">
        <span class='col-sm-12 center'><a class='btn btn-success' href='event.php?op=past&amp;filter=<{$filter}>' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LISTPAST}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LISTPAST}></a></span>
    </div>
<{/if}>

<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>

<{if $gmapsModal|default:false}>
    <{include file='db:wgevents_gmaps_getcoords_modal.tpl' }>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>

<script>
    $(document).ready(function() {
        var opts = {
            slicePoint: <{$user_maxchar|default:100}>,
            expandText:'<{$smarty.const._MA_WGEVENTS_READMORE}>',
            moreLinkClass:'btn btn-success btn-sm',
            expandSpeed: 500,
            userCollapseText:'<{$smarty.const._MA_WGEVENTS_READLESS}>',
            lessLinkClass:'btn btn-success btn-sm',
            expandEffect: 'slideDown',
            collapseEffect: 'slideUp',
            collapseSpeed: 500,
        };

        $('div.expander').expander(opts);
    });
</script>
