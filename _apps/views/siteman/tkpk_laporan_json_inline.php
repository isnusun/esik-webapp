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
					<div id="map_canvas" style="height:500px;width:100%"></div>
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
									foreach($skgakin['sub'] as $key=>$item){
										$strKoma = ($nomer < $n) ? ", ":"";
										$xAxis .= "\"".$item['nama']."\"".$strKoma."";
										$seri_rt[$key] = array($item['rt1'],$item['rt2']);
										$seri_idv[$key] = array($item['art1'],$item['art2']);
										$seri_wil[$key]=$item['nama'];
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

					</div>

				</div>
			</div>

			<!--box filter-->
		</section>
	</div>
<!-- footer section -->

<script src="<?php echo base_url('assets/js/polygon.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.google.com/maps/api/js?key=<?php echo GMAP_KEY;?>"></script>
<script>
	var map;
	var infoWindow;

function initialize() {

	var mapOptions = {
		zoom: <?php echo GMAP_ZOOM;?>,
		scrollwheel: false,
		center: new google.maps.LatLng(<?php echo GMAP_LAT;?>,<?php echo GMAP_LON;?>),
		mapTypeId: google.maps.MapTypeId.TERRAIN
	};

	// These are declared outside of the init function, so I
	// initialized them together at the top of the function..
	map = new google.maps.Map(document.getElementById('map_canvas'),mapOptions);
	infoWindow = new google.maps.InfoWindow();
	<?php
	
	$warna = Gradient("ffffff","336699",count($toMap));
	$n = 0;
	if($toMap){
		if(count($toMap) > 0){
			foreach($toMap as $key=>$item){
				$wpath = json_decode($item["path"]);
				$newpath = "";
				$i = 1;
				$npath = count($wpath);
				if($wpath){
					if(count($wpath) > 0){
						foreach($wpath as $pa){
							if($i < $npath){
								$newpath .= "new google.maps.LatLng(".$pa[1].",".$pa[0]."),\n";
							}else{
								$newpath .= "new google.maps.LatLng(".$pa[1].",".$pa[0].")\n";
							}
							$i++;
						}
						echo "
						var peta_$key = new google.maps.Polygon({
							paths: [".$newpath."],
							strokeColor: '#ffffff',
							strokeOpacity: 0.8,
							strokeWeight: 1,
							fillColor: '#".$warna[$n]."',
							fillOpacity: 0.8,
							name: 'Wilayah ".$item["nama"]."',
							url: '".site_url('petajson/data_skgakin/').$key."',
							map: map			
						});
						
						google.maps.event.addListener(peta_".$key.",'mouseover', function(event) {
							google.maps.event.revertStyle();
							google.maps.event.overrideStyle(event.feature, {strokeWeight: 8});
						});

						google.maps.event.addListener(peta_".$key.",'mouseout', function(event) {
							google.maps.event.revertStyle();
						});
						
						google.maps.event.addListener(peta_".$key.", 'click', AjaxDisplayString);

						
						";
						
					}
				}
				$n++;
			}
			echo "
			
			";

		}
	}
	?>
}

function showInfo(event) {
	var contentString = '<h1 class="info_judul">' + this.name + '</h1>'+
			'<div class="info_box">' +
			'Clicked location: <br>' + this.url +
			'<br>';

	infoWindow.setContent(contentString);
	infoWindow.setPosition(event.latLng);

	infoWindow.open(map);
}

function AjaxDisplayString(event) {
	var addressData;
	var testData;
	$.ajax({
			type: "GET",
			url: ''+ this.url +'',
		 dataType: "HTML",
			contentType: 'application/json',
			traditional: true,
			data: addressData,
			success: function (result) {
					debugger;
					infoWindow.setContent("<div>"+ result + "</div>");  
					infoWindow.setPosition(event.latLng);
					infoWindow.open(map);               
			},
			error: function (arg) {
					alert("Error");
			}

	});

}

google.maps.event.addDomListener(window, 'load', initialize);
</script>

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
	});

</script>


    
<?php

$this->load->view('siteman/siteman_footer');
