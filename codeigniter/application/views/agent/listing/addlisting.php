
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Edit listing','sw_win'); ?> </h1>
<?php else: ?>
<h1><?php echo __('Add listing','sw_win'); ?> </h1>
<?php endif; ?>

<?php

    //dump();

?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Listing data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
    <?php
    $message = __('Changes saved', 'sw_win');
    if(isset($form_object)) {
        $message .= ', <a href="'.esc_url(listing_url($form_object)).'" target="_blank" class="">'.__('Preview listing', 'sw_win').'</a> ';
    }
    ?>   
    <?php _form_messages($message); ?>
    <form action="" class="form-horizontal" method="post">
    
    <div class="row">
    <div class="col-xs-12 col-sm-6">
    
      <div class="form-group <?php _has_error('address'); ?> IS-INPUTBOX">
        <label for="input_address" class="col-sm-3 control-label"><?php echo __('Address','sw_win'); ?></label>
        <div class="col-sm-9">
          <input name="address" value="<?php echo _fv('form_object', 'address'); ?>" type="text" id="input_address" class="form-control" placeholder="<?php echo __('Address','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('gps'); ?> IS-INPUTBOX">
        <label for="input_gps" class="col-sm-3 control-label"><?php echo __('Gps','sw_win'); ?></label>
        <div class="col-sm-9">
          <input name="gps" value="<?php echo _fv('form_object', 'gps'); ?>" type="text" id="input_gps" class="form-control" readonly="" placeholder="<?php echo __('Gps','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_modified'); ?> IS-INPUTBOX">
        <label for="input_date_modified" class="col-sm-3 control-label"><?php echo __('Date modified','sw_win'); ?></label>
        <div class="col-sm-9">
          <input name="date_modified" value="<?php echo _fv('form_object', 'date_modified'); ?>" type="text" id="input_date_modified" readonly="" class="form-control" placeholder="<?php echo __('Date modified','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group hidden <?php _has_error('repository_id'); ?>">
        <label for="input_repository_id" class="col-sm-3 control-label"><?php echo __('Repository','sw_win'); ?></label>
        <div class="col-sm-9">
          <input name="repository_id" value="<?php echo _fv('form_object', 'repository_id'); ?>" type="text" id="input_repository_id" class="form-control" readonly="" placeholder="<?php echo __('Repository','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('transition_id'); ?> hidden IS-INPUTBOX">
        <label for="input_transition_id" class="col-sm-3 control-label"><?php echo __('Transition id','sw_win'); ?></label>
        <div class="col-sm-9">
          <input name="transition_id" value="<?php echo _fv('form_object', 'transition_id'); ?>" type="text" id="input_transition_id" class="form-control" readonly="" placeholder="<?php echo __('Transition id','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group hidden <?php _has_error('user_id'); ?>">
        <label for="input_user_id" class="col-sm-3 control-label"><?php echo __('Agents','sw_win'); ?></label>
        <div class="col-sm-9">
          <?php echo form_multiselect('user_id', $agents, _fv('form_object', 'user_id', 'MULTISELECT', array_keys($agents)), 'class="form-control"')?>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_primary'); ?>">
        <label for="input_is_primary" class="col-sm-3 control-label"><?php echo __('Is primary','sw_win'); ?></label>
        <div class="col-sm-9">
          <input name="is_primary" id="is_primary" value="1" type="checkbox" <?php echo _fv('form_object', 'is_primary', 'CHECKBOX', '1'); ?>/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('related_id'); ?> group_related_id">
        <label for="input_related_id" class="col-sm-3 control-label"><?php echo __('Related','sw_win'); ?></label>
        <div class="col-sm-9">
          <?php echo form_dropdown_ajax('related_id', 'listing_m', _fv('form_object', 'related_id'), 'address', sw_current_language_id());?>
        </div>
      </div>

      <div class="form-group hidden <?php _has_error('is_featured'); ?>">
        <label for="input_is_featured" class="col-sm-3 control-label"><?php echo __('Is featured','sw_win'); ?></label>
        <div class="col-sm-9">
          <input name="is_featured" value="1" type="checkbox" <?php echo _fv('form_object', 'is_featured', 'CHECKBOX'); ?>/>
        </div>
      </div>

      <div class="form-group hidden <?php _has_error('is_activated'); ?>">
        <label for="input_is_activated" class="col-sm-3 control-label"><?php echo __('Is activated','sw_win'); ?></label>
        <div class="col-sm-9">
          <input name="is_activated" value="1" type="checkbox" <?php echo _fv('form_object', 'is_activated', 'CHECKBOX'); ?>/>
        </div>
      </div>
      
      <?php if(sw_settings('show_categories') && sw_settings('enable_multiple_treefield')): ?>
      
      <div class="form-group <?php _has_error('category_id'); ?> group_category_id">
        <label for="input_category_id" class="col-sm-3 control-label"><?php echo __('Primary Category','sw_win'); ?></label>
        <div class="col-sm-9">
          <?php echo form_treefield('category_id', 'treefield_m', _fv('form_object', 'category_id'), 'value', sw_current_language_id(), 'field_', false, '-');?>
        </div>
      </div>

      <div class="form-group <?php _has_error('category_id'); ?> multilevel">
        <label for="category_id_multi" class="col-sm-3 control-label"><?php echo __('Sub Categories','sw_win'); ?></label>
        <div class="col-sm-9">
            <?php echo form_multiselect('category_id_multi[]', $categories, _fv('form_object', 'category_id_multi', 'MULTISELECT', array_keys($categories)), 'class="form-control" id="category_id_multi"')?>
            <div class="agent_add form-inline">
            <?php echo form_treefield('category_id_select', 'treefield_m', '', 'value', sw_current_language_id(), 'field_', false, '-');?>
            <button type="button" class="btn btn-primary add_button"><?php echo __('Add category', 'sw_win'); ?></button>
            <button type="button" title="<?php echo __('Remove latest on list', 'sw_win'); ?>" class="btn btn-default rem_button"><?php echo __('X', 'sw_win'); ?></button>
            </div>
        </div>
      </div>

      <?php elseif(sw_settings('show_categories')): ?>

      <div class="form-group <?php _has_error('category_id'); ?> group_category_id">
        <label for="input_category_id" class="col-sm-3 control-label"><?php echo __('Category','sw_win'); ?></label>
        <div class="col-sm-9">
          <?php echo form_treefield('category_id', 'treefield_m', _fv('form_object', 'category_id'), 'value', sw_current_language_id(), 'field_', false, '-');?>
        </div>
      </div>
      
      <?php endif; ?>
      
      <?php if(sw_settings('show_locations') && sw_settings('enable_multiple_treefield')): ?>
      
      <div class="form-group <?php _has_error('location_id'); ?> group_location_id">
        <label for="input_location_id" class="col-sm-3 control-label"><?php echo __('Primary Location','sw_win'); ?></label>
        <div class="col-sm-9">
          <?php echo form_treefield('location_id', 'treefield_m', _fv('form_object', 'location_id'), 'value', sw_current_language_id(), 'field_', false, '-', 2);?>
        </div>
      </div>

      <div class="form-group <?php _has_error('location_id'); ?> multilevel">
        <label for="location_id_multi" class="col-sm-3 control-label"><?php echo __('Sub Locations','sw_win'); ?></label>
        <div class="col-sm-9">
            <?php echo form_multiselect('location_id_multi[]', $locations, _fv('form_object', 'location_id_multi', 'MULTISELECT', array_keys($locations)), 'class="form-control" id="location_id_multi"')?>
            <div class="agent_add form-inline">
            <?php echo form_treefield('location_id_select', 'treefield_m', '', 'value', sw_current_language_id(), 'field_', false, '-', 2);?>
            <button type="button" class="btn btn-primary add_button"><?php echo __('Add location', 'sw_win'); ?></button>
            <button type="button" title="<?php echo __('Remove latest on list', 'sw_win'); ?>" class="btn btn-default rem_button"><?php echo __('X', 'sw_win'); ?></button>
            </div>
        </div>
      </div>

      <?php elseif(sw_settings('show_locations')): ?>

      <div class="form-group <?php _has_error('location_id'); ?> group_location_id">
        <label for="input_location_id" class="col-sm-3 control-label"><?php echo __('Location','sw_win'); ?></label>
        <div class="col-sm-9">
          <?php echo form_treefield('location_id', 'treefield_m', _fv('form_object', 'location_id'), 'value', sw_current_language_id(), 'field_', false, '-', 2);?>
        </div>
      </div>
      
      <?php endif; ?>
      
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="alert alert-info alert-dismissible" role="alert">
        <?php echo __('Enter address, then drag and drop your autodetected location on map', 'sw_win'); ?>					
        </div>
        <div id="map"></div>
        <?php if(isset($form_object)):?>
        <br/>
        <div class="clearfix">
            <a href="<?php echo esc_url(listing_url($form_object)); ?>" target="_blank" class="btn btn-default add_button pull-right"><?php echo __('Open listing', 'sw_win'); ?></a>
        </div>
        <?php endif;?>
      </div>
      </div>
      
      <div class="row">
      <div class="col-xs-12 col-sm-12">
      <hr />
      
      <?php if(sw_count(sw_get_languages()) > 1): ?>
      <h4><?php echo __('Languages','sw_win'); ?></h4>
      <?php endif; ?>
    <div>
    
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
      
      <?php $i=0;if(sw_count(sw_get_languages()) > 1)foreach(sw_get_languages() as $key=>$row):$i++; 
      
        // show just default language if multilanguage is not required
        if(!sw_settings('multilanguage_required') && sw_default_language() != $row['lang_code'])
        {
            //continue;
        }
      ?>
        <li role="presentation" class="<?php echo $i==1?'active':''?>"><a href="#lang_<?php echo $key?>" aria-controls="<?php echo $row['lang_code']; ?>" role="tab" data-toggle="tab"><?php echo $row['title']; ?></a></li>
      <?php  endforeach; ?>
      </ul>
        
      <!-- Tab panes -->
      <div class="tab-content">
      
      <?php $i=0;foreach(sw_get_languages() as $key=>$row):$i++; ?>
      
      
        <div role="tabpanel" class="tab-pane <?php echo $i==1?'active':''?>" id="lang_<?php echo $key?>">

          <div class="field_slug form-group <?php _has_error('input_slug_'.$key); ?>">
            <label for="input_slug_<?php echo $key; ?>" class="col-sm-2 control-label"><?php echo __('Slug', 'sw_win'); ?></label>
            <div class="col-sm-10">
              <input name="input_slug_<?php echo $key; ?>" type="text" value="<?php echo _fv('form_object', 'input_slug_'.$key); ?>" class="form-control" id="input_slug_<?php echo $key; ?>" placeholder="<?php echo __('Slug', 'sw_win'); ?>">
            </div>
          </div>

            <div class="row">
            <?php foreach($fields_list as $key_field=>$field): ?>
            
            <?php
                if(!$field->is_translatable && sw_default_language() != $row['lang_code'])
                {
            ?>
            <div class="col-sm-12">
              <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?>">
                <label for="input_<?php echo $field->idfield.'_'.$key; ?>" class="col-sm-2 control-label"><?php echo $field->field_name; ?></label>
                <div class="col-sm-10">
                    <div class="alert alert-warning non-translatable" role="alert">
                    <?php echo __('Not translatable', 'sw_win'); ?>
                    </div>
                </div>
              </div>
            </div>

            <?php                    
                    continue;
                }
                
                $required = '';
                if($field->is_required)
                    $required = '*';
                
                $columns ='col-sm-12';
                
                if($field->columns_number)
                    switch ($field->columns_number) {
                        case 1: $columns ='col-sm-12 multi_columns';
                                break;
                        case 2: $columns ='col-sm-6 multi_columns';
                                break;
                        case 3: $columns ='col-sm-4 multi_columns';
                                break;
                    }
            ?>
            <?php if($field->type == 'CATEGORY'): ?>
            <div class="clearfix"></div>
            <div class="col-sm-12">
                <div class="field_<?php echo $field->idfield; ?>">
                <hr />
                <h4><?php echo $field->field_name?></h4>
                <hr />
                </div>
            </div>
            <?php elseif($field->type == 'INPUTBOX' || $field->type == 'DECIMAL' || $field->type == 'INTEGER'): ?>
            
            <?php
            
            $field_lang = $this->field_m->get_field_data($field->idfield, $key);
            
            $presuf='';
            if(!empty($field_lang))
                $presuf = $field_lang->prefix.$field_lang->suffix;
            ?>
            <div class="<?php echo esc_html($columns);?>">
            <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?>">
              <label for="input_<?php echo $field->idfield.'_'.$key; ?>" class="col-sm-2 control-label"><?php echo $required.$field->field_name; ?></label>
              <?php if(empty($presuf)): ?>
              <div class="col-sm-10">
                <input name="input_<?php echo $field->idfield.'_'.$key; ?>" type="text" value="<?php echo _fv('form_object', 'input_'.$field->idfield.'_'.$key); ?>" class="form-control" id="input_<?php echo $field->idfield.'_'.$key; ?>" placeholder="<?php echo $field->field_name; ?>">
              </div>
              <?php else: ?>
                  <div class="col-sm-7">
                    <input name="input_<?php echo $field->idfield.'_'.$key; ?>" type="text" value="<?php echo _fv('form_object', 'input_'.$field->idfield.'_'.$key); ?>" class="form-control" id="input_<?php echo $field->idfield.'_'.$key; ?>" placeholder="<?php echo $field->field_name; ?>">
                  </div>
                  <div class="col-sm-3">
                      <?php echo $presuf; ?>
                  </div>
              <?php endif; ?>
            </div>
          </div>
          
            <?php elseif($field->type == 'TEXTAREA'): ?>
            <div class="<?php echo esc_html($columns);?>">
            <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?>">
              <label for="input_<?php echo $field->idfield.'_'.$key; ?>" class="col-sm-2 control-label"><?php echo $required.$field->field_name; ?></label>
              <div class="col-sm-10">
                  <?php sw_wp_editor( _fv('form_object', 'input_'.$field->idfield.'_'.$key), 'input_'.$field->idfield.'_'.$key ); ?>
              </div>
            </div>
            </div>
          
            <?php elseif($field->type == 'DROPDOWN' || $field->type == 'DROPDOWN_MULTIPLE'): ?>
            
            <?php
            
                $field_lang = $this->field_m->get_field_data($field->idfield, $key);
                $values_available = explode(',', $field_lang->values);
                $values_available = array_combine($values_available, $values_available);
            
            ?>

          <div class="<?php echo esc_html($columns);?>">
          <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?>">
            <label for="input_<?php echo $field->idfield.'_'.$key; ?>" class="col-sm-2 control-label"><?php echo $required.$field->field_name; ?></label>
            <div class="col-sm-10">
              <?php echo form_dropdown('input_'.$field->idfield.'_'.$key, $values_available, _fv('form_object', 'input_'.$field->idfield.'_'.$key), 'class="form-control"')?>
            </div>
          </div>
          </div>
          
        <?php elseif($field->type == 'CHECKBOX'): ?>
        <div class="<?php echo esc_html($columns);?>">
          <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?> form-group-checkbox">
            <label for="inputIsLocked" class="col-sm-2 control-label"><?php echo $required.$field->field_name; ?></label>
            <div class="col-sm-10">
              <input name="<?php echo 'input_'.$field->idfield.'_'.$key; ?>" value="1" type="checkbox" <?php echo _fv('form_object', 'input_'.$field->idfield.'_'.$key, 'CHECKBOX'); ?>/>
            </div>
          </div>
        </div>
      
        <?php elseif($field->type == 'TABLE'): ?>
        <div class="<?php echo esc_html($columns);?>">
          <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?> form-group-checkbox">
            <label for="inputIsLocked" class="col-sm-2 control-label"><?php echo $required.$field->field_name; ?></label>
            <div class="col-sm-10">
                <?php 
                    $field_lang = $this->field_m->get_field_data($field->idfield, $key);
                    $columns = explode(',', $field_lang->values);
                ?>
                <?php echo form_table('input_'.$field->idfield.'_'.$key, $columns, _fv('form_object', 'input_'.$field->idfield.'_'.$key))?>
            </div>
          </div>
        </div>
        <?php elseif($field->type == 'DATETIME'): ?>
            <?php
            
            $field_lang = $this->field_m->get_field_data($field->idfield, $key);
            
            ?>
            <div class="<?php echo esc_html($columns);?>">
            <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?>">
              <label for="input_<?php echo $field->idfield.'_'.$key; ?>" class="col-sm-2 control-label"><?php echo $required.$field->field_name; ?></label>
              <div class="col-sm-10">

                <div class='input-group date' id="datetimepicker_field_<?php _che($field->idfield);?>_<?php _che($key);?>">
                    <?php
                    $date_format = 'YYYY-MM-DD HH:mm:ss';
                    ?>
                    <input name="input_<?php echo $field->idfield.'_'.$key; ?>" type="text" value="<?php echo _fv('form_object', 'input_'.$field->idfield.'_'.$key); ?>" class="picker form-control" id="input_<?php echo $field->idfield.'_'.$key; ?>" placeholder="<?php echo $field->field_name; ?>">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>

                <script>
                jQuery(function($) {
                      $('#datetimepicker_field_<?php _che($field->idfield);?>_<?php _che($key);?>').datetimepicker({
                        format:'<?php echo $date_format;?>', 
                        useCurrent:false
                      });
                });
                </script>
              </div>
            </div>
          </div>
            <?php else: ?>
                <?php dump($field); ?>
            <?php endif; ?>
            <?php endforeach; ?>
            </div>
        
        </div>
        <?php endforeach; ?>
      </div>
    
    </div>
      </div>
      </div>
      <?php if(function_exists('sw_win_load_ci_function_rankpackages') && !empty($rank_packages)): ?>
      <div class="row">
      <div class="col-xs-12 col-sm-12">
        
        <hr />
        <h4><?php echo __('Rank package', 'sw_win'); ?></h4>
        <hr />
        
        <p>
        <?php echo __('Purchase higher listing rank and sell faster', 'sw_win'); ?>
        </p>
        
        <?php
        
        $current_rank = _fv('form_object', 'rank');
        $date_rank_expire = _fv('form_object', 'date_rank_expire');

        if(!empty($current_rank) && !empty($date_rank_expire) && 
           time() > strtotime($date_rank_expire))
          $current_rank = NULL;
        
        if(empty($current_rank)):
        ?>
        
        <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th><?php echo __('Package name', 'sw_win');?></th>
                <th style="text-align:center;"><?php echo __('Days expire', 'sw_win');?></th>
                <th style="text-align:right;"><?php echo __('Price', 'sw_win');?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($rank_packages as $key=>$rank_package): 
            $selected = $key == 0 || (isset($_POST['packagerank']) && $_POST['packagerank'] == $rank_package->idpackagerank);
        ?>
            <tr>
                <td><?php echo form_radio('packagerank', $rank_package->idpackagerank, $selected, 'style="display:inline;width:auto;"'); ?></td>
                <td><strong><?php echo $rank_package->package_name; ?></strong></td>
                <td style="text-align:center;"><?php echo $rank_package->package_days==0?'-':$rank_package->package_days; ?></td>
                <td style="text-align:right;"><?php echo $rank_package->package_price.' '.sw_settings('default_currency'); ?></td>
            </tr>
        <?php //dump($rank_package); ?>
        <?php endforeach; ?>
        </tbody>
        </table>
        <?php else: ?>
        
        <div class="alert alert-warning" role="alert"><?php echo __('You have activated rank', 'sw_win').': '.$current_rank.' '.__('until', 'sw_win').': '.$date_rank_expire;?></div>

        <?php endif; ?>
      </div>
      </div>
      <?php endif; ?>
      <hr />
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary"><?php echo __('Save', 'sw_win'); ?></button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Photo and other files','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
<div class="upload-files-widget" id="upload-files-<?php echo $repository_id; ?>" rel="listing_m">
    <!-- The file upload form used as target for the file upload widget -->
    <form class="fileupload" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="<?php echo admin_url("admin.php?page=listing_manage"); ?>" /></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="fileupload-buttonbar">
            <div class="span7 col-md-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span><?php echo __('Add files...', 'sw_win')?></span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span><?php echo __('Delete selected', 'sw_win')?></span>
                </button>
                <input type="checkbox" class="toggle" />
            </div>
            <!-- The global progress information -->
            <div class="span5 col-md-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <br />
        <!-- The table listing the files available for upload/download -->
        <!--<table role="presentation" class="table table-striped">
        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">-->

          <div role="presentation" class="fieldset-content">
              
            <ul class="files files-list" data-toggle="modal-gallery" data-target="#modal-gallery">      
<?php foreach($this->file_m->get_repository($repository_id) as $file ):?>
<?php sw_add_file_tags($file); ?>
            <li class="img-rounded template-download fade in">
                <div class="preview">
                    <img class="img-rounded" alt="<?php echo $file->filename; ?>" data-src="<?php echo $file->thumbnail_url; ?>" src="<?php echo $file->thumbnail_url; ?>">
                </div>
                <div class="filename">
                    <code><?php echo character_hard_limiter($file->filename, 20)?></code>
                </div>
                <div class="options-container">
                    <?php if($file->zoom_enabled):?>
                    <a data-gallery="gallery" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="zoom-button btn btn-xs btn-success"><i class="glyphicon glyphicon-search"></i></a>                  
                    <a class="btn btn-xs btn-info iedit visible-inline-lg" rel="<?php echo $file->filename?>" href="#<?php echo $file->filename?>"><i class="glyphicon glyphicon-edit"></i></a>
                    <?php else:?>
                    <a target="_blank" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-search"></i></a>
                    <?php endif;?>
                    <span class="delete">
                        <button class="btn btn-xs btn-danger" data-type="POST" data-url="<?php echo $file->delete_url?>"><i class="glyphicon glyphicon-trash"></i></button>
                        <input type="checkbox" value="1" name="delete">
                    </span>
                </div>
            </li>
<?php endforeach;?>
            </ul>
            <br style="clear:both;"/>
          </div>
    </form>

</div>
  
  </div>
</div>

</div>

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">&lsaquo;</a>
    <a class="next">&rsaquo;</a>
    <a class="close">&times;</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<?php
    
    $CI =& get_instance();
    
    $lat = $lng = 0;
    
    if(!empty($CI->data['form_object']->lat))
        $lat = $CI->data['form_object']->lat;
    
    if(!empty($CI->data['form_object']->lng))
        $lng = $CI->data['form_object']->lng;
        
    if($lat == 0)
    {
        $lat = sw_settings('lat');
        $lng = sw_settings('lng');
    }
?>

<?php

if(sw_settings('open_street_map_enabled')) {
    wp_enqueue_script('leaflet-maps-api');
    wp_enqueue_script('leaflet-maps-api-cluster');
} else {
    wp_enqueue_script('google-maps-api-w');
}
wp_enqueue_script( 'jquery' );
wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-widget', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-sortable', false, array('jquery'), false, false);
wp_enqueue_script( 'blueimp-gallery' );
wp_enqueue_script( 'jquery.iframe-transport' );
wp_enqueue_script( 'jquery.fileupload' );
wp_enqueue_script( 'jquery.fileupload-fp' );
wp_enqueue_script( 'jquery.fileupload-ui' );
wp_enqueue_script( 'zebra_dialog' );
wp_enqueue_script( 'datetime-picker-moment' );
wp_enqueue_script( 'datetime-picker-bootstrap' );

wp_enqueue_style( 'blueimp-gallery');
wp_enqueue_style( 'jquery.fileupload-ui');
wp_enqueue_style( 'zebra_dialog');
wp_enqueue_style( 'datetime-picker-css');

?>

<script>

var geocoder;
var map;
var marker;
var timerMap;

jQuery(document).ready(function($) {
    
    primary_check();
    
    $('#is_primary').change(function(){
        primary_check();
    });
    
    
    <?php if(sw_settings('open_street_map_enabled')):?>
    var edit_map = L.map('map', {
        center: [<?php echo $lat; ?>, <?php echo $lng; ?>],
        zoom: 4,
    });     
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(edit_map);
    var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(edit_map);
    var edit_map_marker = L.marker(
        [<?php echo $lat; ?>, <?php echo $lng; ?>],
        {draggable: true}
    ).addTo(edit_map);

    edit_map_marker.on('dragend', function(event){
        var marker = event.target;
        var location = marker.getLatLng();
        var lat = location.lat;
        var lon = location.lng;
        $('#input_gps').val(lat+', '+lon);
        //retrieved the position
      });

        $('#input_address').on('change keyup', function (e) {
        clearTimeout(timerMap);
        timerMap = setTimeout(function () {
            $.get('https://nominatim.openstreetmap.org/search?format=json&q='+$('#input_address').val(), function(data){
                if(data.length && typeof data[0]) {
                    edit_map_marker.setLatLng([data[0].lat, data[0].lon]).update(); 
                    edit_map.panTo(new L.LatLng(data[0].lat, data[0].lon));
                    $('#input_gps').val(data[0].lat+', '+data[0].lon);
                } else {
                     ShowStatus.show('<?php echo_js(__('Address not found', 'sw_win')); ?>');
                    return;
                }
            });
        }, 1000);
        })
    <?php else:?>
    
    $('#input_address').keyup(function (e) {
        clearTimeout(timerMap);
        timerMap = setTimeout(function () {
            google.maps.event.trigger(map, 'resize');
            codeAddress();
        }, 2000);
        
    });
    
    initMap();
    timerMap = setTimeout(function () {
        google.maps.event.trigger(map, 'resize');
        
        var myLatlng = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};
        map.setCenter(myLatlng);
    }, 2000);
    
    <?php endif;?>
    
    loadjQueryUpload();
    
    loadZebra();
    
    $('.add_button').on( "click", function() {

    //var agent_id = $("input[name='agent_id']").val();
    var agent_id = $(this).parent().find('input[type=text]').get(1).value;

    if(agent_id != '')
    {
        //var exists = 0 != $('#user_id option[value='+agent_id+']').length;
        var exists = 0 != $(this).parent().parent().find('select:first').find('option[value='+agent_id+']').length;
        //var agent_name = $('.winter_dropdown button:first').html();
        var agent_name =  $(this).parent().find('div button:first').html();
        
        if(!exists)
        {
            //$("#user_id").append('<option value="'+agent_id+'" selected>'+agent_name+'</option>');
            $(this).parent().parent().find('select:first').append('<option value="'+agent_id+'" selected>'+agent_name+'</option>');
        }
        else
        {
            ShowStatus.show('<?php echo_js(__('Already on list', 'sw_win')); ?>');
        }
    }

    //console.log( agent_id );
    });

    $('.rem_button').on( "click", function() {
    //$('#user_id option:selected:last').remove();
    $(this).parent().parent().find('option:selected:last').remove();
    });

    function primary_check()
    {
        if($('#is_primary').is(":checked"))
        {
            $('div.group_related_id').hide();
        }
        else
        {
            $('div.group_related_id').show();
        }
    }
    
    function codeAddress() {
        var address = document.getElementById('input_address').value;
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == 'OK') {
            map.setCenter(results[0].geometry.location);
            marker.setPosition(results[0].geometry.location);
            
            document.getElementById("input_gps").value = results[0].geometry.location.lat()+', '+results[0].geometry.location.lng();
          } else {
            ShowStatus.show('<?php echo_js(__('Address not found', 'sw_win'));?>');
            //alert('<?php echo_js(__('Address not found', 'sw_win')); ?>');
          }
        });
    }

     function deleteMarkers() {
        //Loop through all the markers and remove
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    };
    
    function loadZebra()
    {
        $('.files a.iedit').click(function (event) {
            new $.Zebra_Dialog('', {
                source: {'iframe': {
                    'src':  '<?php echo admin_url( 'admin-ajax.php' ); ?>?action=ci_action&page=files_edit&rel='+$(this).attr('rel'),
                    'height': 700
                }},
                width: 950,
                title:  '<?php echo_js(__('Edit image', 'sw_win')); ?>',
                type: false,
                buttons: false
            });
            return false;
        });
    }
    
    function loadjQueryUpload()
    {
        
        $('.zoom-button').bind("click touchstart", function()
        {
            var myLinks = new Array();
            var current = $(this).attr('href');
            var curIndex = 0;
            
            $('.files-list .zoom-button').each(function (i) {
                var img_href = $(this).attr('href');
                myLinks[i] = img_href;
                if(current == img_href)
                    curIndex = i;
            });

            options = {index: curIndex}

            blueimp.Gallery(myLinks, options);
            
            return false;
        });
        
        $('form.fileupload').each(function () {
            $(this).fileupload({
            <?php if(config_item('app_type') != 'demo'):?>
            autoUpload: true,
            <?php endif;?>
            dataType: 'json',
            // The maximum width of the preview images:
            previewMaxWidth: 160,
            // The maximum height of the preview images:
            previewMaxHeight: 120,
            formData: {
                action: 'ci_action',
                page: 'files_listing',
                repository_id: '<?php echo $repository_id; ?>'
            },
            uploadTemplateId: null,
            downloadTemplateId: null,
            uploadTemplate: function (o) {
                var rows = $();
                //return rows;
                $.each(o.files, function (index, file) {
                    /*
                    var row = $('<li class="img-rounded template-upload">' +
                        '<div class="preview"><span class="fade"></span></div>' +
                        '<div class="filename"><code>'+file.name+'</code></div>'+
                        '<div class="options-container">' +
                        '<span class="cancel"><button  class="btn btn-xs btn-warning"><i class="icon-ban-circle icon-white"></i></button></span></div>' +
                        (file.error ? '<div class="error"></div>' :
                                '<div class="progress">' +
                                    '<div class="bar" style="width:0%;"></div></div></div>'
                        )+'</li>');
                    row.find('.name').text(file.name);
                    row.find('.size').text(o.formatFileSize(file.size));
                    */
                    
                    var row = $('<div> </div>');
                    rows = rows.add(row);

                });
                return rows;
            },
            downloadTemplate: function (o) {
                var rows = $();
                $.each(o.files, function (index, file) {
                    var added=false;
                    
                    if (file.error) {
                        ShowStatus.show(file.error);

                    } else {
                        added=true;
                        
                        var row = $('<li class="img-rounded template-download fade">' +
                            '<div class="preview"><span class="fade"></span></div>' +
                            '<div class="filename"><code>'+file.short_name+'</code></div>'+
                            '<div class="options-container">' +
                            (file.zoom_enabled?
                                '<a data-gallery="gallery" class="zoom-button btn btn-xs btn-success" download="'+file.name+'"><i class="glyphicon glyphicon-search"></i></a>'
                                : '<a target="_blank" class="btn btn-xs btn-success" download="'+file.name+'"><i class="glyphicon glyphicon-search"></i></a>') +
                            ' <span class="delete"><button class="btn btn-xs btn-danger" data-type="'+file.delete_type+'" data-url="'+file.delete_url+'"><i class="glyphicon glyphicon-trash"></i></button>' +
                            ' <input type="checkbox" value="1" name="delete"></span>' +
                            '</div>' +
                            (file.error ? '<div class="error"></div>' : '')+'</li>');
                        
                        
                        row.find('.name a').text(file.name);
                        if (file.thumbnail_url) {
                            row.find('.preview').html('<img class="img-rounded" alt="'+file.name+'" data-src="'+file.thumbnail_url+'" src="'+file.thumbnail_url+'">');  
                        }
                        row.find('a').prop('href', file.url);
                        row.find('a').prop('title', file.name);
                        row.find('.delete button')
                            .attr('data-type', file.delete_type)
                            .attr('data-url', file.delete_url);
                    }
                    
                    if(added)
                        rows = rows.add(row);
                });
                
                return rows;
            },
            destroyed: function (e, data) {
                <?php if(config_item('app_type') != 'demo'):?>
                if(data.success)
                {

                }
                else
                {
                    ShowStatus.show('<?php echo_js(__('Unsuccessful, possible permission problems or file not exists', 'sw_win')); ?>');
                }
                <?php else: ?>
                if(data.success)
                {
                    
                }
                else
                {
                    ShowStatus.show('<?php echo_js(__('Disabled in demo', 'sw_win')); ?>');
                }
                <?php endif;?>
                return false;
            },
            <?php if(config_item('app_type') == 'demo'):?>
            added: function (e, data) {
                
                ShowStatus.show('<?php echo_js(__('Disabled in demo', 'sw_win')); ?>');
                return false;
            },
            <?php endif;?>
            finished: function (e, data) {
                $('.zoom-button').unbind('click touchstart');
                $('.zoom-button').bind("click touchstart", function()
                {
                    var myLinks = new Array();
                    var current = $(this).attr('href');
                    var curIndex = 0;
                    
                    $('.files-list .zoom-button').each(function (i) {
                        var img_href = $(this).attr('href');
                        myLinks[i] = img_href;
                        if(current == img_href)
                            curIndex = i;
                    });
            
                    options = {index: curIndex}
            
                    blueimp.Gallery(myLinks, options);
                    
                    return false;
                });
            },
            dropZone: $(this)
        });
        });       
        
        $("ul.files").each(function (i) {
            $(this).sortable({
                update: saveFilesOrder
            });
            $(this).disableSelection();
        });
    
    }
    
    function filesOrderToArray(container)
    {
        var data = {};

        container.find('li').each(function (i) {
            var filename = $(this).find('.options-container a:first').attr('download');
            data[i+1] = filename;
        });
        
        return data;
    }
    
    function saveFilesOrder( event, ui )
    {
        var filesOrder = filesOrderToArray($(this));
        var repId = $(this).parent().parent().parent().attr('id').substring(13);
        var modelName = $(this).parent().parent().parent().attr('rel');

        //$.fn.startLoading();
		$.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', 
        {  action: 'ci_action', page: 'files_order',
           'repository_id': repId, 'order': filesOrder }, 
        function(data){
            //$.fn.endLoading();
		}, "json");
    }

});

function initMap() {
    
    var myLatlng = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};
    
    geocoder = new google.maps.Geocoder();
    
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 4,
      center: myLatlng
    });
    
    marker = new google.maps.Marker({
      draggable: true,
      position: myLatlng,
      map: map,
      title: '<?php echo_js(__('Listing location', 'sw_win')); ?>'
    });
    
    google.maps.event.addListener(marker, 'dragend', function(event) {
        document.getElementById("input_gps").value = event.latLng.lat()+', '+event.latLng.lng();
    });
    
    google.maps.event.addListener(map, 'click', function(event) {
        document.getElementById("input_gps").value = event.latLng.lat()+', '+event.latLng.lng();
        marker.setPosition(event.latLng);
    });

}

</script>

<style>

    #map {
        height: 300px;
    }
    
    .bootstrap-wrapper .alert.non-translatable {
        padding: 7px 12px;
        margin-bottom: 0px;
    }

    .blueimp-gallery {
        z-index: 99999;
    }
    
    .agent_add
    {
        display: block;
        padding:5px 0px 0px 0px;
    }
        
    @media (min-width: 768px){
        .col-sm-4.multi_columns > .form-group .control-label {
            width: 50%;
        }

        .col-sm-4.multi_columns > .form-group div {
            width: 40%;
        }

        .col-sm-4.multi_columns > .form-group div.col-sm-3 {
            width: 10%;
        }

        .col-sm-6.multi_columns > .form-group .control-label {
            width: 33.33333333%;
        }

        .col-sm-6.multi_columns > .form-group div {
            width: 40%;
        }

        .col-sm-6.multi_columns > .form-group div.col-sm-3 {
            width: 10%;
        }
    }
</style>

