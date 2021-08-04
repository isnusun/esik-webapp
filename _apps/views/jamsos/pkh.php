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
				</div>
				<div class="box-body">
				<?php 
				$sub_wilayah = false;
				// echo var_dump($sub_wilayah);
				if($sub_wilayah){
					echo "<table class='table table-responsive datatables table-bordered'>
					<thead><tr><th>#</th>
						<th>Nama Wilayah</th>
						<th>&Sigma; Rumah Tangga</th>
						<th>&Sigma; Kartu Keluarga</th>
						<th>&Sigma; Penduduk/Anggota Rumah Tangga</th>
						<th>Tindakan</th>
					</tr></thead>
					<tbody>";
					$nomer=1;
					$sum_rts = 0;
					$sum_kk  = 0;
					$sum_art = 0;
					foreach ($sub_wilayah as $key => $rs) {
						# code...
						echo "<tr><td class='angka'>".$nomer."</td>
							<td><a href='".site_url('backend/administrasi/?kode='.$key)."'>".$rs['nama']."</a></td>
							<td class='angka'><a href='".site_url('backend/administrasi/data_rts/'.$key)."'>".number_format($rs['sum_rts'],0)."</a></td>
							<td class='angka'><a href='".site_url('backend/administrasi/data_kk/'.$key)."'>".number_format($rs['sum_kk'],0)."</a></td>
							<td class='angka'><a href='".site_url('backend/administrasi/data_art/'.$key)."'>".number_format($rs['sum_art'],0)."</a></td>
							<td><div class='btn-group'>
								<a href='' class='btn btn-default btn-sm' 
								title='Edit Data Wilayah'
								data-toggle='modal'
								data-target='#EditWilayahModal'
								data-whatever='wilayah_".$rs['id']."'><i class='fa fa-pencil'></i></a>
								
								<a href='' class='btn btn-primary btn-sm' 
								title='Pindahkan Data RTS'
								data-toggle='modal'
								data-target='#MoveRTSModal'
								data-whatever='wilayah_".$rs['id']."'
								><i class='fa fa-exchange'></i></a>
								";
								if($rs['sum_rts'] == 0){
									echo "<a href='' class='btn btn-danger btn-sm' 
									title='Hapus data Wilayah'
									data-toggle='modal'
									data-target='#HapusWilayahModal'
									data-whatever='wilayah_".$rs['id']."'
										><i class='fa fa-trash'></i></a>";
								}
								echo "
							</div></td>
						</tr>";
						$nomer++;
						$sum_rts += $rs['sum_rts'];
						$sum_kk  += $rs['sum_kk'];
						$sum_art += $rs['sum_art'];
	
					}
					echo "</tbody>
					<tfoot>
						<tr><th></th><th class='angka'>JUMLAH</th>
						<th class='angka'><a href='".site_url('backend/administrasi/data_rts/'.$varKode)."'>".number_format($sum_rts,0)."</a></th>
						<th class='angka'><a href='".site_url('backend/administrasi/data_kk/'.$varKode)."'>".number_format($sum_kk,0)."</a></th>
						<th class='angka'><a href='".site_url('backend/administrasi/data_art/'.$varKode)."'>".number_format($sum_art,0)."</a></th>
						<th></th>
						</tr>
					</tfoot>
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
