
<h1><?php echo __('Listing manage','sw_win'); ?> <a href="<?php menu_page_url( 'listing_addlisting', true ); ?>" class="page-title-action"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo __('Add New','sw_win')?></a></h1>

<div class="bootstrap-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Dynamic table','sw_win'); ?></h3>
        </div>
        <div class="panel-body">
        
<!-- Data Table -->
<div class="box box-without-bottom-padding">
	<div class="tableWrap dataTable table-responsive js-select">
		<table id="din-table" class="table table-striped" style="width: 100%;">
			<thead>
				<tr>
					<th data-priority="1">#</th>
					<th data-priority="4"><?php echo __('Address', 'sw_win'); ?></th>
					<th data-priority="2" style="width: 100% !important;"><?php echo __('Listing', 'sw_win'); ?></th>
                    <th><?php echo _field_name(4); ?></th>
                    <?php if(sw_settings('show_categories')): ?>
                    <th><?php echo __('Category', 'sw_win'); ?></th>
                    <?php else: ?>
					<th><?php echo _field_name(2); ?></th>
                    <?php endif; ?>
                    <th data-priority="3"><?php echo __('Edit', 'sw_win'); ?></th>
                    <th><?php echo __('Delete', 'sw_win'); ?></th>
				</tr>
			</thead>
			<tbody>

			</tbody>
			<tfoot>
				<tr>
					<th><input type="text" placeholder="#" /></th>
					<th><input type="text" placeholder="<?php echo __('Address', 'sw_win'); ?>" /></th>
                    <th><input type="text" placeholder="<?php echo __('Listing', 'sw_win'); ?>" /></th>
					<th>
                    <?php
                    
                        $field = $this->field_m->get_field_data(4, sw_current_language_id());

                        $values = array();
                        if(isset($field->values))
                            $values = explode(',',$field->values);

                        $values = array_combine($values, $values);
                        $values[''] = _field_name(4);

                        echo form_dropdown('', $values, '', 'class="basic-select"'); 
                    
                    ?>
                    </th>
					<th>
                    <?php 
                        if(sw_settings('show_categories'))
                        {
                            $values = $this->treefield_m->get_treefield(sw_current_language_id(),1,NULL,__('Category', 'sw_win'));
                            echo form_dropdown('', $values, '', 'class="basic-select"'); 
                        }
                        else
                        {
                            $field = $this->field_m->get_field_data(2, sw_current_language_id());
                            
                            if(is_object($field))
                            {
                                $values = explode(',',$field->values);
                                $values = array_combine($values, $values);
                                $values[''] = _field_name(2);
                                
                                echo form_dropdown('', $values, '', 'class="basic-select"'); 
                            }
                        }
                    ?>
                    </th>
                    <th></th>
                    <th></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

        </div>
    </div>
</div>

<?php

wp_enqueue_script( 'datatables' );
wp_enqueue_script( 'dataTables-responsive' );

?>
<script>

// Generate table
jQuery(document).ready(function($) {
	if ($('#din-table').length) {

		var table = $('#din-table').DataTable({
            "ordering": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,

 
            'ajax': {
                "url": ajaxurl,
                "type": "POST",
                "data": function ( d ) {
                    return $.extend( {}, d, {
                        "page": 'listing_datatable',
                        "action": 'ci_action'
                    } );
                }
            },
            "fnDrawCallback": function (oSettings){
                $('a.delete_button').click(function(){
                    
                    if(confirm('<?php echo_js(__('Are you sure?', 'sw_win')); ?>'))
                    {
                       // ajax to remove row
                        $.post($(this).attr('href'), function( [] ) {
                            table.row($(this).parent()).remove().draw( false );
                        });
                    }

                   return false;
                });
            },
            'columns': [
                { data: "idlisting" },
                { data: "address"   },
                { data: "field_10"  },
                { data: "field_4"   },
                <?php if(sw_settings('show_categories')): ?>
                { data: "category_id"},
                <?php else: ?>
                { data: "field_2"   },
                <?php endif; ?>
                { data: "edit"      },
                { data: "delete"    }
            ],
//            columnDefs: [
//                { responsivePriority: 1, targets: 0 },
//                { responsivePriority: 2, targets: -2 }
//            ],
            responsive: {
                details: {
                    type: 'column',
                    target: 2
                }
            },
            columnDefs: [ {
                className: 'control',
                orderable: false,
                targets:   2
            } ],
			'oLanguage': {
				'oPaginate': {
					'sPrevious': '<i class="fa fa-angle-left"></i>',
					'sNext': '<i class="fa fa-angle-right"></i>'
				},
                'sSearch': "<?php echo_js(__('Search', 'sw_win')); ?>",
                "sLengthMenu": "<?php echo_js(__('Show _MENU_ entries', 'sw_win')); ?>",
                "sInfoEmpty": "<?php echo_js(__('Showing 0 to 0 of 0 entries', 'sw_win')); ?>",
                "sInfo": "<?php echo_js( __('Showing _START_ to _END_ of _TOTAL_ entries', 'sw_win')); ?>",
                "sEmptyTable": "<?php echo_js(__('No data available in table', 'sw_win')); ?>",
			},
			'dom': "<'row'<'col-sm-7 col-md-5'f><'col-sm-5 col-md-6'l>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>"
		});
        
//		$('.js-select select:not(.basic-select)').select2({
//			minimumResultsForSearch: Infinity
//		});
        
        // Apply the search
        table.columns().every( function () {
            var that = this;
     
            $( 'input,select', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            } );

        } );
        
	}
});


</script>

<style>

.bootstrap-wrapper #din-table_wrapper .row
{
    margin:0px;
}

.bootstrap-wrapper .dataTable div.dataTables_wrapper label
{
    width:100%;
    padding:10px 0px;
}

.dataTable div.dataTables_wrapper div.dataTables_filter input
{
    display:inline-block;
    width:65%;
    margin: 0 10px;
}

.dataTable div.dataTables_wrapper div.dataTables_length select
{
    display:inline-block;
    width:100px;
    margin: 0 10px;
}

.dataTable td.control
{
    color:#337AB7;
    display:table-cell !important;
    font-weight: bold;
}

.dataTable th.control
{
    display:table-cell !important;
}

</style>