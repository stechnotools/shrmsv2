<style>
	.sat-header{
		background-color: #6c0e1f;
		color:#fff;
	}
	.sun-header{
		background-color: #f42932;
		color:#fff;
	}
	.download-btn {
  		position: relative;
		z-index: 99;
	}
</style>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Muster Roll Report</h3>
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
                <h3 class="card-title text-center">Muster Roll Report</h3>
            </div>
            <div class="card-body">
                <?php if($musterroll){?>
                <table class="table row-border order-column" id="datatable">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Paycode</th>
							<th>Card No.</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<?php foreach($months as $column){
								if(date("D",strtotime($column['date']))=="Sun"){
									$class="sun-header";
								}else if(date("D",strtotime($column['date']))=="Sat"){
									$class="sat-header";
								}else{
									$class="";
								}
							?>
							<th class="<?=$class?>"><?php echo date("D-d",strtotime($column['date']));?></th>
							<?php }?>
							<th>Present</th>
							<th>Absent</th>
							<th>MIS</th>
							<th>Sat(<?=$total_sat?>)</th>
							<th>Sun(<?=$total_sun?>)</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1;foreach($musterroll as $key=>$value){?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $value['details']['paycode'];?></td>
							<td><?php echo $value['details']['card_no'];?></td>
							<td><?php echo $value['details']['employee_name'];?></td>
							<td><?php echo $value['details']['designation_name'];?></td>
							<?php foreach($months as $column){?>
							<td><?php echo $value['dates'][$column['date']]['status'];?></td>
							<?php }?>
							<td><?php echo $value['details']['total_present'];?></td>
							<td><?php echo $value['details']['total_absent'];?></td>
							<td><?php echo $value['details']['total_mis'];?></td>
							<td><?php echo $value['details']['total_satpresent'];?></td>
							<td><?php echo $value['details']['total_sunpresent'];?></td>
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
	var table= $('#datatable').DataTable( {
		"fixedHeader": true,
        "scrollY": "300px", // Adjust as per your requirement
        "scrollCollapse": true,
		"scrollX": true,
        "paging":         false,
        "fixedColumns":   {
            left: 4
        },
		"ordering": false,
        "info":     false,
		"searching":false,
		"dom": 'f<"download-btn"B>ltip',
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
		"rowCallback": function(row, data, index) {
			//console.log(row);
			var dayCell = $(row).find('td:first-child');
			var dayContent = dayCell.text();
			if (dayContent.includes('Sat') || dayContent.includes('Sun')) {
				dayCell.addClass('highlight-cell');
			}
		}
	});
	$('#datatable thead th').each(function(index) {
		var headerText = $(this).text();
		var day = headerText.split('-')[0]; // Extract day part
		if (day === 'Sun') {
			table.column(index).nodes().to$().addClass('sun-header');
		} else if (day === 'Sat') {
			table.column(index).nodes().to$().addClass('sat-header');
		}
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