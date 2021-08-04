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
			// $this->load->view('kependudukan/core_head');
			?>
			
			<!--box data-->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i> <?php echo $boxTitle;?> di <strong><?php echo $user['wilayah_nama']; ?></strong></h3>
					<div class="box-tools pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-cogs fa-fw"></i>Atur Wilayah Administrasi
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo site_url('kependudukan/impor_capil/');?>">Impor Data CAPIL</a></li>
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
										echo "<li><a href=\"".site_url('kependudukan/index/'.$item['kode'])."\">".$item['nama']."</a></li>";
									}else{
										echo "<li>".$item['nama']."</li>";
									}
								}else{
									echo "<li><a href=\"".site_url('kependudukan/index/'.$item['kode'])."\">".$item['nama']."</a></li>";
								}
							}
						}
					}	
					
					// echo var_dump($demografi);	

					?>
					</ol>
					<table class="table table-responsive table-bordered datatables">
						<thead><tr>
							<th rowspan="2">No</th>
							<th rowspan="2">WILAYAH</th>
							<th rowspan="2">&Sigma; Rumah Tangga</th>
							<th rowspan="2">&Sigma; Kartu Keluarga</th>
							<th colspan="3">JUMLAH PENDUDUK</th>
							<th rowspan="2"></th>
						</tr>
						<tr>
							<th>LAKI-LAKI</th>
							<th>PEREMPUAN</th>
							<th>SUB TOTAL</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$desil = array(
							'rts'=>'Rumah Tangga',
							'kk'=>'Kartu Keluarga',
							'pendudukLk'=>'Penduduk Laki-laki',
							'pendudukPr'=>'Penduduk Perempuan',
						);
						if(is_array($subwilayah)){
							$toGraph["title"] = "Grafik Statistik Kependudukan";
							$XAxis= array();
							$toGraph["seri"] = array();
							$nomer=1;
							$sumRTS = 0;
							$sumKK = 0;
							$sumLaki = 0;
							$sumPr = 0;
							$sumPddk = 0;
							foreach ($subwilayah as $key => $rs) {
								# code...
								$XAxis[] = $rs['nama'];
								$data_baris = 0;
								$sumRTS += $demografi[$key]['rts'];
								$sumKK += $demografi[$key]['kk'];
								$sumLaki += $demografi[$key]['pendudukLk'];
								$sumPr += $demografi[$key]['pendudukPr'];
								$sumPddk += $demografi[$key]['penduduk'];
								$data_baris = $demografi[$key]['rts'] + $demografi[$key]['kk'] + $demografi[$key]['penduduk'];
								$toGraph["seri"]['rts'][] = $demografi[$key]['rts'];
								$toGraph["seri"]['kk'][] = $demografi[$key]['kk'];
								$toGraph["seri"]['pendudukLk'][] = $demografi[$key]['pendudukLk'];
								$toGraph["seri"]['pendudukPr'][] = $demografi[$key]['pendudukPr'];
								echo "<tr>
								<td class=\"angka\">".$nomer."</td>";
								if(strlen($key) < 16){
									echo "<td><a href=\"".site_url('kependudukan/index/').$key."\">".$rs['nama']."</a></td>";
								}else{
									echo "<td>".$rs['nama']."</td>";
								}
								
								echo "
								<td class=\"angka\"><a href=\"".site_url('kependudukan/data_rts/').$key."\">".number_format($demografi[$key]['rts'],0)."</a></td>
								<td class=\"angka\"><a href=\"".site_url('kependudukan/data_kk/').$key."\">".number_format($demografi[$key]['kk'],0)."</a></td>
								<td class=\"angka\">".number_format($demografi[$key]['pendudukLk'],0)."</td>
								<td class=\"angka\">".number_format($demografi[$key]['pendudukPr'],0)."</td>
								<td class=\"angka\"><a href=\"".site_url('kependudukan/data_penduduk/').$key."\">".number_format($demografi[$key]['penduduk'],0)."</a></td>
								<td><div class=\"btn-group\">
									<a class=\"btn btn-xs btn-default\" title=\"Edit Data\" href=\"".site_url('kependudukan/wilayah/edit/').$key."\"><i class=\"fa fa-pencil\"></i></a>";
									if($data_baris > 0){
										echo "<a class=\"btn btn-xs btn-default\" title=\"Hapus Data\" href=\"#\"><i class=\"fa fa-trash\"></i></a>";
									}else{
										echo "<a class=\"btn btn-xs btn-danger\" title=\"Hapus Data\" href=\"".site_url('kependudukan/wilayah/hapus/').$key."\"><i class=\"fa fa-trash\"></i></a>";
									}
									echo "
								</div></td>
								</tr>";
								$nomer++;
							}
						}
						?>

						</tbody>
						<tfoot>
							<tr>
								<th colspan="2" class="angka">&Sigma; JUMLAH</th>
								<th class="angka"><?php echo number_format($sumRTS,0);?></th>
								<th class="angka"><?php echo number_format($sumKK,0);?></th>
								<th class="angka"><?php echo number_format($sumLaki,0);?></th>
								<th class="angka"><?php echo number_format($sumPr,0);?></th>
								<th class="angka"><?php echo number_format($sumPddk,0);?></th>
							</tr>
						</tfoot>
					</table>
					<!--tabular-->
					
					<div class="">
						<fieldset class="kotak">
							<legend><strong>Grafik Demografi Rumah Tangga </strong></legend>
							<div>
								<div id="bar_chart_container"></div>
							</div>
						</fieldset>`

					</div>
					<!--grafis-->


				</div>
			</div>
			<!--box filter-->
		</section>
	</div>
<!-- footer section -->


<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/highcharts.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/highcharts-more.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/modules/exporting.js"></script>
<script>
	$(document).ready(function(){
		var bc_container = $('#bar_chart_container');
		if(bc_container){
			$('#bar_chart_container').highcharts({
				chart: {type: 'column'},
				title: {text: '<?php echo $toGraph["title"];?>'},
				subtitle: {text: '<?php echo $pageTitle ." ". $wilayah['nama'];?>'},
				xAxis: {
					categories: <?php echo json_encode($XAxis);?>,
					crosshair: true			
				},        
				yAxis: {
					min: 0,
					title: {
						text: 'Statistik Kependudukan'
					}
				},
				tooltip: {
					headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
					pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
							'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
					footerFormat: '</table>',
					shared: true,
					useHTML: true
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},	
				series: [
					<?php 
					$i=1;
					$n = count($desil);
					foreach($desil as $d=>$ds){
						$strKoma = ($i < $n) ? ",":"";
						echo "\n{
						name : '".$ds."',
						data : "; 
						echo json_encode($toGraph['seri'][$d],JSON_NUMERIC_CHECK);
						echo "}".$strKoma;
					}

					?>				
				]
			});
		}
	})

</script>



<?php

$this->load->view('siteman/siteman_footer');
