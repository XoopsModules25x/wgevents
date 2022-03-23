<{if $bookmarks|default:false != 0}>
<{include file="db:system_bookmarks.html"}>
<{/if}>

<{if $fbcomments|default:false != 0}>
<{include file="db:system_fbcomments.html"}>
<{/if}>
<div class="pull-left"><{$copyright}></div>
<{if $pagenav|default:'' != ''}>
    <div class="pull-right"><{$pagenav}></div>
<{/if}>
<br />
<{if $xoops_isadmin|default:false}>
   <div class="text-center bold"><a href="<{$admin}>"><{$smarty.const._MA_WGLINKS_ADMIN}></a></div><br />
<{/if}>
