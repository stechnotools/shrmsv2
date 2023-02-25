<?php
$template = service('template');
$validation = \Config\Services::validation();
$session = service('session');
?>
<div class="wrapper-page">

    <div class="logo text-center">
        <?php if ($logo) { ?>
            <img width="120px" src="<?php echo $logo; ?>" title="<?php echo $site_name; ?>" alt="<?php echo $site_name; ?>"  />
        <?php } else { ?>
            <span><?php echo $site_name; ?></span>
        <?php } ?>
    </div>
    <br />
    <div class="card card-pages">
        <!--<div class="card-header bg-img p-0">
           <h3 class="text-center m-t-10 text-white"> Admin <strong>Login</strong> </h3>
        </div> -->
        <div class="card-body">
            <?php echo form_open($action,array('class' => 'form-horizontal', 'id' => 'form-signin','role'=>'form')); ?>
            <?php if(isset($error)){?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?}else if($session->getFlashdata('message')){?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="fa fa-exclamation-circle"></i> <?php echo $session->getFlashdata('message'); ?>
                </div>
            <?}?>
            <div class="form-group">

                <label for="input-username"><?php echo $entry_username; ?></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="username" id="input-username" class="form-control "  value="" placeholder="<?php echo $entry_username; ?>" required="" autofocus="">
                </div>

            </div>
            <div class="form-group">

                <label for="input-password"><?php echo $entry_password; ?></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" id="input-password" class="form-control" value="" placeholder="<?php echo $entry_password; ?>" required="">
                </div>

            </div>
            <div class="form-group row m-t-10">
                <div class="col-sm-5">
                    <div class="checkbox checkbox-primary">
                        <input type="checkbox" value="" name="rememberme" id="checkbox-signup">
                        <label for="checkbox-signup">
                            <?=$text_remember?>
                        </label>
                    </div>
                </div>
                <div class="col-sm-7 text-right">
                    <a href="pages-recoverpw.html"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                </div>
            </div>
            <?php if ($redirect) { ?>
                <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
            <?php } ?>
            <div class="form-group text-center m-t-10">
                <div class="col-12">
                    <button class="btn btn-primary btn-block btn-sm w-lg waves-effect waves-light" type="submit">Log In</button>
                </div>
            </div>


            <?php echo form_close(); ?>
        </div>
    </div>
</div>

   