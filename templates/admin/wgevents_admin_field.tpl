<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<{if $fields_list|default:''}>
    <{include file='db:admin_pagertop.tpl' }>

    <table id="sortTable" class="tablesorter-blue" cellspacing="1" cellpadding="0"  width="100%">
        <thead>
            <tr class='head'>
                <th class="center sorter-false filter-false">&nbsp;</th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_FIELD_ID}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_FIELD_TYPE}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_FIELD_CAPTION}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_FIELD_VALUE}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_FIELD_PLACEHOLDER}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_FIELD_REQUIRED}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_FIELD_DEFAULT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_PRINT}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_FIELD_DISPLAY_DESC}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_FIELD_DISPLAY_VALUES}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_FIELD_DISPLAY_PLACEHOLDER}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_WEIGHT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_STATUS}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5 sorter-false filter-false"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $fieldCount|default:''}>
        <tbody id="fields-list">
            <{foreach item=field from=$fields_list}>
            <tr class='<{cycle values='odd, even'}>' id="order_<{$field.id}>">
                <td class='center'><img src="<{$wgevents_icons_url_16}>/up_down.png"></td>
                <td class='center'><{$field.id}></td>
                <td class='center'><{$field.type_text}></td>
                <td class='center'><{$field.caption}></td>
                <td class='center'><{$field.value_list}></td>
                <td class='center'><{$field.placeholder}></td>
                <td class='center'>
                    <{if $field.required|default:false}>
                        <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=required&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$field.required}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> fields" ></a>
                    <{else}>
                        <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=required&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$field.required}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> fields" ></a>
                    <{/if}>
                </td>
                <td class='center'>
                    <{if $field.default|default:false}>
                    <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=default&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$field.default}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> fields" ></a>
                    <{else}>
                    <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=default&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$field.default}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> fields" ></a>
                    <{/if}>
                </td>
                <td class='center'>
                    <{if $field.print|default:false}>
                    <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=print&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$field.print}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> fields" ></a>
                    <{else}>
                    <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=print&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$field.print}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> fields" ></a>
                    <{/if}>
                </td>
                <td class='center'>
                    <{if $field.display_desc|default:false}>
                    <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=display_desc&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$field.display_desc}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> fields" ></a>
                    <{else}>
                    <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=display_desc&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$field.display_desc}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> fields" ></a>
                    <{/if}>
                </td>
                <td class='center'>
                    <{if $field.display_values|default:false}>
                    <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=display_values&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$field.display_values}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> fields" ></a>
                    <{else}>
                    <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=display_values&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$field.display_values}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> fields" ></a>
                    <{/if}>
                </td>
                <td class='center'>
                    <{if $field.display_placeholder|default:false}>
                    <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=display_placeholder&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$field.display_placeholder}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> fields" ></a>
                    <{else}>
                    <a href="field.php?op=change_yn&amp;id=<{$field.id}>&amp;field=display_placeholder&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$field.display_placeholder}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> fields" ></a>
                    <{/if}>
                </td>
                <td class='center'><{$field.weight}></td>
                <td class='center'><img src="<{$modPathIcon16}>status<{$field.status}>.png" alt="<{$field.status_text}>" title="<{$field.status_text}>" ></td>
                <td class='center'><{$field.datecreated_text}></td>
                <td class='center'><{$field.submitter_text}></td>
                <td class="center  width5">
                    <a href="field.php?op=edit&amp;id=<{$field.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> fields" ></a>
                    <{if $field.custom|default:false}>
                        <a href="field.php?op=clone&amp;id_source=<{$field.id}>" title="<{$smarty.const._CLONE}>"><img src="<{xoModuleIcons16 editcopy.png}>" alt="<{$smarty.const._CLONE}> fields" ></a>
                        <a href="field.php?op=delete&amp;id=<{$field.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> fields" ></a>
                    <{/if}>
                </td>
            </tr>
            <{/foreach}>
        </tbody>
        <{/if}>
    </table>
    <div class="clear">&nbsp;</div>
    <{include file='db:admin_pagerbottom.tpl' }>
<{/if}>
<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:wgevents_admin_footer.tpl' }>
