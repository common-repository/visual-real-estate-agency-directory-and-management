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