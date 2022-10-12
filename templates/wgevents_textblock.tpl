<{include file='db:wgevents_header.tpl' }>

<{if $textblocksCount|default:0 > 0}>
    <div class='table-responsive'>
        <table class='table table-<{$table_type|default:false}>'>
            <thead>
                <tr class='head'>
                    <th>
                        <{if $showItem|default:false}>
                        <{$smarty.const._MA_WGEVENTS_DETAILS}>
                        <{else}>
                        <{$smarty.const._MA_WGEVENTS_TEXTBLOCKS_LIST}>
                        <{/if}>
                    </th>
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
            <tfoot>
                <tr>
                    <td class="center">
                        <{if $showItem|default:false}>
                            <a class='btn btn-success right wge-btn' href='textblock.php?op=list&amp;start=<{$start}>&amp;limit=<{$limit}>#tbId_<{$textblock.id}>' title='<{$smarty.const._MA_WGEVENTS_TEXTBLOCKS_LIST}>'><{$smarty.const._MA_WGEVENTS_TEXTBLOCKS_LIST}></a>
                        <{/if}>
                        <a class='btn btn-primary center wge-btn' href='textblock.php?op=new' title='<{$smarty.const._MA_WGEVENTS_TEXTBLOCK_ADD}>'><{$smarty.const._MA_WGEVENTS_TEXTBLOCK_ADD}></a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
<{/if}>

<{if $form|default:false}>
    <{$form}>
<{/if}>
<{if $error|default:false}>
    <div class="wge-error-msg"><{$error}></div>
<{/if}>
<{if $permSubmitFirst|default:false}>
    <div class="center wge-spacer2">
        <a class='btn btn-primary center wge-btn' href='textblock.php?op=new' title='<{$smarty.const._MA_WGEVENTS_TEXTBLOCK_ADD}>'><{$smarty.const._MA_WGEVENTS_TEXTBLOCK_ADD}></a>
    </div>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
