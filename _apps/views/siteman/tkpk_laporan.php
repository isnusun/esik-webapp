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
			<li class="active">PBDT 2015</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">

			<!--box data-->
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-map-o fa-fw"></i> <?php echo $boxTitle;?></h3>
				</div>
				
				<!-- content index
				isi berupa rangkuman data
				-->
				<div class="box-body">
					<div class="row">
						<div class="col-md-8">
							<div id="map_canvas" style="height:500px;width:100%"></div>
						</div>
						<div class="col-md-4">
							
									<div id="map_info">
										<div class="box box-primary">
											<div class="box-header with-border"><h3 class="box-title" id="info_title"><?php echo $wilayah; ?></h3></div>
											<div class="box-body">
												<table class="table table-responsive">
													<thead><tr><th>DESIL</th><th>Rumah Tangga</th><th>Individu</th></tr></thead>
													<tbody>
														<tr>
															<td>Rumah Tangga</td>
															<th class="angka"><span id="nrt"></span></th>
														</tr>
														<tr>
															<td>Anggota Rumah Tangga</td>
															<th class="angka"><span id="nart"></span></th>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
							
							
						</div>
					</div>
				</div>
			</div>

			<!--box filter-->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-filter fa-fw"></i> Data Distribusi SK Gakin Per Wilayah di <?php echo $wilayah; ?></a></h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->

				</div>
				<div class="box-body">
					
					<div class="row">
						<div class="col-md-6 col-sm-12 col-xs-12">
							<table class="table table-responsive table-bordered datatables">
								<thead><tr>
									<th rowspan="2">#</th>
									<th rowspan="2">Wilayah</th>
									<?php
									$sumRT = array();
									$sumART = array();
									$i=1;
									$xAxis1 = "";
									$n = count($periode);
									foreach($periode as $key=>$item){
										$sumRT[$key] = 0;
										$sumART[$key] = 0;
										
										$strKoma = ($i < $n) ? ", ":"";
										$xAxis1 .= "\"".$item['nama']."\"".$strKoma; 
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
									
									$xAxis = "";
									$n = count($skgakin['sub']);
									$seri_wil = array();
									$seri_rt = array();
									$seri_idv = array();
									$toBuble = array();
									foreach($skgakin['sub'] as $key=>$item){
										$strKoma = ($nomer < $n) ? ", ":"";
										$xAxis .= "\"".$item['nama']."\"".$strKoma."";
										$seri_rt[$key] = array($item['rt1'],$item['rt2']);
										$seri_idv[$key] = array($item['art1'],$item['art2']);
										$seri_wil[$key]=$item['nama'];
										
										$inisial = "";
										if($skgakin['stingkat'] <6){
											if(strpos(trim($item['nama'])," ")){
												$ini = explode(" ",$item['nama']);
												foreach($ini as $ni){
													$inisial .= substr($ni,0,1);
												}
											}else{
												$inisial = substr($item['nama'],0,2);
											}
										}else{
											$inisial = $skgakin['stingkatan']." ".$item['nama'];
										}
										$toBuble[] = array('x'=>$item['rt2'],'y'=>$item['art2'],'name'=>$inisial,'area'=>$item['nama']);
										
										echo "
										<tr><td class=\"angka\">".$nomer."</td>";
										if($skgakin['stingkat'] <7){
											echo "
											<td><a href=\"".site_url('laporan/index/'.$key)."\">".$skgakin['stingkatan']." ".$item['nama']."</a></td>
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
									
									$series = array(
										0=>array(
											"nama"=>"Rumah Tangga", 
											"satuan"=>"RT",
											"data"=>array($sumRT1,$sumRT2)
											),
										1=>array(
											"nama"=>"Individu", 
											"satuan"=>"Jiwa",
											"data"=>array($sumART1,$sumART2)
											),
									);
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
						<div class="col-md-6 col-sm-12 col-xs-12">
							<div id="skgakin_barchart" style="min-width: 310px; min-height: 400px; margin: 0 auto"></div>
						</div>

					</div> <!--/row-->

					<div class="box box-primary">
						<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-bar-chart fa-fw"></i> Grafik Distribusi SK Gakin dlm Grafik Balon </h3></div>
						<div class="box-body">
							<div id="skgakin_buble"></div>
						</div>
					</div>					
					
					<div class="box box-default">
						<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-bar-chart fa-fw"></i> Grafik Distribusi Rumah Tangga SK Gakin </h3></div>
						<div class="box-body">
							<div id="skgakin_distribusi_rt"></div>
						</div>

						<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-bar-chart fa-fw"></i> Grafik Distribusi Individu SK Gakin </h3></div>
						<div class="box-body">
							<div id="skgakin_distribusi_idv"></div>
						</div>

					</div>
				</div>
			</div>

			<!--box filter-->
		</section>
	</div>
<!-- footer section -->
<!-- Grafik -->

<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/highcharts.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/highcharts-more.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/themes/skies.js"></script>
<script>
	$(document).ready(function(){
		var skgakin_barchart = $('#skgakin_barchart');
		if(skgakin_barchart){
			skgakin_barchart.highcharts({

chart: {
            zoomType: 'xy'
        },
        title: {
            text: '<?php echo $pageTitle;?>'
        },
        subtitle: {
            text: '<?php echo $wilayah; ?>'
        },
        xAxis: [{
            categories: [<?php echo $xAxis1;?>],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: 'Rumah Tangga',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: 'Individu (jiwa)',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 120,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [
					<?php
					$i=1;
					$n = count($series);
					foreach($series as $key=>$item){
						$strKoma = ($i < $n) ? ", ":"";
						
						echo "{
							name: '".$item['nama']."',
							type: 'column',";
							if($i==1){
								echo "yAxis: ".$i.",";
							}
							
							echo "
							data: ".json_encode($item['data']).",
							tooltip: {
								valueSuffix: ' ".$item['satuan']."'
							}
						}".$strKoma."";
						$i++;
					}
					?>
				]
				

			});
		}
		
		var skgakin_distribusi_rt = $('#skgakin_distribusi_rt');
		if(skgakin_distribusi_rt){
			skgakin_distribusi_rt.highcharts({
        chart: {
            type: 'column'
        },

        title: {
            text: 'Distribusi Rumah Tangga Berbasis Wilayah'
        },

        xAxis: {
            categories: [<?php echo $xAxis1; ?>]
        },

        yAxis: {
            min: 0,
            title: {
                text: 'Jumlah Rumah Tangga'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
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

        series: [
        <?php
        $i=1;
        $n = count($seri_wil);
        foreach($seri_wil as $key=>$item){
					$strKoma = ($i < $n) ? ",":"";
					echo "{
						name: '".$item."',
						data: ".str_replace("\"","",json_encode($seri_rt[$key]))."
					}".$strKoma;
				}
        ?>
        ]				
			});
		}
		
		var skgakin_distribusi_idv = $('#skgakin_distribusi_idv');
		if(skgakin_distribusi_idv){
			skgakin_distribusi_idv.highcharts({
        chart: {
            type: 'column'
        },

        title: {
            text: 'Distribusi Individu Berbasis Wilayah'
        },

        xAxis: {
            categories: [<?php echo $xAxis1; ?>]
        },

        yAxis: {
            min: 0,
            title: {
                text: 'Jumlah Jiwa'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
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

        series: [
        <?php
        $i=1;
        $n = count($seri_wil);
        foreach($seri_wil as $key=>$item){
					$strKoma = ($i < $n) ? ",":"";
					echo "{
						name: '".$item."',
						data: ".str_replace("\"","",json_encode($seri_idv[$key]))."
					}".$strKoma;
				}
        ?>
        ]				
			});
		}
		
		var skgakin_buble = $('#skgakin_buble');
		if(skgakin_buble){
			
			Highcharts.chart('skgakin_buble', {

					chart: {
							type: 'bubble',
							plotBorderWidth: 1,
							zoomType: 'xy'
					},

					legend: {
							enabled: false
					},

					title: {
							text: 'Data Sebaran SK Gakin'
					},


					xAxis: {
							gridLineWidth: 1,
							title: {
									text: 'Banyaknya Individu  (jiwa)'
							},
							labels: {
									format: '{value}'
							}
					},

					yAxis: {
							startOnTick: false,
							endOnTick: false,
							title: {
									text: 'Banyaknya Rumah Tangga (rtm)'
							},
							labels: {
									format: '{value}'
							},
							maxPadding: 0.2
					},

					tooltip: {
							useHTML: true,
							headerFormat: '<table>',
							pointFormat: '<tr><th colspan="2"><h3>{point.wilayah}</h3></th></tr>' +
									'<tr><th>Individu:</th><td class="angka">{point.x}</td></tr>' +
									'<tr><th>Rumah Tangga:</th><td class="angka">{point.y}</td></tr>',
							footerFormat: '</table>',
							followPointer: true
					},

					plotOptions: {
							series: {
									dataLabels: {
											enabled: true,
											format: '{point.name}'
									}
							}
					},

					series: [{
							data: [
								<?php 
								$i=1;
								$n = count($toBuble);
								foreach($toBuble as $key=>$item){
									$strKoma = ($i < $n) ? ", \n":"";
									echo "{x: ".$item['x'].",y: ".$item['y'].",name: '".strtoupper($item['name'])."', wilayah: '".$item['area']."'}".$strKoma;
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
/*
 * Pengelolaan Peta (GOogle Map)
 * 
 * referensi yg dibutuhkan: 
 * -google map polygon hover  
 * 
 * */


?>
    <script>
      var map;
      var infoWindow;
      var tengah = {lat: <?php echo GMAP_LAT; ?>, lng: <?php echo GMAP_LON; ?>};
      
      function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
          zoom: <?php echo GMAP_ZOOM; ?>,
          center: tengah
        });

        // Load GeoJSON.
        map.data.loadGeoJson(
            '<?php echo site_url('petajson/pbdt/'.$varKode); ?>');

        // Color each letter gray. Change the color when the isColorful property
        // is set to true.
        map.data.setStyle(function(feature) {
          var color = 'gray';
          if (feature.getProperty('isColorful')) {
            color = feature.getProperty('colorStroke');
          }
          return /** @type {google.maps.Data.StyleOptions} */({
            strokeColor: feature.getProperty('colorStroke'),
            strokeOpacity: 0.9,   
            strokeWeight: 2,
            fillColor: feature.getProperty('colorFill'),
            fillOpacity: 0.8
          });
        });
        
				var infoWindow = new google.maps.InfoWindow({
						content: "",
						pixelOffset: new google.maps.Size(0, -40)
				});
        
        // When the user clicks, set 'isColorful', changing the color of the letters.
        map.data.addListener('click', function(event) {
					/** 
					*/

					event.feature.setProperty('isColorful', true);
					
					var iw = event.feature.getProperty('info_title');
					
					var rtm1 = event.feature.getProperty('rtm_ds1');
					var rtm2 = event.feature.getProperty('rtm_ds2');
					var rtm3 = event.feature.getProperty('rtm_ds3');
					var rtm4 = event.feature.getProperty('rtm_ds4');
					
					var idv1 = event.feature.getProperty('idv_ds1');
					var idv2 = event.feature.getProperty('idv_ds2');
					var idv3 = event.feature.getProperty('idv_ds3');
					var idv4 = event.feature.getProperty('idv_ds4');
					
					document.getElementById('kat1').textContent = rtm1;
					document.getElementById('kat2').textContent = rtm2;
					document.getElementById('kat3').textContent = rtm3;
					document.getElementById('kat4').textContent = rtm4;

					document.getElementById('pkat1').textContent = idv1;
					document.getElementById('pkat2').textContent = idv2;
					document.getElementById('pkat3').textContent = idv3;
					document.getElementById('pkat4').textContent = idv4;
					
					// 
					infoWindow.setPosition(event.latLng);
					infoWindow.setContent(iw);

					var anchor = new google.maps.MVCObject();
					anchor.setValues({ //position of the point
							position: event.latLng,
							anchorPoint: new google.maps.Point(0, -20)
					});

					infoWindow.open(map, anchor);
        });

        map.data.addListener('mouseover', function(event) {
          document.getElementById('info_title').textContent =
            event.feature.getProperty('info_title');
        });

        // When the user hovers, tempt them to click by outlining the letters.
        // Call revertStyle() to remove all overrides. This will use the style rules
        // defined in the function passed to setStyle()
        map.data.addListener('mouseover', function(event) {
          map.data.revertStyle();
          map.data.overrideStyle(event.feature, {strokeWeight: 8});
          
        });

        map.data.addListener('mouseout', function(event) {
          map.data.revertStyle();
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAP_KEY;?>&callback=initMap">
    </script>


    
<?php

$this->load->view('siteman/siteman_footer');
