<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-bank"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-bank','role'=>'form')); ?>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-firstname">Bank Name</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'firstname', 'placeholder'=>"name",'value' => set_value('name', $name))); ?>
								<?php echo $validation->showError('name', 'aio_error'); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Branch Name</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'branch', 'id' => 'branch', 'placeholder'=>"branch",'value' => set_value('branch', $branch))); ?>
								<?php echo $validation->showError('branch', 'aio_error'); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Branch Code</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'code', 'id' => 'firstname', 'placeholder'=>"code",'value' => set_value('code', $code))); ?>
								<?php echo $validation->showError('code', 'aio_error'); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Center</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'center', 'id' => 'center', 'placeholder'=>"center",'value' => set_value('center', $center))); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Address</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'address', 'id' => 'address', 'placeholder'=>"address",'value' => set_value('address', $address))); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">State</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'state', 'id' => 'state', 'placeholder'=>"state",'value' => set_value('state', $state))); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">District</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'district', 'id' => 'district', 'placeholder'=>"district",'value' => set_value('district', $district))); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">City</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'city', 'id' => 'city', 'placeholder'=>"city",'value' => set_value('city', $city))); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Phone</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'phone', 'id' => 'phone', 'placeholder'=>"phone",'value' => set_value('phone', $phone))); ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">IFSC</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'ifsc', 'id' => 'ifsc', 'placeholder'=>"ifsc",'value' => set_value('ifsc', $ifsc))); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">MICR</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'micr', 'id' => 'micr', 'placeholder'=>"micr",'value' => set_value('micr', $micr))); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">SWIFT</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'swift', 'id' => 'swift', 'placeholder'=>"swift",'value' => set_value('swift', $swift))); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">NEFT</label>
							<div class="col-md-8">
								<?php  echo form_dropdown('neft', array('1'=>'Yes','0'=>'No'), set_value('neft',$neft),array('class'=>'form-control select2','id' => 'neft')); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">RTGS</label>
							<div class="col-md-8">
								<?php  echo form_dropdown('rtgs', array('1'=>'Yes','0'=>'No'), set_value('rtgs',$rtgs),array('class'=>'form-control select2','id' => 'rtgs')); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">IMPS</label>
							<div class="col-md-8">
								<?php  echo form_dropdown('imps', array('1'=>'Yes','0'=>'No'), set_value('imps',$imps),array('class'=>'form-control select2','id' => 'imps')); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">UPI</label>
							<div class="col-md-8">
								<?php  echo form_dropdown('upi', array('1'=>'Yes','0'=>'No'), set_value('upi',$upi),array('class'=>'form-control select2','id' => 'upi')); ?>
							</div>
						</div>
						
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-code">Ledger Folio</label>
							<div class="col-md-8">
								<?php  echo form_dropdown('ledger_folio', array('1'=>'Yes','0'=>'No'), set_value('ledger_folio',$ledger_folio),array('class'=>'form-control select2','id' => 'ledger_folio')); ?>
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