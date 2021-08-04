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
			<small>TKPKD</small>
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
			
			<!--box data-->
			<div class="box box-primary box-solid">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i> <?php echo $boxTitle;?></strong></h3>
				</div>
				
				<!-- content index
				isi berupa rangkuman data
				-->
				<div class="box-body">
					<?php
					$colom_to_show= array('RID_RumahTangga','Nama_KRT','Alamat','RT','RW','Desa','Kecamatan','status_kesejahteraan');
					if($items){
						echo "<table class=\"table table-striped table-bordered table-responsive datatables\">
						<thead><tr>
							<th>NO</th>
							<th>RID RumahTangga</th>
							<th>Nama Kepala</th>
							<th>Alamat</th>
							<th>RT</th>
							<th>RW</th>
							<th>KELURAHAN</th>
							<th>KECAMATAN</th>
							<th>DESIL </th>
						</tr>
						</thead>
						<tbody>";
						$nomer = 1;
						foreach($items as $item){
							echo "<tr><td class=\"angka\">".$nomer."</td>";
								foreach($colom_to_show as $s){
									if($s=='RID_RumahTangga'){
										echo "<td class=\"angka\"><a href=\"".site_url('pbdt/show_rts_nilai/'.$item['RID_RumahTangga'])."/".$periode."\" target=\"_blank\">".$item[$s]."</a></td>";
									}else{
										echo "<td>".$item[$s]."</td>";
									}
								}
							echo "</tr>";
							$nomer++;
						}
						echo "
						</tbody>
						</table>";
					}
					?>
				</div>
			</div>
			<!--box filter-->
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
	});
});
</script>
<?php

$this->load->view('siteman/siteman_footer');
