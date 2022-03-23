<{include file="db:wgsitenotice_header.tpl"}>
<div class="col-sm-12 col-md-12 xoops-side-blocks">
    <{foreach item=list from=$contents|default:''}>
        <aside>
            <h4 class="block-title"><{$list.header|default:''}></h4>
            <{$list.text|default:''}>
        </aside>
    <{/foreach}>
</div>
<{include file="db:wgsitenotice_footer.tpl"}>