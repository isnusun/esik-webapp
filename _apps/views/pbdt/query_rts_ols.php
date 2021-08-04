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
			$this->load->view('pbdt/_header');
			?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Query Data Rumah Tangga</a></h3>
					<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="<?php echo $collapse?>"><i class="fa fa-minus"></i></button>
                    <div class="btn-group">
						<button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-sliders fa-fw"></i>Pilih Periode Pendataan</button>
						<ul class="dropdown-menu" role="menu">
							<?php 
							foreach($periodes['periode'] as $p){
								echo '<li><a href="'.site_url('backend/pbdt/').'?periode='.$p['id'].'&amp;kode='.$varKode.'">'.$p['nama'].'</a></li>';
							}
							?>
							<li class="divider"></li>
							<li><a href="#">Separated link</a></li>
						</ul>
                    </div>
                    <button class="btn btn-warning"><i class="fa fa-clock-o fa-fw"></i>Periode <?php echo $periodes['periode'][$periode]['nama']; ?></button>
                  </div>

				</div>
				<div class="box-body">
					<ol class="breadcrumb">
						<?php 
						if($alamat_bc){
							foreach($alamat_bc as $key=>$rs){
								if(strlen($user['wilayah']) <= strlen($rs['kode'])){
									echo "<li class='nav-item'><a href='".site_url('backend/pbdt/?kode='.$rs['kode'])."'>".$rs['nama']."</a></li>";
								}else{
									echo "<li class='nav-item'>".$rs['nama']."</li>";
								}
							}
						}
						?>
					</ol>
					<form action="<?php echo site_url('backend/pbdt/query_rts'); ?>" method="POST" role="form">
						<div class="box box-solid box-primary <?php echo $collapse;?>">
							<div class="box-header with-border">
								<h3 class="box-title">Formulir Check Indikator</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div class="row">
								<?php 
								$i = 1;
								foreach($indikator as $key=>$rs){
									echo '
									<div class="col-md-4">
										<div class="box box-default">
											<div class="box-header with-border">'.$rs['label'].'</div>
											<div class="box-body">
												<fieldset class="kotak">
													<legend>'.$rs['nourut'].'. '.$rs['label'].'</legend>
													<div class="form-group">';
													if($rs['jenis'] == 'pilihan'){
														foreach($rs['opsi'] as $op){
															$op_name = $rs['nama'];
															$strC = "";
															echo '
															<div class="checkbox">
															<label><input type="checkbox" name="'.$op_name.'['.$op['nama'].']" value="'.$op['nama'].'" '.$strC.'/> '.$op['label'].'</label>
															</div>';

														}
													}elseif($rs['jenis'] == 'angka'){
														$val_min_name = $rs['nama']."_min";
														$val_max_name = $rs['nama']."_max";
														// $val_min_name = 0;
														// $val_max_name = 0;

														echo '<div>
														<label class="label-control">Nilai Minimal ( >= ) </label>
														<input type="text" class="form-control" name="'.$val_min_name.'" value="'.@$_POST[$val_min_name].'"/>
														<label>Nilai Maksimal  ( <= )</label>
														<input type="text" class="form-control" name="'.$val_max_name.'" value="'.@$_POST[$val_max_name].'"/>
														</div>';
													}else{
														echo "<div>Isian Text</div>";
													}
													echo '</div>
												</fieldset>
											</div>
										</div>							
									</div>';
									if(fmod($i,3)==0){
										echo '</div><div class="row">';
									}
									$i++;
								}
								?>
								</div>
								<div class="box-footer">
									<input type="hidden" name="periode_id" value="<?php echo $periode;?>" />
									<button type="reset" class="btn btn-default pull-right">Reset <i class="fa fa-refresh"></i></button>
									<button type="submit" class="btn btn-primary pull-right">Proses <i class="fa fa-send"></i></button>
								</div>
							</div>
						</div>
					</form>

					<?php
					if($_POST){
						// $colspan = count($desil) * 2;
						// echo var_dump($data_rts);
						// $colspan = count($periodes['periode']);
						echo "
						<h3>Data Berbasis Indikator</h3>
						<fieldset class=\"kotak\">
							<legend>Indikator Terpilih: </legend>
							<div>
							<ol>
							";
							foreach($indikator_aktif as $p){
								$op_name = $p;
								echo "<li><label>".$param_pilihan[$p]['label']."</label>";
								if($param_pilihan[$p]['jenis'] == 'pilihan'){
									foreach($_POST[$op_name] as $nilai){
										echo "<br /><i class=\"fa fa-check fa-fw\"></i>".$param_pilihan[$p]['opsi'][$nilai]['label'];
									}
								}elseif($param_pilihan[$p]['jenis'] == 'angka'){
									$min = $op_name."_min";
									$max = $op_name."_max";
									echo "<br />Nilai antara ".$_POST[$min]." dan ".$_POST[$max];
								}else{
									echo "Text";
		
								}
								echo "</li>";
							}
							echo "
							</ol>
							</div>						
						</fieldset>
						";
						if($data_rts){
							echo "
							<div class='callout callout-success'>
							<h4>Telah ditemukan Data sebanyak <strong>".count($data_rts)."</strong> Rumah Tangga Sasaran</h4>
							</div>";
							echo "							
							<table class='table table-responsives table-bordered datatables'>
							<thead>
								<tr>
									<th>#</th><th>Propinsi</th>
									<th>Kabupaten/Kota</th>
									<th>Kecamatan</th>
									<th>Desa/Kelurahan</th>
									<th>Alamat</th>
									<th>IDBDT</th>
									<th>Nama Kepala Rumah Tangga</th>
									<th>Percentile</th>
								</tr>
							</thead>
							<tbody>";
							$nomer=1;
							foreach($data_rts as $key=>$rs){
								echo "<tr><td class='angka'>".$nomer."</td>
								<td>".$rs['propinsi']."</td>
								<td>".$rs['kabupaten']."</td>
								<td>".$rs['kecamatan']."</td>
								<td>".$rs['desa']."</td>
								<td>".$rs['alamat']."</td>
								<td><a href='".site_url('backend/pbdt/rts_detail/').$rs['idbdt']."'>".$rs['idbdt']."</a></td>
								<td><a href='".site_url('backend/pbdt/rts_detail/').$rs['idbdt']."'>".$rs['nama_krt']."</a></td>
								<td>".$rs['percentile']."</td>
								</tr>";
								$nomer++;
							}
							echo "</tbody>
							</table>";							
						}else{
							echo "
							<div class='callout callout-danger'>
								<h4 class=''>Tidak ditemukan data dengan paramater indikator tersebut diatas</h4>
								<p>Silakan dicoba paramater yg lain</p>
							</div>";

						}


					}
					?>
				</div>
			</div>

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
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAP_KEY;?>"></script>
<script src="<?php echo base_url('assets/'); ?>js/gmaps.js"></script>
<script>

$(function () {
    map();
});

/* map */
<?php 
$varLat = ($data_rts['koordinat_rumah']['latitude'] > 0) ? $data_rts['koordinat_rumah']['latitude']: $app['lat'];
$varLon = ($data_rts['koordinat_rumah']['longitude']> 0) ? $data_rts['koordinat_rumah']['longitude']: $app['lng'];
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
