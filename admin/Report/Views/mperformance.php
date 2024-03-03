<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Month Performance Report</h3>
				<div class="panel-tools float-right">
					<button type="submit" class="btn btn-primary" name="clear_filter" value="1" form="form-attendance"><span>Clear</span></button>
					<button type="submit" data-toggle="tooltip" title="" class="btn btn-info" form="form-attendance">Generate</button>
				</div>

			</div>
			<div class="card-body">
				<form action="" method="get" enctype="multipart/form-data" id="form-attendance">
					<div class="row">
						<div class="col-lg-4">
							<div class="form-group">
								<label for="inputEmail3" class="control-label">Date</label>
								<?php echo form_input(array('id'=>'daterange','name'=>'daterange', 'class'=>'form-control daterange','placeholder'=>'From Date','value' => set_value('attendance', $fromdate),'required'=>true)) ?>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label class="control-label" for="input-email">Branch</label>
								<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(''=>'All Branch')), set_value('branch_id', $branch_id),"id='branch_id' class='form-control select2' required"); ?>
							</div>
						</div>
						<!--<div class="col-lg-4">
							<div class="form-group row">
								<label for="inputEmail3" class="control-label">Emp Name/Paycode</label>
								<?php /*echo form_dropdown('user_id[]', option_array_value($employees, 'id', 'employee_name'), set_value('user_id',$user_id),"id='user_id' class='form-control select2' multiple");*/ ?>
							</div>-->
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Month Performance Report</h3>
				<div class="panel-tools float-right">
					<a href="javascript:void(0);" class="btn btn-info" id="exportBtn">Excel Download</a>
				</div>
			</div>
			<div class="card-body">
				<?php if ($mperformance) { ?>
					<table class="table" id="datatable">
						<thead>
							<th>Performance Register from <?= reset($months)['date'] ?> To <?= end($months)['date'] ?></th>
						</thead>
						<tbody>
							<?php foreach ($mperformance as $key => $value) { ?>
								<tr>
									<td>
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>PayCode</th>
													<th>Card No.</th>
													<th>Employee Name</th>
													<!--<th>Department</th>
													<th>Designation</th>-->
													<th>Present</th>
													<th>Absent</th>
													<th>Mis</th>
													<th>Holiday</th>
													<th>Weekly Off</th>
													<th>Leave</th>
													<th>Hours Worked</th>
													<th>Overtime</th>
													<th>OT Amount</th>

												</tr>
											</thead>
											<tbody>
												<tr>
													<td><?php echo $value['details']['paycode']; ?></td>
													<td><?php echo $value['details']['card_no']; ?></td>
													<td><?php echo $value['details']['employee_name']; ?></td>
													<!--<td><?php echo $value['details']['department_name']; ?></td>
													<td><?php echo $value['details']['designation_name']; ?></td>-->
													<td><?php echo $value['details']['total_present']; ?></td>
													<td><?php echo $value['details']['total_absent']; ?></td>
													<td><?php echo $value['details']['total_mis']; ?></td>
													<td><?php echo $value['details']['holiday']; ?></td>
													<td><?php echo $value['details']['weekly_off']; ?></td>
													<td><?php echo $value['details']['leave']; ?></td>
													<td><?php echo $value['details']['worked_hr']; ?></td>
													<td><?php echo $value['details']['overtime']; ?></td>
													<td><?php echo $value['details']['ot_amount']; ?></td>
												</tr>
											</tbody>
										</table>
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Day</th>
													<?php foreach ($months as $column) { ?>
														<th scope="col"><?php echo date("d", strtotime($column['date'])); ?></th>
													<?php } ?>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>In1</td>
													<?php foreach ($months as $column) { ?>
														<td> <?php echo $value['dates'][$column['date']]['startin']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<td>Out1</td>
													<?php foreach ($months as $column) { ?>
														<td> <?php echo $value['dates'][$column['date']]['lunch_out']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<td>In2</td>
													<?php foreach ($months as $column) { ?>
														<td> <?php echo $value['dates'][$column['date']]['lunch_in']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<td>Out2</td>
													<?php foreach ($months as $column) { ?>
														<td> <?php echo $value['dates'][$column['date']]['startout']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<td>Work</td>
													<?php foreach ($months as $column) { ?>
														<td> <?php echo $value['dates'][$column['date']]['work']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<td>Overtime</td>
													<?php foreach ($months as $column) { ?>
														<td> <?php echo $value['dates'][$column['date']]['overtime']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<td>Status</td>
													<?php foreach ($months as $column) { ?>
														<td> <?php echo $value['dates'][$column['date']]['status']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<td>Shift</td>
													<?php foreach ($months as $column) { ?>
														<td> <?php echo $value['dates'][$column['date']]['shift']; ?></td>
													<?php } ?>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							<? } ?>
						</tbody>
					</table>
				<? } else { ?>
					No Data Found
				<? } ?>
			</div>
		</div>
	</div>
</div>
<?php js_start(); ?>
<script type="text/javascript">
	<!--
	$(function() {
		$('#datatable').DataTable({
			"paging": false,
			"ordering": false,
			"info": false,
			"searching": false,
			"fixedHeader": true,
        	"scrollY": "300px", // Adjust as per your requirement
        	"scrollCollapse": true,
			"scrollX": true,
			"dom": 'f<"pull-right"B>ltip',
			"buttons": [
				'csv',
				'excel',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'LEGAL'
				},
				'print'
			]
		});

		$('#exportBtn').click(function() {
			let tableHtml = $('#datatable').prop('outerHTML');

			// AJAX call to send the HTML content to PHP
			$.ajax({
				method: 'POST',
				url: '<?=admin_url('report/attendance/generateexcel')?>',
				data: { html: tableHtml },
				dataType:'json',
				success: function(data) {
					let link = document.createElement('a');
					link.style.display = "none"; // because Firefox sux

					link.hidden = true;
					link.download = data.filename;
					link.href = data.file;
					link.text = "downloading...";

					document.body.appendChild(link);
					link.click();
					link.remove();

				},
				error: function(xhr, status, error) {
				console.error(error);
				}
			});
		});
	});
</script>
<script>
	$(function() {
		//var start = moment().subtract(29, "days");
		//var end = moment();
		var start = moment('<?= $fromdate ?>');
		var end = moment('<?= $todate ?>');
		function cb(start, end) {
			$("#daterange").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
		}
		$('#daterange').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				"Today": [moment(), moment()],
				"Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
				"Last 7 Days": [moment().subtract(6, "days"), moment()],
				"Last 30 Days": [moment().subtract(29, "days"), moment()],
				"This Month": [moment().startOf("month"), moment().endOf("month")],
				"Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
			}
		}, cb);
		cb(start, end);
	});
</script>
<?php js_end(); ?>