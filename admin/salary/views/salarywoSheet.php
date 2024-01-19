<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Salary Sheet</h3>
				<div class="panel-tools float-right">
					<form action="" method="get" enctype="multipart/form-data" id="form-payment">          	
						<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
							<div class="">
								<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(0=>'Select Branch')), set_value('branch_id', ''),"id='branch_id' class='form-control select2'"); ?>
							</div>
							<div class="input-group">
								<input type="text" class="form-control monthpicker" id="month" name="month" value="<?=$month?>" placeholder="Month/Year" aria-label="Recipient's username" aria-describedby="basic-addon2" readonly>
								<div class="input-group-append">
									<button type="button" data-toggle="tooltip" title="Upload Bulk Attendance" class="btn btn-info" ><i class="fa fa-calendar"></i></button>
								</div>
							</div>
							<button type="submit" data-toggle="tooltip" title="" class="btn btn-primary">generate</button>
							<button type="button" data-toggle="tooltip" title="" class="btn btn-info" id="exportexcel">Export</button>
						</div>
					</form>
				</div>
			</div>
			<div class="card-body">
				<div class="salary_table" style="overflow:auto">
					<table id="salarysheet" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr class="bg-info">
								<th>SL#</th>
								<th>CODE</th>
								<th>NAME</th>
								<th>DESIGNATION</th>
								<th>EXTRA DUTY</th>
								<th>PWO</th>
								<th>TOTAL DAYS</th>
								<th>SALARY DAYS</th>
								<th>CTC</th>
								<th>HRA</th>
								<th>CONVEYANCE ALLOWANCE</th>
								<th>ALLOWANCE</th>
								<th>OT AMOUNT</th>
								<th>GROSS</th>
								<th>EARN SALARY</th>
								<th>DEDUCTION</th>
								<th>ESI-EMPLOYEES</th>
								<th>ESI-EMPLOYER</th>
								<th>PF - EMPLOYEE</th>
								<th>EPF - EMPLOYER</th>
								<th>PT</th>
								<th>TDS</th>
								<th>ADVANCE</th>
								<th>LOAN</th>
								<th>NET SALARY</th>
								
							</tr>
						</thead>
						<tbody>
							
							<?php foreach($sheets as $key=>$sheet){?>
							<tr>
								<td><?=$key+1?></td>
								<td><?=$sheet['paycode']?></td>
								<td><?=$sheet['emp_name']?></td>
								<td><?=$sheet['designation_name']?></td>
								<td><?=$sheet['extra_duty']?></td>
								<td><?=$sheet['pwo']?></td>
								<td><?=$sheet['total_days']?></td>
								<td><?=$sheet['salary_days']?></td>
								<td><?=$sheet['ctc']?></td>
								<td><?=$sheet['hra']?></td>
								<td><?=$sheet['conveyance']?></td>
								<td><?=$sheet['allownace']?></td>
								<td><?=$sheet['ot_amount']?></td>
								<td><?=$sheet['gross']?></td>
								<td><?=$sheet['earn_salary']?></td>
								<td><?=$sheet['deduction']?></td>
								<td><?=$sheet['esi_employees']?></td>
								<td><?=$sheet['esi_employer']?></td>
								<td><?=$sheet['pf_employee']?></td>
								<td><?=$sheet['epf_employer']?></td>
								<td><?=$sheet['pt']?></td>
								<td><?=$sheet['tds']?></td>
								<td><?=$sheet['advance']?></td>
								<td><?=$sheet['loan']?></td>
								<td><?=$sheet['net_salary']?></td>
								
							</tr>
							<?}?>
							<!--<tr>
								<td colspan="3"><span class="TR_B">Total</span></td>
								<td><span class="TR_B">$370,548.00</span></td>
								<td><span class="TR_B">$113,107.52</span></td>
								<td><span class="TR_B">$257,440.48</span></td>
								<td><span class="TR_C">$29,710.00 </span></td>
								<td><span class="TR_C">$257,440.48 </span></td>
							</tr>-->
						</tbody>
				  </table>
				  
				  
				</div>
      
			</div> <!-- panel-body -->
		</div> <!-- panel -->
	 </div> <!-- col -->
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
   $(function() {
		
		table=$('#salarysheet').DataTable({
			"bProcessing": true,
			"dom"		: "<'top'<'pull-left'l><'pull-right'B>>rt<'bottom'<'pull-left'i><'pull-right'p>>",
			"buttons": [
				
				{
					extend: 'excelHtml5',
					text: 'Export',
					title: 'Salarysheet export',
					/*exportOptions: {
						columns: [':visible:not(.not-export-col):not(.hidden)']
					}*/
				},
				
				'colvis'
			],
			"paging":false,
			"columnDefs": [
				{ targets: 'no-sort', orderable: false },
				{ targets: 'no-visible', visible: false },
				{ responsivePriority: 1, targets: 0 },
				{ responsivePriority: 2, targets: -1 }
			],
			
		});
		
		$('#btn-filter').click(function(){ //button filter event click	
			table.ajax.reload();  //just reload table
		});
		$('#btn-reset').click(function(){ //button reset event click
			$('#form-filter')[0].reset();
			table.ajax.reload();  //just reload table
		});
		
		
		
		$("#exportexcel").click(function(e){
			var table = $('#salarysheet');
			if(table && table.length){
				$(table).table2excel({
					exclude: ".noExl",
					name: "Salary sheet",
					filename: "salarysheet" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
					fileext: ".xls",
					exclude_img: true,
					exclude_links: true,
					exclude_inputs: true,
					
				});
			}
		});
		
		
		
	});
//--></script>
<?php js_end(); ?>