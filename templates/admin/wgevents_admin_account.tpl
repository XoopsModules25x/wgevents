<!-- Header -->
<{include file='db:wgevents_admin_header.tpl' }>

<{if $info|default:''}>
    <div class="wge-msg-noaccount"><{$info}></div>
<{/if}>

<{if $account_check|default:false}>
    <table class='table table-bordered' >
        <thead>
        <tr class='head'>
            <th class='center'><{$smarty.const._AM_WGEVENTS_ACCOUNT_CHECK}></th>
            <th class='center'><{$smarty.const._AM_WGEVENTS_ACCOUNT_CHECK_RESULT}></th>
            <th class='center'><{$smarty.const._AM_WGEVENTS_ACCOUNT_CHECK_INFO}></th>
        </tr>
        </thead>
        <tbody>
        <{foreach item=check from=$checks}>
            <tr class="<{cycle values='odd, even'}>">
                <td class='center'><{$check.check}></td>
                <td class='center'><img src="<{$check.result_img}>" alt="<{$check.result}>" title="<{$check.result}>"> <{$check.result}></td>
                <td class='center'>
                    <{$check.info|default:''}>
                    <{if $check.created|default:false}>
                    <{$check.created}>
                    <{/if}>
                </td>
            </tr>
            <{/foreach}>
        </tbody>
    </table>
<{/if}>

<{if $accounts_list|default:''}>
    <table class='table table-bordered'>
        <thead>
            <tr class='head'>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_ID}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_TYPE}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_NAME}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_YOURNAME}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_YOURMAIL}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_USERNAME}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_PASSWORD}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_SERVER_IN}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_PORT_IN}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_SECURETYPE_IN}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_SERVER_OUT}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_PORT_OUT}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_SECURETYPE_OUT}></th>
                <th class="center"><{$smarty.const._AM_WGEVENTS_ACCOUNT_PRIMARY}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                <th class="center"><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                <th class="center width5 tablesorter-nosort"><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <{if $accountCount|default:''}>
        <tbody>
            <{foreach item=account from=$accounts_list}>
            <tr class='<{cycle values='odd, even'}>'>
                <td class='center'><{$account.id}></td>
                <td class='center'><{$account.type_text}></td>
                <td class='center'><{$account.name}></td>
                <td class='center'><{$account.yourname}></td>
                <td class='center'><{$account.yourmail}></td>
                <td class='center'><{$account.username}></td>
                <td class='center'><{$account.password}></td>
                <td class='center'><{$account.server_in}></td>
                <td class='center'><{$account.port_in}></td>
                <td class='center'><{$account.securetype_in}></td>
                <td class='center'><{$account.server_out}></td>
                <td class='center'><{$account.port_out}></td>
                <td class='center'><{$account.securetype_out}></td>
                <td class='center'>
                    <{if $account.primary|default:false}>
                    <a href="account.php?op=change_yn&amp;id=<{$account.id}>&amp;field=primary&amp;value=0&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETOFF}>"><img src="<{$wgevents_icons_url_16}>/<{$account.primary}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETOFF}> account" ></a>
                    <{else}>
                    <a href="account.php?op=change_yn&amp;id=<{$account.id}>&amp;field=primary&amp;value=1&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._AM_WGEVENTS_SETON}>"><img src="<{$wgevents_icons_url_16}>/<{$account.primary}>.png" alt="<{$smarty.const._AM_WGEVENTS_SETON}> account" ></a>
                    <{/if}>
                </td>
                <td class='center'><{$account.datecreated_text}></td>
                <td class='center'><{$account.submitter_text}></td>
                <td class="center  width5">
                    <a href="account.php?op=edit&amp;id=<{$account.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}> accounts" ></a>
                    <a href="account.php?op=delete&amp;id=<{$account.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}> accounts" ></a>
                    <{if $account.show_check}>
                    <a href='<{$wgevents_url}>/admin/account.php?op=check_account&amp;id=<{$account.id}>' title='<{$smarty.const._AM_WGEVENTS_ACCOUNT_TYPE_CHECK}>'>
                        <img src='<{$wgevents_icons_url_16}>/acc_check.png' alt='<{$smarty.const._AM_WGEVENTS_ACCOUNT_TYPE_CHECK}>'></a>
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
