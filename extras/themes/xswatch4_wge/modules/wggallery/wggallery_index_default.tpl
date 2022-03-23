<{include file='db:wggallery_header.tpl'}>

<{if $albums}>
	<div class='card panel-<{$panel_type}>'>
		<div class='card-header wgg-cats-header'><{$index_alb_title}></div>
		<div class='row card-body'>
			<{foreach item=album from=$albums}>
                <{if $number_cols_album == 4}>
                    <div class='col-12 col-md-3 wgg-album-panel'>
                        <{include file='db:wggallery_albumitem_2.tpl' album=$album}>
                    </div>
                    <{if $album.linebreak}>
                        <div class='clear'>&nbsp;</div>
                    <{/if}>
                <{elseif $number_cols_album == 3}>
                    <div class='col-12 col-md-4 wgg-album-panel'>
                        <{include file='db:wggallery_albumitem_2.tpl' album=$album}>
                    </div>
                    <{if $album.linebreak}>
                        <div class='clear'>&nbsp;</div>
                    <{/if}>
                <{elseif $number_cols_album == 2}>
                    <div class='col-12 col-md-6 wgg-album-panel'>
                        <{include file='db:wggallery_albumitem_2.tpl' album=$album}>
                    </div>
                    <{if $album.linebreak}>
                        <div class='clear'>&nbsp;</div>
                    <{/if}>
                <{else}>
                    <{include file='db:wggallery_albumitem_1.tpl' album=$album}>
                <{/if}>
			<{/foreach}>
		</div>
	</div>
    <{if $pagenav_albums}>
    <div class="col mt-2 mb-2">
        <div class="generic-pagination xo-pagenav pull-right"><{$pagenav_albums|replace:'form':'div'|replace:'id="xo-pagenav"':''|replace:' //':'/'}></div>
    </div>
    <{/if}>
<{/if}>
<{if $categories}>
	<div class='card panel-<{$panel_type}>'>
		<div class='card-header wgg-cats-header'><{$index_cats_title}></div>
		<div class='row card-body'>
			<{foreach item=category from=$categories}>
            <{if $number_cols_cat == 6}>
            <div class='col-12 col-md-2'>
            <{elseif $number_cols_cat == 4}>
            <div class='col-12 col-md-3'>
            <{elseif $number_cols_cat == 3}>
            <div class='col-12 col-md-4'>
            <{elseif $number_cols_cat == 2}>
            <div class='col-12 col-md-6'>
            <{else}>
            <div class='col-12 col-md-12'>
            <{/if}>
                <{include file='db:wggallery_categoryitem_2.tpl' category=$category}>
            </div>
			<{/foreach}>
		</div>
	</div>
    <{if $pagenav_cats}>
        <div class="col mb-2 mb-2">
            <div class="generic-pagination xo-pagenav pull-right"><{$pagenav_cats|replace:'form':'div'|replace:'id="xo-pagenav"':''|replace:' //':'/'}></div>
        </div>
    <{/if}>
<{/if}>

<{if $alb_pid}>
	<div class='clear'>&nbsp;</div>
	<div class='wgg-goback'>
		<a class='btn btn-secondary wgg-btn' href='index.php?op=list<{if $subm_id}>&amp;subm_id=<{$subm_id}><{/if}>' title='<{$smarty.const._CO_WGGALLERY_BACK}>'>
			<img class='wgg-btn-icon' src='<{$wggallery_icon_url_16}>back.png' alt='<{$smarty.const._CO_WGGALLERY_BACK}>'>
			<{if $displayButtonText}><{$smarty.const._CO_WGGALLERY_BACK}><{/if}>
		</a>
	</div>
<{/if}>
<div class='clear'>&nbsp;</div>

<{include file='db:wggallery_footer.tpl'}>
