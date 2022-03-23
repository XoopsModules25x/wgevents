<{include file="db:wgsitenotice_admin_header.tpl"}>
<{if $versions_list|default:''}>
    <table class="outer versions width100">
        <thead>
            <tr class="head">
                <th class="center">&nbsp;</th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_ID}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_NAME}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_LANG}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_DESCR}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_AUTHOR}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_CURRENT}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_ONLINE}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_DATE}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_FORMACTION}></th>
            </tr>
        </thead>
        <tbody id="versions-list">
            <{foreach item=list from=$versions_list}>    
                <tr class="<{cycle values='odd, even'}>" id="vorder_<{$list.id}>" >
                    <td class="center"><img src="<{$wgsitenotice_icons_url}>/16/up_down.png" alt="drag&drop" class="icon-sortable"/></td>
                    <td class="center"><{$list.id}></td>
                    <td class="center"><{$list.name}></td>
                    <td class="center"><{$list.lang}></td>
                    <td class="center"><{$list.descr}></td>
                    <td class="center"><{$list.author}></td>
                    <td class="center">
                        <a href="versions.php?op=change_current&amp;version_id=<{$list.id}>&amp;start=<{$start}>" title="<{$smarty.const._EDIT}>">
                            <{$list.current}>
                        </a>
                    </td>
                    <td class="center">
                        <a href="versions.php?op=change_online&amp;version_id=<{$list.id}>&amp;start=<{$start}>" title="<{$smarty.const._EDIT}>">
                            <{$list.online}>
                        </a>
                    </td>
                    <td class="center"><{$list.date}></td>
                    <td class="center">
                        <a href="versions.php?op=edit&amp;version_id=<{$list.id}>" title="<{$smarty.const._EDIT}>">
                            <img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}>" />
                        </a>                   
                        <a href="versions.php?op=delete&amp;version_id=<{$list.id}>" title="<{$smarty.const._DELETE}>">
                            <img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}>" />
                        </a>
                    </td>
                </tr>
            <{/foreach}>
        </tbody>
    </table>
    <div class="clear">&nbsp;</div>
    <{if $pagenav|default:''}><br />
        <!-- Display navigation -->
        <div class="xo-pagenav floatright"><{$pagenav}></div><div class="clear spacer"></div>
    <{/if}>
<{/if}>    
<{if $error|default:''}>    
    <div class="errorMsg">
        <strong><{$error}></strong>
    </div>
<{/if}>
<{if $form|default:''}>
    <!-- Display form (add,edit) -->
    <div class="spacer"><{$form}></div>
<{/if}>
<br />
<!-- Footer -->
<{include file="db:wgsitenotice_admin_footer.tpl"}>