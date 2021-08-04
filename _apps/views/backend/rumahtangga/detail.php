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
			<?php echo $pageTitle;?>
			<small>Detail Rumahtangga Sasaran</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Detail Rumahtangga Sasaran</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-9 col-sm-6 col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><a href="<?php echo site_url('backend/rumahtangga');?>"><?php echo $boxTitle;?></a></h3>
						</div>
						<div class="box-body">
							<?php 
							// echo var_dump($rts);
							if(isset($_SESSION['strMsg'])){
								echo "
								<div class=\"alert alert-success alert-dismissable\">
									<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
									<h4><i class=\"fa fa-check\"></i> ".$_SESSION['strMsg']['msg']."</h4>
								</div>";
								$_SESSION['strMsg']="";
							}
							echo "
							<div class=\"row mb-3\">
								<div class=\"col-md-6\">
									<dl class=\"dl-horizontal\">
										<dt>NO IDBDT:</dt>
										<dd>".$rts['rumahtangga']['idbdt']."</dd>
										<dt>Kepala Rumah Tangga:</dt>
										<dd>".$rts['rumahtangga']['nama_kepala_rumah_tangga']."</dd>
										<dt>Alamat:</dt>";
										if($alamat_bc){
											foreach ($alamat_bc as $key => $value) {
												# code...
												echo "<dt></dt><dd>".$value['nama']."</dd>";
											}
										}else{
											echo "<dd class=\"alert alert-warning\">Data Alamat Tidak Valid, Silakan dibenahi</dd>";
										}
										echo "
										<dt>Jumlah Keluarga</dt><dd>".count($rts['kartu_keluarga'])."</dd>
										<dt>Jumlah Anggota</dt><dd>".count($rts['anggota'])."</dd>
									</dl>
									<div class=\"btn-group\">
										<button class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#modalAlamatRTS\"><i class=\"fa fa-fw fa-map-marker\"></i>Perbaikan Alamat</button>
										<a href=\"".site_url('backend/verval/form_rts/'.$varID)."\" class=\"btn btn-primary\"><i class=\"fa fa-fw fa-check\"></i>Update Data BDT</a>
									</div>
									
								</div>
								<div class=\"col-md-6\">
									<fieldset class='kotak'>
										<legend>Peta Lokasi RTS</legend>
										<div class='google-maps' id='rts_map'></div>
									</fieldset>

								</div>
							</div>
							<fieldset class=\"kotak\">
								<legend>Kartu Keluarga</legend>
								<div>
								<table class=\"table table-bordered\">
								<thead><tr><th>#</th>
									<th>NO Kartu Keluarga</th>
									<th>Status KK</th>
									<th>Tindakan</th>
								</tr></thead>
								<tbody>";
								// echo var_dump($rts['kartu_keluarga']);
								if(count($rts['kartu_keluarga']) > 0){
									$nomer=1;
									foreach($rts['kartu_keluarga'] as $key=>$kk){
										$utama = ($kk['utama']==1)? "<button class=\"btn btn-xs btn-success\"><i class=\"fa fa-fw fa-check\"></i>Utama</button>":"<button class=\"btn btn-xs btn-default\"><i class=\"fa fa-fw fa-check\"></i>Penumpang</button>";
										$todo = ($kk['utama']==1) ? "":"<a href=\"".site_url('backend/rumahtangga/set_kkutama/'.$varID.'/'.$key)."\" class=\"btn btn-primary btn-xs\">Atur sbg KK Utama</a>";
										echo "<tr><td class=\"angka\">".$nomer."</td>
											<td>".$kk['kk_nomor']."</td>
											<td>".$utama."</td>
											<td><div class=\"btn-group\">
												<a class=\"btn btn-danger btn-xs\" href=\"".site_url('backend/rumahtangga/hapus_kk/'.$varID.'/'.$key)."\"><i class=\"fa fa-trash\"></i>Hapus KK</a>
												".$todo."
											</div>
											</td>
										</tr>";
										$nomer++;
									}
								}
								echo "
								</tbody>
								</table>
									</div>
							</fieldset>
							<fieldset class=\"kotak\">
								<legend>Anggota Rumah Tangga</legend>
								<div>
								<table class=\"table table-bordered\">
								<thead><tr><th>#</th>
									<th>Nama</th>
									<th>NIK</th>
									<th>Nomor PESERTA PBDT ART</th>
									<th>Data BDT</th>
								</tr></thead>
								<tbody>";
								// echo var_dump($rts['anggota']);
								if(count($rts['anggota']) > 0){
									$nomer=1;
									foreach($rts['anggota'] as $key=>$art){
										echo "<tr><td class=\"angka\">".$nomer."</td>
											<td><a href=\"".site_url('backend/pbdt/art_detail/'.$art['nopesertapbdtart'])."\">".$art['nama']."</a></td>
											<td>".$art['nik']."</td>
											<td>".$art['nopesertapbdtart']."</td>
											<td><a href=\"".site_url('backend/pbdt/art_detail/'.$art['nopesertapbdtart'])."\"><i class=\"fa fa-th-list\"></i></a></td>
											</tr>";
										$nomer++;
									}
								}
								echo "
								</tbody>
								</table>
									</div>
							</fieldset>";
							?>
							<fieldset class="kotak">
								<legend>Data BDT</legend>
								<div>
									<table class="table table-responsive table-bordered">
										<thead><tr><th>#</th><th>Periode Pendataan</th><th>Data</th>
											<th>Keterangan</th>
										</tr></thead>
										<tbody>
											<?php 
											// echo var_dump($rts_bdt);
											$nomer=1;
											foreach($bdt_periode['periode'] as $p=>$rs){
												$survey = ($rs['status']==2)? "<button class=\"btn btn-xs btn-success\"><i class=\"fa fa-fw fa-clock-o\"></i> Survey</button>":"<button class=\"btn btn-xs btn-default\"><i class=\"fa fa-fw fa-check-circle\"></i> Arsip</button>";
												$data = (array_key_exists($rs['id'],$rts_bdt)) ? "<a target=\"_blank\" class=\"btn btn-default btn-xs\" href=\"".site_url('backend/pbdt/rts_detail/'.$varID.'/'.$rs['id'])."\">Data BDT (".$rts_bdt[$rs['id']]['percentile'].")</a>":"tidak/belum ada data";
												echo "<tr><td class=\"angka\">".$nomer."</td>
													<td>".$rs['nama']."</td>
													<td>".$data."</td>
													<td>".$survey."</td>
												</tr>";
												$nomer++;
											}
											?>
										</tbody>
									</table>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Pengelolaan Data Rumah Tangga</h3>
						</div>
						<div class="box-body">
							<table class="table">
								<thead><tr>
									<th>Icon</th>
									<th>Keterangan</th>
								</tr></thead>
								<tbody>
									<tr><td><i class="fa fa-pencil"></i></td>
										<td>Link/tautan untuk mengubah data</td></tr>
									<tr><td><i class="fa fa-trash"></i></td>
										<td>Link/tautan menghapus data pada baris bersangkutan.</td></tr>
								</tbody>
							</table>							
						</div>
					</div>

				</div>
			</div>
		</section>
	</div>
<!-- footer section -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Konfirmasi Penghapusan</h4>
                </div>
                <div class="modal-body">
                    <p>Anda hendak menghapus data <b><i class="title"></i></b>, tidak bisa di-kembali-kan.</p>
                    <p>Lanjut?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-ok">Hapus</button>
                </div>
            </div>
        </div>
    </div>

<?php
/*
 * 
 *             // $.ajax({url: '/api/record/' + id, type: 'DELETE'})
            // $.post('/api/record/' + id).then()

 * */
?>

<script>
	$('#confirm-delete').on('click', '.btn-ok', function(e) {
		var $modalDiv = $(e.delegateTarget);
		var id = $(this).data('recordId');
		var strUrl = '<?php echo site_url('admin/pengguna/');?>' + id +'/hapus';
		window.location.replace(strUrl);
		/*
		$.ajax({url: strUrl, type: 'DELETE'})
		*/
		$modalDiv.addClass('loading');
		setTimeout(function() {
				$modalDiv.modal('hide').removeClass('loading');
		}, 1000)
			
	});
	$('#confirm-delete').on('show.bs.modal', function(e) {
		var data = $(e.relatedTarget).data();
		$('.title', this).text(data.recordTitle);
		$('.btn-ok', this).data('recordId', data.recordId);
	});
</script>
    
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
		"pageLength": 25,
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
<!-- OpenStreetMap -->
<script src="//cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
<link rel="stylesheet" href="//cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
<script>
	<?php 
	$varLat = ($rts['rumahtangga']['var_lat'] > 0) ? $rts['rumahtangga']['var_lat']: $app['lat'];
	$varLon = ($rts['rumahtangga']['var_lon']> 0) ? $rts['rumahtangga']['var_lon']: $app['lng'];
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
