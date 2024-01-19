<?php
$template = service('template');
$validation = \Config\Services::validation();
$session = service('session');
?>
<div class="account-pages my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <?php if($login_error){?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="fa fa-exclamation-circle"></i> <?php echo $login_error; ?>
                    </div>
                <?}?>

                <div class="card mt-4">
                    <div class="logo text-center">
                        <?php if ($logo) { ?>
                            <img width="120px" src="<?php echo $logo; ?>" title="<?php echo $site_name; ?>" alt="<?php echo $site_name; ?>"  />
                        <?php } else { ?>
                            <span><?php echo $site_name; ?></span>
                        <?php } ?>
                    </div>
                    <div class="card-body p-4 mt-2">
                        <?php echo form_open($action,array('class' => 'p-3', 'id' => 'form-signin','role'=>'form')); ?>
                            <div class="form-group mb-3">
                                <input type="text" name="username" id="input-username" class="form-control "  value="" placeholder="<?php echo $entry_username; ?>" required="" autofocus="">
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" name="password" id="input-password" class="form-control" value="" placeholder="<?php echo $entry_password; ?>" required="">
                            </div>


                            <div class="form-group row m-t-10">
                                <div class="col-sm-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signin">
                                        <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>
                                <div class="col-sm-8 text-right">
                                    <a href="pages-recoverpw.html"><i class="fa fa-lock mr-1"></i> Forgot your password?</a>
                                </div>
                            </div>
                            <?php if ($redirect) { ?>
                                <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                            <?php } ?>
                            <div class="form-group text-center mt-5 mb-4">
                                <button class="btn btn-primary btn-block waves-effect width-md waves-light" type="submit"> Log In </button>
                            </div>
                        <?php echo form_close(); ?>

                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->

                <!-- end row -->

            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

    </div>

</div>

   