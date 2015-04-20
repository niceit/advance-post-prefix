jQuery(function(){
    jQuery("span.prefix").each(function(){
        var jParent = jQuery(this).parent("a");
        var link = jQuery(this).attr("onclick");
        var prefix_content = jQuery(this).html();
        var prefix_id = jQuery(this).attr('id');
        var filter_page_id = jQuery(this).attr('data-filter-page');
        jQuery(this).remove();
        jParent.before('<a href="' + '/' + '?page_id=' + filter_page_id + '&prefix_id=' + prefix_id + '">' + prefix_content + '</a>');
    });
});
