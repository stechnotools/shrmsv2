<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-hod"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-hod','role'=>'form')); ?>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Branch Name</label>
					<div class="col-md-10">
						<div class="input-group">
							<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(0=>'Select Branch')), set_value('branch_id', $branch_id),"id='branch_id' class='form-control select2'"); ?>
							<?php echo $validation->showerror('branch_id', 'aio_error'); ?>
							<div class="input-group-append">
								<button class="input-group-text btn btn-primary addselect" data-url="<?= admin_url('branch/add') ?>" data-target='branch_id' type="button"><i class="mdi mdi-plus"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Hod Name</label>
					<div class="col-md-10">
						<?php echo form_dropdown('user_id', option_array_value($users, 'user_id', 'employee_name',array(''=>'Select Name')), set_value('user_id', $user_id),"id='user_id' class='form-control select2'"); ?>
						<?php echo $validation->showError('user_id', 'aio_error'); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-code">Hod Code</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'code', 'id' => 'firstname', 'placeholder'=>"code",'value' => set_value('code', $code))); ?>
						<?php echo $validation->showError('code', 'aio_error'); ?>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div> <!-- panel-body -->
		</div> <!-- panel -->
	</div> <!-- col -->
</div>
<?php js_start(); ?>
<script type="text/javascript">
$(function(){
	$("#branch_id").change(function(){
		var branch_id=$(this).val();
		$.ajax({
			url: '<?php echo admin_url("employee/getEmployeeByBranch"); ?>',
			dataType: 'json',
			type: 'post',
			data:{
				'branch_id':branch_id,
			},
			success: function(json) {
				html = '<option value="">Select Employee</option>';

					if (json != '') {
						for (i = 0; i < json.length; i++) {
							html += '<option value="' + json[i]['id'] + '"';

							if (json[i]['id'] == '<?php echo $user_id; ?>') {
								html += ' selected="selected"';
							}

							html += '>' + json[i]['empname'] + '</option>';
						}
					} else {
						html += '<option value="0" selected="selected">Select Employee</option>';
					}

					$('select[name=\'user_id\']').html(html);
					$('select[name=\'userid\']').select2();
			}
		});
	})
});
</script>
<?php js_end(); ?>