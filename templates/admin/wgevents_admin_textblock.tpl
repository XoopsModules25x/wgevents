<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<{if $textblocks_list|default:''}>
    <{include file='db:admin_pagertop.tpl' }>

    <table id="sortTable" class="tablesorter-blue" cellspacing="1" cellpadding="0"  width="100%">
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._MA_WGEVENTS_TEXTBLOCK_ID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_TEXTBLOCK_CATID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_TEXTBLOCK_NAME}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_TEXTBLOCK_TEXT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_TEXTBLOCK_CLASS}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_WEIGHT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5 tablesorter-nosort"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $textblockCount|default:''}>
        <tbody>
            <{foreach item=textblock from=$textblocks_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$textblock.id}></td>
                <td class='center'><{$textblock.catname}></td>
                <td class='center'><{$textblock.name}></td>
                <td class='center'><{$textblock.text_short}></td>
                <td class='center'><{$textblock.class_text}></td>
                <td class='center'><{$textblock.weight}></td>
                <td class='center'><{$textblock.datecreated_text}></td>
                <td class='center'><{$textblock.submitter_text}></td>
                <td class="center  width5">
                    <a href="textblock.php?op=edit&amp;id=<{$textblock.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> textblocks" ></a>
                    <a href="textblock.php?op=clone&amp;id_source=<{$textblock.id}>" title="<{$smarty.const._CLONE}>"><img src="<{xoModuleIcons16 editcopy.png}>" alt="<{$smarty.const._CLONE}> textblocks" ></a>
                    <a href="textblock.php?op=delete&amp;id=<{$textblock.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> textblocks" ></a>
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
