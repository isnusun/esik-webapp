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
			<small>PBDT</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">PBDT 2015</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<!--box filter-->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-newspaper-o fa-fw"></i> Daftar Rumah Tangga di <?php echo $wilayah ." dlm ".$desil[$desil_aktif][2]; ?></h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->

				</div>
				<div class="box-body" >
					<!-- daftar RTM-->
					<ol class="breadcrumb">
					<?php
					if(is_array($alamat)){
						foreach($alamat as $key=>$item){
							echo "<li><a href=\"".site_url('backend/pbdt/rtm/'.$item['kode'].'/'.$desil_aktif)."\">".$item['nama']."</a></li>";
						}
					}
					?>
					</ol>
					
					<?php
					if($rtm){
						if(count($rtm) > 0){

							echo "
							<table class=\"table table-responsive datatables\">
							<thead><tr><th>#</th><th>No RTM</th><th>NIK</th><th>Kepala RTM</th>
							<th>TGL LAHIR</th>
							<th>UMUR</th>
							<th>JENIS KELAMIN</th>
							<th>STATUS KAWIN</th>
							<th>Alamat</th><th></th></tr></thead>
							<tbody>";
							$i=1;
							foreach($rtm as $key=>$item){
								$krt[] = $item["nikkepala"];
								$sex = ($item["sex"] == 1)? "L":"P";
								echo "<tr><td class=\"angka\">".$i."</td>
								<td><a href=\"".site_url('backend/pbdt/rtm_view/'.$item['rtm_no'])."\">".$item['rtm_no']."</a></td>
								<td><a href=\"".site_url('backend/pbdt/rtm_view/'.$item['rtm_no'])."\">".$item["nikkepala"]."</a></td>
								<td><a href=\"".site_url('backend/pbdt/rtm_view/'.$item['rtm_no'])."\">".$item["kepala"]."</a></td>
								<td>".date("j M Y",strtotime($item["dtlahir"]))."</td>
								<td>".$item["umur"]."</td>
								<td>".$sex."</td>
								<td>".$item["kawin"]."</td>
								<td>".$item["alamat"]."</td>
								<td><a href=\"".site_url("verivali/form/".$item["rtm_no"])."\"><i class=\"fa fa-newspaper-o\"></i></a></td>
								</tr>";
								$i++;
							}
							echo "</tbody>
							</table>
							";
							
						}
					}

					?>
					<!-- /daftar RTM-->
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
    } );
} );
</script>

<?php

$this->load->view('siteman/siteman_footer');
