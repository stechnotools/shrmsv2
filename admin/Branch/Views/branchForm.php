<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-branch"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-branch','role'=>'form')); ?>
				<ul class="nav nav-tabs tabs" role="tablist">
					<li class="nav-item tab">
						<a class="nav-link active" id="branch-tab" data-toggle="tab" href="#branch" role="tab" aria-controls="branch" aria-selected="false">
							<span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
							<span class="d-none d-sm-block">Branch Details</span>
						</a>
					</li>
					<li class="nav-item tab">
						<a class="nav-link" id="envirnment-tab" data-toggle="tab" href="#envirnment" role="tab" aria-controls="envirnment" aria-selected="false">
							<span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
							<span class="d-none d-sm-block">Envirment Setup</span>
						</a>
					</li>
					<li class="nav-item tab">
						<a class="nav-link" id="penalty-tab" data-toggle="tab" href="#penalty" role="tab" aria-controls="penalty" aria-selected="false">
							<span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
							<span class="d-none d-sm-block">Penalty Setup</span>
						</a>
					</li>
					<li class="nav-item tab">
						<a class="nav-link" id="payroll-tab" data-toggle="tab" href="#payroll" role="tab" aria-controls="payroll" aria-selected="false">
							<span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
							<span class="d-none d-sm-block">Payroll Setup</span>
						</a>
					</li>
					<li class="nav-item tab">
						<a class="nav-link" id="geofence-tab" data-toggle="tab" href="#geofence" role="tab" aria-controls="geofence" aria-selected="false">
							<span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
							<span class="d-none d-sm-block">Branch Geofence</span>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane show active" id="branch" role="tabpanel" aria-labelledby="branch-tab">
						<div class="form-group row required">
							<label class="col-md-2 control-label" for="input-firstname">Branch Name</label>
							<div class="col-md-10">
								<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'input-name', 'placeholder'=>"name",'value' => set_value('name', $name))); ?>
								<?php echo $validation->showError('name', 'aio_error'); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-2 control-label" for="input-code">Branch Code</label>
							<div class="col-md-10">
								<?php echo form_input(array('class'=>'form-control','name' => 'code', 'id' => 'firstname', 'placeholder'=>"code",'value' => set_value('code', $code))); ?>
								<?php echo $validation->showError('code', 'aio_error'); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-md-2 control-label" for="input-short">Short Name</label>
							<div class="col-md-10">
								<?php echo form_input(array('class'=>'form-control','name' => 'short', 'id' => 'input-short', 'placeholder'=>'short','value' => set_value('short', $short))); ?>
								<?php echo $validation->showError('short', 'aio_error'); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-sm-2 control-label" for="input-address">Branch Address</label>
							<div class="col-md-10">
								<textarea name="address" id="input-address" class="form-control" placeholder="Address"><?=$address?></textarea>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-sm-2 control-label" for="input-phone">Email ID</label>
							<div class="col-md-10">
								<?php echo form_input(array('class'=>'form-control','name' => 'email', 'id' => 'input-email', 'placeholder'=>'email','value' => set_value('email', $email))); ?>
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
					</div>
					<div class="tab-pane" id="envirnment" role="tabpanel" aria-labelledby="envirnment-tab">
						<div class="row">
							<div class="col-6">
								<h4>General</h4>
								<hr>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">1. Permissible Late Arrival</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[per_late_arrival]', 'id' => 'input-per_late_arrival', 'placeholder'=>'','value' => set_value('per_late_arrival', isset($envirnment['per_late_arrival'])?$envirnment['per_late_arrival']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">2. Permissible Early Dep</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[per_early_dep]', 'id' => 'input-per_early_dep', 'placeholder'=>'','value' => set_value('per_early_dep', isset($envirnment['per_early_dep'])?$envirnment['per_early_dep']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">3. Duplicate Check Min</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[duplicate_check_min]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('duplicate_check_min',isset($envirnment['duplicate_check_min'])?$envirnment['duplicate_check_min']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">4. Duplicate Canteen Check Min</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[duplicate_canteen_check_min]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('duplicate_canteen_check_min',isset($envirnment['duplicate_canteen_check_min'])?$envirnment['duplicate_canteen_check_min']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">5. Max Late Arrival Duration</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[max_late_arrival_duration]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('max_late_arrival_duration',isset($envirnment['max_late_arrival_duration'])?$envirnment['max_late_arrival_duration']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">6. Max. Early Departure Duration</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[max_early_departue_duration]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('max_early_departue_duration',isset($envirnment['max_early_departue_duration'])?$envirnment['max_early_departue_duration']:''))); ?>
									</div>
								</div>

								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">7. Permissible Half calculation</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[perm_half]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('perm_half',isset($envirnment['perm_half'])?$envirnment['perm_half']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">8. No of Late/Early days for Half</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[no_of_late_half]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('no_of_late_half',isset($envirnment['no_of_late_half'])?$envirnment['no_of_late_half']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">9. No of Late/Early days for Next Consecutive Half Day</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[no_of_late_chalf]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('no_of_late_chalf',isset($envirnment['no_of_late_chalf'])?$envirnment['no_of_late_chalf']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">10. Half Day Marking</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[half_day_marking]', array('yes'=>'Yes','no'=>'No'), set_value('half_day_marking',isset($envirnment['half_day_marking'])?$envirnment['half_day_marking']:''),array('class'=>'form-control select2','id' => 'half_day_marking')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">11. Present Marking Duration</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[present_marking_dur]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('present_marking_dur',isset($envirnment['present_marking_dur'])?$envirnment['present_marking_dur']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">12. Max. Absent HRS. for Half Day</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[max_absent_hrs]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('max_absent_hrs',isset($envirnment['max_absent_hrs'])?$envirnment['max_absent_hrs']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">13. Min. Absent Hrs. For Half Day</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[min_absent_hrs]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('min_absent_hrs',isset($envirnment['min_absent_hrs'])?$envirnment['min_absent_hrs']:''))); ?>
									</div>
								</div>


								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">14. MaxWrkDuration</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[max_work_dur]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('max_work_dur',isset($envirnment['max_work_dur'])?$envirnment['max_work_dur']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">15. Auto Shift Allowed</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[auto_shift_allowed]', array('yes'=>'Yes','no'=>'No'), set_value('auto_shift_allowed',isset($envirnment['auto_shift_allowed'])?$envirnment['auto_shift_allowed']:''),array('class'=>'form-control select2','id' => 'auto_shift_allowed')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">16. Permis Early Min AutoShift</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[perm_early_min_ashift]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('perm_early_min_ashift',isset($envirnment['perm_early_min_ashift'])?$envirnment['perm_early_min_ashift']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">17. Permis Late Min AutoShift</label>
									<div class="col-sm-5">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'envirnment[perm_late_min_ashift]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('perm_late_min_ashift',isset($envirnment['perm_late_min_ashift'])?$envirnment['perm_late_min_ashift']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">18. Auto Absent Allowed</label>
									<div class="col-sm-5">
										<?php  echo form_dropdown('envirnment[auto_absent_allowed]', array('yes'=>'Yes','no'=>'No'), set_value('auto_absent_allowed',isset($envirnment['auto_absent_allowed'])?$envirnment['auto_absent_allowed']:''),array('class'=>'form-control select2','id' => 'auto_absent_allowed')); ?>
									</div>
								</div>

								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">19. Status For MIS</label>
									<div class="col-sm-5">
										<?php  echo form_dropdown('envirnment[status_for_mis]', array('yes'=>'Yes','no'=>'No'), set_value('status_for_mis',isset($envirnment['status_for_mis'])?$envirnment['status_for_mis']:''),array('class'=>'form-control select2','id' => 'status_for_mis')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">20. IsPresentonWOPresent</label>
									<div class="col-sm-5">
										<?php  echo form_dropdown('envirnment[wo_present]', array('yes'=>'Yes','no'=>'No'), set_value('wo_present',isset($envirnment['wo_present'])?$envirnment['wo_present']:''),array('class'=>'form-control select2','id' => 'wo_present')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">21. IsPresentOnHldPresent</label>
									<div class="col-sm-5">
										<?php  echo form_dropdown('envirnment[hld_present]', array('yes'=>'Yes','no'=>'No'), set_value('hld_present',isset($envirnment['hld_present'])?$envirnment['hld_present']:''),array('class'=>'form-control select2','id' => 'hld_present')); ?>
									</div>
								</div>




								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">22. COF consider days of limit required user difine</label>
									<div class="col-sm-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[cof_limit]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('cof_limit',isset($envirnment['cof_limit'])?$envirnment['cof_limit']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">23. National Holiday /Festival Holiday Consider as OT</label>
									<div class="col-sm-5">
										<?php  echo form_dropdown('envirnment[nh_fh_ot]', array('yes'=>'Yes','no'=>'No'), set_value('nh_fh_ot',isset($envirnment['nh_fh_ot'])?$envirnment['nh_fh_ot']:''),array('class'=>'form-control select2','id' => 'nh_fh_ot')); ?>
									</div>
								</div>


							</div>
							<div class="col-6">
								<h4>Payroll</h4>
								<hr>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">24. Week Off include in attendance</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[week_off_include]', array('yes'=>'Yes','no'=>'No'), set_value('week_off_include',isset($envirnment['week_off_include'])?$envirnment['week_off_include']:''),array('class'=>'form-control select2','id' => 'week_off_include')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">24. Transaction month wise Close</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[trans_month_close]', array('yes'=>'Yes','no'=>'No'), set_value('trans_month_close',isset($envirnment['trans_month_close'])?$envirnment['trans_month_close']:''),array('class'=>'form-control select2','id' => 'trans_month_close')); ?>
									</div>
								</div>

								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">25. OT Round off</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[ot_round_off]', array('yes'=>'Yes','no'=>'No'), set_value('ot_round_off',isset($envirnment['ot_round_off'])?$envirnment['ot_round_off']:''),array('class'=>'form-control select2','id' => 'ot_round_off')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">26. Salary Calculate with out Weekly off</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[salary_without_wo]', array('yes'=>'Yes','no'=>'No'), set_value('salary_without_wo',isset($envirnment['salary_without_wo'])?$envirnment['salary_without_wo']:''),array('class'=>'form-control select2','id' => 'salary_without_wo')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">27. OverTime Allowed</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[overtime_allowed]', array('yes'=>'Yes','no'=>'No'), set_value('overtime_allowed',isset($envirnment['overtime_allowed'])?$envirnment['overtime_allowed']:''),array('class'=>'form-control select2','id' => 'overtime_allowed')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">28. Out Work Allowed</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[out_work_allowed]', array('yes'=>'Yes','no'=>'No'), set_value('out_work_allowed',isset($envirnment['out_work_allowed'])?$envirnment['out_work_allowed']:''),array('class'=>'form-control select2','id' => 'out_work_allowed')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">29. Out Work Exclude from working Hours</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[out_work_exclude]', array('yes'=>'Yes','no'=>'No'), set_value('out_work_exclude',isset($envirnment['out_work_exclude'])?$envirnment['out_work_exclude']:''),array('class'=>'form-control select2','id' => 'out_work_exclude')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">30. Leave aplied range limit required</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[leave_limit]', array('yes'=>'Yes','no'=>'No'), set_value('leave_limit',isset($envirnment['leave_limit'])?$envirnment['leave_limit']:''),array('class'=>'form-control select2','id' => 'leave_limit')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">31. CL period limit(New Joiner)</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[cl_period]', array('yes'=>'Yes','no'=>'No'), set_value('cl_period',isset($envirnment['cl_period'])?$envirnment['cl_period']:''),array('class'=>'form-control select2','id' => 'cl_period')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">32. CL cannot be combined with any other kind of leave</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[cl_combine]', array('yes'=>'Yes','no'=>'No'), set_value('cl_combine',isset($envirnment['cl_combine'])?$envirnment['cl_combine']:''),array('class'=>'form-control select2','id' => 'cl_combine')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">33. Employee can avail 2 casual leave each </label>
									<div class="col-sm-5">
										<?php  echo form_dropdown('envirnment[emp_leave]', array('yes'=>'Yes','no'=>'No'), set_value('emp_leave',isset($envirnment['emp_leave'])?$envirnment['emp_leave']:''),array('class'=>'form-control select2','id' => 'emp_leave')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">34. Holiday leave period </label>
									<div class="col-sm-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[holiday_leave]', 'id' => 'input-email1', 'placeholder'=>'','value' => set_value('holiday_leave',isset($envirnment['holiday_leave'])?$envirnment['holiday_leave']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">35. CL can be split in ½ day and taken accordingly</label>
									<div class="col-sm-5">
										<?php  echo form_dropdown('envirnment[cl_split]', array('yes'=>'Yes','no'=>'No'), set_value('cl_split',isset($envirnment['cl_split'])?$envirnment['cl_split']:''),array('class'=>'form-control select2','id' => 'cl_split')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">36. CL not availed by the end of a calendar year shall lapse</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[cl_calendar]', array('yes'=>'Yes','no'=>'No'), set_value('cl_calendar',isset($envirnment['cl_calendar'])?$envirnment['cl_calendar']:''),array('class'=>'form-control select2','id' => 'cl_calendar')); ?>
									</div>
								</div>

								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">37. SL is applicable from the date of joining. Allowed during probation period also</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[sl_joining]', array('yes'=>'Yes','no'=>'No'), set_value('sl_joining',isset($envirnment['sl_joining'])?$envirnment['sl_joining']:''),array('class'=>'form-control select2','id' => 'sl_joining')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">38. Sites available</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[site_available]', array('yes'=>'Yes','no'=>'No'), set_value('site_available',isset($envirnment['site_available'])?$envirnment['site_available']:''),array('class'=>'form-control select2','id' => 'site_available')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">39. Salary Calculate for CL Limit</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[salary_cl]', 'id' => 'salary_cl', 'placeholder'=>'','value' => set_value('salary_cl',isset($envirnment['salary_cl'])?$envirnment['salary_cl']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">40. Salary Calculate for EL Limit</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[salary_el]', 'id' => 'salary_el', 'placeholder'=>'','value' => set_value('salary_el',isset($envirnment['salary_el'])?$envirnment['salary_el']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">41. Salary Calculate for SL Limit</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[salary_sl]', 'id' => 'salary_sl', 'placeholder'=>'','value' => set_value('salary_sl',isset($envirnment['salary_sl'])?$envirnment['salary_sl']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">42. Absent Day Allow for Present</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[absent_allow]', 'id' => 'absent_allow', 'placeholder'=>'','value' => set_value('absent_allow',isset($envirnment['absent_allow'])?$envirnment['absent_allow']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">43. Half Day Allow for Present(multiple of two half=1 day)</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[half_allow]', 'id' => 'half_allow', 'placeholder'=>'','value' => set_value('half_allow',isset($envirnment['half_allow'])?$envirnment['half_allow']:''))); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="payroll" role="tabpanel" aria-labelledby="payroll-tab">
						<div class="row">
							<div class="col-6">
								<h4>PF Details</h4>
								<hr>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Pension Fund (FPF) %</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[fpf]', 'id' => 'input-fpf', 'placeholder'=>'','value' => set_value('fpf', isset($envirnment['fpf'])?$envirnment['fpf']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Provided Fund (PF) %</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[pf]', 'id' => 'input-pf', 'placeholder'=>'','value' => set_value('pf', isset($envirnment['pf'])?$envirnment['pf']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">PF Limit</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[pf_limit]', 'id' => 'input-pf_limit', 'placeholder'=>'','value' => set_value('pf_limit',isset($envirnment['pf_limit'])?$envirnment['pf_limit']:''))); ?>
									</div>
								</div>
								<h4>ESI Details</h4>
								<hr>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">ESI (Employee) %</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[esi_emp]', 'id' => 'input-esi_emp', 'placeholder'=>'','value' => set_value('esi_emp', isset($envirnment['esi_emp'])?$envirnment['esi_emp']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">ESI (Employer) % </label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[esi_empr]', 'id' => 'input-esi_empr', 'placeholder'=>'','value' => set_value('esi_empr', isset($envirnment['esi_empr'])?$envirnment['esi_empr']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">ESI Limit</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[esi_limit]', 'id' => 'input-esi_limit', 'placeholder'=>'','value' => set_value('esi_limit',isset($envirnment['esi_limit'])?$envirnment['esi_limit']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Deduct ESI on OT</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[deduct_esi_ot]', array('yes'=>'Yes','no'=>'No'), set_value('deduct_esi_ot',isset($envirnment['deduct_esi_ot'])?$envirnment['deduct_esi_ot']:''),array('class'=>'form-control select2','id' => 'deduct_esi_ot')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Show OT Values</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[show_ot]', array('yes'=>'Yes','no'=>'No'), set_value('show_ot',isset($envirnment['show_ot'])?$envirnment['show_ot']:''),array('class'=>'form-control select2','id' => 'show_ot')); ?>
									</div>
								</div>

								<h4>Bonus Details</h4>
								<hr>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Bonus Applicable upto limit</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[bonus_limit]', 'id' => 'input-bonus_limit', 'placeholder'=>'','value' => set_value('bonus_limit',isset($envirnment['bonus_limit'])?$envirnment['bonus_limit']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Bonus Rate</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[bonus_rate]', 'id' => 'input-bonus_rate', 'placeholder'=>'','value' => set_value('bonus_rate',isset($envirnment['bonus_rate'])?$envirnment['bonus_rate']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Bonus Max Payble Limit</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[bonus_max_limit]', 'id' => 'input-bonus_max_limit', 'placeholder'=>'','value' => set_value('bonus_max_limit',isset($envirnment['bonus_max_limit'])?$envirnment['bonus_max_limit']:''))); ?>
									</div>
								</div>

							</div>
							<div class="col-6">
								<h4>Exgratia</h4>
								<hr>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Exgratia Rate</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[exgratia_rate]', 'id' => 'input-exgratia_rate', 'placeholder'=>'','value' => set_value('exgratia_rate',isset($envirnment['exgratia_rate'])?$envirnment['exgratia_rate']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Exgratia Max Payble Limit</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[exgratia_limit]', 'id' => 'input-exgratia_limit', 'placeholder'=>'','value' => set_value('exgratia_limit',isset($envirnment['exgratia_limit'])?$envirnment['exgratia_limit']:''))); ?>
									</div>
								</div>
								<h4>Other Details</h4>
								<hr>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Calculate LTA</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[lta]', array('yes'=>'Yes','no'=>'No'), set_value('lta',isset($envirnment['lta'])?$envirnment['lta']:''),array('class'=>'form-control select2','id' => 'lta')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Calculate LIC Gratuity</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[lic]', array('yes'=>'Yes','no'=>'No'), set_value('lic',isset($envirnment['lic'])?$envirnment['lic']:''),array('class'=>'form-control select2','id' => 'lic')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">No of Months for Gratuity Period</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[gratuity_period]', 'id' => 'input-gratuity_period', 'placeholder'=>'','value' => set_value('gratuity_period',isset($envirnment['gratuity_period'])?$envirnment['gratuity_period']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Professional Tax</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[pt]', array('yes'=>'Yes','no'=>'No'), set_value('pt',isset($envirnment['pt'])?$envirnment['pt']:''),array('class'=>'form-control select2','id' => 'pt')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Print Leave Balance on Pay Slip</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[print_leave]', array('yes'=>'Yes','no'=>'No'), set_value('print_leave',isset($envirnment['print_leave'])?$envirnment['print_leave']:''),array('class'=>'form-control select2','id' => 'print_leave')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Print Salary Arrear on Pay slip</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[print_arrear]', array('yes'=>'Yes','no'=>'No'), set_value('print_arrear',isset($envirnment['print_arrear'])?$envirnment['print_arrear']:''),array('class'=>'form-control select2','id' => 'print_arrear')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Do you Print Full & Final settlement employees in pay slip and salary register</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[print_full_final]', array('yes'=>'Yes','no'=>'No'), set_value('print_full_final',isset($envirnment['print_full_final'])?$envirnment['print_full_final']:''),array('class'=>'form-control select2','id' => 'print_full_final')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Salary Slip Footer</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[salary_footer]', 'id' => 'input-salary_footer', 'placeholder'=>'','value' => set_value('salary_footer',isset($envirnment['salary_footer'])?$envirnment['salary_footer']:''))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Mode of Salary</label>
									<div class="col-md-5">
										<?php  echo form_dropdown('envirnment[salary_mode]', array('yes'=>'Yes','no'=>'No'), set_value('salary_mode',isset($envirnment['salary_mode'])?$envirnment['salary_mode']:''),array('class'=>'form-control select2','id' => 'salary_mode')); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-sm-7 control-label" for="input-phone">Line / Page</label>
									<div class="col-md-5">
										<?php echo form_input(array('class'=>'form-control','name' => 'envirnment[line_page]', 'id' => 'input-line_page', 'placeholder'=>'','value' => set_value('line_page',isset($envirnment['line_page'])?$envirnment['line_page']:''))); ?>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="tab-pane" id="penalty" role="tabpanel" aria-labelledby="penalty-tab">
						<table id="penalty_configs" class="table table-striped table-bordered table-hover">
							<thead>
								<tr class="">
									<th class="text-left">Type</th>
									<th class="text-left">Rule Name</th>
									<th class="text-left">Absent Days</th>
									<th class="text-left">Penalty Day</th>
									<th class="text-right">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $pconfig_row = 0; ?>
								<?php if(isset($envirnment['penalty_config'])) foreach ($envirnment['penalty_config'] as $penalty_config) { ?>
								<tr id="pconfig-row<?php echo $pconfig_row; ?>">
									<td class="text-left">
										<select name="envirnment[penalty_config][<?php echo $pconfig_row; ?>][rule_type]" class="form-control select2">
										<?php foreach($rule_types as $key=>$rule_type){
											if($key==$penalty_config['rule_type']){?>
											<option value="<?php echo $key ;?>" selected="selected"><?php echo $rule_type;?></option>
											<?php } else {?>
											<option value="<?php echo $key ;?>"><?php echo $rule_type ;?></option>
											<?}?>
										<?}?>
										</select>
									</td>
									<td class="text-left">
										<input type="text" name="envirnment[penalty_config][<?php echo $pconfig_row; ?>][rule_name]" value="<?php echo  $penalty_config['rule_name']; ?>" placeholder="Rule Name" class="form-control" />
									</td>
									<td class="text-left">
										<input type="text" name="envirnment[penalty_config][<?php echo $pconfig_row; ?>][absent_days]" value="<?php echo  $penalty_config['absent_days']; ?>" placeholder="Absent Days" class="form-control" />
									</td>
									<td class="text-left">
										<input type="text" name="envirnment[penalty_config][<?php echo $pconfig_row; ?>][penalty_days]" value="<?php echo  $penalty_config['penalty_days']; ?>" placeholder="Penalty Days" class="form-control" />
									</td>

									<td class="text-right"><button type="button" onclick="$('#pconfig-row<?php echo $pconfig_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus"></i></button></td>
								</tr>
								<?php $pconfig_row++; ?>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4"></td>
									<td class="text-right"><button type="button" onclick="addPenalty();" data-toggle="tooltip" title="Penalty Add" class="btn btn-primary"><i class="fa fa-plus"></i></button></td>
								</tr>
							</tfoot>
						</table>
					</div>

					<div class="tab-pane" id="geofence" role="tabpanel" aria-labelledby="geofence-tab">
						<div class="row">
							<div class="col-3">
								<div class="form-group required">
									<label class="control-label" for="input-phone">Office Address</label>
									<?php echo form_input(array('class'=>'form-control','name' => 'oaddress', 'id' => 'oaddress', 'placeholder'=>'','value' => set_value('oaddress', ''))); ?>
								</div>
								<div class="form-group required">
									<label class="control-label" for="input-phone">Latitude</label>
									<?php echo form_input(array('class'=>'form-control','name' => 'latitude', 'id' => 'latitude', 'placeholder'=>'','value' => set_value('latitude', $latitude))); ?>
								</div>
								<div class="form-group required">
									<label class="control-label" for="input-phone">Longitude</label>
									<?php echo form_input(array('class'=>'form-control','name' => 'longitude', 'id' => 'longitude', 'placeholder'=>'','value' => set_value('longitude', $longitude))); ?>
								</div>
								<div class="form-group required">
									<label class="control-label" for="input-phone">Office Boundary</label>
									<input type="hidden" value="<?php echo $boundary_point;?>" id="boundary_point" name="boundary_point"/>
									<?php echo form_textarea(array('class'=>'form-control','name' => 'boundary', 'id' => 'boundary', 'placeholder'=>'','rows' => 4,'cols' => 40,'value' => set_value('boundary', $boundary))); ?>
								</div>
							</div>
							<div class="col-9">
								<div id="map-canvas" style="height:500px;border: 2px solid rgb(83, 188, 157);"></div>
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
var all_overlays = [];
var coordinates	=	[];
var polygons = [];
var polylines = [];
var map;
function initialize() {
	var latlng = new google.maps.LatLng(20.2960587,85.8245398);
    map = new google.maps.Map(document.getElementById('map-canvas'), {
		center: latlng,
		zoom: 13,
		mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var marker = new google.maps.Marker({
      map: map,
      position: latlng,
      draggable: true,
      anchorPoint: new google.maps.Point(0, -29)
   });
    var input = document.getElementById('oaddress');
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	var geocoder = new google.maps.Geocoder();
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();
    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }

        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        bindDataToForm(place.formatted_address,place.geometry.location.lat(),place.geometry.location.lng());
        infowindow.setContent(place.formatted_address);
        infowindow.open(map, marker);

    });
    // this function will work on marker move event into map
    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {
              bindDataToForm(results[0].formatted_address,marker.getPosition().lat(),marker.getPosition().lng());
              infowindow.setContent(results[0].formatted_address);
              infowindow.open(map, marker);
          }
        }
        });
    });
	var selectedShape;

	var drawingManager = new google.maps.drawing.DrawingManager({
		drawingMode: google.maps.drawing.OverlayType.POLYGON,
		drawingControl: true,
		drawingControlOptions: {
			position: google.maps.ControlPosition.TOP_CENTER,
			drawingModes: [
				google.maps.drawing.OverlayType.POLYGON,
			]
		},

		polygonOptions: {
			clickable: true,
			draggable: true,
			editable: true,
			fillColor: '#ffff00',
			fillOpacity: 1,

		},


	});

	function clearSelection() {
		if (selectedShape) {
			selectedShape.setEditable(false);
			selectedShape = null;
		}
	}

	function setSelection(shape) {
		clearSelection();
		selectedShape = shape;
		console.log(shape);
		shape.setEditable(true);
		ctype=shape.type;

		vertices = selectedShape.getPath(); // MVCArray
		console.log('v',vertices);
		var pointsArray = [];
		 //list of polyline points
		for (var i =0; i < vertices.getLength(); i++) {
			var xy = vertices.getAt(i); //LatLang for a polyline
			var item = { "lat" : xy.lat(), "lng":xy.lng()};
			pointsArray.push(item);
		}

		htmlStr = "";
		var checkLast = vertices.getLength() - 1;
		for (var i = 0; i < vertices.getLength(); i++) {

			if (i == checkLast) {
				htmlStr += vertices.getAt(i).toUrlValue(10);
			} else {
				htmlStr += vertices.getAt(i).toUrlValue(10) + "\n";
			}
			//Use this one instead if you want to get rid of the wrap > new google.maps.LatLng(),
			//htmlStr += "" + myPolygon.getPath().getAt(i).toUrlValue(5);
		}
		document.getElementById('boundary').innerHTML = htmlStr;
		console.log(pointsArray);

		cpoints={};
		cpoints[ctype] = pointsArray;

		coordinates.push(cpoints);
		document.getElementById('boundary_point').value = JSON.stringify(coordinates);

		google.maps.event.addListener(selectedShape.getPath(), 'insert_at', getPolygonCoords(shape));
		google.maps.event.addListener(selectedShape.getPath(), 'set_at', getPolygonCoords(shape));
	}

	function deleteSelectedShape() {
		if (selectedShape) {
			selectedShape.setMap(null);
		}
	}

	function deleteAllShape() {
		for (var i = 0; i < all_overlays.length; i++) {
			all_overlays[i].overlay.setMap(null);
		}
		all_overlays = [];
	}

	function CenterControl(controlDiv, map) {

		// Set CSS for the control border.
		var controlUI = document.createElement('div');
		controlUI.style.backgroundColor = '#fff';
		controlUI.style.border = '2px solid #fff';
		controlUI.style.borderRadius = '3px';
		controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
		controlUI.style.cursor = 'pointer';
		controlUI.style.marginBottom = '22px';
		controlUI.style.textAlign = 'center';
		controlUI.title = 'Select to delete the shape';
		controlDiv.appendChild(controlUI);

		// Set CSS for the control interior.
		var controlText = document.createElement('div');
		controlText.style.color = 'rgb(25,25,25)';
		controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
		controlText.style.fontSize = '16px';
		controlText.style.lineHeight = '38px';
		controlText.style.paddingLeft = '5px';
		controlText.style.paddingRight = '5px';
		controlText.innerHTML = 'Delete Selected Area';
		controlUI.appendChild(controlText);

		// Setup the click event listeners: simply set the map to Chicago.
		controlUI.addEventListener('click', function() {
			deleteSelectedShape();
		});

	}
    drawingManager.setMap(map);
	var getPolygonCoords = function(newShape) {
		console.log("We are one");
		var len = newShape.getPath().getLength();
		for (var i = 0; i < len; i++) {
			console.log(newShape.getPath().getAt(i).toUrlValue(6));
		}
	};

	google.maps.event.addListener(drawingManager, 'polygoncomplete', function(event) {

		event.getPath().getLength();
		google.maps.event.addListener(event.getPath(), 'insert_at', function() {
			var len = event.getPath().getLength();
			for (var i = 0; i < len; i++) {
				console.log(event.getPath().getAt(i).toUrlValue(5));
			}
		});
		google.maps.event.addListener(event.getPath(), 'set_at', function() {
			var len = event.getPath().getLength();
			for (var i = 0; i < len; i++) {
				console.log(event.getPath().getAt(i).toUrlValue(5));
			}
		});
	});

	google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {

		all_overlays.push(event);
		if (event.type !== google.maps.drawing.OverlayType.MARKER) {
			drawingManager.setDrawingMode(null);
			//Write code to select the newly selected object.

			var newShape = event.overlay;
			newShape.type = event.type;
			google.maps.event.addListener(newShape, 'click', function() {
				setSelection(newShape);
			});

			setSelection(newShape);
		}
	});




	var centerControlDiv = document.createElement('div');
	var centerControl = new CenterControl(centerControlDiv, map);

	centerControlDiv.index = 1;
	map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(centerControlDiv);



	var bounds = new google.maps.LatLngBounds();

	var geofences=<?php echo json_encode($points);?>;
	console.log(geofences);

	arr = [];

	for (var j = 0; j < geofences.length; j++) {
	  arr.push(new google.maps.LatLng(
		parseFloat(geofences[j][0]),
		parseFloat(geofences[j][1])
	  ));

	  bounds.extend(arr[arr.length - 1])
	}

	polygons.push(new google.maps.Polygon({
	  paths: arr,
	  strokeColor: '#FF0000',
	  strokeOpacity: 0.8,
	  strokeWeight: 2,
	  fillColor: '#FF0000',
	  fillOpacity: 0.35,
	  editable:true
	}));
	polygons[polygons.length - 1].setMap(map);


	  // });
	map.fitBounds(bounds);

	//google.maps.event.addListener(polygons.getPath(), "insert_at", getPolygonCoords);
    //google.maps.event.addListener(polygons.getPath(), "set_at", getPolygonCoords);
}
function bindDataToForm(address,lat,lng){
   document.getElementById('latitude').value = lat;
   document.getElementById('longitude').value = lng;
}

$(function(){
	$('a[href="#geofence"]').one('click', function() {
		initialize();
	});
})


//google.maps.event.addDomListener(window, 'load', initialize);




//--></script>
<script type="text/javascript"><!--
var pconfig_row = <?php echo $pconfig_row; ?>;

function addPenalty() {

   html = '<tr id="pconfig-row' +pconfig_row + '">';
	html += '  	<td class="text-left">';
	html += '		<select name="envirnment[penalty_config][' + pconfig_row + '][rule_type]" class="form-control select2">';
					<?php foreach($rule_types as $key=>$rule_type){?>
	html += '			<option value="<?php echo $key ;?>"><?php echo $rule_type;?></option>';
					<?}?>
	html += '		</select>';
	html += '	</td>';
	html += '  	<td class="text-left">';
	html += '		<input type="text" name="envirnment[penalty_config][' + pconfig_row + '][rule_name]" value="" placeholder="Rule name" class="form-control" />';
	html += '	</td>';
	html += '	<td class="text-left">';
	html += '		<input type="text" name="envirnment[penalty_config][' + pconfig_row + '][absent_days]" value="" placeholder="Absent Days" class="form-control" />';
	html += '	</td>';
	html += '	<td class="text-left">';
	html += '		<input type="text" name="envirnment[penalty_config][' + pconfig_row + '][penalty_days]" value="" placeholder="Penalty Days" class="form-control" />';
	html += '	</td>';
	html += '  	<td class="text-right"><button type="button" onclick="$(\'#pconfig-row' + pconfig_row  + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#penalty_configs tbody').append(html);
	pconfig_row++;
}
//--></script>

<?php js_end(); ?>