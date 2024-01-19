<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-leave"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-leave','role'=>'form')); ?>
				

				<div class="row">
					<div class="col-md-6">
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-firstname">Leave Field</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'leave_field', 'id' => 'leave_field', 'placeholder'=>"leave field",'value' => set_value('leave_field', $leave_field))); ?>
								<?php echo $validation->showerror('name', 'aio_error'); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Leave Code</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'leave_code', 'id' => 'leave_code', 'placeholder'=>"leave code",'value' => set_value('leave_code', $leave_code))); ?>
								<?php echo $validation->showerror('code', 'aio_error'); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Leave Description</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'leave_description', 'id' => 'leave_description', 'placeholder'=>"leave description",'value' => set_value('leave_description', $leave_description))); ?>
								<?php echo $validation->showerror('code', 'aio_error'); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Weekly Off Exclude</label>
							<div class="col-md-8">
								<div class="checkbox checkbox-primary checkbox-single">
									<?php echo form_checkbox(array('name' => 'week_exclude','id'=>'week_exclude', 'value' => '1','checked' => ($week_exclude ? true : false))); ?>
									<label for="week_exclude"></label>
								</div>
							</div>
						</div>	
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Holiday Exclude</label>
							<div class="col-md-8">
								<div class="checkbox checkbox-primary checkbox-single">
									<?php echo form_checkbox(array('name' => 'holiday_exclude', 'value' => '1','checked' => ($holiday_exclude ? true : false))); ?>
									<label></label>
								</div>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Is accural</label>
							<div class="col-md-8">
								<div class="checkbox checkbox-primary checkbox-single">
									<?php echo form_checkbox(array('name' => 'accural', 'value' => '1','checked' => ($accural ? true : false))); ?>
									<label></label>
								</div>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Leave Type</label>
							<div class="col-md-8">
								<?php  echo form_dropdown('leave_type', $leavetypes , set_value('leave_type',$leave_type ),array('class'=>'form-control select2','id' => 'input-current-status')); ?>			
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Insufficient Leave Post</label>
							<div class="col-md-8">
								<div class="checkbox checkbox-primary checkbox-single">
									<?php echo form_checkbox(array('name' => 'insuff_leave_post', 'value' => '1','checked' => ($insuff_leave_post ? true : false))); ?>
									<label></label>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card bg-sky">
							<div class="card-body">
								<h5 class="text-center">Saction Limit</h5>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group row required">
											<label class="col-md-4 control-label" for="input-firstname">Min</label>
											<div class="col-md-8">
												<?php echo form_input(array('class'=>'form-control','name' => 'saction_limit_min', 'id' => 'saction_limit_min', 'placeholder'=>"Min",'value' => set_value('saction_limit_min', $saction_limit_min))); ?>
											</div>
										</div>
											
										<div class="form-group row required">
											<label class="col-md-4 control-label" for="input-firstname">Accural Type</label>
											<div class="col-md-8">
												<?php  echo form_dropdown('accural_type', $accuraltypes , set_value('accural_type',$accural_type ),array('class'=>'form-control select2','id' => 'input-current-status')); ?>
											</div>
										</div>
										<div class="form-group row required">
											<label class="col-md-4 control-label" for="input-firstname">Eligiblilty Day</label>
											<div class="col-md-8">
												<?php echo form_input(array('class'=>'form-control','name' => 'elligible_day', 'id' => 'elligible_day', 'placeholder'=>"Eligiblilty day",'value' => set_value('elligible_day', $elligible_day))); ?>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group row required">
											<label class="col-md-4 control-label" for="input-firstname">Max</label>
											<div class="col-md-8">
												<?php echo form_input(array('class'=>'form-control','name' => 'saction_limit_max', 'id' => 'saction_limit_max', 'placeholder'=>"Max",'value' => set_value('saction_limit_max', $saction_limit_max))); ?>
											</div>
										</div>
										<div class="form-group row required">
											
											<label class="col-md-4 control-label" for="input-code">Saction Type</label>
											<div class="col-md-8">
												<div class="radio radio-info form-check-inline">
													<?php echo form_radio(array('name' => 'saction_type', 'id'=>'carried','value' => 'fixed','checked' => ($saction_type == 'fixed' ? true : false))); ?>
													<label for="config_ssl_yes"> Carried </label>
												</div>
												<div class="radio radio-info form-check-inline">
													<?php echo form_radio(array('name' => 'saction_type','id'=>'fixed',  'value' => 'fixed','checked' => ($saction_type == 'fixed' ? true : false))); ?>
													<label for="config_ssl_no"> Fixed </label>
												</div>
											</div>
										</div>
										
										<div class="form-group row required">
											<label class="col-md-4 control-label" for="input-firstname">Rate per month</label>
											<div class="col-md-8">
												<?php echo form_input(array('class'=>'form-control','name' => 'rate_per_month', 'id' => 'rate_per_month', 'placeholder'=>"Rate per month",'value' => set_value('rate_per_month', $rate_per_month))); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card bg-sky">
							<div class="card-body">
								<h5 class="text-center">Carried Leaves</h5>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group row required">
											<label class="col-md-4 control-label" for="input-firstname">Present</label>
											<div class="col-md-8">
												<?php echo form_input(array('class'=>'form-control','name' => 'carried_leaves_present', 'id' => 'carried_leaves_present', 'placeholder'=>"Present",'value' => set_value('carried_leaves_present', $carried_leaves_present))); ?>
											</div>
										</div>
										<div class="form-group row required">
											<label class="col-md-4 control-label" for="input-firstname">MaxAccuralLimit</label>
											<div class="col-md-8">
												<?php echo form_input(array('class'=>'form-control','name' => 'carried_leaves_limit', 'id' => 'carried_leaves_limit', 'placeholder'=>"Limit",'value' => set_value('carried_leaves_limit', $carried_leaves_limit))); ?>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group row required">
											<label class="col-md-4 control-label" for="input-firstname">Leave</label>
											<div class="col-md-8">
												<?php echo form_input(array('class'=>'form-control','name' => 'carried_leaves_leave', 'id' => 'carried_leaves_leave', 'placeholder'=>"Leaves",'value' => set_value('carried_leaves_leave', $carried_leaves_leave))); ?>
											</div>
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
	
//--></script>
<?php js_end(); ?>