
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Edit treefield value','sw_win'); ?> <a href="<?php echo admin_url("admin.php?page=$wp_page&function=addvalue&field_id="._fv('form_object', 'field_id')); ?>" class="page-title-action"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo __('Add New','sw_win')?></a></h1>
<?php else: ?>
<h1><?php echo __('Add treefield value','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Value data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php _form_messages(); ?>
  
    <form action="" class="form-horizontal" method="post">

      <div class="form-group <?php _has_error('parent_id'); ?>">
        <label for="inputParent" class="col-sm-2 control-label"><?php echo __('Parent','sw_win'); ?></label>
        <div class="col-sm-10">
          <?php echo form_dropdown('parent_id', $treefield_dropdown, _fv('form_object', 'parent_id'), 'class="form-control"')?>
        </div>
      </div>

      <div class="form-group <?php _has_error('order'); ?>">
        <label for="inputOrder" class="col-sm-2 control-label"><?php echo __('Custom order','sw_win'); ?></label>
        <div class="col-sm-10">
          <?php echo form_number('order', _fv('form_object', 'order'), 'class="form-control"')?>
        </div>
      </div>

<?php if($field_id == 1): ?>
      <div class="form-group <?php _has_error('marker_icon_id'); ?>">
        <label for="inputParent" class="col-sm-2 control-label"><?php echo __('Map pin icon','sw_win'); ?></label>
        <div class="col-sm-10">
            <div id="meta-box-id" class="postbox" style="border: 0px;">
            <?php
            $post_id = -1;
            
            // Get WordPress' media upload URL
            $upload_link = esc_url( get_upload_iframe_src( 'image', $post_id ) );

            // See if there's a media id already saved as post meta
            $your_img_id = _fv('form_object', 'marker_icon_id');
            
            // Get the image src
            $your_img_src = wp_get_attachment_image_src( $your_img_id, 'full' );
            
            // For convenience, see if the array is valid
            $you_have_img = is_array( $your_img_src );
            ?>
            
            <!-- Your image container, which can be manipulated with js -->
            <div class="custom-img-container">
                <?php if ( $you_have_img ) : ?>
                    <img src="<?php echo $your_img_src[0] ?>" alt="" style="max-width:100%;" />
                <?php endif; ?>
            </div>
            
            <!-- Your add & remove image links -->
            <p class="hide-if-no-js">
                <a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>" 
                   href="<?php echo $upload_link ?>">
                    <?php _e('Set custom image') ?>
                </a>
                <a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>" 
                  href="#">
                    <?php _e('Remove this image') ?>
                </a>
            </p>
            
            <!-- A hidden input to set and post the chosen image id -->
            <input class="marker_icon_id" name="marker_icon_id" type="hidden" value="<?php echo esc_attr( $your_img_id ); ?>" />
            </div>
        </div>
      </div>
<?php endif; ?>

<?php if(true): ?>
      <div class="form-group <?php _has_error('featured_image_id'); ?>">
        <label for="inputParent" class="col-sm-2 control-label"><?php echo __('Featured image','sw_win'); ?></label>
        <div class="col-sm-10">
            <div id="meta-box-id-featured" class="postbox" style="border: 0px;">
            <?php
            $post_id = -2;
            
            // Get WordPress' media upload URL
            $upload_link = esc_url( get_upload_iframe_src( 'image', $post_id ) );

            // See if there's a media id already saved as post meta
            $your_img_id = _fv('form_object', 'featured_image_id');
            
            // Get the image src
            $your_img_src = wp_get_attachment_image_src( $your_img_id, 'full' );
            
            // For convenience, see if the array is valid
            $you_have_img = is_array( $your_img_src );
            ?>
            
            <!-- Your image container, which can be manipulated with js -->
            <div class="custom-img-container">
                <?php if ( $you_have_img ) : ?>
                    <img src="<?php echo $your_img_src[0] ?>" alt="" style="max-width:100%;" />
                <?php endif; ?>
            </div>
            
            <!-- Your add & remove image links -->
            <p class="hide-if-no-js">
                <a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>" 
                   href="<?php echo $upload_link ?>">
                    <?php _e('Set custom image') ?>
                </a>
                <a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>" 
                  href="#">
                    <?php _e('Remove this image') ?>
                </a>
            </p>
            
            <!-- A hidden input to set and post the chosen image id -->
            <input class="featured_image_id" name="featured_image_id" type="hidden" value="<?php echo esc_attr( $your_img_id ); ?>" />
            </div>
        </div>
      </div>
<?php endif; ?>

<?php if(true): ?>
        
        <?php
        $protocol ='https';
        wp_enqueue_style( 'font-awesome', plugins_url(SW_WIN_SLUG.'').'/assets/css/font-awesome.min.css' );
        wp_enqueue_style( 'icon-custom', plugins_url(SW_WIN_SLUG.'').'/assets/css/icomoon/style.css' );
        wp_enqueue_style( 'icon-io', plugins_url(SW_WIN_SLUG.'').'/assets/css/ionicons-2.0.1/css/ionicons.min.css' );
        
        wp_enqueue_script('bootstrap-select', "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js", false, '1.0.0', false);
        wp_enqueue_style( 'bootstrap-select', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css' );
        
        ?>
        
        
      <div class="form-group <?php _has_error('font_icon_code'); ?>">
        <label for="font_icon_code" class="col-sm-2 control-label"><?php echo __('Font icon code','sw_win'); ?></label>
        <div class="col-sm-10">
            <div id="meta-box-id-featured" class="postbox" style="border: 0px;">
                
            
            <!-- A hidden input to set and post the chosen image id -->
            <?php
                $icons_code_list = 'icomoon-pie,icomoon-bread-basket,icomoon-cupcake-f,icomoon-cupcake,fa fa-shopping-cart,fa fa-cutlery,fa fa-glass,fa fa-graduation-cap,fa fa-building,fa fa-coffee,fa fa-shopping-basket,ion-alert, ion-alert-circled, ion-android-add, ion-android-add-circle, ion-android-alarm-clock, ion-android-alert, ion-android-apps, ion-android-archive, ion-android-arrow-back, ion-android-arrow-down, ion-android-arrow-dropdown, ion-android-arrow-dropdown-circle, ion-android-arrow-dropleft, ion-android-arrow-dropleft-circle, ion-android-arrow-dropright, ion-android-arrow-dropright-circle, ion-android-arrow-dropup, '
                    . 'ion-android-arrow-dropup-circle, ion-android-arrow-forward, ion-android-arrow-up, ion-android-attach, ion-android-bar, ion-android-bicycle, ion-android-boat, ion-android-bookmark, ion-android-bulb, ion-android-bus, ion-android-calendar, ion-android-call, ion-android-camera, ion-android-cancel, ion-android-car, ion-android-cart, ion-android-chat, ion-android-checkbox, ion-android-checkbox-blank, ion-android-checkbox-outline, ion-android-checkbox-outline-blank, ion-android-checkmark-circle, ion-android-clipboard, ion-android-close, ion-android-cloud, ion-android-cloud-circle, ion-android-cloud-done, ion-android-cloud-outline, ion-android-color-palette, ion-android-compass, ion-android-contact, ion-android-contacts, ion-android-contract, ion-android-create, ion-android-delete, ion-android-desktop, ion-android-document, ion-android-done, ion-android-done-all, ion-android-download, ion-android-drafts, ion-android-exit, ion-android-expand, ion-android-favorite, ion-android-favorite-outline, ion-android-film, ion-android-folder, ion-android-folder-open, ion-android-funnel, ion-android-globe, ion-android-hand, ion-android-hangout, ion-android-happy, ion-android-home, ion-android-image, '
                    /*. 'ion-android-laptop, ion-android-list, ion-android-locate, ion-android-lock, ion-android-mail, ion-android-map, ion-android-menu, ion-android-microphone, ion-android-microphone-off, ion-android-more-horizontal, ion-android-more-vertical, ion-android-navigate, ion-android-notifications, ion-android-notifications-none, ion-android-notifications-off, ion-android-open, ion-android-options, ion-android-people, ion-android-person, ion-android-person-add, ion-android-phone-landscape, ion-android-phone-portrait, ion-android-pin, ion-android-plane, ion-android-playstore, ion-android-print, ion-android-radio-button-off, ion-android-radio-button-on, ion-android-refresh, ion-android-remove, ion-android-remove-circle, ion-android-restaurant, ion-android-sad, ion-android-search, ion-android-send, ion-android-settings, ion-android-share, ion-android-share-alt, ion-android-star, ion-android-star-half, ion-android-star-outline, ion-android-stopwatch, ion-android-subway, ion-android-sunny, ion-android-sync, ion-android-textsms, ion-android-time, ion-android-train, ion-android-unlock, ion-android-upload, ion-android-volume-down, ion-android-volume-mute, ion-android-volume-off, ion-android-volume-up, '
                    . 'ion-android-walk, ion-android-warning, ion-android-watch, ion-android-wifi, ion-aperture, ion-archive, ion-arrow-down-a, ion-arrow-down-b, ion-arrow-down-c, ion-arrow-expand, ion-arrow-graph-down-left, ion-arrow-graph-down-right, ion-arrow-graph-up-left, ion-arrow-graph-up-right, ion-arrow-left-a, ion-arrow-left-b, ion-arrow-left-c, ion-arrow-move, ion-arrow-resize, ion-arrow-return-left, ion-arrow-return-right, ion-arrow-right-a, ion-arrow-right-b, ion-arrow-right-c, ion-arrow-shrink, ion-arrow-swap, ion-arrow-up-a, ion-arrow-up-b, ion-arrow-up-c, ion-asterisk, ion-at, ion-backspace, ion-backspace-outline, ion-bag, ion-battery-charging, ion-battery-empty, ion-battery-full, ion-battery-half, ion-battery-low, ion-beaker, ion-beer, ion-bluetooth, ion-bonfire, ion-bookmark, ion-bowtie, ion-briefcase, ion-bug, ion-calculator, ion-calendar, ion-camera, ion-card, ion-cash, ion-chatbox, ion-chatbox-working, ion-chatboxes, ion-chatbubble, ion-chatbubble-working, ion-chatbubbles, ion-checkmark, ion-checkmark-circled, ion-checkmark-round, ion-chevron-down, ion-chevron-left, ion-chevron-right, ion-chevron-up, ion-clipboard, ion-clock, ion-close, ion-close-circled, ion-close-round, ion-closed-captioning, '
                    . 'ion-cloud, ion-code, ion-code-download, ion-code-working, ion-coffee, ion-compass, ion-compose, ion-connection-bars, ion-contrast, ion-crop, ion-cube, ion-disc, ion-document, ion-document-text, ion-drag, ion-earth, ion-easel, ion-edit, ion-egg, ion-eject, ion-email, ion-email-unread, ion-erlenmeyer-flask, ion-erlenmeyer-flask-bubbles, ion-eye, ion-eye-disabled, ion-female, ion-filing, ion-film-marker, ion-fireball, ion-flag, ion-flame, ion-flash, ion-flash-off, ion-folder, ion-fork, ion-fork-repo, ion-forward, ion-funnel, ion-gear-a, ion-gear-b, ion-grid, ion-hammer, ion-happy, ion-happy-outline, ion-headphone, ion-heart, ion-heart-broken, ion-help, ion-help-buoy, ion-help-circled, ion-home, ion-icecream, ion-image, ion-images, ion-information, ion-information-circled, ion-ionic, ion-ios-alarm, ion-ios-alarm-outline, ion-ios-albums, ion-ios-albums-outline, ion-ios-americanfootball, ion-ios-americanfootball-outline, ion-ios-analytics, ion-ios-analytics-outline, ion-ios-arrow-back, ion-ios-arrow-down, ion-ios-arrow-forward, ion-ios-arrow-left, ion-ios-arrow-right, ion-ios-arrow-thin-down, ion-ios-arrow-thin-left, ion-ios-arrow-thin-right, ion-ios-arrow-thin-up, ion-ios-arrow-up, ion-ios-at, '
                    . 'ion-ios-at-outline, ion-ios-barcode, ion-ios-barcode-outline, ion-ios-baseball, ion-ios-baseball-outline, ion-ios-basketball, ion-ios-basketball-outline, ion-ios-bell, ion-ios-bell-outline, ion-ios-body, ion-ios-body-outline, ion-ios-bolt, ion-ios-bolt-outline, ion-ios-book, ion-ios-book-outline, ion-ios-bookmarks, ion-ios-bookmarks-outline, ion-ios-box, ion-ios-box-outline, ion-ios-briefcase, ion-ios-briefcase-outline, ion-ios-browsers, ion-ios-browsers-outline, ion-ios-calculator, ion-ios-calculator-outline, ion-ios-calendar, ion-ios-calendar-outline, ion-ios-camera, ion-ios-camera-outline, ion-ios-cart, ion-ios-cart-outline, ion-ios-chatboxes, ion-ios-chatboxes-outline, ion-ios-chatbubble, ion-ios-chatbubble-outline, ion-ios-checkmark, ion-ios-checkmark-empty, ion-ios-checkmark-outline, ion-ios-circle-filled, ion-ios-circle-outline, ion-ios-clock, ion-ios-clock-outline, ion-ios-close, ion-ios-close-empty, ion-ios-close-outline, ion-ios-cloud, ion-ios-cloud-download, ion-ios-cloud-download-outline, ion-ios-cloud-outline, ion-ios-cloud-upload, ion-ios-cloud-upload-outline, ion-ios-cloudy, ion-ios-cloudy-night, ion-ios-cloudy-night-outline, ion-ios-cloudy-outline, ion-ios-cog, ion-ios-cog-outline, '
                    . 'ion-ios-color-filter, ion-ios-color-filter-outline, ion-ios-color-wand, ion-ios-color-wand-outline, ion-ios-compose, ion-ios-compose-outline, ion-ios-contact, ion-ios-contact-outline, ion-ios-copy, ion-ios-copy-outline, ion-ios-crop, ion-ios-crop-strong, ion-ios-download, ion-ios-download-outline, ion-ios-drag, ion-ios-email, ion-ios-email-outline, ion-ios-eye, ion-ios-eye-outline, ion-ios-fastforward, ion-ios-fastforward-outline, ion-ios-filing, ion-ios-filing-outline, ion-ios-film, ion-ios-film-outline, ion-ios-flag, ion-ios-flag-outline, ion-ios-flame, ion-ios-flame-outline, ion-ios-flask, ion-ios-flask-outline, ion-ios-flower, ion-ios-flower-outline, ion-ios-folder, ion-ios-folder-outline, ion-ios-football, ion-ios-football-outline, ion-ios-game-controller-a, ion-ios-game-controller-a-outline, ion-ios-game-controller-b, ion-ios-game-controller-b-outline, ion-ios-gear, ion-ios-gear-outline, ion-ios-glasses, ion-ios-glasses-outline, ion-ios-grid-view, ion-ios-grid-view-outline, ion-ios-heart, ion-ios-heart-outline, ion-ios-help, ion-ios-help-empty, ion-ios-help-outline, ion-ios-home, ion-ios-home-outline, ion-ios-infinite, ion-ios-infinite-outline, ion-ios-information, ion-ios-information-empty, '
                    . 'ion-ios-information-outline, ion-ios-ionic-outline, ion-ios-keypad, ion-ios-keypad-outline, ion-ios-lightbulb, ion-ios-lightbulb-outline, ion-ios-list, ion-ios-list-outline, ion-ios-location, ion-ios-location-outline, ion-ios-locked, ion-ios-locked-outline, ion-ios-loop, ion-ios-loop-strong, ion-ios-medical, ion-ios-medical-outline, ion-ios-medkit, ion-ios-medkit-outline, ion-ios-mic, ion-ios-mic-off, ion-ios-mic-outline, ion-ios-minus, ion-ios-minus-empty, ion-ios-minus-outline, ion-ios-monitor, ion-ios-monitor-outline, ion-ios-moon, ion-ios-moon-outline, ion-ios-more, ion-ios-more-outline, ion-ios-musical-note, ion-ios-musical-notes, ion-ios-navigate, ion-ios-navigate-outline, ion-ios-nutrition, ion-ios-nutrition-outline, ion-ios-paper, ion-ios-paper-outline, ion-ios-paperplane, ion-ios-paperplane-outline, ion-ios-partlysunny, ion-ios-partlysunny-outline, ion-ios-pause, ion-ios-pause-outline, ion-ios-paw, ion-ios-paw-outline, ion-ios-people, ion-ios-people-outline, ion-ios-person, ion-ios-person-outline, ion-ios-personadd, ion-ios-personadd-outline, ion-ios-photos, ion-ios-photos-outline, ion-ios-pie, ion-ios-pie-outline, ion-ios-pint, ion-ios-pint-outline, ion-ios-play, ion-ios-play-outline, '
                    . 'ion-ios-plus, ion-ios-plus-empty, ion-ios-plus-outline, ion-ios-pricetag, ion-ios-pricetag-outline, ion-ios-pricetags, ion-ios-pricetags-outline, ion-ios-printer, ion-ios-printer-outline, ion-ios-pulse, ion-ios-pulse-strong, ion-ios-rainy, ion-ios-rainy-outline, ion-ios-recording, ion-ios-recording-outline, ion-ios-redo, ion-ios-redo-outline, ion-ios-refresh, ion-ios-refresh-empty, ion-ios-refresh-outline, ion-ios-reload, ion-ios-reverse-camera, ion-ios-reverse-camera-outline, ion-ios-rewind, ion-ios-rewind-outline, ion-ios-rose, ion-ios-rose-outline, ion-ios-search, ion-ios-search-strong, ion-ios-settings, ion-ios-settings-strong, ion-ios-shuffle, ion-ios-shuffle-strong, ion-ios-skipbackward, ion-ios-skipbackward-outline, ion-ios-skipforward, ion-ios-skipforward-outline, ion-ios-snowy, ion-ios-speedometer, ion-ios-speedometer-outline, ion-ios-star, ion-ios-star-half, ion-ios-star-outline, ion-ios-stopwatch, ion-ios-stopwatch-outline, ion-ios-sunny, ion-ios-sunny-outline, ion-ios-telephone, ion-ios-telephone-outline, ion-ios-tennisball, ion-ios-tennisball-outline, ion-ios-thunderstorm, ion-ios-thunderstorm-outline, ion-ios-time, ion-ios-time-outline, ion-ios-timer, ion-ios-timer-outline, ion-ios-toggle, '
                    . 'ion-ios-toggle-outline, ion-ios-trash, ion-ios-trash-outline, ion-ios-undo, ion-ios-undo-outline, ion-ios-unlocked, ion-ios-unlocked-outline, ion-ios-upload, ion-ios-upload-outline, ion-ios-videocam, ion-ios-videocam-outline, ion-ios-volume-high, ion-ios-volume-low, ion-ios-wineglass, ion-ios-wineglass-outline, ion-ios-world, ion-ios-world-outline, ion-ipad, ion-iphone, ion-ipod, ion-jet, ion-key, ion-knife, ion-laptop, ion-leaf, ion-levels, ion-lightbulb, ion-link, ion-load-a, ion-load-b, ion-load-c, ion-load-d, ion-location, ion-lock-combination, ion-locked, ion-log-in, ion-log-out, ion-loop, ion-magnet, ion-male, ion-man, ion-map, ion-medkit, ion-merge, ion-mic-a, ion-mic-b, ion-mic-c, ion-minus, ion-minus-circled, ion-minus-round, ion-model-s, ion-monitor, ion-more, ion-mouse, ion-music-note, ion-navicon, ion-navicon-round, ion-navigate, ion-network, ion-no-smoking, ion-nuclear, ion-outlet, ion-paintbrush, ion-paintbucket, ion-paper-airplane, ion-paperclip, ion-pause, ion-person, ion-person-add, ion-person-stalker, ion-pie-graph, ion-pin, ion-pinpoint, ion-pizza, ion-plane, ion-planet, ion-play, ion-playstation, ion-plus, ion-plus-circled, ion-plus-round, ion-podium, ion-pound, ion-power, ion-pricetag, '
                    . 'ion-pricetags, ion-printer, ion-pull-request, ion-qr-scanner, ion-quote, ion-radio-waves, ion-record, ion-refresh, ion-reply, ion-reply-all, ion-ribbon-a, ion-ribbon-b, ion-sad, ion-sad-outline, ion-scissors, ion-search, ion-settings, ion-share, ion-shuffle, ion-skip-backward, ion-skip-forward, ion-social-android, ion-social-android-outline, ion-social-angular, ion-social-angular-outline, ion-social-apple, ion-social-apple-outline, ion-social-bitcoin, ion-social-bitcoin-outline, ion-social-buffer, ion-social-buffer-outline, ion-social-chrome, ion-social-chrome-outline, ion-social-codepen, ion-social-codepen-outline, ion-social-css3, ion-social-css3-outline, ion-social-designernews, ion-social-designernews-outline, ion-social-dribbble, ion-social-dribbble-outline, ion-social-dropbox, ion-social-dropbox-outline, ion-social-euro, ion-social-euro-outline, ion-social-facebook, ion-social-facebook-outline, ion-social-foursquare, ion-social-foursquare-outline, ion-social-freebsd-devil, ion-social-github, ion-social-github-outline, ion-social-google, ion-social-google-outline, ion-social-googleplus, ion-social-googleplus-outline, ion-social-hackernews, ion-social-hackernews-outline, ion-social-html5, ion-social-html5-outline,'
                    . 'ion-social-instagram, ion-social-instagram-outline, ion-social-javascript, ion-social-javascript-outline, ion-social-linkedin, ion-social-linkedin-outline, ion-social-markdown, ion-social-nodejs, ion-social-octocat, ion-social-pinterest, ion-social-pinterest-outline, ion-social-python, ion-social-reddit, ion-social-reddit-outline, ion-social-rss, ion-social-rss-outline, ion-social-sass, ion-social-skype, ion-social-skype-outline, ion-social-snapchat, ion-social-snapchat-outline, ion-social-tumblr, ion-social-tumblr-outline, ion-social-tux, ion-social-twitch, ion-social-twitch-outline, ion-social-twitter, ion-social-twitter-outline, ion-social-usd, ion-social-usd-outline, ion-social-vimeo, ion-social-vimeo-outline, ion-social-whatsapp, ion-social-whatsapp-outline, ion-social-windows, ion-social-windows-outline, ion-social-wordpress, ion-social-wordpress-outline, ion-social-yahoo, '
                    */. 'ion-social-yahoo-outline, ion-social-yen, ion-social-yen-outline, ion-social-youtube, ion-social-youtube-outline, ion-soup-can, ion-soup-can-outline, ion-speakerphone, ion-speedometer, ion-spoon, ion-star, ion-stats-bars, ion-steam, ion-stop, ion-thermometer, ion-thumbsdown, ion-thumbsup, ion-toggle, ion-toggle-filled, ion-transgender,ion-trash-a, ion-trash-b, ion-trophy, ion-tshirt, ion-tshirt-outline, ion-umbrella, ion-university, ion-unlocked, ion-upload, ion-usb, ion-videocamera, ion-volume-high, ion-volume-low, ion-volume-medium, ion-volume-mute, ion-wand, ion-waterdrop, ion-wifi, ion-wineglass, ion-woman, ion-wrench, ion-xbox';
                    /* end tree_font_icons */
                
                global $add_font_list_icons;
                if(isset($add_font_list_icons) && !empty($add_font_list_icons))
                    $icons_code_list .=','.$add_font_list_icons;
                $icons_code_list = explode(',', $icons_code_list);
                $icons_code_list = array_map('trim', $icons_code_list);
                
              ?>
              <select class="form-control selectpicker" name="font_icon_code" id="font_icon_code" data-size="10" data-live-search="true">
                  <option value=""><?php echo __('Select icon','sw_win'); ?></option>
              <?php foreach ($icons_code_list as $key => $value):?>
                  <?php
                  $val = _fv('form_object', 'font_icon_code');
                  ?>
                  <option value="<?php echo $value;?>" data-icon="<?php echo $value;?>" <?php echo ($val==$value) ? 'selected="selected"': '';?>><?php echo $value;?></option>
              <?php endforeach;?>
              </select>
            
            </div>
        </div>
      </div>
<?php endif; ?>
<?php if(sw_count(sw_get_languages()) > 1): ?>
      <hr />
      
      <h4><?php echo __('Languages','sw_win'); ?></h4>
    <?php endif;?>
    <div>
    
      <!-- Nav tabs -->
      <ul class="nav nav-tabs <?php if(sw_count(sw_get_languages()) <2): ?> no-line <?php endif;?>" role="tablist">
      
      <?php $i=0;if(sw_count(sw_get_languages()) > 1)foreach(sw_get_languages() as $key=>$row):$i++; ?>
        <li role="presentation" class="<?php echo $i==1?'active':''?>"><a href="#lang_<?php echo $key?>" aria-controls="<?php echo $row['lang_code']; ?>" role="tab" data-toggle="tab"><?php echo $row['title']; ?></a></li>
      
      <?php endforeach; ?>
      </ul>
        
      <!-- Tab panes -->
      <div class="tab-content">
      
      <?php $i=0;foreach(sw_get_languages() as $key=>$row):$i++; ?>
      
      
        <div role="tabpanel" class="tab-pane <?php echo $i==1?'active':''?>" id="lang_<?php echo $key?>">
        
          <div class="form-group <?php _has_error('value_'.$key); ?>">
            <label for="inputValue_<?php echo $key?>" class="col-sm-2 control-label"><?php echo __('Value','sw_win'); ?></label>
            <div class="col-sm-10">
              <input name="value_<?php echo $key?>" type="text" value="<?php echo _fv('form_object', 'value_'.$key); ?>" class="form-control" id="inputValue_<?php echo $key?>" placeholder="<?php echo __('Value','sw_win'); ?>">
            </div>
          </div>

          <div class="form-group <?php _has_error('description_'.$key); ?>">
            <label for="inputDescription_<?php echo $key?>" class="col-sm-2 control-label"><?php echo __('Description','sw_win'); ?></label>
            <div class="col-sm-10">
              <textarea name="description_<?php echo $key?>" type="text" class="form-control" id="inputDescription_<?php echo $key?>" placeholder="<?php echo __('Description','sw_win'); ?>"><?php echo _fv('form_object', 'description_'.$key); ?></textarea>
            </div>
          </div>

        </div>
        <?php endforeach; ?>
      </div>
    
    </div>
      <hr />
      
<?php if(function_exists('show_dependent') && show_dependent($field_id)): ?>

<h4><?php echo __('Visible fields','sw_win'); ?></h4>
<hr />
<div>

<?php foreach($fields_under_selected as $key=>$field): ?>

<?php if($field->type == 'CATEGORY'): ?>
<hr />
<?php endif; ?>

<div class="form-group">
  <label class="col-lg-2 control-label"><?php echo $field->field_name; ?></label>
  <div class="col-lg-10 checkbox-padding">
    <?php 
    
    $val = $this->input->post('field_'.$field->idfield);
    
    if(empty($val))
    {
        if(isset($item->{'field_'.$field->idfield}))
            $val = $item->{'field_'.$field->idfield};
    }
    
    $val = !$val;

    if($field->is_required == 1)
    {
        // Not allowed change - submit value..
        echo form_checkbox('field_'.$field->idfield, '1', '1', 'class="hidden type_'.$field->type.'"');
        // .. and show user the value being submitted
        echo '<input type="checkbox" disabled readonly checked>';
        echo '<span class="label label-danger">'.__('Required','sw_win').'</span>';
    }
    else
    {
        echo form_checkbox('field_'.$field->idfield, '1', $val, 'class="type_'.$field->type.'"');
    }
    
    ?>
    
   
  </div>
</div>

<?php if($field->type == 'CATEGORY'): ?>
<hr />
<?php endif; ?>

<?php endforeach; ?>

</div>
<hr />
<?php endif; ?>
      
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary"><?php echo __('Save', 'sw_win'); ?></button>
        </div>
      </div>
    </form>
  </div>
</div>

</div>
    
    
<script>

jQuery(document).ready(function($) {

  $('.selectpicker').selectpicker();

  // Set all variables to be used in scope
  var frame,
      metaBox = $('#meta-box-id.postbox'), // Your meta box id here
      addImgLink = metaBox.find('.upload-custom-img'),
      delImgLink = metaBox.find( '.delete-custom-img'),
      imgContainer = metaBox.find( '.custom-img-container'),
      imgIdInput = metaBox.find( '.marker_icon_id' );
  
  // ADD IMAGE LINK
  addImgLink.on( 'click', function( event ){
    
    event.preventDefault();
    
    // If the media frame already exists, reopen it.
    if ( frame ) {
      frame.open();
      return;
    }
    
    // Create a new media frame
    frame = wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: {
        text: 'Use this media'
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    
    // When an image is selected in the media frame...
    frame.on( 'select', function() {
      
      // Get media attachment details from the frame state
      var attachment = frame.state().get('selection').first().toJSON();

      // Send the attachment URL to our custom image input field.
      imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

      // Send the attachment id to our hidden input
      imgIdInput.val( attachment.id );

      // Hide the add image link
      addImgLink.addClass( 'hidden' );

      // Unhide the remove image link
      delImgLink.removeClass( 'hidden' );
    });

    // Finally, open the modal on click
    frame.open();
  });
  
  
  // DELETE IMAGE LINK
  delImgLink.on( 'click', function( event ){

    event.preventDefault();

    // Clear out the preview image
    imgContainer.html( '' );

    // Un-hide the add image link
    addImgLink.removeClass( 'hidden' );

    // Hide the delete image link
    delImgLink.addClass( 'hidden' );

    // Delete the image id from the hidden input
    imgIdInput.val( '' );

  });

  // Set all variables to be used in scope
  var featured_frame,
      featured_metaBox = $('#meta-box-id-featured.postbox'), // Your meta box id here
      featured_addImgLink = featured_metaBox.find('.upload-custom-img'),
      featured_delImgLink = featured_metaBox.find( '.delete-custom-img'),
      featured_imgContainer = featured_metaBox.find( '.custom-img-container'),
      featured_imgIdInput = featured_metaBox.find( '.featured_image_id' );
  
  // ADD IMAGE LINK
  featured_addImgLink.on( 'click', function( event ){
    
    event.preventDefault();
    
    // If the media frame already exists, reopen it.
    if ( featured_frame ) {
      featured_frame.open();
      return;
    }
    
    // Create a new media frame
    featured_frame = wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: {
        text: 'Use this media'
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    
    // When an image is selected in the media frame...
    featured_frame.on( 'select', function() {
      
      // Get media attachment details from the frame state
      var attachment = featured_frame.state().get('selection').first().toJSON();

      // Send the attachment URL to our custom image input field.
      featured_imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

      // Send the attachment id to our hidden input
      featured_imgIdInput.val( attachment.id );

      // Hide the add image link
      featured_addImgLink.addClass( 'hidden' );

      // Unhide the remove image link
      featured_delImgLink.removeClass( 'hidden' );
    });

    // Finally, open the modal on click
    featured_frame.open();
  });
  
  
  // DELETE IMAGE LINK
  featured_delImgLink.on( 'click', function( event ){

    event.preventDefault();

    // Clear out the preview image
    featured_imgContainer.html( '' );

    // Un-hide the add image link
    featured_addImgLink.removeClass( 'hidden' );

    // Hide the delete image link
    featured_delImgLink.addClass( 'hidden' );

    // Delete the image id from the hidden input
    featured_imgIdInput.val( '' );

  });


});

</script>

<style>
    .wp-admin .bootstrap-wrapper .col-lg-10.checkbox-padding
    {
        padding-top:7px;
    }
    
    .custom-img-container
    {
        max-width:100px;
        max-height:100px;
        background-color: #f3efef;
        border-color: #ddd;
        padding: 5px;
        display: inline-block;
        min-width:40px;
        min-height:40px;
        text-align: center;
    }
    
    .custom-img-container img
    {
        max-width:40px;
        max-height:40px;
    }
    
    #meta-box-id
    {
        padding-top:7px;
    }
    
    .glyphicon.fa {
        display: inline-block;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

</style>