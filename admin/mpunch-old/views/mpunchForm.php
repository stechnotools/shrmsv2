<div class="row punch-row" >
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger" form="form-punch"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart(null,array('class' => 'form-horizontal', 'id' => 'form-punch','role'=>'form')); ?>
					<div class="card card-border">
						<div class="card-header border-info bg-transparent pb-0">
							<h3 class="card-title text-info">Employee Details</h3>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Paycode</label>
										<div class="col-md-8">
											<?php echo form_hidden('punch_userid', $punch_user_id);?>
											<div class="input-group">
                                                <?php echo form_input(array('class'=>'form-control','name' => 'paycode', 'id' => 'paycode', 'placeholder'=>"Paycode",'readonly'=>'true','value' => set_value('paycode', $paycode))); ?>
												<?php if(!$edit){?>
												<span class="input-group-prepend">
													<button type="button" class="btn waves-effect waves-light btn-primary" id="employee_list"><i class="fa fa-search"></i></button>
												</span>
												<?}?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Name</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'employee_name', 'id' => 'loan_name', 'placeholder'=>"Employee Name",'readonly'=>'true','value' => set_value('employee_name', $employee_name))); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Branch</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'branch_name', 'id' => 'branch_name', 'placeholder'=>"Branch Name",'readonly'=>'true','value' => set_value('branch_name', $branch_name))); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Card No</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'card_no', 'id' => 'card_no', 'placeholder'=>"Card No",'readonly'=>'true','value' => set_value('card_no', $card_no))); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Department</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'department_name', 'id' => 'department_name', 'placeholder'=>"Department Name",'readonly'=>'true','value' => set_value('department_name', $department_name))); ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row required">
										<label class="col-md-4 control-label" for="input-firstname">Designation</label>
										<div class="col-md-8">
											<?php echo form_input(array('class'=>'form-control','name' => 'designation_name', 'id' => 'designation_name', 'placeholder'=>"Designation Name",'readonly'=>'true','value' => set_value('designation_name', $designation_name))); ?>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				
				
					<div class="row">
						<div class="col-md-6">
							
							<div class="form-group row required ">
								<label class="col-md-3 control-label" for="input-firstname">Punch date</label>
								<div class="col-md-9">
									<?php 
									if($user_id){
									echo form_input(array('class'=>'form-control datepicker','name' => 'punch_date', 'id' => 'punch-punchdate', 'placeholder'=>"punch date",'disabled'=>'disabled','value' => set_value('punch_date', date("d-m-Y",strtotime($punch_date))))); 
									}else{
										echo form_input(array('class'=>'form-control datepicker','name' => 'punch_date', 'id' => 'punch-punchdate', 'placeholder'=>"punch date",'value' => set_value('punch_date', date("d-m-Y",strtotime($punch_date))))); 
									}?>
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-md-3 control-label" for="input-firstname">Punch Time</label>
								<div class="col-md-9">
									<?php echo form_input(array('class'=>'form-control timepicker','name' => 'punch_time', 'id' => 'punch-punchtime', 'placeholder'=>"punch time",'value' => set_value('punch_time', $punch_time))); ?>
								</div>
							</div>
						</div>
					</div>
				<?php echo form_close(); ?>
				<hr />
				<div id="punchhistory"></div>
			</div> <!-- panel-body -->
		</div> <!-- panel -->
	</div> <!-- col -->
</div>
<?php js_start(); ?>
<script type="text/javascript">
$(function(){
	$("#employee_list").click(function(){
		$.ajax({
			url: '<?php echo admin_url("employee"); ?>',
			dataType: 'html',
			beforeSend: function() {
			},		
			complete: function() {
			},			
			success: function(html) {
				$('.dmodal .modal-body').html(html);

				// Display Modal
				$('.dmodal').modal('show'); 
			}
		});
	});
	
	
	punchhistory();
	
	function punchhistory(){
		var user_id=$('#punch_userid').val(); 
		var punch_date=$('#punch-punchdate').val(); 
		
		$('#punchhistory').load('<?php echo admin_url("mpunch/history?user_id='+user_id+'&punch_date='+punch_date+'");?>');
	}
	
	$(document).on('submit','#form-punch',function() {
		var url = $(this).attr('action');
		//alert(url);
		var f = $(this);
		$.ajax({
			url: url,
			dataType: 'json',
			type: 'post',
			data:{
				'user_id':$("#punch_userid").val(),
				'punch_date':$("#punch-punchdate").val(),
				'punch_time':$("#punch-punchtime").val(),
			},
			success: function(json) {
				$('.alert-dismissible, .text-danger').remove();
				$('.form-group').removeClass('has-error');

				if (json['type']=="error") {
					
					$('.punch-row').before('<div class="alert alert-danger alert-dismissible">' + json['message'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					

					for (i in json['errors']) {
						var element = $('#punch-' + i.replace('_', '-'));

						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['errors'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['errors'][i] + '</div>');
						}
					}

					// Highlight any found errors
					$('.text-danger').parent().addClass('has-error');
				} else {
					punchhistory();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
       return false;
    });
	//punchhistory();
	
	$('.datepicker').datepicker()
	.on('changeDate', function(e) {
		//$(this).datepicker('hide');
		punchhistory();
    });
});
</script>
<?php js_end(); ?>