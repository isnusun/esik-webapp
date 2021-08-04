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
			Program Bantuan Sosial
			<small><?php echo APP_TITLE;?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('program_bansos')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Data Pengguna</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-8 col-sm-6 col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><a href="<?php echo site_url('program_bansos');?>"><?php echo $program['nama'];?></a></h3>
						</div>
						<div class="box-body">
							<?php 
							if($msg){
								echo "
								<div class=\"alert alert-success alert-dismissable\">
									<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
									<h4><i class=\"fa fa-check\"></i> ".$msg['msg']."</h4>
								</div>";
							}
							$tanggal = ($program_id > 0) ? $program['sdate']." - ".$program['edate']:date('Y-m-d')." - ".date('Y-m-d');
							?>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Sasaran Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<input type="text" class="form-control" value="<?php echo $program_sasaran[$program['sasaran']]; ?>" disabled="disabled" />
									</div>
								</div>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Nama Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
									<input type="text" class="form-control" name="nama" id="nama" placeholder="Tuliskan nama program" value="<?php echo $program['nama']; ?>" disabled="disabled"/>
									</div>
								</div>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Pemilik/Pelaksana Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<input type="text" class="form-control" value="<?php echo $program['lembaga_nama']; ?>" disabled="disabled" />
									</div>
								</div>
								
								<div class="form-group">
									<label class="label-control col-md-12 col-sm-12 col-xs-12">Keterangan</label>
									<div class="col-md-12">
										<?php echo $program['ndesc']; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Rentang Waktu Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="" value="<?php echo $tanggal; ?>" disabled="disabled"/>
									</div>
								</div>
							<form action="<?php echo $form_action; ?>" method="POST" class="formular form-horizontal" role="form">
								
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-info fa-fw"></i> Panduan Penggunaan Modul</h3>
						</div>
						<div class="box-body">
							Cukup Jelas / menyusul
						</div>
					</div>
				</div>
			</div>
			<!--Tabular-->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('program_bansos/view/'.$program_id.'/'.$varKode);?>">Daftar Penerima Manfaat: <?php echo $program['nama'];?></a></h3>
				</div>
				<div class="box-body">
					<?php 
					$toGraph = array();
					$toGraph['title'] = "Daftar Penerima Manfaat: ".$program['nama']."";
					$toGraph['series'] = array();
					$toGraph['xAxis'] = "";
					
					if($program_peserta){
						if(count($program_peserta) > 0){
							
							echo "";
							?>
							<table class="table table-responsive datatables"><thead>
								<tr><th>#</th><th>NAMA WILAYAH</th>
								<th><i class="fa fa-female"></i> PEREMPUAN</th>
								<th><i class="fa fa-male"></i> LAKI-LAKI</th>
								<th>&Sigma; SUB TOTAL</th>
								</tr>
							</thead><tbody>
								<?php
								$nomer = 1;
								$sumCo = 0;
								$sumCe = 0;
								$sumTotal = 0;
								$n = count($program_peserta);
								$toGraph['yAxis'] = "Jumlah Penerima Manfaat (jiwa)";
								$dataL = "";
								$dataP = "";
								foreach($program_peserta as $key=>$rs){
									$strKoma = ($nomer < $n)? ",":"";
									
									$sumCe += $rs['angkace'];
									$sumCo += $rs['angkaco'];
									$jumlah = $rs['angkace'] + $rs['angkaco'];
									$sumTotal += $jumlah;
									
									$toGraph['xAxis'] .="'".$rs['nama']."'".$strKoma;
									$dataL .= $rs['angkace'].$strKoma;
									$dataP .= $rs['angkaco'].$strKoma;
									echo "
									<tr><td class=\"angka\">".$nomer."</td>
									<td><a href=\"".site_url('program_bansos/view/'.$program_id.'/'.$key)."\">".$rs['nama']."</a></td>
									<td class=\"angka\"><a href=\"".site_url('program_bansos/siapa/'.$program_id.'/'.$key.'/2')."\">".number_format($rs['angkace'],0)."</a></td>
									<td class=\"angka\"><a href=\"".site_url('program_bansos/siapa/'.$program_id.'/'.$key.'/1')."\">".number_format($rs['angkaco'],0)."</a></td>
									<td class=\"angka\"><a href=\"".site_url('program_bansos/siapa/'.$program_id.'/'.$key.'')."\">".number_format($jumlah,0)."</a></td>
									</tr>";
									$nomer++;
								}
								$toGraph['series']['L'] = array("nama"=>"Laki-laki","data"=>$dataL);
								$toGraph['series']['P'] = array("nama"=>"Perempuan","data"=>$dataP);
								
								$toPeta = array("laki"=>$sumCo, "pere"=>$sumCe, "sum"=>$sumTotal);

								?>
							</tbody>
							<tfoot>
								<tr><th colspan="2" class="angka">TOTAL</th>
								<th class="angka"><?php echo number_format($sumCe,0); ?></th>
								<th class="angka"><?php echo number_format($sumCo,0); ?></th>
								<th class="angka"><?php echo number_format($sumTotal,0); ?></th></tr>
							</tfoot>
							</table>
							<?php
						}else{
							echo "
							<div class=\"alert alert-warning alert-dismissible\">
								<button class=\"close\" type=\"button\" data-dismiss=\"alert\"  aria-hidden=\"true\">&times;</button>
								<h4><i class=\"fa fa-warning fa-fw\"></i> Belum ada data Penerima Manfaat ".$program['nama']."</h4>
							</div>
							";
						}
					}
					?>
				</div>
			</div>
			<!--/Tabular-->
			<!--Grafik-->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('program_bansos/view/'.$program_id.'/'.$varKode);?>">Grafik Distribusi Penerima Manfaat: <?php echo $program['nama'];?></a></h3>
				</div>
				<div class="box-body">
					<div id="chart_container"></div>
				</div>
			</div>
			
			<!--/Grafik-->
			<!--Peta-->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('program_bansos/view/'.$program_id.'/'.$varKode);?>">Peta Penerima Manfaat: <?php echo $program['nama'];?></a></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-8 col-sm-12 col-xs-12">
							<div id="map_canvas" class="map_canvas"></div>
						</div>
						<div class="col-md-4 col-sm-12 col-xs-12">
							<div id="map_info">
								<div class="box box-primary">
									<div class="box-header with-border"><h3 class="box-title" id="info_title"><?php echo $wilayah; ?></h3></div>
									<div class="box-body">
										<table class="table table-responsive">
											<thead><tr><th>Jenis Kelamin</th>
											<th>&Sigma; Jiwa</th></tr></thead>
											<tbody>
												<tr><td>Perempuan</td><td class="angka"><span id="pere"><?php echo number_format($toPeta['pere'],0);?></span></td></tr>
												<tr><td>Laki-laki</td><td class="angka"><span id="laki"><?php echo number_format($toPeta['laki'],0);?></span></td></tr>
												<tr><td>Jumlah</td><th class="angka"><span id="sum"><?php echo number_format($toPeta['sum'],0);?></span></th></tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							
						</div>
					</div><!--/row-->
				</div>
			</div>
			<!--/Peta-->
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
Highcharts.chart('chart_container', {
    chart: {
        type: 'column'
    },
    title: {
        text: '<?php echo $toGraph['title'];?>'
    },
    xAxis: {
        categories: [<?php echo $toGraph['xAxis'];?>]
    },
    yAxis: {
        min: 0,
        title: {
            text: '<?php echo $toGraph['yAxis'];?>'
        },
        stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
            }
        }
    },
    series: [
    <?php
    $i=1;
    $n = count($toGraph['series']);
    foreach($toGraph['series'] as $key=>$item){
			$strKoma = ($i < $n) ? ",\n":"";
			echo "{
				name: '".$item['nama']."',
				data: [".$item['data']."]
			}".$strKoma;
		}
    ?>
    ]
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
<!-- GoogleMap v3 -->
    <script>
      var map; var infoWindow;
      var tengah = {lat: <?php echo GMAP_LAT; ?>, lng: <?php echo GMAP_LON; ?>};
      
      function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
          zoom: <?php echo GMAP_ZOOM; ?>,
          center: tengah
        });

        // Muat GeoJSON.
        map.data.loadGeoJson(
            '<?php echo site_url('petajson/bansos/'.$program_id.'/'.$varKode); ?>');

        // atur semua berwarna abu2, kecuali disebut isColorful
        
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
						content: "<div id='iw'></div>",
						pixelOffset: new google.maps.Size(0, 20)
				});
        
        // When the user clicks, set 'isColorful', changing the color of the letters.
        map.data.addListener('click', function(event) {
					event.feature.setProperty('isColorful', true);
					var dt_laki = event.feature.getProperty('angkaCo');
					document.getElementById('laki').textContent = dt_laki;
					var dt_pere = event.feature.getProperty('angkaCe');
					document.getElementById('pere').textContent = dt_pere;
					var dt_sum = event.feature.getProperty('angkaSum');
					document.getElementById('sum').textContent = dt_sum;

					var iw = event.feature.getProperty('info_title');
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
