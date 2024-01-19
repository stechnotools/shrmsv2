
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
									<?php echo form_dropdown('user_id', array(),set_value('user_id', ''),"id='user_id' class='form-control select2' multiple"); ?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group mg-b-10-force">
									<label for="inputEmail3" class="control-label">Date Range</label>
									<div id="reportrange" class="form-control" style="cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
										<i class="fa fa-calendar"></i>&nbsp;
										<span></span> <i class="fa fa-caret-down"></i>
									</div>
									<input type="hidden" id="selectedDateRange" name="selectedDateRange">
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
					<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
					<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger btn-sm" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-datatable').submit() : false;"><i class="fa fa-trash"></i></button>
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
										<th>Punch Date</th>
										<th>Branch</th>
										<th>Paycode</th>
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
	$('#datatable').DataTable({
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
				$(".datatable_error").html("");
				$("#datatable").append('<tbody class="datatable_error"><tr><th colspan="3">No data found.</th></tr></tbody>');
				$("#datatable_processing").css("display","none");
				
			},
			dataType:'json'
		},
	});

});
//--></script>
<script type="text/javascript"><!--
$(function() {

var start = moment().subtract(29, 'days');
var end = moment();

function cb(start, end) {
	$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	$('#selectedDateRange').val(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
     
}

$('#reportrange').daterangepicker({
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
</script>
<?php js_end(); ?>