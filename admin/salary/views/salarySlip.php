<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Payslip for <?=$payment_month?></h3>
				<div class="panel-tools float-right">
				</div>
			</div>
			<div class="card-body">
				<div align="center">
					<table class="table table-borderless">
						<tbody>
							<tr>
								<td colspan="3" align="center">
									<strong><?=$this->settings->config_site_title?></strong><br/>
									<strong><?=$this->settings->config_address?></strong><br/>
									<strong>Salary Slip For The Month Of : <?=$payment_month?></strong>
								</td>
							</tr>
							<tr>
								<td>
									<span>Paycode : <?=$employee->paycode?> </span><br>
									<span>F/H Name : <?=$employee->guardian_name?> </span><br>
									<span>UAN No. : <?=$employee->pf_no?> </span><br>
									<span>DOJ : <?=$employee->doj?> </span>
									
								</td>
								<td>
									<span>Name : <?=$employee->employee_name?> </span><br>
									<span>Department : <?=$employee->department_name?> </span><br>
									<span>Designation. : <?=$employee->designation_name?> </span><br>
									<span>Salary status : Regular </span>
									
								</td>
								<td>
									<span>Print Date : <?=date("d-m-Y")?> </span>
								</td>
							</tr>	
						</tbody>
					</table>
					<table class="table table-bordered">
					  <thead>
						<tr>
						  <th scope="col"></th>
						  <th scope="col">Days</th>
						  <th scope="col">Earning</th>
						  <th scope="col">Deduction</th>
						  <th scope="col">Total</th>
						</tr>
					  </thead>
					  <tbody>
						<tr>
							<td>
								PF No. :<span class="pull-right"> <?=$employee->pf_no?></span><br>
								ESI No. : <span class="pull-right"> <?=$employee->esi?></span><br>
								PAN No.: <span class="pull-right"> <?=$employee->pan?></span><br>
								Bank A/c.: <span class="pull-right"> <?=$employee->bank_account?></span><br>
								Bank Name: <span class="pull-right"> <?=$employee->bank_id?></span>
							</td>
							<td>
								Days Worked<span class="pull-right"><?=$aemployee->present_days?></span><br/>
								Hld/Wo:<span class="pull-right"><?=$aemployee->weekly_off?></span><br/>
								CL:<span class="pull-right"><?=$aemployee->cl?></span><br/>
								EL:<span class="pull-right"><?=$aemployee->el?></span><br/>
								SL:<span class="pull-right"><?=$aemployee->sl?></span>
								
							</td>
							<td>
								Basic:<span class="pull-right"><?=$payment_info->basic?></span><br>
								HRA:<span class="pull-right"><?=$payment_info->hra?></span><br>
								DA:<span class="pull-right"><?=$payment_info->da?></span><br>
								Conveyance:<span class="pull-right"><?=$payment_info->conveyance?></span><br>
								OT:<span class="pull-right"><?=$payment_info->ot?></span><br>
								Employee Welfare:<span class="pull-right"><?=$payment_info->emp_welfare?></span><br/>
								<?php foreach($allowances as $allowance){?>
								<?=$allowance->label?>:<span class="pull-right"><?=$allowance->value?></span><br/>
								<?}?>
							</td>
							<td>
								<?php 
								$deduct=0;
								foreach($deductions as $deduction){
									$deduct+=$deduction->value;
								?>
								<?=$deduction->label?>:<span class="pull-right"><?=$deduction->value?></span><br/>
								<?}?>
								Professional Tax:<span class="pull-right"><?=$payment_info->pt?></span><br>
								TDS:<span class="pull-right"><?=$payment_info->tds?></span><br/>
								
							</td>
							<td>
								Total Pay:<span class="pull-right"><?=$payment_info->gross-($deduct+$payment_info->pt+$payment_info->tds)?></span><br>
								Less:<span class="pull-right">0</span><br>
								Advance:<span class="pull-right"><?=$payment_info->advance?></span><br>
								Loan:<span class="pull-right"><?=$payment_info->loan?></span><br>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>Days Payble <span class="pull-right"><?=$payment_info->salary_days?></span></td>
							<td>Gross pay <span class="pull-right"><?=$payment_info->gross?></span></td>
							<td>Deduction <span class="pull-right"><?=$deduct+$payment_info->pt+$payment_info->tds?></span></td>
							<td>Net pay <span class="pull-right"><?=$payment_info->net_salary?></span></td>
						</tr>
						
					  </tbody>
					</table>
					<p>This is a Computer Generated Statement, Does not required Signature</p>
					<hr/>
				</div>
			</div> <!-- panel-body -->
		  </div> <!-- panel -->
	 </div> <!-- col -->
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
   
	
//--></script>
<?php js_end(); ?>