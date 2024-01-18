<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-shift"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-shift','role'=>'form')); ?>
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="control-label">Shift Code</label>
							<?php echo form_input(array('class'=>'form-control','name' => 'code', 'id' => 'code', 'placeholder'=>"code",'value' => set_value('code', $code))); ?>
							<?php echo $validation->showError('name', 'aio_error'); ?>
						</div>
						<div class="form-group">
							<label class="control-label">Shift Start Time </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'shift_start_time', 'id' => 'start_time', 'placeholder'=>"Shift Start Time ",'value' => set_value('shift_start_time', $shift_start_time))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Shift End Time </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'shift_end_time', 'id' => 'shift_end_time', 'placeholder'=>"Shift End Time ",'value' => set_value('shift_end_time', $shift_end_time))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Shift Hours </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'shift_hours', 'id' => 'shift_hours', 'placeholder'=>"Shift Hours ",'value' => set_value('shift_hours', $shift_hours))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label">Overtime Deduct After </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'ot_deduct_after', 'id' => 'ot_deduct_after', 'placeholder'=>"Overtime Deduct After",'value' => set_value('ot_deduct_after', $ot_deduct_after))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Overtime Start After </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'ot_start_after', 'id' => 'ot_start_after', 'placeholder'=>"Overtime Start After",'value' => set_value('ot_start_after', $ot_start_after))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Overtime Deduction </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'ot_deduction', 'id' => 'ot_deduction', 'placeholder'=>"Overtime Deduction",'value' => set_value('ot_deduction', $ot_deduction))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Present Marking Duration </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'present_making_duration', 'id' => 'present_making_duration', 'placeholder'=>"Present Marking Duration",'value' => set_value('present_making_duration', $present_making_duration))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Min. Absent Hrs for Half Day </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'min_absent_hrs_halfday', 'id' => 'min_absent_hrs_halfday', 'placeholder'=>"Min. Absent Hrs for Half Day ",'value' => set_value('min_absent_hrs_halfday', $min_absent_hrs_halfday))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>

					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label class="control-label">Branch</label>
							<div class="input-group">
								<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(0=>'Select Branch')), set_value('branch_id', $branch_id),"id='branch_id' class='form-control select2'"); ?>
								<?php echo $validation->showerror('branch_id', 'aio_error'); ?>
								<div class="input-group-append">
									<button class="input-group-text btn btn-primary addselect" data-url="<?= admin_url('branch/add') ?>" data-target='branch_id' type="button"><i class="mdi mdi-plus"></i></button>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Lunch Start Time </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'lunch_starttime', 'id' => 'lunch_starttime', 'placeholder'=>"Lunch Start Time ",'value' => set_value('lunch_starttime', $lunch_starttime))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Lunch Duration </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'lunch_duration', 'id' => 'lunch_duration', 'placeholder'=>"Lunch duration ",'value' => set_value('lunch_duration', $lunch_duration))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Lunch End Time </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'lunch_endtime', 'id' => 'lunch_endtime', 'placeholder'=>"Lunch End Time ",'value' => set_value('lunch_endtime', $lunch_endtime))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Lunch Deduction </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'lunch_deduction', 'id' => 'lunch_deduction', 'placeholder'=>"Lunch Deduction",'value' => set_value('lunch_deduction', $lunch_deduction))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Shift Position </label>
							<?php echo form_dropdown('shift_position', $spositions, set_value('shift_position', $shift_position),"id='shift_position' class='form-control select2'"); ?>
						</div>

						<div class="form-group">
							<label class="control-label">Flexible Lunch Deduction</label>
							<div class="checkbox checkbox-primary mb-4">
								<?php echo form_checkbox(array('name' => 'flexible_lunch_deduction', 'value' => 1,'checked' => ($flexible_lunch_deduction  ? true : false))); ?>
								<label for="checkbox2">
									Check me out !
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Max Absent Hrs for Half Day </label>
							<div class="input-group mb-3">
								<?php echo form_input(array('class'=>'form-control timepicker','name' => 'max_absent_hrs_halfday', 'id' => 'max_absent_hrs_halfday', 'placeholder'=>"Max Absent Hrs for Half Day",'value' => set_value('max_absent_hrs_halfday', $max_absent_hrs_halfday))); ?>
								<div class="input-group-append">
									<span class="input-group-text"><i class="mdi mdi-alarm"></i></span>
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
		$("[name='shift_end_time']").timepicker(
			{showMeridian: false,
			showSeconds: true,
			defaultTime:"0:00"
			}
			).on('changeTime.timepicker', function(e) {
			var valuestart = $("input[name='shift_start_time']").val();
			var valuestop = $("input[name='shift_end_time']").val();
			//console.log(valuestart);
			var startTime=moment(valuestart, "HH:mm");
			//console.log(startTime);
			var endTime=moment(valuestop, "HH:mm");

			if (endTime.isBefore(startTime)) {
				endTime.add(1, 'day'); // Add one day to the end time
			}
			var duration = moment.duration(endTime.diff(startTime));
			var hours = parseInt(duration.asHours());
			var minutes = parseInt(duration.asMinutes())-hours*60;
			var diff= hours + ':'+ minutes;
			$('#shift_hours').timepicker('setTime', diff);

		});

		$("[name='lunch_duration']").timepicker({showMeridian: false,
			showSeconds: true,
			defaultTime:"0:00"
			}).on('changeTime.timepicker', function(e) {
			var valuestart = $("input[name='lunch_starttime']").val();
			var valuestop = $("input[name='lunch_duration']").val();
			//console.log(valuestart);
			var startTime=moment(valuestart, "HH:mm");
			//console.log(startTime);
			var endTime=moment(valuestop, "HH:mm");
			var duration = moment.duration(endTime.add(startTime));
			var hours = parseInt(duration.asHours());
			var minutes = parseInt(duration.asMinutes())-hours*60;
			var diff= hours + ':'+ minutes;
			$('#lunch_endtime').timepicker('setTime', diff);

		});


	});
//--></script>
<?php js_end(); ?>