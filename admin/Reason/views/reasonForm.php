<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-reason"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-reason','role'=>'form')); ?>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Reason Code</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'code', 'id' => 'firstname', 'placeholder'=>"code",'value' => set_value('code', $code))); ?>
						<?php echo $validation->showError('code', 'aio_error'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Reason Name</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>"name",'value' => set_value('name', $name))); ?>
						<?php echo $validation->showError('name', 'aio_error'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Leave Field</label>
					<div class="col-md-10">
						<?php  echo form_dropdown('leave_field', array(''=>'Nill','cl' => 'Casual Leave','t'=>'Tour'), set_value('leave_field',$leave_field),array('class'=>'form-control select2','id' => 'leave_field')); ?>
					</div>
				</div>
				
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Leave Value</label>
					<div class="col-md-10">
						<?php  echo form_dropdown('leave_value', array('0.25'=>'00.25','0.50' => '00.50','0.75'=>'00.75','1'=>'01.00'), set_value('leave_value',$leave_value),array('class'=>'form-control select2','id' => 'leave_value')); ?>
					</div>
				</div>
				
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Leave Reason</label>
					<div class="col-md-10">
						<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
						<label class="form-check-label" for="inlineCheckbox1">1</label>
						</div>
						<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
						<label class="form-check-label" for="inlineCheckbox2">2</label>
						</div>
						<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3" disabled>
						<label class="form-check-label" for="inlineCheckbox3">3 (disabled)</label>
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