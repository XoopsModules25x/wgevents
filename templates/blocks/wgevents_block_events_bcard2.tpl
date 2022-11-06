<{if count($block)}>
    <div class="wge-block-bcard-groups">
        <{foreach name=event item=event from=$block}>
            <div class="wge-block-bcard-group <{if $smarty.foreach.event.iteration % 2 == 0}> wge-block-bcard-group-right<{else}> wge-block-bcard-group-left<{/if}> col-xs-12 col-sm-6 wge-block-bcard-item">
                <div class="wge-block-bcard-itemPanel col-xs-12">
                    <div class="wge-block-bcard-itemHeader col-xs-12">
                        <div class="wge-block-bcard-itemTitle">
                            <a href='<{$wgevents_url|default:false}>/event.php?op=show&amp;id=<{$event.id}>' class='wge-block-bcard-name' title='<{$event.name}>'><{$event.name}></a>
                        </div>
                        <div class="wge-block-bcard-itemInfo">
                            <span class="wge-block-bcard-itemInfo-date glyphicon glyphicon-calendar" title="<{$smarty.const._MA_WGEVENTS_EVENT_DATE}>"></span><{$event.datefromto_text}>
                            <{if $event.location}>
                                <br><span class="glyphicon glyphicon-map-marker" title="<{$smarty.const._MA_WGEVENTS_EVENT_LOCATION}>"></span><{$event.location}>
                            <{/if}>
                            <{if $event.catname}>
                                <br><span class="glyphicon glyphicon-tag" title="<{$smarty.const._MA_WGEVENTS_EVENT_CATS}>"></span><{$event.catname}>
                            <{/if}>
                            <{if $event.contact}>
                                <br><span class="glyphicon glyphicon-user" title="<{$smarty.const._MA_WGEVENTS_EVENT_CONTACT}>"></span><{$event.contact}>
                            <{/if}>
                        </div>
                    </div>
                    <{if $event.desc_short_user|default:''}>
                        <div class="wge-block-bcard-itemText col-xs-12">
                            <{$event.desc_short_user}>
                        </div>
                    <{/if}>
                    <div class="wge-block-bcard-itemFooter col-xs-12">
                        <a class='btn btn-primary' href='<{$wgevents_url|default:false}>/event.php?op=show&amp;id=<{$event.id}>' title='<{$event.summary}>'><{$smarty.const._MA_WGEVENTS_EVENT_DETAILS}></a>
                        <{if $block.permEdit|default:false}>
                            <a class='btn btn-primary' href='<{$wgevents_url|default:false}>/event.php?op=edit&amp;id=<{$event.id}>' title='<{$event.summary}>'><{$smarty.const._MA_WGEVENTS_EVENT_EDIT}></a>
                        <{/if}>
                    </div>
                </div>
            </div>
            <{if $smarty.foreach.event.iteration % 2 == 0}>
                <div class="clear"></div>
            <{/if}>
        <{/foreach}>
    </div>
    <div class="clear"></div>
    <div class="wge-block-bcard-footer center">
        <a class="btn btn-primary" href="<{$wgevents_url|default:false}>/event.php?op=list"><{$smarty.const._MB_WGEVENTS_EVENT_SHOWMORE}></a>
        <{if $wgevents_permAdd|default:false}>
            <a class='btn btn-primary' href='<{$wgevents_url|default:false}>/event.php?op=new' title='<{$event.summary}>'><{$smarty.const._MA_WGEVENTS_EVENT_ADD}></a>
        <{/if}>
    </div>
<{/if}>