<tr id="order_<{$question.id}>">
    <td><img src="<{$wgevents_icons_url_16}>/up_down.png"></td>
    <td><{$question.type_text}><i id='queId_<{$question.id}>'></i></td>
    <td><{$question.caption}></td>
    <td><{$question.desc_text}></td>
    <td><{$question.value_list}></td>
    <td><{$question.placeholder}></td>
    <td><{$question.required_text}></td>
    <td><{$question.print_text}></td>
    <td style="min-width:200px;">
        <{if $permEdit|default:''}>
        <a class='btn btn-primary right wge-btn' href='question.php?op=edit&amp;id=<{$question.id}>&amp;evid=<{$question.evid}>' title='<{$smarty.const._EDIT}>'><i class="fa fa-edit fa-fw"></i></a>
        <a class='btn btn-primary right wge-btn' href='question.php?op=clone&amp;id_source=<{$question.id}>&amp;id=<{$question.evid}>' title='<{$smarty.const._CLONE}>'><i class="fa fa-clone fa-fw"></i></a>
        <a class='btn btn-danger right wge-btn' href='question.php?op=delete&amp;id=<{$question.id}>&amp;evid=<{$question.evid}>' title='<{$smarty.const._DELETE}>'><i class="fa fa-trash fa-fw"></i></a>
        <{/if}>
    </td>
</tr>