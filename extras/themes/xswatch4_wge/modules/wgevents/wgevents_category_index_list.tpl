<div class='wge-panel'>
    <div class='panel-heading center wge-eventheader'><h4><span><{$category.name}></span></h4></div>
    <div class='panel-body'>
        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <{if $category.image|default:'blank.gif' != 'blank.gif'}>
                    <img class="img-responsive img-fluid" src='<{$wgevents_upload_catimages_url|default:false}>/<{$category.image}>' alt='<{$category.name}>' >
                <{else}>
                    <img class="img-responsive img-fluid" src='<{$wgevents_upload_catlogos_url|default:false}>/<{$category.logo}>' alt='<{$category.name}>' >
                <{/if}>
            </div>
            <div class="col-xs-12 col-sm-6 wge-panel-details1"><{$category.desc_text}></div>
            <div class="col-xs-12 col-sm-4 wge-panel-details1"><span class="badge badge-success wge-badge wge-badge-index"><{$category.nbeventsText|default:''}></span></div>
        </div>
    </div>
    <div class='panel-foot row right'></div>
</div>
