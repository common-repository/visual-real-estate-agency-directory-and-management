<div>

<?php foreach($agents as $key=>$user_details): 
      $user = $user_details->data;
?>

<div class="column-sep col-md-4 col-sm-6">
<div class="agent-item">

    <div class="col-xs-4">
        <div class="image-box">
            <img src="<?php echo sw_profile_image($user, 100); ?>" alt="" class="image" />
            <a href="<?php echo agent_url($user); ?>" class="property-card-hover">
                <img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/plus.png" alt="" class="center-icon" />
            </a>
        </div>
    </div>
    
    <div class="col-xs-8">
    <div class="sw-smallbox">
        <div class="sw-smallbox-title"><a href="<?php echo agent_url($user); ?>"><?php echo $user->display_name; ?></a></div>
        <div class="sw-smallbox-address"><?php echo $user->user_email; ?></div>
        <div class="sw-smallbox-price"><?php echo profile_data($user, 'phone_number'); ?></div>
    </div>
    </div>

</div>
</div>

<?php endforeach; ?>
</div>