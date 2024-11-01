<?php

    $def_value = '';
    $def_int = '';
    $def_yea = '';

    // Fetch default values
    
    $CI =& get_instance();

    if(isset($this->data['listing']))
    {
        $obj = json_decode($this->data['listing']->json_object);
        
        if(isset($obj->{'field_36'}))
            if(is_numeric($obj->{'field_36'}))
            {
                $def_value = $obj->{'field_36'};
                $def_value = str_replace(',', '', $def_value);
                $def_value = number_format($def_value, 2, '.', '');
            }
            
//        $def_int = 5;
//        $def_yea = 10;
    }

?>

<div class="">
    <div class="widget-content">
        <form method="get" action="#" id="mortgage_calculator">
            <div class="form-group">
                <input id="mortgage_balance" type="text" class="form-control" value="<?php echo $def_value; ?>" placeholder="<?php echo __('House price', 'sw_win'); ?>*">
            </div><!-- /.form-group -->

            <div class="form-group">
                <input id="mortgage_interest" type="text" class="form-control" value="<?php echo $def_int; ?>" placeholder="<?php echo __('Interest', 'sw_win'); ?>*">
            </div><!-- /.form-group -->

            <div class="form-group">
                <input id="mortgage_downpayment"  type="text" class="form-control" placeholder="<?php echo __('Down payment', 'sw_win'); ?>">
            </div><!-- /.form-group -->

            <div class="form-group">
                <input id="mortgage_years" type="text" class="form-control" value="<?php echo $def_yea; ?>" placeholder="<?php echo __('Years', 'sw_win'); ?>*">
            </div><!-- /.form-group -->

            <div class="form-group">
                <label><?php echo __('Monthly Repayments', 'sw_win'); ?></label>
                <p id="results_monthly" class="form-control-static center"><?php _che($options_prefix_36); ?> 0 <?php _che($options_suffix_36); ?></p>
            </div><!-- /.form-group -->

            <div class="form-group">
                <label><?php echo __('Weekly Repayments', 'sw_win'); ?></label>
                <p id="results_weekly" class="form-control-static center"><?php _che($options_prefix_36); ?> 0 <?php _che($options_suffix_36); ?></p>
            </div><!-- /.form-group -->
            
            <div class="form-group  col-sm-12" style="">
            <button type="submit" class=" btn btn-primary btn-inversed btn-block"><?php echo __('Calculate', 'sw_win'); ?></button>
            </div>
            
        </form>
    </div><!-- /.widget-content -->
</div><!-- /.widget -->  

<style>

form#mortgage_calculator p.form-control-static{
    border:1px solid black;
    padding: 6px 6px;
    margin:0px;
}


</style>

<script>

jQuery(document).ready(function($) {
    
	$('#mortgage_calculator').on('submit', function(e) {
		e.preventDefault();
		var $params = {
			balance: $('#mortgage_balance').val() - $('#mortgage_downpayment').val(),
			rate: $('#mortgage_interest').val(),
			term: $('#mortgage_years').val(),
			period: 12
		};
		
		$(this).calculateMortgage({
			params: $params,
            results_weekly: $('#results_weekly'),
            results_monthly: $('#results_monthly')
		})
	
	});	
    
	$.fn.calculateMortgage = function(options) {
		var defaults = {
			currency_prefix: '<?php _che($options_prefix_36); ?>',
            currency_suffix: '<?php _che($options_suffix_36); ?>',
			params: {}
		};
		options = $.extend(defaults, options);
		
		var calculate = function(params) {
			params = $.extend({
				balance: 0,
				rate: 0,
				term: 0,
				period: 0,
                results_weekly: null,
                results_monthly: null
			}, params);
			
			var N = params.term * params.period;
			var I = (params.rate / 100) / params.period;
			var v = Math.pow((1 + I), N);
			var t = (I * v) / (v - 1);
			var result = params.balance * t;
			
			return result;
		};
		
		return this.each(function() {
			var $element = $(this);
			var $result_custom = calculate(options.params);
            var $result_month = calculate($.extend(options.params, {period: 12}));
            var $result_week = calculate($.extend(options.params, {period: 52}));
            
            $element.find('div.alert').remove();
            
			var output_week = options.currency_prefix + ' ' + $result_week.toFixed(2) + ' ' + options.currency_suffix;
            if(mortgage_is_numeric($result_week.toFixed(2)))
            {
                options.results_weekly.html(output_week);
            }
            else
            {
                $element.prepend('<div class="alert alert-danger" role="alert"><?php echo __('Please fill empty fields', 'sw_win'); ?></div>');
            }
			     
		
			var output_month = options.currency_prefix + ' ' + $result_month.toFixed(2) + ' ' + options.currency_suffix;
			if(mortgage_is_numeric($result_month.toFixed(2)))
                options.results_monthly.html(output_month);
            
		});

	};

});


function mortgage_is_numeric(mixed_var) {
  var whitespace =
    " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
  return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
    1)) && mixed_var !== '' && !isNaN(mixed_var);
}

</script>







