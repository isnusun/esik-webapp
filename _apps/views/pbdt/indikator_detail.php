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
			$this->load->view('pbdt/_header');
			?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('pbdt');?>">Distribusi Data Berbasis Indikator: <?php echo $indikator['label']; ?></a></h3>
				</div>
				<div class="box-body">
				<?php 
				// echo var_dump($num_responden);
				?>
					<table class="table table-bordered table-responsives datatables">
					<thead><tr>
						<th rowspan="2" colspan="2">Indikator / Opsi</th>
						<th colspan="<?php echo count($periodes)?>">Data</th>
					</tr><tr>
					<?php 
					foreach($periodes['periode'] as $periode){
						echo "<th>".$periode['nama']."</th>";
					}
					// echo var_dump($bdt_indikator);
					?>
					</tr></thead>
					<tbody>
					<?php

					// foreach($bdt_indikator as $key=>$rs){
					$rs = $bdt_indikator[$indikator['nama']];
						echo "<tr>";
						if($rs['opsi']){
							echo "<td colspan='2'><a href='".site_url('backend/pbdt/indikator_detail/'.$responden.'/'.$rs['nama'])."/?kode=".$varKode."'>".$rs['label']."</a></td>";
						}else{
							echo "<td colspan='2'>".$rs['label']."</td>";
						}
						foreach($periodes['periode'] as $periode){
							echo "<td></td>";
						}
						echo "</tr>";
						if($rs['opsi']){
							if(is_array($rs['opsi'])){
								$seri = array();
								foreach($rs['opsi'] as $o=>$op){
									echo "<tr>
										<td style='width:20px;'>".$op['nama']."</td>
										<td style='padding-left:30px;'>".$op['label']."</td>";
										$n=0;
										foreach($periodes['periode'] as $periode){
											$selected = ($n==0)? "true":"false";
											$nilai = 0;
											if(array_key_exists($periode['id'],$num_responden)){
												if(is_array($num_responden[$periode['id']][$rs['nama']])){
													if(array_key_exists($op['nama'],$num_responden[$periode['id']][$rs['nama']])){
														$nilai = $num_responden[$periode['id']][$rs['nama']][$op['nama']];
													}
												}
											}
											if($n==0){
												$seri[$periode['id']][] = array(
													'name'=>$op['label'],
													'y'=>$nilai,
													'sliced'=>$selected,
													'selected'=>$selected,
												);
											}else{
												$seri[$periode['id']][] = array(
													'name'=>$op['label'],
													'y'=>$nilai);
											}
											$n++;
											// $nilai = ($num_responden[$periode['id'][$rs['nama']]])? $num_responden[$periode['id'][$rs['nama']]][$op['nama']]: 0;
											echo "<td class='angka'><a href='".site_url("backend/pbdt/".$responden."_mana/?periode_id=".$periode['id']."&amp;indikator=".$indikator['nama']."&amp;opsi=".$o."&amp;kode=".$varKode)."'>".number_format($nilai,0)."</a></td>";

										}
										echo"
									</tr>";
								}
							}
						}
					// }
					?>
					</tbody>
					</table>
					<div class="row">
						<?php 
						foreach($periodes['periode'] as $periode){
							echo '
							<div class="col-md-4">
								<div class="google-maps">
									<div id="highchart_indikator_'.$periode['id'].'"></div>
								</div>
							</div>';
						}
						?>
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
	<?php 
	foreach($periodes['periode'] as $periode){
	?>
	Highcharts.chart('<?php echo 'highchart_indikator_'.$periode['id']; ?>', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: 'Data Responden atas Indikator <?php echo $indikator['label'] ." Periode ".$periode['nama'];?>'
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
			name: 'Opsi Jawaban',
			colorByPoint: true,
			data: <?php echo json_encode($seri[$periode['id']],JSON_NUMERIC_CHECK);?>
		}]
	});

	<?php 
	}
	?>
</script>

<?php


$this->load->view('siteman/siteman_footer');
