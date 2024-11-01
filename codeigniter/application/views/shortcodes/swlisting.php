<div class="bootstrap-wrapper">
<h2><?php echo _field($listing, 10); ?><span class="pull-right review_stars_<?php echo $avarage_stars; ?>"> </span></h2>


<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

<?php if(sw_count($images) > 1): ?>
  <!-- Indicators -->
  <ol class="carousel-indicators">
<?php $i=0;foreach($images as $image): ?>
    <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i++; ?>" class="<?php if($i == 1)echo 'active'; ?>"></li>
<?php endforeach; ?>
  </ol>
<?php endif; ?>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
<?php $i=0;foreach($images as $image): ?>
    <div class="item <?php if($i++ == 0)echo 'active'; ?>">
      <img src="<?php echo _show_img($image->filename, '900x500', false, null); ?>" alt="<?php echo $image->alt; ?>">
      <div class="carousel-caption">
        <?php echo $image->title; ?>
      </div>
    </div>
<?php endforeach; ?>
  </div>

<?php if(sw_count($images) > 1): ?>
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

<h4><?php echo _field_name(13);?></h4>

<?php echo _field($listing, 13); ?>

<?php foreach($fields as $key=>$field): ?>
<?php if($field['parent']['type'] == 'CATEGORY' && $field['parent']['is_preview_visible']): ?>
<?php $in_cat_counter = 0; ?>


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
$w_p_title = _field($listing, 10);
$gmap_lat = $listing->lat;
$gmap_long = $listing->lng;
$property_address = $listing->address;
$metric = 'km';
$zoom = '18';


$pin_icon = 'null';

if(file_exists(SW_WIN_PLUGIN_PATH.'assets/img/markers/'._field($listing, 14).'.png'))
{
    $pin_icon = plugins_url( SW_WIN_SLUG.'/assets').'/img/markers/'._field($listing, 14).'.png';
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

?>


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
            console.log(data.message);
                            
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
    padding-top: 10px;
}



</style>