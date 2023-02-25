<?php
$validation = \Config\Services::validation();
?>
<?php echo form_open_multipart('', 'id="form-user"'); ?>
<div class="content-heading pt-0">
    <div class="dropdown float-right">
        <button type="submit" form="form-user" class="btn btn-primary">Save</button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary">Cancel</a>
    </div>
    <?php echo $text_form; ?>
</div>
<div class="row">
<div class="col-xl-12">
<div class="row">
	<div class="col-xl-12">

        <div class="block">

            <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#general">General</a>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link" href="#assign">Form Assign</a>
                </li>-->
                <li class="nav-item">
                    <a class="nav-link" href="#account">Account</a>
                </li>
            </ul>
            <div class="block-content tab-content">
                <div class="tab-pane active" id="general" role="tabpanel">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label" for="example-hf-email">Name</label>
                        <div class="col-lg-10 <?=$validation->hasError('firstname')?'is-invalid':''?>">
                            <input type="hidden" name="id" id="id" value="<?=$id?>"/>
                            <?php echo form_input(array('class'=>'form-control','name' => 'firstname', 'id' => 'firstname', 'placeholder'=>'Name','value' => set_value('firstname', $firstname))); ?>
                            <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('firstname'); ?></div>
                        </div>
                    </div>
					
					<div class="form-group row required">
						<label class="col-sm-2 control-label" for="input-user-group">User Role</label>
						<div class="col-md-10 <?=$validation->hasError('user_group_id')?'is-invalid':''?>">
							<?php echo form_dropdown('user_group_id', option_array_value($user_groups, 'id', 'name'), set_value('user_group_id', $user_group_id),"id='input-user-group' class='form-control js-select2'"); ?>
							<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('user_group_id'); ?></div>
                        </div>
					</div>

					<div class="form-group row required">
						<label class="col-md-2 control-label" for="input-email">Email</label>
						<div class="col-md-10 <?=$validation->hasError('email')?'is-invalid':''?>">
							<?php echo form_input(array('class'=>'form-control','name' => 'email', 'id' => 'input-email', 'placeholder'=>'Email','value' => set_value('email', $email))); ?>
							<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('email'); ?></div>
                        </div>
					</div>
						
					<div class="form-group row required">
						<label class="col-sm-2 control-label" for="input-phone">Phone</label>
						<div class="col-md-10">
							<?php echo form_input(array('class'=>'form-control','name' => 'phone', 'id' => 'input-phone', 'placeholder'=>'phone','value' => set_value('phone', $phone))); ?>
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-2 control-label" for="input-address">Address</label>
						<div class="col-md-10">
							<textarea name="address" id="input-address" class="form-control" placeholder="Address"><?=$address?></textarea>
						</div>
					</div>
					<div class="form-group row required">
						<label class="col-md-2 control-label" for="input-meta-keywords">District</label>
						<div class="col-md-10">
							<?php echo form_dropdown('district', option_array_value($districts, 'code', 'name',array("0"=>"select District")), set_value('district', $district),"id='district' class='form-control js-select2'"); ?>
                        </div>
					</div>
					<div class="form-group row required">
						<label class="col-md-2 control-label" for="input-meta-keywords">Block</label>
						<div class="col-md-10">
							<?php echo form_dropdown('block', array(), set_value('block', $block),"id='block' class='form-control select2'"); ?>
                        </div>
					</div>

					<div class="form-group row">
						<label class="col-md-2 control-label" for="input-meta-keywords">GP</label>
						<div class="col-md-10">
                            <?php echo form_dropdown('gp[]', array(), set_value('gp[]', $gp),"id='gp' class='form-control select2' multiple='multiple'"); ?>
                        </div>
					</div>
                </div>
                <div class="tab-pane" id="assign" role="tabpanel">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label" for="example-hf-email">Form Assign</label>
                        <div class="col-lg-10">
                            <div class="card">
                                <div class="card-body overflow-auto bg-gray-200" style="height:300px;">
                                    <?php
                                    foreach($forms as $form): ?>
                                        <div class="">
                                            <label><input type="checkbox" value="<?php echo $form['xmlFormId']; ?>" name="form_assign[]" <?php echo set_checkbox('form_assign[]', $form['xmlFormId'], (in_array($form['xmlFormId'], $form_assign) ? TRUE : FALSE)); ?> /> <?php echo $form['name']; ?></label>
                                        </div>

                                    <?php endforeach; ?>

                                </div>
                                <div class="card-footer">
                                     <button onclick="$(this).parents('.card').find(':checkbox').prop('checked', true); return false;" class="btn btn-info">Select All</button>
                                    <button onclick="$(this).parents('.card').find(':checkbox').prop('checked', false); return false;" class="btn btn-info">Unselect All</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="account" role="tabpanel">
					<div class="form-group row required">
						<label class="col-sm-2 control-label" for="input-username">Username</label>
						<div class="col-md-10 <?=$validation->hasError('username')?'is-invalid':''?>">
							<?php echo form_input(array('class'=>'form-control','name' => 'username', 'id' => 'input-username', 'placeholder'=>'Username','value' => set_value('username', $username))); ?>
                            <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('username'); ?></div>
                        </div>
					</div>
					<div class="form-group row required">
						<label class="col-sm-2 control-label" for="input-password">Password</label>
						<div class="col-md-10 <?=$validation->hasError('password')?'is-invalid':''?>">
							<?php echo form_input(array('class'=>'form-control','name' => 'password', 'id' => 'input-password', 'placeholder'=>'Password','value' => set_value('password', $show_password))); ?>
                            <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('password'); ?></div>
                        </div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 control-label" for="input-status">Status</label>
						<div class="col-md-10">
							<?php  echo form_dropdown('enabled', array('1'=>'Enable','0'=>'Disable'), set_value('enabled',$enabled),array('class'=>'form-control','id' => 'input-status')); ?>
						</div>
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
        $('select[name=\'district\']').bind('change', function() {
            $.ajax({
                url: '<?php echo admin_url("district/block"); ?>/' + this.value,
                dataType: 'json',
                beforeSend: function() {
                    //$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
                },
                complete: function() {
                    //$('.wait').remove();
                },
                success: function(json) {

                    html = '<option value="0">Select Block</option>';

                    if (json['block'] != '') {
                        for (i = 0; i < json['block'].length; i++) {
                            html += '<option value="' + json['block'][i]['code'] + '"';
                            if (json['block'][i]['code'] == '<?php echo $block;?>') {
                                html += ' selected="selected"';
                            }
                            html += '>' + json['block'][i]['name'] + '</option>';
                        }
                    } else {
                        html += '<option value="0" selected="selected">Select Block</option>';
                    }

                    $('select[name=\'block\']').html(html);
                    $('select[name=\'block\']').select2();
                    $('select[name=\'block\']').trigger('change');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
        $('select[name=\'district\']').trigger('change');
        $('select[name=\'block\']').bind('change', function() {
            $.ajax({
                url: '<?php echo admin_url("block/grampanchayat"); ?>/' + this.value,
                dataType: 'json',
                beforeSend: function() {
                    //$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
                },
                complete: function() {
                    //$('.wait').remove();
                },
                success: function(json) {
					var gp=<?=json_encode($gp)?>;
                    html = '<option value="">Select Grampanchayat</option>';

                    if (json['grampanchayat'] != '') {
                        for (i = 0; i < json['grampanchayat'].length; i++) {
                            html += '<option value="' + json['grampanchayat'][i]['code'] + '"';
                            if ($.inArray(json['grampanchayat'][i]['code'], gp) != -1){
								html += ' selected="selected"';
							}
							
                            html += '>' + json['grampanchayat'][i]['name'] + '</option>';
                        }
                    } else {
                        //html += '<option value="0" selected="selected">Select Grampanchayat</option>';
                    }

                    $('select[name=\'gp[]\']').html(html);
                    $('select[name=\'gp[]\']').select2();

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
        Codebase.helpers([ 'select2']);
    });
    //--></script>
<?php js_end(); ?>