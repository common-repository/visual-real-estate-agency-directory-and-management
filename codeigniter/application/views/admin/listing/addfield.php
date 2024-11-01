
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Edit listing field','sw_win'); ?> </h1>
<?php else: ?>
<h1><?php echo __('Add listing field','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Field data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php _form_messages(); ?>
  
    <form action="" class="form-horizontal" method="post">
    
      <div class="form-group <?php _has_error('type'); ?>">
        <label for="inputType" class="col-sm-2 control-label"><?php echo __('Type','sw_win'); ?></label>
        <div class="col-sm-10">
          <?php echo form_dropdown('type', $this->field_m->field_types, _fv('form_object', 'type'), 'class="form-control"')?>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('parent_id'); ?>">
        <label for="inputParent" class="col-sm-2 control-label"><?php echo __('Parent','sw_win'); ?></label>
        <div class="col-sm-10">
          <?php echo form_dropdown('parent_id', $fields_no_parents, _fv('form_object', 'parent_id'), 'class="form-control"')?>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_table_visible'); ?>">
        <label for="inputVisibleInTable" class="col-sm-2 control-label"><?php echo __('Visible in table','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_table_visible" value="1" type="checkbox" <?php echo _fv('form_object', 'is_table_visible', 'CHECKBOX'); ?>/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_preview_visible'); ?>">
        <label for="inputVisibleOnPreview" class="col-sm-2 control-label"><?php echo __('Visible on listing preview','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_preview_visible" value="1" type="checkbox" <?php echo _fv('form_object', 'is_preview_visible', 'CHECKBOX', '1'); ?>/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_submission_visible'); ?>">
        <label for="inputVisibleOnSubmission" class="col-sm-2 control-label"><?php echo __('Visible on frontend submission','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_submission_visible" value="1" type="checkbox" <?php echo _fv('form_object', 'is_submission_visible', 'CHECKBOX', '1'); ?>/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_quickvisible'); ?>">
        <label for="inputis_quickvisible" class="col-sm-2 control-label"><?php echo __('Visible on quick submission','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_quickvisible" value="1" type="checkbox" <?php echo _fv('form_object', 'is_quickvisible', 'CHECKBOX', '1'); ?>/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_translatable'); ?>">
        <label for="inputVisibleOnSubmission" class="col-sm-2 control-label"><?php echo __('Is translatable','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_translatable" value="1" type="checkbox" <?php echo _fv('form_object', 'is_translatable', 'CHECKBOX', '1'); ?>/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_required'); ?> NOT-TREE NOT-UPLOAD NOT-CATEGORY">
        <label for="inputIsRequired" class="col-sm-2 control-label"><?php echo __('Is required','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_required" value="1" type="checkbox" <?php echo _fv('form_object', 'is_required', 'CHECKBOX', ''); ?>/>
          <span class="label label-info"><?php echo __('Not available for all field types', 'sw_win')?></span>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_locked'); ?>">
        <label for="inputIsLocked" class="col-sm-2 control-label"><?php echo __('Is locked','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_locked" value="1" type="checkbox" <?php echo _fv('form_object', 'is_locked', 'CHECKBOX'); ?>/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('max_length'); ?> IS-INPUTBOX">
        <label for="inputMaxLength" class="col-sm-2 control-label"><?php echo __('Max length','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="max_length" value="<?php echo _fv('form_object', 'max_length'); ?>" type="text" id="inputMaxLength" class="form-control" placeholder="<?php echo __('Max length','sw_win'); ?>"/>
        </div>
      </div>
      
      <?php if($make_searchable_visible): ?>
      <div class="form-group <?php _has_error('make_searchable'); ?> NOT-TREE NOT-UPLOAD NOT-CATEGORY">
        <label for="inputIsRequired" class="col-sm-2 control-label"><?php echo __('Make searchable','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="make_searchable" value="1" type="checkbox" <?php echo _fv('form_object', 'make_searchable', 'CHECKBOX', ''); ?>/>
          <span class="label label-info"><?php echo __('Enable searching like from/to or some text', 'sw_win')?></span>
        </div>
      </div>
      <?php endif; ?>
        
      <div class="form-group <?php _has_error('columns_number'); ?> ">
        <label for="inputcolumns_number" class="col-sm-2 control-label"><?php echo __('Columns number','sw_win'); ?></label>
        <div class="col-sm-10">
          <?php echo form_dropdown('columns_number', $this->field_m->columns_number, _fv('form_object', 'columns_number'), 'class="form-control"')?>
        </div>
      </div>
              
    <?php if(true): ?>
          <div class="form-group <?php _has_error('image_id'); ?>">
            <label for="inputParent" class="col-sm-2 control-label"><?php echo __('Image field','sw_win'); ?></label>
            <div class="col-sm-10">
                <div id="meta-box-id" class="postbox" style="border: 0px;">
                <?php
                $post_id = -1;

                // Get WordPress' media upload URL
                $upload_link = esc_url( get_upload_iframe_src( 'image', $post_id ) );

                // See if there's a media id already saved as post meta
                $your_img_id = _fv('form_object', 'image_id');

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
                <input class="image_id" name="image_id" type="hidden" value="<?php echo esc_attr( $your_img_id ); ?>" />
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
        
          <div class="form-group <?php _has_error('field_name_'.$key); ?>">
            <label for="inputField_<?php echo $key?>" class="col-sm-2 control-label"><?php echo __('Field name','sw_win'); ?></label>
            <div class="col-sm-10">
              <input name="field_name_<?php echo $key?>" type="text" value="<?php echo _fv('form_object', 'field_name_'.$key); ?>" class="form-control" id="inputField_<?php echo $key?>" placeholder="<?php echo __('Field name','sw_win'); ?>">
            </div>
          </div>
          
          <div class="form-group <?php _has_error('values_'.$key); ?>">
            <label for="inputValues_<?php echo $key?>" class="col-sm-2 control-label"><?php echo __('Values (Without spaces)','sw_win'); ?></label>
            <div class="col-sm-10">
              <input name="values_<?php echo $key?>" type="text" value="<?php echo _fv('form_object', 'values_'.$key); ?>" class="form-control" id="inputValues_<?php echo $key?>" placeholder="<?php echo __('Values (Without spaces)','sw_win'); ?>">
            </div>
          </div>
          
          <div class="form-group <?php _has_error('prefix_'.$key); ?>">
            <label for="inputPrefix_<?php echo $key?>" class="col-sm-2 control-label"><?php echo __('Prefix','sw_win'); ?></label>
            <div class="col-sm-10">
              <input name="prefix_<?php echo $key?>" type="text" value="<?php echo _fv('form_object', 'prefix_'.$key); ?>" class="form-control" id="inputPrefix_<?php echo $key?>" placeholder="<?php echo __('Prefix','sw_win'); ?>">
            </div>
          </div>
          
          <div class="form-group <?php _has_error('suffix_'.$key); ?>">
            <label for="inputSuffix_<?php echo $key?>" class="col-sm-2 control-label"><?php echo __('Suffix','sw_win'); ?></label>
            <div class="col-sm-10">
              <input name="suffix_<?php echo $key?>" type="text" value="<?php echo _fv('form_object', 'suffix_'.$key); ?>" class="form-control" id="inputSuffix_<?php echo $key?>" placeholder="<?php echo __('Suffix','sw_win'); ?>">
            </div>
          </div>
          
          <div class="form-group <?php _has_error('hint_'.$key); ?>">
            <label for="inputHint_<?php echo $key?>" class="col-sm-2 control-label"><?php echo __('Hint','sw_win'); ?></label>
            <div class="col-sm-10">
              <input name="hint_<?php echo $key?>" type="text" value="<?php echo _fv('form_object', 'hint_'.$key); ?>" class="form-control" id="inputHint_<?php echo $key?>" placeholder="<?php echo __('Hint','sw_win'); ?>">
            </div>
          </div>
          
          <div class="form-group <?php _has_error('placeholder_'.$key); ?>">
            <label for="inputplaceholder_<?php echo $key?>" class="col-sm-2 control-label"><?php echo __('Placeholder','sw_win'); ?></label>
            <div class="col-sm-10">
              <input name="placeholder_<?php echo $key?>" type="text" value="<?php echo _fv('form_object', 'placeholder_'.$key); ?>" class="form-control" id="inputplaceholder_<?php echo $key?>" placeholder="<?php echo __('Placeholder','sw_win'); ?>">
            </div>
          </div>
        
        </div>
        <?php endforeach; ?>
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
</div>

</div>
    
    
<script>

/* 
    For custom field type elements, hide/show feature
    
    Example usage:
    css class: NOT-TREE, IS-TREE
    <div class="form-group NOT-TREE">
    <div class="form-group IS-TREE">
*/

jQuery(document).ready(function($) {
    reset_field_visibility();
    
    var field_type = $("select[name=type]").val();
    $(".NOT-"+field_type).hide();
    $(".IS-"+field_type).show();
        
    $("select[name=type]").change(function(){
        reset_field_visibility();
        
        var field_type = $(this).val();
        $(".NOT-"+field_type).hide();
        $(".IS-"+field_type).show();
    });
    
    function reset_field_visibility()
    {
        $("select[name=type] option" ).each(function( index ) {
            var field_type = $( this ).attr('value');
            
            $(".NOT-"+field_type).show();
            $(".IS-"+field_type).hide();
        });
    }

});

</script>

<?php wp_enqueue_media();?>
    
<script>

jQuery(document).ready(function($) {

  // Set all variables to be used in scope
  var frame,
      metaBox = $('#meta-box-id.postbox'), // Your meta box id here
      addImgLink = metaBox.find('.upload-custom-img'),
      delImgLink = metaBox.find( '.delete-custom-img'),
      imgContainer = metaBox.find( '.custom-img-container'),
      imgIdInput = metaBox.find( '.image_id' );
  
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