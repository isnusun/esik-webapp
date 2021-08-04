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
			<?php echo $app['title'];?>
			<small>Administrasi dan Kependudukan <?php echo $user['wilayah_nama']; ?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">VeriVali Data</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="box box-primary">
				<div class="box-body">
					<ul class="nav nav-pills">
						<li class="nav-item">
							<a href="" class='nav-link'>Tambah Wilayah</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('backend/administrasi');?>">Formulir Data Wilayah Administrasi <?php echo $user['wilayah_nama']?></a></h3>
				</div>
				<div class="box-body">
				<ol class="breadcrumb">
			<?php

				if(count($alamat_bc)> 0){
					$nama = "";
					foreach($alamat_bc as $key=>$item){
						if($user['tingkat'] <=1){
							if(strlen($item['kode']) >= strlen($user['wilayah'])){
								echo "<li class=\"breadcrumb-item \"><a href=\"".site_url('backend/administrasi/?kode=').$item['kode']."\">".$item['nama']."</a></li>";
								$nama = $item['nama'];
							}else{
								echo "<li class=\"breadcrumb-item \"><a href=\"".site_url('backend/administrasi/?kode=').KODE_BASE."\">".$item['nama']."</a></li>";
							}
	
						}else{
							if(strlen($item['kode']) >= strlen($user['wilayah'])){
								echo "<li class=\"breadcrumb-item \"><a href=\"".site_url('backend/administrasi/?kode=').$item['kode']."\">".$item['nama']."</a></li>";
								$nama = $item['nama'];
							}else{
								echo "<li class=\"breadcrumb-item \"><a href=\"#\">".$item['nama']."</a></li>";
							}
	
						}
					}
				}
			?>

			</ol>								
					<form action="<?php echo site_url('backend/administrasi/wilayah_add_save/')?>" method="POST" class="formular" role="form">
						<div class="form-group">
							<label>Nama Wilayah <?php echo $subNama;?></label>
							<input class="form-control validate[required]" name="nama" id="nama" />
						</div>
						<div class="form-group">
							<input type="hidden" name="kode" value="<?php echo $varKode;?>" />
							<input type="hidden" name="tingkat" value="<?php echo $subTingkat;?>" />
							<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>Simpan</button>
						</div>
					</form>
				<?php 

				?>
				</div>
			</div>

		</section>
		</div>
<!-- footer section -->
<!-- Modal -->
<div class="modal fade" id="EditWilayahModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="memberModalLabel">Edit Data Wilayah<strong><label id="relawanTitle"></label></strong></h4>
			</div>
			<div class="dash">
			<!-- Content goes in here -->
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="HapusWilayahModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="memberModalLabel">Hapus Data Wilayah<strong><label id="relawanTitle"></label></strong></h4>
			</div>
			<div class="modal-body">
				<h4>Yakin akan menghapus data Wilayah Administrasi: <strong id="hapus_wilayah_nama"></strong></h4>

			<!-- Content goes in here -->
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default"><i class="fa fa-backward fa-fw"></i>Batal</a>
				<a href="<?php ?>" class="btn btn-danger"><i class="fa fa-trash fa-fw"></i>Hapus</a>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="MoveRTSModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="memberModalLabel">Memindahkan Rumah Tangga<strong><label id="relawanTitle"></label></strong></h4>
			</div>
			<div class="dash">
			<!-- Content goes in here -->
			</div>
		</div>
	</div>
</div>

<!-- ValidationEngine -->
<script src="<?php echo base_url()?>assets/plugins/validation-engine/jquery.validationEngine.js"></script>
<script src="<?php echo base_url()?>assets/plugins/validation-engine/languages/jquery.validationEngine-id.js"></script>

<!-- DataTables CSS -->
<link href="<?php echo base_url("assets/plugins/"); ?>datatables/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- Datatables-->
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>jszip/jszip.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/buttons.colVis.min.js"></script>
<script>
	
$(document).ready(function() {
    $('table.datatables').DataTable( {
				"language": {
                "url": "<?php echo base_url("assets/plugins/"); ?>datatables/datatables_ID.js"
            },
        dom: 'Bfrtip',
        buttons: [
					'excelHtml5',
					'csvHtml5',
					{extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A4'
					},
					'print',
					{
						extend: 'colvis',
						columns: ':gt(2)'
					}
        ]
    } );
} );
</script>

<?php


$this->load->view('siteman/siteman_footer');
