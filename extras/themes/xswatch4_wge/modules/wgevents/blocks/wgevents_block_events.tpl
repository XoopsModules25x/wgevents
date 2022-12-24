<i id="wge_block_events"></i>
<{include file="db:wgevents_block_events_$wgevents_blocktype.tpl" }>

<script src="<{$wgevents_url|default:false}>/assets/js/expander/jquery.expander.min.js"></script>
<script>
    $(document).ready(function() {
        var opts = {
            slicePoint: <{$user_maxchar|default:100}>,
                expandText:'<{$smarty.const._MA_WGEVENTS_READMORE}>',
                    moreLinkClass:'btn btn-success btn-sm',
                    expandSpeed: 500,
                    userCollapseText:'<{$smarty.const._MA_WGEVENTS_READLESS}>',
                        lessLinkClass:'btn btn-success btn-sm',
                        expandEffect: 'slideDown',
                        collapseEffect: 'slideUp',
                        collapseSpeed: 500,
        };

        $('div.expander').expander(opts);
    });
</script>

