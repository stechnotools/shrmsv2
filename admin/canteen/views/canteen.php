<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?php echo $heading_title; ?></h3>
				<div class="panel-tools float-right">
					<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
					<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-book').submit() : false;"><i class="fa fa-trash-o"></i></button>
				</div>
			</div>
			<div class="card-body">
				<form id="form-filter" class="form-horizontal">
					<div class="form-layout">
						<div class="row">
							<div class="col-lg-3">
								<div class="form-group">
									<label class="form-control-label">From Date: <span class="tx-danger">*</span></label>
									<div class="input-group date">
										<input type="text" name="from" value="" placeholder="From Date" id="start_date" class="form-control datepicker" />
										<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
									<label class="form-control-label">To Date: <span class="tx-danger"></span></label>
									<div class="input-group date">
										<input type="text" name="to" value="" placeholder="To Date" id="end_date" class="form-control datepicker" />
										<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group mg-b-10-force">
									<label class="form-control-label">Department: <span class="tx-danger">*</span></label>
									<?php echo form_dropdown('department_id', option_array_value($departments, 'id', 'name',array(''=>'Select Department')), set_value('department_id', $department_id),"id='department_id' class='form-control select2'"); ?>	
								</div>
							</div><!-- col-4 -->
							<div class="col-lg-3">
								<div class="form-group mg-b-10-force">
									<label class="form-control-label">Employee: <span class="tx-danger">*</span></label>
									<?php echo form_dropdown('user_id', option_array_value($employees, 'id', 'employee_name',array(''=>'Select Employee')), set_value('user_id', $user_id),"id='user_id' class='form-control select2'"); ?>
								</div>
							</div><!-- col-4 -->
						</div>
						
							
						<div class="form-layout-footer text-right">
							<button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
							<button type="button" id="btn-reset" class="btn btn-default">Reset</button>
							
						</div><!-- form-layout-footer -->
					</div><!-- form-layout -->
				</form>
				<hr/>
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-canteen">          
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<table id="canteen_list" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
									<tr>
										<th style="width: 1px;" class="text-center no-sort"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
										<th>Order Date</th>
										<th>Employee Code</th>
										<th>Employee Name</th>
										<th>Department</th>
										<th>Breakfast</th>
										<th>Lunch</th>
										<th>Snack</th>
										<th>Dinner</th>
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
	table=$('#canteen_list').DataTable({
		"processing": true,
		"serverSide": true,
		"dom"		: "<'top'<'pull-left'l><'pull-right'B>>rt<'bottom'<'pull-left'i><'pull-right'p>>",
		"buttons": [
            
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            
            'colvis'
        ],
		
		"columnDefs": [
			{ targets: 'no-sort', orderable: false },
			{ targets: 'no-visible', visible: false },
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // method  , by default get
			data: function ( data ) {
				data.from = $('#start_date').val();
				data.to = $('#end_date').val();
				data.user_id = $('#user_id').val();
				data.department_id = $('#department_id').val();
			},
			error: function(){  // error handling
				$(".canteen_list_error").html("");
				$("#canteen_list").append('<tbody class="canteen_list_error"><tr><th colspan="3">No data found.</th></tr></tbody>');
				$("#canteen_list_processing").css("display","none");
				
			},
			dataType:'json'
		},
	});
	
	$('#btn-filter').click(function(){ //button filter event click	
		table.ajax.reload();  //just reload table
	});
	$('#btn-reset').click(function(){ //button reset event click
		$('#form-filter')[0].reset();
		table.ajax.reload();  //just reload table
	});
});
//--></script>
<?php js_end(); ?>