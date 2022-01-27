<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<{if $additionals_list|default:''}>
    <table class='table table-bordered'>
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ADDITIONAL_ID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ADDITIONAL_EVID}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ADDITIONAL_TYPE}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ADDITIONAL_CAPTION}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ADDITIONAL_DESC}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ADDITIONAL_VALUE}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ADDITIONAL_PLACEHOLDER}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ADDITIONAL_REQUIRED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ADDITIONAL_PRINT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_WEIGHT}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $additionals_count|default:''}>
        <tbody>
            <{foreach item=additional from=$additionals_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$additional.id}></td>
                <td class='center'><{$additional.evid}></td>
                <td class='center'><{$additional.type_text}></td>
                <td class='center'><{$additional.caption}></td>
                <td class='center'><{$additional.desc_short}></td>
                <td class='center'><{$additional.value}></td>
                <td class='center'><{$additional.placeholder}></td>
                <td class='center'><{$additional.required}></td>
                <td class='center'><{$additional.print}></td>
                <td class='center'><{$additional.weight}></td>
                <td class='center'><{$additional.datecreated}></td>
                <td class='center'><{$additional.submitter}></td>
                <td class="center  width5">
                    <a href="additionals.php?op=edit&amp;add_id=<{$additional.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> additionals" ></a>
                    <a href="additionals.php?op=clone&amp;add_id_source=<{$additional.id}>" title="<{$smarty.const._CLONE}>"><img src="<{xoModuleIcons16 editcopy.png}>" alt="<{$smarty.const._CLONE}> additionals" ></a>
                    <a href="additionals.php?op=delete&amp;add_id=<{$additional.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> additionals" ></a>
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
