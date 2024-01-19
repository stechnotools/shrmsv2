<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-site"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-site','role'=>'form')); ?>
				

				<div class="row">
					<div class="col-3">
						<div class="form-group required">
							<label class="control-label" for="input-name">Site Name</label>
							<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>'Site Name','value' => set_value('name', $name))); ?>
							<?php echo $validation->showerror('name', 'aio_error'); ?>
						</div>
					</div>
					<div class="col-3">
						<div class="form-group required">
							<label class="control-label" for="input-name">Site Code</label>
							<?php echo form_input(array('class'=>'form-control','name' => 'code', 'id' => 'name', 'placeholder'=>'Site Code','value' => set_value('code', $code))); ?>
							<?php echo $validation->showerror('code', 'aio_error'); ?>
						</div>
					</div>
					<div class="col-3">
						<div class="form-group required">
							<label class="control-label" for="input-name">Site Address</label>
							<?php echo form_input(array('class'=>'form-control','name' => 'address', 'id' => 'name', 'placeholder'=>'Site Address','value' => set_value('address', $address))); ?>
							<?php echo $validation->showerror('address', 'aio_error'); ?>
						</div>
					</div>
					<div class="col-3">
						<div class="form-group">
							<label class="control-label" for="input-status">Status</label>
							
							<?php echo form_dropdown('status', array('1'=>'Enabled', '0' => 'Disabled'), set_value('status', $status), 'id=\'status\' class=\'form-control\'')?>
							
						</div>
					</div>
				</div>
				<table id="site_salaries" class="table table-striped table-bordered table-hover">
					<thead>
						<tr class="">
							<th class="text-left">Designation</th>
							<th class="text-left">Type</th>
							<th class="text-left">Salary</th>
							<th class="text-right">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $salary_row = 0; ?>
						<?php foreach ($site_salaries as $site_salary) { ?>
						<tr id="salary-row<?php echo $salary_row; ?>">
							<td class="text-left">
								<select name="site_salary[<?php echo $salary_row; ?>][designation_id]" class="form-control select2">
								<?php foreach($designations as $designation){
									if($designation->id==$site_salary['designation_id']){
								?>
									<option value="<?php echo $designation->id ;?>" selected="selected"><?php echo $designation->name ;?></option>
									<?php } else {?>
									<option value="<?php echo $designation->id ;?>"><?php echo $designation->name ;?></option>
									<?}?>
								<?}?>
								</select>
							</td>
							<td class="text-left">
								<select name="site_salary[<?php echo $salary_row; ?>][type]" class="form-control">
                                  <?php if ($site_salary['type']=="day") { ?>
                                  <option value="day" selected="selected">Day</option>
                                  <option value="month">Month</option>
                                  <?php } else { ?>
                                  <option value="day" >Day</option>
                                  <option value="month" selected="selected">Month</option><?php } ?>
                                </select>
							</td>
							<td class="text-left">
								<input name="site_salary[<?php echo $salary_row; ?>][salary]" class=" form-control" value="<?php echo $site_salary['salary']; ?>" />
							</td>
							<td class="text-right"><button type="button" onclick="$('#salary-row<?php echo $salary_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus"></i></button></td>
						</tr>
						<?php $salary_row++; ?>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3"></td>
							<td class="text-right"><button type="button" onclick="addSalary();" data-toggle="tooltip" title="Salary Add" class="btn btn-primary"><i class="fa fa-plus"></i></button></td>
						</tr>
					</tfoot>
				</table>
				
				<?php echo form_close(); ?>
			</div> <!-- panel-body -->
		</div> <!-- panel -->
	</div> <!-- col -->
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
var salary_row = <?php echo $salary_row; ?>;

function addSalary() {
	
   html = '<tr id="salary-row' +salary_row + '">';
	html += '  	<td class="text-left">';
	html += '		<select name="site_salary[' + salary_row + '][designation_id]" class="form-control select2">';
					<?php foreach($designations as $designation){?>
	html += '			<option value="<?php echo $designation->id ;?>"><?php echo addslashes($designation->name) ;?></option>';	
					<?}?>
	html += '		</select>';
	html += '	</td>';
	html += '  	<td class="text-left">';
	html += '		<select name="site_salary[' + salary_row + '][type]"  class="form-control">';
	html += '	      <option value="day">Day</option>';
	html += '	      <option value="month">Month</option>';
	html += '	   </select>';
	html += '	</td>';
	html += '  	<td class="text-left">';
	html += '		<input type="text" name="site_salary[' + salary_row + '][salary]" value="" placeholder="Salary" class="form-control" />';
	html += '	</td>';
	html += '  	<td class="text-right"><button type="button" onclick="$(\'#salary-row' + salary_row  + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#site_salaries tbody').append(html);
	$('.select2').select2({
		
	});
	salary_row++;
}
function removeSalary(j)
{
	$(".salary-row"+j).remove();
	var instance="site_salary["+j+"][description]";
	var editor = CKEDITOR.instances[instance];
	if (editor) { editor.destroy(true); }
	//$('textarea.description').ckeditor(thin_config);
	
}
//--></script>
<?php js_end(); ?>