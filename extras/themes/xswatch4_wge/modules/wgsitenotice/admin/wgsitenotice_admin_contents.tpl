<{include file="db:wgsitenotice_admin_header.tpl"}>
<{if $contents_list|default:''}>
    <table class="table table-bordered  table-striped" id="sortable">
        <thead>
            <tr class="head">
                <th class="center">&nbsp;</th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_CONT_ID}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_CONT_HEADER}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_CONT_DATE}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_FORMACTION}></th>
            </tr>
        </thead>
        <{if $contents_count|default:''}>
            <{foreach item=list from=$contents_list}>    
                <{if $list.new_version|default:'' > 0}> 
                <tbody>
                    <tr class="odd">
                        <td class="left" colspan="5"><{$list.version_name}></td>
                    </tr>
                <{/if}>
                    <tr class="even" id="corder_<{$list.id}>">
                        <td class="center">
                        <{if $list.nb_conts_version|default:'' > 1}>
                            <img src="<{$wgsitenotice_icons_url}>/16/up_down.png" alt="drag&drop" class="icon-sortable"/>
                        <{else}>
                            &nbsp;
                        <{/if}>
                        </td>
                        <td class="center"><{$list.id}></td>
                        <td class="center"><{$list.header}></td>
                        <td class="center"><{$list.date}></td>
                        <td class="center">
                            <a href="contents.php?op=edit&amp;cont_id=<{$list.id}>" title="<{$smarty.const._EDIT}>">
                                <img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}>" />
                            </a>                   
                            <a href="contents.php?op=delete&amp;cont_id=<{$list.id}>" title="<{$smarty.const._DELETE}>">
                                <img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}>" />
                            </a>
                        </td>
                    </tr>
                <{/foreach}>
            </tbody>
        <{/if}>
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