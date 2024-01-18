<?php
$validation = \Config\Services::validation();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="Save" class="btn btn-danger btn-sm" form="form-user"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
                <?php echo form_open_multipart("",array('class' => 'form-horizontal', 'id' => 'form-user','role'=>'form')); ?>
                <ul class="nav nav-tabs tabs" role="tablist">
                    <li class="nav-item tab">
                        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                            <span class="d-none d-sm-block">General</span>
                        </a>
                    </li>
                    <li class="nav-item tab">
                        <a class="nav-link" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                            <span class="d-none d-sm-block">Account</span>
                        </a>
                    </li>
				</ul>
                <div class="tab-content">
					<div class="tab-pane show active" id="general" role="tabpanel" aria-labelledby="general-tab">
						<div class="row">
                            <div class="col-md-6">
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-name">Full Name *</label>
                                    <div class="col-md-9">
                                        <input type="hidden" name="id" id="id" value="<?=$id?>"/>
                                        <?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'name', 'placeholder'=>"Full Name",'value' => set_value('name', $name))); ?>
                                        <?php echo $validation->showerror('name', 'aio_error'); ?>
                                    </div>
                                </div>
                               
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-user-group">User Role *</label>
                                    <div class="col-md-9">
                                        <?php echo form_dropdown('user_group_id', option_array_value($user_groups, 'id', 'name'), set_value('user_group_id', $user_group_id),"id='input-user-group' class='form-control select2'"); ?>
                                        <?php echo $validation->showerror('user_group_id', 'aio_error'); ?>
                                    </div>
                                </div>
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-email">Email *</label>
                                    <div class="col-md-9">
                                        <?php echo form_input(array('class'=>'form-control','name' => 'email', 'id' => 'input-email', 'placeholder'=>'Email','value' => set_value('email', $email))); ?>
                                        <?php echo $validation->showerror('email', 'aio_error'); ?>
                                    </div>
                                </div>
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-phone">Phone</label>
                                    <div class="col-md-9">
                                        <?php echo form_input(array('class'=>'form-control','name' => 'phone', 'id' => 'input-phone', 'placeholder'=>'phone','value' => set_value('phone', $phone))); ?>
                                    </div>
                                </div>
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-meta-keywords">Country *</label>
                                    <div class="col-md-9">
                                        <?php echo form_dropdown('country_id', option_array_value($countries, 'id', 'name'), set_value('country_id', $country_id),"id='country_id' class='form-control select2'"); ?>
                                        <?php echo $validation->showerror('country_id', 'aio_error'); ?>
                                    </div>
                                </div>
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-meta-keywords">State *</label>
                                    <div class="col-md-9">
                                        <?php echo form_dropdown('state_id', array(), set_value('state_id', $state_id),"id='state_id' class='form-control select2'"); ?>
                                        <?php echo $validation->showerror('state_id', 'aio_error'); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 control-label" for="input-meta-keywords">City *</label>
                                    <div class="col-md-9">
                                        <?php echo form_dropdown('city_id', array(), set_value('city_id', $city_id),"id='city_id' class='form-control select2'"); ?>
                                        <?php echo $validation->showerror('city_id', 'aio_error'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label" for="input-image">Image</label>
                                    <div class="col-sm-4">
                                        <div class="gal-detail card mb-0">
                                            <img src="<?php echo $thumb_image; ?>" alt="" id="thumb_image" />
                                            <input type="hidden" name="image" value="<?php echo $image?>" id="image" />
                                            <div class="btn-group d-flex text-white mt-3" role="group">
                                                <a class="btn btn-primary waves-effect waves-light btn-xs" onclick="image_upload('image','thumb_image')">Browse</a>
                                                <a class="btn btn-danger waves-effect waves-light btn-xs" onclick="$('#thumb_image').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');">Clear</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-address">Address</label>
                                    <div class="col-md-9">
                                        <textarea name="address" id="input-address" class="form-control" placeholder="Address"><?=$address?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 control-label" for="input-meta-keywords">Postcode</label>
                                    <div class="col-md-9">
                                        <?php echo form_input(array('class'=>'form-control','name' => 'zip', 'id' => 'zip', 'placeholder'=>'postcode','value' => set_value('zip', $zip))); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="tab-pane" id="account" role="tabpanel" aria-labelledby="account-tab">
						<div class="row">
                            <div class="col-md-6">
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-username">Username *</label>
                                    <div class="col-md-9">
                                        <?php echo form_input(array('class'=>'form-control','name' => 'username', 'id' => 'input-username', 'placeholder'=>'Username','value' => set_value('username', $username))); ?>
                                        <?php echo $validation->showerror('username', 'aio_error'); ?>
                                    </div>
                                </div>
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-password">Password *</label>
                                    <div class="col-md-9">
                                        <?php echo form_input(array('class'=>'form-control','name' => 'password', 'id' => 'input-password', 'placeholder'=>'Password','value' => set_value('password', $password))); ?>
                                        <?php echo $validation->showerror('password', 'aio_error'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-password">Branch *</label>
                                    <div class="col-md-9">
                                        <?php echo form_dropdown('branch_id[]', option_array_value($branches, 'id', 'name'), set_value('branch_id', $branch_id),"id='branch_id' class='form-control select2' multiple"); ?>
                                        <?php echo $validation->showerror('branch_id[]', 'aio_error'); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 control-label" for="input-status">Status</label>
                                    <div class="col-md-9">
                                        <?php  echo form_dropdown('enabled', array('1'=>'Enable','0'=>'Disable'), set_value('enabled',$enabled),array('class'=>'form-control','id' => 'input-status')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
						
					</div>
				</div>
			
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php js_start(); ?>
<script type="text/javascript"><!--
    $(document).ready(function() {
		$('select[name=\'country_id\']').bind('change', function() {
			$.ajax({
				url: '<?php echo admin_url("localisation/country/state"); ?>/' + this.value,
				dataType: 'json',
				beforeSend: function() {
					//$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
				},		
				complete: function() {
					//$('.wait').remove();
				},			
				success: function(json) {
					
					html="";
					if (json['state'] != '') {
						html += '<option value="0" selected="selected" >Select State</option>';
						for (i = 0; i < json['state'].length; i++) {
							html += '<option value="' + json['state'][i]['id'] + '"';
							
							if (json['state'][i]['id'] == '<?php echo $state_id; ?>') {
								html += ' selected="selected"';
							}
			
							html += '>' + json['state'][i]['name'] + '</option>';
						}
					} else {
						html += '<option value="0" selected="selected">Select State</option>';
					}
					
					$('select[name=\'state_id\']').html(html);
					$('select[name=\'state_id\']').select2(); 
					$('select[name=\'state_id\']').trigger('change');  
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
		$('select[name=\'country_id\']').trigger('change');

		$('select[name=\'state_id\']').bind('change', function() {
			
			$.ajax({
				url: '<?php echo admin_url("localisation/state/city"); ?>/' + this.value,
				dataType: 'json',
				beforeSend: function() {
					//$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
				},		
				complete: function() {
					//$('.wait').remove();
				},			
				success: function(json) {
					
					html="";
					if (json['city'] != '') {
						html = '<option value="0">Select City</option>';
						for (i = 0; i < json['city'].length; i++) {
							html += '<option value="' + json['city'][i]['id'] + '"';
							
							if (json['city'][i]['id'] == '<?php echo $city_id; ?>') {
								html += ' selected="selected"';
							}
			
							html += '>' + json['city'][i]['name'] + '</option>';
						}
					} else {
						html += '<option value="0" selected="selected">Select City</option>';
					}
					
					$('select[name=\'city_id\']').html(html);
					$('select[name=\'city_id\']').select2();     
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
		//$('select[name=\'state_id\']').trigger('change');

    });
	function image_upload(field, thumb) {
		CKFinder.modal( {
			chooseFiles: true,
			width: 800,
			height: 600,
			onInit: function( finder ) {
				console.log(finder);
				finder.on( 'files:choose', function( evt ) {
					var file = evt.data.files.first();
					url=file.getUrl();
					var lastSlash = url.lastIndexOf("uploads/");
					var fileName=url.substring(lastSlash+8);
					//url=url.replace("images", ".thumbs/images"); 
					$('#'+thumb).attr('src', url);
					$('#'+field).attr('value', fileName);
				} );
				finder.on( 'file:choose:resizedImage', function( evt ) {
					var output = document.getElementById( field );
					output.value = evt.data.resizedUrl;
					console.log(evt.data.resizedUrl);
				} );
			}
		});
	};
//--></script>
<?php js_end(); ?>