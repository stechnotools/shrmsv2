<?php
$validation = \Config\Services::validation();
$settings = new \Config\Settings();
?>
<?php echo form_open_multipart('', 'id="form-setting"'); ?>
<div class="card">
    <div class="card-header py-2 text-white">
        <h3 class="card-title float-left my-2"><?php echo $heading_title; ?></h3>
        <div class="panel-tools float-right">
            <button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger btn-sm" form="form-setting"><i class="fa fa-save"></i></button>
			<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
		</div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs tabs-bordered nav-justified" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="company-tab" data-toggle="tab" href="#btabs-company" role="tab" aria-controls="btabs-company" aria-selected="false">Company</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="system-tab" data-toggle="tab" href="#btabs-system" role="tab" aria-controls="btabs-system" aria-selected="false">System</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="theme-tab" data-toggle="tab" href="#btabs-theme" role="tab" aria-controls="btabs-theme" aria-selected="false">Theme</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="email-tab" data-toggle="tab" href="#btabs-email" role="tab" aria-controls="btabs-email" aria-selected="false">Email</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ip-tab" data-toggle="tab" href="#btabs-ip" role="tab" aria-controls="btabs-ip" aria-selected="false">Allowed IP</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="cron-tab" data-toggle="tab" href="#btabs-cron" role="tab" aria-controls="btabs-cron" aria-selected="false">Cron </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="notification-tab" data-toggle="tab" href="#btabs-notification" role="tab" aria-controls="btabs-notification" aria-selected="false" >Notification</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="enotification-tab" data-toggle="tab" href="#btabs-enotification" role="tab" aria-controls="btabs-enotification" aria-selected="false">Email Notification</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="whatsapp-tab" data-toggle="tab" href="#btabs-whatsapp" role="tab" aria-controls="btabs-whatsapp" aria-selected="false">Whatsapp Notification</a>
            </li>
        </ul>
        <div class="block-content tab-content">
            <div class="tab-pane show active" id="btabs-company" role="tabpanel" aria-labelledby="company-tab">
                <div class="form-group row required">
                    <label class="col-md-2 control-label" for="input-title">Site Title</label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_site_title', 'id' => 'config_site_title', 'placeholder'=>lang('Setting.entry_title'),'value' => set_value('config_site_title', $config_site_title??""))); ?>
                        <?php echo $validation->showError('config_site_title', 'aio_error'); ?>
                    </div>
                </div>
                <div class="form-group row required">
                    <label class="col-md-2 control-label" for="input-tagline"><?php echo lang('Setting.entry_tagline'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_site_tagline', 'id' => 'config_site_tagline', 'placeholder'=>lang('Setting.entry_tagline'),'value' => set_value('config_site_tagline', $config_site_tagline??""))); ?>
                        <?php echo $validation->showError('config_site_tagline', 'aio_error'); ?>
                    </div>
                </div>
                <div class="form-group row required">
                    <label class="col-md-2 control-label" for="input-meta-title"><?php echo lang('Setting.entry_meta_title'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_meta_title', 'id' => 'config_meta_title', 'placeholder'=>lang('Setting.entry_meta_title'),'value' => set_value('config_meta_title', $config_meta_title??""))); ?>
                        <?php echo $validation->showError('config_meta_title', 'aio_error'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="input-meta-title"><?php echo lang('Setting.entry_meta_description'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_textarea(array('class'=>'form-control','name'=>'config_meta_description', 'id'=>'config_meta_description', 'style'=>'height: 100px;','value'=>set_value('config_meta_description',$config_meta_description??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="input-meta-keywords"><?php echo lang('Setting.entry_meta_keyword'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_meta_keywords', 'id' => 'config_meta_keywords', 'placeholder'=>lang('Setting.entry_meta_keyword'),'value' => set_value('config_meta_keywords', $config_meta_keywords??""))); ?>
                    </div>
                </div>
                <div class="form-group row required">
                    <label class="col-md-2 control-label" for="site_owner"><?php echo lang('Setting.entry_site_owner'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_site_owner', 'id' => 'config_site_owner', 'placeholder'=>lang('Setting.entry_site_owner'),'value' => set_value('config_site_owner', $config_site_owner??""))); ?>
                        <?php echo $validation->showError('config_site_owner', 'aio_error'); ?>
                    </div>
                </div>

                <div class="form-group row required">
                    <label class="col-md-2 control-label" for="input-meta-description"><?php echo lang('Setting.entry_address'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_textarea(array('class'=>'form-control','name'=>'config_address', 'id'=>'config_address', 'style'=>'height: 100px;','value'=>set_value('config_address',$config_address??""))); ?>
                        <?php echo $validation->showError('config_address', 'aio_error'); ?>
                    </div>
                </div>


                <div class="form-group row required">
                    <label class="col-md-2 control-label" for="input-meta-keywords"><?php echo lang('Setting.entry_email'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_email', 'id' => 'config_email', 'placeholder'=>lang('Setting.entry_email'),'value' => set_value('config_email', $config_email))); ?>
                        <?php echo $validation->showError('config_email', 'aio_error'); ?>
                    </div>
                </div>
                <div class="form-group row required">
                    <label class="col-md-2 control-label" for="input-meta-keywords"><?php echo lang('Setting.entry_telephone'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_telephone', 'id' => 'config_telephone', 'placeholder'=>lang('Setting.entry_telephone'),'value' => set_value('config_telephone', $config_telephone??""))); ?>
                        <?php echo $validation->showError('config_telephone', 'aio_error'); ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="btabs-system" role="tabpanel" aria-labelledby="system-tab">
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="ssl"><span data-toggle="tooltip" title="<?php echo lang('Setting.help_ssl'); ?>"><?php echo lang('Setting.entry_ssl'); ?></span></label>
                    <div class="col-md-10">
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('class'=>'css-control-input','name' => 'config_ssl', 'id'=>'config_ssl_yes' ,'value' => 'Yes','checked' => ($config_ssl == 'Yes' ? true : false) )); ?>
                            <label for="config_ssl_yes"> Yes </label>
                        </div>
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('class'=>'css-control-input','name' => 'config_ssl', 'id'=>'config_ssl_no' ,'value' => 'No','checked' => ($config_ssl == 'No' ? true : false) )); ?>
                            <label for="config_ssl_no"> No </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="robots"><span data-toggle="tooltip" title="<?php echo lang('Setting.help_robots'); ?>"><?php echo lang('Setting.entry_robots'); ?></span></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control tags','name' => 'config_robots', 'id' => 'config_robots', 'value' => set_value('config_robots', $config_robots??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="time_zone"><?php echo lang('Setting.entry_time_zone'); ?></label>
                    <div class="col-md-10">
                        <select class="form-control js-select2" name="config_time_zone" id="config_time_zone">
                            <option value="0">Please, select timezone</option>
                            <?php foreach($timezone as $optgroup=>$zone){?>
                                <optgroup label="<?=$optgroup?>">
                                    <?php foreach($zone as $key=>$value){?>
                                        <option value="<?=$key?>" <?=($config_time_zone==$key)?"selected='selected'":""?>><?=$value?></option>
                                    <?}?>
                                </optgroup>
                            <?}?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="date_format"><?php echo lang('Setting.entry_date_format'); ?></label>
                    <div class="col-md-10">
                        <div class="radio radio-success">
                            <?php echo form_radio(array('class'=>'css-control-input','id'=>'config_date_format_1','name' => 'config_date_format', 'value' => 'F j, Y','checked' => ($config_date_format == 'F j, Y' ? true : false))); ?>
                            <label for="config_date_format_1">
                                <span class="css-control-indicator"></span> <?=date("F j, Y")?>
                                <code class="label label-default">F j, Y</code>
                            </label>
                        </div>

                        <div class="radio radio-success">
                            <?php echo form_radio(array('class'=>'css-control-input','id'=>'config_date_format_2','name' => 'config_date_format', 'value' => 'Y/m/d','checked' => ($config_date_format == 'Y/m/d' ? true : false))); ?>
                            <label for="config_date_format_2">
                                <span class="css-control-indicator"></span><?=date("Y/m/d")?>
                                <code class="label label-default">Y/m/d</code>
                            </label>
                        </div>
                        <div class="radio radio-success">
                            <?php echo form_radio(array('class'=>'css-control-input','id'=>'config_date_format_3','name' => 'config_date_format', 'value' => 'm/d/Y','checked' => ($config_date_format == 'm/d/Y' ? true : false))); ?>
                            <label for="config_date_format_3">
                                <span class="css-control-indicator"></span><?=date("m/d/Y")?>
                                <code class="label label-default">m/d/Y</code>
                            </label>
                        </div>
                        <div class="radio radio-success">
                            <?php echo form_radio(array('class'=>'css-control-input','id'=>'config_date_format_4','name' => 'config_date_format', 'value' => 'd/m/Y','checked' => ($config_date_format == 'd/m/Y' ? true : false))); ?>
                            <label for="config_date_format_4">
                                <span class="css-control-indicator"></span><?=date("d/m/Y")?>
                                <code class="label label-default">d/m/Y</code>
                            </label>
                        </div>
                        <div class="radio radio-success">
                            <?php echo form_radio(array('class'=>'css-control-input','id'=>'config_date_format_5','name' => 'config_date_format', 'value' => 'custom','checked' => ($config_date_format == 'custom' ? true : false))); ?>
                            <label for="config_date_format_5">
                                <span class="css-control-indicator"></span>Custom
                                <?php echo form_input(array('class'=>'form-control','name' => 'config_date_format_custom', 'id' => 'config_date_format_custom', 'value' => set_value('config_date_format_custom', $config_date_format_custom??""))); ?> <?=date($config_date_format_custom??"")?>
                            </label>
                        </div>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="time_format"><?php echo lang('Setting.entry_time_format'); ?></label>
                    <div class="col-md-10">
                        <div class="radio radio-success">
                            <?php echo form_radio(array('class'=>'css-control-input','id'=>'config_time_format_1','name' => 'config_time_format', 'value' => 'g:i a','checked' =>  ($config_time_format == 'g:i a' ? true : false))); ?>
                            <label for="config_time_format_1">
                                <span class="css-control-indicator"></span><?=date("g:i a")?>
                                <code class="label label-default">g:i a</code>
                            </label>
                        </div>
                        <div class="radio radio-success">
                            <?php echo form_radio(array('class'=>'css-control-input','id'=>'config_time_format_2','name' => 'config_time_format', 'value' => 'g:i A','checked' =>  ($config_time_format == 'g:i A' ? true : false))); ?>
                            <label for="config_time_format_2">
                                <span class="css-control-indicator"></span><?=date("g:i A")?>
                                <code class="label label-default">g:i A</code>
                            </label>
                        </div>
                        <div class="radio radio-success">
                            <?php echo form_radio(array('class'=>'css-control-input','id'=>'config_time_format_3','name' => 'config_time_format', 'value' => 'H:i','checked' =>  ($config_time_format == 'H:i' ? true : false))); ?>
                            <label for="config_time_format_3">
                                <span class="css-control-indicator"></span><?=date("H:i")?>
                                <code class="label label-default">H:i</code>
                            </label>
                        </div>
                        <div class="radio radio-success">
                            <?php echo form_radio(array('class'=>'css-control-input','id'=>'config_time_format_4','name' => 'config_time_format', 'value' => 'custom','checked' =>  ($config_time_format == 'custom' ? true : false))); ?>
                            <label for="config_time_format_4">
                                <span class="css-control-indicator"></span>Custom
                                <?php echo form_input(array('class'=>'form-control','name' => 'config_time_format_custom', 'id' => 'config_time_format_custom', 'value' => set_value('config_time_format_custom', $config_time_format_custom??""))); ?> <?=date($config_time_format_custom??"")?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row required">
                    <label class="col-md-2 control-label" for="pagination_limit_front"><?php echo lang('Setting.entry_pagination_limit_front'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_pagination_limit_front', 'id' => 'config_pagination_limit_front', 'value' => set_value('config_pagination_limit_front', $config_pagination_limit_front??""))); ?>
                        <?php echo $validation->showError('config_pagination_limit_front', 'aio_error'); ?>

                    </div>
                </div>
                <div class="form-group row required">
                    <label class="col-md-2 control-label" for="pagination_limit_admin"><?php echo lang('Setting.entry_pagination_limit_admin'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_pagination_limit_admin', 'id' => 'config_pagination_limit_admin', 'value' => set_value('config_pagination_limit_admin', $config_pagination_limit_admin??""))); ?>
                        <?php echo $validation->showError('config_pagination_limit_admin', 'aio_error'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="seo_url"><span data-toggle="tooltip" title="<?php echo lang('Setting.help_seo_url'); ?>"><?php echo lang('Setting.entry_seo_url'); ?></span></label>
                    <div class="col-md-10">
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('class'=>'css-control-input','name' => 'config_seo_url', 'id'=>'config_seo_url_yes' ,'value' => 'Yes','checked' => ($config_seo_url == 'Yes' ? true : false) )); ?>
                            <label for="config_seo_url_yes"> Yes </label>
                        </div>
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('class'=>'css-control-input','name' => 'config_seo_url', 'id'=>'config_seo_url_no' ,'value' => 'No','checked' => ($config_seo_url == 'No' ? true : false) )); ?>
                            <label for="config_seo_url_no"> No </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="max_file_size"><?php echo lang('Setting.entry_file_max_size'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_max_file_size', 'id' => 'config_max_file_size', 'value' => set_value('config_max_file_size', $config_max_file_size??""))); ?>
                        <span class="help-block"><small>The maximum file size you can allow customers to upload. Enter as megabyte.</small></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="file_extensions"><?php echo lang('Setting.entry_file_extensions'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control tags','name'=>'config_file_extensions', 'id'=>'config_file_extensions', 'value'=>set_value('config_file_extensions',$config_file_extensions??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="file_mimetypes"><?php echo lang('Setting.entry_file_mimetypes'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control tags','name'=>'config_file_mimetypes', 'id'=>'config_file_mimetypes','value'=>set_value('config_file_mimetypes',$config_file_mimetypes??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="maintenance_mode"><?php echo lang('Setting.entry_maintenance_mode'); ?></label>
                    <div class="col-md-10">
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('class'=>'css-control-input','name' => 'config_maintenance_mode', 'id'=>'config_maintenance_mode_yes' ,'value' => 'Yes','checked' => ($config_maintenance_mode == 'Yes' ? true : false) )); ?>
                            <label for="config_maintenance_mode_yes"> Yes </label>
                        </div>
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('class'=>'css-control-input','name' => 'config_maintenance_mode', 'id'=>'config_maintenance_mode_no' ,'value' => 'No','checked' => ($config_maintenance_mode == 'No' ? true : false) )); ?>
                            <label for="config_maintenance_mode_no"> No </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="compression_level"><?php echo lang('Setting.entry_compression_level'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_compression_level', 'id' => 'config_compression_level', 'value' => set_value('config_compression_level', $config_compression_level??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="encryption_key"><?php echo lang('Setting.entry_encryption_key'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_encryption_key', 'id' => 'config_encryption_key', 'value' => set_value('config_encryption_key', $config_encryption_key??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="display_error"><?php echo lang('Setting.entry_display_error'); ?></label>
                    <div class="col-md-10">
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('class'=>'css-control-input','name' => 'config_display_error', 'id'=>'config_display_error_yes' ,'value' => 'Yes','checked' => ($config_display_error == 'Yes' ? true : false) )); ?>
                            <label for="config_display_error_yes"> Yes </label>
                        </div>
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('class'=>'css-control-input','name' => 'config_display_error', 'id'=>'config_display_error_no' ,'value' => 'No','checked' => ($config_display_error == 'No' ? true : false) )); ?>
                            <label for="config_display_error_no"> No </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="log_error"><?php echo lang('Setting.entry_log_error'); ?></label>
                    <div class="col-md-10">
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('class'=>'css-control-input','name' => 'config_log_error', 'id'=>'config_log_error_yes' ,'value' => 'Yes','checked' => ($config_log_error == 'Yes' ? true : false) )); ?>
                            <label for="config_log_error_yes"> Yes </label>
                        </div>
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('class'=>'css-control-input','name' => 'config_log_error', 'id'=>'config_log_error_no' ,'value' => 'No','checked' => ($config_log_error == 'No' ? true : false) )); ?>
                            <label for="config_log_error_no"> No </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="error_log_filename"><?php echo lang('Setting.entry_error_log_filename'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_error_log_filename', 'id' => 'config_error_log_filename', 'value' => set_value('config_error_log_filename', $config_error_log_filename??""))); ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="btabs-theme" role="tabpanel" aria-labelledby="theme-tab">
                <div class="form-group row">
                    <label class="col-sm-2 control-label" for="input-image"><?php echo lang('Setting.entry_logo'); ?></label>
                    <div class="col-sm-3">
                        <div class="card-box bg-secondary">
                            <div class="media justify-content-center text-center">
                                <div class="thumb-img">
                                    <img src="<?php echo $thumb_logo??''; ?>" alt=""  style="height:80px" class=" img-fluid" id="thumb_logo">
                                    <input type="hidden" name="config_site_logo" value="<?php echo $config_site_logo??''?>" id="site_logo" />
                                </div>
                            </div>
                            <hr>
                            <ul class="text-center list-inline m-0">
                                <li class="list-inline-item">
                                    <a class="btn btn-sm btn-outline-primary waves-effect waves-light" onclick="image_upload('site_logo','thumb_logo')">Browse</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn btn-sm btn-outline-danger waves-effect waves-light" onclick="$('#thumb_logo').attr('src', '<?php echo $no_image; ?>'); $('#site_logo').attr('value', '');">Clear</a>
                                </li>

                            </ul>
                        </div>

                    </div>
                    <label class="col-sm-2 control-label" for="input-image"><?php echo lang('Setting.entry_icon'); ?></label>
                    <div class="col-sm-3">
                        <div class="card-box bg-secondary">
                            <div class="media justify-content-center text-center">
                                <div class="thumb-img">
                                    <img src="<?php echo $thumb_icon??''; ?>" alt="" style="height:80px" class=" img-fluid" id="thumb_icon">
                                    <input type="hidden" name="config_site_icon" value="<?php echo $config_site_icon??''?>" id="site_icon" />
                                </div>
                            </div>
                            <hr>
                            <ul class="text-center list-inline m-0">
                                <li class="list-inline-item">
                                    <a class="btn btn-sm btn-outline-primary waves-effect waves-light" onclick="image_upload('site_icon','thumb_icon')">Browse</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn btn-sm btn-outline-danger waves-effect waves-light" onclick="$('#thumb_icon').attr('src', '<?php echo $no_image; ?>'); $('#site_icon').attr('value', '');">Clear</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 control-label" for="admin_theme"><?php echo lang('Setting.entry_admin_theme_mode'); ?></label>
                    <div class="col-md-10">
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('name' => 'config_admin_theme_mode','id'=>'dark-mode-change','data-bsstyle'=>'assets/css/bootstrap-dark.min.css','data-appstyle'=>'assets/css/app-dark.min.css', 'value' => 'dark','checked' => ($config_admin_theme_mode == 'dark' ? true : false) )); ?>
                            <label for="dark_theme"> Dark Theme</label>
                        </div>
                        <div class="radio radio-info form-check-inline">
                            <?php echo form_radio(array('name' => 'config_admin_theme_mode','id'=>'light-mode-change','data-bsstyle'=>'assets/css/bootstrap.min.css','data-appstyle'=>'assets/css/app.min.css', 'value' => 'light','checked' => ($config_admin_theme_mode == 'light' ? true : false) )); ?>
                            <label for="dark_theme"> Light Theme</label>
                        </div>
                        <input type="hidden" name="config_admin_theme" value="default">
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="btabs-email" role="tabpanel" aria-labelledby="email-tab">
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="mail_protocol"><?php echo lang('Setting.entry_mail_protocol'); ?></label>
                    <div class="col-md-10">
                        <?php  echo form_dropdown('config_mail_protocol', array('mail'=>'Mail','smtp' => 'SMTP'), set_value('config_mail_protocol',$config_mail_protocol??"mail"),array('class'=>'form-control select2','id' => 'config_mail_protocol')); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="mail_parameter"><?php echo lang('Setting.entry_mail_parameter'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_mail_parameter', 'id' => 'config_mail_parameter', 'value' => set_value('config_mail_parameter', $config_mail_parameter??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="smtp_host"><?php echo lang('Setting.entry_smtp_host'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_smtp_host', 'id' => 'config_smtp_host', 'value' => set_value('config_smtp_host', $config_smtp_host??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="smtp_username"><?php echo lang('Setting.entry_smtp_username'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_smtp_username', 'id' => 'config_smtp_username', 'value' => set_value('config_smtp_username', $config_smtp_username??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="smtp_password"><?php echo lang('Setting.entry_smtp_password'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_smtp_password', 'id' => 'config_smtp_password', 'value' => set_value('config_smtp_password', $config_smtp_password??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="smtp_port"><?php echo lang('Setting.entry_smtp_port'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_smtp_port', 'id' => 'config_smtp_port', 'value' => set_value('config_smtp_port', $config_smtp_port??""))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 control-label" for="smtp_timeout"><?php echo lang('Setting.entry_smtp_timeout'); ?></label>
                    <div class="col-md-10">
                        <?php echo form_input(array('class'=>'form-control','name' => 'config_smtp_timeout', 'id' => 'config_smtp_timeout', 'value' => set_value('config_smtp_timeout', $config_smtp_timeout??""))); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
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
                    $('#'+thumb).attr('src', decodeURI(url));
                    $('#'+field).attr('value', decodeURI(fileName));

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