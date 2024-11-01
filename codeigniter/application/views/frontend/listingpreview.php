<div class="bootstrap-wrapper">

<?php if(isset($edit_url)): ?>
<a href="<?php echo $edit_url; ?>" class="btn btn-primary pull-right" style="margin-left: 10px;"><i class="glyphicon glyphicon-pencil"></i></a>
<?php endif; ?>

<h2><?php echo _field($listing, 10); ?><?php echo get_treefield_value($listing->category_id, ",", ""); ?><span class="pull-right review_stars_<?php echo $avarage_stars; ?>"> </span></h2>

<?php

    $image_files_exists = false;
    $document_files_exists = false;
    $images_types = array('image/jpeg','image/png','image/gif','image/bmp','image/vnd.microsoft.icon','image/tiff','image/svg+xml',
                          'jpg', 'jpeg', 'png','gif','bmp','tiff');
    
    foreach($images as $image)
    {
         if(in_array($image->filetype, $images_types) !== FALSE )$image_files_exists = true;
         if(in_array($image->filetype, $images_types) === FALSE )$document_files_exists = true;
    }

?>

<?php if($image_files_exists): ?>

<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

<?php if(sw_count($images) > 1 && $image_files_exists): ?>
  <!-- Indicators -->
  <ol class="carousel-indicators">
<?php $i=0;foreach($images as $image): ?>
<?php if(in_array($image->filetype, $images_types) !== FALSE): ?>
    <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i++; ?>" class="<?php if($i == 1)echo 'active'; ?>"></li>
<?php endif; ?>
<?php endforeach; ?>
  </ol>
<?php endif; ?>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
<?php $i=0;foreach($images as $image): ?>
<?php if(in_array($image->filetype, $images_types) !== FALSE ): ?>
    <div class="item <?php if($i++ == 0)echo 'active'; ?>">
      <img src="<?php echo _show_img($image->filename, '900x500', false, null); ?>" alt="<?php echo $image->alt; ?>">
      <div class="carousel-caption">
        <?php echo $image->title; ?>
      </div>
    </div>
    <?php endif; ?>
<?php endforeach; ?>
  </div>

<?php if(sw_count($images) > 1  && $image_files_exists): ?>
  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
<?php endif; ?>
</div>

<?php endif; ?>

<?php 

    $favorite_added=false;
    if(get_current_user_id() != 0)
    {
        $CI =& get_instance();
        $CI->load->model('favorite_m');
        $favorite_added = $CI->favorite_m->check_if_exists(get_current_user_id(), 
                                                           $listing->idlisting);
        if($favorite_added>0)$favorite_added = true;
    }

?>
            
<div class="favorite" style="">
    <?php if(function_exists('sw_show_favorites')): ?>
    <button class="btn btn-primary btn-inversed btn-block" id="add_to_favorites" href="#" style="<?php echo ($favorite_added)?'display:none;':''; ?>">&nbsp;<i class="fa fa-star-o" aria-hidden="true"></i>&nbsp;<?php echo __('Add to favorites', 'sw_win'); ?>&nbsp;<i class="load-indicator fa fa-spinner fa-spin fa-fw"></i>&nbsp;</button>
    <button class="btn btn-primary btn-inversed btn-block" id="remove_from_favorites" href="#" style="<?php echo (!$favorite_added)?'display:none;':''; ?>">&nbsp;<i class="fa fa-star" aria-hidden="true"></i>&nbsp;<?php echo __('Remove from favorites', 'sw_win'); ?>&nbsp;<i class="load-indicator fa fa-spinner fa-spin fa-fw"></i>&nbsp;</button>
    <?php endif; ?>
</div>

<?php if(function_exists('sw_win_report_added')):
    $report_added = sw_win_report_added($listing->idlisting);

    if(!$report_added):
    include(SW_WIN_REPORT_PLUGIN_PATH.'report_form.php');
?>
            
<div class="report" style="">
    <button class="btn btn-warning btn-inversed btn-block popup-with-form-report" style="margin-left: 5px; <?php echo ($report_added)?'display:none;':'display:inline-block;'; ?>" id="report_listing" href="#popup_report_listing"><i class="fa fa-flag-o" aria-hidden="true"></i> <?php echo __('Report listing', 'sw_win'); ?> <i class="load-indicator"></i></button>
</div>

<?php endif;endif; ?>

<br style="clear:both;" />

<h4><?php echo _field_name(13); ?></h4>

<?php echo _field($listing, 13); ?>

<p class='clearfix'>
      <?php
      if(!empty($listing->location_id)){
          $location = array();
          $CI =& get_instance();
          $lang_id = sw_current_language_id();
          $CI->load->model('treefield_m');
          $tree = $CI->treefield_m->get_lang($listing->location_id);
          $location[] = $tree->{'value_'.$lang_id};

          while(!empty($tree->parent_id)) {
              $tree = $CI->treefield_m->get_lang($tree->parent_id);
              $location[] = $tree->{'value_'.$lang_id};
          }
          $location = array_reverse($location);
          $location = implode(', ', $location);
          echo '<strong>'.esc_html__('Location','sw_win').':</strong> '.esc_html($location);
      }
      ?>
  </p>

<?php foreach($fields as $key=>$field): ?>

<?php if(isset($field['parent']) && $field['parent']['type'] == 'CATEGORY' && $field['parent']['is_preview_visible']): ?>
<?php $in_cat_counter = 0; ?>

<br style="clear:both;" />
<h4 class="<?php echo 'field_'.$field['parent']['idfield']; ?> <?php echo $field['parent']['type']; ?>"><?php echo $field['parent']['field_name']; ?></h4>

<ul class="<?php echo 'field_'.$field['parent']['idfield']; ?> <?php echo $field['parent']['type']; ?>">
<?php foreach($field['children'] as $key_children=>$field_children): ?>

<?php $field_val = _field($listing, $field_children['idfield']); ?>

<?php if($field_val != '-' && !empty($field_val)): ?>
<?php $in_cat_counter++; ?>
<?php if($field_children['type'] == 'DROPDOWN' || $field_children['type'] == 'DROPDOWN_MULTIPLE'): ?>
<li><?php echo $field_children['field_name']; ?>: <span class="label label-primary"><?php echo $field_val; ?></span></li>

<?php elseif($field_children['type'] == 'INPUTBOX' || $field_children['type'] == 'INTEGER'): ?>

<?php
// version for youtube link
if(strpos($field_val, 'watch?v=') !== FALSE)
{
    $embed_code = substr($field_val, strpos($field_val, 'watch?v=')+8);
    echo '<li class="embed"><iframe width="560" height="315" src="https://www.youtube.com/embed/'.$embed_code.'" frameborder="0" allowfullscreen></iframe></li>';
}
// version for youtube link
elseif(strpos($field_val, 'youtu.be/') !== FALSE)
{
    $embed_code = substr($field_val, strpos($field_val, 'youtu.be/')+9);
    echo '<li class="embed"><iframe width="560" height="315" src="https://www.youtube.com/embed/'.$embed_code.'" frameborder="0" allowfullscreen></iframe></li>';
}
elseif(strpos($field_val, 'imeo.com/') !== FALSE)
{
    $embed_code = substr($field_val, strpos($field_val, 'imeo.com/')+9);

    echo '<li class="embed"><iframe src="https://player.vimeo.com/video/'.$embed_code.'" width="640" height="337" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></li>';
}
// basic text
else
{
    echo '<li>'.$field_children['field_name'].': '.$field_val.'</li>';
}
?>

<?php elseif($field_children['type'] == 'CHECKBOX'): ?>

<?php $field_val?$field_val='check':$field_val='remove'; ?>

<li><?php echo $field_children['field_name']; ?>: <i class="fa fa-<?php echo $field_val; ?>"></i></li>

<?php else: ?>

<?php dump($field_children); ?>

<?php endif; ?>
<?php endif; ?>

<?php //dump($field_children); ?>

<?php endforeach; ?>
</ul>

<?php 
    // Hide category if there is no items to show
    if($in_cat_counter == 0)
    {
        echo '<style>'.'.field_'.$field['parent']['idfield'].'{display:none;}</style>';
    }
?>

<?php endif; ?>
<?php endforeach; ?>

<?php //dump($listing); ?>

<?php
    // [Rates]

    if(function_exists('sw_win_load_ci_function_calendar'))
    {
        $CI = & get_instance();
        $CI->load->model('calendar_m');
        $CI->load->model('rates_m');
        $calendar = $CI->calendar_m->get_by(array('sw_calendar.listing_id'=>$listing->idlisting), true);
        
         if(sw_count($calendar))
         {
            // fetch rates
            $rates = $CI->rates_m->get_by(array('sw_rates.listing_id'=>$listing->idlisting, 'date_to >'=>date('Y-m-d H:i:s')));

            if(sw_count($rates) > 0)
            {

                echo '<h4>'.__('Reservation rates', 'sw_win').'</h4>';
                
                echo '<table class="table table-striped table-hover">';
                
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>#</th><th>'.__('Date from', 'sw_win').'</th>';
                        echo '<th>'.__('Date to', 'sw_win').'</th>';
                        echo '<th>'.__('Day', 'sw_win').'</th>';
                        echo '<th>'.__('Hour', 'sw_win').'</th>';
                        echo '<th>'.__('Week', 'sw_win').'</th>';
                        echo '<th>'.__('Month', 'sw_win').'</th>';
                    echo '</tr>';
                echo '</thead>';
                
                echo '<tbody>';
                foreach($rates as $rate)
                {
                    echo '<tr>';
                        echo '<td>';
                        echo $rate->idrates;
                        echo '</td>';
                        echo '<td>';
                        echo $rate->date_from;
                        echo '</td>';
                        echo '<td>';
                        echo $rate->date_to;
                        echo '</td>';
                        echo '<td>';
                        echo _ch($rate->rate_night);
                        echo '</td>';
                        echo '<td>';
                        echo _ch($rate->rate_hour);
                        echo '</td>';
                        echo '<td>';
                        echo _ch($rate->rate_week);
                        echo '</td>';
                        echo '<td>';
                        echo _ch($rate->rate_month);
                        echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';

                echo '*'.__('Prices in currency: ', 'sw_win').sw_settings('default_currency');
                echo '<br />';
                echo '<br />';
            }
         }
    }

?>

<?php
    // [Related listings]
    
    if(sw_count($related) > 0)
    {
        echo '<h4>'.__('Related listings', 'sw_win').'</h4>';
        
        echo '<table class="table table-striped table-hover">';
        
        echo '<thead>';
            echo '<tr>';
                echo '<th>#</th><th>'.__('Address', 'sw_win').'</th>';
                foreach($this->field_m->get_tablefields() as $field)
                {
                    echo '<th>';
                    echo _field_name($field->idfield);
                    echo '</th>';
                }
            echo '</tr>';
        echo '</thead>';
        
        echo '<tbody>';
        foreach($related as $related_listing)
        {
            echo '<tr>';
                echo '<td>';
                echo _field($related_listing, 'idlisting');
                echo '</td>';
                echo '<td><a href="'.listing_url($related_listing).'">';
                echo _field($related_listing, 'address');
                echo '</a></td>';
                
                foreach($this->field_m->get_tablefields() as $field)
                {
                    echo '<td>';
                    echo _field($related_listing, $field->idfield);
                    echo '</td>';
                }

            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }

    // [/Related listings]
?>

<?php

if(!sw_settings('hide_map_listingpage')){
    $w_p_title = _field($listing, 10);
    $gmap_lat = $listing->lat;
    $gmap_long = $listing->lng;
    $property_address = $listing->address;
    $metric = 'km';
    $zoom = '18';


    $pin_icon = plugins_url( SW_WIN_SLUG.'/assets').'/img/markers/empty.png';

    // check for version with field_id = 14
    if(file_exists(SW_WIN_PLUGIN_PATH.'assets/img/markers/'._field($listing, 14).'.png'))
    {
        $pin_icon = plugins_url( SW_WIN_SLUG.'/assets').'/img/markers/'._field($listing, 14).'.png';
    }

    // check for version with category related marker
    if(isset($category->marker_icon_id))
    {
        $img = wp_get_attachment_image_src($category->marker_icon_id, 'thumbnail', true, '' );
        if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
        {
            $pin_icon = $img[0];
        }
    }

    if(!empty($gmap_lat))
    {
        echo '<h4>'.__('Location', 'sw_win').'</h4>';

        if(sw_settings('use_walker'))
        {
            echo do_shortcode('[walker metric="'.$metric.'" zoom="18" latitude="'.$gmap_lat.'" longitude="'.$gmap_long.'" default_index="0"]'.$w_p_title.'<br />'.$property_address.'[/walker]');
        }
        else
        {
            echo do_shortcode('[swmap metric="'.$metric.'" marker_url="'.$pin_icon.'" zoom="18" latitude="'.$gmap_lat.'" longitude="'.$gmap_long.'"]'.str_replace("'", "\'", _infowindow_content($listing, array('show_details'=>false))).'[/swmap]');
        }
    }
}
?>

<?php if($document_files_exists): ?>
</br>
<div class="widget widget-box box-container">
    <div class="widget-header text-uppercase">
        <h2><?php echo esc_html__('Documents files', 'sw_win'); ?></h2>
    </div>
    <ul>  
<?php if(sw_count($images) >= 1): ?>
<?php $i=0;foreach($images as $image): ?>
<?php if(in_array($image->filetype, $images_types) === FALSE): ?>
        <li>
            <a href="<?php echo esc_url(sw_win_upload_dir().'/files/'.$image->filename); ?>"><?php echo esc_html__($image->filename); ?></a>
        </li>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

    </ul>
</div><!-- /. widget-gallery -->  
<?php endif; ?>

<?php if(function_exists('sw_show_reviews')): ?>
<?php $this->load->view('frontend/reviews'); ?>
<?php endif; ?>

                
<?php if(!sw_settings('hide_fbcomments_listingpage')): ?>
<div class="widget widget-box box-container widget-facebook-comments">
    <div class="widget-header text-uppercase">
        <h2><?php echo esc_html__( 'Facebook comments', 'sw_win' ); ?></h2> 
    </div>
    <div class="fb-comments" data-href="<?php echo esc_url(get_current_url()); ?>" data-width="100%" data-numposts="5" data-colorscheme="light"></div>
</div><!-- /. widget-facebook -->    

<script>
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.5";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<?php endif; ?>

</div>

<script>

jQuery(document).ready(function($) {

    var estate_data_id = <?php echo $listing->idlisting; ?>
    
    // [START] Add to favorites //  
    
    $("#add_to_favorites").click(function(){
        
        var data = { listing_id: estate_data_id };
        
        $.extend( data, {
            "page": 'frontendajax_addfavorite',
            "action": 'ci_action'
        });
        
        var load_indicator = $(this).find('.load-indicator');
        load_indicator.css('display', 'inline-block');
        $.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", data, 
               function(data){
            
            ShowStatus.show(data.message);
            //console.log(data.message);
                            
            load_indicator.css('display', 'none');
            
            if(data.success)
            {
                $("#add_to_favorites").css('display', 'none');
                $("#remove_from_favorites").css('display', 'inline-block');
            }
        });

        return false;
    });
    
    $("#remove_from_favorites").click(function(){
        
        var data = { listing_id: estate_data_id };
        
        $.extend( data, {
            "page": 'frontendajax_remfavorite',
            "action": 'ci_action'
        });
        
        var load_indicator = $(this).find('.load-indicator');
        load_indicator.css('display', 'inline-block');
        $.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", data, 
               function(data){
            
            ShowStatus.show(data.message);
            console.log(data.message);
                            
            load_indicator.css('display', 'none');
            
            if(data.success)
            {
                $("#remove_from_favorites").css('display', 'none');
                $("#add_to_favorites").css('display', 'inline-block');
            }
        });

        return false;
    });
    
    // [END] Add to favorites //  

});



</script>

<style>

h1.entry-title
{
    display:none;
}

ul.CATEGORY
{
    margin: 0px;
    padding: 0px;
}

h4.CATEGORY
{
    margin: 0px;
    padding: 15px 0px;
}

ul.CATEGORY li
{
    list-style: none;
    margin: 0px;
    padding: 5px 5px 5px 0px;
    width: 168px;
    float: left;
}

ul.CATEGORY li.embed
{
    width:100%;
}

.favorite
{
    padding: 10px 10px 10px 0px;
    float:left;
}

.report
{
    padding: 10px 10px 10px 0px;
    float:left;
}



</style>