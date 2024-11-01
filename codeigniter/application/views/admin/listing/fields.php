<?php

wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-widget', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-sortable', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-droppable', false, array('jquery'), false, false);
wp_enqueue_script( 'jquery-nestedSortable', false, array('jquery'), false, false );

?>

<h1><?php echo __('Listing fields','sw_win'); ?> <a href="<?php menu_page_url( 'listing_addfield', true ); ?>" class="page-title-action"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo __('Add New','sw_win')?></a></h1>

<div class="bootstrap-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Fields structure','sw_win'); ?></h3>
        </div>
        <div class="panel-body">
        
            <?php _form_messages(); ?>
        
            <div id="orderResult">
            <?php echo get_ol($fields_nested)?>
            </div>
        </div>
    </div>
</div>


<script>

jQuery(document).ready(function($) {

    $('#option_sortable').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div',
        maxLevels: 2,
        isAllowed: function(item, parent) {
            
            // category can be only child of root element
            if($(item).find('.label-danger').length == 1)
            {
                if($(parent).length > 0)return false;
            }
            
            return true; 
        },
        dropedCallback: sortableDroped
    });
    
    function sortableDroped()
    {
        oSortable = null;
        if($('#option_sortable').length)
            oSortable = $('#option_sortable').nestedSortable('toArray');
        startLoading();
        
    	$.post(ajaxurl, 
        { action: 'ci_action', page: 'listing_updateajax', sortable: oSortable }, 
        function(data){
            endLoading();
    	}, "json");
    }
    
    function startLoading(){
        //$('#saveAll, #add-new-page, ol.sortable button, #saveRevision').button('loading');
    }
    
    function endLoading(){
        //$('#saveAll, #add-new-page, ol.sortable button, #saveRevision').button('reset');       
        <?php if(config_item('app_type') == 'demo'):?>
            ShowStatus.show('<?php echo_js(__('Data editing disabled in demo', 'sw_win')); ?>');
        <?php else:?>
            //ShowStatus.show('<?php echo_js(__('data_saved', 'sw_win')); ?>');
        <?php endif;?>
    }

});

</script>

<style>

/* Sortable start */

.placeholder {
	outline: 1px dashed #4183C4;
	/*-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	margin: -1px;*/
}

.mjs-nestedSortable-error {
	background: #fbe3e4;
	border-color: transparent;
}

ol {
	margin: 0;
	padding: 0;
	padding-left: 30px;
}

ol.sortable, ol.sortable ol {
	margin: 0 0 0 25px;
	padding: 0;
	list-style-type: none;
}

ol.sortable {
	padding: 10px;
    margin:0px;
}

.sortable li {
	margin: 5px 0 0 0;
	padding: 0;
}

.sortable li div  {
	border: 1px solid #d4d4d4;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	border-color: #D4D4D4 #D4D4D4 #BCBCBC;
	padding: 6px;
	margin: 0;
	cursor: move;
	background: #f6f6f6;
}

.sortable li>div:hover
{
    background: white;
}

.sortable li.mjs-nestedSortable-branch div {
	background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #f0ece9 100%);
	background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#f0ece9 100%);

}

.sortable li.mjs-nestedSortable-leaf div {
	background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #bcccbc 100%);
	background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#bcccbc 100%);

}

li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
	border-color: #999;
	background: #fafafa;
}

.disclose {
	cursor: pointer;
	width: 10px;
	display: none;
}

.sortable li.mjs-nestedSortable-collapsed > ol {
	display: none;
}

.sortable li.mjs-nestedSortable-branch > div > .disclose {
	display: inline-block;
}

.sortable li.mjs-nestedSortable-collapsed > div > .disclose > span:before {
	content: '+ ';
}

.sortable li.mjs-nestedSortable-expanded > div > .disclose > span:before {
	content: '- ';
}

.sortable .btn-group
{
    padding:0px; margin:0px;
    border:0px;
}

.sortable .btn-group, .btn-group-vertical
{
    display: block;
}

#option_sortable div
{
    font-size: 15px;
    line-height: 1.5em;
}

/* Sortable end */



</style>
    