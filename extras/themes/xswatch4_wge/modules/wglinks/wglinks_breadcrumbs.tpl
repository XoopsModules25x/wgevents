<ul class="breadcrumb">
    <li><a href='<{xoAppUrl index.php}>' title='home'><i class="glyphicon glyphicon-home"></i></a></li>
    <{foreach item=itm from=$xoBreadcrumbs name=bcloop}>
        <{if $itm.link|default:false}>
            <li><a href='<{$itm.link}>' title='<{$itm.title}>'><{$itm.title}></a></li>
        <{else}>
            <li><{$itm.title}></li>
        <{/if}>
    <{/foreach}>
</ul>