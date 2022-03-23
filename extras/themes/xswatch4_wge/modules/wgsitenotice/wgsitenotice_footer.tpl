<{if $bookmarks|default:0 != 0}>
    <{include file="db:system_bookmarks.html"}>
<{/if}>
<{if $fbcomments|default:0 != 0}>
    <{include file="db:system_fbcomments.html"}>
<{/if}>
<div class="left"><{$copyright|default:''}></div>
<{if $pagenav|default:'' != ''}>
    <div class="right"><{$pagenav}></div>
<{/if}>
<br />
<{if $xoops_isadmin|default:false}>
   <div class="center bold"><a href="<{$admin}>"><{$smarty.const._MA_WGSITENOTICE_ADMIN}></a></div><br />
<{/if}>
