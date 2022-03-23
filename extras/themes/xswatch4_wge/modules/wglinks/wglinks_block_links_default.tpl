<ul class="nav flex-column nav-pills nav-stacked wglinks-block-ul">
    <{foreach item=link from=$block}>
        <li class="wglinks-block-li">
            <a class="wglinks-block-link" href="<{$link.url}>" title="<{$link.tooltip|default:''}>" target="_self">
            <{if $link.logo|default:false}>
                <img src="<{$wglinks_upload_url}>/images/links/thumbs/<{$link.logo}>" alt="<{$link.tooltip|default:''}>" class="wglinks-block-img" style="max-height:<{$imgheight}>">
            <{/if}>
            <{$link.tooltip|default:''}></a>
        </li>
    <{/foreach}>
</ul>