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
			<?php echo $app['app_title'];?>
			<small>Basis Data Terpadu <?php echo $user['wilayah_nama']; ?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Basis Data Terpadu</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			$this->load->view('bdt2015/_header');
			?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('bdt2015');?>"><?php echo $pageTitle;?></a></h3>
					<!-- <div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<div class="btn-group">
							<button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-sliders fa-fw"></i>Pilih Periode Pendataan</button>
							<ul class="dropdown-menu" role="menu">
								<?php 
								foreach($periodes['periode'] as $p){
									echo '<li><a href="'.site_url('backend/bdt2015/').'?periode='.$p['id'].'&amp;kode='.$varKode.'">'.$p['nama'].'</a></li>';
								}
								?>
								<li class="divider"></li>
								<li><a href="#">Separated link</a></li>
							</ul>
						</div>
						<button class="btn btn-warning"><i class="fa fa-clock-o fa-fw"></i>Periode <?php echo $periodes['periode'][$periode]['nama']; ?></button>
                  	</div> -->

				</div>
				<div class="box-body no-padding">
					<?php
					if($data){
						echo "<pre>";
						// echo var_dump($indikator);
						$hub_rts = $indikator['art'][73]['opsi'];
						// echo var_dump($hub_rts);
						echo "</pre>";
						echo "<table class='table table-responsives table-bordered datatables'>
						<thead>
							<tr><th>#</th>
								<th>NURT</th>
								<th>Nama</th>
								<th>NIK</th>
								<th>Jenis Kelamin</th>
								<th>Umur</th>
								<th>Hubungan dlm Rumah Tangga</th>
							</tr>
						</thead>
						<tbody>";
						$nomer=1;
						foreach($data as $key=>$rs){
							$sex = ($rs['Jenis kelamin'] == 1) ? "L":"P";
							$hub_rt = $hub_rts[$rs['Hubungan dengan Kepala Rumah Tangga']];
							echo "
							<tr><td class='angka'>".$nomer."</td>
								<td><a href='".site_url('backend/bdt2015/rts_detail')."/".$rs['Nomor Urut Rumah Tangga']."'>".$rs['Nomor Urut Rumah Tangga']."</a></td>
								<td><a href='".site_url('backend/bdt2015/art_detail')."/".$rs['id']."'>".strtoupper(strtolower($rs['Nama']))."</a></td>
								<td><a href='".site_url('backend/bdt2015/art_detail')."/".$rs['id']."'>".$rs['NIK']."</a></td>
								<td>".$sex."</td>
								<td class=\"angka\">".$rs['Umur saat pendataan']."</td>
								<td>".$hub_rt."</td>
							</tr>";
							$nomer++;
						}
						echo "</tbody>
						</table>";
					}
					?>
				</div>
			</div>

		</section>
	</div>
<!-- footer section -->

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
		responsive: true,
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
