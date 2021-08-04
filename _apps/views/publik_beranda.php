<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="description" content="<?php echo $pageDesc;?>">
	<meta name="author" content="">

	<title><?php echo $pageTitle; ?></title>

	<!-- Bootstrap 3.3.5 -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">

	<!-- Custom Google Web Font -->
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url('assets/fonts/css/font-awesome.min.css'); ?>">
	<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Arvo:400,700' rel='stylesheet' type='text/css'>

	<!-- Custom CSS-->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/general.css'); ?>" rel="stylesheet">

	<!-- Owl-Carousel -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/owl.carousel.css'); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/owl.theme.css'); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/animate.css'); ?>" rel="stylesheet">

	<!-- Magnific Popup core CSS file -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/magnific-popup.css'); ?>"> 

	<!-- JS Library -->
	<script src="<?php echo base_url('assets/plugins/jQuery/jQuery.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
	
	<script src="<?php echo base_url('assets/js/modernizr-2.8.3.min.js'); ?>"></script>  <!-- Modernizr /-->
	<!--[if IE 9]>
		<link rel="stylesheet" href="<?php echo base_url('assets/js/PIE_IE9.js'); ?>"></script>
	<![endif]-->
	<!--[if lt IE 9]>
		<link rel="stylesheet" href="<?php echo base_url('assets/js/PIE_IE678.js'); ?>"></script>
	<![endif]-->

	<!--[if lt IE 9]>
		<link rel="stylesheet" href="<?php echo base_url('assets/js/html5shiv.js'); ?>"></script>
	<![endif]-->

</head>

<body id="home">

	<!-- Preloader -->
	<div id="preloader">
		<div id="status"></div>
	</div>
	
	<!-- FullScreen -->
    <div class="intro-header">
		<div class="col-xs-12 text-center abcen1">
			<h1 class="h1_home wow fadeIn" data-wow-delay="0.4s">TKPKD</h1>
			<h3 class="h3_home wow fadeIn" data-wow-delay="0.6s"><?php echo APP_TITLE; ?></h3>
			<ul class="list-inline intro-social-buttons">
				<li><a href="<?php echo site_url('publikasi/'); ?>" class="btn btn-lg mybutton_cyano wow fadeIn" data-wow-delay="0.8s"><span class="network-name"><i class="fa fa-newspaper-o"></i> Publikasi</span></a></li>
				<li id="download" ><a href="<?php echo site_url('dlmangka/pbdt'); ?>" class="btn btn-lg btn-warning wow swing wow fadeIn" data-wow-delay="1.2s"><span class="network-name"><i class="fa fa-line-chart"></i> Capaian Kesejahteraan</span></a></li>
			</ul>
		</div>    
        <!-- /.container -->
		<div class="col-xs-12 text-center abcen wow fadeIn">
			<div class="button_down "> 
				<a class="imgcircle wow bounceInUp" data-wow-duration="1.5s"  href="#whatis"> <img class="img_scroll" src="<?php echo base_url('assets/img/'); ?>icon/circle.png" alt=""> </a>
			</div>
		</div>
    </div>
	
	<!-- NavBar-->
	<?php
	$this->load->view('navbar');
	$this->load->view('footer');
	?>
	
	<!-- What is -->


