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
                    <li class="nav-item tab">
                        <a class="nav-link" id="form-tab" data-toggle="tab" href="#form" role="tab" aria-controls="form" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                            <span class="d-none d-sm-block">Forms</span>
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
                                        <?php echo form_dropdown('user_role_id', option_array_value($user_roles, 'id', 'name'), set_value('user_role_id', $user_role_id),"id='input-user-group' class='form-control select2'"); ?>
                                        <?php echo $validation->showerror('user_role_id', 'aio_error'); ?>
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
                                    <label class="col-md-3 control-label" for="input-address">Address</label>
                                    <div class="col-md-9">
                                        <textarea name="address" id="input-address" class="form-control" placeholder="Address"><?=$address?></textarea>
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

                        </div>


					</div>
                    <div class="tab-pane" id="form" role="tabpanel" aria-labelledby="form-tab">
						<div class="row">
                            <div class="col-md-6">
                                <div class="form-group row required">
                                    <label class="col-md-3 control-label" for="input-username">Form Assign *</label>
                                    <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-body overflow-auto bg-gray-200" style="height:300px;">
                                            <?php
                                            foreach($forms as $form): ?>
                                                <div class="">
                                                    <label><input type="checkbox" value="<?php echo $form['id']; ?>" name="form_assign[]" <?php echo set_checkbox('form_assign[]', $form['id'], (in_array($form['id'], $form_assign) ? TRUE : FALSE)); ?> /> <?php echo $form['name']; ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="card-footer">
                                            <button onclick="$(this).parents('.card').find(':checkbox').prop('checked', true); return false;" class="btn btn-info btn-sm">Select All</button>
                                            <button onclick="$(this).parents('.card').find(':checkbox').prop('checked', false); return false;" class="btn btn-info btn-sm">Unselect All</button>
                                        </div>
                                    </div>
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