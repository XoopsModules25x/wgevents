<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>
<{if $modules_list|default:''}>
    <table class="tablesorter-<{$tablesorter_theme}>" cellspacing="1" cellpadding="0"  width="100%">
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._AM_WGEVENTS_IMPORT_MODULES}></th>
                <{if $showData|default:''}>
                    <th class="center"><{$smarty.const._AM_WGEVENTS_IMPORT_RESULT}></th>
                <{/if}>
                <th class="center"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <tbody>
            <{foreach item=module from=$modules_list}>
                <tr class='<{cycle values='odd, even'}>'>
                    <td class='center'>
                        <{$module.name}>

                    </td>
                    <{if $showData|default:''}>
                        <td class='center'>
                            <{$smarty.const._AM_WGEVENTS_IMPORT_RESULT_CATS}>: <{$module.catsResult|default:''}><br>
                            <{$smarty.const._AM_WGEVENTS_IMPORT_RESULT_EVENTS}>: <{$module.eventsResult|default:''}>
                        </td>
                    <{/if}>
                    <td class='center'>
                        <{if $showData|default:''}>
                            <a class="btn" href="import.php?op=list" title="<{$smarty.const._BACK}>"><{$smarty.const._BACK}></a>
                        <{else}>
                            <{if $module.installed|default:false}>
                                <a class="btn" href="import.php?op=<{$module.op}>" title="<{$smarty.const._AM_WGEVENTS_IMPORT_SHOWFORM}>"><{$smarty.const._AM_WGEVENTS_IMPORT_SHOWFORM}></a>
                            <{else}>
                                <img src="<{$wgevents_icons_url_32}>/important.png"> <{$smarty.const._AM_WGEVENTS_IMPORT_NOTINSTALLED}>
                            <{/if}>
                        <{/if}>
                    </td>
                </tr>
            <{/foreach}>
        </tbody>
    </table>
    <div class="clear">&nbsp;</div>
<{/if}>

<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:wgevents_admin_footer.tpl' }>
