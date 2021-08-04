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
<?php
$this->load->view('siteman/pbk_header');
?>
			<!--box data-->
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i> Rangkuman <?php echo $boxTitle;?> di <strong><?php echo $wilayah; ?></strong></h3>
				</div>
				
				<!-- content index
				isi berupa rangkuman data
				-->
				<div class="box-body">

					<!--tabular-->
					<ol class="breadcrumb">
					<?php
					if(is_array($alamat)){
						foreach($alamat as $key=>$item){
							echo "<li><a href=\"".site_url('pbk/index/'.$item['kode'])."\">".$item['nama']."</a></li>";
						}
					}
					?>
					</ol>
					<table class="table table-responsive table-bordered datatables">
						<thead><tr><th rowspan="2">WILAYAH</th>
						<?php 

						foreach($desil as $key=>$item){
							echo "<th colspan=\"2\">".$item['nama']."</th>";
						}
						?>
						<th colspan="2">SUB TOTAL</th>
						</tr>
						<tr>
						<?php 
						foreach($desil as $key=>$item){
							$sumIDV[$key] = 0;
							$sumRTM[$key] = 0;
							echo "
							<th><i class=\"fa fa-user fa-fw\"></i> IDV</th>
							<th><i class=\"fa fa-home fa-fw\"></i> RT</th>
							";
						}
						$totalIDV = 0;
						$totalRTM = 0;
						?>
							<th>&Sigma; <i class="fa fa-user fa-fw"></i> IDV</th>
							<th>&Sigma; <i class="fa fa-home fa-fw"></i> RT</th>
						</tr>
						</thead>
						<tbody>
							<?php
							$toGraph["title"][0] = "Grafik Data Lokal/Sisiran Rumah Tangga Per Wilayah";
							$toGraph["title"][1] = "Grafik Data Lokal/Sisiran Individu Per Wilayah";
							$i = 1;
							
							$n = count($data_kelas);
							$XAxis = "";
							$toGraph["seri"] = array();
							$toGraphIdv["seri"] = array();						
							
							
							foreach($data_kelas as $key=>$item) {
								$strKoma = ($i < $n) ? ",":"";
								$XAxis .= "'".$tingkatan[$item['tingkat']]." ".$item["nama"]."'".$strKoma;
								
								if($item['tingkat'] < 7){
									echo "
									<tr><td><a href=\"".site_url('pbk/index/'.$key)."\">".$tingkatan[$item['tingkat']]." ".$item['nama']."</a></td>";
								}else{
									echo "
									<tr><td>".$tingkatan[$item['tingkat']]." ".$item['nama']."</td>";
								}

								$sumByWil['idv'] = 0;
								$sumByWil['rtm'] = 0;

								foreach($desil as $d=>$ds){
									$nilai_idv = $item['p'.$d];
									$nilai_rtm = $item[$d];
									$sumIDV[$d] += $nilai_idv;
									$sumRTM[$d] += $nilai_rtm;

									$toGraphIdv["seri"][$d][$key] = $nilai_idv;
									$toGraph["seri"][$d][$key] = $nilai_rtm;
									
									$sumByWil['idv'] += $nilai_idv;
									$sumByWil['rtm'] += $nilai_rtm;
									
									echo "
									<td class=\"angka\"><a href=\"".site_url('pbk/individu/'.$key.'/'.$d.'/')."\">".number_format($nilai_idv,0)."</a></td>
									<td class=\"angka\"><a href=\"".site_url('pbk/rtm/'.$key.'/'.$d.'/')."\">".number_format($nilai_rtm,0)."</a></td>
									";
								}
								echo "
									<td><a href=\"".site_url('pbk/individu/'.$key)."\">".number_format($sumByWil['idv'],0)."</a></td>
									<td><a href=\"".site_url('pbk/rtm/'.$key)."\">".number_format($sumByWil['rtm'],0)."</a></td>
								
								</tr>
								";
								$totalIDV += $sumByWil['idv'];
								$totalRTM += $sumByWil['rtm'];
							}
							?>
						</tbody>
						<tfoot>
							<tr><th class="angka">&Sigma; JUMLAH</th>
								<?php 
								foreach($desil as $key=>$item){
									echo "
									<th class=\"angka\"><a href=\"\">".number_format($sumIDV[$key],0)."</a></th>
									<th class=\"angka\"><a href=\"\">".number_format($sumRTM[$key],0)."</a></th>
									";
								}
									echo "
									<th class=\"angka\"><a href=\"\">".number_format($totalIDV,0)."</a></th>
									<th class=\"angka\"><a href=\"\">".number_format($totalRTM,0)."</a></th>
									";
								?>
							</tr>
						</tfoot>
					</table>
					<!--tabular-->
					
					<!--grafis-->
					<?php
					$the_alamat = "";
					if(is_array($alamat)){
						foreach($alamat as $key=>$item){
							$the_alamat .= $item['nama'] ." ";
						}
					}
					?>
					<div class="">
						<fieldset class="kotak">
							<legend><strong>Grafik Data Sisiran </strong><?php echo $wilayah;?></legend>
							<div>
								<div id="bar_chart_container"></div>
							</div>
						</fieldset>`

						<fieldset class="kotak">
							<legend><strong>Grafik Data Sisiran Individu </strong><?php echo $wilayah;?></legend>
							<div>
								<div id="bar_chart_container_idv"></div>
							</div>
						</fieldset>`						

					</div>
					<!--grafis-->

					<!--peta-->
						<fieldset class="kotak">
							<legend><strong>Peta Sebaran PBDT </strong><?php echo $wilayah;?></legend>
							<div class="row">
								<div class="col-md-9 col-sm-6 col-xs-12">
									<div id="map_canvas" class="map_canvas"></div>
								</div>
								<div class="col-md-3 col-sm-6 col-xs-12">
									<div id="map_info">
										<div class="box box-primary">
											<div class="box-header with-border"><h3 class="box-title" id="info_title"><?php echo $wilayah; ?></h3></div>
											<div class="box-body">
												<table class="table table-responsive">
													<thead><tr><th>Klasifikasi</th><th>Rumah Tangga</th><th>Individu</th></tr></thead>
													<tbody>
													<?php 
													foreach($desil as $key=>$item){
														echo "
														<tr><td><nobr>".$item['nama']."</nobr></td>
														<th class=\"angka\"><a href=\"#\" id=\"".$key."\">".number_format($sumIDV[$key],0)."</a></th>
														<th class=\"angka\"><a href=\"#\" id=\"p".$key."\">".number_format($sumRTM[$key],0)."</a></th>
														</tr>
														";
													}
													?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div id="tt"></div>
								</div>
							</div>
						</fieldset>`						

					<!--peta-->

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
				title: {text: '<?php echo $toGraph["title"][0];?>'},
				subtitle: {text: '<?php echo $pageTitle ." ". $wilayah;?>'},
				xAxis: {
					categories: [ <?php 
					echo $XAxis;
					?>
					],
					crosshair: true			
				},        
				yAxis: {
					min: 0,
					title: {
						text: 'Populasi (Rumah Tangga)'
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
						name : '".$ds['nama']."',
						data : [";
						$x = 1;
						$y = count($toGraph['seri'][$d]);
						foreach($toGraph['seri'][$d] as $dt){
							$strC = ($x < $y) ? ",":"";
							echo $dt.$strC;
							$x++;
						}
						echo "]}".$strKoma;
					}

					?>				
				]
			});
		}
		
		var bc_container = $('#bar_chart_container_idv');
		if(bc_container){
			$('#bar_chart_container_idv').highcharts({
				chart: {type: 'column'},
				title: {text: '<?php echo $toGraph["title"][1];?>'},
				subtitle: {text: '<?php echo $pageTitle ." ". $wilayah;?>'},
				xAxis: {
					categories: [ <?php 
					echo $XAxis;
					?>
					],
					crosshair: true			
				},        
				yAxis: {
					min: 0,
					title: {
						text: 'Populasi (Individu)'
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
						name : '".$ds['nama']."',
						data : [";
						$x = 1;
						$y = count($toGraphIdv['seri'][$d]);
						foreach($toGraphIdv['seri'][$d] as $dt){
							$strC = ($x < $y) ? ",":"";
							echo $dt.$strC;
							$x++;
						}
						echo "]}".$strKoma;
					}

					?>				
				]
			});
		}		
	})

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
<!-- GoogleMap v3 -->
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
