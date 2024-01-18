<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger" form="form-branch"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart(null,array('class' => 'form-horizontal', 'id' => 'form-branch','role'=>'form')); ?>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Branch Name</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'firstname', 'placeholder'=>"name",'value' => set_value('name', $name))); ?>
						<?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Branch Code</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'code', 'id' => 'firstname', 'placeholder'=>"code",'value' => set_value('code', $code))); ?>
						<?php echo form_error('code', '<div class="text-danger">', '</div>'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-short">Short Name</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'short', 'id' => 'input-short', 'placeholder'=>'short','value' => set_value('short', $short))); ?>
						<?php echo form_error('short', '<div class="text-danger">', '</div>'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-sm-2 control-label" for="input-address">Branch Address</label>
					<div class="col-md-10">
						<textarea name="address" id="input-address" class="form-control" placeholder="Address"><?=$address?></textarea>
					</div>
				</div>		
				<div class="form-group row required">
					<label class="col-sm-2 control-label" for="input-phone">Email ID1</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'email1', 'id' => 'input-email1', 'placeholder'=>'email','value' => set_value('email1', $email1))); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-sm-2 control-label" for="input-phone">Email ID2</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'email2', 'id' => 'input-email2', 'placeholder'=>'email','value' => set_value('email2', $email2))); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-sm-2 control-label" for="input-phone">Total No. of Gate Pass</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'total_pass', 'id' => 'input-total_pass', 'placeholder'=>'total_pass','value' => set_value('total_pass', $total_pass))); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-sm-2 control-label" for="input-phone">Gate Pass Monthly Duration(min)</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'pass_duration', 'id' => 'input-pass_duration', 'placeholder'=>'pass_duration','value' => set_value('pass_duration', $pass_duration))); ?>
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