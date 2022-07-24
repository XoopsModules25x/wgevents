<!-- pager -->
<div id="pager" class="pager">
    <form>
        <img src="<{$mod_url}>/assets/js/tablesorter/css/images/first.png" class="first"/>
        <img src="<{$mod_url}>/assets/js/tablesorter/css/images/prev.png" class="prev"/>
        <!-- the "pagedisplay" can be any element, including an input -->
        <span class="pagedisplay" data-pager-output="{startRow:input} &ndash; {endRow} / {totalRows} <{$tablesorter_total}>" data-pager-output-filtered="{startRow:input} &ndash;{endRow} / {filteredRows} <{$tablesorter_of}> {totalRows} <{$tablesorter_total}>"></span>
        <img src="<{$mod_url}>/assets/js/tablesorter/css/images/next.png" class="next"/>
        <img src="<{$mod_url}>/assets/js/tablesorter/css/images/last.png" class="last"/>
        <select class="pagesize">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="all"><{$tablesorter_allrows}></option>
        </select>
    </form>
</div>

<script>
    $(function() {

        // **********************************
        //  Description of ALL pager options
        // **********************************
        var pagerOptions = {

            // target the pager markup - see the HTML block below
            container: $(".pager"),

            // use this url format "http:/mydatabase.com?page={page}&size={size}&{sortList:col}"
            ajaxUrl: null,

            // modify the url after all processing has been applied
            customAjaxUrl: function(table, url) { return url; },

            // ajax error callback from $.tablesorter.showError function
            // ajaxError: function( config, xhr, settings, exception ) { return exception; };
            // returning false will abort the error message
            ajaxError: null,

            // add more ajax settings here
            // see http://api.jquery.com/jQuery.ajax/#jQuery-ajax-settings
            ajaxObject: { dataType: 'json' },

            // process ajax so that the data object is returned along with the total number of rows
            ajaxProcessing: null,

            // Set this option to false if your table data is preloaded into the table, but you are still using ajax
            processAjaxOnInit: true,

            // output string - default is '{page}/{totalPages}'
            // possible variables: {size}, {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
            // also {page:input} & {startRow:input} will add a modifiable input in place of the value
            // In v2.27.7, this can be set as a function
            // output: function(table, pager) { return 'page ' + pager.startRow + ' - ' + pager.endRow; }
            output: '{startRow:input} – {endRow} / {totalRows} rows',

            // apply disabled classname (cssDisabled option) to the pager arrows when the rows
            // are at either extreme is visible; default is true
            updateArrows: true,

            // starting page of the pager (zero based index)
            page: 0,

            // Number of visible rows - default is 10
            size: <{$tablesorter_pagesize}>,

            // Save pager page & size if the storage script is loaded (requires $.tablesorter.storage in jquery.tablesorter.widgets.js)
            savePages : true,

            // Saves tablesorter paging to custom key if defined.
            // Key parameter name used by the $.tablesorter.storage function.
            // Useful if you have multiple tables defined
            storageKey:'tablesorter-pager',

            // Reset pager to this page after filtering; set to desired page number (zero-based index),
            // or false to not change page at filter start
            pageReset: 0,

            // if true, the table will remain the same height no matter how many records are displayed. The space is made up by an empty
            // table row set to a height to compensate; default is false
            fixedHeight: false,

            // remove rows from the table to speed up the sort of large tables.
            // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
            removeRows: false,

            // If true, child rows will be counted towards the pager set size
            countChildRows: false,

            // css class names of pager arrows
            cssNext: '.next', // next page arrow
            cssPrev: '.prev', // previous page arrow
            cssFirst: '.first', // go to first page arrow
            cssLast: '.last', // go to last page arrow
            cssGoto: '.gotoPage', // select dropdown to allow choosing a page

            cssPageDisplay: '.pagedisplay', // location of where the "output" is displayed
            cssPageSize: '.pagesize', // page size selector - select dropdown that sets the "size" option

            // class added to arrows when at the extremes (i.e. prev/first arrows are "disabled" when on the first page)
            cssDisabled: 'disabled', // Note there is no period "." in front of this class name
            cssErrorRow: 'tablesorter-errorRow' // ajax error information row

        };

        $("table")

            // Initialize tablesorter
            // ***********************
            .tablesorter({
                theme: '<{$tablesorter_theme}>',
                widthFixed: true,
                widgets: ['zebra', 'filter'],
                dateFormat: '<{$tablesorter_dateformat}>', // set the default date format
            })

            // bind to pager events
            // *********************
            .bind('pagerChange pagerComplete pagerInitialized pageMoved', function(e, c) {
                var msg = '"</span> event triggered, ' + (e.type === 'pagerChange' ? 'going to' : 'now on') +
                    ' page <span class="typ">' + (c.page + 1) + '/' + c.totalPages + '</span>';
                $('#display')
                    .append('<li><span class="str">"' + e.type + msg + '</li>')
                    .find('li:first').remove();
            })

            // initialize the pager plugin
            // ****************************
            .tablesorterPager(pagerOptions);

    });

</script>
