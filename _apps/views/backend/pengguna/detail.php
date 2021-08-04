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
			<li class="active">Detail Pengguna</li>
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
						<div class="box-body">
							<div class="form-group has-warning">
								<label for="inputNama" class="control-label">Nama Lengkap</label>
								<input type="text" class="form-control validate[required]" id="nama" readonly="readonly" name="nama" value="<?php echo $pengguna['nama']; ?>">
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
												<input type="text" class="form-control validate[required,custom[email]<?php echo $ajaxEmail; ?>" id="email" readonly="readonly" name="email" value="<?php echo $pengguna['email']; ?>"></div>
											</div>
										</div>
									<div class="col-xs-6">
									<label for="inputAlamat" class="control-label">Website</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-globe fa-fw"></i></span>
										<input type="text" class="form-control validate[optional]" id="exampleInputUrl" placeholder="Tuliskan website" name="url" readonly="readonly"  value="<?php echo $pengguna['url']; ?>"></div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-6">
									<div class="form-group has-warning">
									<label for="inputAlamat" class="control-label">No HP.</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-mobile fa-fw"></i></span>
										<input type="phone" class="form-control validate[required, custom[phone]]" id="exampleInputFax" readonly="readonly"  placeholder="Tuliskan nomor handphone" name="nohp" value="<?php echo $pengguna['nohp']; ?>"></div>
									</div>
									</div>
									<div class="col-xs-6">
									<label for="exampleInputPhone" class="control-label">Telp</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
										<input type="phone" class="form-control validate[optional, custom[phone]]" id="exampleInputPhone" placeholder="Tuliskan nomer telp" readonly="readonly"  name="telp" value="<?php echo $pengguna['telp']; ?>"></div>
									</div>
								</div>
							</div>
							<div class="form-group has-warning">
								<label for="inputAlamat" class="control-label">Peran dalam Sistem</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-star"></i></span>
									<input class="form-control" readonly="readonlye" value="<?php echo $user_level[$pengguna['tingkat']]; ?>" />
								</div>
							</div><!--/form-group-->

							<div class="form-group has-warning">
								<label for="inputAlamat" class="control-label">Wilayah Kerja</label>
								<div class="input-group has-warning">
									<span class="input-group-addon"><i class="fa fa-map-o"></i></span>
									<input class="form-control" readonly="readonlye" value="<?php echo $pengguna['wilayah_nama']; ?>" />
								</div>
							</div><!--/form-group-->
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
