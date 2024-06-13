
<div class="row">
	<div class="col-lg-12">
		<div class="card bg-sky">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2">Filter</h3>
			</div>
			<div class="card-body">
				<form id="form-filter" class="form-horizontal">
					<div class="form-layout">
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group mg-b-10-force">
									<label class="form-control-label">Branch: <span class="tx-danger">*</span></label>
									<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(''=>'Select Branch')), set_value('branch_id', ''),"id='branch_id' class='form-control select2'"); ?>
								</div>
							</div>

							<div class="col-lg-4">
								<div class="form-group mg-b-10-force">
									<label for="inputEmail3" class="control-label">Emp Name/Paycode</label>
									<?php echo form_dropdown('user_id', array(),set_value('user_id', ''),"id='user_id' class='form-control select2'"); ?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group mg-b-10-force">
									<label for="inputEmail3" class="control-label">Date Range</label>
									<?php echo form_input(array('name'=>'daterange','id'=>'daterange', 'class'=>'form-control daterange','placeholder'=>'From','value' => set_value('daterange', ''))) ?>

								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-layout-footer">
									<button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
									<button type="button" id="btn-reset" class="btn btn-default">Reset</button>
								</div>
							</div>
						</div>
					</div><!-- form-layout -->
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $heading_title; ?></h3>
				<div class="panel-tools float-right">
					<?php echo form_open_multipart('admin/employee/upload','class="upload-form"');?>
					<a href="" data-toggle="tooltip"  class="btn btn-primary btn-sm" target="_self" download>Download Sample</a>
					<input type="file" name="bemployee" id="bemployee" style="display:none">
					<button type="button" data-toggle="tooltip" title="Upload Bulk Attendance" class="btn btn-info btn-sm" onclick="thisFileUpload();">Bulk Month Attendance Upload</i></button>

					<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
					<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger btn-sm" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-datatable').submit() : false;"><i class="fa fa-trash"></i></button>
					<?php echo form_close(); ?>
				</div>
			</div>
			<div class="card-body">
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-datatable">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
									<tr>
										<th style="width: 1px;" class="text-center no-sort">
											<div class="checkbox checkbox-primary checkbox-single">
												<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
												<label></label>
											</div>
										</th>
										<th>Branch</th>
										<th>Employee</th>
										<th>Paycode</th>
										<th>Queue Date</th>
										<th>Shift</th>
										<th>In Time</th>
										<th>Out Time</th>
										<th>Worked Hours</th>
										<th>Late Arrival</th>
										<th>Early Departure</th>
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
	table=$('#datatable').DataTable({
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
			type: "post",  // metqueue  , by default get
			data: function ( data ) {
				data.branch_id = $('#branch_id').val();
				data.user_id = $('#user_id').val();
				data.daterange = $('#daterange').val() || getDefaultDateRange();
			},
			beforeSend: function(){
				$('.alert-dismissible, .text-danger').remove();
				$("#datatable_wrapper").LoadingOverlay("show");
			},
			complete: function(){
				$("#datatable_wrapper").LoadingOverlay("hide");
			},
			error: function(){  // error handling
				$(".queue_list_error").html("");
				$("#queue_list").append('<tbody class="queue_list_error"><tr><th colspan="3">No data found.</th></tr></tbody>');
				$("#queue_list_processing").css("display","none");

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
<script type="text/javascript"><!--
$(function() {

var start = moment().subtract(29, 'days');
var end = moment();

function cb(start, end) {
	$(".daterange").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
}

$('.daterange').daterangepicker({
	startDate: start,
	endDate: end,
	ranges: {
	   'Today': [moment(), moment()],
	   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	   'This Month': [moment().startOf('month'), moment().endOf('month')],
	   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	}
}, cb);

cb(start, end);

});
function getDefaultDateRange() {
   // Customize the default date range as needed
   var defaultStartDate = moment().subtract(29, 'days'); // Default start date is 7 days ago
   var defaultEndDate = moment(); // Default end date is today

   return defaultStartDate.format('MMMM D, YYYY') + ' - ' + defaultEndDate.format('MMMM D, YYYY');
}
</script>
<?php js_end(); ?>