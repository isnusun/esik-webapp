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
			<?php echo $pageTitle;?>
			<small>SK Gakin</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">SK GAKIN</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-8 col-sm-6 col-xs-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">SK GAKIN Berdasar Sumber Data  di <strong><?php echo $wilayah; ?></strong></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-6 col-sm-12 col-xs-12">
									<table class="table table-responsive">
										<thead><tr><th>Sumber Data</th><th>&Sigma; <i class="fa fa-user"></i> ART</th></tr></thead>
										<tbody>
											<?php
											$toGraph = array();
											foreach($sumber as $key=>$item){
												$nilai = $skgakin_sumber[$key];
												$toGraph[$key] = array('nama'=>$item, 'data'=>$nilai);
												echo "<tr><td>".$item."</td><td class=\"angka\">".number_format($nilai,0)."</td></tr>";
											}
											?>
										</tbody>
									</table>
								</div>
								<div class="col-md-6 col-sm-12 col-xs-12">
									<div id="pie_chart_skgakin" style="height: 300px; width: 100%; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
					<!--/box-->
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<!--box filter-->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">
								<span class="fa-stack">
								<i class="fa fa-square-o fa-stack-2x"></i>
								<i class="fa fa-info fa-stack-1x"></i>
								</span> Periode SK Gakin</a>
							</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div><!-- /.box-tools -->

						</div>
						<div class="box-body">
							<div class="btn-group-vertical">
									<?php 
									foreach($periode as $key=>$item){
										$strA = ($key==$periode_on)? "btn-primary":"btn-default";
										$strI = ($key==$periode_on)? "<i class=\"fa fa-check\"></i> ":"";
										echo "<a class=\"btn ".$strA."\" href=\"".site_url('skgakin/index/'.$kode.'/'.$key.'/')."\">".$strI.$item['nama']."</a></li>";
									}
									
									?>
							</div>
						</div>
					</div>
					<!--/periode-->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">
								<span class="fa-stack">
								<i class="fa fa-square-o fa-stack-2x"></i>
								<i class="fa fa-info fa-stack-1x"></i>
								</span> Data SK Gakin Individual</a>
							</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div><!-- /.box-tools -->

						</div>
						<div class="box-body">
							<ul class="nav nav-pills">
								<?php
								
								foreach($list_query_individu as $key=>$item){
									$strBtn = ($key == $this->uri->segment('3')) ? "btn-primary":"btn-default";
									$strBtn = ($key == $this->uri->segment('3')) ? "class=\"active\"":"";
									//class=\"btn ".$strBtn." \"
									echo "<li ".$strBtn."><a  href=\"".site_url('skgakin/arts/'.$key)."\">".$item['nama']."</a></li>";
								}
								
								?>
							</ul>					
						</div>
					</div>
					<!--/akhir individual-->
					
				</div>
			</div><!--/row-->
			
					<!--box data-->
					<div class="box box-primary box-solid">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i> Rangkuman <a href="<?php echo site_url('skgakin');?>"><?php echo $boxTitle;?></a> di <strong><?php echo $wilayah; ?></strong></h3>
						</div>
						<!-- content index
						isi berupa rangkuman data
						-->
						<div class="box-body">
							
							<?php
							//echo var_dump($skgakin);
							?>
							
							<table class="table table-responsive table-bordered datatables">
								<thead><tr>
									<th rowspan="2">#</th>
									<th rowspan="2">Wilayah</th>
									<?php
									$i=1;
									foreach($periode as $key=>$item){
										if($i < 3){
											$toPeriode[$i] = $key;
											echo "<th colspan=\"2\">".$item['nama']."</th>";
										}
										$i++;
									}
									?>
								</tr>
								<tr>
									<?php 
									for($i=0;$i<2;$i++){
										echo "<th>RT</th><th>IDV</th>";
									}
									?>
									</tr>
								</thead>
								<tbody>
									<?php
									$nomer = 1;
									$sumRT1 = 0;
									$sumART1 = 0;
									$sumRT2 = 0;
									$sumART2 = 0;
									
									
									foreach($skgakin['sub'] as $key=>$item){
										echo "
										<tr><td class=\"angka\">".$nomer."</td>";
										if($skgakin['stingkat'] <7){
											echo "
											<td><a href=\"".site_url('skgakin/index/'.$key)."\">".$skgakin['stingkatan']." ".$item['nama']."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_rt/'.$key.'/'.$toPeriode[1])."\">".number_format($item['rt1'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_idv/'.$key.'/'.$toPeriode[1])."\">".number_format($item['art1'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_rt/'.$key.'/'.$toPeriode[2])."\">".number_format($item['rt2'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_idv/'.$key.'/'.$toPeriode[2])."\">".number_format($item['art2'],0)."</a></td>";
										}else{
											echo "
											<td>".$skgakin['stingkatan']." ".$item['nama']."</td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_rt/'.$key.'/'.$toPeriode[1])."\">".number_format($item['rt1'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_idv/'.$key.'/'.$toPeriode[1])."\">".number_format($item['art1'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_rt/'.$key.'/'.$toPeriode[2])."\">".number_format($item['rt2'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_idv/'.$key.'/'.$toPeriode[2])."\">".number_format($item['art2'],0)."</a></td>";
										}
										
											echo "
										</tr>
										";
										$sumART1 += $item['art1'];
										$sumRT1 += $item['rt1'];
										$sumART2 += $item['art2'];
										$sumRT2 += $item['rt2'];
										$nomer++;
									}
									?>
								</tbody>
								<tfoot>
									<tr><th colspan="2" class="angka">JUMLAH</th>
										<th class="angka"><?php echo number_format($sumRT1,0); ?></th>
										<th class="angka"><?php echo number_format($sumART1,0); ?></th>
										<th class="angka"><?php echo number_format($sumRT2,0); ?></th>
										<th class="angka"><?php echo number_format($sumART2,0); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					

					<!--box filter-->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-filter fa-fw"></i> <a href="<?php echo site_url('skgakin');?>"><?php echo $boxTitle_1;?></a></h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div><!-- /.box-tools -->

						</div>
						<div class="box-body">
							<form action="<?php echo $form_action;?>" method="POST" role="form" class="form-horizontal formular">
							<div class="form-group">
								<label class="col-md-4 col-sm-12 col-xs-12">Periode</label>
								<div class="col-md-8 col-sm-12 col-xs-12">
									<select name="periode" id="periode" class="form-control validate[required]">
										<?php 
										foreach($periode as $key=>$item){
											echo "<option value=\"".$key."\" ".$strS.">".$item['nama']."</option>";
										}
										
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 col-sm-12 col-xs-12">Wilayah Administrasi</label>
								<div class="col-md-8 col-sm-12 col-xs-12">
									<select name="kode" id="kode" class="form-control validate[required]" placeholder="Pilih Nama Wilayah">
										<?php
										echo "<option value=\"".KODE_BASE."\">Seluruh Wilayah</option>";
										foreach($kecamatan as $key=>$item){
											$strS = ($key == $param[1]) ? "selected=\"selected\"":"";
											echo "<option value=\"".$key."\" ".$strS.">Kecamatan ".$item['nama']."</option>";
											foreach ($desa as $de=>$sa){
												if(substr($de,0,7) == $key){
													$strS = ($de == $param[1]) ? "selected=\"selected\"":"";
													echo "<option value=\"".$de."\" ".$strS.">&nbsp;&nbsp;&nbsp;-&nbsp;Kelurahan ".$sa['nama']."</option>";
												}
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 col-sm-12 col-xs-12">Sumber Data</label>
								<div class="col-md-8 col-sm-12 col-xs-12">
									<select name="sumber" id="sumber" class="form-control validate[required]">
										<?php 
										echo "<option value=\"0\">Semua Sumber Data</option>";
										foreach($sumber as $key=>$item){
											$strS = ($key == $param[2]) ? "selected=\"selected\"":"";
											echo "<option value=\"".$key."\">".$item."</option>";
										}
										
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 col-sm-12 col-xs-12"></label>
								<div class="col-md-8 col-sm-12 col-xs-12">
									<button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Tampilkan Data</button>
								</div>
							</div>
							</form>
						</div>
					</div>


			
		</section>
	</div>
<!-- footer section -->


<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/highcharts.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/highcharts-more.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/modules/exporting.js"></script>
<script>
	$(document).ready(function(){
		var bc_container = $('#pie_chart_skgakin');
		if(bc_container){
			// Make monochrome colors and set them as default for all pies
			Highcharts.getOptions().plotOptions.pie.colors = (function () {
					var colors = [],
							base = Highcharts.getOptions().colors[0],
							i;

					for (i = 0; i < 10; i += 1) {
							// Start out with a darkened base color (negative brighten), and end
							// up with a much brighter color
							colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
					}
					return colors;
			}());

			// Build the chart
			Highcharts.chart('pie_chart_skgakin', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
				title: {
						text: 'Data SK GAKIN Berdasar Sumber Data  di <strong><?php echo $wilayah; ?></strong>'
				},
				tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
						pie: {
								allowPointSelect: true,
								cursor: 'pointer',
								dataLabels: {
										enabled: false,
										format: '<b>{point.name}</b>: {point.percentage:.1f} %',
										style: {
												color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
										}
								},
								showInLegend: true
						}
				},
				series: [{
						name: 'Sumber Data',
						colorByPoint: true,
						data: [
							<?php 
							$i=1;
							$n = count($toGraph);
							foreach($toGraph as $item){
								$strKoma = ($i < $n)? ", \n":"";
								echo "{name: \"".$item['nama']."\", y:".$item['data']."}".$strKoma;
								$i++;
							}
							?>
						]
				}]
			});			
		}
	});
</script>
<?php

$this->load->view('siteman/siteman_footer');
