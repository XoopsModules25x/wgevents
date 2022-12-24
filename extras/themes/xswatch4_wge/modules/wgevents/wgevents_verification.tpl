<{include file='db:wgevents_header.tpl' }>

<div class="wge-eventheader">
    <h3 class="center"><{$smarty.const._MA_WGEVENTS_MAIL_REG_VERIF_INFO}></h3>
</div>

<{if $verif_success|default:''}>
    <div class="alert alert-success"><{$verif_success}></div>
<{/if}>

<{if $verif_error|default:''}>
    <div class="alert alert-warning"><{$verif_error}></div>
<{/if}>


<{include file='db:wgevents_footer.tpl' }>
