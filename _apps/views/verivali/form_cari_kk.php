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
			<div class="row">
				<div class="col-md-6">
					<div class="callout callout-warning">
						<h4>Pencarian Data Kartu Keluarga</h4>
						<form action="<?php echo site_url('verval/cari_kk'); ?>" method="POST" class="formular" role="form">
							<div class="form-group">
								<label class="label-control" for="search">Data yang dicari</label>
								<div class="input-group">
									<input type="text" class="form-control" name="q" id="q" placeholder="masukkan nomor Kartu Keluarga" value="<?php echo @$q;?>"/>
									<span class="input-group-btn">
										<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
									</span>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-6">
					<div class="callout callout-info">
						<h4>Penambahan Rumah Tangga Baru</h4>
						<p>Kita bisa memulai verifikasi dan validasi data dengan mencari dari daftar Rumah Tangga Sasaran berbasis wilayah, atau pun menggunakan formulir pencarian data RTS berbasis nama</p>
						<p>Bila Rumah Tangga Sasaran belum/tidak terdaftar disini, silakan gunakan <a href="<?php echo site_url('verivali/new_rtm/?kode='.$user['wilayah'])?>" class="btn btn-sm btn-warning"><i class="fa fa-plus"></i> Tombol ini untuk menambahkan data Rumah Tangga Sasaran Baru</a></p>
					</div>

				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('verval');?>">Hasil Pencaraian Data RTS di Wilayah Administrasi <?php echo $user['wilayah_nama']?></a></h3>
				</div>
				<div class="box-body">
					<?php 
					if($sasaran)
					{
						if(count($sasaran) > 0){
							echo "
							<table class=\"table table-responsives table-stripped datatables\">
							<thead><tr><th>#</th>
								<th>NO RTM</th>
								<th>NO KK</th>
								<th>&Sigma; ART</th>
								<th>Kepala RT</th>
								<th>Alamat</th></tr></thead>
							<tbody>";
							$nomer=1;
							foreach($sasaran as $rs)
							{
								echo "<tr><td class=\"angka\">".$nomer."</td>
									<td><a href=\"".site_url('verval/form_rts/'.$rs['rtm_no'])."\">".$rs['rtm_no']."</a></td>
									<td><a href=\"".site_url('verval/form_kks/'.$rs['kk_no'])."\">".$rs['kk_no']."</a></td>
									<td class=\"angka\">".$rs['nart']."</td>
									<td>".$rs['pnama']."</td>
									<td>".$rs['kode_wilayah']." </td>
								</tr>";
								$nomer++;
							}
							echo "
							</tbody>
							</table>";
							// echo var_dump($paging);

							// if($paging) {
							// 	echo "<ul class=\"pagination\">";
							// 	if($paging['page_min'] > 1){
							// 		echo "<li><a href=\"".site_url('verval/cari_kk/?q='.$q)."\"><i class=\"fa fa-fast-backward\"></i></a></li>";
							// 	}
								
							// 	$page_min = $paging['page_min'];
							// 	while($page_min < $paging['page_max']){
							// 		$strC = ($page_min == $paging['page']) ? "class=\"active\"":"";
							// 		echo "<li ".$strC."><a href=\"".site_url('verval/cari_kk/').$page_min."/?q=".$q."\">".$page_min."</li>";
							// 		$page_min++;
							// 	}

							// 	if($paging['page_max'] < $paging['page_count']){
							// 		echo "<li><a href=\"".site_url('verval/cari_kk/').$paging['page_count']."/?q=".$q."\"><i class=\"fa fa-fast-forward\"></i></a></li>";
							// 	}

							// 	echo "</ul>";
							// }
						}
					}
					else
					{
						?>
						<div class="callout callout-warning">
							<h4>Hasil Pencarian <strong><?php echo $q; ?></strong>: <strong>KOSONG/ TIDAK DITEMUKAN DATA</strong></h4>
							<p>Silakan gunakan <a href="<?php echo site_url('verval/new_rtm'); ?>" class="btn btn-sm btn-warning"><i class="fa fa-plus"></i> Tombol ini untuk menambahkan data Rumah Tangga Sasaran Baru</a></p>
						</div>
						<?php
					}
					?>
				</div>
			</div>

		</section>
	</div>
<!-- footer section -->

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
