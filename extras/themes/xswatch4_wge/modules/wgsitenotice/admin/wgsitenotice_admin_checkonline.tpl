<{include file="db:wgsitenotice_admin_header.tpl"}>
<h1><{$disclaimer|default:''}></h1>
<p style="font-size:120%;"><{$disclaimer_desc|default:''}></p>
<br/><br/>
<{if $form|default:''}>
    <!-- Display form (add,edit) -->
    <div class="spacer"><{$form}></div>
<{/if}>
<{if $versions_list|default:''}>
    <h2><{$smarty.const._AM_WGSITENOTICE_OC_RESULT}></h2>
    <table class="outer versions width100">
        <thead>
            <tr class="head">
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_ID}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_NAME}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_LANG}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_DESCR}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_AUTHOR}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_VERSION_DATE}></th>
                <th class="center"><{$smarty.const._AM_WGSITENOTICE_FORMACTION}></th>
            </tr>
        </thead>
        <tbody>
            <{foreach item=list from=$versions_list}>    
                <tr class="<{cycle values='odd, even'}>">
                    <td class="center"><{$list.id}></td>
                    <td class="center"><{$list.name}></td>
                    <td class="center"><{$list.lang}></td>
                    <td class="center"><{$list.descr}></td>
                    <td class="center"><{$list.author}></td>
                    <td class="center"><{$list.date}></td>
                    <td class="center">
                        <a href="checkonline.php?op=download&amp;version_id=<{$list.id}>&amp;oc_server=<{$oc_server}>" title="<{$smarty.const._AM_WGSITENOTICE_FORMDOWNLOAD}>">
                            <img src="../<{$modPathIcon16}>/download.png" alt="<{$smarty.const._AM_WGSITENOTICE_FORMDOWNLOAD}>" />
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
<br/><br/>

<!-- Footer -->
<{include file="db:wgsitenotice_admin_footer.tpl"}>
