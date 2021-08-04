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
			<small>SK Gakin</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Data Pengguna</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-9 col-sm-6 col-xs-12">
					<!--box filter-->
					<?php
// 					echo var_dump($param);
					?>
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-filter fa-fw"></i> <a href="<?php echo site_url('skgakin');?>"><?php echo $boxTitle_1;?></a></h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div><!-- /.box-tools -->

						</div>
						<div class="box-body">
							<form action="<?php echo $form_action;?>" method="POST" role="form" class="form-horizontal formular">
							<div class="form-group">
								<label class="col-md-4 col-sm-12 col-xs-12">Periode</label>
								<div class="col-md-8 col-sm-12 col-xs-12">
									<select name="periode" id="periode" class="form-control validate[required]">
										<?php 
										foreach($periode as $key=>$item){
											$strS = ($key == $param[0]) ? "selected=\"selected\"":"";
											echo "<option value=\"".$key."\" ".$strS.">".$item['nama']."</option>";
										}
										
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 col-sm-12 col-xs-12">Wilayah Administrasi</label>
								<div class="col-md-8 col-sm-12 col-xs-12">
									<select name="kode" id="kode" class="form-control validate[required]" placeholder="Pilih Nama Wilayah">
										<?php
										echo "<option value=\"".KODE_BASE."\">Seluruh Wilayah</option>";
										foreach($kecamatan as $key=>$item){
											$strS = ($key == $param[1]) ? "selected=\"selected\"":"";
											echo "<option value=\"".$key."\" ".$strS.">Kecamatan ".$item['nama']."</option>";
											foreach ($desa as $de=>$sa){
												if(substr($de,0,7) == $key){
													$strS = ($de == $param[1]) ? "selected=\"selected\"":"";
													echo "<option value=\"".$de."\" ".$strS.">&nbsp;&nbsp;&nbsp;-&nbsp;Kelurahan ".$sa['nama']."</option>";
												}
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 col-sm-12 col-xs-12">Sumber Data</label>
								<div class="col-md-8 col-sm-12 col-xs-12">
									<select name="sumber" id="sumber" class="form-control validate[required]">
										<?php 
										echo "<option value=\"0\">Semua Sumber Data</option>";
										foreach($sumber as $key=>$item){
											$strS = ($key == $param[2]) ? "selected=\"selected\"":"";
											echo "<option value=\"".$key."\" ".$strS.">".$item."</option>";
										}
										
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 col-sm-12 col-xs-12"></label>
								<div class="col-md-8 col-sm-12 col-xs-12">
									<button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Tampilkan Data</button>
								</div>
							</div>
							</form>
						</div>
					</div>

					<!--box data-->
					<div class="box box-primary box-solid">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i> <a href="<?php echo site_url('skgakin');?>"><?php echo $boxTitle;?></a> 
							di <strong><?php echo $wilayah; ?></strong> Periode <strong><?php echo $periode[$param[0]]['nama']; ?></strong> </h3>
						</div>
						
							<?php 
							if($skgakin) { 
								echo "<div class=\"box-body\">";

								if($skgakin['n'] > 0) {
									$limit = LIMIT_TAMPIL;
									$numrows = $skgakin['n'];
									
									$maxPage = ceil($numrows / $limit);
									
									echo "
									<table class=\"table table-responsive table-bordered\">
										<thead><tr>
											<th>NO</th>
											<th>NAMA</th>
											<th>ALAMAT</th>
											<th>SUMBER</th>
											<th></th>
										</tr></thead>
										<tbody>";
											$nomer = 1 + (($page -1) * $limit);
											foreach($skgakin['data'] as $key=>$item){
												echo "
												<tr><td class=\"angka\">".number_format($nomer,0)."</td>
													<td><a href=\"".site_url('kependudukan/individu/'.$item['penduduk_id'].'/')."\">".$item['nama']."</a></td>
													<td>".$item['alamat']."
														<br />RT: ".$item['rt']." - RW : ".$item['rw']." 
														<br />Kel. ".$item['kelurahan']." - Kec. ".$item['kecamatan']."</td>
													<td>".$item['src']."</td>													
												</tr>
												";
												$nomer++;
											}

									echo "
										</tbody>
									</table>";	

									if($maxPage >= 1){
										$apage = 10;
										echo "
										<div class=\"box-footer\">
										<ul class=\"pagination\">";
										
										if($page > 1){
											$hpre = $page-1;
											echo "<li><a href=\"".site_url('skgakin/data/'.$param[0].'/'.$param[1].'/'.$param[2].'/1')."\"><i class=\"fa fa-fast-backward\"></i></a></li>";
											if($hpre>1){
												echo "<li><a href=\"".site_url('skgakin/data/'.$param[0].'/'.$param[1].'/'.$param[2].'/'.$hpre)."\"><i class=\"fa fa-backward\"></i></a></li>";
											}
										}
										if($maxPage <= $apage){
											for($hal=1;$hal<=$maxPage;$hal++){
												$strC = ($hal == $page) ? "class=\"active\"":""; 
												echo "<li".$strC."><a href=\"".site_url('skgakin/data/'.$param[0].'/'.$param[1].'/'.$param[2].'/'.$hal)."\">".$hal."</a></li>";
											}
										}else{
											if($page < $apage){
												for($hal=1;$hal<=$apage;$hal++){
													$strC = ($hal== $page) ? "class=\"active\"":""; 
													echo "<li ".$strC."><a href=\"".site_url('skgakin/data/'.$param[0].'/'.$param[1].'/'.$param[2].'/'.$hal)."\">".$hal."</a></li>";
												}
											}else{
												$ppage = $page-4;
												$spage = ($ppage > 0) ? $ppage : 1 ;
												$npage = $spage+9;
												$epage = ($npage >= $maxPage)? $maxPage:$npage;

												for($hal=$spage;$hal<=$epage;$hal++){
													$strC = ($hal== $page) ? "class=\"active\"":""; 
													echo "<li ".$strC."><a href=\"".site_url('skgakin/data/'.$param[0].'/'.$param[1].'/'.$param[2].'/'.$hal)."\">".$hal."</a></li>";
												}
											}

										}
										if($page<$maxPage){
											$hnext = $page+1;
											if($hnext<$maxPage){
												echo "<li><a href=\"".site_url('skgakin/data/'.$param[0].'/'.$param[1].'/'.$param[2].'/'.$hnext)."\"><i class=\"fa fa-forward\"></i></a></li>";
											}
											echo "<li><a href=\"".site_url('skgakin/data/'.$param[0].'/'.$param[1].'/'.$param[2].'/'.$maxPage)."\"><i class=\"fa fa-fast-forward\"></i></a></li>";
										}
										
										echo "</ul>";
										echo "</div>\n<!--end pagination-->";
									}

								}else{
									echo "<div class=\"alert alert-danger\">Belum ada data SKGAKIN</div>";
								}
								
								echo "</div>";
							}else{
								echo "
								<div class=\"box-body\">
									<div class=\"alert alert-warning\">Belum ada data SK GAKIN, sila gunakan formulir penyaringan diatas untuk menampilkannya</div>
								</div>";
								
							}
							?>
					</div>
					
					
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">
					<!--box filter-->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">
								<span class="fa-stack">
								<i class="fa fa-square-o fa-stack-2x"></i>
								<i class="fa fa-info fa-stack-1x"></i>
								</span> Info</a>
							</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div><!-- /.box-tools -->

						</div>
						<div class="box-body">

						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<!-- footer section -->

<?php

$this->load->view('siteman/siteman_footer');
