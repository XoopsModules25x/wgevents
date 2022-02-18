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

<div class='wge-spacer2 center'><h3 class="center"><{$listDescr|default:''}></h3></div>
<{if $eventsCount|default:0 > 0}>
    <!-- Start new link loop -->
    <{foreach item=event from=$events name=event}>
        <div class='top center'>
            <{if $index_displaytype|default:'' == 'card'}>
                <{include file='db:wgevents_event_index_card.tpl' event=$event}>
            <{else}>
                <{include file='db:wgevents_event_index_list.tpl' event=$event}>
            <{/if}>
        </div>
    <{/foreach}>
    <!-- End new link loop -->
<{else}>
    <{$smarty.const._MA_WGEVENTS_INDEX_THEREARENT}>
<{/if}>
<{if $showBtnComing|default:false}>
    <div class="row wge-row1">
        <span class='col-sm-12 center'><a class='btn btn-success' href='index.php?op=coming' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LISTCOMING}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LISTCOMING}></a></span>
    </div>
<{/if}>
<{if $showBtnPast|default:false}>
    <div class="row wge-row1">
        <span class='col-sm-12 center'><a class='btn btn-success' href='index.php?op=past' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LISTPAST}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LISTPAST}></a></span>
    </div>
<{/if}>
<{if $errorPerm|default:false}>
    <div class="row wge-row1">
        <span class='col-sm-12 center'><{$errorPerm}></span>
    </div>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
