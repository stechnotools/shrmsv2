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

		<!-- Fonts and Codebase framework -->
        <link href="<?php echo theme_url('assets/css/bootstrap.min.css');  ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo theme_url('assets/css/icons.css');  ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo theme_url('assets/css/style.css');  ?>" rel="stylesheet" type="text/css" />
        <!-- Controller Defined Stylesheets -->
		<?php echo $template->stylesheets() ?>
        <script src="<?php echo theme_url('assets/js/modernizr.min.js');  ?>"></script>
        <script type="text/javascript">
            var BASE_URL = '<?php echo base_url(); ?>';
            var ADMIN_URL = '<?php echo admin_url(); ?>';
            var THEME_URL = '<?php echo theme_url(); ?>';
        </script>
		<!-- Controller Defined JS Files -->
		<?php echo $template->javascripts() ?>
	</head>
	<body class="fixed-left <?=$class?>">
		<?php if ($user->isLogged() ){?>
        <div id="wrapper">
            <div class="topbar">
                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="<?=admin_url()?>" class="logo">
                            <?php if ($logo) { ?>
                                <!--<img width="100%" height="40px" src="<?php echo $logo; ?>" title="<?php echo $site_name; ?>" alt="<?php echo $site_name; ?>"  />-->
                            <?php } ?>
                            <span><?php echo $site_name; ?></span>
                        </a>
                    </div>
                </div>
                <!-- Button mobile view to collapse sidebar menu -->

                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <ul class="list-inline menu-left mb-0">
                            <li class="float-left">
                                <a href="#" class="button-menu-mobile open-left">
                                    <i class="fa fa-bars"></i>
                                </a>
                            </li>
                            <li class="hide-phone float-left">
                                <form role="search" class="navbar-form">
                                    <input type="text" placeholder="Type here for search..." class="form-control search-bar">
                                    <a href="" class="btn btn-search"><i class="fa fa-search"></i></a>
                                </form>
                            </li>
                        </ul>

                        <ul class="nav navbar-right float-right list-inline">
                            <?php if($relogin){?>
                                <li>
                                    <a href="<?=admin_url('relogin')?>" class="waves-effect waves-light">
                                        <i class="fa fa-times text-danger"></i>
                                    </a>

                                </li>
                            <?}?>
                            <li class="dropdown">
                                <a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true"><img src="<?=$profile_img?>" alt="user-img" class="rounded-circle"> </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?=$profile?>" class="dropdown-item"><i class="md md-face-unlock mr-2"></i> Profile</a></li>
                                    <li><a href="<?=$settings?>"class="dropdown-item"><i class="md md-settings mr-2"></i> Settings</a></li>
                                    <li><a href="<?=$logout?>" class="dropdown-item"><i class="md md-settings-power mr-2"></i> Logout</a></li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </nav>
            </div>
            <?php echo $menu;?>

        <?}?>
