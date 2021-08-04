<?php
/*
 * penduduk_individu.php
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
			<small>Data Rumah Tangga</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Kependudukan</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<!--box filter-->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-newspaper-o fa-fw"></i> <?php echo $pageTitle; ?></h3>
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
											<dt>Alamat</dt><dd>".$rtm['alamat']."</dd>";
											if($rtm['kelas_pbdt'] > 0){
												echo "<dt>Klasifikasi PBDT</dt><dd>DESIL ".$rtm['kelas_pbdt']."</dd>";
											}
											if($rtm['kelas_pbk'] > 0){
												echo "<dt>Klasifikasi Data Sisiran</dt><dd>Prioritas ".$rtm['kelas_pbk']."</dd>";
											}
											
											echo "
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
													<td><a href=\"".site_url('kependudukan/individu/'.$o['id'].'/')."\">".$o['pnama']."</a></td>
													<td>".$o['pnik']."</td>
													<td>".$o['psex']."</td>
													<td>".$o['hubungan']."</td>
													<td><div class=\"btn-group\">
														<a href=\"".site_url('kependudukan/individu/'.$o['id'].'/edit')."\"><i class=\"fa fa-edit\"></i></a>
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

					
				</div>
			</div>


			<!--box filter-->
		</section>
	</div>
<!-- footer section -->


<?php

$this->load->view('siteman/siteman_footer');
