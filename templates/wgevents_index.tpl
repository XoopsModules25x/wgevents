<{include file='db:wgevents_header.tpl' }>

<!-- Start index list -->
<{if $index_header|default:''}>
    <div class="row">
        <{$index_header}>
    </div>
<{/if}>

<{if $adv|default:''}>
    <div class="row">
        <{$adv}>
    </div>
<{/if}>
<!-- End index list -->

<!-- start display categories -->
<{if $index_displaycats|default:'' != 'none'}>
    <{if $categoriesCount|default:0 > 0}>
        <div class="col-12 wge-filter-cat-<{$index_displaycats}>">
            <{if $index_displaycats|default:'' == 'form'}>
                <{include file="db:wgevents_category_index_form.tpl"}>
            <{else}>
                <!-- Start cat loop -->
                <{foreach item=category from=$categories name=categories}>
                <{include file="db:wgevents_category_index_$index_displaycats.tpl" category=$category}>
                <{/foreach}>
                <!-- End cat loop -->
            <{/if}>
        </div>
    <{else}>
        <div class="col-12 center wge-error-msg"><{$smarty.const._MA_WGEVENTS_INDEX_THEREARENT_CATS}></div>
    <{/if}>
<{/if}>
<!-- end display categories -->

<{if $index_displayevents|default:'' != 'none'}>
    <div class='wge-spacer2 center'><h3 class="center"><{$listDescr|default:''}></h3></div>
    <{if $gmapsShowList|default:false && $gmapsEnableEvent|default:false && $gmapsPositionList|default:'none' == 'top'}>
        <div class="row wge-row1">
            <span class='col-sm-12 center'><{include file='db:wgevents_gmaps_show.tpl' }></span>
        </div>
    <{/if}>
    <{if $eventsCount|default:0 > 0}>
        <!-- Start new link loop -->
        <{foreach item=event from=$events name=event}>
            <div class='top center'>
                <{include file="db:wgevents_event_index_$index_displayevents.tpl" event=$event}>
            </div>
        <{/foreach}>
        <!-- End new link loop -->
    <{else}>
        <div class="col-12 center wge-error-msg"><{$smarty.const._MA_WGEVENTS_INDEX_THEREARENT_EVENTS}></div>
    <{/if}>
    <{if $gmapsShowList|default:false && $gmapsEnableEvent|default:false && $gmapsPositionList|default:'none' == 'bottom'}>
        <div class="row wge-row1">
            <span class='col-sm-12 center'><{include file='db:wgevents_gmaps_show.tpl' }></span>
        </div>
    <{/if}>
    <{if $showBtnComing|default:false}>
        <div class="row wge-row1">
            <span class='col-sm-12 center'><a class='btn btn-success' href='index.php?op=coming&amp;cat_id=<{$categoryCurrent}>' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LISTCOMING}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LISTCOMING}></a></span>
        </div>
    <{/if}>
    <{if $showBtnPast|default:false}>
        <div class="row wge-row1">
            <span class='col-sm-12 center'><a class='btn btn-success' href='index.php?op=past&amp;cat_id=<{$categoryCurrent}>' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LISTPAST}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LISTPAST}></a></span>
        </div>
    <{/if}>
    <{if $errorPerm|default:false}>
        <div class="row wge-row1">
            <span class='col-sm-12 center'><{$errorPerm}></span>
        </div>
    <{/if}>
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

