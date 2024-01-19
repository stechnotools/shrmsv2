<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?php echo $heading_title; ?></h3>
				<div class="panel-tools float-right">
					<?php echo form_open_multipart('admin/mpunch/upload','class="upload-form"');?>
					<input type="file" name="bpunch" id="bpunch" style="display:none">
					<?php echo form_close(); ?>
					<a href="" data-toggle="tooltip"  class="btn btn-primary" target="_self" download>Download Sample</a>
					
					<button type="button" data-toggle="tooltip" title="Upload Bulk Attendance" class="btn btn-info" onclick="thisFileUpload();">Bulk Attendance Upload</i></button>
					<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
					<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-punch').submit() : false;"><i class="fa fa-trash-o"></i></button>
				</div>
				
			</div>
			<div class="card-body">
				<div class="progress">
				  <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><div class="percent">0%</div></div>
				</div>
				<form action="<?php echo $delete; ?>" action="post" enctype="multipart/form-data" id="form-punch">          
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<table id="punch_list" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
									<tr>
										<th>punch date</th>
										<th>paycode</th>
										<th>Shift</th>
										<th>In Time</th>
										<th>Out Time</th>
										<th>Worked Hours</th>
										<th>Late Arrival</th>
										<th>Early Departure</th>
										<th class="no-sort">Action</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</form>
				
				
			</div>
		</div>
	</div>
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
$(function(){
	$('#punch_list').DataTable({
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ targets: 'no-sort', orderable: false },
			{ targets: 'no-visible', visible: false },
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // metpunch  , by default get
			error: function(){  // error handling
				$(".punch_list_error").html("");
				$("#punch_list").append('<tbody class="punch_list_error"><tr><th colspan="3">No data found.</th></tr></tbody>');
				$("#punch_list_processing").css("display","none");
				
			},
			dataType:'json'
		},
	});

	var bar = $('.progress-bar');
    var percent = $('.percent');
    
	
	$('.upload-form').ajaxForm({
		dataType:'json',
        beforeSend: function() {
            var percentVal = '0%';
            bar.width(percentVal);
            percent.html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
        },
        complete: function(xhr) {
            //console.log(xhr);
			//location=xhr.redirect;
        },
		success: function(data){
			console.log(data);
			if(data.success){
				location=data.redirect;
			}
			
		}
    });
	
	$('#bpunch').on('change',function () {
		$('.upload-form').submit();
	})
});
function thisFileUpload() {
	document.getElementById("bpunch").click();
};
//--></script>
<?php js_end(); ?>