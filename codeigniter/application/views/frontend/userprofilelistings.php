<div id="results-profile" class="properties-rows">

<br style="clear:both;" />
<span id="results_top"></span>

<div class="results_count sw_widget">
    <h2 class="widget-title"><?php echo __('Other user listings', 'sw_win'); ?>: <?php echo $listings_count; ?></h2>
</div>

<?php

    $grid_active = 'active';
    $list_active = '';
    
    $_MERG = array_merge($_GET, $_POST);
    
    if(isset($_MERG['search_view']) && $_MERG['search_view'] == 'list')
    {
        $grid_active = '';
        $list_active = 'active';
    }
    
    $order_dropdown = array('idlisting ASC'    => __('By publish date ASC', 'sw_win'),
                            'idlisting DESC'   => __('By publish date DESC', 'sw_win'),
                            'idlisting DESC,field_36_int ASC' => __('By price ASC', 'sw_win'),
                            'idlisting DESC,field_36_int DESC'=> __('By price DESC', 'sw_win'));

?>

<div class="sw-order-view">
    <a class="view-type <?php echo $grid_active; ?>" ref="grid" href="#"><img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/glyphicons/glyphicons_156_show_thumbnails.png"></a>
    <a class="view-type <?php echo $list_active; ?>" ref="list" href="#"><img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/glyphicons/glyphicons_157_show_thumbnails_with_lines.png"></a>
    
    <div class="control-group options">
        <label for="search_order" class="control-label"><?php echo __('Order By', 'sw_win'); ?></label>
        <div class="controls">
            <?php echo form_dropdown('search_order', $order_dropdown, search_value('order', NULL, 'idlisting DESC'), 'id="search_order" class="form-control pull-right selectpicker-small" ')?>
        </div><!-- /.controls -->
    </div><!-- /.control-group -->
</div>
<br style="clear:both;" />

<?php if($listings_count == 0): ?>
<div class="row sw-listing-results">
    <div class="col-xs-12">
    <div class="alert alert-info" role="alert"><?php echo __('Results not found', 'sw_win'); ?></div>
    </div>
</div>
<?php endif; ?>

<?php if(!empty($list_active)): // is list view ?>

<div class="row sw-listing-results">
<?php foreach($listings as $key=>$listing): ?>
    <div class="col-xs-12">
        <div class="property-card card  property-card-list row-fluid clearfix">
            <div class="property-card-header image-box col-sm-4">
                <img src="<?php echo _show_img($listing->image_filename, '260x165', false); ?>" alt="" class="">
                <?php if($listing->is_featured): ?>
                <div class="budget"><i class="fa fa-star"></i></div>
                <?php endif; ?>
                <a href="<?php echo listing_url($listing); ?>" class="property-card-hover">
                    <img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/property-hover-arrow.png" alt="" class="left-icon">
                    <img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/plus.png" alt="" class="center-icon">
                    <img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/icon-notice.png" alt="" class="right-icon">
                </a>
            </div>
            <div class="col-sm-8">
                <div class="property-card-tags">
                    <span class="label label-default label-tag-warning color-<?php echo url_title(_field($listing, 4), '-', TRUE); ?>"><?php echo _field($listing, 4); ?></span>
                </div>
                <div class="property-card-box card-box card-block">
                    <h3 class="property-card-title"><a href="<?php echo listing_url($listing); ?>"><?php echo _field($listing, 10); ?></a></h3>
                    <div class="property-card-descr"><?php echo _field($listing, 8, 210); ?></div>
                    <div class="property-preview-footer  clearfix">
                        <div class="property-preview-f-right">
                            <a href="<?php echo listing_url($listing); ?>" class="btn btn-details text-uppercase"><?php echo __('View details', 'sw_win'); ?></a>
                        </div>
                        <div class="property-preview-f-left">
                        <?php
                            // show items from visual result item builder
                            _show_items($listing, 2);
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
    
</div>

<?php else: ?>
<div class="row sw-listing-results">
<?php foreach($listings as $key=>$listing): ?>
    <div class="col-md-4 col-sm-6">
        <div class="property-card card">
            <div class="property-card-header image-box">
                <img src="<?php echo _show_img($listing->image_filename, '260x165', false); ?>" alt="" class="">
                <?php if($listing->is_featured): ?>
                <div class="budget"><i class="fa fa-star"></i></div>
                <?php endif; ?>
                <a href="<?php echo listing_url($listing); ?>" class="property-card-hover">
                    <img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/property-hover-arrow.png" alt="" class="left-icon">
                    <img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/plus.png" alt="" class="center-icon">
                    <img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/icon-notice.png" alt="" class="right-icon">
                </a>
            </div>
            <div class="property-card-tags">
                <span class="label label-default label-tag-warning color-<?php echo url_title(_field($listing, 4), '-', TRUE); ?>"><?php echo _field($listing, 4); ?></span>
            </div>
            <div class="property-card-box card-box card-block">
                <h3 class="property-card-title"><a href="<?php echo listing_url($listing); ?>"><?php echo _field($listing, 10); ?></a></h3>
                <div class="property-card-descr"><?php echo _field($listing, 8, 95); ?></div>
                <div class="property-preview-footer  clearfix">
                    <div class="property-preview-f-left">
                        <?php
                            // show items from visual result item builder
                            _show_items($listing, 2);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 

// solve issue with possible larger result items
if( ($key+1) % 3 == 0  )
{
    echo '<br style="clear:both;" class="hidden-sm" />';
}
if( ($key+1) % 2 == 0  )
{
    echo '<br style="clear:both;" class="visible-sm" />';
}
if( ($key+1) == sw_count($listings)  )
{
    echo '<br style="clear:both;" />';
}
?>
<?php endforeach; ?>
    
</div>

<?php endif; ?>

<?php echo $pagination_links; ?>

</div>