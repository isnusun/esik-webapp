<?php
/*
 * siteman_dashboard.php
 * 
 * Copyright 2016 Isnu Suntoro <isnusun@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

$this->load->view('siteman/siteman_header');
?>
<!-- Left side column. contains the logo and sidebar -->
<?php
$this->load->view('siteman/siteman_sidebar');
?>
 <!-- Content Wrapper. Contains page content -->

	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
			<?php echo APP_TITLE;?>
			<small>Verifikasi dan Validasi Data <?php echo $user['wilayah_nama']; ?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">VeriVali Data</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			$this->load->view('verivali/_header');
			?>
			<div class="callout callout-info">
				<h4>Pendaftaran Rumah Tangga Baru (<?php echo $user['wilayah']?>)</h4>
				<p>Formulir ini digunakan untuk menambahkan data Rumah Tangga Sasaran Baru.</p>
			</div>
			<div class="row">
				<div class="col-md-8 col-sm-6 col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><a href="<?php echo site_url('verivali');?>"><?php echo $boxTitle;?></a></h3>
						</div>
						<div class="box-body">
							<form action="<?php echo $form_action; ?>" method="POST" class="formular" role="form">
								<div class="form-group" id="p_cari">
									<label class="form-label">Nomor Urut Rumah Tangga Sasaran</label>
									<input type="text" class="form-control" name="rtm_no" id="rtm_no" value="<?php echo $new_rtm_no?>" readonly/>
								</div>
								<fieldset class="kotak">
									<legend>Domisili Rumah Tangga</legend>
									
									<div class="form-group">
										<label class="label-control">Kecamatan</label>
										<div class="" id="">
											<?php 
											$strDisabled = " disabled=\"disabled\"";
											if(strlen($user['wilayah']) == 4){
												$strDisabled = "";
											}
											?>
											<select id="kec_id" class="form-control validate[required]" name="rts_kecamatan" placeholder="Kecamatan ..." <?php echo $strDisabled;?>>
												<?php 
												foreach($kecamatan as $key=>$item){
													$strS = ($key==substr($user['wilayah'],0,7)) ? "selected=\"selected\"":"";
													echo "<option value=\"".$key."\" ".$strS.">".$item['nama']."</option>";
												}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="label-control">Kelurahan</label>
										<div class="" id="">
											<?php 
											$strDisabled = " disabled=\"disabled\"";
											if(strlen($user['wilayah']) == 4){
												$strDisabled = "";
											}
											?>
											<select id="kel_id" class="form-control validate[required]" name="rts_kelurahan" placeholder="Kelurahan ..." <?php echo $strDisabled;?>>

												<?php 
												foreach($kelurahan as $key=>$item){
													$strS = ($key==substr($user['wilayah'],0,10)) ? "selected=\"selected\"":"";
													echo "<option value=\"".$key."\" ".$strS.">".$item['nama']."</option>";
												}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="label-control">RW</label>
										<div class="" id="">
											<select id="rw_id" class="form-control validate[required]" name="rts_rw" placeholder="RW ...">
												<?php 
												foreach($rws as $key=>$item){
													echo "<option value=\"".$key."\">".$item['nama']."</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="label-control">RT</label>
										<div class="" id="">
											<select id="rt_id" class="form-control validate[required]" name="rts_rt" placeholder="RT ...">
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="label-control">Alamat</label>
										<div id="">
											<textarea class="form-control validate[required]" id="rts_alamat" name="rts_alamat" placeholder="Tuliskan alamat Rumah Tangga, nama jalan/gang nomor rumah"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="label-control">Nomor Kartu Keluarga</label>
										<div id="">
											<input type="text" class="form-control validate[required]" id="rts_nokk" name="rts_nokk" placeholder="Tuliskan alamat Nomor Kartu Keluarga"/>
										</div>
									</div>
									
								</fieldset>
								<div class="form-group">
									<div class="">
										<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
									</div>
								</div>
							</form>
							
						</div>
					</div>

					
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Penduan Pendaftaran Rumah Tangga Baru</h3>
						</div>
						<div class="box-body">
							<p>Untuk mendaftarkan RTS baru, silakan gunakan formulir ini:</p>
							<ol>
								<li>Pertama, isi dan lengkapi data alamat/domisili RTS. Nomor Urut RTS akan otomatis. Klik tombol Simpan dibawah formulir ini.</li>
								<li>Setelah data domisili tersimpan, anda akan diminta untuk mengisikan data NOMOR Kartu Keluarga RTS yang akan ditambahkan.</li>
								<li>Sistem akan mensinkronkan data ke SIAK/Dukcapil</li>
								<li>Bila data KK valid dan sudah tersinkronkan, akan muncul data Anggota KK yg sekaligus juga jadi Anggota Rumah Tangga</li>
								<li>Jangan lupa untuk menjadikan KK baru yang ditambahkan ini sebagai KK Utama</li>
								<li>Selanjutnya sila isikan data dari formulir visit lapangannya.</li>
							</ol>

						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<!-- footer section -->
<!-- ValidationEngine & CSS ValidationEngine -->
<link href="<?php echo base_url('assets/plugins/'); ?>validation-engine/validationEngine.jquery.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url()?>assets/plugins/validation-engine/jquery.validationEngine.js"></script>
<script src="<?php echo base_url()?>assets/plugins/validation-engine/languages/jquery.validationEngine-id.js"></script>

<!-- Select INTERDEPENDENT -->
<link href="<?php echo base_url("assets/plugins/"); ?>dependent-dropdown/css/dependent-dropdown.css " rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/plugins/"); ?>dependent-dropdown/js/dependent-dropdown.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>dependent-dropdown/js/locales/id.js"></script>
<script>
$(document).ready(function() {	
	/*
	 * jQueryValidation
	 */
						
	var formular = $("form.formular");
	if(formular){
		$("form.formular").validationEngine();
	}
	
	/*
	Dependant dropdown
	*/
	var kel = $("#rw_id");
	if(kel){
		<?php 
		if(strlen($user['wilayah'])==4){
			?>
			$("#kel_id").depdrop({
				url: '<?php echo site_url('ajax/siteman_wilayah/'.substr($user['wilayah'],0,10));?>',
				depends: ['kec_id'],
				loadingText: 'Memuat daftar Kelurahan ...'
			});
			$("#rw_id").depdrop({
				url: '<?php echo site_url('ajax/siteman_wilayah/'.substr($user['wilayah'],0,10));?>',
				depends: ['kel_id'],
				loadingText: 'Memuat daftar RW ...'
			});
			<?php
		}
		
		
		?>
		/*
		*/
		$("#rt_id").depdrop({
			depends: ['rw_id'],
			initialize: true,
			initDepends: ['rw_id'],
			url: '<?php echo site_url('ajax/siteman_wilayah/a10000');?>',
			loadingText: 'Memuat daftar RT ...'
		});
	}
		
});	
</script>

<?php


$this->load->view('siteman/siteman_footer');
