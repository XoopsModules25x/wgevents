<{if $category.eventsCount|default:0 > 0 || $category.id|default:0 == 0}>
    <span class="btn <{if $category.id != $categoryCurrent}>btn-primary<{else}>btn-success<{/if}><{if $category.eventsCount == 0 && $category.id > 0}> disabled<{/if}>">
        <{if $category.id != $categoryCurrent}>
            <a href="<{$wgevents_url|default:false}>/index.php?op=list&amp;cat_id=<{$category.id}>" alt="<{$category.name}>" title="<{$category.name}>">
        <{/if}>
        <{if $category.logo|default:'blank.gif' != 'blank.gif'}>
            <img class="<{if $category.eventsCount|default:0 > 0}>wge-cat<{/if}>" style="height:30px" src='<{$wgevents_upload_catlogos_url|default:false}>/<{$category.logo}>' alt='<{$category.name}>' >
        <{/if}>
        <{$category.name}> (<{$category.eventsCount}>)
        <{if $categoryCurrent != $category.id}>
            </a>
        <{/if}>
    </span>
<{/if}>