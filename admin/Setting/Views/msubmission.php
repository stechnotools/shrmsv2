<?php
$validation = \Config\Services::validation();
?>
<?php echo form_open_multipart('', 'id="form-submission"'); ?>
    <div class="content-heading pt-0" xmlns="http://www.w3.org/1999/html">
        <div class="dropdown float-right">
            <button type="submit" form="form-submission" class="btn btn-primary">Save</button>
            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary">Cancel</a>
        </div>
        Manual Form Submission
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="form-group row">
                <label class="col-md-2 control-label" for="site_homepage">Projects</label>
                <div class="col-md-10">
                    <?php  echo form_dropdown('projectId', option_array_values($projects, 'id', 'name'), set_value('projectId',""),array('class'=>'form-control select2','id' => 'projectId') ); ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 control-label" for="site_homepage">Forms</label>
                <div class="col-md-10">
                    <?php  echo form_dropdown('formId', array(), set_value('formId',""),array('class'=>'form-control select2','id' => 'formId') ); ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 control-label" for="site_homepage">Submission data</label>
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="submissiondata" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>
<?php js_start(); ?>
<script type="text/javascript"><!--
    $(document).ready(function() {
        $('select[name=\'projectId\']').bind('change', function () {
            $.ajax({
                url: '<?php echo admin_url("msubmission/getForms"); ?>/' + this.value,
                dataType: 'json',
                beforeSend: function () {
                    //$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
                },
                complete: function () {
                    //$('.wait').remove();
                },
                success: function (json) {

                    html = '<option value="0">Select Form</option>';

                    if (json['form'] != '') {
                        for (i = 0; i < json['form'].length; i++) {
                            html += '<option value="' + json['form'][i]['xmlFormId'] + '"';
                            html += '>' + json['form'][i]['name'] + '</option>';
                        }
                    } else {
                        html += '<option value="0" selected="selected">Select Form</option>';
                    }

                    $('select[name=\'formId\']').html(html);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });

    //--></script>
<?php js_end(); ?>