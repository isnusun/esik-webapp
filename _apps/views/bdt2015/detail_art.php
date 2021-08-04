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
			<?php echo $app['app_title'];?>
			<small>Basis Data Terpadu <?php echo $user['wilayah_nama']; ?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Basis Data Terpadu</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			$this->load->view('bdt2015/_header');
			?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('backend/bdt2015/art_detail/'.$data_art['id']);?>"><?php echo $data_art['Nama'];?></a></h3>
					<div class="box-tools pull-right">
						<button class="btn btn-warning"><i class="fa fa-clock-o fa-fw"></i>PBDT 2015</button>
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>

				</div>
				<div class="box-body">
					<ol class="breadcrumb">
						<?php 
						if($alamat_bc){
							foreach($alamat_bc as $key=>$rs){
								if(strlen($user['wilayah']) <= strlen($rs['kode'])){
									echo "<li class='nav-item'><a href='".site_url('backend/bdt2015/?kode='.$rs['kode'])."'>".$rs['nama']."</a></li>";
								}else{
									echo "<li class='nav-item'>".$rs['nama']."</li>";
								}
							}
						}
						?>
					</ol>

					<div class="row">
						<div class="col-md-6">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title"><a href="<?php echo site_url('backend/bdt2015/art_detail/'.$data_art['id']);?>">Detail RTS <?php echo $data_art['Nama'];?></a></h3>
								</div>
								<div class="box-body">
									<dl class="dl-horizontal">
										<dt>No IDBDT Rumah Tangga</dt>
										<dd><a href="<?php echo site_url('backend/bdt2015/rts_detail/'.$data_art['Nomor Urut Rumah Tangga']);?>"><?php echo $data_art['Nomor Urut Rumah Tangga'];?></a></dd>
										<dt>IDARTBDT</dt>
										<dd><?php echo $data_art['id'];?></dd>
										<dt>Nama</dt>
										<dd><?php echo $data_art['Nama'];?></dd>
										<dt>Jenis Kelamin</dt>
										<dd><?php echo $data_art['Jenis kelamin'];?></dd>
										<dt>NIK</dt>
										<dd><?php echo $data_art['NIK'];?></dd>
										<dt>Umur Saat Pendataan</dt>
										<dd><?php echo $data_art['Umur saat pendataan'];?></dd>
										<dt>Alamat</dt>
										<dd><?php echo $data_art['Alamat'];?></dd>
										<dt>Desil</dt>
										<dd><?php echo $data_art['Status Kesejahteraan'];?></dd>
									</dl>
									<?php
									if(isset($data_art['foto'])){
										echo "<div><img src='".$data_art['foto']."'/></div>";
									}else{
										echo "<div><img src='/assets/img/nophoto.png' class='thumbnail'/></div>";
									}
									?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
						<div class="box box-warning">
								<div class="box-header with-border">
									<h3 class="box-title"><a href="<?php echo site_url('backend/bdt2015/rts_detail/'.$data_art['Nomor Urut Rumah Tangga']);?>">Data RTS <?php echo $data_art['Nomor Urut Rumah Tangga'];?></a></h3>
								</div>
								<div class="box-body">
									<dl class="dl-horizontal">
										<dt>Alamat BDT</dt>
										<dd><?php echo $data_art['Alamat'];?></dd>
										<dt>Alamat Administratif</dt>
										<dd><ul class='nav flex-column'><?php 
										if($alamat_bc){
											foreach($alamat_bc as $key=>$rs){
												echo "<li class='nav-item'>".$rs['nama']."</li>";
											}
										}
										// echo var_dump($alamat_bc);?></ul></dd>
									</dl>
									<fieldset class='kotak'>
										<legend>Peta Lokasi RTS</legend>
										<div class='google-maps' id="rts_map"></div>
									</fieldset>
								</div>
							</div>						
						</div>
					</div>
					<?php
					if($data_art){
						// $colspan = count($desil) * 2;
						// echo var_dump($indikator);
						echo "
						<h3>Data Berbasis Indikator</h3>
						<table class='table table-responsives table-bordered datatables'>
						<thead>
							<tr><th rowspan='2'>#</th><th rowspan='2'>Indikator</th>
								<th>BDT 2015</th>
							</tr>
						</thead>
						<tbody>";
						$nomer=1;
						foreach($indikator['art'] as $key=>$rs){
							echo "<tr><td class='angka'>".$nomer."</td>
							<td>".$rs['nama']."</td>";
							if(isset($data_art)){
								if($rs['jenis'] == 'pilihan'){
									$value = (array_key_exists($data_art[$rs['nama']],$rs['opsi'])) ? $rs['opsi'][$data_art[$rs['nama']]]:0;
									echo "<td>";
									// echo "<pre>".var_dump($rs['opsi'])."</pre>";
									// $value = 0;
									// $value = (array_key_exists($data_rts['data_bdt'][$rs['nama']],$rs['opsi'])) ? $rs['opsi'][$data_rts['data_bdt'][$p['id']][$rs['nama']]]['label']:"-";
									// $value = $data_rts['data_bdt'][$rs['nama']];
									echo $value."</td>";
								}else{
									echo "<td>".$data_art[$rs['nama']]."</td>";
								}
							}

							echo "
							</tr>";
							$nomer++;
						}
						echo "</tbody>
						</table>";
					}
					?>
				</div>
			</div>

		</section>
	</div>
<!-- footer section -->

<!-- ValidationEngine -->
<script src="<?php echo base_url()?>assets/plugins/validation-engine/jquery.validationEngine.js"></script>
<script src="<?php echo base_url()?>assets/plugins/validation-engine/languages/jquery.validationEngine-id.js"></script>
<!-- Select2 -->
<link href="<?php echo base_url("assets/plugins/"); ?>select2/select2.css" rel="stylesheet" type="text/css"/>
<!-- Select2 -->
<script src="<?php echo base_url("assets/plugins/"); ?>select2/select2.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>select2/select2_locale_id.js"></script>

<script>
	$(document).ready(function() {
		
	});

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAP_KEY;?>"></script>
<script src="<?php echo base_url('assets/'); ?>js/gmaps.js"></script>
<script>

$(function () {
    map();
});

/* map */
<?php 
$varLat = ($data_art['koordinat_rumah']['latitude'] > 0) ? $data_art['koordinat_rumah']['latitude']: $app['lat'];
$varLon = ($data_art['koordinat_rumah']['longitude']> 0) ? $data_art['koordinat_rumah']['longitude']: $app['lng'];
?>
function map() {

    var styles = [{"featureType": "landscape", "stylers": [{"saturation": -100}, {"lightness": 65}, {"visibility": "on"}]}, {"featureType": "poi", "stylers": [{"saturation": -100}, {"lightness": 51}, {"visibility": "simplified"}]}, {"featureType": "road.highway", "stylers": [{"saturation": -100}, {"visibility": "simplified"}]}, {"featureType": "road.arterial", "stylers": [{"saturation": -100}, {"lightness": 30}, {"visibility": "on"}]}, {"featureType": "road.local", "stylers": [{"saturation": -100}, {"lightness": 40}, {"visibility": "on"}]}, {"featureType": "transit", "stylers": [{"saturation": -100}, {"visibility": "simplified"}]}, {"featureType": "administrative.province", "stylers": [{"visibility": "off"}]}, {"featureType": "water", "elementType": "labels", "stylers": [{"visibility": "on"}, {"lightness": -25}, {"saturation": -100}]}, {"featureType": "water", "elementType": "geometry", "stylers": [{"hue": "#ffff00"}, {"lightness": -25}, {"saturation": -97}]}];
    map = new GMaps({
	el: '#rts_map',
	lat: <?php echo $varLat;?>,
	lng: <?php echo $varLon;?>,
	zoomControl: true,
	zoomControlOpt: {
	    style: 'SMALL',
	    position: 'TOP_LEFT'
	},
	panControl: false,
	streetViewControl: false,
	mapTypeControl: false,
	overviewMapControl: false,
	scrollwheel: false,
	draggable: false,
	styles: styles
    });

    var image = '/assets/img/marker.png';

    map.addMarker({
			lat: -7.8138024,
			lng: 110.9223669,
			icon: image,
			title: 'Kantor BAPPEDA dan LITBANG Kab. WONOGIRI',
			infoWindow: {
			content: '<p>Jl. Pemuda 1/26 Wonogiri</p>'
			}
    });
}
</script>
<!-- <script src="<?php echo base_url('assets/'); ?>js/gmaps.init.js"></script> -->

<?php


$this->load->view('siteman/siteman_footer');
