<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Daily Performance Report</h3>
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
                            <?php echo form_input(array('id'=>'fromdate','name'=>'fromdate', 'class'=>'form-control datepicker','placeholder'=>'From Date','value' => set_value('attendance', $fromdate),'required'=>true)) ?>
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
                <h3 class="card-title text-center">Daily Performance Report</h3>
            </div>
            <div class="card-body">
                <?php if($dperformance){?>
                <table class="table" id="datatable">
					<thead>
						<tr>
							<th>SL.No.</th>
							<th>PayCode</th>
							<th>Card No.</th>
							<th>Employee Name</th>
							<th>Department</th>
							<th>Designation</th>
							<th>Shift</th>
							<th>Start</th>
							<th>In</th>
							<th>Lunch Out</th>
							<th>Lunch In</th>
							<th>Out</th>
							<th>Hours Worked</th>
							<th>Status</th>
							<th>Early Arrival</th>
							<th>Late Arrival</th>
							<th>Early Departure</th>
							<th>Late Departure</th>
							<th>Excess Lunch</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($dperformance as $key=>$value){?>
					<tr>
						<td><?php echo $value['slno'];?></td>
						<td><?php echo $value['paycode'];?></td>
						<td><?php echo $value['card_no'];?></td>
						<td><?php echo $value['employee_name'];?></td>
						<td><?php echo $value['department_name'];?></td>
						<td><?php echo $value['designation_name'];?></td>
						<td><?php echo $value['shift'];?></td>
						<td><?php echo $value['start'];?></td>
						<td><?php echo $value['startin'];?></td>
						<td><?php echo $value['lunch_out'];?></td>
						<td><?php echo $value['lunch_in'];?></td>
						<td><?php echo $value['out'];?></td>
						<td><?php echo $value['worked_hr'];?></td>
						<td><?php echo $value['status'];?></td>
						<td><?php echo $value['early_arrival'];?></td>
						<td><?php echo $value['late_arrival'];?></td>
						<td><?php echo $value['early_departure'];?></td>
						<td><?php echo $value['late_departure'];?></td>
						<td><?php echo $value['excess_lunch'];?></td>
					</tr>
					<?}?>
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
//--></script>
<?php js_end(); ?>