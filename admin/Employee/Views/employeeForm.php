<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-employee"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-employee','role'=>'form')); ?>
				<ul class="nav nav-tabs tabs-bordered nav-justified" role="tablist">
					<li class="nav-item tab">
						<a class="nav-link active" id="office-tab" data-toggle="tab" href="#office" role="tab" aria-controls="general" aria-selected="false">
							<span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
							<span class="d-none d-sm-block">Office Details</span>
						</a>
					</li>
					<li class="nav-item tab">
						<a class="nav-link" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="social" aria-selected="false">
							<span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
							<span class="d-none d-sm-block">Personal Details</span>
						</a>
					</li>
					<li class="nav-item tab">
						<a class="nav-link" id="time-tab" data-toggle="tab" href="#time" role="tab" aria-controls="account" aria-selected="false">
							<span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
							<span class="d-none d-sm-block">Time Office Policy</span>
						</a>
					</li>
					<li class="nav-item tab">
						<a class="nav-link" id="shift-tab" data-toggle="tab" href="#shift" role="tab" aria-controls="subscription" aria-selected="false">
							<span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
							<span class="d-none d-sm-block">Shift/WO Policy</span>
						</a>
					</li>
					<li class="nav-item tab">
						<a class="nav-link" id="mobile-tab" data-toggle="tab" href="#mobile" role="tab" aria-controls="subscription" aria-selected="false">
							<span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
							<span class="d-none d-sm-block">Mobile Setup</span>
						</a>
					</li>
				</ul>
				<div class="block-content tab-content">
					<div class="tab-pane show active" id="office" role="tabpanel" aria-labelledby="office-tab">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-firstname">Card No(Company ID)</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'card_no', 'id' => 'card_no', 'placeholder'=>"Card No",'value' => set_value('card_no', $card_no))); ?>
										<?php echo $validation->showerror('card_no', 'aio_error'); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Paycode(Machine emp ID)</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'paycode', 'id' => 'input-email', 'placeholder'=>'Paycode','value' => set_value('paycode', $paycode))); ?>
										<?php echo $validation->showerror('paycode', 'aio_error'); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Safty Pass No.</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'safety_pass_no', 'id' => 'input-email', 'placeholder'=>'Safty Pass No.','value' => set_value('safety_pass_no', $safety_pass_no))); ?>
										<?php echo $validation->showerror('safety_pass_no', 'aio_error'); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Employee Type</label>
									<div class="col-md-8">
										<?php echo form_dropdown('employee_type', array("general"=>"General","field"=>"Field"), set_value('employee_type', $employee_type),"id='employee_type' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-employee_name">Employee Name</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'employee_name', 'id' => 'employee_name', 'placeholder'=>"Employee Name",'value' => set_value('employee_name', $employee_name))); ?>
										<?php echo $validation->showerror('employee_name', 'aio_error'); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Guardian Name</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'guardian_name', 'id' => 'input-guardian_name', 'placeholder'=>'Guardian Name','value' => set_value('guardian_name', $guardian_name))); ?>
										<?php echo $validation->showerror('guardian_name', 'aio_error'); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Relationship</label>
									<div class="col-md-8">
										<?php  echo form_dropdown('relationship', array('father'=>'Father','mother' => 'Mother','brother'=>'Brother'), set_value('relationship',$relationship),array('class'=>'form-control select2','id' => 'relationship')); ?>
										<?php echo $validation->showerror('relationship', 'aio_error'); ?>
									</div>
								</div>

								<hr/>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Branch</label>
									<div class="col-md-8">
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
									<label class="col-md-4 control-label" for="input-email">Department</label>
									<div class="col-md-8">
										<div class="input-group">
											<?php echo form_dropdown('department_id', option_array_value($departments, 'id', 'name',array(''=>'Select Department')), set_value('department_id', $department_id),"id='department' class='form-control select2'"); ?>
											<div class="input-group-append">
												<button class="input-group-text btn btn-primary addselect" data-url="<?= admin_url('department/add') ?>" data-target='department_id' type="button"><i class="mdi mdi-plus"></i></button>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Category</label>
									<div class="col-md-8">
										<?php echo form_dropdown('category_id', option_array_value($categories, 'id', 'name',array(''=>'Select Category')), set_value('category_id', $category_id),"id='category_id' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Section</label>
									<div class="col-md-8">
										<?php echo form_dropdown('section_id', option_array_value($sections, 'id', 'name',array(''=>'Select Section')), set_value('section_id', $section_id),"id='section_id' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Grade</label>
									<div class="col-md-8">
										<?php echo form_dropdown('grade_id', option_array_value($grades, 'id', 'name',array(''=>'Select Grade')), set_value('grade_id', $grade_id),"id='grade_id' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Site Location</label>
									<div class="col-md-8">
										<?php echo form_dropdown('location_id', option_array_value($locations, 'id', 'name',array(''=>'Select site location')), set_value('location_id', $location_id),"id='location_id' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Designation</label>
									<div class="col-md-8">
										<?php echo form_dropdown('designation_id', option_array_value($designations, 'id', 'name',array(''=>'Select Designation')), set_value('designation_id', $designation_id),"id='designation_id' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">HOD</label>
									<div class="col-md-8">
										<?php echo form_dropdown('hod_id', option_array_value($hods, 'id', 'name',array(''=>'Select hod')), set_value('hod_id', $hod_id),"id='hod_id' class='form-control select2'"); ?>
									</div>
								</div>

							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-image">Image</label>
									<div class="col-sm-5">
										<div class="card-box bg-sky">
											<div class="media justify-content-center">
												<div class="avatar-md bg-primary rounded-circle">
													<img src="<?php echo $thumb_image; ?>" alt="" class=" img-fluid" id="thumb_image">
													<input type="hidden" name="image" value="<?php echo $image?>" id="image" />
												</div>
											</div>
											<hr class="mt-4">
											<ul class="text-center list-inline m-0">
												<li class="list-inline-item">
													<a class="btn btn-sm btn-rounded btn-alt-primary min-width-75" onclick="image_upload('image','thumb_image')">Browse</a>
												</li>
												<li class="list-inline-item">
													<a class="btn btn-sm btn-rounded btn-alt-danger min-width-75" onclick="$('#thumb_image').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');">Clear</a>
												</li>

											</ul>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-image">Signature</label>
									<div class="col-sm-5">
										<div class="card-box bg-sky">
											<div class="media justify-content-center">
												<div class="avatar-md bg-primary rounded-circle">
													<img src="<?php echo $thumb_sign; ?>" alt="" class=" img-fluid" id="thumb_sign">
													<input type="hidden" name="signature" value="<?php echo $signature?>" id="image" />
												</div>
											</div>
											<hr class="mt-4">
											<ul class="text-center list-inline m-0">
												<li class="list-inline-item">
													<a class="btn btn-sm btn-rounded btn-alt-primary min-width-75" onclick="image_upload('signature','thumb_sign')">Browse</a>
												</li>
												<li class="list-inline-item">
													<a class="btn btn-sm btn-rounded btn-alt-danger min-width-75" onclick="$('#thumb_sign').attr('src', '<?php echo $no_image; ?>'); $('#signature').attr('value', '');">Clear</a>
												</li>

											</ul>
										</div>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">PF No</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'pf_no', 'id' => 'input-pf_no', 'placeholder'=>'PF No','value' => set_value('pf_no', $pf_no))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">ESI No.</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'esi', 'id' => 'input-esi', 'placeholder'=>'ESi NO','value' => set_value('esi', $esi))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">PAN No.</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'pan', 'id' => 'input-pan', 'placeholder'=>'PAN NO','value' => set_value('pan', $pan))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Leaving Date</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control datepicker','name' => 'leaving_date', 'id' => 'input-email', 'placeholder'=>'Leaving date','value' => set_value('leaving_date', $leaving_date))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Reason of Leaving</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'reason', 'id' => 'input-reason', 'placeholder'=>'Reason','value' => set_value('reason', $reason))); ?>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-4 control-label" for="input-email">Employee Status</label>
									<div class="col-md-8">
										<?php  echo form_dropdown('enabled', array('1'=>'Active','0' => 'Deactive'), set_value('enabled',$enabled),array('class'=>'form-control select2','id' => 'enabled')); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="personal" role="tabpanel" aria-labelledby="personal-tab">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-website">Date of Birth</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control datepicker','name' => 'dob', 'id' => 'input-dob', 'placeholder'=>'Date of Birth','value' => set_value('dob', date('d-m-Y',strtotime($dob))) )); ?>
										<?php echo $validation->showerror('dob', 'aio_error'); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-facebook">Date of Join</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control datepicker','name' => 'doj', 'id' => 'input-doj', 'placeholder'=>'Date of Join','value' => set_value('doj', date('d-m-Y',strtotime($doj))))); ?>
										<?php echo $validation->showerror('doj', 'aio_error'); ?>

									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-twitter">Married</label>
									<div class="col-md-8">
										<?php  echo form_dropdown('married', array('yes'=>'Yes','no' => 'No'), set_value('married',$married),array('class'=>'form-control select2','id' => 'married')); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-youtube">Blood Group</label>
									<div class="col-md-8">
										<?php echo form_dropdown('blood_group', $blood_groups, set_value('blood_group', $blood_group),"id='blood_group' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Qualification</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'qualification', 'id' => 'input-qualification', 'placeholder'=>'qualification','value' => set_value('qualification', $qualification))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Experience</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'experience', 'id' => 'input-experience', 'placeholder'=>'experience','value' => set_value('experience', $experience))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Gender</label>
									<div class="col-md-8">
										<?php echo form_dropdown('sex', array("male"=>"Male","female"=>"Female","other"=>"Other"), set_value('sex', $sex),"id='sex' class='form-control select2'"); ?>
										<?php echo $validation->showerror('sex', 'aio_error'); ?>

									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Email</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'email', 'id' => 'input-email', 'placeholder'=>'email','value' => set_value('email', $email))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Bus Route</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'bus_route', 'id' => 'input-bus_route', 'placeholder'=>'Bus Route','value' => set_value('bus_route', $bus_route))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Vechicle No.</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'vehicle', 'id' => 'input-vehicle', 'placeholder'=>'Vechicle No','value' => set_value('vehicle', $vehicle))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">EID Number</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'eid_no', 'id' => 'input-eid_no', 'placeholder'=>'EID Number','value' => set_value('eid_no', $eid_no))); ?>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Permanet Address</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'permanent', 'id' => 'input-permanent', 'placeholder'=>'Permanet Address','value' => set_value('permanent', $permanent))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Pincode</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'pincode', 'id' => 'input-pincode', 'placeholder'=>'pincode','value' => set_value('pincode', $pincode))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Telephone</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'telephone', 'id' => 'input-telephone', 'placeholder'=>'telephone','value' => set_value('telephone', $telephone))); ?>
										<?php echo $validation->showerror('telephone', 'aio_error'); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Temporary Address</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'temporary', 'id' => 'input-temporary', 'placeholder'=>'temporary','value' => set_value('temporary', $temporary))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Pincode</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'temp_pin', 'id' => 'input-temp_pin', 'placeholder'=>'Pincode','value' => set_value('temp_pin', $temp_pin))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Telephone</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'temp_tel', 'id' => 'input-temp_tel', 'placeholder'=>'Telephone','value' => set_value('temp_tel', $temp_tel))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Bank Name</label>
									<div class="col-md-8">
										<?php echo form_dropdown('bank_id', option_array_value($banks, 'id', array('name','ifsc'),array(''=>'Select Bank')), set_value('bank_id', $bank_id),"id='bank_id' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Bank Account No</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'bank_account', 'id' => 'input-bank', 'placeholder'=>'bank Account No','value' => set_value('bank_account', $bank_account))); ?>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">EID Time</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'eid_time', 'id' => 'input-eid_time', 'placeholder'=>'EID Time','value' => set_value('eid_time', $eid_time))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">EID Name</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'eid_name', 'id' => 'input-eid_name', 'placeholder'=>'EID Name','value' => set_value('eid_name', $eid_name))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">AADHAAR</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'aadhaar', 'id' => 'input-aadhaar', 'placeholder'=>'aadhaar','value' => set_value('aadhaar', $aadhaar))); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="time" role="tabpanel" aria-labelledby="time-tab">
						<div class="row">
							<div class="col-md-5">
								<div class="form-group row">
									<label class="col-sm-6 control-label" for="input-website">Perm Issible Late Arrival</label>
									<div class="col-md-6">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'perm_late', 'id' => 'input-perm_late', 'placeholder'=>'Perm Issible Late Arrival','value' => set_value('perm_late', $perm_late))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-6 control-label" for="input-facebook">Perm Issible Early Dep</label>
									<div class="col-md-6">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'perm_early', 'id' => 'input-perm_early', 'placeholder'=>'Perm Issible Early Dep','value' => set_value('perm_early', $perm_early))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-6 control-label" for="input-twitter">Max Working Hrs In a day</label>
									<div class="col-md-6">
										<?php echo form_input(array('class'=>'form-control timepicker','name' => 'max_work', 'id' => 'input-max_work', 'placeholder'=>'Max Working Hrs In a day','value' => set_value('max_work', $max_work))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-6 control-label" for="input-youtube">Out pass Duration</label>
									<div class="col-md-6">
										<?php echo form_input(array('class'=>'form-control','name' => 'out_dura', 'id' => 'input-out_dura', 'placeholder'=>'Out pass Duration','value' => set_value('out_dura', $out_dura))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-6 control-label" for="input-vimeo">Out pass frequency</label>
									<div class="col-md-6">
										<?php echo form_input(array('class'=>'form-control','name' => 'out_freq', 'id' => 'input-out_freq', 'placeholder'=>'Out pass frequency','value' => set_value('out_freq', $out_freq))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-6 control-label" for="input-vimeo">Round Time clock Working</label>
									<div class="col-md-6">
										<div class="checkbox checkbox-primary checkbox-single">
                                            <input type="checkbox" id="clock_work" name="clock_work" value="1" <?php echo $clock_work?"checked='checked'":"";?> aria-label="Single checkbox Two">
                                            <label></label>
                                        </div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-6 control-label" for="input-vimeo">Consider Time loss</label>
									<div class="col-md-6">
										<div class="checkbox checkbox-primary checkbox-single">
                                            <input type="checkbox" id="time_loss" name="time_loss" value="1" <?php echo $time_loss?"checked='checked'":"";?> aria-label="Single checkbox Two">
                                            <label></label>
                                        </div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-6 control-label" for="input-vimeo">Halfday markting</label>
									<div class="col-md-6">
										<div class="checkbox checkbox-primary checkbox-single">
                                            <input type="checkbox" id="half_markting" name="half_markting" value="1" <?php echo $half_markting?"checked='checked'":"";?> aria-label="Single checkbox Two">
                                            <label></label>
                                        </div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-6 control-label" for="input-vimeo">Short Leave Markting</label>
									<div class="col-md-6">
										<div class="checkbox checkbox-primary checkbox-single">
                                            <input type="checkbox" id="short_markting" name="short_markting" value="1" <?php echo $short_markting?"checked='checked'":"";?> aria-label="Single checkbox Two">
                                            <label></label>
                                        </div>
									</div>
								</div>
								<div class="form-group row required">
									<label class="col-md-6 control-label" for="input-email">Device Access</label>
									<div class="col-md-6">
										<?php echo form_dropdown('device_access', array("clm"=>"CLM","savior"=>"Savior",'both'=>'Both'), set_value('device_access', $device_access),"id='device_access' class='form-control select2'"); ?>
									</div>
								</div>
							</div>
							<div class="col-md-7">
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Punches Required In a day</label>
									<div class="col-md-8">
										<div class="radio radio-success">
											<input type="radio" name="punches" id="punches0" value="0" <?php echo $punches==0?"checked='checked'":"";?>>
											<label for="punches0">
												No Punch
											</label>
                                        </div>
										<div class="radio radio-success">
											<input type="radio" name="punches" id="punches1" value="1" <?php echo $punches==1?"checked='checked'":"";?>>
											<label for="punches1">
												Single Punch Only
											</label>
                                        </div>
										<div class="radio radio-success">
											<input type="radio" name="punches" id="punches2" value="2" <?php echo $punches==2?"checked='checked'":"";?>>
											<label for="punches2">
											Two Punches
											</label>
                                        </div>
										<div class="radio radio-success">
											<input type="radio" name="punches" id="punches4" value="4" <?php echo $punches==4?"checked='checked'":"";?>>
											<label for="punches4">
											Four Punches
											</label>
                                        </div>
										<div class="radio radio-success">
											<input type="radio" name="punches" id="punches5" value="-1" <?php echo $punches==-1?"checked='checked'":"";?>>
											<label for="punches5">
											Multiple Punches
											</label>
                                        </div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Single Punch Only</label>
									<div class="col-md-8">
										<div class="radio radio-info form-check-inline">
                                            <input type="radio" id="single_punch1" value="fixed" name="single_punch" <?php echo $single_punch=='fixed'?"checked='checked'":"";?>>
                                            <label for="single_punch1"> Fixed Outtime </label>
                                        </div>
										<div class="radio radio-info form-check-inline">
                                            <input type="radio" id="single_punch2" value="over" name="single_punch" <?php echo $single_punch=='over'?"checked='checked'":"";?>>
                                            <label for="single_punch2"> Over write</label>
                                        </div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Overtime Applicable</label>
									<div class="col-md-8">
										<div class="checkbox checkbox-primary checkbox-single">
                                            <input type="checkbox" id="overtime_app" name="overtime_app" value="1" <?php echo $overtime_app?"checked='checked'":"";?> aria-label="Single checkbox Two">
                                            <label></label>
                                        </div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Overstay Applicable</label>
									<div class="col-md-8">
										<div class="checkbox checkbox-primary checkbox-single">
                                            <input type="checkbox" id="overstay_app" name="overstay_app" value="1" <?php echo $overstay_app?"checked='checked'":"";?> aria-label="Single checkbox Two">
                                            <label></label>
                                        </div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">HalfDayon Late/Early</label>
									<div class="col-md-8">
										<div class="checkbox checkbox-primary checkbox-single">
                                            <input type="checkbox" id="halfday_late" name="halfday_late" value="1" <?php echo $halfday_late?"checked='checked'":"";?> aria-label="Single checkbox Two">
                                            <label></label>
                                        </div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Late Utility Applicable</label>
									<div class="col-md-8">
										<div class="checkbox checkbox-primary checkbox-single">
                                            <input type="checkbox" id="late_utility" name="late_utility" value="1" <?php echo $late_utility?"checked='checked'":"";?> aria-label="Single checkbox Two">
                                            <label></label>
                                        </div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Overstay minus</label>
									<div class="col-md-8">
										<div class="checkbox checkbox-primary checkbox-single">
                                            <input type="checkbox" id="overstay_min" name="overstay_min" value="1" <?php echo $overstay_min?"checked='checked'":"";?> aria-label="Single checkbox Two">
                                            <label></label>
                                        </div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Rate Per Hour</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'rate_hour', 'id' => 'input-rate_hour', 'placeholder'=>'Rate Per Hour','value' => set_value('rate_hour', $rate_hour))); ?>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">half Late</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'half_late', 'id' => 'input-half_late', 'placeholder'=>'half Late','value' => set_value('half_late', $half_late))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">half Early</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'half_early', 'id' => 'input-half_early', 'placeholder'=>'half Early','value' => set_value('half_early', $half_early))); ?>
									</div>
								</div>
							</div>
						</div>
						</div>
					<div class="tab-pane" id="shift" role="tabpanel" aria-labelledby="shift-tab">
					<div class="row">
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-website">Shift Type</label>
									<div class="col-md-8">
										<?php echo form_dropdown('shift_type', array("f"=>"Fixed","r"=>"Rotational","i"=>"Ignore"), set_value('shift_type', $shift_type),"id='shift_type' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="fix-div">
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-facebook">Shift</label>
										<div class="col-md-8">
											<?php echo form_dropdown('shift_id', option_array_value($shifts, 'id', 'code',array(''=>'Select Shift')), set_value('shift_id', $shift_id),"id='shift_id' class='form-control select2'"); ?>
										</div>
									</div>
								</div>
								<div class="rotational-div">
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-twitter">Shift Pattern</label>
										<div class="col-md-8">
											<?php echo form_dropdown('shift_pattern', $shift_patterns, set_value('shift_pattern', $shift_pattern),"data-placeholder='Select pattern' id='shift_pattern' class='form-control select2'"); ?>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-vimeo">Run Auto Shift</label>
										<div class="col-md-8">
											<div class="checkbox checkbox-primary checkbox-single">
												<input type="checkbox" id="run_auto_shift" name="run_auto_shift" value="1" <?php echo $run_auto_shift?"checked='checked'":"";?> aria-label="Single checkbox Two">
												<label></label>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-vimeo">Shift Remaining Days</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'shift_remain', 'id' => 'input-shift_remain', 'placeholder'=>'Shift Remaining Days','value' => set_value('shift_remain', $shift_remain))); ?>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 control-label" for="input-vimeo">Shift change after How Many Days</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'shift_change', 'id' => 'input-shift_change', 'placeholder'=>'Shift change after How Many Days','value' => set_value('shift_change', $shift_change))); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">first weekly Off</label>
									<div class="col-md-8">
										<?php echo form_dropdown('first_week', $weeks, set_value('first_week', $first_week),"id='first_week' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Second weekly Off</label>
									<div class="col-md-8">
										<?php echo form_dropdown('second_week', $weeks, set_value('second_week', $second_week),"id='second_week' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Second Wo Type</label>
									<div class="col-md-8">
										<?php echo form_dropdown('second_wo', array('f'=>'Full','h'=>'Half'), set_value('second_wo', $second_wo),"id='second_wo' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Halfday Shift</label>
									<div class="col-md-8">
										<?php echo form_dropdown('half_day', array(), set_value('half_day', $half_day),"id='half_day' class='form-control select2'"); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-vimeo">Second Weekly Off Days</label>
									<div class="col-md-8">
										<div class="checkbox checkbox-primary">
                                            <input id="second_week_off1" type="checkbox" value="1" name="second_week_off[]" <?php echo in_array(1,$second_week_off)?"checked='checked'":"";?>>
                                            <label for="second_week_off1">
                                                I
                                            </label>
                                        </div>
										<div class="checkbox checkbox-primary">
                                            <input id="second_week_off2" type="checkbox" value="2" name="second_week_off[]" <?php echo in_array(2,$second_week_off)?"checked='checked'":"";?>>
                                            <label for="second_week_off2">
                                                II
                                            </label>
                                        </div>
										<div class="checkbox checkbox-primary">
                                            <input id="second_week_off3" type="checkbox" value="3" name="second_week_off[]" <?php echo in_array(3,$second_week_off)?"checked='checked'":"";?>>
                                            <label for="second_week_off3">
                                                III
                                            </label>
                                        </div>
										<div class="checkbox checkbox-primary">
                                            <input id="second_week_off4" type="checkbox" value="4" name="second_week_off[]" <?php echo in_array(4,$second_week_off)?"checked='checked'":"";?>>
                                            <label for="second_week_off4">
											IV
                                            </label>
                                        </div>
										<div class="checkbox checkbox-primary">
                                            <input id="second_week_off5" type="checkbox" value="5" name="second_week_off[]" <?php echo in_array(5,$second_week_off)?"checked='checked'":"";?>>
                                            <label for="second_week_off5">
											V
                                            </label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="mobile" role="tabpanel" aria-labelledby="shift-tab">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-website">Mobile Number</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'mobile', 'id' => 'input-mobile', 'placeholder'=>'Mobile Number','value' => set_value('mobile', $mobile))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-facebook">Mobile Device Id/IMEI No</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'imei', 'id' => 'input-imei', 'placeholder'=>'Mobile Device Id/IMEI No','value' => set_value('imei', $imei))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-twitter">Mobile Make</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'mobile_make', 'id' => 'input-mobilemake', 'placeholder'=>'Mobile Make','value' => set_value('mobile_make', $mobile_make))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-twitter">Geofence</label>
									<div class="col-md-8">
										<?php echo form_dropdown('geofence', array("1"=>"Yes","0"=>"No"), set_value('geofence', $geofence),"id='geofence' class='form-control select2'"); ?>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-twitter">Mobile Model</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'mobile_model', 'id' => 'input-mobilemodel', 'placeholder'=>'Mobile Model','value' => set_value('mobile_model', $mobile_model))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-twitter">OS Name</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'mobile_os', 'id' => 'input-mobileos', 'placeholder'=>'Mobile OS','value' => set_value('mobile_os', $mobile_os))); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 control-label" for="input-twitter">Mobile Operator</label>
									<div class="col-md-8">
										<?php echo form_input(array('class'=>'form-control','name' => 'mobile_operator', 'id' => 'input-mobileop', 'placeholder'=>'Mobile operator','value' => set_value('mobile_operator', $mobile_operator))); ?>
									</div>
								</div>
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
	$(function(){
		$(".fix-div").hide();
  		$(".rotational-div").hide();
		// Show the default selected div on page load
		var defaultOption = $("#shift_type").val();
		if (defaultOption === "f") {
			$(".fix-div").show();
		} else if (defaultOption === "r") {
			$(".rotational-div").show();
		} else if (defaultOption === "i") {
			$(".rotational-div").hide();
			$(".fix-div").hide();
		}
		$("#shift_type").on("change", function() {
			var selectedOption = $(this).val();
			if (selectedOption === "f") {
				$(".fix-div").show();
				$(".rotational-div").hide();
			} else if (selectedOption === "r") {
				$(".fix-div").hide();
				$(".rotational-div").show();
			}else if (selectedOption === "i") {
				$(".fix-div").hide();
				$(".rotational-div").hide();
			}
		});
	})
	function image_upload1(field, thumb) {
		window.KCFinder = {
			callBack: function(url) {
				window.KCFinder = null;
				var lastSlash = url.lastIndexOf("uploads/");
				var fileName=url.substring(lastSlash+8);
				url=url.replace("images", ".thumbs/images");
				$('#'+thumb).attr('src', url);
				$('#'+field).attr('value', fileName);
				$.colorbox.close();
			}
		};
		$.colorbox({href:BASE_URL+"plugins/kcfinder/browse.php?type=images",width:"850px", height:"550px", iframe:true,title:"Image Manager"});
	};
	function image_upload(field, thumb) {
		CKFinder.modal( {
			chooseFiles: true,
			width: 800,
			height: 600,
			onInit: function( finder ) {
				console.log(finder);
				finder.on( 'files:choose', function( evt ) {
					var file = evt.data.files.first();
					url=file.getUrl();
					var lastSlash = url.lastIndexOf("uploads/");
					var fileName=url.substring(lastSlash+8);
					//url=url.replace("images", ".thumbs/images");
					$('#'+thumb).attr('src', url);
					$('#'+field).attr('value', fileName);
				} );
				finder.on( 'file:choose:resizedImage', function( evt ) {
					var output = document.getElementById( field );
					output.value = evt.data.resizedUrl;
					console.log(evt.data.resizedUrl);
				} );
			}
		});
	};


//--></script>
<?php js_end(); ?>