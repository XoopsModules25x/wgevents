<i id='tbId_<{$textblock.tb_id}>'></i>
<div class='panel-heading'>
</div>
<div class='panel-body'>
    <span class='col-sm-9 justify'><{$textblock.name}></span>
    <span class='col-sm-9 justify'><{$textblock.text}></span>
    <span class='col-sm-9 justify'><{$textblock.weight}></span>
</div>
<div class='panel-foot'>
    <div class='col-sm-12 right'>
        <{if $showItem|default:''}>
            <a class='btn btn-success right wge-btn' href='textblocks.php?op=list&amp;start=<{$start}>&amp;limit=<{$limit}>#tbId_<{$textblock.tb_id}>' title='<{$smarty.const._MA_WGEVENTS_TEXTBLOCKS_LIST}>'><{$smarty.const._MA_WGEVENTS_TEXTBLOCKS_LIST}></a>
        <{else}>
            <a class='btn btn-success right wge-btn' href='textblocks.php?op=show&amp;tb_id=<{$textblock.tb_id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._MA_WGEVENTS_DETAILS}>'><{$smarty.const._MA_WGEVENTS_DETAILS}></a>
        <{/if}>
        <{if $permEdit|default:''}>
            <a class='btn btn-primary right wge-btn' href='textblocks.php?op=edit&amp;tb_id=<{$textblock.tb_id}>&amp;start=<{$start}>&amp;limit=<{$limit}>' title='<{$smarty.const._EDIT}>'><{$smarty.const._EDIT}></a>
            <a class='btn btn-primary right wge-btn' href='textblocks.php?op=clone&amp;tb_id_source=<{$textblock.tb_id}>' title='<{$smarty.const._CLONE}>'><{$smarty.const._CLONE}></a>
            <a class='btn btn-danger right wge-btn' href='textblocks.php?op=delete&amp;tb_id=<{$textblock.tb_id}>' title='<{$smarty.const._DELETE}>'><{$smarty.const._DELETE}></a>
        <{/if}>
    </div>
</div>
