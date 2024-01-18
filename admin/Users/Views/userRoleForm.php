<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="Save" class="btn btn-danger btn-sm" form="form-role"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
                <?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-role','role'=>'form')); ?>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="example-hf-email">Name</label>
                    <div class="col-lg-10 <?=$validation->hasError('name')?'is-invalid':''?>">
                        <input type="hidden" name="id" id="id" value="<?=$id?>"/>
                        <?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>'Name','value' => set_value('name', $name))); ?>
                        <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('name'); ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 control-label" for="input-status">Status</label>
                    <div class="col-md-10">
                        <?php  echo form_dropdown('status', array('1'=>'Enable','0'=>'Disable'), set_value('status',$status),array('class'=>'form-control','id' => 'input-status')); ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php js_start(); ?>
    <script type="text/javascript"><!--

        //--></script>
<?php js_end(); ?>