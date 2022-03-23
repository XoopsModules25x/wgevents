<{if $xoBlocks.canvas_left && $xoBlocks.canvas_right}>
    <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8">
<{elseif $xoBlocks.canvas_left}>
    <div class="col-xs-9 col-sm-9 col-md-10 col-lg-10">
<{elseif $xoBlocks.canvas_right}>
    <div class="col-xs-9 col-sm-9 col-md-10 col-lg-10">
<{else}>
    <div class="col-12">
<{/if}>
    <div class="row">
        <{include file="$theme_name/tpl/centerLeft.tpl"}>
        <{include file="$theme_name/tpl/centerBlock.tpl"}>
        <{include file="$theme_name/tpl/centerRight.tpl"}>
    </div>
    <{include file="$theme_name/tpl/contents.tpl"}>
</div>