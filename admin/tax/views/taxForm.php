<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger" form="form-tax"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart(null,array('class' => 'form-horizontal', 'id' => 'form-tax','role'=>'form')); ?>
				<div class="form-group row required">
					<label class="col-sm-2 control-label" for="input-name">Tax Name</label>
					<div class="col-sm-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>'Tax Name','value' => set_value('name', $name))); ?>
						<?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>		
					</div>
				</div>
				<div class="form-group row required">
					<label class="col-sm-2 control-label" for="input-name">Description</label>
					<div class="col-sm-10">
						<?php echo form_input(array('class'=>'form-control','name' => 'description', 'id' => 'description', 'placeholder'=>'Description','value' => set_value('description', $description))); ?>
						<?php echo form_error('description', '<div class="text-danger">', '</div>'); ?>		
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group row required">
							<label class="col-sm-4 control-label" for="input-name">From Date</label>
							<div class="col-sm-8">
								<?php echo form_input(array('class'=>'form-control datepicker','name' => 'from_date', 'id' => 'from_date', 'placeholder'=>'From Date','value' => set_value('from_date', $from_date))); ?>
								<?php echo form_error('from_date', '<div class="text-danger">', '</div>'); ?>		
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group row required">
							<label class="col-sm-4 control-label" for="input-name">To Date</label>
							<div class="col-sm-8">
								<?php echo form_input(array('class'=>'form-control datepicker','name' => 'to_date', 'id' => 'to_date', 'placeholder'=>'To Date','value' => set_value('to_date', $to_date))); ?>
								<?php echo form_error('to_date', '<div class="text-danger">', '</div>'); ?>		
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 control-label" for="input-status">Status</label>
					<div class="col-sm-10">
						<?php echo form_dropdown('status', array('1'=>'Enabled', '0' => 'Disabled'), set_value('status', $status), 'id=\'status\' class=\'form-control\'')?>
					</div>
				</div>
				
				<table id="tax_rates" class="table table-striped table-bordered table-hover">
					<thead>
						<tr class="">
							<th class="text-left">Amount >= </th>
							<th class="text-left">Amount < </th>
							<th class="text-left">Type</th>
							<th class="text-left">Rate</th>
							<th class="text-left">Additional Rate</th>
							<th class="text-right">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $tax_row = 0; ?>
						<?php foreach ($tax_rates as $tax_rate) { ?>
						<tr id="tax-row<?php echo $tax_row; ?>">
							<td class="text-left">
								<input type="text" name="tax_rate[<?php echo $tax_row; ?>][to_amount]" value="<?php echo  $tax_rate['to_amount']; ?>" placeholder="To amount" class="form-control" />
							</td>
							<td class="text-left">
								<input type="text" name="tax_rate[<?php echo $tax_row; ?>][from_amount]" value="<?php echo  $tax_rate['from_amount']; ?>" placeholder="From amount" class="form-control" />
							</td>
							<td class="text-left">
								<select name="tax_rate[<?php echo $tax_row; ?>][type]" class="form-control">
									<option value="P" <?php echo ($tax_rate['type']=="P")? 'selected="selected"':'';?>>Percentage</option>
									<option value="F" <?php echo ($tax_rate['type']=="F")? 'selected="selected"':'';?>>Fixed</option>
								</select>
							</td>
							<td class="text-left">
								<input type="text" name="tax_rate[<?php echo $tax_row; ?>][rate]" value="<?php echo  $tax_rate['rate']; ?>" placeholder="Rate" class="form-control" />
							</td>
							<td class="text-left">
								<input type="text" name="tax_rate[<?php echo $tax_row; ?>][additional]" value="<?php echo  $tax_rate['additional']; ?>" placeholder="Additional Rate" class="form-control" />
							</td>
							<td class="text-right"><button type="button" onclick="$('#tax-row<?php echo $tax_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus"></i></button></td>
						</tr>
						<?php $tax_row++; ?>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5"></td>
							<td class="text-right"><button type="button" onclick="addRate();" data-toggle="tooltip" title="Rate Add" class="btn btn-primary"><i class="fa fa-plus"></i></button></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>	
<?php js_start(); ?>

<script type="text/javascript"><!--
var tax_row = <?php echo $tax_row; ?>;

function addRate() {
	
   html = '<tr id="tax-row' +tax_row + '">';
	html += '  	<td class="text-left">';
	html += '		<input type="text" name="tax_rate[' + tax_row + '][to_amount]" value="" placeholder="To Amount" class="form-control" />';
	html += '	</td>';
	html += '  	<td class="text-left">';
	html += '		<input type="text" name="tax_rate[' + tax_row + '][from_amount]" value="" placeholder="From Amount" class="form-control" />';
	html += '	</td>';
	html += '  	<td class="text-left">';
	html += '		<select name="tax_rate[' + tax_row + '][type]" class="form-control">';
    html += '    		<option value="P">Percentage</option>';
    html += '    		<option value="F">Fixed</option>';
    html += '  		</select>';
	html += '	</td>';
	html += '  	<td class="text-left">';
	html += '		<input type="text" name="tax_rate[' + tax_row + '][rate]" value="" placeholder="Rate" class="form-control" />';
	html += '	</td>';
	html += '  	<td class="text-left">';
	html += '		<input type="text" name="tax_rate[' + tax_row + '][additional]" value="" placeholder="Additional Rate" class="form-control" />';
	html += '	</td>';
	
	html += '  	<td class="text-right"><button type="button" onclick="$(\'#tax-row' + tax_row  + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#tax_rates tbody').append(html);
	tax_row++;
}
function removetax(j)
{
	$(".tax-row"+j).remove();
}
//--></script>
<?php js_end(); ?>

