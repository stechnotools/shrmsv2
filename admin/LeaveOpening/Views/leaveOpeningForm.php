<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-leaveopening"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-leaveopening','role'=>'form')); ?>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-firstname">Type</label>
							<div class="col-md-8">
								<?php echo form_dropdown('type', $types, set_value('type', $type),"id='type' class='form-control select2'"); ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-firstname">Financial Year</label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control fdatepicker','name' => 'fyear', 'id' => 'fyear', 'placeholder'=>"Financial Year",'value' => set_value('fyear', $fyear))); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row user action d-none">
					<div class="col-md-6">
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-firstname">Employee</label>
							<div class="col-md-8">
								<div class="input-group">
									<?php echo form_input(array('class'=>'form-control', 'id' => 'username', 'placeholder'=>"Employee",'readonly'=>'true')); ?>
									<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
									<span class="input-group-prepend">
										<button type="button" class="btn waves-effect waves-light btn-primary employee_list" data-userId="user_id" data-userName="username"><i class="fa fa-search"></i></button>
									</span>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="row">
					<div class="col-md-6 branch action d-none">
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-firstname">Branch</label>
							<div class="col-md-8">
								<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(''=>'Select Branch')), set_value('branch_id', $branch_id),"id='branch_id' class='form-control select2'"); ?>
							</div>
						</div>

					</div>
				</div>
				<div class="row">
					<div class="col-md-6 department action d-none">
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-firstname">Department</label>
							<div class="col-md-8">
								<?php echo form_dropdown('department_id', option_array_value($departments, 'id', 'name',array(''=>'Select Department')), set_value('department_id', $department_id),"id='department_id' class='form-control select2'"); ?>
							</div>
						</div>

					</div>
				</div>
				<hr>
				<div class="row">
					<?php foreach($leaves as $leave){?>
					<div class="col-md-6">
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-firstname"><?php echo $leave->leave_field;?></label>
							<div class="col-md-8">
								<?php echo form_input(array('class'=>'form-control','name' => 'leave_field['.$leave->id.']', 'id' => 'leave_field', 'placeholder'=>"",'value' => set_value('leave_field['.$leave->id .']', isset($leave_fields[$leave->id])?$leave_fields[$leave->id]:'' ))); ?>
							</div>
						</div>

					</div>
					<?}?>
				</div>

				<?php echo form_close(); ?>
			</div> <!-- panel-body -->
		</div> <!-- panel -->
	</div> <!-- col -->
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
	$(document).ready(function() {
		$("#type").change(function(){
			val=$(this).val();
			$(".action").addClass('d-none');
			$("."+val).removeClass('d-none');
			//alert(val);
		});
		$('#type').trigger('change');
		$(".employee_list").click(function(){
				user_id=$(this).data('userid');
				user_name=$(this).data('username');
				$.ajax({
					url: '<?php echo admin_url("employee"); ?>',
					dataType: 'html',
					data:{
						popup:true
					},
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function(html) {
						$('.employeemodal .modal-body').html(html);

						// Display Modal
						$('.employeemodal').modal('show');
						$(document).on( "click", 'a[data-reload="false"]', function(e){
							e.preventDefault();
							$("#" + user_id).val($(this).data('id'));
							$("#" + user_name).val($(this).data('name'));

							$('.employeemodal').modal('hide');
						});
					}
				});
			});

			/*$('.fdatepicker').datepicker({
				autoclose: true,
				orientation: "bottom",
				format: "yyyy",
				startDate: '-1Y',
				endDate: '+0Y',
				viewMode: "years",
				minViewMode: "years"
			});*/

			$('.fdatepicker').datepicker({
					format: "yyyy",
					minViewMode: 2,
					autoclose : true
				}).on('hide',function(date){
				$(".fdatepicker").val(date.target.value + "-" + (parseInt(date.target.value) + parseInt(1)));
			});
		});

//--></script>

<?php js_end(); ?>