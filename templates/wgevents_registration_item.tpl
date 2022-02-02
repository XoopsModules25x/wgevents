<tr>
    <td><{$smarty.foreach.regdetails.iteration}></td>
    <td><{$regdetails.salutation_text|default:''}></td>
    <td><{$regdetails.firstname|default:''}></td>
    <td><{$regdetails.lastname|default:''}></td>
    <td><{$regdetails.email|default:''}></td>
    <{foreach item=answer from=$regdetails.answers}>
        <td><{$answer|default:''}></td>
    <{/foreach}>
    <td>
        <{$regdetails.status_text}>
        <{if $registration.event_register_max|default:0 > 0 && $regdetails.listwait|default:false}>
            <br><img src="<{$wgevents_icons_url_16}>/attention.png" alt="<{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT}>" title="<{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT}>"><{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT}>
        <{/if}>
    </td>
    <{if $registration.event_fee|default:0 > 0}>
        <td><{$regdetails.financial_text}></td>
    <{/if}>
    <{if $showSubmitter|default:false}>
        <td><{$regdetails.submitter_text}></td>
    <{/if}>
    <td><{$regdetails.datecreated_text}></td>
    <td>
        <{if $regdetails.permRegistrationApprove|default:''}>
            <{if $regdetails.financial|default:0 > 0}>
                <a class='btn btn-primary right wge-btn-2' href='registration.php?op=change_financial&amp;changeto=0&amp;redir=<{$redir}>&amp;id=<{$regdetails.id}>&amp;evid=<{$regdetails.evid}>' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_0}>'><img src="<{$wgevents_icons_url_16}>/wallet.png" alt='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_0}> title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_0}>></a>
            <{else}>
                <a class='btn btn-primary right wge-btn-2' href='registration.php?op=change_financial&amp;changeto=1&amp;redir=<{$redir}>&amp;id=<{$regdetails.id}>&amp;evid=<{$regdetails.evid}>' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_1}>'><img src="<{$wgevents_icons_url_16}>/wallet.png" alt='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_1}> title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_1}>></a>
            <{/if}>
            <{if $regdetails.listwait|default:0 > 0}>
                <a class='btn btn-primary right wge-btn-2' href='registration.php?op=listwait_takeover&amp;redir=<{$redir}>&amp;id=<{$regdetails.id}>&amp;evid=<{$regdetails.evid}>' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT_TAKEOVER}>'><i class="fa fa-user-plus fa-fw"></i></a>
            <{/if}>
        <{/if}>
        <{if $regdetails.permRegistrationEdit|default:''}>
            <a class='btn btn-primary right wge-btn-2' href='registration.php?op=edit&amp;redir=<{$redir}>&amp;id=<{$regdetails.id}>' title='<{$smarty.const._EDIT}>'><i class="fa fa-edit fa-fw"></i></i></a>
            <a class='btn btn-danger right wge-btn-2' href='registration.php?op=delete&amp;redir=<{$redir}>&amp;evid=<{$regdetails.evid}>&amp;id=<{$regdetails.id}>' title='<{$smarty.const._DELETE}>'><i class="fa fa-trash fa-fw"></i></a>
        <{/if}>
    </td>
</tr>
