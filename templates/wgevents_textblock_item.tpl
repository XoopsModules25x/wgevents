<i id='tbId_<{$textblock.id}>'></i>
<div class='panel-heading'>
</div>
<div class='panel-body'>
    <span class='col-sm-9 justify'><{$textblock.name}></span>
    <span class='col-sm-9 justify'><{$textblock.text}></span>
</div>
<div class='panel-foot'>
    <div class='col-sm-12 right'>
        <{if !$showItem|default:false}>
            <a class='btn btn-success right wge-btn' href='textblock.php?op=show&amp;id=<{$textblock.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._MA_WGEVENTS_DETAILS}>'><{$smarty.const._MA_WGEVENTS_DETAILS}></a>
        <{/if}>
        <{if $textblock.permEdit|default:''}>
            <a class='btn btn-primary right wge-btn' href='textblock.php?op=edit&amp;id=<{$textblock.id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._EDIT}>'><{$smarty.const._EDIT}></a>
            <a class='btn btn-primary right wge-btn' href='textblock.php?op=clone&amp;id_source=<{$textblock.id}>' title='<{$smarty.const._CLONE}>'><{$smarty.const._CLONE}></a>
            <a class='btn btn-danger right wge-btn' href='textblock.php?op=delete&amp;id=<{$textblock.id}>' title='<{$smarty.const._DELETE}>'><{$smarty.const._DELETE}></a>
        <{/if}>
    </div>
</div>
