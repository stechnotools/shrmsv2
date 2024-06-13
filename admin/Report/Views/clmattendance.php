<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Daily Attendance Report</h3>
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
                <h3 class="card-title text-center">Daily Attendance Report</h3>
            </div>
            <div class="card-body">
                <?php if($clmattendance){?>

					<table class="table row-border order-column" id="datatable">

						<thead>
							<tr>
								<th>SL.No.</th>
								<th>EMP Code</th>
								<th>Safety Code</th>
								<th>Employee Name</th>
								<th>Designation</th>
								<th>Branch</th>
								<th>Site</th>
								<th>CLM In</th>
								<th>CLM out</th>
								<th>Working HRS</th>
								<th>Savior In</th>
								<th>Savior out</th>
								<th>Working HRS</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($clmattendance as $key=>$spot){?>
						<tr>
							<td><?php echo $key+1;?></td>
							<td><?php echo $spot['card_no'];?></td>
							<td><?php echo $spot['safety_pass_no'];?></td>
							<td><?php echo $spot['employee_name'];?></td>
							<td><?php echo $spot['designation_name'];?></td>
							<td><?php echo $spot['branch_name'];?></td>
							<td><?php echo $spot['department_name'];?></td>
							<td><?php echo $spot['clm_in'];?></td>
							<td><?php echo $spot['clm_out'];?></td>
							<td><?php echo $spot['clm_working_hr'];?></td>
							<td><?php echo $spot['savior_in'];?></td>
							<td><?php echo $spot['savior_out'];?></td>
							<td><?php echo $spot['savior_working_hr'];?></td>
							<td><?php echo $spot['status'];?></td>
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
	var table= $('#datatable').DataTable( {
		"fixedHeader": true,
        "scrollY": "300px", // Adjust as per your requirement
        "scrollCollapse": true,
		"scrollX": true,
        "paging":         false,
        "fixedColumns":   {
            left: 2
        },
		"ordering": false,
        "info":     false,
		"searching":true,
		"dom": '<"left"l><"middle"B><"right"f>rtip',
        "buttons": [
            'csv',
			'excel',
			{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
			'print'
        ],
	} );
});
//--></script>
<?php js_end(); ?>