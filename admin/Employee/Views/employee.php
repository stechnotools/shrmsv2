
<div class="row">
	<div class="col-lg-12">
		<div class="card bg-sky">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2">Filter</h3>
			</div>
			<div class="card-body">
				<form id="form-filter" class="form-horizontal">
					<div class="form-layout">
						<div class="row">
							<div class="col-lg-3">
								<div class="form-group mg-b-10-force">
									<label class="form-control-label">Branch: <span class="tx-danger">*</span></label>
									<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(''=>'Select Branch')), set_value('branch_id', ''),"id='branch_id' class='form-control select2'"); ?>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group mg-b-10-force">
									<label class="form-control-label">Department: <span class="tx-danger">*</span></label>
									<?php echo form_dropdown('department_id', option_array_value($departments, 'id', 'name',array(''=>'Select Department')), set_value('department_id', ''),"id='department_id' class='form-control select2'"); ?>
								</div>
							</div><!-- col-4 -->
							<div class="col-lg-3">
								<div class="form-group mg-b-10-force">
									<label class="form-control-label">Designation: <span class="tx-danger">*</span></label>
									<?php echo form_dropdown('designation_id', option_array_value($designations, 'id', 'name',array(''=>'Select Designation')), set_value('designation_id', ''),"id='designation_id' class='form-control select2'"); ?>
								</div>
							</div><!-- col-4 -->
							<div class="col-lg-3">
								<div class="form-group mg-b-10-force">
									<label class="form-control-label">Status: <span class="tx-danger">*</span></label>
									<?php echo form_dropdown('status', array(1=>'Active',0=>'Deactive'), set_value('status', ''),"id='status' class='form-control select2'"); ?>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-layout-footer">
									<button type="button" id="btn-filter" class="btn btn-primary btn-sm">Filter</button>
									<button type="button" id="btn-reset" class="btn btn-warning btn-sm">Reset</button>
								</div>
							</div>
						</div>
					</div><!-- form-layout -->
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $heading_title; ?></h3>
				<?php if(!$popup){?>
				<div class="panel-tools float-right">
					<?php echo form_open_multipart('admin/employee/upload','class="upload-form"');?>
					<a href="<?=$emp_sample?>" data-toggle="tooltip"  class="btn btn-primary btn-sm" target="_self" download>Download Sample</a>
					<input type="file" name="bemployee" id="bemployee" style="display:none">
					<button type="button" data-toggle="tooltip" title="Upload Bulk Employee" class="btn btn-info btn-sm" onclick="thisFileUpload();">Bulk Employee Upload</i></button>

					<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
					<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger btn-sm" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-datatable').submit() : false;"><i class="fa fa-trash"></i></button>
					<?php echo form_close(); ?>
				</div>
				<?}?>
			</div>
			<div class="card-body">
				<div class="progress progress-lg">
					<div class="progress-bar bg-info wow animated progress-animated" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						0%
					</div>
				</div>

				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-datatable">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
									<tr>
										<?php if(!$popup){?>
										<th style="width: 1px;" class="text-center no-sort">
											<div class="checkbox checkbox-primary checkbox-single">
												<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
												<label></label>
											</div>
										</th>
										<?}?>
										<th>Image</th>
										<th>Name</th>
										<th>Card No</th>

										<?php if(!$popup){?>
										<th>Mobile</th>
										<th>Status</th>
										<?}?>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>
<script type="text/javascript"><!--
$(function(){
	table=$('#datatable').DataTable({
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
			type: "post",  // method  , by default get
			data: function ( data ) {
				data.popup = '<?=$popup?>';
				data.branch_id = $('#branch_id').val();
				data.department_id = $('#department_id').val();
				data.status = $('#status').val();
				data.designation_id = $('#designation_id').val();
			},
			beforeSend: function(){
				$('.alert-dismissible, .text-danger').remove();
				$("#datatable_wrapper").LoadingOverlay("show");
			},
			complete: function(){
				$("#datatable_wrapper").LoadingOverlay("hide");
			},
			error: function(){  // error handling
				$(".datatable_error").html("");
				$("#datatable").append('<tbody class="datatable_error"><tr><th colspan="3">No data found.</th></tr></tbody>');
				$("#datatable_processing").css("display","none");
			},
			dataType:'json'
		},
	});
	$('#btn-filter').click(function(){ //button filter event click
		table.ajax.reload();  //just reload table
	});
	$('#btn-reset').click(function(){ //button reset event click
		$('#form-filter')[0].reset();
		table.ajax.reload();  //just reload table
	});
});


$(function(){


	$('#bemployee1').on('change',function () {
		$('.upload-form').submit();
	});

	$("#bemployee").change(function(evt) {
		const [selectedFile] = evt.target.files;
		const fileReader = new FileReader();
		const batchSize = 10; // Define batchSize here

		fileReader.onload = function(event) {
			const data = new Uint8Array(event.target.result);
			const workbook = XLSX.read(data, { type: 'array',cellDates: true });

			// Read only the first sheet
			const firstSheetName = workbook.SheetNames[0]; // Get the name of the first sheet
			const firstWorksheet = workbook.Sheets[firstSheetName]; // Get the first worksheet

			// Convert the first sheet to JSON
			const range = XLSX.utils.decode_range(firstWorksheet['!ref']);
			range.s.r = 1; // Start from the second row (index 1)
			const rows = [];

			// Convert Excel rows to arrays of values
			for (let rowIndex = range.s.r; rowIndex <= range.e.r; rowIndex++) {
				const row = [];
				for (let colIndex = range.s.c; colIndex <= range.e.c; colIndex++) {
					const cellAddress = { c: colIndex, r: rowIndex };
					const cellRef = XLSX.utils.encode_cell(cellAddress);
					const cell = firstWorksheet[cellRef];
					const cellValue = cell ? cell.v : '';
					row.push(cellValue);
				}
				rows.push(row);
			}

			// Split rows into batches (100 rows per batch)
			batchIndex = 0; // Reset batchIndex
			uploadNextBatch(rows, batchSize);

		};

		fileReader.readAsArrayBuffer(selectedFile);
	});

	function uploadNextBatch(rows, batchSize) {
		const progressBar = $('#progressbar');

		const start = batchIndex * batchSize;
		const end = start + batchSize;
		const batch = rows.slice(start, end);

		if (batch.length > 0) {
			$.ajax({
				url: '<?=admin_url('employee/uploademp')?>', // Replace with your server endpoint
				type: 'POST',
				data: JSON.stringify(batch),
				contentType: 'application/json',
				success: function(response) {
					console.log(`Batch ${batchIndex} uploaded:`, response);

					const progress = Math.floor(((batchIndex + 1) * batchSize) / rows.length * 100);

					progressBar.css('width', `${progress}%`).attr('aria-valuenow', progress).text(`${progress}%`);

					batchIndex++;
					uploadNextBatch(rows, batchSize); // Upload the next batch
				},
				error: function(xhr, status, error) {
					console.error(`Error uploading batch ${batchIndex}:`, error);
					// Handle the error
				}
			});
		}
	}

	$("#bemployee2").change(async evt => {
		const [progressBar, status] = document.querySelectorAll('#progressBar, #status');
		progressBar.value = 0;
		status.innerText = 'Uploading';

		const [selectedFile] = evt.target.files;
		const data = await selectedFile.arrayBuffer();
		const workbook = XLSX.read(new Uint8Array(data), {
			type: 'array'
		});

		workbook.SheetNames.forEach(sheetName => {
			const XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName], {
			defval: ''
			});
			const json = JSON.stringify(XL_row_object);
			const xhr = new XMLHttpRequest();

			xhr.upload.onprogress = evt => {
			const percent = (evt.loaded / evt.total) * 100;
			progressBar.value = Math.round(percent);
			};

			// Specify the method, URL, and send the request
			xhr.open('POST', 'YOUR_UPLOAD_ENDPOINT_HERE'); // Replace with your server upload endpoint
			xhr.setRequestHeader('Content-Type', 'application/json'); // Set proper content type
			xhr.onload = () => {
			if (xhr.status === 200) {
				// Handle successful upload
				status.innerText = 'Upload completed';
			} else {
				// Handle upload error
				status.innerText = 'Upload failed';
			}
			};

			xhr.send(json);
		});
	});


	$('.upload-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        let formData = new FormData(this);

		var bar = $('.progress-bar');
    	var percent = $('.percent');


		var percentVal = '0%';
        bar.width(percentVal);
        percent.html(percentVal);

		$.ajax({
			url: '<?=admin_url('employee/upload')?>',
			type: 'POST',
			dataType: 'json',
			data: formData,
			contentType: false,
            processData: false,
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener('progress', function(e) {
					if (e.lengthComputable) {
						var progress = Math.round((e.loaded / e.total) * 100);
						bar.width(percentVal);
						percent.html(percentVal);
					}
				}, false);
				return xhr;
			},
			success: function(response) {
                // Handle success response from backend
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(error);
            }
		});
	});

});
function thisFileUpload() {
	document.getElementById("bemployee").click();
};
//--></script>
<?php js_end(); ?>