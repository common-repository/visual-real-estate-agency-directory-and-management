<?php if(sw_is_logged_user()): ?>

<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-success" role="alert"><?php echo __('You are already logged in', 'sw_win'); ?>, <a href="<?php echo admin_url(""); ?>"><?php echo __('Open dashboard', 'sw_win'); ?></a></div>
    </div>
</div>

<?php else: ?>

<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-info" role="alert"><?php echo __('Please login or register to add your listings', 'sw_win'); ?></div>
    </div>
</div>
<br />
<div class="row">
    <div class="col-sm-6" id="sw_login">
        <h2><?php echo __('Login', 'sw_win'); ?></h2>
        <form method="post" action="#sw_login" class="box">
        <?php _form_messages(__('Login successfully', 'sw_win'), __('Wrong credentials', 'sw_win'), 'login'); ?>
        <div class="form-group">
            <label><?php echo __('Username or Email', 'sw_win'); ?></label>
            <input class="form-control" id="username" name="username" type="text" value="<?php echo _fv('form_widget', 'username'); ?>" placeholder="<?php echo __('Username', 'sw_win'); ?>" />
        </div><!-- /.form-group -->
        <div class="form-group">
            <label><?php echo __('Password', 'sw_win'); ?></label>
            <input class="form-control" autocomplete="off" id="password" name="password" type="password" value="" placeholder="<?php echo __('Password', 'sw_win'); ?>" />
        </div><!-- /.form-group -->
        
        <input class="hidden" id="widget_id" name="widget_id" type="text" value="login" />
        
        <?php //echo _recaptcha(TRUE); ?>
        <div class="form-group">
            <input type="submit" value="<?php echo __('Login', 'sw_win'); ?>" class="btn btn-primary btn-inversed">
        </div><!-- /.form-group -->
        </form>
        
        <?php if(sw_settings('facebook_login_enabled') == '1' && sw_settings('facebook_app_id') != ''): ?>
        
        <a class="facebook_login_button" href="<?php echo $facebook_login_url; ?>"><img src="<?php echo plugins_url(SW_WIN_SLUG.'' ).'/assets/img/login-facebook.png';?>" alt="<?php echo __('Facebook login', 'sw_win'); ?>" /></a>
        
        <?php endif; ?>
        
    </div>
    <div class="col-sm-6"  id="sw_register">
        <h2><?php echo __('Register', 'sw_win'); ?></h2>
        <form method="post" action="#sw_register" class="box">
        <?php _form_messages(__('Register successfully, you can login now', 'sw_win'), NULL, 'register'); ?>
        <div class="form-group">
            <label><?php echo __('Account type', 'sw_win'); ?></label>
            <?php echo form_dropdown('account_type', config_item('account_types'), _fv('form_widget', 'account_type')); ?>
        </div><!-- /.form-group -->
        <div class="form-group">
            <label><?php echo __('Email', 'sw_win'); ?></label>
            <input class="form-control" id="email" name="email" type="text" value="<?php echo _fv('form_widget', 'email'); ?>" placeholder="<?php echo __('Email', 'sw_win'); ?>" />
        </div><!-- /.form-group -->
        <div class="form-group">
            <label><?php echo __('Username', 'sw_win'); ?></label>
            <input class="form-control" id="username" name="username" type="text" value="<?php echo _fv('form_widget', 'username'); ?>" placeholder="<?php echo __('Username', 'sw_win'); ?>" />
        </div><!-- /.form-group -->
        <div class="form-group">
            <label><?php echo __('Password', 'sw_win'); ?></label>
            <input class="form-control" id="password" name="password" type="password" value="" placeholder="<?php echo __('Password', 'sw_win'); ?>" />
        </div><!-- /.form-group -->
        <div class="form-group">
            <label><?php echo __('Re-enter password', 'sw_win'); ?></label>
            <input class="form-control" id="re_password" name="re_password" type="password" value="" placeholder="<?php echo __('Re-enter password', 'sw_win'); ?>" />
        </div><!-- /.form-group -->
        
        <input class="hidden" id="widget_id" name="widget_id" type="text" value="register" />
        
        <?php echo _recaptcha(TRUE); ?>
    
        <div class="form-group">
            <input type="submit" value="<?php echo __('Register', 'sw_win'); ?>" class="btn btn-primary btn-inversed">
        </div><!-- /.form-group -->
        </form>
    </div>
</div>

<?php endif; ?>





<style>

h1.entry-title
{
    display:none;
}

</style>
