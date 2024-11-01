<?php

if(empty($button_title))
{
    $button_title = __('Save', 'sw_win');
}


?>

<div class="col-xs-12 col-sm-12">
	<div class="form-group">
        <button type="submit" class="btn btn-primary"><?php echo $button_title; ?></button>
    </div>
</div>

<br style="clear: both;" />
