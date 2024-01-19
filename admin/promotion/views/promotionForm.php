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
										<label class="col-md-4 control-label" for="input-firstname">Effective From</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'effective_from', 'id' => 'effective_from', 'placeholder'=>"Effective From",'value' => set_value('effective_from', ''))); ?>
										</div>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Payout Month</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'payout_month', 'id' => 'payout_month', 'placeholder'=>"Payout Month",'value' => set_value('payout_month', ''))); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Remarks</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'remarks', 'id' => 'remarks', 'placeholder'=>"Remarks",'value' => set_value('remarks', ''))); ?>
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
                            <a class="nav-link" id="salary-tab" data-toggle="tab" href="#earning" role="tab" aria-controls="earning" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">Salary Revision</span>
                            </a>
                        </li>
                        
						
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane show active" id="general" role="tabpanel" aria-labelledby="general-tab">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">Employee Type</label>
										<div class="col-md-8">
											<?php echo form_dropdown('employee_type', array("general"=>"General","field"=>"Field"), set_value('employee_type', $employee_type),"id='employee_type' class='form-control select2'"); ?>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">Branch</label>
										<div class="col-md-8">
											<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(0=>'Select Branch')), set_value('branch_id', $branch_id),"id='branch_id' class='form-control select2'"); ?>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">Department</label>
										<div class="col-md-8">
											<?php echo form_dropdown('department_id', option_array_value($departments, 'id', 'name',array(''=>'Select Department')), set_value('department_id', $department_id),"id='department' class='form-control select2'"); ?>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">Category</label>
										<div class="col-md-8">
											<?php echo form_dropdown('category_id', option_array_value($categories, 'id', 'name',array(''=>'Select Category')), set_value('category_id', $category_id),"id='category_id' class='form-control select2'"); ?>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">Section</label>
										<div class="col-md-8">
											<?php echo form_dropdown('section_id', option_array_value($sections, 'id', 'name',array(''=>'Select Section')), set_value('section_id', $section_id),"id='section_id' class='form-control select2'"); ?>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">Grade</label>
										<div class="col-md-8">
											<?php echo form_dropdown('grade_id', option_array_value($grades, 'id', 'name',array(''=>'Select Grade')), set_value('grade_id', $grade_id),"id='grade_id' class='form-control select2'"); ?>
										</div>
									</div>
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-email">Designation</label>
										<div class="col-md-8">
											<?php echo form_dropdown('designation_id', option_array_value($designations, 'id', 'name',array(''=>'Select Designation')), set_value('designation_id', $designation_id),"id='designation_id' class='form-control select2'"); ?>
										</div>
									</div>
									
									
								</div>
								<div class="col-md-6">
									
							
								</div>
							</div>
						</div>
						<div class="tab-pane" id="earning" role="tabpanel" aria-labelledby="earning-tab">
							<div class="table-responsive">
								<table class="table mb-0">
									<thead>
										<tr>
											<th>Salary Item</th>
											<th>Current Salary</th>
											<th>Revised Salary</th>
											<th>Revision %</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
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