<div class="card">
	<div class="card-header py-2 text-white">
		<h3 class="card-title float-left my-2"><?php echo $heading_title; ?></h3>
		<div class="panel-tools float-right">
			<a href="<?php echo $add; ?>" data-toggle="tooltip" title="Add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
			<button type="button" data-toggle="tooltip" title="Delete" class="btn btn-danger btn-sm" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-datatable').submit() : false;"><i class="fa fa-trash"></i></button>
		</div>
	</div>
	<div class="card-body">
		<!-- DataTables functionality is initialized with .js-dataTable-full class in js/datatable/be_tables_datatables.min.js which was auto compiled from _es6/datatable/be_tables_datatables.js -->
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-datatable">
		<table id="datatable_list" class="table table-bordered table-striped table-vcenter js-dataTable-full">
			<thead>
				<tr>
					<th style="width: 1px;" class="text-center no-sort"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
					<th>Route</th>
					<th>Module</th>
                    <th>Description </th>
					<th>Status</th>
                    <th class="text-right no-sort">Actions</th>
				</tr>
			</thead>
		</table>
		</form>
	</div>
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
$(function(){
	$('#datatable_list').DataTable({
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ targets: 'no-sort', orderable: false }
		],
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".datatable_list_error").html("");
				$("#datatable_list").append('<tbody class="datatable_list_error"><tr><th colspan="5">No data found.</th></tr></tbody>');
				$("#datatable_list_processing").css("display","none");

			},
			dataType:'json'
		},
	});
});
//--></script>
<?php js_end(); ?>