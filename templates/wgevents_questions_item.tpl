<tr id="order_<{$question.id}>">
    <td><img src="<{$wgevents_icons_url_16}>/up_down.png"></td>
    <td><{$question.type_text}><i id='queId_<{$question.que_id}>'></i></td>
    <td><{$question.caption}></td>
    <td><{$question.desc}></td>
    <td><{$question.value_list}></td>
    <td><{$question.placeholder}></td>
    <td><{$question.required}></td>
    <td><{$question.print}></td>
    <td style="min-width:200px;">
        <{if $permEdit|default:''}>
        <a class='btn btn-primary right wge-btn' href='questions.php?op=edit&amp;que_id=<{$question.que_id}>&amp;que_evid=<{$question.que_evid}>' title='<{$smarty.const._EDIT}>'><i class="fa fa-edit fa-fw"></i></a>
        <a class='btn btn-primary right wge-btn' href='questions.php?op=clone&amp;que_id_source=<{$question.que_id}>&amp;que_id=<{$question.que_evid}>' title='<{$smarty.const._CLONE}>'><i class="fa fa-clone fa-fw"></i></a>
        <a class='btn btn-danger right wge-btn' href='questions.php?op=delete&amp;que_id=<{$question.que_id}>&amp;que_evid=<{$question.que_evid}>' title='<{$smarty.const._DELETE}>'><i class="fa fa-trash fa-fw"></i></a>
        <{/if}>
    </td>
</tr>