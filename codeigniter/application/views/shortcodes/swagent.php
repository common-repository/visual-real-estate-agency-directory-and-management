<div class="row">

    <div class="col-xs-4">
        <div class="image-box">
            <img src="<?php echo sw_profile_image($user, 200); ?>" alt="" class="image" />
        </div>
    </div>

    <div class="col-xs-8">
        <?php if(!empty($user_meta['description'][0])): ?>
        <p><?php echo _ch($user_meta['description'][0], '-'); ?></p>
        <?php endif; ?>
        <p><?php echo __('Name', 'sw_win').': '._ch($user->display_name, '-'); ?></p>
        <p><?php echo __('Email', 'sw_win').': <a href="'._ch($user->user_email, '#').'">'._ch($user->user_email, '-').'</a>'; ?></p>
        
        <?php if(!empty($user->user_url)): ?>
        <p><?php echo __('Website', 'sw_win').': <a href="'._ch($user->user_url, '#').'">'._ch($user->user_url, '-').'</a>'; ?></p>
        <?php endif; ?>
    </div>

</div>