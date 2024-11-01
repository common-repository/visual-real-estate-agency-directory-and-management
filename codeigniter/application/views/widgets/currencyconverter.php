
<?php

    $def_value = 100;
    $def_currency = NULL;
    
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
    }
    
    $CI->load->model('field_m');
    $field_data = $CI->field_m->get_field_data(36, sw_current_language_id());
    if(!empty($field_data))
    {
        $prefix = $field_data->prefix;
        $suffix = $field_data->suffix;
        
        foreach($dropdown_currency as $key=>$val)
        {
            if( !empty($prefix) && substr_count($val, $prefix) > 0 || !empty($suffix) && substr_count($val, $suffix) > 0)
            {
                $def_currency = $key;
            }
        }
    }

?>

<div class="">
<div class="widget-content text-center currency_widget">
    <table class="table table-striped">
        <tr>
            <td><input id="ccw_value" class="form-control currency_value" name="currency_value" type="text" value="<?php echo $def_value; ?>" /></td>
            <td>
                <?php
                echo form_dropdown('currency_code', $dropdown_currency, $def_currency, 'id="ccw_code" class="form-control currency-select"');
                ?>
            </td>
        </tr>
        
<?php

$js_array_gen = '';

foreach($conversion_table['code'] as $key=>$row)
{    
    
    $code_full = $key;
    if(!empty($row->currency_symbol))
    {
        $code_full.=" ({$row->currency_symbol})";
    }
    
    $js_array_gen.= '{code:"'.$key.'", id:"'.$row->idcurrency.'", codefull:"'.$code_full.'", rate:'.$row->rate_index.'},'."\n";
    
    echo '
    <tr>
        <td></td>
        <td>'.$code_full.'</td>
    </tr>
    ';
}

if(!empty($js_array_gen))
    $js_array_gen = substr($js_array_gen, 0, -1);
        
?>
    </table>
</div>
</div>

<script>

jQuery(document).ready(function($) {

    $("#ccw_code, #ccw_value").change(function() {
        refresh_cctable();
    });
    
    refresh_cctable();
    
    function refresh_cctable()
    {
        var curr_value = $("#ccw_value").val();
        var curr_code = $("#ccw_code").val();
        var curr_rate = 1;
        
        var cc_array = 
        [
            <?php echo $js_array_gen; ?>
        ];

        $('.currency_widget table tr:not(:first)').remove();
        
        $.each(cc_array, function( index, value_obj ) {
            if(value_obj.id == curr_code)
            {
                curr_rate = value_obj.rate;
            }
        });

        $.each(cc_array, function( index, value_obj ) {
            if(value_obj.id != curr_code)
            {
                $('.currency_widget table').append('<tr><td>'+custom_number_format(curr_value/curr_rate*value_obj.rate)+'</td><td>'+value_obj.codefull+'</td></tr>');
            }
        });
        
    }
    
    function custom_number_format(val)
    {
        return val.toFixed(2);
    }

});

</script>

<style>

.currency_widget table
{
    border:0px;
}

.currency_widget table td
{
    width: 50%;
}

.currency_widget table input,
.currency_widget table select
{
    width: 100%;
    min-width:0px;
    display:inline;
}

.currency_widget .table-striped tr:first-child td {
   border: 0; 
}

.currency_widget .table-striped tr td {
   border: 0 !important; 
}

.currency_widget .currency_value {
    padding-right: 0; 
} 

.currency-select {
    width: 100% !important;
}

.currency_widget .table-striped tr:first-child td:first-child {
    padding-left: 0;
}

.currency_widget .table-striped tr:first-child td:last-child {
    padding-right: 0;
}

</style>