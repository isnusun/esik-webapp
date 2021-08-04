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
					<dl class="dl-horizontal">
						<?php 
							echo "
							<dt>NO Urut RT</dt><dd>".$rtm['rtm_no']." <a href=\"".site_url('verivali/form/'.$rtm['rtm_no'])."\" class=\"btn btn-default btn-xs\"><i class=\"fa fa-check-circle-o\"></i> Data Indikator</a></dd>
							<dt>Kepala Rumah Tangga</dt><dd>".$rtm['kepala']."</dd>
							<dt>Jenis Kelamin</dt><dd>".$rtm['kepala_sex']."</dd>
							<dt>Tanggal Lahir, Umur</dt><dd>".date("j F Y",strtotime(trim($rtm['kepala_dtlahir']))).", ".$rtm['kepala_umur']." tahun</dd>
							<dt>Alamat</dt><dd>".$rtm['alamat']."</dd>
							<dt>Klasifikasi PBDT</dt><dd>DESIL ".$rtm['kelas_pbdt']."</dd>
							";
						?>
					</dl>
					<?php
					if(count($rtm['anggota']) > 0){
						?>
						<div class="box box-primary box-solid">
							<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-users"></i> Anggota Rumah Tangga</h3></div>
							<div class="box-body">
								<table class="table table-responsive table-bordered">
									<thead><tr>
										<th>#</th><th>Nama</th>
										<th>NIK</th>
										<th>Jenis Kelamin</th>
										<th>Hubungan</th>
										<th>Tgl Lahir</th>
										<th>Umur</th><th></th>
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
												<td>".$o['dtlahir']."</td>
												<td>".$o['umur']."</td>
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
							</div>
						</div>
						<?php
					}
					?>
					
					<div class="row">
						<div class="col-md-6 col-sm-12 col-xs-12">
							<!--Program Bantuan RTM-->
							<div class="box box-warning">
								<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-cubes"></i> Program Bantuan utk RTM</h3></div>
								<div class="box-body">
								</div>
							</div>
							
						</div>
						<div class="col-md-6 col-sm-12 col-xs-12">
							<!--Program Bantuan Individu-->
							<div class="box box-warning">
								<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-cubes"></i> Program Bantuan utk Individu</h3></div>
								<div class="box-body">
								</div>
							</div>
						
						</div>
					</div>
					
				</div>
			</div>


			<!--box filter-->
		</section>
	</div>
<!-- footer section -->


<?php

$this->load->view('siteman/siteman_footer');
