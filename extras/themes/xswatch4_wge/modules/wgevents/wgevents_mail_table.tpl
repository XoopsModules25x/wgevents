<!-- table for mail notifications for modify event -->
<style>
    .mail_table th,
    .mail_table td {
        border:1px solid #000;
        margin:0;
        padding:5px;
    }
</style>
<table class="mail_table">
    <tr>
        <th class="head"><{$smarty.const._MA_WGEVENTS_MAIL_REG_MODIFICATION_VAL}></th>
        <th class="head"><{$smarty.const._MA_WGEVENTS_MAIL_REG_MODIFICATION_FROM}></th>
        <th class="head"><{$smarty.const._MA_WGEVENTS_MAIL_REG_MODIFICATION_TO}></th>
    </tr>
    <{foreach item=value from=$changedValues}>
        <tr>
            <td><{$value.caption|default:''}></td>
            <td><{$value.valueOld|default:''}></td>
            <td><{$value.valueNew|default:''}></td>
        </tr>
    <{/foreach}>
</table>
