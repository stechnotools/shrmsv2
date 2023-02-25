<?php
$validation = \Config\Services::validation();
?>

<?php echo form_open_multipart('', 'id="form-country"'); ?>
<div class="row">
	<div class="col-xl-12">
		<div class="card">
            <div class="card-header">
                <h3 class="card-title float-left"><?php echo $heading_title; ?></h3>
                <div class="panel-tools float-right">
                    <button type="submit" form="form-country" class="btn btn-primary">Save</button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary">Cancel</a>
                </div>
            </div>

			<div class="card-body">
                <div class="form-group <?=$validation->hasError('code')?'is-invalid':''?>">
                    <label class=" control-label" for="input-name">Country Code</label>
                    <div class="">
                        <?php echo form_input(array('class'=>'form-control','name' => 'iso_code_2', 'id' => 'input-iso_code_2', 'placeholder'=>'Country Code','value' => set_value('iso_code_2', $iso_code_2))); ?>
                    </div>
                </div>
				<div class="form-group <?=$validation->hasError('name')?'is-invalid':''?>">
					<label for="name" >Name</label>
					<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>'Name','value' => set_value('name', $name))); ?>
					<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('name'); ?></div>		
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