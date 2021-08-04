<?php $this->load->view('pubs/header');
?>
	<div id="content">
	<div class="container">
		<section class="bar no-mb no-padding-bottom">
			<div class="row">
				<div class="col-md-12">
					<div class="heading">
						<h2>Telusur Data PBDT berbasis Wilayah Administrasi</h2>
						<div class="box-tools pull-right">
							<div class="btn-group">
								<button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-sliders fa-fw"></i>Pilih Periode Pendataan</button>
								<ul class="dropdown-menu" role="menu">
									<?php 
									foreach($periodes['periode'] as $p){
										echo '<li class="dropdown-item"><a href="'.site_url('publik/pbdt_wilayah/').'?periode='.$p['id'].'&amp;kode='.$varKode.'">'.$p['nama'].'</a></li>';
									}
									?>
								</ul>
							</div>
							<button class="btn btn-warning"><i class="fa fa-clock-o fa-fw"></i>Periode <?php echo $periodes['periode'][$periode]['nama']; ?></button>
						</div>

					</div>
					<p class="lead">Distribusi Basis Data Terpadu Berbasis Wilayah Administratif</p>
				</div>
			</div>
		</section>
		<section class="bar pt-0">
			<div class="box box-primary">
				<div class="box-body">
					<ul class="breadcrumb">
						<?php 
						foreach($alamat_bc as $key=>$rs){
							echo "<li class='breadcrumb-item'><a href='".site_url('publik/pbdt_wilayah/'.$rs['kode'])."'>".$rs['nama']."</a></li>";
						}
						?>
					</ul>
					<div class='alert alert-warning alert-dismissible'>
					<button type="button" data-dismiss="alert" class="close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<p>Perhatikan, pada kanan atas kotak ini, kesesuain data yang dikehendaki dengan periode pendataan</p>
					</div>
					<div id="peta" class="row">
						<div class="col-md-6">
							<div id="map_rts" style="min-height:300px;width:100%;"></div>
						</div>
						<div class="col-md-6">
							<div id="map_idv" style="min-height:300px;width:100%;"></div>
						</div>
					</div>
					<h3 class="box-title">Perkembangan Data Kesejahteraan</h3>
					<div class="row">
						<div class="col-md-6">
							<div id="highchart_bdt"></div>
						</div>
						<div class="col-md-6">
							<table class="table table-responsives">
								<thead><tr>
								<td>#</td>
								<td>Periode</td>
								<td>&Sigma; Rumah Tangga</td>
								<td>&Sigma; Anggota Rumah Tangga</td>
								</tr></thead>
								<tbody>
								<?php 
								$nomer=0;
								$tumbuh=array();
								$tumbuh['rts']['nama'] = "Jumlah Rumah Tangga";
								$tumbuh['idv']['nama'] = "Jumlah Anggota Rumah Tangga";
								$t_axis = array();
								foreach($periodes['periode'] as $p){
									if(array_key_exists($p['id'],$bdt['rts'])){
										$nomer++;
										$t_axis[] = $p['nama'];
										$tumbuh['rts']['data'][] =$bdt['rts'][$p['id']];
										$tumbuh['idv']['data'][] =$bdt['idv'][$p['id']];
										echo "<tr><td class=\"angka\">".$nomer."</td>
										<td>".$p['nama']."</td>
										<td class=\"angka\">".number_format($bdt['rts'][$p['id']],0)."</td>
										<td class=\"angka\">".number_format($bdt['idv'][$p['id']],0)."</td>
										</tr>";
									}
								}
								?>
								</tbody>
							</table>
						</div>
					</div>

					<?php

					if($sub_wilayah){
						$colspan = (count($desil) * 2) + 2;
						// echo var_dump($desil);
						echo "
						<h3 class=\"box-title\">Tabulasi Data Per Sub Wilayah</h3>
						<div class='scroll'>
						<table class='table table-striped table-responsives table-bordered nowrap'>
						<thead>
							<tr><th rowspan='3'>#</th><th rowspan='3'>Wilayah</th>
								<th colspan='".$colspan."'>Data Periode ".$periodes['periode'][$periode]['nama']."</th>
							</tr>
							<tr>";
							foreach($desil as $d){
								echo "<th colspan='2'>".$d['nama']."</th>";
							}
							echo "
								<th colspan='2' class=' total'>TOTAL</th>
							</tr>
							<tr>";
							$total['rts'] = 0;
							$total['art'] = 0;
							$sum = array();
							foreach($desil as $d){
								$sum['rts'][$d['id']] = 0;
								$sum['art'][$d['id']] = 0;
								echo "
								<th><i class='fa fa-home'></i></th>
								<th><i class='fa fa-user'></i></th>";
							}
							echo "<th class=' total'><i class='fa fa-home'></i></th>
							<th class=' total'><i class='fa fa-user'></i></th></tr>
						</thead>
						<tbody>";
						$nomer=1;
						$x_axis = array();
						$seri = array();
						$data_seri =array();
						foreach($sub_wilayah as $w){
							$x_axis[] = $tingkat_wilayah[$w['tingkat']]." ".strtoupper(strtolower($w['nama']));
							echo "<tr><td class='angka'>".$nomer."</td>
							<td><a href='".site_url("publik/pbdt_wilayah/".$w['kode'])."')'>".$tingkat_wilayah[$w['tingkat']]." ".strtoupper(strtolower($w['nama']))."</a></td>";
							$subtotal['rts'] = 0;
							$subtotal['art'] = 0;
							foreach($desil as $d){
								$nilai_rts = $data_rts[$w['kode']][$d['id']];
								$nilai_art = $data_art[$w['kode']][$d['id']];
								$sum['rts'][$d['id']] += $nilai_rts;
								$sum['art'][$d['id']] += $nilai_art;
								$subtotal['rts'] += $nilai_rts;
								$subtotal['art'] += $nilai_art;
								
								$data_seri[$d['id']]['rts'][] = $nilai_rts;
								$data_seri[$d['id']]['art'][] = $nilai_art;
		
								echo "<td class='angka'>".number_format($nilai_rts,0)."</td>
								<td class='angka'>".number_format($nilai_art,0)."</td>";
							}
							echo "
							<td class='angka total'>".number_format($subtotal['rts'],0)."</td>
							<td class='angka total'>".number_format($subtotal['art'],0)."</td>
							</tr>";
							$total['rts'] += $subtotal['rts'];
							$total['art'] += $subtotal['art'];

							$nomer++;
						}
						echo "</tbody>
						<tfoot><tr><th>&nbsp;</th><th class='angka'>&Sigma; JUMLAH</th>";
						foreach($desil as $d){
							echo "
							<th class='angka'>".number_format($sum['rts'][$d['id']],0)."</th>
							<th class='angka'>".number_format($sum['art'][$d['id']],0)."</th>";
						}
// 							echo "
							// <th class='angka'><a href='".site_url('backend/pbdt/rts_desil/'.$d['id'].'/')."?kode=".$varKode."'>".number_format($sum['rts'][$d['id']],0)."</a></th>
							// <th class='angka'><a href='".site_url('backend/pbdt/art_desil/'.$d['id'].'/')."?kode=".$varKode."'>".number_format($sum['art'][$d['id']],0)."</a></th>";

						echo "
						<th class='angka total'>".number_format($total['rts'],0)."</th>
						<th class='angka total'>".number_format($total['art'],0)."</th>";

						echo "</tr></tfoot>
						</table>
						</div>
						";
						foreach($desil as $d){
							$seri['rts'][] = array(
								'name'=>"RTS ".$d['nama'],
								'data'=>$data_seri[$d['id']]['rts'],
								'stack'=>'rts'
							);
							$seri['art'][] = array(
								'name'=>'ART '.$d['nama'],
								'data'=>$data_seri[$d['id']]['art'],
								'stack'=>'art'
							);
						}

					}
					?>
					<div class="bar">
						<div id="highchart_graph_rts"></div>
					</div>
					<div class="bar">
						<div id="highchart_graph_art"></div>
					</div>
				</div>
			</div>
	</section>
	</div>
<!-- DataTables CSS -->
<link href="<?php echo base_url("assets/plugins/"); ?>datatables/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- Datatables-->
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/fixedHeader/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/responsive/js/responsive.bootstrap.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>jszip/jszip.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/buttons.colVis.min.js"></script>
<script>
	
$(document).ready(function() {
    $('table.datatables').DataTable({
		paging: false,
		info: false,
		"scrollX": true,
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
    });
});
</script>
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>highcharts.js"></script>
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>modules/exporting.js"></script>
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>modules/offline-exporting.js"></script>
<!-- <script src="<?php echo base_url("assets/plugins/highcharts/"); ?>modules/export-data.js"></script> -->
<!-- <script src="https://code.highcharts.com/modules/export-data.js"> -->
<script>

Highcharts.chart('highchart_bdt', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Pertumbuhan Data'
    },
    subtitle: {
        text: 'Source: PBDT'
    },
    xAxis: {
        categories: <?php echo json_encode($t_axis);?>
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: '<?php echo $tumbuh['rts']['nama'];?>',
        data: <?php echo json_encode($tumbuh['rts']['data'],JSON_NUMERIC_CHECK);?>
    }, {
        name: '<?php echo $tumbuh['idv']['nama'];?>',
        data: <?php echo json_encode($tumbuh['idv']['data'],JSON_NUMERIC_CHECK);?>
    }]
});


Highcharts.chart('highchart_graph_rts', {

chart: {
		type: 'column'
	},
	title: {
		text: 'Jumlah Rumah Tangga Sasaran, Dikelompokkan Berbasis Wilayah dan Desil'
	},
	xAxis: {
		categories: <?php echo json_encode($x_axis);?>
	},
	yAxis: {
		allowDecimals: false,
		min: 0,
		title: {
			text: 'Jumlah Rumah Tangga'
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
	series: <?php echo json_encode($seri['rts'],JSON_NUMERIC_CHECK);?>
});
Highcharts.chart('highchart_graph_art', {

chart: {
		type: 'column'
	},
	title: {
		text: 'Jumlah Penduduk Dikelompokkan Berbasis Wilayah dan Desil'
	},
	xAxis: {
		categories: <?php echo json_encode($x_axis);?>
	},
	yAxis: {
		allowDecimals: false,
		min: 0,
		title: {
			text: 'Jumlah Penduduk'
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
	series: <?php echo json_encode($seri['art'],JSON_NUMERIC_CHECK);?>
});
</script>
<!-- Peta -->
<?php 
$url_geojson = site_url('petajson/json_osm/rts/'.$varKode.'/'.$periode);
$url_geojson_idv = site_url('petajson/json_osm/idv/'.$varKode.'/'.$periode);
$peta = file_get_contents($url_geojson);
if($peta == "FALSE"){

}else{
	?>
	<style>
	.info, .info_idv { padding: 6px 8px; font: 14px/16px Arial, Helvetica, sans-serif; background: white; background: rgba(255,255,255,0.8); box-shadow: 0 0 15px rgba(0,0,0,0.2); border-radius: 5px; } .info h4 { margin: 0 0 5px; color: #777; }
	.legend { text-align: left; line-height: 18px; color: #555; } .legend i { width: 18px; height: 18px; float: left; margin-right: 8px; opacity: 0.7; }</style>
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/2.1.0/chroma.min.js"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.css" />
<!-- 
	<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
 -->
	<script type="text/javascript" src="<?php echo $url_geojson;?>"></script>
	<script type="text/javascript" src="<?php echo $url_geojson_idv;?>"></script>

	<script type="text/javascript">
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	var map = L.map('map_rts').setView([-7.614193, 110.993328], 10);

	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=<?php echo MB_TOKEN;?>', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox/light-v9'
	}).addTo(map);


	// control that shows state info on hover
	var info = L.control();

	info.onAdd = function (map) {
		this._div = L.DomUtil.create('div', 'info');
		this.update();
		return this._div;
	};

	info.update = function (props) {
		this._div.innerHTML = '<h4>Data Sebaran Rumah Tangga</h4>' +  (props ?
			'<b>' + props.name + '</b><br />' + numberWithCommas(props.density) + ' Rumah Tangga'
			: 'Gerakan kursor diatas peta');
	};

	info.addTo(map);

	colors = chroma.scale(['#eeeeff','#0000ff']).mode('lch').colors(<?php echo count($kecamatan);?>);


	function style(feature) {
		return {
			weight: 2,
			opacity: 1,
			color: 'white',
			dashArray: '3',
			fillOpacity: 0.7,
			fillColor: colors[feature.properties.color_id]
		};
	}

	function highlightFeature(e) {
		var layer = e.target;

		layer.setStyle({
			weight: 5,
			color: '#666',
			dashArray: '',
			fillOpacity: 0.7
		});

		if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
			layer.bringToFront();
		}

		info.update(layer.feature.properties);
	}

	var geojson;

	function resetHighlight(e) {
		geojson.resetStyle(e.target);
		info.update();
	}

	function zoomToFeature(e) {
		map.fitBounds(e.target.getBounds());
	}

	function onEachFeature(feature, layer) {
		layer.on({
			mouseover: highlightFeature,
			mouseout: resetHighlight,
			click: zoomToFeature
		});
	}

	geojson = L.geoJson(statesData_rts, {
		style: style,
		onEachFeature: onEachFeature
	}).addTo(map);
	map.fitBounds(geojson.getBounds()); 
	map.attributionControl.addAttribution('Data Kesejahteraan &copy; <a href="<?php echo base_url(); ?>">PBDT <?php echo $periodes['periode'][$periode]['nama'];?></a>');

	var map_idv = L.map('map_idv').setView([-7.614193, 110.993328], 10);

	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=<?php echo MB_TOKEN;?>', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox/light-v9'
	}).addTo(map_idv);


	// control that shows state info on hover
	var info_idv = L.control();

	info_idv.onAdd = function (map_idv) {
		this._div = L.DomUtil.create('div', 'info_idv');
		this.update();
		return this._div;
	};

	info_idv.update = function (props) {
		this._div.innerHTML = '<h4>Data Sebaran Anggota Rumah Tangga</h4>' +  (props ?
			'<b>' + props.name + '</b><br />' + numberWithCommas(props.density) + ' Jiwa'
			: 'Gerakan kursor diatas peta');
	};

	info_idv.addTo(map_idv);
	function highlightFeatureIdv(e) {
		var layer = e.target;

		layer.setStyle({
			weight: 5,
			color: '#666',
			dashArray: '',
			fillOpacity: 0.7
		});

		if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
			layer.bringToFront();
		}

		info_idv.update(layer.feature.properties);
	}
	var geojson_idv;

	function resetHighlightIdv(e) {
		geojson_idv.resetStyle(e.target);
		info_idv.update();
	}

	function zoomToFeatureIdv(e) {
		map_idv.fitBounds(e.target.getBounds());
	}

	function onEachFeatureIdv(feature, layer) {
		layer.on({
			mouseover: highlightFeatureIdv,
			mouseout: resetHighlightIdv,
			click: zoomToFeatureIdv
		});
	}		

	geojson_idv = L.geoJson(statesData_idv, {
		style: style,
		onEachFeature: onEachFeatureIdv
	}).addTo(map_idv);
	map_idv.fitBounds(geojson.getBounds()); 
	map_idv.attributionControl.addAttribution('Data Kesejahteraan &copy; <a href="<?php echo base_url(); ?>">PBDT <?php echo $periodes['periode'][$periode]['nama'];?></a>');	
	</script>
<?php 
}
