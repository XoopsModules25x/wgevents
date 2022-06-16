<!-- Header -->
<{include file='db:wgevents_admin_header.tpl'}>
<style>
    .btn {
        margin:0;
        padding: 4px 10px;
        border:1px solid #ccc;
        border-radius:5px;
        line-height:26px;
    }
</style>

<{if $system_check|default:''}>
<{*	<table class='table table-bordered'>*}>

    <{include file='db:admin_pagertop.tpl' }>

    <table id="sortTable" class="tablesorter-<{$tablesorter_theme}>" cellspacing="1" cellpadding="0"  width="100%">


		<thead>
			<tr class='head'>
				<th class='center' style='width:50%'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_CHECK_SYSTEM}></th>
				<th class='center' style='width:50%' colspan='2'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_CHECK_RESULTS}></th>
			</tr>
		</thead>
		<tbody>
			<{foreach item=check from=$system_check}>
				<tr class="<{cycle values='odd, even'}>">
					<td class='left'><div style="font-size:100%;margin:10px 0 5px 0;"><{$check.type}></div><div style="font-size:80%;margin:0 0 10px 0;"><{$check.info1}></div></td>
					<td class='left'><{$check.result1}><{if $check.result2|default:''}><br><{$check.result2}><{/if}></td>
					<td class='left'>
						<{if $check.change|default:''}>
							<img src="<{$wggallery_icon_url_16}>off.png" alt="_AM_WGEVENTS_MAINTENANCE_CHECKOK"> <{$check.solve}> <{if $check.info2|default:''}><br><{/if}>
						<{else}>
							<img src="<{$wggallery_icon_url_16}>on.png" alt="_AM_WGEVENTS_MAINTENANCE_CHECKOK"> 
						<{/if}>
						<{$check.info2|default:''}>
					</td>
				</tr>
			<{/foreach}>
		</tbody>
	</table>
	<p><a class='btn pull-right' href='maintenance.php?op=list' title='<{$smarty.const._CO_WGEVENTS_BACK}>'><{$smarty.const._CO_WGEVENTS_BACK}></a></p>
	<br><br>
<{else}>
    <table class='table table-bordered'>
        <thead>
            <tr class='head'>
                <th class='center' style='width:10%'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_TYP}></th>
                <th class='center' style='width:30%'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_DESC}></th>
                <{if $show_result|default:false}><th class='center' style='width:35%'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_RESULTS}></th><{/if}>
                <th class='center' style='width:25%'><{$smarty.const._MA_WGEVENTS_ACTION}></th>
            </tr>
        </thead>
        <tbody>
            <{if $invalid_adds_show|default:false}>
				<tr class="<{cycle values='odd, even'}>">
					<td class='left'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_INVALID_QUE}></td>
					<td class='left'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_INVALID_QUE_DESC}></td>
					<{if $show_result|default:false}>
					<td class='left'>
						<{if $result_success|default:''}><span><{$result_success}></span><{/if}>
						<{if $result_error|default:''}><span class='maintenance-error'><{$result_error}></span><{/if}>
					</td>
					<{/if}>
					<td class='center'>
						<p class='maintenance-btn center'><a class='btn' href='maintenance.php?op=invalid_adds_exec' title='<{$smarty.const._MA_WGEVENTS_EXEC}>'><{$smarty.const._MA_WGEVENTS_EXEC}></a></p>
					</td>
				</tr>
			<{/if}>
			<{if $invalid_answers_show|default:false}>
				<tr class="<{cycle values='odd, even'}>">
					<td class='left'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_INVALID_ANSWERS}></td>
					<td class='left'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_INVALID_ANSWERS_DESC}></td>
					<{if $show_result|default:false}>
					<td class='left'>
						<{if $result_success|default:''}><span><{$result_success}></span><{/if}>
						<{if $result_error|default:''}><span class='maintenance-error'><{$result_error}></span><{/if}>
					</td>
					<{/if}>
					<td class='center'>
						<p class='maintenance-btn center'><a class='btn' href='maintenance.php?op=invalid_answers_exec' title='<{$smarty.const._MA_WGEVENTS_EXEC}>'><{$smarty.const._MA_WGEVENTS_EXEC}></a></p>
					</td>
				</tr>
			<{/if}>
			<{if $anon_data_show|default:false}>
				<tr class="<{cycle values='odd, even'}>">
					<td class='left'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_ANON_DATA}></td>
					<td class='left'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_ANON_DATA_DESC}></td>
					<{if $show_result|default:false}>
					<td class='left'>
						<{if $result_success|default:''}><span><{$result_success}></span><{/if}>
						<{if $result_error|default:''}><span class='maintenance-error'><{$result_error}></span><{/if}>
					</td>
					<{/if}>
					<td class='center'>
						<{$formGdpr|default:''}>
					</td>
				</tr>
			<{/if}>

        </tbody>
    </table>
<{/if}>
<{if $show_result|default:false}>
	<p><a class='btn pull-right' href='maintenance.php?op=list' title='<{$smarty.const._AM_WGEVENTS_MAINTENANCE_BACK}>'><{$smarty.const._AM_WGEVENTS_MAINTENANCE_BACK}></a></p>
<{/if}>

<{if $form|default:''}>
	<{$form}>
<{/if}>
<{if $error|default:''}>
	<div class='errorMsg'><strong><{$error}></strong></div>
<{/if}>
<br>
<!-- Footer --><{include file='db:wgevents_admin_footer.tpl'}>
