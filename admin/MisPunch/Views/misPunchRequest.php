<div class="row punch-row" >
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?=$heading_title?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="" class="btn btn-danger" form="form-punch"><i class="fa fa-save"></i></button>
					<a href="" data-toggle="tooltip" title="" class="btn btn-primary"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart('',array('class' => 'form-horizontal', 'id' => 'form-punch','role'=>'form')); ?>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputEmail1">Punch date</label>
							<?php echo form_input(array('class'=>'form-control datepicker','name' => 'punch_date', 'id' => 'punch-punchdate', 'placeholder'=>"punch date",'value' => set_value('punch_date', date("d-m-Y",strtotime($punch_date))))); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputEmail1">Shift</label>
							<?php echo form_dropdown('shift_id', option_array_value($shifts, 'id', 'code',array(''=>'Select Shift')), set_value('shift_id', $shift_id),"id='shift_id' class='form-control select2'"); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputEmail1">CLM IN</label>
							<?php echo form_input(array('class'=>'form-control timepicker','name' => 'clm_in', 'id' => 'punch-clm-in', 'placeholder'=>"CLM IN",'value' => set_value('clm_in', $clm_in))); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputEmail1">CLM OUT</label>
							<?php echo form_input(array('class'=>'form-control timepicker','name' => 'clm_out', 'id' => 'punch-clm-out', 'placeholder'=>"CLM OUT",'value' => set_value('clm_out', $clm_out))); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputEmail1">Savior IN</label>
							<?php echo form_input(array('class'=>'form-control timepicker','name' => 'savior_in', 'id' => 'punch-savior-in', 'placeholder'=>"Savior OUT",'value' => set_value('savior_in', $savior_in))); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputEmail1">Savior OUT</label>
							<?php echo form_input(array('class'=>'form-control timepicker','name' => 'savior_out', 'id' => 'punch-savior-out', 'placeholder'=>"Savior OUT",'value' => set_value('savior_out', $savior_out))); ?>
						</div>
					</div>
				</div>

			</div> <!-- panel-body -->
		</div> <!-- panel -->
	</div> <!-- col -->
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
$(function() {
	$('.datepicker').datepicker({
		format: 'dd-mm-yyyy',
		autoclose: true
	});
	$('.timepicker').timepicker({
		showMeridian: false,
		showSeconds: true,
		defaultTime:"0:00"
	});

});
</script>
<?php js_end(); ?>