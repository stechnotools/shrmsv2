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
		z-index: 9;
	}
</style>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Shift Roster Report</h3>
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
								<label for="inputEmail3" class="control-label">Month</label>
								<?php echo form_input(array('id'=>'month','name'=>'month', 'class'=>'form-control monthpicker','placeholder'=>'Month','value' => set_value('month',$month),'required'=>true)) ?>
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
                <h3 class="card-title text-center">Roster Report</h3>
            </div>
            <div class="card-body">
                <?php if($rosterdata){?>
                <table class="table row-border order-column" id="datatable">
					<thead>
						<tr>
							<th>Card No.</th>
							<th>Employee Name</th>
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
						</tr>
					</thead>
					<tbody>
						<?php $i=1;foreach($rosterdata as $key=>$value){?>
						<tr data-user="<?php echo $value['details']['user_id'];?>">
							<td><?php echo $value['details']['card_no'];?></td>
							<td><?php echo $value['details']['employee_name'];?><br/><span><?php echo $value['details']['designation_name'];?></span></td>
							<?php foreach($months as $column){?>
							<td data-date="<?php echo $column['date'];?>" class="shift"><?php echo $value['dates'][$column['date']]['shift'];?></td>
							<?php }?>
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
<div class="modal fade" id="shiftModal" tabindex="-1" role="dialog" aria-labelledby="shiftModalLabel" aria-hidden="true" style="display: none">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title mt-0" id="shiftModalLabel">Change Shift</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label for="shiftDropdown">Select Shift:</label>
				<?php echo form_dropdown('shift_id', option_array_value($shifts, 'id', 'code',array('0'=>'OFF')), set_value('shift_id', ''),"id='shift_id' class='form-control select2'"); ?>
			</div>
			<div class="modal-footer text-center">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          		<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="updateShift()">Save</button>
        	</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
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
		},
		"initComplete": function(settings, json) {
			// Set the background color of fixed columns to gray
			$('.dtfc-fixed-left').css('background-color', '#dbd9d9');
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
$(document).ready(function() {
	$('td.shift').click(function() {
        // Remove the "selected" class from all cells with class "clickable"
        $('.shift').removeClass('selected');
        // Add the "selected" class to the clicked cell
        $(this).addClass('selected');
		$("#shiftModal").modal("show");
    });
});
function updateShift() {
      // Get the selected shift from the dropdown
      const selectedShift = $('#shift_id').val();
      // Get the data-date attribute from the parent <tr>
      const date = $('td.selected').data('date');
      // Get the data-user attribute from the parent <tr>
      const user_id = $('td.selected').closest('tr').data('user');
	  const branch_id=$("#branch_id").val();
      // Perform an AJAX request to save the data
      $.ajax({
        url: '<?=admin_url('');?>',
        method: 'POST',
        data: {
          user_id: user_id,
          date: date,
          shift: selectedShift,
		  branch_id:branch_id
        },
        success: function(response) {
			response=JSON.parse(response);
			console.log(response.shift);
			$('td.selected').text(response.shift);
        },
        error: function(error) {
          // Handle any errors here
          console.error(error);
        }
      });
      // Close the modal
      $('#myModal').modal('hide');
    }
//--></script>
<?php js_end(); ?>