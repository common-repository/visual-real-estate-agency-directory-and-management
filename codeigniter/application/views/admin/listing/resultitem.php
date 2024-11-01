
<h1><?php echo __('Result item editor','sw_win'); ?></h1>

<div class="bootstrap-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Result item data','sw_win'); ?></h3>
        </div>
        <div class="panel-body">
        
    <?php _form_messages(); ?>
  <div class="row">
    <form action="" class="form-horizontal" method="post">

      <div class="form-group <?php _has_error('form_name'); ?> IS-INPUTBOX">
        <label for="input_form_name" class="col-sm-2 control-label"><?php echo __('Form name','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="form_name" value="<?php echo _fv('form_object', 'form_name'); ?>" type="text" id="input_form_name" class="form-control" placeholder="<?php echo __('Form name','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('type'); ?> IS-INPUTBOX">
        <label for="input_type" class="col-sm-2 control-label"><?php echo __('Type','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="type" value="<?php echo _fv('form_object', 'type', 'TEXT', 'RESULT_ITEM'); ?>" type="text" id="input_type" class="form-control" placeholder="<?php echo __('Type','sw_win'); ?>" readonly="" />
        </div>
      </div>
      
      <div class="form-group <?php _has_error('fields_order'); ?> IS-INPUTBOX">
        <label for="input_fields_order" class="col-sm-2 control-label"><?php echo __('Fields order','sw_win'); ?></label>
        <div class="col-sm-10">
        <?php 
        $fields_value_json_1 = set_value('fields_order', _fv('form_object', 'fields_order'));
        $fields_value_json_1 = htmlspecialchars_decode($fields_value_json_1);
        $fields_value_json_1 = stripslashes($fields_value_json_1);
        
        $data = array(
            'name'        => 'fields_order',
            'id'          => 'input_fields_order',
            'rows'        => '3',
            'cols'        => '10',
            'class'       => 'form-control'
        );
        echo form_textarea($data, $fields_value_json_1, 'placeholder="'.__('Fields order', 'sw_win').'" class="form-control" id="input_fields_order" readonly="" ')?>
        </div>
      </div>

      <hr />
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary"><?php echo __('Save', 'sw_win'); ?></button>
        </div>
      </div>
    </form>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                <h3 class="panel-title"><?php echo __('Drag from here','sw_win'); ?></h3>
                </div>
                <div class="panel-body">
        <div class="drag_visual_container">
        <table>
            <tr>
                <td class="box header">
                <span><?php echo __('FIELDS', 'sw_win'); ?></span>
<?php

    $disabled_items = array('UPLOAD', 'TEXTAREA', 'PEDIGREE', 'HTMLTABLE', 'CATEGORY');
    
//    if(!file_exists(APPPATH.'controllers/admin/treefield.php'))
//    {
//        $disabled_items[] = 'TREE';
//    }

    foreach($this->fields as $key=>$row)
    {
        if(!in_array($row->type, $disabled_items))
            echo '<div class="el_drag el_style '.$row->type.'" f_direction="NONE" f_type="'.$row->type.'" f_id="'.$row->idfield.'" rel="'.$row->type.'_'.$row->idfield.'">#'.$row->idfield.', '.$row->field_name.'</div>';
    }          
?>
                </td>
            </tr>
        </table>
        </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                <h3 class="panel-title"><?php echo __('Drop to here','sw_win'); ?></h3>
                </div>
                <div class="panel-body">
                
        <div class="drop_visual_container">
        <table>
            <tr>
                <td class="box PRIMARY" colspan="2"><span class=""><?php echo __('RESULT ITEM FIELDS', 'sw_win'); ?></span>
<?php
$fields_value_json_1 = htmlspecialchars_decode($fields_value_json_1);
$obj_widgets = json_decode($fields_value_json_1);

if(is_object($obj_widgets) && is_object($obj_widgets->PRIMARY))
foreach($obj_widgets->PRIMARY as $key=>$obj)
{
    $title = '';
    $rel = $obj->type;
    $class_color = $obj->type;
    $direction = 'NONE';
    if($obj->id != 'NONE')
    {
        $field_data = $this->field_m->get_field_data($obj->id, sw_current_language_id());
        
        if(isset($field_data) && isset($field_data->field_name))
        {
            $title.='#'.$obj->id.', ';
            $title.=$field_data->field_name;
            $rel = $field_data->type.'_'.$obj->id;
            
            if($obj->direction != 'NONE')
            {
                $direction = $obj->direction;
                $title.=', '.$direction;
                $rel.='_'.$obj->direction;
            }
        }
    }
    else
    {
        $title.=$obj->type;
        $class_color='custom';
    }

    if(!empty($title))
    echo '<div class="el_sort el_style '.$class_color.'" f_style="'.$obj->style.'" f_class="'.$obj->class.'" f_direction="'.$direction.'" f_type="'.$obj->type.'" f_id="'.$obj->id.'" rel="'.$rel.'" style="width:100%;"><span>'.$title.
         '</span><a href="#test-form" target="_blank" class="btn btn-success btn-xs popup-with-form"><i class="glyphicon glyphicon-pencil"></i></a>'.
         '<button type="button" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></button></div>';
}
?>
                </td>
            </tr>

        </table>
        </div>
                
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

<?php

wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-widget', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-sortable', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-droppable', false, array('jquery'), false, false);
wp_enqueue_script('jquery-magnific-popup');
wp_enqueue_style( 'jquery-magnific-popup');

?>

<script>

// Generate table
jQuery(document).ready(function($) {
    save_json_changes();
    $('#widgets_order_json').val('<?php echo $fields_value_json_1; ?>');
    
    $( ".el_drag" ).draggable({
        revert: "invalid",
        zIndex: 9999,
        helper: "clone"
    });
    
    <?php $widget_positions = array('PRIMARY', 'SECONDARY');
          foreach($widget_positions as $position_box): ?>
    
    $( ".box.<?php echo $position_box; ?>" ).sortable({items: "div.el_sort"});
    
    $(".drop_visual_container .box.<?php echo $position_box; ?>" ).droppable({
      accept: ".el_drag",
      activeClass: "ui-state-hover",
      hoverClass: "ui-state-active",
      drop: function( event, ui ) {
        var exists = false;
        
        jQuery.each($('.el_sort'), function( i, val ) {
            if(ui.draggable.attr('rel') == $(this).attr('rel') && ui.draggable.attr('rel') != 'BREAKLINE')
            {
                exists = true;
            }
        });
        
        if(exists)
        {
            ShowStatus.show('<?php echo_js(__('Already added', 'sw_win')); ?>');
            return;   
        }
        
        <?php if($position_box == 'SECONDARY'): ?>
        
        if(ui.draggable.attr('f_type') != 'INPUTBOX' && ui.draggable.attr('f_type') != 'DROPDOWN' && 
           ui.draggable.attr('f_type') != 'CHECKBOX' && ui.draggable.attr('f_type') != 'BREAKLINE' &&
           ui.draggable.attr('f_type') != 'INTEGER' && 
           ui.draggable.attr('f_type') != 'DROPDOWN_MULTIPLE')
        {
            
            //console.log(ui.draggable.attr('f_type'));
            
            ShowStatus.show('<?php echo_js(__('Supported only for PRIMARY form', 'sw_win')); ?>');
            return;   
        }
        
        <?php endif; ?>
        
        var new_el = ui.draggable.clone();
        new_el.css('top', 'auto');
        new_el.css('left', 'auto');
        new_el.css('width', '100%');
        new_el.removeClass('el_drag');
        new_el.addClass('el_sort');
        new_el.attr('f_style', '');
        new_el.attr('f_class', '');
        new_el.html('<span>'+new_el.html()+'</span>');
        new_el.append('<button type="button" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></button>');
        new_el.append('<a href="#test-form" class="btn btn-success btn-xs popup-with-form"><i class="glyphicon glyphicon-pencil"></i></a>');
        
        new_el.clone().appendTo( this );

        $(this).sortable("refresh"); 
        
        $('.drop_visual_container .box .btn-danger').click(function(){
           $(this).parent().remove();
           save_json_changes();
        });
        
        save_json_changes();
      }
    }).sortable({
      update: function( event, ui ) {
        save_json_changes();
      },
      items: "div.el_sort"
    });
    <?php endforeach;?>
    
    $('.drop_visual_container .box .btn-danger').click(function(){
       $(this).parent().remove();
       save_json_changes();
    });
    
    define_popup_trigers();
    
    $('#unhide-agent-mask').click(function(){
        
        var data = $('#test-form').serializeArray();

        $('.el_sort[rel='+data[0].value+']').attr('f_style', filterInput(data[1].value));
        $('.el_sort[rel='+data[0].value+']').attr('f_class', filterInput(data[2].value));

        var res = data[0].value.split("_");
        var res2 = $('.el_sort[rel='+data[0].value+'] span').html().split(", ");
        
        save_json_changes();

        // Display agent details
        //$('.popup-with-form').css('display', 'none');
        // Close popup
        $.magnificPopup.instance.close();

        return false;
    });
    
    function filterInput(input){
        return input.replace(/[^a-zA-Z0-9:;-\s]/g, '');
    }
    
    function define_popup_trigers()
    {
        $('.popup-with-form').magnificPopup({
        	type: 'inline',
        	preloader: false,
        	focus: '#inputStyle',
                            
        	// When elemened is focused, some mobile browsers in some cases zoom in
        	// It looks not nice, so we disable it:
        	callbacks: {
        		beforeOpen: function() {
        			if($(window).width() < 700) {
        				this.st.focus = false;
        			} else {
        				this.st.focus = '#inputStyle';
        			}
        		},
                
        		open: function() {
                    var magnificPopup = $.magnificPopup.instance,
                    cur = magnificPopup.st.el.parent();
                    
                    $('#inputRel').val(cur.attr('rel'));
                    $('#inputStyle').val(cur.attr('f_style'));
                    $('#inputClass').val(cur.attr('f_class'));
                    $('#inputDirection').val(cur.attr('f_direction'));
                    
        		}
        	}
        });
    }
    
    function save_json_changes()
    {
        var js_gen = '{ ';
        <?php foreach($widget_positions as $position_box): ?>
        js_gen+= ' "<?php echo $position_box; ?>": {  ';
        
        jQuery.each($(".drop_visual_container .box.<?php echo $position_box; ?> div"), function( i, val ) {
           if($(this).attr('rel'))
            js_gen+= '"'+$(this).attr('rel')+'":{"direction":"'+$(this).attr('f_direction')+'", "style":"'
                        +$(this).attr('f_style')+'", "class":"'+$(this).attr('f_class')+'", "id":"'+$(this).attr('f_id')
                        +'", "type":"'+$(this).attr('f_type')+'"} ,';
        });
        
        js_gen = js_gen.slice(0,-2);
            
        js_gen+= ' },';
        <?php endforeach; ?>
        js_gen = js_gen.slice(0,-1);
        js_gen+= ' }';
        
        $('#input_fields_order').val(js_gen);
        
        define_popup_trigers();
    }

});


</script>

<style>



.drag_visual_container
{
    width:100%;
    border:1px solid black;
    padding:5px;
    background: white;
    max-width:600px;
    margin:auto;
}

.drag_visual_container table
{
    width:100%;
}

.drag_visual_container .box
{
    border:1px solid #EEEEEE;
    height:40px;
    position: relative;
    vertical-align: top;
}

.drag_visual_container .box span
{
    display:block;
    text-align: center;
    background:#EEEEEE;
}

div.el_style
{
    background: #67BDC4;
    border:1px solid white;
    display:block;
    text-align: center;
    color:white;
    padding:5px;
    margin:0px 2px 0px 2px;
    float:left;
    width:49%;
    z-index: 100;
    cursor:move;
}

div.el_style.ui-draggable-dragging
{
    border:1px solid black;
    cursor: move;
}

div.el_style.custom
{
    background: #699057;
}

div.el_style.CHECKBOX
{
    background: #CC470C;
}

div.el_style.DROPDOWN
{
    background: #1E0D38;
}

div.el_style.INPUTBOX
{
    background: #4C8AB4;
}

div.el_style.DROPDOWN_MULTIPLE
{
    background: #155F86;
}






.drop_visual_container
{
    width:100%;
    border:1px solid black;
    padding:5px;
    background: white;
    max-width:600px;
    margin:auto;
}

.drop_visual_container table
{
    width:100%;
}

.drop_visual_container .box
{
    border:1px solid #EEEEEE;
    height:200px;
    position: relative;
    vertical-align: top;
}

.drop_visual_container .box.bottom,
.drop_visual_container .box.footer,
.drop_visual_container .box.header
{
    height:100px;
}

.drop_visual_container .box span
{
    display:block;
    text-align: center;
    background:#EEEEEE;
}

.drop_visual_container .box .el_sort span
{
    background:none;
}

.drop_visual_container .box div
{
    position:relative;
}

.drop_visual_container .box .btn-danger
{
    right:5px;
    position:absolute;
    top:3px;
}

.drop_visual_container .box .btn-success
{
    right:28px;
    position:absolute;
    top:3px;
}

</style>

<!-- form itself -->
<form id="test-form" class="form-horizontal mfp-hide white-popup-block">
    <div id="popup-form-validation">
    <p class="hidden alert alert-error"><?php echo __('Submit failed, please populate all fields!', 'sw_win'); ?></p>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputRel"><?php echo __('Rel', 'sw_win'); ?></label>
        <div class="controls">
            <input type="text" name="rel" id="inputRel" value="" placeholder="<?php echo __('Rel', 'sw_win'); ?>" readonly>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputStyle"><?php echo __('Style', 'sw_win'); ?></label>
        <div class="controls">
            <input type="text" name="style" id="inputStyle" value="" placeholder="<?php echo __('Style', 'sw_win'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputClass"><?php echo __('Class', 'sw_win'); ?></label>
        <div class="controls">
            <input type="text" name="class" id="inputClass" value="" placeholder="<?php echo __('Class', 'sw_win'); ?>">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button id="unhide-agent-mask" type="button" class="btn"><?php echo __('Submit', 'sw_win'); ?></button>
            <img id="ajax-indicator-masking" src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/ajax-loader.gif" style="display: none;" />
        </div>
    </div>
</form>
