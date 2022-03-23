<{include file='db:wglinks_header.tpl'}>

<{if count($links_list|default:null) > 0}>
    <{if $index_header|default:false}>
        <h3 class='wglinks-header'><{$index_header}></h3>
    <{/if}>
    <{if $index_info|default:false}>
        <p class='wglinks-info'><{$index_info}></p>
    <{/if}>
    
    <div class='row'>
        <{foreach item=links from=$links_list}>
            <{foreach name=link item=link from=$links}>
                <{if $index_style|default:'' == '2cards' || $index_style|default:'' == '3cards' || $index_style|default:'' == '4cards'}>
                    <{include file='db:wglinks_links_cards.tpl' link=$link}>
                <{else}>
                    <{include file='db:wglinks_links_default.tpl' link=$link}>
                <{/if}>
            <{/foreach}>
        <{/foreach}>
    </div>
<{/if}>

<{include file='db:wglinks_footer.tpl'}>
