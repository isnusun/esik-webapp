<?php $this->load->view('pubs/header');
?>
      <div id="content">
        <div class="container">
          <section class="bar">
            <div class="row">
              <div class="col-md-12">
                <div class="heading">
                  <h2><?php echo $app['app_title'];?></h2>
                </div>
                <p class="lead">Sebaran Data Rumah Tangga Sasaran Basis Data Terpadu</p>

                <div id="peta" class="row">
					<div class="col-md-6">
						<div id="map_rts" style="min-height:300px;width:100%;"></div>
					</div>
					<div class="col-md-6">
						<div id="map_idv" style="min-height:300px;width:100%;"></div>
					</div>
                </div>
              </div>
            </div>
          </section>
        </div>
				<section class="bar background-pentagon no-mb">
          <div class="container">
            <div class="row showcase text-center">
              <div class="col-md-3 col-sm-6">
                <div class="item">
                  <div class="icon-outlined icon-sm icon-thin"><i class="fa fa-home"></i></div>
                  <h4><span class="h1 counter"><?php echo number_format($angka['rts'],0);?></span><br> Rumah Tangga Sasaran</h4>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="item">
                  <div class="icon-outlined icon-sm icon-thin"><i class="fa fa-users"></i></div>
                  <h4><span class="h1 counter"><?php echo number_format($angka['art'],0);?></span><br>Anggota Rumah Tangga</h4>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="item">
                  <div class="icon-outlined icon-sm icon-thin"><i class="fa fa-map"></i></div>
                  <h4><span class="h1 counter"><?php echo count($desa);?></span><br>Kecamatan</h4>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="item">
                  <div class="icon-outlined icon-sm icon-thin"><i class="fa fa-font"></i></div>
                  <h4><span class="h1 counter"><?php echo $num_desa;?></span><br>Desa dan Kelurahan</h4>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section class="bar bg-gray">
          <div class="container">
            <div class="heading text-center">
              <h2>WILAYAH ADMINISTRASI</h2>
            </div>
						<div class="row">
						<?php 
						// echo var_dump($sites);
						$i=0;
						foreach($kecamatan as $kec){
							$i++;
							echo "
							<div class=\"col-md-3 col-sm-6\">
								<h4><a href='".site_url('publik/pbdt_wilayah/'.$kec['kode'])."'>Kec. ".$kec['nama']."</a></h4>";
								if(count($desa[$kec['kode']]) > 0){
									echo "<ul class=\"nav flex-column\">";
									foreach($desa[$kec['kode']] as $ds){
										if($ds['sites']){
											echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"".site_url('publik/pbdt_wilayah/'.$ds['kode'])."\" target=\"_blank\">".$ds['nama']."</a></li>";
										}else{
											echo "<li class=\"nav-item\"><a href='".site_url('publik/pbdt_wilayah/'.$ds['kode'])."'>".$ds['nama']."</a></li>";
										}
									}
									echo "</ul>";
								}
								echo "
							</div>";
							if(fmod($i,4) == 0){
								echo "</div><div class=\"row\">";
							}
						}
						?>
						</div>
          </div>
        </section>
      </div>
      <!-- GET IT-->
      <div class="get-it">
        <div class="container">
          <div class="row">
            <div class="col-lg-8 text-center p-3">
              <h3>Anda memiliki ide, saran, kritik dan masukan untuk kami?</h3>
            </div>
            <div class="col-lg-4 text-center p-3">   <a href="<?php echo site_url('beranda/hubungikami');?>" class="btn btn-template-outlined-white">Sampaikan melalui formulir berikut ini</a></div>
          </div>
        </div>
      </div>
<!-- Peta -->
<style>
.info, .info_idv { padding: 6px 8px; font: 14px/16px Arial, Helvetica, sans-serif; background: white; background: rgba(255,255,255,0.8); box-shadow: 0 0 15px rgba(0,0,0,0.2); border-radius: 5px; } .info h4 { margin: 0 0 5px; color: #777; }
.legend { text-align: left; line-height: 18px; color: #555; } .legend i { width: 18px; height: 18px; float: left; margin-right: 8px; opacity: 0.7; }</style>
</style>
<script src="//cdnjs.cloudflare.com/ajax/libs/chroma-js/2.1.0/chroma.min.js"></script>
<script src="//cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
<link rel="stylesheet" href="//cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />

<script type="text/javascript" src="<?php echo site_url('petajson/json_osm/rts/'.$varKode.'/'.$periode);?>"></script>
<script type="text/javascript" src="<?php echo site_url('petajson/json_osm/idv/'.$varKode.'/'.$periode);?>"></script>
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

	colors = chroma.scale(['#eeeeff','#02022b']).mode('lch').colors(<?php echo count($kecamatan);?>);


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
$this->load->view('pubs/footer');