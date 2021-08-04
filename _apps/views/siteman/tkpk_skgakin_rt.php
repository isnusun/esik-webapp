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
					<!--box data-->
					<div class="box box-primary box-solid">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i> Daftar <a href="<?php echo site_url('skgakin');?>"><?php echo $boxTitle;?></a> di <strong><?php echo $wilayah; ?></strong> Periode <strong><?php echo $periode[$periode_on]['nama']; ?></strong></h3>
						</div>
						<!-- content index
						isi berupa rangkuman data
						-->
						<div class="box-body">
							<?php 
								$maxPage = ceil($skgakin['n']/LIMIT_TAMPIL);
								$nomer = (($page -1 ) * LIMIT_TAMPIL) + 1;
								$akhir = $nomer + LIMIT_TAMPIL;
								if($akhir > $skgakin['n']){
									$akhir = $skgakin['n'];
								}
								
							?>
							<div>
								<dl class="dl-horizontal">
									<dt>Total Data</dt>
									<dd><?php echo $skgakin['n']; ?></dd>
									<dt>Data</dt>
									<dd><?php echo $nomer ." - ".$akhir; ?></dd>
								</dl>
							</div>
							<table class="table table-responsive table-bordered datatables">
								<thead><tr>
									<th>#</th>
									<th>NO RTM</th>
									<th>Kepala Rumah Tangga</th>
									<th>N ART</th>
									<th>Jenis Kelamin</th>
									<th>Tgl Lahir</th>
								</tr>
								</thead>
								<tbody>
									<?php
									foreach($skgakin['data'] as $key=>$item){
										$sex = ($item['sex']==1)? "L":"P";
										echo "
										<tr><td class=\"angka\">".$nomer."</td>
											<td>".$item['rtm_no']."</a></td>
											<td><a href=\"".site_url('kependudukan/individu/'.$item['penduduk_id'])."\">".$item['pnama']."</a></td>
											<td><a href=\"".$item['penduduk_id']."\">".$item['nanggota']."</a></td>
											<td>".$item['sex']."</td>
										";

											echo "
										</tr>
										";
										$nomer++;
									}

									?>
								</tbody>
							</table>
						</div>
						<div class="box-footer">
							<?php
							$strUrl = site_url('skgakin/responden_rt/'.$param[1].'/'.$param[0].'/');
							if($maxPage >= 1){
								$apage = 10;
								echo "
								<ul class=\"pagination\">";
								
								if($page > 1){
									$hpre = $page-1;
									echo "<li><a href=\"".$strUrl."1\"><i class=\"fa fa-fast-backward\"></i></a></li>";
									if($hpre>1){
										echo "<li><a href=\"".$strUrl.$hpre."\"><i class=\"fa fa-backward\"></i></a></li>";
									}
								}
								if($maxPage <= $apage){
									for($hal=1;$hal<=$maxPage;$hal++){
										$strC = ($hal == $page) ? "class=\"active\"":""; 
										echo "<li ".$strC."><a href=\"".$strUrl.$hal."\">".$hal."</a></li>";
									}
								}else{
									if($page < $apage){
										for($hal=1;$hal<=$apage;$hal++){
											$strC = ($hal== $page) ? "class=\"active\"":""; 
											echo "<li ".$strC."><a href=\"".$strUrl.$hal."\">".$hal."</a></li>";
										}
									}else{
										$ppage = $page-4;
										$spage = ($ppage > 0) ? $ppage : 1 ;
										$npage = $spage+9;
										$epage = ($npage >= $maxPage)? $maxPage:$npage;

										for($hal=$spage;$hal<=$epage;$hal++){
											$strC = ($hal== $page) ? "class=\"active\"":""; 
											echo "<li ".$strC."><a href=\"".$strUrl.$hal."\">".$hal."</a></li>";
										}
									}

								}
								if($page<$maxPage){
									$hnext = $page+1;
									if($hnext<$maxPage){
										echo "<li><a href=\"".$strUrl.$hnext."\"><i class=\"fa fa-forward\"></i></a></li>";
									}
									echo "<li><a href=\"".$strUrl.$maxPage."\"><i class=\"fa fa-fast-forward\"></i></a></li>";
								}
								
								echo "</ul>";
							}							
							?>
						</div>
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
							<fieldset>
								<legend>Periode SK Gakin</legend>
								<div class="btn-group-vertical">
										<?php 
										foreach($periode as $key=>$item){
											$strA = ($key==$periode_on)? "btn-primary":"btn-default";
											echo "<a class=\"btn ".$strA."\" href=\"".site_url('skgakin/index/'.$kode.'/'.$key.'/')."\">".$item['nama']."</a></li>";
										}
										
										?>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<!-- footer section -->

<?php

$this->load->view('siteman/siteman_footer');
