<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger" form="form-member"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart(null,array('class' => 'form-horizontal', 'id' => 'form-member','role'=>'form')); ?>
					<div class="card card-border">
						<div class="card-header border-info bg-transparent pb-0">
							<h3 class="card-title text-info">Employee Details</h3>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Paycode</label>
										<div class="col-md-8">
											<?php echo form_hidden('user_id', $user_id);?>
											<?php echo form_hidden('suser_id', $suser_id);?>
											<div class="input-group">
                                                <?php echo form_input(array('class'=>'form-control','name' => 'paycode', 'id' => 'paycode', 'placeholder'=>"Paycode",'readonly'=>'true','value' => set_value('paycode', $paycode))); ?>
												<?php if(!$edit){?>
												<span class="input-group-prepend">
													<button type="button" class="btn waves-effect waves-light btn-primary" id="employee_list"><i class="fa fa-search"></i></button>
												</span>
												<?}?>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Name</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'employee_name', 'id' => 'employee_name', 'placeholder'=>"Employee Name",'readonly'=>'true','value' => set_value('employee_name', $employee_name))); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Branch</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'branch_name', 'id' => 'branch_name', 'placeholder'=>"Branch Name",'readonly'=>'true','value' => set_value('branch_name', $branch_name))); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Card No</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'card_no', 'id' => 'card_no', 'placeholder'=>"Card No",'readonly'=>'true','value' => set_value('card_no', $card_no))); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Department</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'department_name', 'id' => 'department_name', 'placeholder'=>"Department Name",'readonly'=>'true','value' => set_value('department_name', $department_name))); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Designation</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'designation_name', 'id' => 'designation_name', 'placeholder'=>"Designation Name",'readonly'=>'true','value' => set_value('designation_name', $designation_name))); ?>
										</div>
									</div>
								</div>
								
							</div>
							<hr/>
							<div class="row">
								
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Payble in Month</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'payble_month', 'id' => 'payble_month', 'placeholder'=>"Payble in Month",'value' => set_value('Payble in Month', ''))); ?>
										</div>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Applicable From</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'payble_month', 'id' => 'payble_month', 'placeholder'=>"Payble in Month",'value' => set_value('Payble in Month', ''))); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Applicable To</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'payble_month', 'id' => 'payble_month', 'placeholder'=>"Payble in Month",'value' => set_value('Payble in Month', ''))); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					
					<ul class="nav nav-tabs tabs" role="tablist">
                        <li class="nav-item tab">
                            <a class="nav-link active" id="office-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">General</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="earning-tab" data-toggle="tab" href="#earning" role="tab" aria-controls="earning" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">Earning</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="deduction-tab" data-toggle="tab" href="#deduction" role="tab" aria-controls="deduction" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">Deduction</span>
                            </a>
                        </li>
						<li class="nav-item tab">
                            <a class="nav-link" id="shift-other" data-toggle="tab" href="#other" role="tab" aria-controls="other" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">Other</span>
                            </a>
                        </li>
						
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane show active" id="general" role="tabpanel" aria-labelledby="general-tab">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">CTC</label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'ctc', 'id' => 'ctc', 'data-value'=>$ctc, 'placeholder'=>"CTC",'value' => set_value('ctc', $ctc))); ?>
												<!--<div class="input-group-append w-50">
													<div class="fselect w-100">
														<?php echo form_dropdown('formula[formula_ctc]', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_ctc]', $formula['formula_ctc']),"id='formula_ctc' data-link='ctc' class='form-control select2'"); ?>
													</div>
													
												</div>-->
												
											</div>
											<?php echo form_error('ctc', '<div class="text-danger">', '</div>'); ?>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Basic</label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'basic', 'id' => 'basic', 'data-value'=>$basic,'placeholder'=>"Basic",'value' => set_value('basic', $basic))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_basic]', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_basic]', $formula['formula_basic']),"id='formula-basic' data-link='basic' class='form-control select2'"); ?>
												</div>
											</div>
											<?php echo form_error('basic', '<div class="text-danger">', '</div>'); ?>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">DA</label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'da', 'id' => 'da', 'data-value'=>$da, 'placeholder'=>'DA','value' => set_value('da', $da))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_da]', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_da]', $formula['formula_da']),"id='formula-da' data-link='da' class='form-control select2'"); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">HRA</label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'hra', 'id' => 'hra', 'data-value'=>$hra, 'placeholder'=>'HRA','value' => set_value('hra', $hra))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_hra]', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_hra]', $formula['formula_hra']),"id='formula-hra' data-link='hra' class='form-control select2'"); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">Conveyance</label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'conveyance', 'id' => 'conveyance', 'data-value'=>$conveyance, 'placeholder'=>'conveyance','value' => set_value('conveyance', $conveyance))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_conveyance]', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_conveyance]', $formula['formula_conveyance']),"id='formula-conveyance' data-link='conveyance' class='form-control select2'"); ?>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
								<div class="col-md-6">
									
									
									
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">OT Rate/hr</label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'ot', 'id' => 'ot', 'data-value'=>$ot, 'placeholder'=>'OT','value' => set_value('ot', $ot))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_ot]', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_ot]', $formula['formula_ot']),"id='formula-ot' data-link='ot' class='form-control select2'"); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">TDS</label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'tds', 'id' => 'tds', 'data-value'=>$tds, 'placeholder'=>'TDS','value' => set_value('tds', $tds))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_tds]', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_tds]', $formula['formula_tds']),"id='formula-tds' data-link='tds' class='form-control select2'"); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">Employee Welfare</label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'emp_welfare', 'data-value'=>$emp_welfare, 'id' => 'emp_welfare', 'placeholder'=>'Employee Welfare','value' => set_value('emp_welfare', $emp_welfare))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_emp_welfare]', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_emp_welfare]', $formula['formula_emp_welfare']),"id='formula-emp_welfare' data-link='emp_welfare' class='form-control select2'"); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">Employer Welfare</label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'empr_welfare', 'id' => 'empr_welfare', 'data-value'=>$empr_welfare, 'placeholder'=>'Employer Welfare','value' => set_value('empr_welfare', $empr_welfare))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_empr_welfare]', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_empr_welfare]', $formula['formula_empr_welfare']),"id='formula-empr_welfare' data-link='empr_welfare' class='form-control select2'"); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="earning" role="tabpanel" aria-labelledby="earning-tab">
							<div class="row">
								<div class="col-md-6">
									<?php foreach($fields as $field){
										if($field->type=="earning"){
									?>
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-website"><?=$field->name?></label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' =>'earning['.$field->field.']', 'id' => $field->field, 'data-value'=>$_earning[$field->field], 'placeholder'=>"$field->name",'value' => set_value($field->field, $_earning[$field->field]))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_'.$field->field.']', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_'.$field->field.']', $formula["formula_$field->field"]),"id='formula-$field->field' data-link='$field->field' class='form-control select2'"); ?>
												</div>
											</div>
										</div>
									</div>
									<?}}?>
								</div>
								
							</div>
						</div>
						<div class="tab-pane" id="deduction" role="tabpanel" aria-labelledby="deduction-tab">
							<div class="row">
								<div class="col-md-6">
									<?php 
									foreach($fields as $field){
										if($field->type=="deduction" || $field->type=="company"){
									?>
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-website"><?=$field->name?></label>
										<div class="col-md-8">
											<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'deduction['.$field->field.']', 'id' => $field->field, 'data-value'=>$_deduction[$field->field], 'placeholder'=>"$field->name",'value' => set_value($field->field, $_deduction[$field->field]))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_'.$field->field.']', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_'.$field->field.']', $formula["formula_$field->field"]),"id='formula-$field->field' data-link='$field->field' class='form-control select2'"); ?>
												</div>
											</div>
										</div>
									</div>
									<?}}?>
								
								</div>
								<div class="col-md-6">
									<?php foreach($taxs as $tax){?>
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-website"><?=$tax->description?></label>
										<div class="col-md-8">
											<input class="form-check-input" type="checkbox" value="1" id = "<?=$tax->field?>" name="deduction[<?=$tax->field?>]" <?php echo $_deduction[$tax->field] ?"checked='checked'":"";?>>
											  
											<!--<div class="input-group">
												<?php echo form_input(array('class'=>'form-control','name' => 'deduction['.$tax->field.']', 'id' => $tax->field, 'data-value'=>$_deduction[$tax->field], 'placeholder'=>"$tax->name",'value' => set_value($tax->field, $_deduction[$tax->field]))); ?>
												<div class="input-group-append w-50">
													<?php echo form_dropdown('formula[formula_'.$tax->field.']', option_array_value($formulas, 'id', 'code',array(0=>'None')), set_value('formula[formula_'.$tax->field.']', $formula["formula_$tax->field"]),"id='formula-$tax->field' data-link='$tax->field' class='form-control select2'"); ?>
												</div>
											</div>-->
										</div>
									</div>
									<?}?>
								
									
								</div>
								
							</div>
							</div>
						<div class="tab-pane" id="other" role="tabpanel" aria-labelledby="other-tab">
						<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-website">Mode of Salary</label>
										<div class="col-md-8">
											<div class="form-check form-check-inline">
											  <input class="form-check-input" type="radio" name="salary_mode" id="cash" value="cash" <?php echo $salary_mode=='cash'?"checked='checked'":"";?>>
											  <label class="form-check-label" for="cash">Cash</label>
											</div>
											<div class="form-check form-check-inline">
											  <input class="form-check-input" type="radio" name="salary_mode" id="cheque" value="cheque" <?php echo $salary_mode=='cheque'?"checked='checked'":"";?>>
											  <label class="form-check-label" for="cheque">Cheque</label>
											</div>
											<div class="form-check form-check-inline">
											  <input class="form-check-input" type="radio" name="salary_mode" id="bank" value="bank" <?php echo $salary_mode=='bank'?"checked='checked'":"";?>>
											  <label class="form-check-label" for="bank">Bank</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-website">Bank Name</label>
										<div class="col-md-8">
											<?php echo form_dropdown('bank_id', array("f"=>"Fixed","r"=>"Rotational","i"=>"Ignore"), set_value('bank_id', $bank_id),"id='bank_id' class='form-control select2'"); ?>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-vimeo">Bank Account No</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'account_no', 'id' => 'input-account_no', 'placeholder'=>'Bank Account Number','value' => set_value('account_no', $account_no))); ?>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-vimeo">L Folio No</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'l_folio_no', 'id' => 'input-l_folio_no', 'placeholder'=>'L Folio No','value' => set_value('l_folio_no', $l_folio_no))); ?>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12">
											<div class="form-check">
											  <input class="form-check-input" type="checkbox" value="1" id="wo_payable" name="wo_payable" <?php echo $wo_payable?"checked='checked'":"";?>>
											  <label class="form-check-label" for="defaultCheck1">
												Payble Days(WO Include)
											  </label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12">
											<div class="form-check">
											  <input class="form-check-input" type="checkbox" value="1" id="holiday_payable" name="holiday_payable" <?php echo $holiday_payable?"checked='checked'":"";?>>
											  <label class="form-check-label" for="defaultCheck1">
												Payble Days(Holidays Include)
											  </label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<div class="col-md-12">
											<div class="form-check">
											  <input class="form-check-input" type="checkbox" value="1" id="lta" name="lta" <?php echo $lta?"checked='checked'":"";?>>
											  <label class="form-check-label" for="defaultCheck1">
												Is LTA Process
											  </label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12">
											<div class="form-check">
											  <input class="form-check-input" type="checkbox" value="1" id="lic" name="lic" <?php echo $lic?"checked='checked'":"";?>>
											  <label class="form-check-label" for="defaultCheck1">
												Is LIC Gratuity
											  </label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12">
											<div class="form-check">
											  <input class="form-check-input" type="checkbox" value="1" id="edli" name="edli" <?php echo $edli?"checked='checked'":"";?>>
											  <label class="form-check-label" for="defaultCheck1">
												Is EDLI Applicable
											  </label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12">
											<div class="form-check">
											  <input class="form-check-input" type="checkbox" value="1" id="exgra" name="exgra" <?php echo $exgra?"checked='checked'":"";?>>
											  <label class="form-check-label" for="defaultCheck1">
												Is Exgratia Applicable
											  </label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12">
											<div class="form-check">
											  <input class="form-check-input" type="checkbox" value="1" id="gratuity_add" name="gratuity_add" <?php echo $gratuity_add?"checked='checked'":"";?>>
											  <label class="form-check-label" for="defaultCheck1">
												Is Add Gratuity in Gross
											  </label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<?php echo form_close(); ?>
				</div> <!-- panel-body -->
		  </div> <!-- panel -->
	 </div> <!-- col -->
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
    $(document).ready(function() {
		$("#employee_list").click(function(){
			$.ajax({
				url: '<?php echo admin_url("employee"); ?>',
				dataType: 'html',
				beforeSend: function() {
				},		
				complete: function() {
				},			
				success: function(html) {
					$('.dmodal .modal-body').html(html);

					// Display Modal
					$('.dmodal').modal('show'); 
				}
			});
		});
		
		$( "select[name^='formula']" ).change(function() {
		  
		  formula_id=$(this).val();
		  field=$(this).data("link");
		  cvalue=$("#"+field).data("value");
		  //alert(cvalue);
		  user_id='<?=$user_id?>';
		  //alert(formula_id);
		  if(formula_id!=0){
			  $("#"+field).val("");
			  $("#"+field).attr('readonly',true);
		  }else{
			  //alert("ok");
			  $("#"+field).val(cvalue);
			  $("#"+field).attr('readonly',false);
		  }
		  /*$.ajax({
				url: '<?php echo admin_url("formula/calculate"); ?>',
				data:{formula_id:formula_id,user_id:user_id},
				method:'POST',
				dataType: 'json',
				beforeSend: function() {
				},		
				complete: function() {
				},			
				success: function(json) {
					if(json.result){
						$("#"+field).val(json.result);
						/*var sbutton='<div class="btn-group dsave"><button class="btn btn-info fsave" type="button"><i class="fa fa-check"></i></button><button class="btn btn-warning fcancel" type="button"><i class="fa fa-times"></i></button></div>';
						$("#"+field).parent().find('.fselect').hide();
						$("#"+field).parent().find('.input-group-append').append(sbutton);
						
						$(".select2").prop("disabled", true);*/
					/*}else{
						$("#"+field).val(cvalue);
					}
					
				}
			});*/
		})
		
		//$("body ")
	});
	
//--></script>
<?php js_end(); ?>