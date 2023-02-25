<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger" form="form-department"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart(null,array('class' => 'form-horizontal', 'id' => 'form-department','role'=>'form')); ?>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Department Name</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'firstname', 'placeholder'=>"name",'value' => set_value('name', $name))); ?>
						<?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Department Code</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'code', 'id' => 'firstname', 'placeholder'=>"code",'value' => set_value('code', $code))); ?>
						<?php echo form_error('code', '<div class="text-danger">', '</div>'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-short">HOD</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'hod', 'id' => 'input-hod', 'placeholder'=>'hod','value' => set_value('hod', $hod))); ?>
						<?php echo form_error('hod', '<div class="text-danger">', '</div>'); ?>
					</div>
				</div>
					
				<div class="form-group row required">
					<label class="col-sm-2 control-label" for="input-phone">Email</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'email', 'id' => 'input-email', 'placeholder'=>'email','value' => set_value('email', $email))); ?>
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