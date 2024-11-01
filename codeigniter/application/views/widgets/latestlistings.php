<div class="sw-latest-listings">

<?php if(sw_count($listings) == 0): ?>

<div class="alert alert-info" role="alert"><?php echo __('Not available', 'sw_win'); ?></div>

<?php elseif($atts['view_type'] == 'GRID'): ?>

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

<?php else: ?>

<?php foreach($listings as $key=>$listing): ?>
<div class="row-smallbox">

    <div class="col-xs-5">
        <div class="image-box">
            <img src="<?php echo _show_img($listing->image_filename, '125x100', false); ?>" alt="" class="image" />
            <a href="<?php echo listing_url($listing); ?>" class="property-card-hover">
                <img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/plus.png" alt="" class="center-icon" />
            </a>
        </div>
    </div>
    
    <div class="col-xs-7">
    <div class="sw-smallbox">
        <div class="sw-smallbox-title"><a href="<?php echo listing_url($listing); ?>"><?php echo _field($listing, 10); ?></a></div>
        <div class="sw-smallbox-address"><?php echo _field($listing, 'address'); ?></div>
        <div class="sw-smallbox-price"><?php echo _field($listing, 36); ?></div>
    </div>
    </div>

</div>
<?php endforeach; ?>

<?php endif; ?>

</div>


<style>

.sw-latest-listings .row-smallbox
{
    background: #F8F8F8;
    display:block;
    min-height:100px;
    margin-bottom:5px;
    color:black;
}

.widget .sw-latest-listings .row-smallbox a
{
    color:#008EC2;
}

.sw-latest-listings .row-smallbox div
{
    padding: 0px;
}

.sw-latest-listings .row-smallbox div.sw-smallbox
{
    padding: 3px;
}

.sw-latest-listings .image-box
{
    height: 100px;
    position: relative;
    overflow: hidden;
}

.sw-latest-listings a.property-card-hover {
    background: rgba(0,0,0,0.25);
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    opacity: 0;
    transition: all .2s;
    -webkit-transition: opacity .2s;
}

.sw-latest-listings .image-box:hover a.property-card-hover {
    opacity: 1;
}

.sw-latest-listings .property-card-hover .center-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    -webkit-transform: translate(-50%,-50%);
    width: 35px;
    height: 35px;
}

.sw-latest-listings div.sw-smallbox-price
{
    font-weight: bold;
    color: #217DBB;
}


</style>












