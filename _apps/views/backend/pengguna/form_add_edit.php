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
			<?php echo $pageTitle;?>
			<small>Panel Pengguna</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Data Pengguna</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-9 col-sm-6 col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?php echo $boxTitle;?></h3>
					</div>
					<form action="<?php echo $form_action; ?>" enctype="multipart/form-data" method="POST" role="form" class="formular">
					
					<div class="box-body">
						<div class="form-group has-warning">
							<label for="inputNama" class="control-label">Nama Lengkap</label>
							<input type="text" class="form-control validate[required]" id="nama" placeholder="Tuliskan Nama Karyawan" name="nama" value="<?php echo $pengguna['nama']; ?>">
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group  has-warning">
										<label for="inputAlamatEmail" class="control-label">Email</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
											<?php 
											$ajaxEmail = ($id > 0) ? "":",ajax[ajaxUserEmail]";
											?>
											<input type="text" class="form-control validate[required,custom[email]<?php echo $ajaxEmail; ?>" id="email" placeholder="Enter email" name="email" value="<?php echo $pengguna['email']; ?>"></div>
										</div>
									</div>
								<div class="col-xs-6">
								<label for="inputAlamat" class="control-label">Website</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-globe fa-fw"></i></span>
									<input type="text" class="form-control validate[optional]" id="exampleInputUrl" placeholder="Tuliskan website" name="url" value="<?php echo $pengguna['url']; ?>"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
								<div class="form-group has-warning">
								<label for="inputAlamat" class="control-label">No HP.</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-mobile fa-fw"></i></span>
									<input type="phone" class="form-control validate[required, custom[phone]]" id="exampleInputFax" placeholder="Tuliskan nomor handphone" name="nohp" value="<?php echo $pengguna['nohp']; ?>"></div>
								</div>
								</div>
								<div class="col-xs-6">
								<label for="exampleInputPhone" class="control-label">Telp</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
									<input type="phone" class="form-control validate[optional, custom[phone]]" id="exampleInputPhone" placeholder="Tuliskan nomer telp" name="telp" value="<?php echo $pengguna['telp']; ?>"></div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="inputProfil" class="control-label">Alamat</label>
							<textarea class="form-control validate[optional]" placeholder="Tuliskan alamat" name="alamat" id="alamat"><?php echo $pengguna['alamat']; ?></textarea>
						</div><!--/form-group-->
						<div class="form-group">
							<label for="inputProfil" class="control-label">Foto</label>
							<?php 
							if($id > 0){
								echo $pengguna['foto'];
							}
							?>
							<input type="file" class="form-control" name="foto" id="foto" />
						</div><!--/form-group-->
						<div class="form-group">
							<label for="inputProfil" class="control-label">Profil</label>
							<textarea class="summernote form-control" placeholder="Tuliskan keterangna profil kantor" name="ndesc" id="ndesc"><?php // echo $pengguna['$ndesc']; ?></textarea>
						</div><!--/form-group-->

						<div class="form-group has-warning">
							<label for="inputAlamat" class="control-label">Peran dalam Sistem</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-star"></i></span>
								<select name="tingkat" class="form-control validate[required]" id="tingkat">
									<option value="">Pilih Level Akses</option>
								<?php 
								
								foreach ($user_level as $key=>$item) {
									if($key >= $user['tingkat']){
										$strS = (trim($key)==trim($pengguna['tingkat']))? " selected=\"selected\"":"";
										echo "<option value=\"".$key."\" ".$strS.">".$item." ".$pengguna['tingkat']."</option>";
									}
								}
								?>
								</select>
							</div>
						</div><!--/form-group-->

						<div class="form-group has-warning">
							<label for="inputAlamat" class="control-label">Wilayah Kerja</label>
							<div class="input-group has-warning">
								<span class="input-group-addon"><i class="fa fa-map-o"></i></span>
								<select name="kode_wilayah" class="form-control validate[required]" id="kode_wilayah">
									<option value="">Pilih Wilayah</option>
										<?php
										if($user['tingkat'] >= 3){
											echo "<option value=\"".$user['wilayah']."\" selected=\"selected\">&nbsp;&nbsp;&nbsp;-&nbsp;Kelurahan ".$user['wilayah_nama']."</option>";
										}else{
											echo "<option value=\"".$app['kode_wilayah']."\">SELURUH WILAYAH KERJA</option>";
											foreach($kecamatan as $key=>$item){
												$strS = ($item['kode'] == $pengguna['wilayah']) ? "selected=\"selected\"":"";
												echo "<option value=\"".$item['kode']."\">Kecamatan ".$item['nama']."</option>";
												foreach ($desa as $de=>$sa){
													if(substr($de,0,7) == $key){
														$strS = ($de == $pengguna['wilayah']) ? "selected=\"selected\"":"";
														echo "<option value=\"".$de."\" ".$strS.">&nbsp;&nbsp;&nbsp;-&nbsp;Desa/Kel. ".$sa['nama']."</option>";
													}
												}
											}
										}
										?>
										
									</select>
								</select>
							</div>
						</div><!--/form-group-->

						<div class="form-group">
						<?php 
						
						if($id==0){
							?>
							<label class="control-label">Akun @<?php echo $app['app_title']; ?></label>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group  has-warning">
									<label for="inputAlamat">Username</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
										<input type="text" name="userid" id="userid" value="" class="form-control validate[required,ajax[ajaxUser]]"  value=""/>
									</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group  has-warning">
										<label for="inputAlamat">Password</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-lock"></i></span>
											<input type="password" name="pass1" id="pass1" class="form-control validate[required]"/>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group  has-warning">
										<label for="inputAlamat">Ketik Ulang Password</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-lock"></i></span>
											<input type="password" name="pass2" id="pass2" class="form-control validate[required,equals[pass1]]"/>
										</div>
									</div>
								</div>
							</div>
							<?php 
						}else{
							?>
							<h5 class="has-error"><em>Pengaturan Ulang Password untuk <strong><?php echo $pengguna['nama']; ?> [<?php echo $pengguna['userid']; ?>]</strong></em></h5>
							<p class="help-block">Isian ini hanya digunakan jika anda hendak mengganti password untuk <strong><?php echo $pengguna['nama']; ?></strong></p>
							<input type="hidden" name="userid" id="userid" class="form-control"  value="<?php echo $pengguna['userid']; ?>"/>
							<div class="row">
								<div class="col-md-4">
									<label for="inputAlamat">Password</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										<input type="password" name="pass1" id="pass1" class="form-control validate[optional,minSize[6]]"/>
									</div>
								</div>
								<div class="col-md-4">
									<label for="inputAlamat">Ketik Ulang Password</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										<input type="password" name="pass2" id="pass2" class="form-control validate[optional,minSize[6],equals[pass1]]"/>
									</div>
								</div>
							</div>
							<?php 
						}
						?>
						</div><!--/box-body-->
						<div class="box-footer">
							<a class="btn btn-default" href="<?php echo site_url('admin/pengguna')?>"><i class="fa fa-refresh"></i> Batal</a>
							<input type="hidden" name="id" value="<?php echo $id;?>"/>
							<input type="hidden" name="referer" value="<?php echo uri_string();?>"/>
							<button type="submit" name="submit" id="submit" value="1" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
						</div>
						<?php echo form_close();?>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Pengelolaan Data Pengguna</h3>
						</div>
						<div class="box-body">
							<table class="table">
								<thead><tr>
									<th>Icon</th>
									<th>Keterangan</th>
								</tr></thead>
								<tbody>
									<tr><td><i class="fa fa-pencil"></i></td>
										<td>Link/tautan untuk mengubah data</td></tr>
									<tr><td><i class="fa fa-trash"></i></td>
										<td>Link/tautan menghapus data pada baris bersangkutan.</td></tr>
									<tr><td><i class="fa fa-user-plus"></i></td>
										<td>Link/tautan menambah data pengguna baru</td></tr>
									<tr><td><i class="fa fa-th-list"></i></td>
										<td>Biasa digunakan pada tombol menuju tautan daftar/indek dari halamannya</td></tr>
								</tbody>
							</table>							
						</div>
					</div>

				</div>
			</div>
		</section>
	</div>
<!-- footer section -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Konfirmasi Penghapusan</h4>
                </div>
                <div class="modal-body">
                    <p>Anda hendak menghapus data <b><i class="title"></i></b>, tidak bisa di-kembali-kan.</p>
                    <p>Lanjut?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-ok">Hapus</button>
                </div>
            </div>
        </div>
    </div>
<!-- ValidationEngine -->

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/validation-engine/validationEngine.jquery.css'); ?>" />
<script src="<?php echo base_url('assets/plugins/validation-engine/jquery.validationEngine.js'); ?>"></script>
<script src="<?php echo site_url('backend/jsphp/jqueryValidationEngineId'); ?>"></script>
<script>
$(document).ready(function() {
	var formular = $("form.formular");
	if(formular){
		$("form.formular").validationEngine();
	}
	$('#confirm-delete').on('click', '.btn-ok', function(e) {
			var $modalDiv = $(e.delegateTarget);
			var id = $(this).data('recordId');
			var strUrl = '<?php echo site_url('admin/pengguna/');?>' + id +'/hapus';
			window.location.replace(strUrl);
			/*
			$.ajax({url: strUrl, type: 'DELETE'})
			*/
			$modalDiv.addClass('loading');
			setTimeout(function() {
					$modalDiv.modal('hide').removeClass('loading');
			}, 1000)
			
	});
	$('#confirm-delete').on('show.bs.modal', function(e) {
			var data = $(e.relatedTarget).data();
			$('.title', this).text(data.recordTitle);
			$('.btn-ok', this).data('recordId', data.recordId);
	});
});
</script>
<?php

$this->load->view('siteman/siteman_footer');
