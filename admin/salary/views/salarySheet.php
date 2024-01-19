<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Salary Sheet</h3>
				<div class="panel-tools float-right">
					<form action="" method="get" enctype="multipart/form-data" id="form-payment">          	
						<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
							<div class="">
								<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(0=>'Select Branch')), set_value('branch_id', $branch_id),"id='branch_id' class='form-control select2'"); ?>
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
								<th rowspan="2">SL#</th>
								<th rowspan="2">CODE</th>
								<th rowspan="2">NAME</th>
								<th rowspan="2">DESIGNATION</th>
								<th rowspan="2">EXTRA DUTY</th>
								<th rowspan="2">PWO</th>
								<th rowspan="2">TOTAL DAYS</th>
								<th rowspan="2">SALARY DAYS</th>
								<th rowspan="2">CTC</th>
								<th rowspan="2">BASIC</th>
								<th rowspan="2">HRA</th>
								<th rowspan="2">CONVEYANCE ALLOWANCE</th>
								<th colspan="<?=count($earning_fields)?>">ALLOWANCE</th>
								<th rowspan="2">GROSS</th>
								<th colspan="<?=count($deduction_fields)?>">DEDUCTION</th>
								<th rowspan="2">PT</th>
								<th rowspan="2">TDS</th>
								<th rowspan="2">ADVANCE</th>
								<th rowspan="2">LOAN</th>
								<th rowspan="2">NET SALARY</th>
								<th rowspan="2">OT AMOUNT</th>
								<th colspan="3">SITE</th>
								<th rowspan="2">TOTAL PAY SALARY</th>
							</tr>
							<tr>
								<?php foreach($earning_fields as $efields){?>
								<th><?=$efields->name?></th>
								<?}?>
								<?php foreach($deduction_fields as $dfields){?>
								<th><?=$dfields->name?></th>
								<?}?>
								<th>SITE NAME</th>
								<th>SITE SALARY</th>
								<th>SITE TOTAL SALARY</th>
							</tr>
						</thead>
						<tbody>
							
							<?php foreach($sheets as $key=>$sheet){
								$site_name=$site_salary=[];
								$site_tsalary=0;
								foreach($sheet['sitedata'] as $site){
									$site_name[]=$site['site_name'];
									$site_salary[]=round($site['site_salary']);
									$site_tsalary+=$site['site_salary'];
								}
							?>
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
								<td><?=$sheet['basic']?></td>
								<td><?=$sheet['hra']?></td>
								<td><?=$sheet['conveyance']?></td>
								<?php foreach($earning_fields as $efields){?>
								<td><?=isset($sheet['earning'][$efields->field])?$sheet['earning'][$efields->field]:0?></td>
								<?}?>
								<td><?=$sheet['gross']?></td>
								<?php foreach($deduction_fields as $dfields){?>
								<td><?=isset($sheet['deduction'][$dfields->field])?$sheet['deduction'][$dfields->field]:0?></td>
								<?}?>
								<td><?=$sheet['pt']?></td>
								<td><?=$sheet['tds']?></td>
								<td><?=$sheet['advance']?></td>
								<td><?=$sheet['loan']?></td>
								<td><?=$sheet['net_salary']?></td>
								<td><?=$sheet['ot_amount']?></td>
								<td><?=implode("<br>",$site_name)?></td>
								<td><?=implode("<br>",$site_salary)?></td>
								<td><?=round($site_tsalary)?></td>
								<td><?=round($site_tsalary)+round($sheet['net_salary'])?></td>
							</tr>
							<?}?>
							
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
			"bProcessing": false,
			"responsive":false,
			//"dom"		: "<'top'<'pull-left'l><'pull-right'B>>rt<'bottom'<'pull-left'i><'pull-right'p>>",
			/*"buttons": [
				
				{
					extend: 'excelHtml5',
					text: 'Export',
					title: 'Salarysheet export',
					/*exportOptions: {
						columns: [':visible:not(.not-export-col):not(.hidden)']
					}*/
				/*},
				
				'colvis'
			],*/
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
				//var table1 = $('<table>').append(table.clone());
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