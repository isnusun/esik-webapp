<?php $this->load->view('pubs/header');
?>
      <div id="content">
			<div id="contact" class="container">
          <section class="bar">
            <div class="row">
              <div class="col-md-12">
                <div class="heading">
                  <h2>Kami ada untuk membantu sesama kita</h2>
                </div>
                <p class="lead">Anda merasa penasaran akan suatu hal tentang kami? Anda memiliki permasalahan dengan layanan kami? 
								Sampaikan kepada kami apa yang anda rasakan.</p>
              </div>
            </div>
          </section>
          <section>
            <div class="row text-center">
              <div class="col-md-4">
                <div class="box-simple">
                  <div class="icon-outlined"><i class="fa fa-map-marker"></i></div>
                  <h3 class="h4">Alamat</h3>
                  <p>Jl. Veteran No.54, Ngabeyan, Jetis
									<br>Kec. Sukoharjo, Kab. Sukoharjo
									<br>Jawa Tengah,  <strong>Indonesia</strong></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="box-simple">
                  <div class="icon-outlined"><i class="fa fa-phone"></i></div>
                  <h3 class="h4">Call center</h3>
                  <p>Hubungi nomor telepon berikut ini pada jam kerja.</p>
                  <p><strong>+62 (0271) 593182</strong></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="box-simple">
                  <div class="icon-outlined"><i class="fa fa-envelope"></i></div>
                  <h3 class="h4">Dukungan Elektronis</h3>
                  <p>Atau Anda juga bisa memanfaatkan fitur komunikasi menggunakan surate elektronis melalui formulir berikut ini, atau kriim langsung melai     </p>
                  <ul class="list-unstyled text-sm">
                    <li><strong><a href="mailto:bappelbangda@sukoharjokab.go.id">bappelbangda@sukoharjokab.go.id</a></strong></li>
                  </ul>
                </div>
              </div>
            </div>
          </section>
          <section class="bar pt-0">
            <div class="row">
              <div class="col-md-12">
                <div class="heading text-center">
                  <h2>Formulir Narahubung</h2>
                </div>
              </div>
              <div class="col-md-8 mx-auto">
                <form>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="firstname">Nama Depan</label>
                        <input id="firstname" type="text" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="lastname">Nama Belakang</label>
                        <input id="lastname" type="text" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="text" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="subject">Judul</label>
                        <input id="subject" type="text" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="message">Isi Pesan</label>
                        <textarea id="message" class="form-control"></textarea>
                      </div>
                    </div>
                    <div class="col-sm-12 text-center">
                      <button type="submit" class="btn btn-template-outlined"><i class="fa fa-envelope-o"></i> Kirimkan Pesan</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </section>
        </div>
        <div id="map"></div>

      </div>
		<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAP_KEY;?>"></script>
    <script src="<?php echo base_url('assets/'); ?>js/gmaps.js"></script>
    <script src="<?php echo base_url('assets/'); ?>js/gmaps.init.js"></script>

<?php 
$this->load->view('pubs/footer');