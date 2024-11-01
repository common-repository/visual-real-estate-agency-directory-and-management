<!DOCTYPE html>
<html>
  <head>
    <title><?php echo __('Image croping', 'sw_win'); ?></title>

    <link href="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/css/bootstrap.css" rel="stylesheet">
    <script src="<?php echo includes_url( 'js/jquery/jquery.js' );?>"></script>
    <script src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/js/cropit/jquery.cropit.js"></script>

    <style>
      .cropit-image-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-top: 7px;
        width: <?php echo $width; ?>px;
        height: <?php echo $height; ?>px;
        cursor: move;
      }
      
      .cropit-image-zoom-input
      {
        padding:13px 0px 0px 5px;
        margin:0px;
      }

      .cropit-image-background {
        opacity: .2;
        cursor: auto;
      }
      
      .hidden-image-data
      {
        dinplay:none;
      }

      .image-size-label {
        margin-top: 10px;
        float:left;
      }

      input {
        display: block;
      }

      button[type="submit"] {
        margin-top: 10px;
      }

      #result {
        margin-top: 10px;
        width: <?php echo $width; ?>px;
      }

      #result-data {
        display: block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        word-wrap: break-word;
      }
      
    .alert-success {
        color: #468847;
        background-color: #DFF0D8;
        border-color: #D6E9C6;
        padding:5px;
    }
    
    .form-horizontal .form-group {
    margin-right: 0px;
    margin-left: 0px;
}

    form {
        padding-top: 15px;
    }

    </style>
  </head>
  <body>
    <form action="" class="form-horizontal" method="post" role="form" >
    <?php if($resize!=='false'): ?>
        <?php if($width_r >= $width+1 || $height_r >= $height+1): ?>
        <div class="alert alert-success">
        <?php echo __('Move image to wanted position, only on aspect ratio issues.', 'sw_win'); ?>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">
        <?php echo __('Image is already in right aspect ration.', 'sw_win'); ?>
        </div>
        <?php endif; ?>
    <?php endif;?>
    <?php //if($this->session->flashdata('message')):?>
    <?php //echo $this->session->flashdata('message')?>
    <?php //endif;?>
      
      <div class="image-editor">
        <?php if($resize!=='false'): ?>
        <?php if($width_r >= $width+1 || $height_r >= $height+1): ?>
        <input type="file" class="cropit-image-input" style="visibility:  hidden;display:none;" />
        <div class="cropit-image-preview"></div>
        
        <div class="form-group">
            <label for="input-alt" class="col-sm-2 control-label"><?php echo __('Resize image', 'sw_win'); ?></label>
            <div class="col-sm-5">
                <input type="range" class="cropit-image-zoom-input">
                <input type="hidden" name="image-data" class="hidden-image-data" />
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        

        <div class="form-group">
            <label for="input-alt" class="col-sm-2 control-label"><?php echo __('Alt', 'sw_win'); ?></label>
            <div class="col-sm-5">
                <input type="text" name="alt" value="<?php echo set_value('alt', $form->alt); ?>" class="form-control" id="input-alt" placeholder="<?php echo __('Alt', 'sw_win'); ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label for="input-description" class="col-sm-2 control-label"><?php echo __('Description', 'sw_win'); ?></label>
            <div class="col-sm-5">
                <textarea class="form-control" name="description" placeholder="<?php echo __('Description', 'sw_win'); ?>" rows="3"><?php echo set_value('description', $form->description); ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="input-description" class="col-sm-2 control-label"><?php echo __('Title', 'sw_win'); ?></label>
            <div class="col-sm-5">
                <input type="text" name="title" value="<?php echo set_value('alt', $form->title); ?>" class="form-control" id="input-title" placeholder="<?php echo __('Title', 'sw_win'); ?>" />
            </div>
        </div>
        <div class="form-group hidden">
            <label for="input-description" class="col-sm-2 control-label"><?php echo __('Link', 'sw_win'); ?></label>
            <div class="col-sm-5">
                 <input type="text" name="link" value="<?php echo set_value('alt', $form->link); ?>" class="form-control" id="input-link" placeholder="<?php echo __('Link', 'sw_win'); ?>" />
            </div>
        </div>
        
        <div class="form-group">
    <div class="col-sm-offset-2 col-sm-5">
      <button type="submit" class="btn btn-primary"><?php echo __('Save', 'sw_win'); ?></button>
    </div>
  </div>
        
      </div>
      
    </form>

    <div id="result" style="visibility:  hidden;">
      <code>$form.serialize() =</code>
      <code id="result-data"></code>
    </div>

    <script>
    
      var imageSrc = "<?php echo $filepath; ?>";
      <?php if($width_r >= $width+1 || $height_r >= $height+1): ?>
      jQuery(document).ready(function($) {
        $('.image-editor').cropit({ imageState: { src: imageSrc } });
        
        $('form').submit(function() {
          // Move cropped image data to hidden input
          var imageData = $('.image-editor').cropit('export');
          $('.hidden-image-data').val(imageData);

          // Print HTTP request params
          var formValue = $(this).serialize();
          //$('#result-data').text(formValue);

          // Prevent the form from actually submitting
          return true;
        });
        
      });
        <?php endif; ?>
    </script>
  </body>
</html>
