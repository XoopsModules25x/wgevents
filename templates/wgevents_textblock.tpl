<{include file='db:wgevents_header.tpl' }>

<{if $textblocksCount|default:0 > 0}>
<div class='table-responsive'>
    <table class='table table-<{$table_type|default:false}>'>
        <thead>
            <tr class='head'>
                <th colspan='<{$divideby|default:false}>'><{$smarty.const._MA_WGEVENTS_TEXTBLOCKS_TITLE}></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <{foreach item=textblock from=$textblocks name=textblock}>
                <td>
                    <div class='panel panel-<{$panel_type|default:false}>'>
                        <{include file='db:wgevents_textblock_item.tpl' }>
                    </div>
                </td>
                <{if $smarty.foreach.textblock.iteration is div by $divideby}>
                    </tr><tr>
                <{/if}>
                <{/foreach}>
            </tr>
        </tbody>
        <tfoot><tr><td>&nbsp;</td></tr></tfoot>
    </table>
</div>
<{/if}>
<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $error|default:''}>
    <{$error|default:false}>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
