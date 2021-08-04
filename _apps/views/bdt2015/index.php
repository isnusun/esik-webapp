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
					<h3 class="box-title"><a href="<?php echo site_url('backend/bdt2015');?>">Telusur Data PBDT berbasis Wilayah Administrasi <?php echo $user['wilayah_nama']?></a></h3>
					<div class="box-tools pull-right">
						<button class="btn btn-warning"><i class="fa fa-clock-o fa-fw"></i>PBDT 2015</button>
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<ol class="breadcrumb">
						<?php 
						foreach($alamat_bc as $key=>$rs){
							if(strlen($user['wilayah']) <= strlen($rs['kode'])){
								echo "<li class='nav-item'><a href='".site_url('backend/bdt2015/?kode='.$rs['kode'])."'>".$rs['nama']."</a></li>";
							}else{
								echo "<li class='nav-item'>".$rs['nama']."</li>";
							}
						}
						?>
					</ol>

					<?php
					if($sub_wilayah){
						$colspan = (count($desil) * 2) + 2;
						echo "
						<div class='scroll'>
						<table class='table table-responsives table-bordered datatables'>
						<thead>
							<tr><th rowspan='3'>#</th><th rowspan='3'>Wilayah</th>
								<th colspan='".$colspan."'>PBDT 2015</th>
							</tr>
							<tr>";
							foreach($desil as $d){
								echo "<th colspan='2'>".$d."</th>";
							}
							echo "
								<th colspan='2' class=' total'>TOTAL</th>
							</tr>
							<tr>";
							$total['rts'] = 0;
							$total['art'] = 0;
							$sum = array();
							foreach($desil as $k=>$d){
								$sum['rts'][$k] = 0;
								$sum['art'][$k] = 0;
								echo "
								<th><i class='fa fa-home'></i></th>
								<th><i class='fa fa-user'></i></th>";
							}
							echo "<th class=' total'><i class='fa fa-home'></i></th>
							<th class=' total'><i class='fa fa-user'></i></th></tr>
						</thead>
						<tbody>";
						$nomer=1;
						$x_axis = array();
						$seri = array();
						$data_seri =array();
						foreach($sub_wilayah as $w){
							$x_axis[] = $tingkat_wilayah[$w['tingkat']]." ".strtoupper(strtolower($w['nama']));
							echo "<tr><td class='angka'>".$nomer."</td>";
							if($w['tingkat'] < 7){
								echo "<td><a href='".site_url('backend/bdt2015/')."?kode=".$w['kode']."'>".$tingkat_wilayah[$w['tingkat']]." ".strtoupper(strtolower($w['nama']))."</a></td>";
							}else{
								echo "<td>".$tingkat_wilayah[$w['tingkat']]." ".strtoupper(strtolower($w['nama']))."</td>";
							}
							$subtotal['rts'] = 0;
							$subtotal['art'] = 0;
							foreach($desil as $k=>$d){
								$nilai_rts = $data_rts[$w['kode']][$d];
								$nilai_art = $data_art[$w['kode']][$d];
								$sum['rts'][$k] += $nilai_rts;
								$sum['art'][$k] += $nilai_art;
								$subtotal['rts'] += $nilai_rts;
								$subtotal['art'] += $nilai_art;
								
								$data_seri[$k]['rts'][] = $nilai_rts;
								$data_seri[$k]['art'][] = $nilai_art;
		
								echo "<td class='angka'><a href='".site_url('backend/bdt2015/rts_desil/'.$k.'/')."?kode=".$w['kode']."'>".number_format($nilai_rts,0)."</a></td>
								<td class='angka'><a href='".site_url('backend/bdt2015/art_desil/'.$k.'/')."?kode=".$w['kode']."'>".number_format($nilai_art,0)."</a></td>";
							}
							echo "
							<td class='angka total'><a href='".site_url('backend/bdt2015/rts_desil/0/')."?kode=".$w['kode']."'>".number_format($subtotal['rts'],0)."</a></td>
							<td class='angka total'><a href='".site_url('backend/bdt2015/art_desil/0/')."?kode=".$w['kode']."'>".number_format($subtotal['art'],0)."</a></td>
							</tr>";
							$total['rts'] += $subtotal['rts'];
							$total['art'] += $subtotal['art'];

							$nomer++;
						}
						echo "</tbody>
						<tfoot><tr><th>&nbsp;</th><th class='angka'>&Sigma; JUMLAH</th>";
						foreach($desil as $k=>$d){
							echo "
							<th class='angka'>".number_format($sum['rts'][$k],0)."</th>
							<th class='angka'>".number_format($sum['art'][$k],0)."</th>";
						}

						echo "
						<th class='angka total'>".number_format($total['rts'],0)."</th>
						<th class='angka total'>".number_format($total['art'],0)."</th>";

						echo "</tr></tfoot>
						</table>
						</div>";
						foreach($desil as $k=>$d){
							$seri['rts'][] = array(
								'name'=>"RTS ".$d,
								'data'=>$data_seri[$k]['rts'],
								'stack'=>'rts'
							);
							$seri['art'][] = array(
								'name'=>'ART '.$d,
								'data'=>$data_seri[$k]['art'],
								'stack'=>'art'
							);
						}

					}
					?>
					<div class="google-maps">
						<div id="highchart_graph_rts"></div>
					</div>
					<div class="google-maps">
						<div id="highchart_graph_art"></div>
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
		"responsive":true,
		"paging": false,
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
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>highcharts.js"></script>
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>modules/exporting.js"></script>
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>modules/offline-exporting.js"></script>
<!-- <script src="<?php echo base_url("assets/plugins/highcharts/"); ?>modules/export-data.js"></script> -->
<!-- <script src="https://code.highcharts.com/modules/export-data.js"> -->
<script>
Highcharts.chart('highchart_graph_rts', {

chart: {
		type: 'column'
	},
	title: {
		text: 'Jumlah Rumah Tangga Sasaran, Dikelompokkan Berbasis Wilayah dan Desil'
	},
	xAxis: {
		categories: <?php echo json_encode($x_axis);?>
	},
	yAxis: {
		allowDecimals: false,
		min: 0,
		title: {
			text: 'Jumlah Rumah Tangga'
		}
	},
	tooltip: {
		formatter: function () {
			return '<b>' + this.x + '</b><br/>' +
				this.series.name + ': ' + this.y + '<br/>' +
				'Total: ' + this.point.stackTotal;
		}
	},

	plotOptions: {
		column: {
			stacking: 'normal'
		}
	},
	series: <?php echo json_encode($seri['rts'],JSON_NUMERIC_CHECK);?>
});
Highcharts.chart('highchart_graph_art', {

chart: {
		type: 'column'
	},
	title: {
		text: 'Jumlah Penduduk Dikelompokkan Berbasis Wilayah dan Desil'
	},
	xAxis: {
		categories: <?php echo json_encode($x_axis);?>
	},
	yAxis: {
		allowDecimals: false,
		min: 0,
		title: {
			text: 'Jumlah Penduduk'
		}
	},
	tooltip: {
		formatter: function () {
			return '<b>' + this.x + '</b><br/>' +
				this.series.name + ': ' + this.y + '<br/>' +
				'Total: ' + this.point.stackTotal;
		}
	},

	plotOptions: {
		column: {
			stacking: 'normal'
		}
	},
	series: <?php echo json_encode($seri['art'],JSON_NUMERIC_CHECK);?>
});
</script>
<?php


$this->load->view('siteman/siteman_footer');
