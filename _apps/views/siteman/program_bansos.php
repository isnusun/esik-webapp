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
			<small>Panel Konfigurasi</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('program_bansos')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Data Pengguna</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-8 col-sm-6 col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><a href="<?php echo site_url('program_bansos');?>"><?php echo $boxTitle;?></a></h3>
							<div class="box-tools pull-right">
								<a href="<?php echo site_url('program_bansos/edit/0'); ?>" class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
							</div><!-- /.box-tools -->
							
						</div>
						<div class="box-body">
							<?php 
							if($msg){
								echo "
								<div class=\"alert alert-success alert-dismissable\">
									<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
									<h4><i class=\"fa fa-check\"></i> ".$msg['msg']."</h4>
								</div>";
							}
							if($program_bansos){
								if(count($program_bansos) > 0){
									echo "<table class=\"table table-responsive datatables\">
									<thead><tr><th>#</th><th>Nama Program</th>
									<th>Pelaksana/Pemilik Program</th>
									<th>Periode/Masa Berlaku</th>
									<th>&Sigma; Penerima Manfaat</th>
									<th>Status</th>
									<th></th></tr></thead>
									<tbody>";
									$nomer = 1;
									foreach($program_bansos as $key=>$item){
										echo "
										<tr><td class=\"angka\">".$nomer."</td>
											<td><a href=\"".site_url('program_bansos/view/'.$key.'/'.KODE_BASE.'/'.fixNamaUrl($item['nama']))."\" >".$item['nama']."</a></td>
											<td>".$item['lembaga_nama']."</td>
											<td>".fTampilTgl($item['sdate'],$item['edate'])."</td>
											<td>".number_format($item['npeserta'],0)."</td>
											<td>".$item['status']."</td>
											<td><a class=\"btn btn-xs btn-default\" href=\"".site_url('program_bansos/edit/'.$key)."\"><i class=\"fa fa-edit\"></i></a></td>
										</tr>";
										$nomer++;
									}
									echo "
									</tbody>
									</table>";
								}
							}else{
								echo "
								<div class=\"alert alert-warning\">Belum ada data Program Layanan Sosial. <a href=\"".site_url('program_bansos/edit')."\">Klik disini untuk menuliskan Program Bantuan</a></div>
								";
							}
							?>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-info fa-fw"></i> Panduan Penggunaan Modul</h3>
						</div>
						<div class="box-body">
							<ul>
							</ul>
						</div>
					</div>
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
