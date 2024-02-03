<?php
$validation = \Config\Services::validation();
?>
<?php echo form_open_multipart('', 'id="form-permission"'); ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="Save" class="btn btn-danger btn-sm" form="form-permission"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Route</label>
                    <div class="col-lg-10 <?=$validation->hasError('route')?'is-invalid':''?>">
                        <input type="hidden" name="id" id="id" value="<?=$id?>"/>
                        <?php echo form_input(array('class'=>'form-control','name' => 'route', 'id' => 'route', 'placeholder'=>'Route','value' => set_value('route', $route))); ?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('route'); ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Description</label>
                    <div class="col-lg-10 <?=$validation->hasError('description')?'is-invalid':''?>">
                        <?php echo form_input(array('class'=>'form-control','name' => 'description', 'id' => 'description', 'placeholder'=>'Description','value' => set_value('description', $description))); ?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('description'); ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Module</label>
                    <div class="col-lg-10 <?=$validation->hasError('module')?'is-invalid':''?>">
                        <?php echo form_input(array('class'=>'form-control','name' => 'module', 'id' => 'description', 'placeholder'=>'Module','value' => set_value('module', $module))); ?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('module'); ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Action</label>
                    <div class="col-lg-10 <?=$validation->hasError('module')?'is-invalid':''?>">
                        <?php echo form_dropdown('action', $permission_actions, set_value('action', $action), 'id=\'action\' class=\'form-control\'')?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('action'); ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 control-label" for="input-status">Status</label>
                    <div class="col-md-10">
                        <?php  echo form_dropdown('status', array('1'=>'Enable','0'=>'Disable'), set_value('status',$status),array('class'=>'form-control','id' => 'input-status')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<?php js_start(); ?>
    <script type="text/javascript"><!--
    //--></script>
<?php js_end(); ?>