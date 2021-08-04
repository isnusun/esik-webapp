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
			Program Bantuan Sosial
			<small><?php echo APP_TITLE;?></small>
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
							<h3 class="box-title"><a href="<?php echo site_url('program_bansos');?>"><?php echo $program['nama'];?></a></h3>
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
							$tanggal = ($program_id > 0) ? $program['sdate']." - ".$program['edate']:date('Y-m-d')." - ".date('Y-m-d');
							?>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Sasaran Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<input type="text" class="form-control" value="<?php echo $program_sasaran[$program['sasaran']]; ?>" disabled="disabled" />
									</div>
								</div>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Nama Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
									<input type="text" class="form-control" name="nama" id="nama" placeholder="Tuliskan nama program" value="<?php echo $program['nama']; ?>" disabled="disabled"/>
									</div>
								</div>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Pemilik/Pelaksana Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<input type="text" class="form-control" value="<?php echo $program['lembaga_nama']; ?>" disabled="disabled" />
									</div>
								</div>
								
								<div class="form-group">
									<label class="label-control col-md-12 col-sm-12 col-xs-12">Keterangan</label>
									<div class="col-md-12">
										<?php echo $program['ndesc']; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Rentang Waktu Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="" value="<?php echo $tanggal; ?>" disabled="disabled"/>
									</div>
								</div>
							<form action="<?php echo $form_action; ?>" method="POST" class="formular form-horizontal" role="form">
								
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-info fa-fw"></i> Panduan Penggunaan Modul</h3>
						</div>
						<div class="box-body">
							Cukup Jelas / menyusul
						</div>
					</div>
				</div>
			</div>
			<!--Daftar Peserta-->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('program_bansos/view/'.$program_id.'/'.$varKode);?>">Daftar Penerima Manfaat: <?php echo $program['nama'];?></a></h3>
				</div>
				<div class="box-body">
					<?php 
					if($program_peserta){
						if($program_peserta['ndata'] > 0){
							echo "";
							?>
							<table class="table table-responsive datatables"><thead>
								<tr><th>#</th><th>NAMA</th><th>NIK</th>
								<th>NO RTM</th><th>JENIS KELAMIN</th><th>TGL LAHIR</th>
								<th>UMUR</th><th>STATUS KAWIN</th><th>ALAMAT</th><th></th>
								</tr>
							</thead><tbody>
								<?php
								$nomer = 1;
								foreach($program_peserta['data'] as $key=>$rs){
									echo "
									<tr><td class=\"angka\">".$nomer."</td>
									<td><a href=\"".site_url('kependudukan/individu/'.$rs['penduduk_id'])."\">".$rs['pnama']."</a></td>
									<td><a href=\"".site_url('kependudukan/individu/'.$rs['penduduk_id'])."\">".$rs['pnik']."</a></td>
									<td><a href=\"".site_url('kependudukan/rtm/'.$rs['rtm_no'])."\">".$rs['rtm_no']."</a></td>
									<td>".$rs['psex']."</td>
									<td>".date("j F Y",strtotime($rs['dtlahir']))."</td>
									<td>".$rs['umur']."</td>
									<td>".$rs['kawin']."</td>
									<td>".$rs['alamat']."</td>
									<td></td>
									</tr>";
									$nomer++;
								}
								
								?>
							</tbody></table>
							<?php
						}else{
							echo "
							<div class=\"alert alert-warning alert-dismissible\">
								<button class=\"close\" type=\"button\" data-dismiss=\"alert\"  aria-hidden=\"true\">&times;</button>
								<h4><i class=\"fa fa-warning fa-fw\"></i> Belum ada data Penerima Manfaat ".$program['nama']."</h4>
							</div>
							";
						}
					}
					?>
				</div>
			</div>
			
			<!--/Daftar Peserta-->
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
