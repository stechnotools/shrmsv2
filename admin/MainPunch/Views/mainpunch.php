
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
							<div class="col-lg-4">
								<div class="form-group mg-b-10-force">
									<label class="form-control-label">Branch: <span class="tx-danger">*</span></label>
									<?php echo form_dropdown('branch_id', option_array_value($branches, 'id', 'name',array(''=>'Select Branch')), set_value('branch_id', ''),"id='branch_id' class='form-control select2'"); ?>
								</div>
							</div>

							<div class="col-lg-4">
								<div class="form-group mg-b-10-force">
									<label for="inputEmail3" class="control-label">Emp Name/Paycode</label>
									<?php echo form_dropdown('user_id', array(),set_value('user_id', ''),"id='user_id' class='form-control select2'"); ?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group mg-b-10-force">
									<label for="inputEmail3" class="control-label">Date Range</label>
									<?php echo form_input(array('name'=>'daterange','id'=>'daterange', 'class'=>'form-control daterange','placeholder'=>'From','value' => set_value('daterange', ''))) ?>

								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-layout-footer">
									<button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
									<button type="button" id="btn-reset" class="btn btn-default">Reset</button>
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
				<div class="panel-tools float-right">
					<?php echo form_open_multipart('admin/employee/upload','class="upload-form"');?>
					<a href="<?=$download_sample?>" data-toggle="tooltip"  class="btn btn-primary btn-sm" target="_self" download>Download Sample</a>
					<input type="file" name="bpunch" id="bpunch" style="display:none">
					<button type="button" data-toggle="tooltip" title="Upload Bulk Attendance" class="btn btn-info btn-sm" onclick="thisFileUpload();">Attendance Upload</i></button>

					<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
					<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger btn-sm" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-datatable').submit() : false;"><i class="fa fa-trash"></i></button>
					<?php echo form_close(); ?>
				</div>
			</div>
			<div class="card-body">
				<div class="progress progress-lg">
					<div class="progress-bar bg-info wow animated progress-animated" id="sheetprogressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						0%
					</div>
				</div>
				<div class="progress progress-sm">
					<div class="progress-bar bg-primary" role="progressbar" id="batchprogressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						0%
					</div>
				</div>
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-datatable">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
									<tr>
										<th style="width: 1px;" class="text-center no-sort">
											<div class="checkbox checkbox-primary checkbox-single">
												<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
												<label></label>
											</div>
										</th>
										<th>Branch</th>
										<th>Employee</th>
										<th>Paycode</th>
										<th>Punch Date</th>
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
			type: "post",  // metpunch  , by default get
			data: function ( data ) {
				data.branch_id = $('#branch_id').val();
				data.user_id = $('#user_id').val();
				data.daterange = $('#daterange').val() || getDefaultDateRange();
			},
			beforeSend: function(){
				$('.alert-dismissible, .text-danger').remove();
				$("#datatable_wrapper").LoadingOverlay("show");
			},
			complete: function(){
				$("#datatable_wrapper").LoadingOverlay("hide");
			},
			error: function(){  // error handling
				$(".punch_list_error").html("");
				$("#punch_list").append('<tbody class="punch_list_error"><tr><th colspan="3">No data found.</th></tr></tbody>');
				$("#punch_list_processing").css("display","none");

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
//--></script>
<script type="text/javascript"><!--
$(function() {

var start = moment().subtract(29, 'days');
var end = moment();

function cb(start, end) {
	$(".daterange").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
}

$('.daterange').daterangepicker({
	startDate: start,
	endDate: end,
	ranges: {
	   'Today': [moment(), moment()],
	   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	   'This Month': [moment().startOf('month'), moment().endOf('month')],
	   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	}
}, cb);

cb(start, end);

});
function getDefaultDateRange() {
   // Customize the default date range as needed
   var defaultStartDate = moment().subtract(29, 'days'); // Default start date is 7 days ago
   var defaultEndDate = moment(); // Default end date is today

   return defaultStartDate.format('MMMM D, YYYY') + ' - ' + defaultEndDate.format('MMMM D, YYYY');
}
let sheetProgress = 0; // Variable to track overall sheet progress
let batchProgress = 0; // Variable to track batch progress
let batchIndex = 0; // Variable to track batch index
let totalBatches = 0; // Variable to track total number of batches

$("#bpunch").change(async function(evt) {
    const [selectedFile] = evt.target.files;
    const fileReader = new FileReader();

    fileReader.onload = async function(event) {
        const data = new Uint8Array(event.target.result);
        const workbook = XLSX.read(data, { type: 'array', cellDates: true });

        // Get the total number of sheets
        const totalSheets = workbook.SheetNames.length;

        // Initialize sheet progress
        sheetProgress = 0;
        updateSheetProgress(0); // Update sheet progress bar

        // Iterate through each sheet
        for (const sheetName of workbook.SheetNames) {
            const worksheet = workbook.Sheets[sheetName];
            const range = XLSX.utils.decode_range(worksheet['!ref']);

            // Convert the sheet to JSON
            const rows = [];
            for (let rowIndex = range.s.r; rowIndex <= range.e.r; rowIndex++) {
                const row = [];
                for (let colIndex = range.s.c; colIndex <= range.e.c; colIndex++) {
                    const cellAddress = { c: colIndex, r: rowIndex };
                    const cellRef = XLSX.utils.encode_cell(cellAddress);
                    const cell = worksheet[cellRef];
                    const cellValue = cell ? cell.v : '';
                    row.push(cellValue);
                }
                rows.push(row);
            }

            // Reset batch progress
            batchProgress = 0;
            updateBatchProgress(0); // Update batch progress bar

            // Calculate the total number of batches
            totalBatches = Math.ceil(rows.length / 100); // Assuming 100 as batchSize

            // Upload batches
            batchIndex = 0;
            await uploadBatches(rows, 100,sheetName); // Upload batches with default batch runs

            // Update sheet progress after all batches are uploaded
            sheetProgress++;
            updateSheetProgress((sheetProgress / totalSheets) * 100);
        }
    };

    fileReader.readAsArrayBuffer(selectedFile);
});

async function uploadBatches(rows, batchSize,sheetName) {
    const progressBar = $('#batchprogressbar');

    while (batchIndex < totalBatches) {
        const start = batchIndex * batchSize;
        const end = Math.min(start + batchSize, rows.length);
        const batch = rows.slice(start, end);

        if (batch.length > 0) {
            try {
                const response = await $.ajax({
                    url: '<?=admin_url('mainpunch/uploadpunch')?>', // Replace with your server endpoint
                    type: 'POST',
                    data: JSON.stringify({ sheetName: sheetName, sheetData: batch }),
                    contentType: 'application/json'
                });
                console.log(`Batch ${batchIndex} uploaded:`, response);

                batchIndex++;
                batchProgress++;
                const progress = Math.floor((batchProgress / totalBatches) * 100);

                progressBar.css('width', `${progress}%`).attr('aria-valuenow', progress).text(`${progress}%`);
            } catch (error) {
				console.log(error);
                console.error(`Error uploading batch ${batchIndex}:`, error);
                // Handle the error
            }
        } else {
            // If all batches are uploaded, break the loop
            break;
        }
    }
}

function updateSheetProgress(progress) {
    const sheetProgressBar = $('#sheetprogressbar');
    sheetProgressBar.css('width', `${progress}%`).attr('aria-valuenow', progress).text(`${progress}%`);
}

function updateBatchProgress(progress) {
    const batchProgressBar = $('#batchprogressbar');
    batchProgressBar.css('width', `${progress}%`).attr('aria-valuenow', progress).text(`${progress}%`);
}


function thisFileUpload() {
	document.getElementById("bpunch").click();
};
</script>
<?php js_end(); ?>