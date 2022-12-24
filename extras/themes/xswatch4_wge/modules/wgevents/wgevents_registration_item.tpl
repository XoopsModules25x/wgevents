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
        <span id="lbl_status_<{$regdetails.id}>"><{$regdetails.status_text}></span>
        <{if $registration.event_register_max|default:0 > 0 && $regdetails.listwait|default:false}>
            <br><span id="lbl_listwait_<{$regdetails.id}>"><img src="<{$wgevents_icons_url_16}>/attention.png" alt="<{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT}>" title="<{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT}>"><{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT}></span>
        <{/if}>
    </td>
    <{if $registration.evfees_count|default:0 > 0}>
        <td id="financial_<{$regdetails.id}>"><{$regdetails.financial_text}></td>
        <td id="paidamount_<{$regdetails.id}>"><{$regdetails.paidamount_text}></td>
    <{/if}>
    <{if $showSubmitter|default:false}>
        <td><{$regdetails.submitter_text}></td>
    <{/if}>
    <td><{$regdetails.datecreated_text}></td>
    <td>
        <{if $regdetails.permRegistrationApprove|default:''}>
            <{if $registration.evfees_count|default:0 > 1}>
                <div class="btn-group">
                    <button type="button" class="btn <{if $regdetails.financial|default:0 > 0}>btn-success<{else}>btn-primary<{/if}> dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL}>'>
                        <i class="fa fa-money fa-fw"></i>
                    </button>
                    <div class="dropdown-menu">
                        <{if $regdetails.financial|default:0 > 0}>
                            <a id='btn_change_financial_0_<{$regdetails.id}>' class='dropdown-item' href='javascript:change_financial(<{$regdetails.id}>, <{$regdetails.evid}>, 0, "<{$js_feezero_text|default:'?'}>", 0)' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_0}>'><{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_0}></a>
                            <div class="dropdown-divider"></div>
                        <{/if}>
                        <{foreach item=evfee from=$registration.evfees|default:false name=evfee}>
                            <a id='btn_change_financial_1_<{$regdetails.id}>' class='dropdown-item' href='javascript:change_financial(<{$regdetails.id}>, <{$regdetails.evid}>, 1, "<{$evfee.text|default:'?'}>", <{$evfee.value|default:0}>)' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_1}>'><{$evfee.text|default:'?'}></a>
                        <{/foreach}>
                    </div>
                </div>
            <{else}>
                <{if $regdetails.financial|default:0 > 0}>
                    <a id='btn_change_financial_0_<{$regdetails.id}>' class='btn btn-success right wge-btn-1' href='javascript:change_financial(<{$regdetails.id}>, <{$regdetails.evid}>, 0, "<{$js_feezero_text|default:'?'}>", 0)' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_0}>'><i class="fa fa-money fa-fw"></i></a>
                    <a id='btn_change_financial_1_<{$regdetails.id}>' class='btn btn-primary right wge-btn-1 hidden' href='javascript:change_financial(<{$regdetails.id}>, <{$regdetails.evid}>, 1, "<{$js_feedefault_text|default:'?'}>", <{$js_feedefault_value|default:'?'}>)' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_1}>'><i class="fa fa-money fa-fw"></i></a>
                <{else}>
                    <a id='btn_change_financial_0_<{$regdetails.id}>' class='btn btn-success right wge-btn-1 hidden' href='javascript:change_financial(<{$regdetails.id}>, <{$regdetails.evid}>, 0, "<{$js_feezero_text|default:'?'}>", 0)' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_0}>'><i class="fa fa-money fa-fw"></i></a>
                    <a id='btn_change_financial_1_<{$regdetails.id}>' class='btn btn-primary right wge-btn-1' href='javascript:change_financial(<{$regdetails.id}>, <{$regdetails.evid}>, 1, "<{$js_feedefault_text|default:'?'}>", <{$js_feedefault_value|default:'?'}>)' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL_CHANGE_1}>'><i class="fa fa-money fa-fw"></i></a>
                <{/if}>
            <{/if}>
            <{if $regdetails.listwait|default:0 > 0}>
                <a id='btn_listwait_<{$regdetails.id}>' class='btn btn-primary right wge-btn-1' href='javascript:listwait_takeover(<{$regdetails.id}>, <{$regdetails.evid}>)' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_LISTWAIT_TAKEOVER}>'><i class="fa fa-user-plus fa-fw"></i></a>
            <{/if}>
            <{if $regdetails.permRegistrationConfirm|default:false}>
                <a id='btn_approve_status_<{$regdetails.id}>' class='btn btn-primary right wge-btn-1' href='javascript:approve_status(<{$regdetails.id}>, <{$regdetails.evid}>)' title='<{$smarty.const._MA_WGEVENTS_REGISTRATION_CONFIRM}>'><i class="fa fa-check fa-fw"></i></a>
            <{/if}>
        <{/if}>
        <{if $regdetails.permRegistrationEdit|default:''}>
            <a class='btn btn-primary right wge-btn-1' href='registration.php?op=edit&amp;redir=<{$redir}>&amp;id=<{$regdetails.id}>' title='<{$smarty.const._EDIT}>'><i class="fa fa-edit fa-fw"></i></i></a>
            <a class='btn btn-danger right wge-btn-1' href='registration.php?op=delete&amp;redir=<{$redir}>&amp;evid=<{$regdetails.evid}>&amp;id=<{$regdetails.id}>' title='<{$smarty.const._DELETE}>'><i class="fa fa-trash fa-fw"></i></a>
        <{/if}>
    </td>
</tr>
