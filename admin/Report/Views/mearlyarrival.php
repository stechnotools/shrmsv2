<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Monthly Early Report</h3>
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
                <h3 class="card-title text-center">Monthly Early Report</h3>
            </div>
            <div class="card-body">
                <?php if($mearlydata){?>
                <table class="table" id="datatable">
					<thead>
						<tr>
							<td rowspan="2">SL.No.</td>
							<td rowspan="2">PayCode</td>
							<td rowspan="2">Employee Name</td>
							<td rowspan="2">Shift</td>
							<?php foreach($months as $column){?>
							<th colspan="2"><?php echo date("D-d",strtotime($column['date']));?></th>
							<?php }?>
							<th colspan="2">Total</th>
						</tr>
						<tr>
							<?php foreach($months as $column){?>
							<th>Morning</th>
							<th>Lunch</th>
							<?php }?>
							<th>Morning</th>
							<th>Lunch</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1;foreach($mearlydata as $key=>$value){?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $value['details']['paycode'];?></td>
							<td><?php echo $value['details']['employee_name'];?></td>
							<td><?php echo $value['details']['shift_name'];?></td>
							<?php foreach($months as $column){?>
							<td><?php echo $value['dates'][$column['date']]['early_arrival'];?></td>
							<td><?php echo $value['dates'][$column['date']]['less_lunch'];?></td>
							<?php }?>
							<td><?php echo $value['details']['total_morning'];?></td>
							<td><?php echo $value['details']['total_lunch'];?></td>
						</tr>
						<?$i++;}?>
					</tbody>
				</table>
				<?}else{?>
                No Data Found
                <?}?>
            </div>
        </div>
	</div>
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
$(function(){
	$('#datatable').DataTable( {
		"paging": false,
		"ordering": false,
        "info":     false,
		"searching":false,
		"fixedHeader": true,
        "scrollY": "300px", // Adjust as per your requirement
        "scrollCollapse": true,
		"scrollX": true,
		"fixedColumns":   {
            left: 4
        },
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
});
$(function(){
	//var start = moment().subtract(29, "days");
	//var end = moment();
	var start =moment('<?= $fromdate ?>');
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
//--></script>
<?php js_end(); ?>