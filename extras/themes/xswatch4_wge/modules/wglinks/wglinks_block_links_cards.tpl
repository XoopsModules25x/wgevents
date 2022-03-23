<div class="row"><div class="col-xs-12">
    <{foreach name=link item=link from=$block}>
        <div class='<{if $index_style|default:'' == '2cards'}>col-xs-6 col-sm-6<{/if}><{if $index_style|default:'' == '3cards'}>col-xs-6 col-sm-4<{/if}><{if $index_style|default:'' == '4cards'}>col-xs-6 col-sm-3<{/if}><{if $index_style|default:'' == '6cards'}>col-xs-6 col-sm-2<{/if}><{if $index_style|default:'' == '12cards'}>col-xs-6 col-sm-1<{/if}> wglinks-link-panel'>
            <div class='wglinks-link-card'>
                <{if $link.logo|default:false}>
                    <p class="wglinks-link-img">
                        <a href="<{$wglinks_url}>/index.php?link_id=<{$link.id}>" title="<{$link.tooltip|default:''}>" target="_self">
                            <img src="<{$wglinks_upload_url}>/images/links/thumbs/<{$link.logo}>" alt="<{$link.tooltip|default:''}>" class="img-responsive center" style="height:<{$imgheight}>">
                        </a>
                    </p>
                <{/if}>
                <p class="wglinks-link-block center">
                    <a href="<{$wglinks_url}>/index.php?link_id=<{$link.id}>" title="<{$link.tooltip|default:''}>" target="_self"><{$link.tooltip|default:''}></a>
                </p>
            </div>
        </div>
        <{if ($index_style|default:'' == '2cards' && $smarty.foreach.link.iteration % 2 == 0) || ($index_style|default:'' == '3cards' && $smarty.foreach.link.iteration % 3 == 0) || ($index_style|default:'' == '4cards' && $smarty.foreach.link.iteration % 4 == 0) || ($index_style|default:'' == '6cards' && $smarty.foreach.link.iteration % 6 == 0) || ($index_style|default:'' == '12cards' && $smarty.foreach.link.iteration % 12 == 0)}>
            <div class="clear"></div>
        <{/if}>
    <{/foreach}>
</div></div>