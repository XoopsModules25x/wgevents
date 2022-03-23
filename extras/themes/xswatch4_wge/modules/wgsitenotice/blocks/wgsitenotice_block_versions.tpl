<ul class="nav flex-column nav-pills nav-stacked">
    <{foreach item=list from=$block|default:''}>
        <li class="li-wgsitenotice <{if $list.highlight}>active<{/if}>">
            <a href="<{$smarty.const.WGSITENOTICE_URL}>/index.php?version_id=<{$list.version_id}>" title="<{$list.version_name}>">
                <{$list.version_name}>
            </a>
        </li>
    <{/foreach}>
</ul>

<{if $list.show_more|default:false}>
    <div class="center">
        <a class="btn-wgsitenotice btn btn-success" href="<{$smarty.const.WGSITENOTICE_URL}>/index.php" title="<{$smarty.const._MB_WGSITENOTICE_SHOW_MORE}>"><{$smarty.const._MB_WGSITENOTICE_SHOW_MORE}></a>                
    </div>
<{/if}>