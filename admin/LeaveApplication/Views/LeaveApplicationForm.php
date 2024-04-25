<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm saveleave" form="form-leaveapplication"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">form-leaveapplication
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => '','role'=>'form')); ?>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group row required">
							<label class="col-md-2 control-label" for="input-firstname">Branch</label>
							<div class="col-md-10">
								<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(''=>'Select Branch')), set_value('branch_id', $branch_id),"id='branch_id' class='form-control select2'"); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-2 control-label" for="input-firstname">Employee</label>
							<div class="col-md-10">
								<?php echo form_dropdown('user_id', array(), set_value('user_id', $user_id),"id='user_id' class='form-control select2'"); ?>
							</div>
						</div>

						<div class="form-group row required ">
							<label class="col-md-2 control-label" for="input-firstname">Date from</label>
							<div class="col-md-3">
								<?php echo form_input(array('class'=>'form-control datepicker','name' => 'leave_from', 'id' => 'leave_from', 'placeholder'=>"Leave From",'data-date-start-date'=>"0d",'value' => set_value('leave_from', date("d-m-Y",strtotime($leave_from))))); ?>
							</div>
							<label class="col-md-2 control-label" for="input-firstname">Date To</label>
							<div class="col-md-3">
								<?php echo form_input(array('class'=>'form-control datepicker','name' => 'leave_to', 'id' => 'leave_to', 'placeholder'=>"Leave To",'data-date-start-date'=>"0d",'value' => set_value('leave_to', date("d-m-Y",strtotime($leave_to))))); ?>
							</div>
							<div class="col-md-2">
								<?php echo form_input(array('class'=>'form-control','name' => 'leave_total', 'id' => 'leave_total', 'placeholder'=>"Total",'value' => set_value('leave_total',''),'readonly'=>'readonly')); ?>
							</div>
						</div>

						<div class="form-group row required">
							<label class="col-md-2 control-label" for="input-firstname">Leave Code *</label>
							<div class="col-md-10">
								<?php echo form_dropdown('leave_id', option_array_value($leavecodes, 'id', 'leave_field',array(''=>'Select Leave Code')), set_value('leave_id',$leave_id),array('class'=>'form-control select2','id' => 'leave_id')); ?>
								<?php echo $validation->showerror('leave_id', 'aio_error'); ?>

							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-2 control-label" for="input-firstname">Leave Type</label>
							<div class="col-md-10">
								<?php echo form_dropdown('leave_type', $leavetypes, set_value('leave_type',$leave_type),array('class'=>'form-control select2','id' => 'leave_type')); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-2 control-label" for="input-firstname">Reason</label>
							<div class="col-md-10">
								<?php echo form_input(array('class'=>'form-control','name' => 'reason', 'id' => 'reason', 'placeholder'=>"Reason",'value' => set_value('reason', $reason)));  ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-2 control-label" for="input-firstname"></label>
							<div class="col-md-9">
								<div class="form-group form-check">
									<input type="checkbox" class="form-check-input" id="chkLeavePost" name="chkLeavePost" value="1" <?php echo $chkLeavePost?"checked='checked'":"";?>>
									Leave Post in case of full day present
								</div>
							</div>
						</div>

					</div>

					<div class="col-md-6">
						<h4>My Leave Details</h4>
						<div id="leavedetails"></div>

					</div>
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
		});

		$('#user_id').on('select2:select', function (event) {
			//get leave details
			var user_id=event.params.data.id;
			$.ajax({
				url: '<?php echo admin_url("leaveapplication/getLeaveDetails"); ?>',
				dataType: 'html',
				type: 'post',
				data:{
					'user_id':user_id,
				},
				success: function(html) {
					$('#leavedetails').html(html);
				}

			})
		});

		const leaveFromDateInput = $("#leave_from");
		const leaveToDateInput = $("#leave_to");
		const totalDaysInput = $("#leave_total");

		// Calculate the total days on page load
		calculateTotalDays();

		// Add an event listener to calculate the total days when the input fields change
		$('#leave_from, #leave_to').on('change', calculateTotalDays);

		function calculateTotalDays() {
			const fromDate = leaveFromDateInput.datepicker('getDate');
			const toDate = leaveToDateInput.datepicker('getDate');

			if (fromDate && toDate) {
				const timeDifference = toDate.getTime() - fromDate.getTime();
				const totalDays = timeDifference / (1000 * 60 * 60 * 24);

			totalDaysInput.val(totalDays+1);

			}
		}

		$(".saveleave").click(function(e){
			e.preventDefault();
			$.ajax({
				url: '<?php echo admin_url("leaveapplication/saveLeaveApplication"); ?>',
				dataType: 'html',
				type: 'post',
				data: $("#leaveapplicationForm").serialize(),
				success: function(html) {
					$('#leaveapplicationForm').html(html);
				}
			})
		});

	});

//--></script>

<?php js_end(); ?>