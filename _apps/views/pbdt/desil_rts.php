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
			$this->load->view('pbdt/_header');
			?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('pbdt');?>"><?php echo $pageTitle;?></a></h3>
					<!-- <div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<div class="btn-group">
							<button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-sliders fa-fw"></i>Pilih Periode Pendataan</button>
							<ul class="dropdown-menu" role="menu">
								<?php 
								foreach($periodes['periode'] as $p){
									echo '<li><a href="'.site_url('backend/pbdt/').'?periode='.$p['id'].'&amp;kode='.$varKode.'">'.$p['nama'].'</a></li>';
								}
								?>
								<li class="divider"></li>
								<li><a href="#">Separated link</a></li>
							</ul>
						</div>
						<button class="btn btn-warning"><i class="fa fa-clock-o fa-fw"></i>Periode <?php echo $periodes['periode'][$periode]['nama']; ?></button>
                  	</div> -->

				</div>
				<div class="box-body">
					<ol class="breadcrumb">
						<?php 
						if($alamat_bc){
							foreach($alamat_bc as $key=>$rs){
								if(strlen($user['wilayah']) <= strlen($rs['kode'])){
									echo "<li class='nav-item'><a href='".site_url('backend/pbdt/?kode='.$rs['kode'])."'>".$rs['nama']."</a></li>";
								}else{
									echo "<li class='nav-item'>".$rs['nama']."</li>";
								}
							}
						}
						?>
					</ol>


					<?php
					if($data){
						// echo var_dump($data);
						echo "
						<div class='scroll'>
						<table class='table table-responsives table-bordered datatables'>
						<thead>
							<tr><th>#</th>
								<th>ID BDT</th>
								<th>Nama Kepala RTS</th>
								<th>RT</th>
								<th>RW</th>
								<th>Dusun/Lingkungan</th>
								<th>Desa/Kelurahan</th>
								<th>Kecamatan</th>
								<th>Kabupaten</th>
								<th>Skor</th>
							</tr>
						</thead>
						<tbody>";
						$nomer=1;
						foreach($data as $key=>$rs){
							$rt = substr($rs['kode_wilayah'],-3);
							$rw = substr($rs['kode_wilayah'],-6,3);
							echo "
							<tr><td class='angka'>".$nomer."</td>
								<td><a href='".site_url('backend/pbdt/rts_detail')."/".$key."'>".$key."</a></td>
								<td><a href='".site_url('backend/pbdt/rts_detail')."/".$key."'>".strtoupper(strtolower($rs['nama_krt']))."</a></td>
								<td>".$rt."</td><td>".$rw."</td>
								<td>".strtoupper($rs['dusun'])."</td>
								<td>".$rs['desa']."</td>
								<td>".$rs['kecamatan']."</td>
								<td>".$rs['kabupaten']."</td>
								<td>".$rs['percentile']."</td>

							</tr>";
							$nomer++;
						}
						echo "</tbody>
						</table></div>";
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
