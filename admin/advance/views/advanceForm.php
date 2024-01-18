<div class="row">	<div class="col-lg-12">		<div class="card">			<div class="card-header">				<h3 class="card-title float-left"><?php echo $text_form; ?></h3>				<div class="panel-tools float-right">					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger" form="form-advance"><i class="fa fa-save"></i></button>					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>				</div>			</div>			<div class="card-body">				<?php echo form_open_multipart(null,array('class' => 'form-horizontal', 'id' => 'form-advance','role'=>'form')); ?>					<div class="card card-border">						<div class="card-header border-info bg-transparent pb-0">							<h3 class="card-title text-info">Employee Details</h3>						</div>						<div class="card-body">							<div class="row">								<div class="col-md-4">									<div class="form-group row required">										<label class="col-md-4 control-label" for="input-firstname">Paycode</label>										<div class="col-md-8">											<?php echo form_hidden('user_id', $user_id);?>											<div class="input-group">                                                <?php echo form_input(array('class'=>'form-control','name' => 'paycode', 'id' => 'paycode', 'placeholder'=>"Paycode",'readonly'=>'true','value' => set_value('paycode', $paycode))); ?>												<?php if(!$edit){?>												<span class="input-group-prepend">													<button type="button" class="btn waves-effect waves-light btn-primary" id="employee_list"><i class="fa fa-search"></i></button>												</span>												<?}?>											</div>										</div>									</div>								</div>								<div class="col-md-4">									<div class="form-group row required">										<label class="col-md-4 control-label" for="input-firstname">Name</label>										<div class="col-md-8">											<?php echo form_input(array('class'=>'form-control','name' => 'employee_name', 'id' => 'advance_name', 'placeholder'=>"Employee Name",'readonly'=>'true','value' => set_value('employee_name', $employee_name))); ?>										</div>									</div>								</div>								<div class="col-md-4">									<div class="form-group row required">										<label class="col-md-4 control-label" for="input-firstname">Branch</label>										<div class="col-md-8">											<?php echo form_input(array('class'=>'form-control','name' => 'branch_name', 'id' => 'branch_name', 'placeholder'=>"Branch Name",'readonly'=>'true','value' => set_value('branch_name', $branch_name))); ?>										</div>									</div>								</div>								<div class="col-md-4">									<div class="form-group row required">										<label class="col-md-4 control-label" for="input-firstname">Card No</label>										<div class="col-md-8">											<?php echo form_input(array('class'=>'form-control','name' => 'card_no', 'id' => 'card_no', 'placeholder'=>"Card No",'readonly'=>'true','value' => set_value('card_no', $card_no))); ?>										</div>									</div>								</div>								<div class="col-md-4">									<div class="form-group row required">										<label class="col-md-4 control-label" for="input-firstname">Department</label>										<div class="col-md-8">											<?php echo form_input(array('class'=>'form-control','name' => 'department_name', 'id' => 'department_name', 'placeholder'=>"Department Name",'readonly'=>'true','value' => set_value('department_name', $department_name))); ?>										</div>									</div>								</div>								<div class="col-md-4">									<div class="form-group row required">										<label class="col-md-4 control-label" for="input-firstname">Designation</label>										<div class="col-md-8">											<?php echo form_input(array('class'=>'form-control','name' => 'designation_name', 'id' => 'designation_name', 'placeholder'=>"Designation Name",'readonly'=>'true','value' => set_value('designation_name', $designation_name))); ?>										</div>									</div>								</div>															</div>						</div>					</div>										<div class="row">						<div class="col-md-6">							<div class="form-group row required">								<label class="col-md-4 control-label" for="input-firstname">Paid Month</label>								<div class="col-md-8">									<div class="input-group">									  <?php echo form_input(array('class'=>'form-control datepick','name' => 'paid_month', 'id' => 'paid_month', 'placeholder'=>"Paid Month",'value' => set_value('paid_month', $paid_month))); ?>										<div class="input-group-append">										<span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>									  </div>									</div>										</div>							</div>							<div class="form-group row required">								<label class="col-md-4 control-label" for="input-email">Installment Start Month</label>								<div class="col-md-8">									<div class="input-group">									  <?php echo form_input(array('class'=>'form-control datepick','name' => 'install_start', 'id' => 'input-install_start', 'placeholder'=>'Installment Start Month','value' => set_value('install_start', $install_start))); ?>										<div class="input-group-append">										<span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>									  </div>									</div>								</div>							</div>							<div class="form-group row required">								<label class="col-md-4 control-label" for="input-email">Total Advance Amount</label>								<div class="col-md-8">									<?php echo form_input(array('class'=>'form-control','name' => 'advance_amount', 'id' => 'advance_amount', 'placeholder'=>'Total Advance Amount','value' => set_value('advance_amount', $advance_amount))); ?>								</div>							</div>							<div class="form-group row required">								<label class="col-md-4 control-label" for="input-email">Installment Amount</label>								<div class="col-md-8">									<?php echo form_input(array('class'=>'form-control','name' => 'installment_amount', 'id' => 'installment_amount', 'placeholder'=>'Installment','value' => set_value('installment_amount', $installment_amount))); ?>								</div>							</div>							<div class="form-group row required">								<label class="col-md-4 control-label" for="input-email">No of Installment</label>								<div class="col-md-8">									<?php echo form_input(array('class'=>'form-control','name' => 'no_installment', 'id' => 'no_installment', 'placeholder'=>'No of Installment','value' => set_value('no_installment', $no_installment))); ?>								</div>							</div>													</div>					</div>				<?php echo form_close(); ?>			</div> <!-- panel-body -->		</div> <!-- panel -->	 </div> <!-- col --></div><?php js_start(); ?><script type="text/javascript"><!--    $(document).ready(function() {		$("#employee_list").click(function(){			$.ajax({				url: '<?php echo admin_url("employee"); ?>',				dataType: 'html',				beforeSend: function() {				},						complete: function() {				},							success: function(html) {					$('.dmodal .modal-body').html(html);					// Display Modal					$('.dmodal').modal('show'); 				}			});		});				$(".datepick").datepicker({			format: 'mm-yyyy',			 viewMode: "months", 			minViewMode: "months"		});				$("#installment_amount").keyup(function(){			 advance = parseFloat ($("#advance_amount").val());			 installment = parseFloat ($("#installment_amount").val());			 no_installment=Math.ceil(advance/installment);			 $("#no_installment").val(no_installment);		})	});	//--></script><?php js_end(); ?>