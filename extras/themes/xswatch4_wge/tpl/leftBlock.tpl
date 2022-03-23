<{if $xoBlocks.canvas_left}>
    <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2 xoops-side-blocks">
        <{foreach item=block from=$xoBlocks.canvas_left}>
            <aside>
                <{if $block.title}><h4 class="block-title"><{$block.title}></h4><{/if}>
                <{include file="$theme_name/tpl/blockContent.tpl"}>
            </aside>
        <{/foreach}>
    </div>
<{/if}>
