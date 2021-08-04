<?php
/*bdt
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
					<h3 class="box-title"><a href="<?php echo site_url('backend/pbdt/usulanbaru/');?>">Usulan Rumah Tangga Sasaran Baru</a></h3>
					<div class="box-tools pull-right">
                    	<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
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
							<form action="" method="POST" class="formular" role="form">
								<div class="form-group">
									<label class="control-label">Nomor IDBDT</label>
									<input type="text" name=""
								</div>
							</form>
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title"><a href="<?php echo site_url('backend/pbdt/rts_detail/'.$data_rts['idbdt']);?>">Detail RTS <?php echo $data_rts['nama_kepala_rumah_tangga'] ." : ".$data_rts['idbdt'];?></a></h3>
								</div>
								<div class="box-body">
									<dl class="dl-horizontal">
										<dt>No IDBDT</dt>
										<dd><?php echo $data_rts['idbdt'];?></dd>
										<dt>Nama Kepala Rumah Tangga</dt>
										<dd><?php echo $data_rts['nama_kepala_rumah_tangga'];?></dd>
										<dt>Alamat</dt>
										<dd><?php echo $data_rts['alamat'];?></dd>
										<dt>Nilai Percentile</dt>
										<dd><?php echo $data_rts['desil']."/ skor: ".$data_rts['percentile'];?></dd>
									</dl>
									<div>
										<fieldset class="kotak">
											<legend>Anggota RTS</legend>
											<table class="table table-responsive table-bordered">
											<thead><tr><th>#</th><th>Nama</th><th>NIK</th><th>NO KK</th></tr></thead>
											<tbody>
											<?php 
											if(@$data_rts['anggota_rumah_tangga']){
												$nomer = 1;
												foreach($data_rts['anggota_rumah_tangga'] as $key=>$rs){
													echo "<tr><td class='angka'>".$nomer."</td>
													<td><a href='".site_url('backend/pbdt/art_detail/'.$rs['idartbdt'])."'>".$rs['nama']."</a></td>
													<td>".$rs['nik']."</td>
													<td>".$rs['kk_nomor']."</td>
													</tr>";
													$nomer++;
												}
											}
											?>
											</tbody>
											</table>
										</fieldset>
									</div>
									<!-- Data BPNT -->
									<?php 
									if($data_bpnt){
										?>
									<div>
										<fieldset class="kotak">
											<legend>Bantuan Pangan Non Tunai</legend>
											<table class="table table-responsive table-bordered">
											<thead><tr><th>#</th><th>Periode</th><th>Pengurus</th><th>Keterangan</th></tr></thead>
											<tbody>
											<?php 
											
											if(@$data_bpnt){
												$nomer = 1;
												foreach($data_bpnt as $key=>$rs){
													echo "<tr><td class='angka'>".$nomer."</td>
													<td>".$rs['periodeLabel']."</td>
													<td><a href=\"".site_url('backend/pbdt/art_detail/'.$rs['IDARTBDT'])."\">".$rs['Nama_Pengurus']."</a>
														<br />".$rs['BANK'].": ".$rs['NOREKENING']."
														<br />".$rs['NOKARTU']."
														<br />".$rs['Alamat_Pengurus']."
														</td>
													<td>".$rs['kelas']." <br />".$rs['keterangan']."</td>
													</tr>";
													$nomer++;
												}
											}
											
											?>
											</tbody>
											</table>
										</fieldset>
									</div>
										<?php
									}
									?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
						<div class="box box-warning">
								<div class="box-header with-border">
									<h3 class="box-title"><a href="<?php echo site_url('backend/pbdt/rts_detail/'.$data_rts['idbdt']);?>">Alamat RTS <?php echo $data_rts['nama_kepala_rumah_tangga'] ." : ".$data_rts['idbdt'];?></a></h3>
								</div>
								<div class="box-body">
									<dl class="dl-horizontal">
										<dt>Alamat BDT</dt>
										<dd><?php echo $data_rts['alamat'];?></dd>
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
					if($data_rts){
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
								echo "<th>".$d['nama']."</th>";
							}
							echo "</tr>
						</thead>
						<tbody>";
						$nomer=1;
						foreach($indikator as $key=>$rs){
							echo "<tr><td class='angka'>".$nomer."</td>
							<td>".$rs['label']."</td>";
							foreach($periodes['periode'] as $p){
								if(array_key_exists($p['id'],$data_rts['data_bdt'])){
									if($rs['jenis'] == 'pilihan'){
										if(array_key_exists($rs['nama'],$data_rts['data_bdt'][$p['id']])){
											$value = (array_key_exists($data_rts['data_bdt'][$p['id']][$rs['nama']],$rs['opsi'])) ? $rs['opsi'][$data_rts['data_bdt'][$p['id']][$rs['nama']]]['label']:"-";
										}else{
											$value = "";
										}
										echo "<td>".$value."</td>";
									}else{
										echo "<td>".$data_rts['data_bdt'][$p['id']][$rs['nama']]."</td>";
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

<!-- OpenStreetMap -->
<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
<script>
	<?php 
	$varLat = ($data_rts['koordinat_rumah']['latitude'] > 0) ? $data_rts['koordinat_rumah']['latitude']: $app['lat'];
	$varLon = ($data_rts['koordinat_rumah']['longitude']> 0) ? $data_rts['koordinat_rumah']['longitude']: $app['lng'];
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
