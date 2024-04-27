<?php
$template=service('template');
$user=service('user');
?>
<!doctype html>
<html lang="en" class="no-focus">
	<head>
		<base href="<?=base_url()?>">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="robots" content="noindex, nofollow">
		<?php echo $template->metadata() ?>

		<!-- Icons -->
		<!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
		<link rel="shortcut icon" href="<?=theme_url('assets/images/favicon.ico');?>">
		<!-- END Icons -->
        <?php echo $template->stylesheets() ?>
		<!-- Fonts and Codebase framework -->
        <link href="<?php echo $bootstrapStylesheet;  ?>" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?php echo theme_url('assets/css/icons.min.css');  ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo $appStylesheet;  ?>" rel="stylesheet" type="text/css" id="app-stylesheet"  />

        <!-- Controller Defined Stylesheets -->
        <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
        <link href="<?php echo theme_url('assets/css/custom.css');  ?>" rel="stylesheet" type="text/css" />
        <noscript>
        </noscript>

        <script type="text/javascript">
            var BASE_URL = '<?php echo base_url(); ?>';
            var ADMIN_URL = '<?php echo admin_url(); ?>';
            var THEME_URL = '<?php echo theme_url(); ?>';
        </script>
		<!-- Controller Defined JS Files -->
		<?php echo $template->javascripts() ?>
	</head>
	<body class="<?=$class?>">
		<?php if ($user->isLogged() ){?>
        <div id="wrapper">
            <div class="navbar-custom">
                <div class="logo-box">
                    <a href="<?=admin_url()?>" class="logo text-center logo-light">
                        <span class="logo-lg">
                            <?php if ($logo) { ?>
                            <img width="90%" height="50" src="<?php echo $logo; ?>" title="<?php echo $site_name; ?>" alt="<?php echo $site_name; ?>"  />
                            <?php }else{?>
                            <span class="logo-lg-text-light"><?php echo $site_name; ?></span>
                            <?}?>
                        </span>
                    </a>
                </div>

                <!-- LOGO -->
                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>
                </ul>
                <ul class="list-unstyled topnav-menu float-right mb-0">
                    <?php if($relogin){?>
                        <li class="dropdown notification-list">
                            <a href="<?=admin_url('relogin')?>" class="nav-link right-bar-toggle waves-effect waves-light">
                                <i class="fa fa-times text-danger"></i>
                            </a>
                        </li>
                    <?}?>
                    <li class="dropdown notification-list d-none d-md-inline-block">
                        <a href="#" id="btn-fullscreen" class="nav-link waves-effect waves-light">
                            <i class="mdi mdi-crop-free noti-icon"></i>
                        </a>
                    </li>

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="mdi mdi-bell noti-icon"></i>
                            <span class="badge badge-danger rounded-circle noti-icon-badge">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="font-16 m-0">
                                    <span class="float-right">
                                        <a href="" class="text-dark">
                                            <small>Clear All</small>
                                        </a>
                                    </span>Notification
                                </h5>
                            </div>

                            <div class="slimscroll noti-scroll">

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon">
                                        <i class="fa fa-user-plus text-info"></i>
                                    </div>
                                    <p class="notify-details">New user registered
                                        <small class="noti-time">You have 10 unread messages</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon text-success">
                                        <i class="far fa-gem text-primary"></i>
                                    </div>
                                    <p class="notify-details">New settings
                                        <small class="noti-time">There are new settings available</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon text-danger">
                                        <i class="far fa-bell text-danger"></i>
                                    </div>
                                    <p class="notify-details">Updates
                                        <small class="noti-time">There are 2 new updates available</small>
                                    </p>
                                </a>
                            </div>

                            <!-- All-->
                            <a href="javascript:void(0);" class="dropdown-item text-center notify-item notify-all">
                                    See all notifications
                            </a>

                        </div>
                    </li>

                    <li class="dropdown notification-list">


                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="<?=$profile_img?>" alt="user-image" class="rounded-circle">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome <?=$name?></h6>
                            </div>

                            <!-- item-->
                            <a href="<?=$profile?>" class="dropdown-item notify-item">
                                <i class="mdi mdi-face-profile"></i>
                                <span>Profile</span>
                            </a>

                            <!-- item-->
                            <a href="<?=$settings?>" class="dropdown-item notify-item">
                                <i class="mdi mdi-settings"></i>
                                <span>Settings</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="<?=$logout?>" class="dropdown-item notify-item">
                                <i class="mdi mdi-power-settings"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </li>




                </ul>

                <!-- Button mobile view to collapse sidebar menu -->


            </div>
            <?php echo $menu;?>

        <?}?>
