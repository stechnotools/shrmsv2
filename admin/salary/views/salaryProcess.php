<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?=$heading_title?></h3>
				<div class="panel-tools float-right">
					<form action="" method="get" enctype="multipart/form-data" id="form-payment">          	
						<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
							<div class="">
								<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name'), set_value('branch_id', $branch_id),"id='branch_id' class='form-control select2'"); ?>
							</div>
							
							<div class="input-group">
								<input type="text" class="form-control monthpicker" id="month" name="month" value="<?=$month?>" placeholder="Month/Year" aria-label="Recipient's username" aria-describedby="basic-addon2" readonly>
								<div class="input-group-append">
									<button type="button" data-toggle="tooltip" title="Upload Bulk Attendance" class="btn btn-info" ><i class="fa fa-calendar"></i></button>
								</div>
							</div>
							<button type="submit" data-toggle="tooltip" title="" class="btn btn-primary">Filter</button>
						</div>
					</form>
				</div>
			</div>
			<div class="card-body">
				<div class="salary_table">
					<table id="salarysheet" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr class="bg-info">
								<th>SL#</th>
								<th>CODE</th>
								<th>NAME</th>
								<th>SALARY DAYS</th>
								<th>BASIC</th>
								<th>NET SALARY</th>
								<th>STATUS</th>
								<th>ACTION</th>
								
							</tr>
						</thead>
						<tbody>
							
							<?php foreach($sheets as $key=>$sheet){?>
							<tr>
								<td><?=$key+1?></td>
								<td><?=$sheet['paycode']?></td>
								<td><?=$sheet['emp_name']?></td>
								<td><?=$sheet['salary_days']?></td>
								<td><?=$sheet['basic']?></td>
								<td><?=$sheet['net_salary']?></td>
								<td>
									<?=$sheet['payment_info']?'<span class="badge badge-primary">Paid</span>':'<span class="badge badge-secondary">UnPaid</span>'?>
								</td>
								<td>
								<?php if($sheet['payment_info']){?>
								<a class="btn btn-info" href="<?=admin_url("salary/slip/{$sheet['payment_info']->id}")?>">Generate Slip</a></td>
								<?}else{?>
								<a class="btn btn-primary" href="<?=admin_url("salary/payment?user_id={$sheet['user_id']}&month={$month}")?>">Make Payment</a></td>
								
								<?}?>
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
			"responsive":true,
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