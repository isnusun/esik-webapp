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
$periode = $periodes["periode"];
?>
 <!-- Content Wrapper. Contains page content -->

	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>Verifikasi dan Validasi Data <strong class="text-red">[<?php echo strtoupper($periode[$periode_q]['nama']);?>]</strong>
				<small><?php echo $app['title'];?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">VeriVali Data</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			$rtm = $data_rtm['rts'];
			// echo var_dump($rtm);
			$pesan = (@$_SESSION['strMsg']) ? $_SESSION['strMsg']: false;
			if($pesan){
				if(is_array($pesan)){
					if($pesan['status']){
						echo "
						<div class=\"alert alert-success alert-dismissible\">
							<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
							<h4><i class=\"icon fa fa-check\"></i> Berhasil!</h4>
							".$pesan['msg']."
						</div>
						";
					}else{
						echo "
						<div class=\"alert alert-danger alert-dismissible\">
							<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
							<h4><i class=\"icon fa fa-ban\"></i> Ada Kesalahan!</h4>
							".$pesan['msg']."
						</div>
						";
					}
				}
				$_SESSION['strMsg'] = "";
			}
			
			?>


			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo $boxTitle;?></a></h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="box box-danger box-solid collapsed-box">
						<div class="box-header">
							<h3 class="box-title"><i class="fa fa-question-circle	"></i> Panduan dan Perhatian</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
							</div>
						
						</div>
						<div class="box-body">
							<p>Dalam melakukan entri data verifikasi dan validasi ini, tahapan-tahapan harus dilakukan secara urut dan runut, yaitu mulai dari</p>
							<ol>
								<li>Memastikan data domisili Rumah Tangga Sasaran Secara lengkap/detail. yaitu di box </li>
								<li>Memastikan data anggota rumah tangga/kartu keluarga sudah sinkron dengan data DUKCAPIL
								<br />Pastikan ada tanda <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button> 
								atau <button class="btn btn-danger btn-xs"><i class="fa fa-minus"></i></button> di kolom <a href="#stat_di_capil"><strong>Status di CAPIL</strong></a> baris nama yang bersangkutan. 
								<br />Bila tombol pada kolom tersebut adalah masih berupa <button class="btn btn-default btn-xs"><i class="fa fa-minus"></i></button>, 
								maka silakan klik pada tombol <button class="btn btn-default btn-xs"><i class="fa fa-refresh"></i></button> untuk mensinkronkan data dengan data DUKCAPIL</li>
								<li>Peta lokasi Domisili Rumah Tangga Sasaran, hanya bila anda mengenalnya dengan pasti. Cara menggunakan peta, 
								gunakan skrol pada mouse atau tombol + dan - untuk memperbesar/memperkecil skala peta. Klik pada titik dimana kira-kira posisi rumah berada. hingga muncul balon <img src="<?php echo base_url('assets/img/map-marker-16.png'); ?>" /></li>
								<li>Isikan data Formulir Verifikasi dan Validasi Data</li>
							</ol>
						</div>
					</div>
					<div class="row">
					<div class="col-md-6 col-sm-12 col-xs-12">
							<!-- form #1-->
							<div class="box box-primary box-solid">
								<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-home fa-fw"></i> Detail Rumah Tangga Sasaran</h3></div>
								<div class="box-body">
									<?php 
									echo var_dump($data_rtm);
									?>
									
								</div>
							</div>
							<!-- form #1-->
							
						</div>
						<div class="col-md-6 col-sm-12 col-xs-12">
							<form action="#" method="POST" class="formular" id="form_rts_dom">
							<div class="box box-primary box-solid">
								<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-map-marker fa-fw"></i> Domisili RTS</h3></div>
								<div class="box-body">
									<p class="help-block">Bila ada perubahan data domisili RTS, jangan lupa klik tombol Simpan Domisili RTS</p>
									<!-- <p class="help-block">Bila ada alamat RW atau RT tidak/belum ada di daftar pilihan, silakan <a href="<?php echo site_url('admin/wilayah_administrasi')?>">tambahkan melalui formulir berikut ini</a></p> -->
									<div class="form-group">
										<label class="label-control">Kecamatan</label>
										<div class="" id="">
											<select id="kec_id" class="form-control rts_dom" name="rts_kecamatan" placeholder="Kecamatan ...">
												<?php 
												foreach($kecamatan as $key=>$item){
													$strS = ($key==substr($rtm['kode_wilayah'],0,7)) ? "selected=\"selected\"":"";
													echo "<option value=\"".$key."\" ".$strS.">Kecamatan ".$item['nama']."</option>";
												}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="label-control">Desa/Kelurahan</label>
										<div class="" id="">
											<select id="kel_id" class="form-control rts_dom" name="rts_kelurahan" placeholder="Kelurahan ...">
												<option id="">Desa/Kelurahan ...</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="label-control">Dusun/Lingkungan</label>
										<div class="" id="">
											<select id="dusun_id" class="form-control rts_dom" name="rts_dusun" placeholder="Dusun/Lingkungan ...">
												<option id="">Dusun/Lingkungan ...</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="label-control">RW</label>
										<div class="" id="">
											<select id="rw_id" class="form-control rts_dom" name="rts_rw" placeholder="RW ...">
												<option id="">RW ...</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="label-control">RT</label>
										<div class="" id="">
											<select id="rt_id" class="form-control rts_dom" name="rts_rt" placeholder="RT ...">
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="label-control">Alamat</label>
										<div id="">
											<textarea class="form-control rts_dom" id="rts_alamat" name="rts_alamat"><?php echo $rtm['alamat']; ?></textarea>
										</div>
									</div>
									
								</div><!--/box-body -->
								<div class="box-footer">
									<input type="hidden" name="rts_no_rtm" value="<?php echo $rtm['idbdt']; ?>"/>
									<input type="hidden" name="rts_id_rtm" value="<?php echo $rtm['id']; ?>"/>
									<button type="submit" id="save_rts_dom" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Domilisi RTS</button>
								</div>
							</div>
							
							</form> <!-- form domisili-->

						</div>
							
					</div>
				</div>
			</div>
				<?php
				if($data_rtm['anggota']){
					if(count($data_rtm['anggota']) > 0){
						
						?>
						<div class="box box-primary box-solid" id="rts_club">
							<div class="box-header with-border">
								<h3 class="box-title" id="list_art"><i class="fa fa-users"></i> Anggota Rumah Tangga</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
									</button>
								</div>
							
							</div>
							<div class="box-body">
            
								<div class="overlay hidden" id="panel2capil">
									<i class="fa fa-refresh fa-spin"></i>
								</div>
            								
								<table class="table table-responsive table-bordered">
									<thead><tr>
										<th>#</th>
										<th style="min-width:120px;">Status KK</th>
										<th style="min-width:120px;">Status ART</th>
										<th>Status di Capil</th>
										<th>Nama</th>
										<th>NIK<br />NO KK</th>
										<th>Jenis Kelamin</th>
										<th>Hubungan RTM</th>
										<th>Hubungan KK</th>
										<th>Tgl Lahir<br />(Umur)</th>
									</tr></thead>
									<tbody>
										<?php

										$nomer =1;
										foreach($rtm['anggota'] as $a=>$o){
											
											$sex = ($o['sex']==1)? "L":"P";
											$penyebut = $indi_stat['kks'];
											
											if($o['rts_kk_utama'] == 1){
												$rts_btn = 'KK Utama';
												$pembilang = $o['kk_data'];
												$penyebut = $indi_stat['rts'];
												if($pembilang >= $penyebut){
													$pembilang = $penyebut;
												}
											}else{
												$rts_btn = 'Anggota RTS';
												if($o['kk_data'] >= $indi_stat['kks']){
													$o['kk_data'] = $indi_stat['kks'];
												}
												$pembilang = $o['kk_data'];
											}
											

											if($pembilang >=$penyebut){
												$btn_kks = "btn-success";
												$btn_kk = "<button type=\"button\" class=\"btn btn-xs btn-success\"><i class=\"fa fa-check\"></i> ".$pembilang."/".$penyebut."</button>";
											}else{
												$btn_kks = "btn-warning";
												$btn_kk = "<button type=\"button\" class=\"btn btn-xs btn-default\">".$pembilang."/".$penyebut."</button>";
											}
											
											
											
											if($o['idv_data'] == $indi_stat['idv']){
												$btn_art = "btn-success";
												$btn_idv = "<button type=\"button\" class=\"btn btn-xs btn-success\"><i class=\"fa fa-check\"></i></button>";
											}else{
												$btn_art = "btn-warning";
												$btn_idv = "<button type=\"button\" class=\"btn btn-xs btn-default\">".$o['idv_data']."/".$indi_stat['idv']."</button>";
											}
											
											
											$stat_capil = _capil_status($o['capil_stat'],$o['capil_desc'],$o['capil_at']);
											
											if(($o['capil_stat'] == 1) || ($o['capil_stat'] == 2)){
												$strClass = "";
											}else{
												$strClass = "class=\"coret\"";
											}
											

											echo "
											<tr id=\"kks_".$o['nokk']."\" ".$strClass.">
											<td class=\"angka\">".$nomer."</td>
											<td>";

											if(($o['capil_stat'] == 1)){
												
												if ($o['kk_hubungan'] == 1) {
													
													if($o['kk_id'] == NULL){
														$kk_id = 0;
														$strCap = 'Tuliskan data KK';
													}else{
														$kk_id = $o['kk_id'];
														$strCap = 'Edit data KK';
													}
													echo "
													<a href=\"".site_url('verval/form_kks/'.$o['nokk'])."/".$o['id']."/".$periode_q."\" title=\"".$strCap."\">
														<div class=\"btn-group\">
															<span class=\"btn btn-xs ".$btn_kks."\" ><i class=\"fa fa-th-large\"></i> Data KK</span>
															".$btn_kk."
														</div>
													</a>
													";
												}else{
													if($o['rt_hubungan']==1){
														echo "
														<a href=\"".site_url('verval/form_kks/'.$o['nokk'])."/".$o['id']."/".$periode_q."\">
														<div class=\"btn-group\">
															<span class=\"btn btn-xs ".$btn_kks."\" ><i class=\"fa fa-th-large\"></i> Data KK</span>
															".$btn_kk."
														</div>
														";
													}
												}
											}
											
											echo "
												</td>
												<td>";
												if(($o['capil_stat'] == 1) || ($o['capil_stat'] == 2)){
													echo "
													<a href=\"".site_url('verval/form_art/'.$o['id'])."/".$periode_q."\">
													<div class=\"btn-group\">
														<span class=\"btn btn-xs ".$btn_art."\" ><i class=\"fa fa-user\"></i> ART</span>
														".$btn_idv."
													</div>";
												}
												
												if($o['serumah'] == 1){
													echo "
													<div>
													<a href=\"#\" class=\"btn btn-default btn-xs\" data-toggle=\"modal\" data-target=\"#modal_art_usaha_".$o['pnik']."\"><i class=\"fa fa-home\"></i> serumah</a>
													</div>";
												}else{
													echo "
													<div>
													<a href=\"#\" class=\"btn btn-warning btn-xs\" data-toggle=\"modal\" data-target=\"#modal_art_usaha_".$o['pnik']."\"><i class=\"fa fa-home\"></i> tdk serumah</a>
													</div>";
												}
													echo "
													<!-- Modal HTML -->
													<div id=\"modal_art_usaha_".$o['pnik']."\" class=\"modal fade\">
														<form action=\"".site_url('verval/simpan_idv_rumah')."\" method=\"POST\" class=\"formular\">
														<div class=\"modal-dialog\">
															<div class=\"modal-content\">
															
																	<div class=\"modal-header\">
																		<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
																		<h4 class=\"modal-title\">Data Tanggungan Tidak Serumah: <strong>".$o['pnama']."</strong></h4>
																	</div>
																	<div class=\"modal-body\">
																		<div class=\"form-group\">
																			<label class=\"control-label\">Serumah/Tidak?</label>
																			<select name=\"pdd[serumah]\" class=\"form-control\">";
																				$strS = ($o['serumah']==1) ? "selected=\"selected\"":"";
																				echo "<option value=\"1\" ".$strS.">Serumah</option>";
																				$strS = ($o['serumah']!=1) ? "selected=\"selected\"":"";
																				echo "<option value=\"0\" ".$strS.">Tidak Serumah</option>";
																			echo "</select>
																		</div>
																		<div class=\"form-group\">
																			<label class=\"control-label\">Alamat Tinggal Sekarang</label>
																			<textarea name=\"pdd[alamat]\" class=\"form-control\" id=\"pdd_alamat\">".$o['serumah_alamat']."</textarea>
																		</div>
																		<div class=\"form-group\">
																			<label class=\"control-label\">Nama Sekolah</label>
																			<textarea name=\"pdd[sekolah]\" class=\"form-control\" id=\"pdd_alamat\">".$o['serumah_sekolah']."</textarea>
																		</div>
																		<div class=\"form-group\">
																			<label class=\"control-label\">NISN/No KTM</label>
																			<textarea name=\"pdd[nisn]\" class=\"form-control\" id=\"pdd_alamat\">".$o['serumah_nisn']."</textarea>
																		</div>
																	</div>
																	<div class=\"modal-footer\">
																		<input type=\"hidden\" name=\"pdd[id]\" value=\"".$o['id']."\"/>
																		<input type=\"hidden\" name=\"pdd[rtm]\" value=\"".$rtm['rtm_no']."\"/>
																		<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
																		<button type=\"submit\" class=\"btn btn-primary\">Save changes</button>
																	</div>
															</div>
															</form>
														</div>
													</div>													
													
												</td>
												<td>
												
													<div class=\"btn-group\">
														".$stat_capil."
														<button type=\"button\" class=\"btn btn-xs btn-default dropdown-toggle\" data-toggle=\"dropdown\">
															<span class=\"caret\"></span>
															<span class=\"sr-only\">Toggle Dropdown</span>
														</button>
														<ul class=\"dropdown-menu\" role=\"menu\">
															<li><a href=\"".site_url('verval/set_art_capil_stat/'.$rtm['rtm_no'].'/'.$o['id'].'/2')."\"><span class=\"btn btn-warning btn-xs\"><i class=\"fa fa-minus fa-fw\"></i></span> Tidak Terdaftar di Capil</a></li>
															<li class=\"divider\"></li>
															<li><a href=\"".site_url('verval/set_art_capil_stat/'.$rtm['rtm_no'].'/'.$o['id'].'/4')."\"><span class=\"btn btn-danger btn-xs\"><i class=\"fa fa-minus fa-fw\"></i></span> Sdh meninggal tapi blum lapor</a></li>
														</ul>
													</div>
													
												</td>
												<td>".$o['pnama']."</td>
												<td>NIK: ";
												if($o['capil_stat'] == 1){
													echo "<a href=\"".site_url('kependudukan/individu/'.$o['id'].'/')."\" target=\"_blank\">".$o['pnik']."</a>
													<br />NOKK: ".$o['nokk']."";
												}else{
													echo "
													<div id=\"penduduk-nik-".$o['id']."\" class=\"editable editable_idv\">".$o['pnik']."</div>
													NKK: <div id=\"penduduk-kk_nomor-".$o['id']."\" class=\"editable editable_idv\">".$o['nokk']."</div>
													";
												}
												echo "
													</td>
												<td>".$sex."</td>
												<td>
													<div id=\"idv_hub_rtm_".$o['id']."_".$rtm['rtm_no']."\" class=\"editable_hub_rtm editable\">".$o['hubungan']."
													</div>
												</td>
												<td>".$o['hubungankk']."</td>
												<td>".date("d/m/y",strtotime($o['dtlahir']))."<br />(".$o['umur'].")</td>
											</tr>
											";
											
											if ($o['kk_hubungan'] == 1) {
												// Tampilkan Alamat KK
												echo "
												<tr><td>&nbsp;</td>
													<td colspan=\"3\" class=\"angka\">Alamat Kartu Keluarga</td>
													<td colspan=\"6\">RT ".$o['kk_rt']." / RT ".$o['kk_rw'].", Kel. ".$o['kk_kelurahan']." - Kec. ".$o['kk_kecamatan']." - </td>
													</tr>
												";
											}else{
												// Tampilkan Alamat KTP
												echo "
												<tr><td>&nbsp;</td>
													<td colspan=\"3\" class=\"angka\">Alamat Kartu Tanda Penduduk / KTP</td>
													<td colspan=\"6\">RT ".$o['kk_rt']." / RT ".$o['kk_rw'].", Kel. ".$o['kk_kelurahan']." - Kec. ".$o['kk_kecamatan']." - </td>
													</tr>
												";
											}
											
											$nomer++;
											/*
											 * 
												<!--
													<a class=\"btn btn-default btn-xs\" href=\"".site_url('tools/update4capil/'.$o['pnik'].'/'.$o['id'].'/'.$rtm['rtm_no'])."\"><i class=\"fa fa-refresh\"></i></a>
													-->
											 * */
										}
										?>
									</tbody>
								</table>
							</div>
							<div class="box-footer">
								<fieldset>
									<legend>Legenda</legend>
									<div>
										<table class="table">
											<thead>
												<tr>
													<th style="width:50px;">Icon</th>
													<th>Keterangan</th>
												</tr>
											</thead>
											<tbody>
												<tr><td><button class="btn btn-warning btn-xs"><i class="fa fa-th"></i> Data KK</button></td>
													<td>Tombol untuk menampilkan formulir data Rumah Tangga (KK Utama - 88 isian)/Keluarga (KK Tambahan 56 isian). Bila data sudah terisi penuh, tombol akan berwarna hijau <button class="btn btn-success btn-xs"><i class="fa fa-th"></i> Data KK</button></td>
												</tr>
												<tr><td><button class="btn btn-warning btn-xs"><i class="fa fa-user"></i> ART</button></td>
													<td>Tombol untuk menampilkan formulir data Anggota Rumah Tangga (Individu - 22 isian) Bila data sudah terisi penuh, tombol akan berwarna hijau <button class="btn btn-success btn-xs"><i class="fa fa-user"></i> ART</button></td>
												</tr>
												<tr><td><button class="btn btn-default btn-xs"><i class="fa fa-minus"></i></button></td>
													<td>Data BELUM DISINKRONKAN dgn data DUKCAPIL</td>
												</tr>
												<tr><td><button class="btn btn-warning btn-xs"><i class="fa fa-minus"></i></button></td>
													<td>Data Individu TIDAK DITEMUKAN di data DUKCAPIL</td>
												</tr>
												<tr><td><button class="btn btn-danger btn-xs"><i class="fa fa-exclamation-circle"></i></button></td>
													<td>Data Individu SUDAH MENINGGAL, tapi BELUM LAPOR ke DUKCAPIL</td>
												</tr>
												<tr><td><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></td>
													<td>Data Individu SUDAH TERMUTAKHIRKAN dgn data DUKCAPIL</td>
												</tr>
												<tr><td><button class="btn btn-default btn-xs"><i class="fa fa-refresh"></i></button></td>
													<td>Tombol utk memutakhirkan data dgn DUKCAPIL</td>
												<tr><td><button class="btn btn-default btn-xs"><i class="fa fa-home"></i></button></td>
													<td>Merupakan tombol utk melengkapi data tentang tempat tinggal anggota rumah tangga.
													<br />Bila ART TIDAK TINGGAL SERUMAH, silakan klik tombol <span class="btn btn-xs btn-default"><i class="fa fa-home"></i> serumah</span> untuk melengkapi data tinggal ART</td>
												</tr>
											</tbody>
										</table>
									</div>
								</fieldset>
								<!--
								<a href="<?php $o="m"; echo $o;?>" class="btn btn-primary"><i class="fa fa-user-plus"></i> Tambah Data Anggota Keluarga</a>
								-->
							</div>
						</div>

						
						<!-- PETA LOKASI RUMAH TANGGA SASARAN-->

						<form action="<?php echo $form_action_rts; ?>" method="POST" class="formular form-horizonal" role="form">
									
							<div class="">
								<div class="box box-primary">
									<div class="box-header with-border">
										<h3 class="box-title">PETA LOKASI Domisili RTS </h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
											</button>
										</div>
										</div>
									<div class="box-body">
										<div class="map_canvas" id="map_canvas" ></div>
									</div>
									<div class="box-footer">
									</div>
								</div>
							</div>

						</form>


						
						<?php
					}
				}
				?>					

		</section>
	</div>
	
	
<!-- footer section -->

<script src="<?php echo base_url("assets/plugins/"); ?>jeditable/jquery.jeditable.mini.js"></script>


<!-- CSS ValidationEngine -->
<link href="<?php echo base_url('assets/plugins/'); ?>validation-engine/validationEngine.jquery.css" rel="stylesheet" type="text/css">

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
		$("#addnokk" ).select2({
			placeholder: "Cari KK dengan NOMOR KK",
			minimumInputLength: 5,
			// instead of writing the function to execute the request we use Select2's convenient helper
			ajax: {
				url: "<?php echo site_url("ajax/"); ?>kartu_keluarga/",
				dataType: "json",
				quietMillis: 250,
				data: function( term, page ) {
					return {
						// search term
						q: term
					};
				},
				results: function( data, page ) {
						// parse the results into the format expected by Select2.
						// since we are using custom formatting functions we do not need to alter the remote JSON data
						return { results: data.items };
				},
				cache: true
			},
			formatResult: repoFormatResult,
			formatSelection: repoFormatSelection,  // omitted for brevity, see the source of this page
			dropdownCssClass: "bigdrop",
			escapeMarkup: function( m ) {
				return m;
			}
		});
		$("#reset").click(function(){
			$("#detail_cari").removeClass("hidden");
			$("#detail_rtm").addClass("hidden");
			
		});
	});

	function repoFormatResult( repo ) {
		var markup = "<div class='select2-result-repository clearfix'>" +
			"<div class='select2-result-repository__meta'>" +
				"<div class='select2-result-repository__title'>" + repo.kk_nomor + " ["+ repo.kk_nama +"] "+
				"<br />Alamat: " + repo.kk_alamat + "</div>"+
			"</div></div>";
		return markup;
	}

	function repoFormatSelection(repo) {
		$("#detail_cari").addClass("hidden");
		$("#detail_rtm").removeClass("hidden");
		$("#rtm_no_view").html(repo.kk_nomor);
		$("#datane").html(repo.kk_nomor);
		$("#rtm_kepala").html(repo.kk_nama);
		$("#rtm_alamat").html(repo.kk_alamat);

	}

</script>

<!-- Select INTERDEPENDENT -->
<link href="<?php echo base_url("assets/plugins/"); ?>dependent-dropdown/css/dependent-dropdown.css " rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/plugins/"); ?>dependent-dropdown/js/dependent-dropdown.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>dependent-dropdown/js/locales/id.js"></script>
<script>
<?php
// $RTSHub = array();
// foreach($opsiRTSHub as $key=>$item){
// 	$RTSHub[$item['id']]=$item['nama'];
// }
?>	
$(document).ready(function() {
	var $busak = $('a.remkk');
	if($busak){
		$busak.click(function(){
			var d=$(this).attr('rel');
			var r=$(this).attr('id');
			var k=$(this).attr('name');
			$('#toDel').prop('href',d);
			$('#the_kk').html(k);
			$('#the_rts').html(r);
		});
	}

	var formular = $("form.formular");
	if(formular){
		$("form.formular").validationEngine();
	}
	/*
	* Hover Pop Over
	*/	
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover()
	
	/*
	Dependant dropdown
	*/
	var kel = $("#kel_id");
	if(kel){
		$("#kel_id").depdrop({
			url: '<?php echo site_url('ajax/siteman_wilayah/'.substr($rtm['kode_wilayah'],0,10));?>',
			depends: ['kec_id'],
			loadingText: 'Memuat daftar Desa/Kelurahan ...'
		});
		$("#dusun_id").depdrop({
			url: '<?php echo site_url('ajax/siteman_wilayah/'.substr($rtm['kode_wilayah'],0,12));?>',
			depends: ['kel_id'],
			loadingText: 'Memuat daftar Dusun/Lingkungan ...'
		});
		$("#rw_id").depdrop({
			url: '<?php echo site_url('ajax/siteman_wilayah/'.substr($rtm['kode_wilayah'],0,15));?>',
			depends: ['dusun_id'],
			loadingText: 'Memuat daftar RW ...'
		});
		$("#rt_id").depdrop({
			depends: ['rw_id'],
			initialize: true,
			initDepends: ['kec_id'],
			url: '<?php echo site_url('ajax/siteman_wilayah/'.$rtm['kode_wilayah']);?>',
			loadingText: 'Memuat daftar RT ...'
		});
	}
	
	/*
	DOMISILI RTS
	*/
	var form_rts_dom = $('form#form_rts_dom');
	if(form_rts_dom){
		
		$(form_rts_dom).submit(function(event) {
			var formData = {
				'rts_kec' 			: $('select[name=rts_kecamatan]').val(),
				'rts_kel' 			: $('select[name=rts_kelurahan]').val(),
				'rts_dusun' 			: $('select[name=rts_dusun]').val(),
				'rts_rw' 				: $('select[name=rts_rw]').val(),
				'rts_rt' 				: $('select[name=rts_rt]').val(),
				'rts_alamat'		: $('textarea[name=rts_alamat]').val(),
				'rts_no' 				: $('input[name=rts_no_rtm]').val(),
				'rts_id' 				: $('input[name=rts_id_rtm]').val()
			};

			// process the form
			$.ajax({
				type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
				url 		: '<?php echo $form_action_domisili;?>', // the url where we want to POST
				data 		: formData, // our data object
				dataType 	: 'json', // what type of data do we expect back from the server
				encode 		: true
			})
				// using the done promise callback
				.done(function(data) {

					// log data to the console so we can see
					console.log(data); 

					// here we will handle errors and validation messages
					if ( ! data.success) {
						if (data.errors.rts_kecamatan) {
							$('#kec_id').parents('div.form-group').addClass('has-error');
							$('#kec_id').parents('div.form-group').append('<div class="help-block">' + data.errors.rts_kecamatan + '</div>');
						}
						if (data.errors.rts_kelurahan) {
							$('#kel_id').parents('div.form-group').addClass('has-error');
							$('#kel_id').parents('div.form-group').append('<div class="help-block">' + data.errors.rts_kelurahan + '</div>');
						}
						if (data.errors.rts_dusun) {
							$('#dusun_id').parents('div.form-group').addClass('has-error');
							$('#dusun_id').parents('div.form-group').append('<div class="help-block">' + data.errors.rts_dusun + '</div>');
						}
						if (data.errors.rts_rt) {
							$('#rw_id').parents('div.form-group').addClass('has-error');
							$('#rw_id').parents('div.form-group').append('<div class="help-block">' + data.errors.rts_rw + '</div>');
						}
						if (data.errors.rts_rt) {
							$('#rt_id').parents('div.form-group').addClass('has-error');
							$('#rt_id').parents('div.form-group').append('<div class="help-block">' + data.errors.rts_rt + '</div>');
						}

					} else {

						// ALL GOOD! just show the success message!
						$('button#save_rts_dom').removeClass('btn-primary');
						$('button#save_rts_dom').addClass('btn-success');
						$('form').append('<div class="alert alert-success">' + data.message + '</div>');
						var strBtn = "<i class='fa fa-check'></i> Data Tersimpan";
						$('button#save_rts_dom').html(strBtn);

					}
				})
				.fail(function(data) {

					// show any errors
					// best to remove for production
					console.log(data);
				});
			event.preventDefault();
		});
		
	}
	
	
} );
</script>

<script type="text/javascript" src="//maps.google.com/maps/api/js?key=<?php echo GMAP_KEY; ?>" async defer></script>
<script>
		var map;
		var marker;
		var location;
		var image = "<?php echo base_url("assets/images/map-marker.png"); ?>";
		var rumah = "<?php echo base_url("assets/img/icon/home_icon_red.png"); ?>";
		
		
		function placeMarker(location) {
			if ( marker ) {
				marker.setPosition(location);
			} else {
				marker = new google.maps.Marker({
					position: location,
					title: 'Lokasi Rumah Tangga Sasaran',
					map: map,
					draggable: false
				});
			}
		}
		
		function initialize(){
			var infowindow = new google.maps.InfoWindow({
				size: new google.maps.Size(150, 50)
			});
			
			<?php
			$thisZoom = GMAP_ZOOM;
			if($rtm['varlat']  != 0){
				$thisZoom = $rtm['zoom'];
				echo "var myLatlng = new google.maps.LatLng('".$rtm['varlat']."','".$rtm['varlon']."');";
			}else{
				echo "var myLatlng = new google.maps.LatLng('".GMAP_LAT."','".GMAP_LON."');";
			}
			?>
			
			
			var myOptions = {
				zoom: <?php	echo $thisZoom;?>,
				center: myLatlng,
				overviewMapControl: true
			}
			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);		
			
			<?php
			if($rtm['varlat']  != 0){
				echo "
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng('".$rtm['varlat']."','".$rtm['varlon']."'),
					map: map,
					icon:rumah,
					draggable:false
					});
				";				
			}
			?>

			google.maps.event.addListener(map, 'click', function(event){
				if (event.latLng) {
					placeMarker(event.latLng);
					var postdata = {'rtm_id':0,
						'lat':event.latLng.lat(),
						'lng':event.latLng.lng(),
						'zoom':map.getZoom()
						};
					savePos(postdata);
				}
			});
		}
		function addEvent(obj, evType, fn){ 
		 if (obj.addEventListener){ 
			 obj.addEventListener(evType, fn, false); 
			 return true; 
		 } else if (obj.attachEvent){ 
			 var r = obj.attachEvent("on"+evType, fn); 
			 return r; 
		 } else { 
			 return false; 
		 } 
		}
		
		addEvent(window, 'load',initialize);
		function savePos(vdata){
			$(document).ready(function() {
				$.ajax({
					type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
					url 		: '<?php echo $form_action_peta;?>', // the url where we want to POST
					data 		: vdata, // our data object
					dataType 	: 'json', // what type of data do we expect back from the server
					encode 		: true
				})
					// using the done promise callback
					.done(function(data) {

						// log data to the console so we can see
						console.log(data); 
					})
					.fail(function(data) {

						// show any errors
						// best to remove for production
						console.log(data);
					});
				event.preventDefault();
			});
		}
</script>
<!-- Modal -->

<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Keluarkan KK dari Rumah Tangga</h4>
      </div>
      <div class="modal-body">
        <p>Anda yakin akan mengeluarkan KK No <strong id="the_kk"></strong> dari RTS <strong id="the_rts"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <a href="" class="btn btn-primary" id="toDel">Ya</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php


$this->load->view('siteman/siteman_footer');
