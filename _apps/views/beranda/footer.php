      <!-- FOOTER -->
      <footer class="main-footer">
        <div class="container">
          <div class="row">
            <div class="col-lg-3">
              <h4 class="h6">Tentang Kami</h4>
              <p><strong><?php echo $app['owner'];?></p>
              <hr>
              <h4 class="h6">Berlangganan Kabar</h4>
              <form>
                <div class="input-group">
                  <input type="text" class="form-control">
                  <div class="input-group-append">
                    <button type="button" class="btn btn-secondary"><i class="fa fa-send"></i></button>
                  </div>
                </div>
              </form>
              <hr class="d-block d-lg-none">
            </div>

            <div class="col-lg-3">
              <h4 class="h6">Nara Hubung:</h4>
              <p><strong><?php echo $app['owner'];?>
              <br /><?php echo $app['alamat_kantor'];?>
              </p>
              <hr class="d-block d-lg-none">
            </div>
            <div class="col-lg-6">
              <ul class="list-inline photo-stream">
                <li class="list-inline-item"><a href="#"><img src="<?php  echo base_url('assets/');?>img/detailsquare.jpg" alt="..." class="img-fluid"></a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="copyrights">
          <div class="container">
            <div class="row">
              <div class="col-lg-4 text-center-md">
                <p>&copy; <?php echo date('Y');?>.  <?php echo $app['app_title'];?> 
								<br /><?php echo $app['alamat_kantor'];?>
                </p>
              </div>
              <div class="col-lg-8 text-right text-center-md">
                <p><?php echo $app['app_title'];?></a></p>
                <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <!-- Javascript files-->
    <script src="<?php echo base_url('assets/'); ?>vendor/waypoints/lib/jquery.waypoints.min.js"> </script>
    <script src="<?php echo base_url('assets/'); ?>vendor/jquery.counterup/jquery.counterup.min.js"> </script>
    <script src="<?php echo base_url('assets/'); ?>vendor/owl.carousel/owl.carousel.min.js"></script>
    <script src="<?php echo base_url('assets/'); ?>vendor/owl.carousel2.thumbs/owl.carousel2.thumbs.min.js"></script>
    <script src="<?php echo base_url('assets/'); ?>js/jquery.parallax-1.1.3.js"></script>
    <script src="<?php echo base_url('assets/'); ?>vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script src="<?php echo base_url('assets/'); ?>vendor/jquery.scrollto/jquery.scrollTo.min.js"></script>
    <script src="<?php echo base_url('assets/'); ?>js/sik_front.js"></script>
  </body>
</html>