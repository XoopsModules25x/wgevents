<{include file='db:wgevents_header.tpl' }>

<{if $textblocksCount|default:0 > 0}>
<div class='table-responsive'>
    <table class='table table-<{$table_type|default:false}>'>
        <thead>
            <tr class='head'>
                <th><{$smarty.const._MA_WGEVENTS_TEXTBLOCKS_LIST}></th>
            </tr>
        </thead>
        <tbody>
            <{foreach item=textblock from=$textblocks name=textblock}>
                <tr>
                    <td>
                        <div class='panel panel-<{$panel_type|default:false}>'>
                            <{include file='db:wgevents_textblock_item.tpl' }>
                        </div>
                    </td>
                </tr>
            <{/foreach}>
        </tbody>
        <tfoot><tr><td>&nbsp;</td></tr></tfoot>
    </table>
</div>
<{/if}>
<{if $form|default:false}>
    <{$form}>
<{/if}>
<{if $error|default:false}>
    <div class="wge-error-msg"><{$error}></div>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
