<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-machine"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-machine','role'=>'form')); ?>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Machine Name</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'firstname', 'placeholder'=>"name",'value' => set_value('name', $name))); ?>
						<?php echo $validation->showError('name', 'aio_error'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Machine Code</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'code', 'id' => 'firstname', 'placeholder'=>"code",'value' => set_value('code', $code))); ?>
						<?php echo $validation->showError('code', 'aio_error'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Branch</label>
					<div class="col-md-10">
						<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name'), set_value('branch_code', $branch_id),"id='branch_id' class='form-control select2'"); ?>
						<?php echo $validation->showError('branch_id', 'aio_error'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Location</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'location', 'id' => 'location', 'placeholder'=>"location",'value' => set_value('location', $location))); ?>
					</div>
				</div>
				
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Used For</label>
					<div class="col-md-10">
						<?php  echo form_dropdown('used_for', array('a'=>'Attendance','c' => 'Canteen','v'=>'Vechicle'), set_value('used_for',$used_for),array('class'=>'form-control select2','id' => 'used_for')); ?>
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