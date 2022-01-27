<tr id="order_<{$additional.id}>">
    <td><img src="<{$wgevents_icons_url_16}>/up_down.png"></td>
    <td><{$additional.type_text}><i id='addId_<{$additional.add_id}>'></i></td>
    <td><{$additional.caption}></td>
    <td><{$additional.desc}></td>
    <td><{$additional.value_list}></td>
    <td><{$additional.placeholder}></td>
    <td><{$additional.required}></td>
    <td><{$additional.print}></td>
    <td style="min-width:200px;">
        <{if $permEdit|default:''}>
        <a class='btn btn-primary right wge-btn' href='additionals.php?op=edit&amp;add_id=<{$additional.add_id}>&amp;add_evid=<{$additional.add_evid}>' title='<{$smarty.const._EDIT}>'><i class="fa fa-edit fa-fw"></i></a>
        <a class='btn btn-primary right wge-btn' href='additionals.php?op=clone&amp;add_id_source=<{$additional.add_id}>&amp;add_id=<{$additional.add_evid}>' title='<{$smarty.const._CLONE}>'><i class="fa fa-clone fa-fw"></i></a>
        <a class='btn btn-danger right wge-btn' href='additionals.php?op=delete&amp;add_id=<{$additional.add_id}>&amp;add_evid=<{$additional.add_evid}>' title='<{$smarty.const._DELETE}>'><i class="fa fa-trash fa-fw"></i></a>
        <{/if}>
    </td>
</tr>