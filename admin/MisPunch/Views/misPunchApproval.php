
<div class="row">
	<div class="col-lg-12">
		<div class="card bg-sky">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2">Filter</h3>
			</div>
			<div class="card-body">
				<form id="form-filter" method="GET" class="form-horizontal">
					<div class="form-layout">
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group mg-b-10-force">
									<label class="form-control-label">Branch: <span class="tx-danger">*</span></label>
									<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(''=>'Select Branch')), set_value('branch_id', $branch_id),"id='branch_id' class='form-control select2'"); ?>
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
									<label for="inputEmail3" class="control-label">Status</label>
									<?php echo form_dropdown('status', array(0=>'Pending',1=>'Approved',2=>'Rejected'),set_value('status', ''),"id='status' class='form-control select2'"); ?>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-layout-footer">
									<button type="submit"  class="btn btn-primary">Search</button>
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

				</div>
			</div>
			<div class="card-body">

				<form action="" method="post" enctype="multipart/form-data" id="form-datatable">
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
										<th>Emp Code</th>
										<th>Employee</th>
										<th>Punch Date</th>
										<th>CLM IN</th>
										<th>CLM OUT</th>
										<th>Savior IN</th>
										<th>Savior OUT</th>
										<th>Status</th>
										<th class="no-sort">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if($mispunches){
										foreach($mispunches as $mispunch){?>
										<tr>
											<td class="text-center">
												<div class="checkbox checkbox-primary checkbox-single">
													<input type="checkbox" name="selected[]" value="" />
													<label></label>
												</div>
											</td>
											<td>
												<?php echo $mispunch['card_no']; ?>
											</td>
											<td>
												<?php echo $mispunch['employee_name']; ?>
											</td>
											<td>
												<?php echo $mispunch['punch_date']; ?>
											</td>
											<td>
												<?php echo $mispunch['clm_in']; ?>
											</td>
											<td>
												<?php echo $mispunch['clm_out']; ?>
											</td>
											<td>
												<?php echo $mispunch['savior_in']; ?>
											</td>
											<td>
												<?php echo $mispunch['savior_out']; ?>
											</td>
											<td>
												<?php if($mispunch['is_request'] == 0){?>
												<span class="badge badge-warning">Pending</span>
												<?}elseif($mispunch['is_request'] == 1){?>
												<span class="badge badge-success">Approved</span>
												<?}elseif($mispunch['is_request'] == 2){?>
												<span class="badge badge-danger">Rejected</span>
												<?}?>
											</td>
											<td>
											<a href="<?php echo admin_url('mispunch/approve/'.$mispunch['id']); ?>" class="btn btn-danger btn-sm ajaxaction">Action</a>
											</td>
										</tr>
									<?}}?>
								</tbody>
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

</script>
<?php js_end(); ?>