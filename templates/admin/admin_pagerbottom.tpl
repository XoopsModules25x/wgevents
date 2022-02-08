<!-- pager -->
<div id="pager" class="pager">
    <form>
        <img src="<{$mod_url}>/assets/js/tablesorter/css/images/first.png" class="first"/>
        <img src="<{$mod_url}>/assets/js/tablesorter/css/images/prev.png" class="prev"/>
        <!-- the "pagedisplay" can be any element, including an input -->
        <span class="pagedisplay" data-pager-output-filtered="{startRow:input} &ndash;{endRow} / {filteredRows} of {totalRows} total rows"></span>
        <img src="<{$mod_url}>/assets/js/tablesorter/css/images/next.png" class="next"/>
        <img src="<{$mod_url}>/assets/js/tablesorter/css/images/last.png" class="last"/>
        <select class="pagesize">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="all">All Rows</option>
        </select>
    </form>
</div>
