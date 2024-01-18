<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<?php if(!$bdate){?>
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger" form="form-canteen"><i class="fa fa-save"></i></button>
					<?}?>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart(null,array('class' => 'form-horizontal', 'id' => 'form-canteen','role'=>'form')); ?>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-firstname">Employee Name</label>
							<div class="col-md-8">
								<?php 
									$disabled=$edit?"disabled='disabled'":"";
									//printr($extra);
									echo form_dropdown('user_id', option_array_values($employees, 'id', 'name'), set_value('user_id', $user_id),"id='user_id' class='form-control select2' $disabled"); ?>
								<?php if($edit){?>
								<input name="user_id" type="hidden" value="<?=$user_id?>"/>
								<?}?>
								<?php echo form_error('user_id', '<div class="text-danger">', '</div>'); ?>
								
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-sm-4 control-label" for="input-phone">No of Guest</label>
							<div class="col-md-8">
								<?php 
								$disable= $emp?'disableddiv':'';
								echo form_input(array('class'=>"form-control $disable",'name' => 'no_of_guest', 'id' => 'input-guest', 'placeholder'=>'No of Guest','value' => set_value('no_of_guest', $no_of_guest))); ?>
							</div>
						</div>
						<div class="form-group row required">
							<label class="col-sm-4 control-label" for="input-phone">Guest Name</label>
							<div class="col-md-8">
								<?php 
								$disable= $this->user->getEmployeeId()?'disableddiv':'';
								echo form_input(array('class'=>"form-control $disable",'name' => 'guest_name', 'id' => 'input-guest-name', 'placeholder'=>'Guest Name','value' => set_value('guest_name', $guest_name))); ?>
							</div>
						</div>
						<!--<div class="form-group row required">
							<label class="col-md-2 control-label" for="input-code">Type</label>
							<div class="col-md-6">
								<?php foreach($typelist as $key=>$_type){?>
								<div class="form-check">
								  <input class="form-check-input icheck <?=$edit && $_type['disable']?'disableddiv':'';?>" type="checkbox" name="types[]" value="<?=$_type['name']?>" <?=in_array($_type['name'],$types)?"checked='checked'":''?> >
								  <label class="form-check-label" >
									<?=$_type['name']?>
								  </label>
								</div>
								<?}?>
								<?php echo form_error('types[]', '<div class="text-danger">', '</div>'); ?>
							
							</div>
						</div>-->
						
						
						<div class="form-group row required">
							<label class="col-sm-4 control-label" for="input-phone">Order Date</label>
							<div class="col-md-8">
								<div class="input-group" >
									<?php 
										$disable= $edit?'disableddiv':'';
										echo form_input(array('class'=>"form-control datepickerrange $disable",'name' => 'from_date', 'id' => 'input-date1', 'placeholder'=>'Order Date','value' => set_value('from_date', $from_date))); ?>
									<div class="input-group-append">
										<span class="input-group-text"><i class="fa fa-calendar"></i><span class="count"></span></span>
									</div>
								</div>
								<?php echo form_error('from_date', '<div class="text-danger">', '</div>'); ?>
							
							</div>
						</div>
							
						<div class="form-group row required">
							<label class="col-md-4 control-label" for="input-email">Status</label>
							<div class="col-md-8">
								<?php  echo form_dropdown('status', array('1'=>'Confirm'), set_value('status',$status),array('class'=>'form-control select2','id' => 'status')); ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group row required">
							<label class="col-sm-2 control-label" for="input-phone">Type</label>
							<div class="col-md-10">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Food Type</th>
											<th>Action</th>
											<th>Quantity</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Breakfast</td>
											<td>
											<input class="type <?=$dbreakfast?'disableddiv':'';?>" type="checkbox" name="types[]" value="breakfast" <?=in_array('breakfast',$types)?"checked='checked'":""?> ></td>
											<td>
												<?php 
												//$readonly=$dbreakfast?"readonly='readonly'":"";
												$disable= ($dbreakfast || $emp)?'disableddiv':'';
												echo form_input(array('class'=>"form-control $disable",'name' => 'breakfast', 'id' => 'breakfast', 'placeholder'=>'','value' => set_value('breakfast', $breakfast))); ?>
												<?php echo form_error('breakfast', '<div class="text-danger">', '</div>'); ?>
											</td>
										</tr>
										<tr>
											<td>Lunch</td>
											<td><input class="type <?=$dlunch?'disableddiv':'';?>" type="checkbox" name="types[]" value="lunch" <?=in_array('lunch',$types)?"checked='checked'":""?>></td>
											
											<td>
												<?php 
												$disable= ($dlunch || $emp)?'disableddiv':'';
												echo form_input(array('class'=>"form-control $disable",'name' => 'lunch', 'id' => 'lunch', 'placeholder'=>'','value' => set_value('lunch', $lunch))); ?>
												<?php echo form_error('lunch', '<div class="text-danger">', '</div>'); ?>
											</td>
										</tr>
										<tr>
											<td>Snacks</td>
											<td><input class="type <?=($dsnack)?'disableddiv':'';?>" type="checkbox" name="types[]" value="snack" <?=in_array('snack',$types)?"checked='checked'":""?>></td>
											
											<td>
												<?php 
												$disable= ($dsnack || $emp)?'disableddiv':'';
												echo form_input(array('class'=>"form-control $disable",'name' => 'snack','id' => 'snack', 'placeholder'=>'','value' => set_value('snack', $snack))); ?>
												<?php echo form_error('snack', '<div class="text-danger">', '</div>'); ?>
											</td>
										</tr>
										<tr>
											<td>Dinner</td>
											<td><input class="type <?=($ddinner )?'disableddiv':'';?>" type="checkbox" name="types[]" value="dinner" <?=in_array('dinner',$types)?"checked='checked'":""?>></td>
											
											<td>
												<?php
												$disable= ($ddinner || $emp)?'disableddiv':'';
												echo form_input(array('class'=>"form-control $disable",'name' => 'dinner','id' => 'dinner', 'placeholder'=>'','value' => set_value('dinner', $dinner))); ?>
												<?php echo form_error('dinner', '<div class="text-danger">', '</div>'); ?>
											</td>
										</tr>
									</tbody>
								</table>
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
		$(".datepickerrange").datepicker({
			format: 'dd-mm-yyyy' ,
			multidate:<?php echo $edit?'false':'true';?>,
			startDate: new Date()
		}).on('changeDate', function(e) {
			$(this).parent().find('.input-group-text .count').text(' ' + e.dates.length);
		});
		<?php if($edit && $bdate){?>
		$("#form-canteen :input").prop("disabled", true);
		<?}?>
		$('.type').click(function() {
			val=$(this).val();
			if ($(this).is(':checked')) {
				
				$("#"+val).val(1);
			}else{
				$("#"+val).val(0);
			}
		  });
		
	})
//--></script>
<?php js_end(); ?>