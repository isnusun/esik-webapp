<?php
/*
 * pbdt.php
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
			<small><?php echo APP_TITLE;?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">PBDT</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			$this->load->view('pbdt/pbdt_head');
			
			?>
			<!--box data indikator-->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i><?php echo $boxTitle;?></h3>
					<div class="box-tools pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							Pilih Sub Wilayah<i class="fa fa-chevron-down fa-fw"></i>
						</a>
						<ul class="dropdown-menu">
							<?php 
							foreach($subwilayah as $key=>$rs){
								echo "<li><a href=\"".site_url('kependudukan/data_rts/').$key."\">".$rs['nama']."</a></li>";
							}
							?>
						</ul>
						
					</div>
				</div>
				<div class="box-body">
					<!--tabular-->
					<ol class="breadcrumb">
					<?php
					if(is_array($alamat)){
						foreach($alamat as $key=>$item){
							if(strlen($item['kode']) > 2){
								if($user['tingkat'] >=3){
									if(strlen($item['kode']) >= 10){
										echo "<li><a href=\"".site_url('kependudukan/data_rts/'.$item['kode'])."\">".$item['nama']."</a></li>";
									}else{
										echo "<li>".$item['nama']."</li>";
									}
								}else{
									echo "<li><a href=\"".site_url('kependudukan/data_rts/'.$item['kode'])."\">".$item['nama']."</a></li>";
								}
							}
						}
					}
					

					?>
					</ol>
					<?php
					// echo var_dump($dataset);	
					echo "
						<table class=\"table table-responsive table-bordered datatables\">
							<thead><tr><th>#</th>
								<th>RID Rumah Tangga</th>
								<th>Nama Kepala Rumah Tangga</th>
								<th>&Sigma;KK</th>
								<th>Alamat</th>
								<th>Dusun</th>
								<th>RW</th>
								<th>RT</th>
								<th style=\"min-width:70px;\"></th>
							</tr></thead>
							<tbody>";
							$nomer=0;
							if($dataset){
								foreach($dataset as $key=>$rs){
									$nomer++;
									echo "
									<tr>
									<td>".$nomer."</td>
									<td><a href=\"".site_url('kependudukan/detail_rts/').$rs['rtm_no']."\">".$rs['rtm_no']."</a></td>
									<td>".$rs['kepala_rumahtangga']."</td>
									<td>".$rs['sumKK']."</td>
									<td>".$rs['alamat']."</td>
									<td>".$rs['dusun']."</td>
									<td>RW ".$rs['rw']."</td>
									<td>RT ".$rs['rt']."</td>
									<td><div class=\"btn-group\">
									<a class=\"btn btn-xs btn-default\" title=\"Edit Data\" href=\"".site_url('kependudukan/detail_rts/').$rs['rtm_no']."/edit\"><i class=\"fa fa-pencil fa-fw\"></i></a>
									<a data-toggle=\"modal\" data-target=\"#mdl_rts\" class=\"btn btn-xs btn-default mdl_RtsPindah\" title=\"Pindah alamat\" href=\"".site_url('kependudukan/rts_pindah/').$rs['rtm_no']."/?ref=".$varKode."\"><i class=\"fa fa-exchange fa-fw\"></i></a>
									<a class=\"btn btn-xs btn-danger\" title=\"Hapus Data\" href=\"".site_url('kependudukan/detail_rts/').$rs['rtm_no']."/hapus\"><i class=\"fa fa-trash fa-fw\"></i></a>
								</div></td>
									</tr>";
								}
							}
							echo "
							</tbody>
						</table>
						";
					?>

				</div>
			</div>
			<!--/box data-->
			<div class="modal-container"></div>

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
		$('a.mdl_RtsPindah').click(function(e) {
			var $url = $(this).attr('href');
			e.preventDefault();
			$('.modal-container').load($url,function(result){
			$('#mdl_rts').modal({show:true});
			});
		});
	
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
