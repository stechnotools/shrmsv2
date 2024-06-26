<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">

			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-field"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-field','role'=>'form')); ?>
				
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Field Name</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>"name",'value' => set_value('name', $name))); ?>
						<?php echo $validation->showError('name', 'aio_error'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Leave Type</label>
					<div class="col-md-10">
						<?php  echo form_dropdown('type', array('deduction'=>'Deduction','earning' => 'Earning','company'=>'Company'), set_value('type',$type),array('class'=>'form-control select2','id' => 'type')); ?>
					</div>
				</div>

				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Gross Calculation</label>
					<div class="col-md-10">
						<?php  echo form_dropdown('status', array(1=>'Yes',0 => 'No'), set_value('status',$status),array('class'=>'form-control select2','id' => 'status')); ?>
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
