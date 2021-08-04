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
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-newspaper-o fa-fw"></i> Detail Rumah Tangga <?php echo $rtm['kepala']; ?></h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->

				</div>
				<div class="box-body" >
					<!-- individu header-->
					<div class="row">
						<div class="col-md-6 col-sm-12 col-xs-12">
							<!--Program Bantuan RTM-->
							<div class="box box-warning">
								<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-cubes"></i> Data Rumah Tangga</h3></div>
								<div class="box-body">
									<dl class="dl-horizontal">
										<?php 
											echo "
											<dt>NO Urut RT</dt><dd>".$rtm['rtm_no']."</dd>
											<dt>Kepala Rumah Tangga</dt><dd>".$rtm['kepala']."</dd>
											<dt>Jenis Kelamin</dt><dd>".$rtm['kepala_sex']."</dd>
											<dt>Tanggal Lahir, Umur</dt><dd>".date("j F Y",strtotime(trim($rtm['kepala_dtlahir']))).", ".$rtm['kepala_umur']." tahun</dd>
											<dt>Alamat</dt><dd>".$rtm['alamat']."</dd>
											<dt>Klasifikasi PBDT</dt><dd>DESIL ".$rtm['kelas_pbdt']."</dd>
											";
										?>
									</dl>
								</div>
							</div>
							
						</div>
						<div class="col-md-6 col-sm-12 col-xs-12">
							<!--Program Bantuan Individu-->
							<div class="box box-warning">
								<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-cubes"></i> Anggota Rumah Tangga Lainnya</h3></div>
								<div class="box-body">
								<?php
								if(count($rtm['anggota']) > 0){
									?>
									<table class="table table-responsive">
										<thead><tr>
											<th>#</th><th>Nama</th>
											<th>NIK</th>
											<th>Jenis Kelamin</th>
											<th>Hubungan</th>
											<th></th>
										</tr></thead>
										<tbody>
											<?php
											$nomer =1;
											foreach($rtm['anggota'] as $a=>$o){
												echo "
												<tr><td class=\"angka\">".$nomer."</td>
													<td><a href=\"".site_url('pbdt/idv_update/'.$o['id'].'/')."\">".$o['pnama']."</a></td>
													<td>".$o['pnik']."</td>
													<td>".$o['psex']."</td>
													<td>".$o['hubungan']."</td>
													<td><div class=\"btn-group\">
														<a href=\"".site_url('pbdt/idv_update/'.$o['id'].'/')."\"><i class=\"fa fa-edit\"></i></a>
														</div></td>
												</tr>
												";
												$nomer++;
											}
											?>
										</tbody>
									</table>
									<?php
								}
								?>
								</div>
							</div>
						
						</div>
					</div>					
					
					<!-- /individu header-->
					<!-- individu detail-->
					<div class="box box-primary">
						<div class="box-header with-border"><h3 class="box-title">Detail <?php echo $penduduk['nama'];?></h3></div>
						<div class="box-body">
							<form action="" method="POST" enctype="multipart/form-data" class="formular form-horizontal">
								<fieldset class="kotak">
									<legend>Data Pribadi</legend>
									<div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Nomor Induk Kependudukan</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['nik']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Nomor KK</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['kk_nomor']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Nama Lengkap</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['nama']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Jenis Kelamin</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['psex']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Tempat Lahir</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['tlahir']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Tanggal Lahir, Umur</label>
											<div class="col-md-4 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['dtlahir']; ?>" disabled>
											</div>
											<div class="col-md-4 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['umur']; ?> tahun" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Status Perkawinan</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['pkawin']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Memiliki Buku Nikah</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo ($penduduk['kawin_buku']==1)? "Ya":"Tidak"; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Kepilikan Kartu Identitas</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['kartu_identitas']; ?>" disabled>
												<?php echo pbdt_kitas($penduduk['kartu_identitas']); ?>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Hubungan dalam Rumah Tangga</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['hubungan_rtm']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Hubungan dalam Kartu Keluarga</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['hubungan_kk']; ?>" disabled>
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Terdaftar dalam Kartu Keluarga</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo ($penduduk['kk_terdaftar']==1)? "Ya":"Tidak"; ?>" disabled>
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Alamat</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['alamat']; ?>" disabled>
											</div>
										</div>
									</div>
								</fieldset>

								
								
								<fieldset class="kotak">
									<legend>Bidang Kesehatan</legend>
									<div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Hamil</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['phamil']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Kecacatan yang diderita</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['pcacat_jenis']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Penyakit Kronis yang diderita</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['psakit_kronis']; ?>" disabled>
											</div>
										</div>
									</div>
								</fieldset>

								<fieldset class="kotak">
									<legend>Bidang Pendidikan</legend>
									<div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Pertisipasi Sekolah</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['psekolah']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Jenjang Pendidikan</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['pjenjang_pendidikan']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Kelas Tertinggi yang Pernah/Sedang diduduki</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['kelas_tertinggi']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Ijazah Terakhir</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['pijazah']; ?>" disabled>
											</div>
										</div>
										
									</div>
								</fieldset>
								
								<fieldset class="kotak">
									<legend>Bidang Pekerjaan</legend>
									<div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Status Kerja</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['pkerja']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Jam Kerja</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['kerja_jam']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Bidang Pekerjaan</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['pbidang_kerja']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-12 col-xs-12">Jabatan dalam Pekerjaan</label>
											<div class="col-md-8 col-sm-12 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $penduduk['pkerja_kedudukan']; ?>" disabled>
											</div>
										</div>
									
									</div>
								</fieldset>

							<?php
							/*
							foreach($penduduk as $key=>$item){
								echo "
								<div class=\"form-group\">
									<label class=\"control-label col-md-4 col-sm-12 col-xs-12\">".$key."</label>
									<div class=\"col-md-8 col-sm-12 col-xs-12\">
										<input type=\"text\" class=\"form-control\" value=\"".$item."\" disabled>
									</div>
								</div>";
							}
							*/ 
							?>
							</form>
						</div>
					</div>
					<!-- /individu detail-->
					

					
				</div>
			</div>


			<!--box filter-->
		</section>
	</div>
<!-- footer section -->


<?php

$this->load->view('siteman/siteman_footer');
