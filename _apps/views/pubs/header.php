<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $app['title']; ?></title>
	<meta name="description" content="<?php echo $app['title']; ?>">
	<meta name="author" content="<?php echo $app['owner']; ?>">
	<meta name="description" content="<?php echo $app['description']; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="all,follow">
	<!-- Bootstrap CSS-->
	<link rel="stylesheet" href="<?php echo base_url('assets/'); ?>vendor/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome CSS-->
	<link rel="stylesheet" href="<?php echo base_url('assets/'); ?>vendor/font-awesome/css/font-awesome.min.css">
	<!-- Google fonts - Roboto-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,700">
	<!-- Bootstrap Select-->
	<link rel="stylesheet" href="<?php echo base_url('assets/'); ?>vendor/bootstrap-select/css/bootstrap-select.min.css">
	<!-- owl carousel-->
	<link rel="stylesheet" href="<?php echo base_url('assets/'); ?>vendor/owl.carousel/assets/owl.carousel.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/'); ?>vendor/owl.carousel/assets/owl.theme.default.css">
	<!-- theme stylesheet-->
	<link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/style.red.css" id="theme-stylesheet">
	<!-- Custom stylesheet - for your changes-->
	<link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/custom_sik.css">
	<!-- Favicon and apple touch icons-->
	<link rel="shortcut icon" href="<?php echo base_url('favicon.ico'); ?>" type="image/x-icon">
	<link rel="apple-touch-icon" href="<?php echo base_url('assets/'); ?>img/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url('assets/'); ?>img/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('assets/'); ?>img/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('assets/'); ?>img/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('assets/'); ?>img/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url('assets/'); ?>img/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url('assets/'); ?>img/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url('assets/'); ?>img/apple-touch-icon-152x152.png">
	<!-- Tweaks for older IEs-->
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
	<script src="<?php echo base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>vendor/popper.js/umd/popper.min.js"> </script>
	<script src="<?php echo base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>vendor/jquery.cookie/jquery.cookie.js"> </script>

</head>

<body>
	<div id="all">
		<!-- Top bar-->
		<div class="top-bar">
			<div class="container">
				<div class="row d-flex align-items-center">
					<div class="col-md-6 d-md-block d-none">

						<?php
						if ($app['telp']) {
							echo '<p>Hubungi kami di ';
							echo '<phone><i class="fa fa-phone fa-fw"></i>' . $app['telp'] . '</phone>';
						}
						if ($app['fax']) {
							echo '<i class="fa fa-print fa-fw"></i>' . $app['fax'] . '';
						}
						?>
						</p>
					</div>
					<div class="col-md-6">
						<div class="d-flex justify-content-md-end justify-content-between">
							<ul class="list-inline contact-info d-block d-md-none">
								<li class="list-inline-item"><a href="#"><i class="fa fa-phone"></i></a></li>
								<li class="list-inline-item"><a href="#"><i class="fa fa-envelope"></i></a></li>
							</ul>
							<div class="login">
								<a href="#" data-toggle="modal" data-target="#login-modal" class="login-btn"><i class="fa fa-sign-in"></i><span class="d-none d-md-inline-block">Sign In</span></a>
								<!-- <a href="customer-register.html" class="signup-btn"><i class="fa fa-user"></i><span class="d-none d-md-inline-block">Sign Up</span></a> -->
							</div>
							<ul class="social-custom list-inline">
								<?php
								if ($app['google_plus']) {
									echo '<li class="list-inline-item"><a href="' . $app['google_plus'] . '"><i class="fa fa-google-plus"></i></a></li>';
								}
								if ($app['facebook']) {
									echo '<li class="list-inline-item"><a href="' . $app['facebook'] . '"><i class="fa fa-facebook"></i></a></li>';
								}
								if ($app['twitter']) {
									echo '<li class="list-inline-item"><a href="' . $app['twitter'] . '"><i class="fa fa-twitter"></i></a></li>';
								}
								if ($app['email']) {
									echo '<li class="list-inline-item"><a href="' . $app['email'] . '"><i class="fa fa-envelope"></i></a></li>';
								}

								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Top bar end-->
		<!-- Login Modal-->
		<div id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modalLabel" aria-hidden="true" class="modal fade">
			<div role="document" class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 id="login-modalLabel" class="modal-title text-primary">User Login</h4>
						<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
					</div>
					<div class="modal-body">
						<form action="<?php echo site_url('siteman/auth') ?>" method="post">
							<div class="form-group">
								<input id="username" name="username" type="text" placeholder="Tuliskan akun anda" class="form-control">
							</div>
							<div class="form-group">
								<input id="password_modal" name="password" type="password" placeholder="Tuliskan kata sandi anda" class="form-control">
							</div>
							<p class="text-center">
								<button class="btn btn-template-outlined"><i class="fa fa-sign-in"></i> Log in</button>
							</p>
						</form>
						<!-- <p class="text-center text-muted">Not registered yet?</p>
				<p class="text-center text-muted"><a href="customer-register.html"><strong>Register now</strong></a>! It is easy and done in 1 minute and gives you access to special discounts and much more!</p> -->
					</div>
				</div>
			</div>
		</div>
		<!-- Login modal end-->
		<!-- Navbar Start-->
		<header class="nav-holder make-sticky">
			<div id="navbar" role="navigation" class="navbar navbar-expand-lg">
				<div class="container"><a href="/" class="navbar-brand home"><img style="max-width:200px;" src="<?php echo base_url('assets/'); ?>img/logo.png" alt="<?php echo $app['title']; ?>" class="d-none d-md-inline-block"><img src="<?php echo base_url('assets/'); ?>img/logo-small.png" alt="<?php echo $app['title']; ?>" class="d-inline-block d-md-none"><span class="sr-only"><?php echo $app['title']; ?></span></a>
					<button type="button" data-toggle="collapse" data-target="#navigation" class="navbar-toggler btn-template-outlined"><span class="sr-only">Toggle navigation</span><i class="fa fa-align-justify"></i></button>
					<div id="navigation" class="navbar-collapse collapse">
						<ul class="nav navbar-nav ml-auto">
							<li><a href="<?php echo site_url(); ?>">Beranda</a></li>
							<li class="nav-item dropdown menu-large"><a href="#" data-toggle="dropdown" class="dropdown-toggle">DTKS<b class="caret"></b></a>
								<ul class="dropdown-menu megamenu">
									<li>
										<div class="row">
											<div class="col-md-6 col-lg-4">
												<h5>Data Berbasis Wilayah Administratif</h5>
												<ul class="list-unstyled mb-3">
													<?php

													foreach ($kecamatan as $key => $rs) {
														echo '<li class="nav-item"><a href="' . site_url('publik/pbdt_wilayah/' . $rs['kode']) . '" class="nav-link">Kecamatan ' . $rs['nama'] . '</a></li>';
													}
													?>
												</ul>
											</div>
											<div class="col-md-6 col-lg-4">
												<h5><a href="<?php echo site_url('publik/pbdt_indikator/rts'); ?>">Indikator Rumah Tangga</a></h5>
												<ul class="list-unstyled mb-3">
													<?php
													foreach ($indikator['rts'] as $key => $rs) {
														if ($rs['jenis'] == 'pilihan') {
															echo '<li class="nav-item"><a href="' . site_url('publik/indikator_detail/rts/' . $rs['nama']) . '/" class="nav-link">' . $rs['label'] . '</a></li>';
														}
													}
													?>
												</ul>
											</div>
											<div class="col-md-6 col-lg-4">
												<h5><a href="<?php echo site_url('publik/pbdt_indikator/art'); ?>">Indikator Penduduk Individu</a></h5>
												<ul class="list-unstyled mb-3">
													<?php
													foreach ($indikator['art'] as $key => $rs) {
														if ($rs['jenis'] == 'pilihan') {
															echo '<li class="nav-item"><a href="' . site_url('publik/indikator_detail/art/' . $rs['nama']) . '/" class="nav-link">' . $rs['label'] . '</a></li>';
														}
													}
													?>
												</ul>
											</div>
										</div>
									</li>
								</ul>
							</li>
							<li class="nav-item dropdown menu-large"><a href="https://sideka-wonogiri.pbdt.web.id/" target="_blank" class="dropdown-toggle">Kabar Berita <b class="caret"></b></a>

							</li>
							<!-- ========== Contact dropdown ==================-->
							<li><a href="<?php echo site_url('/beranda/hubungikami') ?>">Hubungi Kami</a></li>
							<!-- ========== Contact dropdown end ==================-->
						</ul>
					</div>
					<div id="search" class="collapse clearfix">
						<form role="search" class="navbar-form">
							<div class="input-group">
								<input type="text" placeholder="Search" class="form-control"><span class="input-group-btn">
									<button type="submit" class="btn btn-template-main"><i class="fa fa-search"></i></button></span>
							</div>
						</form>
					</div>
				</div>
			</div>
		</header>
		<!-- Navbar End-->

		<div id="heading-breadcrumbs">
			<div class="container">
				<div class="row d-flex align-items-center flex-wrap">
					<div class="col-md-7">
						<h1 class="h2 text-primary"><?php echo $app['title']; ?></h1>
					</div>
					<div class="col-md-5">
						<ul class="breadcrumb d-flex justify-content-end">
							<li class="breadcrumb-item"><a href="/">Beranda</a></li>
							<?php
							if ($breadcrumb) {
								foreach ($breadcrumb as $key => $value) {
									# code...
									echo "<li class=\"breadcrumb-item\"><a href=\"" . $value['url'] . "\">" . $value['name'] . "</a></li>";
								}
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>