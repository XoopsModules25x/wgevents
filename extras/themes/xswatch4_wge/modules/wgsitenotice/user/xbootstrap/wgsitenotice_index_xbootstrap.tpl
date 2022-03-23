<{include file="db:wgsitenotice_header.tpl"}>
<div class="wgsitenotice container">
    <div class="row">
      <{foreach item=list from=$contents|default:''}>    
        <div class="wgsitenotice_header text-center bold"><{$list.header|default:''}></div>
        <div class="wgsitenotice_text text-left"><{$list.text|default:''}></div>
      <{/foreach}>
    </div> <!--row -->
</div> <!--wgsitenotice -->
<{include file="db:wgsitenotice_footer.tpl"}>