<div style="width:125px; height:150px;">

<img src="<?php echo _show_img($listing->image_filename, '125x60', false); ?>" alt="" class="" />

<p><?php echo _field($listing, 'address'); ?></p>

<p><?php echo get_treefield_value($listing->category_id, "", "-"); ?></p>

<p class="label label-default label-tag-primary"><?php echo _field($listing, 4); ?></p>

<?php if($show_details): ?>
<p><a href="<?php echo listing_url($listing); ?>"><?php echo __('Details', 'sw_win'); ?></a></p>
<?php endif; ?>

</div>
