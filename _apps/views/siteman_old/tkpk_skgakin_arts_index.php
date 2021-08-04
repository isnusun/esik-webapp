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
					<!--/akhir individual-->
					<div class="box box-primary">
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

					
				</div>
			</div><!--/row-->


			
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
