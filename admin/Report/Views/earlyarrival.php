<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Spot Early Arrival Report</h3>
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
                <h3 class="card-title text-center">Early Arrival Report</h3>
            </div>
            <div class="card-body">
                <?php if($earlyarrival){?>
                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th rowspan="2">SL.No.</th>
                            <th rowspan="2">PayCode</th>
                            <th rowspan="2">Card No.</th>
                            <th rowspan="2">Employee Name</th>
                            <th rowspan="2">Shift</th>
                            <th rowspan="2">Start</th>
                            <th rowspan="2">In</th>
                            <th rowspan="2">Early Arrival</th>
                            <th colspan="3">Early</th>
                        </tr>
                        <tr>
                            <th>>(0.10)</th>
                            <th>>(0.30)</th>
                            <th>>(1.00)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($earlyarrival as $key=>$spot){?>
                        <tr>
                            <td><?php echo $spot['slno'];?></td>
                            <td><?php echo $spot['paycode'];?></td>
                            <td><?php echo $spot['card_no'];?></td>
                            <td><?php echo $spot['employee_name'];?></td>
                            <td><?php echo $spot['shift'];?></td>
                            <td><?php echo $spot['start'];?></td>
                            <td><?php echo $spot['startin'];?></td>
                            <td><?php echo $spot['early_arrival'];?></td>
                            <td><?php echo $spot['ten'];?></td>
                            <td><?php echo $spot['thirty'];?></td>
                            <td><?php echo $spot['one'];?></td>
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
            'csv', 'excel', 'pdf', 'print'
        ]
	});
});
//--></script>
<?php js_end(); ?>