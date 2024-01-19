<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Canteen Timing Setup</h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger" form="form-canteen"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart(null,array('class' => 'form-horizontal', 'id' => 'form-canteen','role'=>'form')); ?>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Breakfast</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control timepicker','name' => 'canteen_breakfast', 'id' => 'input-breakfast', 'placeholder'=>'Breakfast','value' => set_value('canteen_breakfast', $canteen_breakfast))); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Lunch</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control timepicker','name' => 'canteen_lunch', 'id' => 'input-lunch', 'placeholder'=>'Lunch','value' => set_value('canteen_lunch', $canteen_lunch))); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Dinner</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control timepicker','name' => 'canteen_dinner', 'id' => 'input-dinner', 'placeholder'=>'Dinner','value' => set_value('canteen_dinner', $canteen_dinner))); ?>
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-md-2 control-label" for="input-firstname">Snack</label>
					<div class="col-md-10">
						<?php echo form_input(array('class'=>'form-control timepicker','name' => 'canteen_snack', 'id' => 'input-snack', 'placeholder'=>'Snack','value' => set_value('canteen_snack', $canteen_snack))); ?>
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
		$(".datepickerrange").datepicker({
			multidate: true
		});
	})
//--></script>
<?php js_end(); ?>