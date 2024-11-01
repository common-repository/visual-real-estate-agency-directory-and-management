
<h1><?php echo __('Categories','sw_win'); ?> <a href="<?php echo admin_url("admin.php?page=treefield_categories&function=addvalue&field_id=".$field_id); ?>" class="page-title-action"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo __('Add New','sw_win')?></a></h1>

<div class="bootstrap-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Treefield table','sw_win'); ?></h3>
        </div>
        <div class="panel-body">
        
<!-- Data Table -->
<div class="box box-without-bottom-padding">
	<div class="tableWrap dataTable table-responsive js-select">
		<table id="din-table" class="table table-striped" style="width: 100%;">
			<thead>
				<tr>
					<th data-priority="1">#</th>
					<th data-priority="2" style="width: 100% !important;"><?php echo __('Value', 'sw_win'); ?></th>
					<th data-priority="3"><?php echo __('Parent', 'sw_win'); ?></th>
                    <th><?php echo __('Level', 'sw_win'); ?></th>
                    <th data-priority="4"><?php echo __('Edit', 'sw_win'); ?></th>
                    <th><?php echo __('Delete', 'sw_win'); ?></th>
				</tr>
			</thead>
			<tbody>
<?php if (sw_count($treefield_table)): foreach ($treefield_table as $item): ?>
                <tr>
                    <td><?php echo $item->idtreefield; ?></td>
                    <td><?php echo $item->visual . $item->value; ?></td>
                    <td><?php echo $item->parent_id; ?></td>
                    <td><?php echo $item->level; ?></td>
                    <td><?php echo btn_edit(admin_url("admin.php?page=treefield_categories&function=addvalue&id=".$item->idtreefield)) ?></td>
                    <td><?php echo btn_delete(admin_url("admin.php?page=treefield_categories&function=remvalue&id=".$item->idtreefield)); ?></td>
                </tr>
<?php endforeach; ?>
<?php else: ?>
                <tr>
                    <td colspan="20"><?php echo __('We could not find any','sw_win'); ?></td>
                </tr>
<?php endif; ?>           
			</tbody>
		</table>
	</div>
</div>

        </div>
    </div>
</div>

<script>

// Generate table
jQuery(document).ready(function($) {

});


</script>

<style>


</style>
