 <!-- FlatFy Theme - Andrea Galanti /-->
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="description" content="Flatfy Free Flat and Responsive HTML5 Template ">
	<meta name="author" content="">

	<title><?php echo APP_TITLE; ?></title>

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
				<li id="download" ><a href="<?php echo site_url('dlmangka'); ?>" class="btn btn-lg btn-warning wow swing wow fadeIn" data-wow-delay="1.2s"><span class="network-name"><i class="fa fa-line-chart"></i> Capaian Kesejahteraan</span></a></li>
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
	?>
	
	<!-- What is -->
	<div id="whatis" class="content-section-b" style="border-top: 0">
		<div class="container">

			<div class="col-md-6 col-md-offset-3 text-center wrap_title">
				<h2>What is?</h2>
				<p class="lead" style="margin-top:0">A special thanks to Death.</p>
				
			</div>
			
			<div class="row">
			
				<div class="col-sm-4 wow fadeInDown text-center">
				  <img class="rotate" src="<?php echo base_url('assets/img/'); ?>icon/tweet.svg" alt="Generic placeholder image">
				  <h3>Follow Me</h3>
				  <p class="lead">Epsum factorial non deposit quid pro quo hic escorol. Olypian quarrels et gorilla congolium sic ad nauseum. </p>

				  <!-- <p><a class="btn btn-embossed btn-primary view" role="button">View Details</a></p> -->
				</div><!-- /.col-lg-4 -->
				
				<div class="col-sm-4 wow fadeInDown text-center">
				  <img  class="rotate" src="<?php echo base_url('assets/img/'); ?>icon/picture.svg" alt="Generic placeholder image">
				   <h3>Gallery</h3>
				   <p class="lead">Epsum factorial non deposit quid pro quo hic escorol. Olypian quarrels et gorilla congolium sic ad nauseum. </p>
				   <!-- <p><a class="btn btn-embossed btn-primary view" role="button">View Details</a></p> -->
				</div><!-- /.col-lg-4 -->
				
				<div class="col-sm-4 wow fadeInDown text-center">
				  <img  class="rotate" src="<?php echo base_url('assets/img/'); ?>icon/retina.svg" alt="Generic placeholder image">
				   <h3>Retina</h3>
					<p class="lead">Epsum factorial non deposit quid pro quo hic escorol. Olypian quarrels et gorilla congolium sic ad nauseum. </p>
				  <!-- <p><a class="btn btn-embossed btn-primary view" role="button">View Details</a></p> -->
				</div><!-- /.col-lg-4 -->
				
			</div><!-- /.row -->
				
			<div class="row tworow">
			
				<div class="col-sm-4  wow fadeInDown text-center">
				  <img class="rotate" src="<?php echo base_url('assets/img/'); ?>icon/laptop.svg" alt="Generic placeholder image">
				  <h3>Responsive</h3>
				  <p class="lead">Epsum factorial non deposit quid pro quo hic escorol. Olypian quarrels et gorilla congolium sic ad nauseum. </p>
				 <!-- <p><a class="btn btn-embossed btn-primary view" role="button">View Details</a></p> -->
				</div><!-- /.col-lg-4 -->
				
				<div class="col-sm-4 wow fadeInDown text-center">
				  <img  class="rotate" src="<?php echo base_url('assets/img/'); ?>icon/map.svg" alt="Generic placeholder image">
				   <h3>Google</h3>
				   <p class="lead">Epsum factorial non deposit quid pro quo hic escorol. Olypian quarrels et gorilla congolium sic ad nauseum. </p>
				   <!-- <p><a class="btn btn-embossed btn-primary view" role="button">View Details</a></p> -->
				</div><!-- /.col-lg-4 -->
				
				<div class="col-sm-4 wow fadeInDown text-center">
				  <img  class="rotate" src="<?php echo base_url('assets/img/'); ?>icon/browser.svg" alt="Generic placeholder image">
				   <h3>Bootstrap</h3>
				 <p class="lead">Epsum factorial non deposit quid pro quo hic escorol. Olypian quarrels et gorilla congolium sic ad nauseum. </p>
				  <!-- <p><a class="btn btn-embossed btn-primary view" role="button">View Details</a></p> -->
				</div><!-- /.col-lg-4 -->
				
			</div><!-- /.row -->
		</div>
	</div>
	
	<!-- Use it -->
    <div id ="useit" class="content-section-a">

        <div class="container">
			
            <div class="row">
			
				<div class="col-sm-6 pull-right wow fadeInRightBig">
                    <img class="img-responsive " src="<?php echo base_url('assets/img/'); ?>ipad.png" alt="">
                </div>
				
                <div class="col-sm-6 wow fadeInLeftBig"  data-animation-delay="200">   
                    <h3 class="section-heading">Full Responsive</h3>
					<div class="sub-title lead3">Lorem ipsum dolor sit atmet sit dolor greand fdanrh<br> sdfs sit atmet sit dolor greand fdanrh sdfs</div>
                    <p class="lead">
						In his igitur partibus duabus nihil erat, quod Zeno commuta rest gestiret. 
						Sed virtutem ipsam inchoavit, nihil ampliusuma. Scien tiam pollicentur, 
						uam non erat mirum sapientiae lorem cupido
						patria esse cariorem. Quae qui non vident, nihilamane umquam magnum ac cognitione.
					</p>

					 <p><a class="btn btn-embossed btn-primary" href="#" role="button">View Details</a> 
					 <a class="btn btn-embossed btn-info" href="#" role="button">Visit Website</a></p>
				</div>   
            </div>
        </div>
        <!-- /.container -->
    </div>

    <div class="content-section-b"> 
		
		<div class="container">
            <div class="row">
                <div class="col-sm-6 wow fadeInLeftBig">
                     <div id="owl-demo-1" class="owl-carousel">
						<a href="img/iphone.png" class="image-link">
							<div class="item">
								<img  class="img-responsive img-rounded" src="<?php echo base_url('assets/img/'); ?>iphone.png" alt="">
							</div>
						</a>
						<a href="img/iphone.png" class="image-link">
							<div class="item">
								<img  class="img-responsive img-rounded" src="<?php echo base_url('assets/img/'); ?>iphone.png" alt="">
							</div>
						</a>
						<a href="img/iphone.png" class="image-link">
							<div class="item">
								<img  class="img-responsive img-rounded" src="<?php echo base_url('assets/img/'); ?>iphone.png" alt="">
							</div>
						</a>
					</div>       
                </div>
				
                <div class="col-sm-6 wow fadeInRightBig"  data-animation-delay="200">   
                    <h3 class="section-heading">Drag Gallery</h3>
					<div class="sub-title lead3">Lorem ipsum dolor sit atmet sit dolor greand fdanrh<br> sdfs sit atmet sit dolor greand fdanrh sdfs</div>
                    <p class="lead">
						In his igitur partibus duabus nihil erat, quod Zeno commuta rest gestiret. 
						Sed virtutem ipsam inchoavit, nihil ampliusuma. Scien tiam pollicentur, 
						uam non erat mirum sapientiae lorem cupido
						patria esse cariorem. Quae qui non vident, nihilamane umquam magnum ac cognitione.
					</p>

					 <p><a class="btn btn-embossed btn-primary" href="#" role="button">View Details</a> 
					 <a class="btn btn-embossed btn-info" href="#" role="button">Visit Website</a></p>
				</div>  			
			</div>
        </div>
    </div>

    <div class="content-section-a">

        <div class="container">

             <div class="row">
			 
				<div class="col-sm-6 pull-right wow fadeInRightBig">
                    <img class="img-responsive " src="<?php echo base_url('assets/img/'); ?>doge.png" alt="">
                </div>
			 
                <div class="col-sm-6 wow fadeInLeftBig"  data-animation-delay="200">   
                    <h3 class="section-heading">Font Awesome & Glyphicon</h3>
                    <p class="lead">A special thanks to Death to the Stock Photo for 
					providing the photographs that you see in this template. 
					</p>
					
					<ul class="descp lead2">
						<li><i class="glyphicon glyphicon-signal"></i> Reliable and Secure Platform</li>
						<li><i class="glyphicon glyphicon-refresh"></i> Everything is perfectly orgainized for future</li>
						<li><i class="glyphicon glyphicon-headphones"></i> Attach large file easily</li>
					</ul>
				</div>           
            </div>
        </div>

    </div>

	<!-- Screenshot -->
	<div id="screen" class="content-section-b">
        <div class="container">
          <div class="row" >
			 <div class="col-md-6 col-md-offset-3 text-center wrap_title ">
				<h2>Screen App</h2>
				<p class="lead" style="margin-top:0">A special thanks to Death.</p>
			 </div>
		  </div>
		    <div class="row wow bounceInUp" >
              <div id="owl-demo" class="owl-carousel">
				
				<a href="img/slide/1.png" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="<?php echo base_url('assets/img/'); ?>slide/1.png" alt="Owl Image">
					</div>
				</a>
				
               <a href="img/slide/2.png" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="<?php echo base_url('assets/img/'); ?>slide/2.png" alt="Owl Image">
					</div>
				</a>
				
				<a href="img/slide/3.png" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="<?php echo base_url('assets/img/'); ?>slide/3.png" alt="Owl Image">
					</div>
				</a>
				
				<a href="img/slide/1.png" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="<?php echo base_url('assets/img/'); ?>slide/1.png" alt="Owl Image">
					</div>
				</a>
				
               <a href="img/slide/2.png" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="<?php echo base_url('assets/img/'); ?>slide/2.png" alt="Owl Image">
					</div>
				</a>
				
				<a href="img/slide/3.png" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="<?php echo base_url('assets/img/'); ?>slide/3.png" alt="Owl Image">
					</div>
				</a>
              </div>       
          </div>
        </div>


	</div>
	
	<div  class="content-section-c ">
		<div class="container">
			<div class="row">
			<div class="col-md-6 col-md-offset-3 text-center white">
				<h2>Get Live Updates</h2>
				<p class="lead" style="margin-top:0">A special thanks to Death.</p>
			 </div>
			<div class="col-md-6 col-md-offset-3 text-center">
				<div class="mockup-content">
						<div class="morph-button wow pulse morph-button-inflow morph-button-inflow-1">
							<button type="button "><span>Subscribe to our Newsletter</span></button>
							<div class="morph-content">
								<div>
									<div class="content-style-form content-style-form-4 ">
										<h2 class="morph-clone">Subscribe to our Newsletter</h2>
										<form>
											<p><label>Your Email Address</label><input type="text"/></p>
											<p><button>Subscribe me</button></p>
										</form>
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>	
			</div>>
		</div>
	</div>	
	
	<!-- Credits -->
	<div id="credits" class="content-section-a">
		<div class="container">
			<div class="row">
			
			<div class="col-md-6 col-md-offset-3 text-center wrap_title">
				<h2>Credits</h2>
				<p class="lead" style="margin-top:0">A special thanks to Death.</p>
			 </div>
			 
				<div class="col-sm-6  block wow bounceIn">
					<div class="row">
						<div class="col-md-4 box-icon rotate"> 
							<i class="fa fa-desktop fa-4x "> </i> 
						</div>
						<div class="col-md-8 box-ct">
							<h3> Bootstrap </h3>
							<p> Lorem ipsum dolor sit ametconsectetur adipiscing elit. Suspendisse orci quam. </p>
						</div>
				  </div>
			  </div>
			  <div class="col-sm-6 block wow bounceIn">
					<div class="row">
					  <div class="col-md-4 box-icon rotate"> 
						<i class="fa fa-picture-o fa-4x "> </i> 
					  </div>
					  <div class="col-md-8 box-ct">
						<h3> Owl-Carousel </h3>
						<p> Nullam mo  arcu ac molestie scelerisqu vulputate, molestie ligula gravida, tempus ipsum.</p> 
					  </div>
					</div>
			  </div>
		  </div>
		  
		  <div class="row tworow">
				<div class="col-sm-6  block wow bounceIn">
					<div class="row">
						<div class="col-md-4 box-icon rotate"> 
							<i class="fa fa-magic fa-4x "> </i> 
						</div>
						<div class="col-md-8 box-ct">
							<h3> Codrops </h3>
							<p> Lorem ipsum dolor sit ametconsectetur adipiscing elit. Suspendisse orci quam. </p>
						</div>
				  </div>
			  </div>
			  <div class="col-sm-6 block wow bounceIn">
					<div class="row">
					  <div class="col-md-4 box-icon rotate"> 
						<i class="fa fa-heart fa-4x "> </i> 
					  </div>
					  <div class="col-md-8 box-ct">
						<h3> Lorem Ipsum</h3>
						<p> Nullam mo  arcu ac molestie scelerisqu vulputate, molestie ligula gravida, tempus ipsum.</p> 
					  </div>
					</div>
			  </div>
		  </div>
		</div>
	</div>
	
	<!-- Banner Download -->
	<div id="downloadlink" class="banner">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center wrap_title">
				<h2>Download Free</h2>
				<p class="lead" style="margin-top:0">Pay with a Tweet</p>
				<p><a class="btn btn-embossed btn-primary view" role="button">Free Download</a></p> 
			 </div>
			</div>
		</div>
	</div>
	
	<!-- Contact -->
	<div id="contact" class="content-section-a">
		<div class="container">
			<div class="row">
			
			<div class="col-md-6 col-md-offset-3 text-center wrap_title">
				<h2>Contact Us</h2>
				<p class="lead" style="margin-top:0">A special thanks to Death.</p>
			</div>
			
			<form role="form" action="" method="post" >
				<div class="col-md-6">
					<div class="form-group">
						<label for="InputName">Your Name</label>
						<div class="input-group">
							<input type="text" class="form-control" name="InputName" id="InputName" placeholder="Enter Name" required>
							<span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span>
						</div>
					</div>
					
					<div class="form-group">
						<label for="InputEmail">Your Email</label>
						<div class="input-group">
							<input type="email" class="form-control" id="InputEmail" name="InputEmail" placeholder="Enter Email" required  >
							<span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span>
						</div>
					</div>
					
					<div class="form-group">
						<label for="InputMessage">Message</label>
						<div class="input-group">
							<textarea name="InputMessage" id="InputMessage" class="form-control" rows="5" required></textarea>
							<span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span>
						</div>
					</div>

					<input type="submit" name="submit" id="submit" value="Submit" class="btn wow tada btn-embossed btn-primary pull-right">
				</div>
			</form>
			
			<hr class="featurette-divider hidden-lg">
				<div class="col-md-5 col-md-push-1 address">
					<address>
					<h3>Office Location</h3>
					<p class="lead"><a href="https://www.google.com/maps/preview?ie=UTF-8&q=The+Pentagon&fb=1&gl=us&hq=1400+Defense+Pentagon+Washington,+DC+20301-1400&cid=12647181945379443503&ei=qmYfU4H8LoL2oATa0IHIBg&ved=0CKwBEPwSMAo&safe=on">The Pentagon<br>
					Washington, DC 20301</a><br>
					Phone: XXX-XXX-XXXX<br>
					Fax: XXX-XXX-YYYY</p>
					</address>

					<h3>Social</h3>
					<li class="social"> 
					<a href="#"><i class="fa fa-facebook-square fa-size"> </i></a>
					<a href="#"><i class="fa  fa-twitter-square fa-size"> </i> </a> 
					<a href="#"><i class="fa fa-google-plus-square fa-size"> </i></a>
					<a href="#"><i class="fa fa-flickr fa-size"> </i> </a>
					</li>
				</div>
			</div>
		</div>
	</div>
	
	
	
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-md-7">
            <h3 class="footer-title">Follow Me!</h3>
            <p>Vuoi ricevere news su altri template?<br/>
              Visita Andrea Galanti.it e vedrai tutte le news riguardanti nuovi Theme!<br/>
              Go to: <a  href="http://andreagalanti.it" target="_blank">andreagalanti.it</a>
            </p>
			
			<!-- LICENSE -->
			<a rel="cc:attributionURL" href="http://www.andreagalanti.it/flatfy"
		   property="dc:title">Flatfy Theme </a> by
		   <a rel="dc:creator" href="http://www.andreagalanti.it"
		   property="cc:attributionName">Andrea Galanti</a>
		   is licensed to the public under 
		   <BR>the <a rel="license"
		   href="http://creativecommons.org/licenses/by-nc/3.0/it/deed.it">Creative
		   Commons Attribution 3.0 License - NOT COMMERCIAL</a>.
		   
	   
          </div> <!-- /col-xs-7 -->

          <div class="col-md-5">
            <div class="footer-banner">
              <h3 class="footer-title">Flatfy Theme</h3>
              <ul>
                <li>12 Column Grid Bootstrap</li>
                <li>Form Contact</li>
                <li>Drag Gallery</li>
                <li>Full Responsive</li>
                <li>Lorem Ipsum</li>
              </ul>
              Go to: <a href="http://andreagalanti.it/flatfy" target="_blank">andreagalanti.it/flatfy</a>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!-- JavaScript -->
	<script src="<?php echo base_url('assets/js/owl.carousel.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>
	<!-- StikyMenu -->
	<script src="<?php echo base_url('assets/js/stickUp.min.js'); ?>"></script>
	<script type="text/javascript">
	  jQuery(function($) {
		$(document).ready( function() {
		  $('.navbar-default').stickUp();
		  
		});
	  });
	
	</script>
	<!-- Smoothscroll -->
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.corner.js'); ?>"></script> 
	<script src="<?php echo base_url('assets/js/wow.min.js'); ?>"></script>
	<script>
	 new WOW().init();
	</script>
	<script src="<?php echo base_url('assets/js/classie.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/uiMorphingButton_inflow.js'); ?>"></script>
	<!-- Magnific Popup core JS file -->
	<script src="<?php echo base_url('assets/js/jquery.magnific-popup.js'); ?>"></script> 
</body>

</html>
