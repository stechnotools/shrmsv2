<?php
$validation = \Config\Services::validation();
?>

<?php echo form_open_multipart('', 'id="form-country"'); ?>
<div class="row">
	<div class="col-xl-12">
		<div class="card">
            <div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-country"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>

			<div class="card-body">
                <div class="form-group <?=$validation->hasError('code')?'is-invalid':''?>">
                    <label class=" control-label" for="input-name">Country Code *</label>
                    <div class="">
                        <?php echo form_input(array('class'=>'form-control','name' => 'iso_code_2', 'id' => 'input-iso_code_2', 'placeholder'=>'Country Code','value' => set_value('iso_code_2', $iso_code_2))); ?>
                        <?php echo $validation->showerror('iso_code_2', 'aio_error'); ?>
                    </div>
                </div>
				<div class="form-group <?=$validation->hasError('name')?'is-invalid':''?>">
					<label for="name" >Name *</label>
					<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>'Name','value' => set_value('name', $name))); ?>
					<?php echo $validation->showerror('name', 'aio_error'); ?>		
				</div>
                
			</div> 
		</div>
	</div> 
</div>
<?php echo form_close(); ?>
<?php js_start(); ?>
<script type="text/javascript"><!--
	
//--></script>
<script type="text/javascript"><!--
	
//--></script>

<?php js_end(); ?>