<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<{if $addtypes_list|default:''}>
    <table class='table table-bordered'>
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ADDTYPE_ID}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ADDTYPE_TYPE}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ADDTYPE_CAPTION}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ADDTYPE_VALUE}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ADDTYPE_PLACEHOLDER}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ADDTYPE_REQUIRED}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ADDTYPE_DEFAULT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_PRINT}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ADDTYPE_DISPLAY_VALUES}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ADDTYPE_DISPLAY_PLACEHOLDER}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_WEIGHT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_STATUS}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $addtypes_count|default:''}>
        <tbody>
            <{foreach item=addtype from=$addtypes_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$addtype.id}></td>
                <td class='center'><{$addtype.type_text}></td>
                <td class='center'><{$addtype.name}></td>
                <td class='center'><{$addtype.value_list}></td>
                <td class='center'><{$addtype.placeholder}></td>
                <td class='center'>
                    <{if $addtype.at_required|default:false}>
                        <a href="addtypes.php?op=change_yn&amp;at_id=<{$addtype.id}>&amp;field=at_required&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$addtype.at_required}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> addtypes" ></a>
                    <{else}>
                        <a href="addtypes.php?op=change_yn&amp;at_id=<{$addtype.id}>&amp;field=at_required&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$addtype.at_required}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> addtypes" ></a>
                    <{/if}>
                </td>
                <td class='center'>
                    <{if $addtype.at_default|default:false}>
                    <a href="addtypes.php?op=change_yn&amp;at_id=<{$addtype.id}>&amp;field=at_default&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$addtype.at_default}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> addtypes" ></a>
                    <{else}>
                    <a href="addtypes.php?op=change_yn&amp;at_id=<{$addtype.id}>&amp;field=at_default&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$addtype.at_default}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> addtypes" ></a>
                    <{/if}>
                </td>
                <td class='center'>
                    <{if $addtype.at_print|default:false}>
                    <a href="addtypes.php?op=change_yn&amp;at_id=<{$addtype.id}>&amp;field=at_print&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$addtype.at_print}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> addtypes" ></a>
                    <{else}>
                    <a href="addtypes.php?op=change_yn&amp;at_id=<{$addtype.id}>&amp;field=at_print&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$addtype.at_print}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> addtypes" ></a>
                    <{/if}>
                </td>



                <td class='center'>
                    <{if $addtype.at_display_values|default:false}>
                    <a href="addtypes.php?op=change_yn&amp;at_id=<{$addtype.id}>&amp;field=at_display_values&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$addtype.at_display_values}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> addtypes" ></a>
                    <{else}>
                    <a href="addtypes.php?op=change_yn&amp;at_id=<{$addtype.id}>&amp;field=at_display_values&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$addtype.at_display_values}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> addtypes" ></a>
                    <{/if}>
                </td>
                <td class='center'>
                    <{if $addtype.at_display_placeholder|default:false}>
                    <a href="addtypes.php?op=change_yn&amp;at_id=<{$addtype.id}>&amp;field=at_display_placeholder&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$addtype.at_display_placeholder}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> addtypes" ></a>
                    <{else}>
                    <a href="addtypes.php?op=change_yn&amp;at_id=<{$addtype.id}>&amp;field=at_display_placeholder&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$addtype.at_display_placeholder}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> addtypes" ></a>
                    <{/if}>
                </td>




                <td class='center'><{$addtype.weight}></td>
                <td class='center'><img src="<{$modPathIcon16}>status<{$addtype.status}>.png" alt="<{$addtype.status_text}>" title="<{$addtype.status_text}>" ></td>
                <td class='center'><{$addtype.datecreated}></td>
                <td class='center'><{$addtype.submitter}></td>
                <td class="center  width5">
                    <a href="addtypes.php?op=edit&amp;at_id=<{$addtype.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> addtypes" ></a>
                    <{if $addtype.custom|default:false}>
                        <a href="addtypes.php?op=clone&amp;at_id_source=<{$addtype.id}>" title="<{$smarty.const._CLONE}>"><img src="<{xoModuleIcons16 editcopy.png}>" alt="<{$smarty.const._CLONE}> addtypes" ></a>
                        <a href="addtypes.php?op=delete&amp;at_id=<{$addtype.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> addtypes" ></a>
                    <{/if}>
                </td>
            </tr>
            <{/foreach}>
        </tbody>
        <{/if}>
    </table>
    <div class="clear">&nbsp;</div>
    <{if $pagenav|default:''}>
        <div class="xo-pagenav floatright"><{$pagenav|default:false}></div>
        <div class="clear spacer"></div>
    <{/if}>
<{/if}>
<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:wgevents_admin_footer.tpl' }>
