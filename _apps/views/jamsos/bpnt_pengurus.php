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
			<small><?php echo $pageTitle ."::". $user['wilayah_nama']; ?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active"><?php echo $pageTitle ?></li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('layanan_jamsos');?>"><?php echo $pageTitle;?></a></h3>
					<div class="box-tools pull-right">
						<?php 
						if($user['tingkat'] <= 2){
							echo '<a class="btn btn-box-tool btn-secondary" data-toggle="modal" data-target="#PeriodeModal"><i class="fa fa-plus-circle"></i> Tambah Data BPNT</a>';
						}
						?>
						<button class="btn btn-box-tool btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Daftar Periode <span class="caret"></span></button>
						<ul class="dropdown-menu" role="menu">
							<?php 
							if($bpnt_periodes){
								foreach($bpnt_periodes as $key=>$rs){
									echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"".site_url('bpnt/?periode='.$rs['nama'])."\">".$rs['label']."</a></li>";
								}
							}
							?>
						</ul>
					</div>
				</div>
				<div class="box-body">
				<?php 
				// $sub_wilayah = false;
				if($data){
					echo "<table class='table table-responsive datatables table-bordered'>
					<thead><tr><th>#</th>
						<th>ID_Pengurus</th>
						<th>NAMA / NIK</th>
						<th>ALAMAT</th>
						<th>BANK</th>
						<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>";
					$nomer=1;
					foreach ($data as $key => $rs) {
						$kode_kec = substr($rs['kode_desa'],0,7);
						# code...
						echo "<tr><td class='angka'>".$nomer."</td>
							<td>".$rs['ID_Pengurus']."</td>
							<td><a href=\"".site_url('backend/pbdt/art_detail/'.$rs['NoArtPBDTKemsos'])."\">".$rs['Nama_Pengurus']."</a>
								<br />NIK: ".$rs['NIK_Pengurus']."
								<br /><a href=\"".site_url('backend/pbdt/rts_detail/'.$rs['NoPBDTKemsos'])."\"><i class=\"fa fa-home\"></i>".$rs['NoPBDTKemsos']."</a></td>
							<td>".$rs['Alamat_Pengurus']."
								<br /><a href=\"".site_url('bpnt/pengurus/?kode='.$rs['kode_desa'])."\">Desa/Kel. ".$rs['NMDESA']."</a>
								<br /><a href=\"".site_url('bpnt/pengurus/?kode='.$kode_kec)."\">Kec. ".$rs['NMKEC']."</a>
							</td>
							<td>".$rs['BANK']."
								<br />".$rs['NOKARTU']."
								<br />".$rs['NOREKENING']."
							</td>
							<td>".$rs['keterangan']."</td>
						</tr>";
						$nomer++;
	
					}
					echo "</tbody>
					</table>";
				}else{
					echo "<div class='alert alert-warning'>
						<h4>Data Sub Wilayah tidak ditemukan</h4>
						<p>Tidak ada data sub wilayah</p>
					</div>";
				}
				?>
				</div>
			</div>

		</section>
		</div>
<!-- footer section -->
<!-- Modal -->
<div class="modal fade" id="PeriodeModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="memberModalLabel">Form Tambah Data<strong>Periode Bantuan Pangan Non Tunai</strong></h4>
			</div>
			<div class="modal-body">
			<!-- Content goes in here -->
				<form action="" role="form" enctype="multipart/form-data" method="POST" class="formular">
					<div class="form-group">
						<label class="control-label">Tahun </label>
						<select class="form-control validate[required]" name="tahun" id="tahun">
							<?php 
							for($i=2019;$i<=date("Y")+1;$i++){
								echo "<option value=\"".$i."\">".$i."</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Bulan </label>
						<select class="form-control validate[required]" name="tahun" id="tahun">
							<?php 
							for($i=1;$i<=12;$i++){
								$strM = "2019-".str_pad($i,2,'0',STR_PAD_LEFT)."-01";
								echo "<option value=\"".str_pad($i,2,'0',STR_PAD_LEFT)."\">".str_pad($i,2,'0',STR_PAD_LEFT)." - ".date("F",strtotime($strM))."</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Berkas File .xls </label>
						<input type="file" name="file_xls" class="form-control" />
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary"><i class="fa fa-sent"></i> Unggha Berkas</button>
						<button type="button" class="btn btn-danger close " data-dismiss="modal"><i class="fa fa-close"></i>Batal</button>
					</div>

				</form>
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
			<div class="dash">
			<!-- Content goes in here -->
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
