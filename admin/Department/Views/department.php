<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $heading_title; ?></h3>
				<div class="panel-tools float-right">
					<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
					<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger btn-sm" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-department').submit() : false;"><i class="fa fa-trash"></i></button>
				</div>
			</div>
			<div class="card-body">
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-department">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<table id="department_list" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
									<tr>
										<th style="width: 1px;" class="text-center no-sort">
											<div class="checkbox checkbox-primary checkbox-single">
												<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
												<label></label>
											</div>
										</th>
										<th>Department Code</th>
										<th>Department Name</th>
										<th>HOD</th>
										<th>Email</th>
										<th class="no-sort">Action</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php js_start(); ?>
<script type="text/javascript"><!--
$(function(){
	$('#department_list').DataTable({
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ targets: 'no-sort', orderable: false },
			{ targets: 'no-visible', visible: false },
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".department_list_error").html("");
				$("#department_list").append('<tbody class="department_list_error"><tr><th colspan="3">No data found.</th></tr></tbody>');
				$("#department_list_processing").css("display","none");

			},
			dataType:'json'
		},
	});
});
//--></script>
<?php js_end(); ?>