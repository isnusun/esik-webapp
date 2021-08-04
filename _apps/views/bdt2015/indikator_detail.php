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
			<small>Data Berbasis Indikator <?php echo $user['wilayah_nama']; ?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Penyajian Data Indikator</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			$this->load->view('bdt2015/_header');
			?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('bdt2015');?>">Distribusi Data Berbasis Indikator: <?php echo $indikator['nama']; ?></a></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<table class="table table-bordered table-responsives datatables">
								<thead><tr>
									<th colspan="2">Indikator / Opsi</th>
									<th>Data</th>
								</tr></thead>
								<tbody>
								<?php
									echo "</tr>";
									if($indikator['opsi']){
										if(is_array($indikator['opsi'])){
											$pie_series =array();
											$col_series = array();
											$hc_col_name = array();
											$hc_col_value = array();
											$nomer = 1;
											foreach($indikator['opsi'] as $o=>$op){
												if($nomer==1){
													$pie_series[] = array(
														'name'=>$op,
														'y'=>$num_responden[$o],
														'sliced'=>'true',
														'selected'=>'true'
													);

												}else{
													$pie_series[] = array(
														'name'=>$op,
														'y'=>$num_responden[$o],
													);
												}
												$hc_col_name[] = $op;
												$hc_col_value[] = $num_responden[$o];
												$col_series[] = array(
													'name'=>$op,
													'y'=>$num_responden[$o],
												);
												echo "<tr>
													<td style='width:20px;'>".$nomer."</td>
													<td style='padding-left:30px;'>".$op."</td>
													<td class=\"angka\"><a href=\"".site_url('backend/bdt2015/rts_mana/?indikator='.$indikator['id'].'&opsi='.$o.'&kode='.$varKode)."\">".number_format($num_responden[$o])."</a></td>
												</tr>";
												$nomer++;
											}

										}
									}

								?>
								</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<div class="google-maps">
								<div id="highchart_indikator_opsi"></div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="google-maps">
								<div id="highchart_indikator_opsi_column"></div>
							</div>
						</div>
					</div>
				</div>
			</div>


		</section>
	</div>
<!-- footer section -->
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>highcharts.js"></script>
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>modules/exporting.js"></script>
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>modules/offline-exporting.js"></script>
<script>
	Highcharts.chart('highchart_indikator_opsi', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: 'Distribusi Data Responden atas Indikator <?php echo $indikator['nama'];?>'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: false
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Responden:',
			colorByPoint: true,
			data: <?php echo json_encode($pie_series,JSON_NUMERIC_CHECK);?>
		}]
	});
	
	Highcharts.chart('highchart_indikator_opsi_column', {
		chart: {
        	type: 'column'
		},
		title: {
			text: 'Grafik Perbandingan Responden <?php echo $indikator['nama'];?>'
		},
		subtitle: {
			text: 'PBDT 2015'
		},
		xAxis: {
			categories: <?php echo json_encode($hc_col_name);?>,
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Jumlah Responden'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				'<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		series:[{
			'name':'PBDT 2015',
			'data':<?php echo json_encode($hc_col_value,JSON_NUMERIC_CHECK); ?>
		}]
		
	});	
</script>

<?php


$this->load->view('siteman/siteman_footer');
