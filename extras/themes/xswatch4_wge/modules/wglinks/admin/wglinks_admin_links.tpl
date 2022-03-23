<!-- Header -->
<{include file='db:wglinks_admin_header.tpl'}>

<{if $links_list|default:false}>
    <table class='table table-bordered' id='sortable'>
        <thead>
            <tr class="head">
                <th class="center">&nbsp;</th>
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_ID}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_NAME}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_TOOLTIP}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_DETAIL}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_URL}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_CONTACT}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_EMAIL}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_PHONE}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_ADDRESS}></th>              
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_LOGO}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_LINK_STATE}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_SUBMITTER}></th>
                <th class="center"><{$smarty.const._AM_WGLINKS_DATE_CREATED}></th>
                <th class="center width5"><{$smarty.const._AM_WGLINKS_FORM_ACTION}></th>
            </tr>
        </thead>
        <{if $links_count|default:0}>
            <!-- <tbody id="links-list"> -->
            <tbody>
                <{foreach item=link from=$links_list}>
                    <{if $link.new_cat|default:0 > 0}>
                        <tr class="odd">
                            <td class="left" colspan="16"><{$link.catname|default:''}></td>
                        </tr>
                    <{/if}>
                    <tr class="<{cycle values="odd, even"}>"  id="lorder_<{$link.id}>">
                        <td class="center"><img src="<{$wglinks_url}>/assets/icons/16/up_down.png" alt="drag&drop" class="icon-sortable"/></td>
                        <td class='center'><{$link.id}></td>
                        <td class="center"><{$link.name|default:''}></td>
                        <td class="center"><{$link.tooltip|default:''}></td>
                        <td class="center"><{$link.detail_truncated|default:''}></td>
                        <td class="center"><{$link.url|default:''}></td>
                        <td class="center"><{$link.contact|default:''}></td>
                        <td class="center"><{$link.email|default:''}></td>
                        <td class="center"><{$link.phone|default:''}></td>
                        <td class="center"><{$link.address|default:''}></td>
                       
                        <td class="center"><img src="<{$wglinks_upload_url}>/images/links/thumbs/<{$link.logo|default:''}>" alt="<{$link.name|default:''}>" style="height:50px" /></td>
                        <td class="center">
                            <{if $link.state|default:0 == 0}>
                                <a href='links.php?op=change_state&amp;link_state=1&amp;link_id=<{$link.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._AM_WGLINKS_STATE_ONLINE}>'>
                                    <img src='<{$wglinks_url}>/assets/icons/16/state0.png' alt='<{$smarty.const._AM_WGLINKS_STATE_ONLINE}>' />
                                </a>
                            <{/if}>
                            <{if $link.state|default:0 == 1}>
                                <a href='links.php?op=change_state&amp;link_state=0&amp;link_id=<{$link.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._AM_WGLINKS_STATE_OFFLINE}>'>
                                    <img src='<{$wglinks_url}>/assets/icons/16/state1.png' alt='<{$smarty.const._AM_WGLINKS_STATE_OFFLINE}>' />
                                </a>
                            <{/if}>
                        </td>
                        <td class="center"><{$link.submitter}></td>
                        <td class="center"><{$link.date_created}></td>
                        <td class="center  width5">
                        <a href="links.php?op=edit&amp;link_id=<{$link.id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="links" /></a>
                        <a href="links.php?op=delete&amp;link_id=<{$link.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="links" /></a>
                        </td>
                    </tr>
                <{/foreach}>
            </tbody>
        <{/if}>
    </table>

    <div class="clear">&nbsp;</div>
    <{if $pagenav|default:''|default:false}>
        <div class="xo-pagenav floatright"><{$pagenav}></div><div class="clear spacer"></div>
    <{/if}>
<{/if}>

<{if $form|default:false}>
    <{$form}>
<{/if}>

<{if $error|default:false}>
    <div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<br />
<!-- Footer -->
<{include file='db:wglinks_admin_footer.tpl'}>
