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
				<div class="box-header with-border <?php echo $box_collapse[0];?>">
					<h3 class="box-title"><i class="fa fa-check-square-o fa-fw"></i> Indikator PBDT</a></h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->

				</div>
				<div class="box-body"  <?php echo $box_collapse[1];?>>
					
					<div class="row">
						<div class="col-md-8 col-sm-12 col-xs-12">
							<div class="box-group" id="accordion">
								<?php
								$i = 1;
								$n = count($pbk_kategori);
								foreach($pbk_kategori as $k=>$cat){
									$strIn = ($i == 1) ? "in":"";
									echo "
									<!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
									<div class=\"panel box box-primary\">
										<div class=\"box-header\">
											<h4 class=\"box-title\">
												<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse".$k."\">
													".$cat['nama']."
												</a>
											</h4>
										</div>
										<div id=\"collapse".$k."\" class=\"panel-collapse collapse \">
											<div class=\"box-body\">
												<ul class=\"nav nav-stack\">
												";
												foreach($pbk_param[$k] as $key=>$item){
													echo "<li><a href=\"".site_url('backend/pbdt/indikator/'.$varKode.'/'.$item['id'].'/'.fixNamaUrl($item['nama']))."\">".$item['nama']."</a></li>";
												}
												echo "
												</ul>
											</div>
										</div>
									</div>
									
									";
									$i++;
								}
								
								?>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 col-xs-12">
							
							<div class="box box-warning box-solid">
								<div class="box-header">
									<h4 class="box-title">Periode Pemutakhiran Data</h4>
								</div>
								<div class="box-body">
									<ul class="nav nav-stack">
										<?php
										foreach($pbk_periode['periode'] as $key=>$item){
											$strStatus = ($item['status'] == 1) ? "Sedang Berproses":"Sudah Diarsipkan";
											echo "<li><a href=\"\">".$item['nama']." (".$strStatus.")</a> </li>";
										}
										?>
									</ul>
								</div>
							</div>
							
						</div><!--/col--->
					</div><!--/row--->
				</div>
			</div>

			<!--box data-->
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i> Indikator per Rumah Tangga <strong><?php echo $boxTitle;?></strong> di <strong><?php echo $wilayah; ?></strong></h3>
				</div>
				
				<!-- content index
				isi berupa rangkuman data
				-->
				<div class="box-body">
					<ol class="breadcrumb">
					<?php
					if(is_array($alamat)){
						foreach($alamat as $key=>$item){
							echo "<li><a href=\"".site_url('backend/pbdt/indikator/'.$item['kode'].'/'.$indikator['id'].'/'.fixNamaUrl($indikator['nama']))."\">".$item['nama']."</a></li>";
						}
					}
					?>
					</ol>
					<?php

					$toGraphIdv["seri"] = array();						

					foreach($desil as $d=>$des){
						?>
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title"><?php echo $des[2];?> | <small><?php echo $boxTitle; ?></small></h3>
							</div>
							<div class="box-body">
								<!--tabular-->
								
								<table class="table table-responsive table-bordered datatables">
									<thead><tr><th>WILAYAH</th>
									<?php 
									//echo var_dump($indikator);
									foreach($indikator_opsi as $key=>$item){
										$sumIDV[$key]= 0;
										echo "<th>".$item['nama']."</th>";
									}
									?>
									</tr>
									</thead>
									<tbody>
										<?php
										$toGraph["title"][0][$d] = "Grafik PBDT ".$des[2]." Rumah Tangga Per Wilayah dgn ".$boxTitle;
										$i = 1;
										
										$n = count($data_kelas);
										$XAxis = "";
										
										foreach($data_kelas[$d] as $key=>$item) {
											
											$strKoma = ($i < $n) ? ",":"";
											$XAxis .= "'".$tingkatan[$item['tingkat']]." ".$item["nama"]."'".$strKoma;
											
											if($item['tingkat'] < 7){
												echo "
												<tr><td><a href=\"".site_url('backend/pbdt/indikator/'.$key.'/'.$indikator['id'].'/'.fixNamaUrl($indikator['nama']))."\">".$tingkatan[$item['tingkat']]." ".$item['nama']."</a></td>";
											}else{
												echo "
												<tr><td>".$tingkatan[$item['tingkat']]." ".$item['nama']."</td>";
											}
											foreach($indikator_opsi as $o=>$ops){
												$nilai_idv = $item['nDS_'.$ops['id']];
												$sumIDV[$o] += $nilai_idv;

												$toGraphIdv["seri"][$d][$key][$o] = $nilai_idv;
												
												echo "
												<td class=\"angka\"><a href=\"".site_url('backend/pbdt/indikator_rtm/'.$key.'/'.$d.'/'.$ops['param_id'].'/'.$ops['id'].'/')."\">".number_format($nilai_idv,0)."</a></td>
												";
											}
											echo "
											</tr>
											";
										}
										?>
									</tbody>
									<tfoot>
										<tr><th class="angka">&Sigma; JUMLAH</th>
											<?php 
											foreach($indikator_opsi as $key=>$item){
												echo "
												<th class=\"angka\"><a href=\"".site_url('backend/pbdt/indikator_rtm/'.$varKode.'/'.$d.'/'.$item['param_id'].'/'.$item['id'].'/')."\">".number_format($sumIDV[$key],0)."</a></th>
												";
											}
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
										<legend><strong>Grafik Indikator "<?php echo $boxTitle . "\" kategori ".$des[2]; ?></strong> di <?php echo $wilayah;?></legend>
										<div>
											<div id="bar_chart_container_<?php echo $d;?>"></div>
										</div>
									</fieldset>`

								</div>
								<!--grafis-->

								<!--peta-->
									<fieldset class="kotak">
										<legend><strong>Peta Sebaran PBDT </strong><?php echo $wilayah;?></legend>
										<div class="row">
											<div class="col-md-6 col-sm-12 col-xs-12">
												<div id="map_canvas_<?php echo $d;?>" class="map_canvas"></div>
											</div>
											<div class="col-md-6 col-sm-12 col-xs-12">
												<div id="map_info">
													<div class="box box-primary">
														<div class="box-header with-border"><h3 class="box-title" id="info_title_<?php echo $d; ?>"><?php echo $wilayah; ?></h3></div>
														<div class="box-body">
															<table class="table table-responsive">
																<thead><tr><th>Opsi Jawaban</th><th>&Sigma; Responden</th></tr></thead>
																<tbody>
																<?php 
																foreach($indikator_opsi as $key=>$item){
																	echo "
																	<tr><td><nobr>".$item['nama']."</nobr></td>
																	<th class=\"angka\"><a href=\"#\" id=\"val_".$d."_".$item['id']."\">".number_format($sumIDV[$key],0)."</a></th>
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
						<?php
					}
					
					?>
					

					
				</div>
			</div>
			<!--/box data-->
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

<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/highcharts.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/highcharts-more.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/modules/exporting.js"></script>
<script>
	$(document).ready(function(){
		<?php
		foreach($desil as $d=>$des){
		?>
			var bc_container<?php echo $d;?> = $('#bar_chart_container_<?php echo $d;?>');
			if(bc_container<?php echo $d;?>){
				$('#bar_chart_container_<?php echo $d;?>').highcharts({
					chart: {type: 'column'},
					title: {text: '<?php echo $toGraph["title"][0][$d];?>'},
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
						$n = count($indikator_opsi);
						foreach($indikator_opsi as $o=>$op){
							$strKoma = ($i < $n) ? ",":"";
							echo "\n{
							name : '".$op['nama']."',
							data : [";
							$x = 1;
							$y = count($toGraphIdv['seri'][$d]);
							// echo var_dump($toGraphIdv['seri'][$d]);
							foreach($toGraphIdv['seri'][$d] as $key=>$dt){
								$strC = ($x < $y) ? ",":"";
								echo $dt[$o].$strC;
								$x++;
							}
							echo "]}".$strKoma;
						}

						?>				
					]
				});
			}
			<?php 
			}

			?>
		
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
			<?php 
			foreach($desil as $d=>$des){
				echo "var map_".$d.";
				var infoWindow_".$d.";";
			}
				
			?>
      
      var tengah = {lat: <?php echo GMAP_LAT; ?>, lng: <?php echo GMAP_LON; ?>};
      
      function initMap() {
				<?php 
				foreach($desil as $d=>$des){
					
				?>
        map_<?php echo $d;?> = new google.maps.Map(document.getElementById('map_canvas_<?php echo $d;?>'), {
          zoom: <?php echo GMAP_ZOOM; ?>,
          center: tengah
        });

        // Muat GeoJSON.
        map_<?php echo $d;?>.data.loadGeoJson(
            '<?php echo site_url('petajson/pbdt_indikator/'.$varKode.'/'.$indikator['id'].'/'.$des[3]); ?>');

        // atur semua berwarna abu2, kecuali disebut isColorful
        
        map_<?php echo $d;?>.data.setStyle(function(feature) {
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
        
				var infoWindow_<?php echo $d;?> = new google.maps.InfoWindow({
						content: "<div id='iw_<?php echo $d;?>'><?php echo $d;?></div>",
						pixelOffset: new google.maps.Size(0, 20)
				});
        
        // When the user clicks, set 'isColorful', changing the color of the letters.
        map_<?php echo $d;?>.data.addListener('click', function(event) {
					event.feature.setProperty('isColorful', true);
					<?php 
					foreach($indikator_opsi as $o=>$ops){
						echo "
						var dt_".$d."_".$ops['id']." = event.feature.getProperty('nDS".$ops['id']."');
						document.getElementById('val_".$d."_".$ops['id']."').textContent = dt_".$d."_".$ops['id'].";
						";
					}
					?>
					var iw_<?php echo $d;?> = event.feature.getProperty('info_title');
					infoWindow_<?php echo $d?>.setPosition(event.latLng);
					infoWindow_<?php echo $d?>.setContent(iw_<?php echo $d;?>);

					//document.getElementById('iw_<?php echo $d;?>').textContent = iw_<?php echo $d;?>;

					var anchor = new google.maps.MVCObject();
					anchor.setValues({ //position of the point
							position: event.latLng,
							anchorPoint: new google.maps.Point(0, -20)
					});

					infoWindow_<?php echo $d; ?>.open(map_<?php echo $d;?>, anchor);
        });

        map_<?php echo $d;?>.data.addListener('mouseover', function(event) {
          document.getElementById('info_title_<?php echo $d; ?>').textContent =
            event.feature.getProperty('info_title');
        });

        // When the user hovers, tempt them to click by outlining the letters.
        // Call revertStyle() to remove all overrides. This will use the style rules
        // defined in the function passed to setStyle()
        map_<?php echo $d;?>.data.addListener('mouseover', function(event) {
          map_<?php echo $d;?>.data.revertStyle();
          map_<?php echo $d;?>.data.overrideStyle(event.feature, {strokeWeight: 8});
          
        });

        map_<?php echo $d;?>.data.addListener('mouseout', function(event) {
          map_<?php echo $d;?>.data.revertStyle();
        });
        <?php
				}
				
        ?>
				
        
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAP_KEY;?>&callback=initMap">
    </script>



<?php

$this->load->view('siteman/siteman_footer');
