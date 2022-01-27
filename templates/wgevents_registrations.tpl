<{include file='db:wgevents_header.tpl' }>

<{if $registrationsCount|default:0 > 0}>
    <{foreach item=registration from=$registrations}>
        <div class="wge-eventheader">
            <h3><{if $captionList|default:false}><{$captionList}>: <{/if}><{$registration.event_name|default:''}></h3>
        </div>
        <div class='table-responsive'>
            <table class='table table-<{$table_type|default:false}>'>
                <thead>
                    <tr class='head'>
                        <th>&nbsp;</th>
                        <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_SALUTATION}></th>
                        <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_FIRSTNAME}></th>
                        <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_LASTNAME}></th>
                        <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_EMAIL}></th>
                        <{foreach item=question from=$registration.questions}>
                            <th><{$question.caption|default:'false'}></th>
                        <{/foreach}>
                        <th><{$smarty.const._MA_WGEVENTS_STATUS}></th>
                        <{if $registration.event_fee|default:0 > 0}>
                            <th><{$smarty.const._MA_WGEVENTS_REGISTRATION_FINANCIAL}></th>
                        <{/if}>
                        <{if $showSubmitter|default:false}>
                            <th><{$smarty.const._MA_WGEVENTS_SUBMITTER}></th>
                        <{/if}>
                        <th><{$smarty.const._MA_WGEVENTS_DATECREATED}></th>
                        <th style="min-width:250px;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <{foreach item=regdetails from=$registration.details name=regdetails}>
                        <{include file='db:wgevents_registrations_item.tpl' }>
                    <{/foreach}>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="center" colspan="<{$registration.footerCols}>">
                            <a class='btn btn-success wge-btn' href='events.php?op=show&amp;ev_id=<{$registration.ev_id}>' title='<{$smarty.const._MA_WGEVENTS_GOTO_EVENT}>'><{$smarty.const._MA_WGEVENTS_GOTO_EVENT}></a>
                            <{if $regdetails.permEdit|default:''}>
                                <a class='btn btn-success wge-btn' href='outputs.php?op=reg_all&amp;output_type=xlsx&amp;ev_id=<{$registration.ev_id}>&amp;redir=<{$redir}>' title='<{$smarty.const._MA_WGEVENTS_OUTPUT_EXCEL}>'><{$smarty.const._MA_WGEVENTS_OUTPUT_EXCEL}></a>
                            <{/if}>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <{/foreach}>
<{else}>
    <div class="alert alert-warning">
        <{$smarty.const._MA_WGEVENTS_REGISTRATIONS_THEREARENT}>
    </div>
<{/if}>

<{if $warning_period|default:''}>
    <div class="alert alert-warning"><{$warning_period}></div>
<{/if}>

<{if $form|default:''}>
    <div class="wge-form-registration"><{$form|default:false}></div>
<{/if}>
<{if $error|default:''}>
    <{$error|default:false}>
<{/if}>

<{include file='db:wgevents_footer.tpl' }>
