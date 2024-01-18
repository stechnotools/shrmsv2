<div class="card">
	<div class="card-header py-2 text-white">
		<h3 class="card-title float-left my-2"><?php echo $heading_title; ?></h3>
		<div class="panel-tools float-right">
			<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
			<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger btn-sm" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-datatable').submit() : false;"><i class="fa fa-trash"></i></button>
		</div>
	</div>
	<div class="card-body">
		<!-- DataTables functionality is initialized with .js-dataTable-full class in js/country/be_tables_datatables.min.js which was auto compiled from _es6/country/be_tables_datatables.js -->
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-country">        		
		<table id="datatable" class="table table-bordered table-striped table-vcenter js-dataTable-full">
			<thead>
				<tr>
					<th style="width: 1px;" class="text-center no-sort">
						<div class="checkbox checkbox-primary checkbox-single">
							<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
							<label></label>
						</div>
					</th>
					<th>Country Name</th>
                    <th>Country Code</th>
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
	table=$('#datatable').DataTable({
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ targets: 'no-sort', orderable: false }
		],
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".datatable_error").html("");
				$("#datatable").append('<tbody class="datatable_error"><tr><th colspan="5">No data found.</th></tr></tbody>');
				$("#datatable_processing").css("display","none");
				
			},
			dataType:'json'
		},
	});
});
function delete_country(title,id){

	gbox.show({
		content: '<h2>Delete Manager</h2>Are you sure you want to delete this Manager?<br><b>'+title,
		buttons: {
			'Yes': function() {
				$.post('<?php echo admin_url('members.delete');?>',{user_id:id}, function(data) {
					if (data.success) {
						gbox.hide();
						$('#member_list').DataTable().ajax.reload();
					} else {
						gbox.show({
							content: 'Failed to delete this Manager.'
						});
					}
				});
			},
			'No': gbox.hide
		}
	});
	return false;
}
//--></script>
<?php js_end(); ?>