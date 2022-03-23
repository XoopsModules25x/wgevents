<{if $block|default:false}>
    <{if ($blockStyle|default:'' == '2cards' || $blockStyle|default:'' == '3cards' || $blockStyle|default:'' == '4cards' || $blockStyle|default:'' == '6cards' || $blockStyle|default:'' == '12cards')}>
        <{include file='db:wglinks_block_links_cards.tpl' link=$block}>
    <{else}>
        <{include file='db:wglinks_block_links_default.tpl' link=$block}>
    <{/if}>
    <{if $showMore|default:false}>
        <div class="wglinks-block-more"><a class="btn btn-primary wg-color1" href="<{$wglinks_url}>/index.php<{if $cat_ids}>?cat_ids=<{$cat_ids}><{/if}>" title="<{$smarty.const._MA_WGLINKS_SHOW_MORE}>" target="_self"><{$smarty.const._MA_WGLINKS_SHOW_MORE}></a></div>
    <{/if}>
<{/if}>