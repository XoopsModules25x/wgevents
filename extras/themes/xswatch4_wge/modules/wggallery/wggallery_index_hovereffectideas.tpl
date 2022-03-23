<{include file='db:wggallery_header.tpl'}>

<div class="container no-pad-lr">
<{if $albums}>
	<div class='card panel-<{$panel_type}>'>
		<div class='card-header wgg-cats-header'><{$index_alb_title}></div>
		<div class='card-body'>
			<{foreach name=album item=album from=$albums}>
                <{include file='db:wggallery_albumitem_hovereffectideas.tpl' album=$album}>
			<{/foreach}>
		</div>
	</div>
	<{if $pagenav_albums}>
	<div class="row mt-2">
		<div class="col">
			<div class="generic-pagination xo-pagenav pull-right"><{$pagenav_albums|replace:'form':'div'|replace:'id="xo-pagenav"':''|replace:' //':'/'}></div>
		</div>
	</div>
	<{/if}>
<{/if}>
<{if $categories}>
	<div class='card panel-<{$panel_type}>'>
		<div class='card-header wgg-cats-header'><{$index_cats_title}></div>
		<div class='card-body'>
			<{foreach name=category item=category from=$categories}>
                <{if $category.newrow}><div class="grid"><{/if}>
                <{include file='db:wggallery_categoryitem_hovereffectideas.tpl' category=$category}>
                <{if $category.linebreak}></div><div class='clear'>&nbsp;</div><{/if}>
			<{/foreach}>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col">
			<div class="generic-pagination xo-pagenav pull-right"><{$pagenav_cats|replace:'form':'div'|replace:'id="xo-pagenav"':''|replace:' //':'/'}></div>
		</div>
	</div>
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

</div>


<{include file='db:wggallery_footer.tpl'}>
