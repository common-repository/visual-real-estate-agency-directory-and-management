<?php if( sw_user_in_role('AGENT') ||
          sw_user_in_role('OWNER') ||
          sw_user_in_role('AGENCY')||
          sw_user_in_role('administrator')): ?>
<h3><?php echo __('Recently Added', 'sw_win'); ?>, <a href="<?php 
    
    $url = '#no-permissions';
    
    if( sw_user_in_role('AGENT') ||
        sw_user_in_role('OWNER') ||
        sw_user_in_role('AGENCY'))
    {
        $url = admin_url("admin.php?page=ownlisting_addlisting");
    }
    else if(sw_user_in_role('administrator'))
    {
        $url = admin_url("admin.php?page=listing_addlisting");
    }
    
    echo $url;
    
    ?>" aria-label="<?php echo __('Add listing', 'sw_win'); ?>"><?php echo __('Add listing', 'sw_win'); ?></a></h3>

<?php if(sw_count($listings) == 0): ?>
<div class="bootstrap-wrapper">
    <div class="alert alert-warning" role="alert"><?php echo __('You don\'t have any listings', 'sw_win').', <a href="'.$url.'">'.__('Add listing', 'sw_win').'</a>'; ?></div>
</div>
<?php else: ?>
<ul>
<?php foreach($listings as $key=>$listing): ?>
    <li>
    <span><?php echo '#'._field($listing, 'idlisting').', '._field($listing, 'address').', '._field($listing, 10); ?></span>, 
    <a href="<?php 
    
    $url = '#no-permissions';
    
    if( sw_user_in_role('AGENT') ||
        sw_user_in_role('OWNER') ||
        sw_user_in_role('AGENCY'))
    {
        $url = admin_url("admin.php?page=ownlisting_addlisting&id=".$listing->idlisting);
    }
    else if(sw_user_in_role('administrator'))
    {
        $url = admin_url("admin.php?page=listing_addlisting&id=".$listing->idlisting);
    }
    
    echo $url;
    
    ?>" aria-label="<?php echo __('Edit', 'sw_win'); ?>"><?php echo __('Edit', 'sw_win'); ?></a>
    <a href="<?php echo listing_url($listing); ?>"><?php echo __('Preview', 'sw_win'); ?></a>
    </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<a href="<?php 
    
    $url = '#no-permissions';
    
    if( sw_user_in_role('AGENT') ||
        sw_user_in_role('OWNER') ||
        sw_user_in_role('AGENCY'))
    {
        $url = admin_url("admin.php?page=ownlisting_manage");
    }
    else if(sw_user_in_role('administrator'))
    {
        $url = admin_url("admin.php?page=listing_manage");
    }
    
    echo $url;
    
    ?>" aria-label="<?php echo __('Manage listings', 'sw_win'); ?>"><?php echo __('Manage listings', 'sw_win'); ?></a>

<style>

</style>

<?php else: ?>
<div class="bootstrap-wrapper">
    <div class="alert alert-warning" role="alert"><?php echo __('Listing features are not available for your account type', 'sw_win'); ?></div>
</div>
<?php endif; ?>

