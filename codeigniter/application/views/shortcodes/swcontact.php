<?php _form_messages(__('Message sent successfully', 'sw_win'), NULL, $widget_id); ?>

<form id="sw_contactform_<?php echo $widget_id?>" method="post" action="#sw_contactform_<?php echo $widget_id?>" class="box">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo __('Full name', 'sw_win'); ?></label>
                <input class="form-control" id="fullname_<?php echo $widget_id?>" name="fullname" type="text" value="<?php echo _fv('form_widget', 'fullname'); ?>" placeholder="<?php echo __('Full name', 'sw_win'); ?>" />
            </div><!-- /.form-group -->
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo __('Your email', 'sw_win'); ?></label>
                <input class="form-control" id="email_<?php echo $widget_id?>" name="email" type="text" value="<?php echo _fv('form_widget', 'email'); ?>" placeholder="<?php echo __('Your email', 'sw_win'); ?>" />
            </div><!-- /.form-group -->
        </div>
    </div><!-- /.row -->
    
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo __('Phone number', 'sw_win'); ?></label>
                <input class="form-control" id="phone_<?php echo $widget_id?>" name="phone" type="text" value="<?php echo _fv('form_widget', 'phone'); ?>" placeholder="<?php echo __('Phone number', 'sw_win'); ?>" />
            </div><!-- /.form-group -->
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo __('Subject', 'sw_win'); ?></label>
                <input class="form-control" id="subject_<?php echo $widget_id?>" name="subject" type="text" value="<?php echo _fv('form_widget', 'subject'); ?>" placeholder="<?php echo __('Subject', 'sw_win'); ?>" />
            </div><!-- /.form-group -->
        </div>

    </div><!-- /.row -->

    <div class="form-group">
        <label><?php echo __('Message', 'sw_win'); ?></label>
        <textarea id="message_<?php echo $widget_id?>" name="message" rows="4" class="form-control" type="text"><?php echo _fv('form_widget', 'message'); ?></textarea>
    </div><!-- /.form-group -->
    
    <input class="hidden" id="widget_id" name="widget_id" type="text" value="<?php echo $widget_id?>" />

    <?php echo _recaptcha(); ?>

    <div class="form-group">
        <input type="submit" value="<?php echo __('Send', 'sw_win'); ?>" class="btn btn-primary btn-inversed">
    </div><!-- /.form-group -->
</form>