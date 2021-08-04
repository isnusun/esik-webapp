    <footer>
      <div class="container">
        <div class="row">
          <div class="col-md-7">
            <h3 class="footer-title">
            </h3>
            <a href="<?php echo site_url('siteman'); ?>" class="btn btn-primary">Masuk ke dalam Sistem</a>
            <p>&copy;<?php echo COPY_YEAR; ?>
            <?php
						if(ENVIRONMENT=='development'){
							echo "<br />". $this->benchmark->memory_usage();
						}
            
            
            ?>
            </p>
          </div> <!-- /col-xs-7 -->

          <div class="col-md-5">
            <div class="footer-banner">
              <h3 class="footer-title"><?php echo APP_TITLE; ?></h3>
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
	<script src="<?php echo base_url('assets/plugins/bootstrap-submenu/js/bootstrap-submenu.min.js'); ?>" defer></script>
	<script>
		$(document).ready(function(){
			$('[data-submenu]').submenupicker();
		});
	</script>
	
	<script src="<?php echo base_url('assets/js/classie.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/uiMorphingButton_inflow.js'); ?>"></script>
	<!-- Magnific Popup core JS file -->
	<script src="<?php echo base_url('assets/js/jquery.magnific-popup.js'); ?>"></script> 

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-35956217-19', 'auto');
  ga('send', 'pageview');

</script>
</Body>

</html>
