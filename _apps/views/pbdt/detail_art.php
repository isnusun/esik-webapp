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
					<h3 class="box-title"><a href="<?php echo site_url('backend/pbdt/art_detail/'.$data_art['idbdt']);?>"><?php echo $data_art['nama'] ." : ".$data_art['idbdt'];?></a></h3>
					<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
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

					<div class="row">
						<div class="col-md-6">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title"><a href="<?php echo site_url('backend/pbdt/art_detail/'.$data_art['idartbdt']);?>"><?php echo $data_art['nama'] ." : ".$data_art['idartbdt'];?></a></h3>
								</div>
								<div class="box-body">
									<dl class="dl-horizontal">
										<dt>No IDBDT Rumah Tangga</dt>
										<dd><a href="<?php echo site_url('backend/pbdt/rts_detail/'.$data_art['idbdt']);?>"><?php echo $data_art['idbdt'];?></a></dd>
										<dt>IDARTBDT</dt>
										<dd><?php echo $data_art['idartbdt'];?></dd>
										<dt>Nama</dt>
										<dd><?php echo $data_art['nama'];?></dd>
										<dt>NIK</dt>
										<dd><?php echo $data_art['nik'];?></dd>
										<dt>Tempat/Tanggal Lahir</dt>
										<dd><?php echo $data_art['tmplahir'] ."/ ".$data_art['tgllahir'];?></dd>
										<dt>Alamat</dt>
										<dd><?php echo $data_art['alamat'];?></dd>
										<dt>Nilai Percentile</dt>
										<dd><?php echo $data_art['desil']."/ skor: ".$data_art['percentile'];?></dd>
									</dl>
									<?php
									if($data_art['foto']){
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
									<h3 class="box-title"><a href="<?php echo site_url('backend/pbdt/rts_detail/'.$data_art['idbdt']);?>">Data RTS <?php echo $data_art['nama_kepala_rumah_tangga'] ." : ".$data_art['idbdt'];?></a></h3>
								</div>
								<div class="box-body">
									<dl class="dl-horizontal">
										<dt>Alamat BDT</dt>
										<dd><?php echo $data_art['alamat'];?></dd>
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
						// echo var_dump($app);
						$colspan = count($periodes['periode']);
						echo "
						<h3>Data Berbasis Indikator</h3>
						<table class='table table-responsives table-bordered datatables'>
						<thead>
							<tr><th rowspan='2'>#</th><th rowspan='2'>Indikator</th>
								<th colspan='".$colspan."'>Periode Pendataan / BDT</th>
							</tr>
							<tr>";
							foreach($periodes['periode'] as $d){
								// if($d['status'])
								$caption = ($d['status']==2) ? "<button class=\"btn btn-xs btn-success\">Survey</button>":"<button class=\"btn btn-xs btn-default\">Arsip</button>";
								echo "<th>".$d['nama']."<br />".$caption."</th>";
							}
							echo "</tr>
						</thead>
						<tbody>";
						$nomer=1;
						foreach($indikator as $key=>$rs){
							echo "<tr><td class='angka'>".$nomer."</td>
							<td>".$rs['label']."</td>";
							foreach($periodes['periode'] as $p){
								if(array_key_exists($p['id'],$data_art['data_bdt'])){
									if($rs['jenis'] == 'pilihan'){
										$value = (array_key_exists($data_art['data_bdt'][$p['id']][$rs['nama']],$rs['opsi'])) ? $rs['opsi'][$data_art['data_bdt'][$p['id']][$rs['nama']]]['label']:"-";
										echo "<td>".$value."</td>";
									}else{
										echo "<td>".$data_art['data_bdt'][$p['id']][$rs['nama']]."</td>";
									}
								}else{
									echo "<td>-</td>";
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
<!-- OpenStreetMap -->
<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
<script>
	<?php 
	$varLat = ($data_art['koordinat_rumah']['latitude'] > 0) ? $data_art['koordinat_rumah']['latitude']: $app['lat'];
	$varLon = ($data_art['koordinat_rumah']['longitude']> 0) ? $data_art['koordinat_rumah']['longitude']: $app['lng'];
	?>

	// initialize Leaflet
	var map = L.map('rts_map').setView({lon: <?php echo $varLon;?>, lat: <?php echo $varLat;?>}, 14);

	// add the OpenStreetMap tiles
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 18,
		attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
	}).addTo(map);

	// show the scale bar on the lower left corner
	L.control.scale().addTo(map);

	// show a marker on the map
	L.marker({lon: <?php echo $varLon;?>, lat: <?php echo $varLat;?>}).bindPopup('The center of the world').addTo(map);
	</script>
<?php


$this->load->view('siteman/siteman_footer');
