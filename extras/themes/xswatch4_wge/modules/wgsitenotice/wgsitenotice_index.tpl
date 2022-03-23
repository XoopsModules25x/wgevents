<{include file="db:wgsitenotice_header.tpl"}>
<{foreach item=list from=$contents|default:''}>    
    <h3 style="text-align:center;"><{$list.header|default:''}></h3>
    <span style="text-align:left;"><{$list.text|default:''}></span>
<{/foreach}>
<{include file="db:wgsitenotice_footer.tpl"}>
