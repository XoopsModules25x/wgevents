<!-- Header -->
<{include file='db:wglinks_admin_header.tpl'}>

<{if $categories_list|default:false}>
    <table class='table table-bordered'>
        <thead>
            <tr class="head">
                <th class="center"><{$smarty.const._AM_WGLINKS_CAT_ID}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_CAT_NAME}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_CAT_DESC}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_WEIGHT}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_SUBMITTER}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_DATE_CREATED}></th>
                <th class="center width5"><{$smarty.const._AM_WGLINKS_FORM_ACTION}></th>
            </tr>
        </thead>
        <{if $categories_count|default:0}>
            <tbody><{foreach item=category from=$categories_list}>
                <tr class="<{cycle values="odd, even"}>">
                    <td class='center'><{$category.id}></td>
                    <td class="center"><{$category.name}></td>
                    <td class="center"><{$category.desc|default:''}></td>
                    <td class="center"><{$category.weight}></td>
                    <td class="center"><{$category.submitter}></td>
                    <td class="center"><{$category.date_created}></td>
                    <td class="center  width5">
                        <a href="categories.php?op=edit&amp;cat_id=<{$category.id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="categories" /></a>
                        <a href="categories.php?op=delete&amp;cat_id=<{$category.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="categories" /></a>
                    </td>
                </tr>
            <{/foreach}></tbody>
        <{/if}>
    </table>

    <div class="clear">&nbsp;</div>
    <{if $pagenav|default:''|default:''}>
        <div class="xo-pagenav floatright"><{$pagenav}></div><div class="clear spacer"></div>
    <{/if}>
<{/if}>

<{if $form|default:false}>
    <{$form}>
<{/if}>

<{if $error|default:false}>
    <div class="errorMsg"><strong><{$error}></strong>
</div>

<{/if}>

<br />
<!-- Footer -->
<{include file='db:wglinks_admin_footer.tpl'}>
