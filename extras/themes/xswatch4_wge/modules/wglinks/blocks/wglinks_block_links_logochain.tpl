<{if $block|default:false}>
    <div class="row wglinks-block-logochain center">
        <{foreach item=link from=$block}>
            <{if $link.url|default:false}><a href="<{$link.url}>" title="<{$link.tooltip|default:''}>" target="_blank"><{/if}>
                <img src="<{$wglinks_upload_url}>/images/links/thumbs/<{$link.logo}>" alt="<{$link.name}>" class="wglinks-block-img" style="height:<{$imgheight}>px">
            <{if $link.url|default:false}></a><{/if}>
        <{/foreach}>
    </div>
<{/if}>