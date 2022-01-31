<{include file='db:wgevents_header.tpl' }>

<!-- Start index list -->

<h3 class='center'><{$smarty.const._MA_WGEVENTS_TITLE}></h3>
<p><{$index_header|default:''}></p>

<div class="row">
    <ul class='menu text-center'>
        <li><a href='<{$wgevents_url|default:''}>'><{$smarty.const._MA_WGEVENTS_INDEX}></a></li>
        <li><a href='<{$wgevents_url|default:''}>/event.php'><{$smarty.const._MA_WGEVENTS_EVENTS}></a></li>
    </ul>
</div>

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
            <{include file='db:wgevents_event_index_list.tpl' event=$event}>
        </div>
    <{/foreach}>
    <!-- End new link loop -->
<{else}>
    <{$smarty.const._MA_WGEVENTS_INDEX_THEREARENT}>
<{/if}>
<{if $showBtnComing|default:false}>
    <div class="row wge-spacer1">
        <span class='col-sm-12 center'><a class='btn btn-primary' href='index.php?op=coming' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LISTCOMING}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LISTCOMING}></a></span>
    </div>
<{/if}>
<{if $showBtnPast|default:false}>
    <div class="row wge-spacer1">
        <span class='col-sm-12 center'><a class='btn btn-primary' href='index.php?op=past' title='<{$smarty.const._MA_WGEVENTS_EVENTS_LISTPAST}>'><{$smarty.const._MA_WGEVENTS_EVENTS_LISTPAST}></a></span>
    </div>
<{/if}>
<{if $errorPerm|default:false}>
    <div class="row wge-spacer1">
        <span class='col-sm-12 center'><{$errorPerm}></span>
    </div>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
