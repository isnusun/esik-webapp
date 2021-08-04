<?php 
$this->siteman_login->cek_login();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $pageTitle; ?> | <?php echo $app['title']; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/fonts/css/font-awesome.min.css'); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/AdminLTE.css'); ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    <link rel="stylesheet" href="<?php echo base_url('assets/css/AdminLTE.min.css'); ?>">
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/skins/skin-blue.min.css'); ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/iCheck/square/blue.css'); ?>">
    <!-- Custom style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/Siteman_styles.css'); ?>">


    <!-- JS Library -->
		<script src="<?php echo base_url('assets/plugins/jQuery/jQuery.min.js') ?>"></script>
		<script src="<?php echo base_url('assets/js/lodash.min.js') ?>"></script>
		<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>

		<script src="<?php echo base_url('assets/plugins/pace/pace.min.js') ?>"></script>
		
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
		
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo site_url("siteman")?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">
						<img src="<?php echo base_url('assets/img/'); ?>android-icon-36x36.png" />
						</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><?php echo $app['app_title'] ; ?></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo $user['foto']['m']; ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $user['nama'];?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo $user['foto']['a']; ?>" class="img-circle" alt="User Image">
                    <p>
											<?php echo $user['nama'] ." - ". $user['lembaga_nama'];?>
                      <small>Terdaftar sejak <?php echo indonesian_date(strtotime($user['created_at']),"F Y",""); ?></small>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo site_url('siteman/profil'); ?>" class="btn btn-default btn-flat">Profilku</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo site_url('siteman/logout'); ?>" class="btn btn-default btn-flat">Keluar / Log Out </a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
               -->
            </ul>
          </div>

        </nav>
      </header>
