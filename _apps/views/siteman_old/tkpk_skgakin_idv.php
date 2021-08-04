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
							<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i> <a href="<?php echo site_url('skgakin');?>"><?php echo $boxTitle;?></a> di <strong><?php echo $wilayah; ?></strong> Periode <strong><?php echo $periode[$periode_on]['nama']; ?></strong></h3>
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
								
							if($strMsg){
								echo "<div class=\"alert alert-".$strMsg['btn']." alert-dismissible\">
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                <h4><i class=\"icon fa ".$strMsg['icon']."\"></i> ".$strMsg['msg']."</h4>
								</div>";
							}
							?>
							<div>
								<dl class="dl-horizontal">
									<dt>Data</dt>
									<dd><?php echo $nomer ." - ".$akhir; ?></dd>
									<dt>Total Data</dt>
									<dd><?php echo number_format($skgakin['n'],0); ?></dd>
								</dl>
							</div>
							<table class="table table-responsive table-bordered datatables">
								<thead><tr>
									<th>#</th>
									<th>NO RTM</th>
									<th>NIK</th>
									<th>NAMA</th>
									<th>Jenis Kelamin</th>
									<th>Tgl Lahir</th>
									<th>Sumber Data</th>
									<th></th>
								</tr>
								</thead>
								<tbody>
									<?php
									foreach($skgakin['data'] as $key=>$item){
										echo "
										<tr><td class=\"angka\">".$nomer."</td>
											<td><a href=\"".site_url('kependudukan/rtm/'.$item['rtm_no'])."\">".$item['rtm_no']."</a></td>
											<td><a href=\"".site_url('kependudukan/individu/'.$item['penduduk_id'])."\">".$item['pnik']."</a></td>
											<td><a href=\"".site_url('kependudukan/individu/'.$item['penduduk_id'])."\">".$item['pnama']."</a></td>
											<td>".$item['sex']."</td>
											<td>".$item['dtlahir']."</td>
											<td>".$item['sumber']."</td>
											<td>
												<div class=\"btn-group\">
												<a class=\"btn btn-danger btn-xs\" href=\"#\" data-toggle=\"modal\" data-href=\"".site_url('skgakin/hapus_idv/'.$item['id'])."\"  data-target=\"#confirm-delete\" title=\"Hapus baris data ini\"><i class=\"fa fa-trash\"></i></a>
												</div>
											</td>
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
							$strUrl = site_url('skgakin/responden_idv/'.$param[1].'/'.$param[0].'/');
							if($maxPage >= 1){
								$apage = 10;
								echo "
								<div class=\"box-footer\">
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
								echo "</div>\n<!--end pagination-->";
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
								<i class="fa fa-calendar fa-stack-1x"></i>
								</span> Periode SK Gakin</a>
							</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div><!-- /.box-tools -->

						</div>
						<div class="box-body">
							<div class="btn-group-vertical">
									<?php 
									foreach($periode as $key=>$item){
										$strA = ($key==$periode_on)? "btn-primary":"btn-default";
										echo "<a class=\"btn ".$strA."\" href=\"".site_url('skgakin/index/'.$kode.'/'.$key.'/')."\">".$item['nama']."</a></li>";
									}
									
									?>
							</div>
						</div>
					</div>

					<!--data individu-->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">
								<span class="fa-stack">
								<i class="fa fa-square-o fa-stack-2x"></i>
								<i class="fa fa-th-list fa-stack-1x"></i>
								</span> Data Individu</a>
							</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div><!-- /.box-tools -->

						</div>
						<div class="box-body">
							<ul class="nav nav-pills">
								<?php
								
								foreach($list_query_individu as $key=>$item){
									$strBtn = ($key == $this->uri->segment('3')) ? "btn-primary":"btn-default";
									$strBtn = ($key == $this->uri->segment('3')) ? "class=\"active\"":"";
									//class=\"btn ".$strBtn." \"
									echo "<li ".$strBtn."><a  href=\"".site_url('skgakin/arts/'.$key)."\">".$item['nama']."</a></li>";
								}
								
								?>
							</ul>					
						</div>
					</div>					
					<!--data individu-->
					
				</div>
			</div>
		</section>
	</div>
<!-- footer section -->
	<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
					<div class="modal-content">
					
							<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="myModalLabel">Konfirmasi Penghapusan Data</h4>
							</div>
					
							<div class="modal-body">
									<p>Anda hendak menghapus satu baris data SKGAKIN. Penghapusan ini tidak bisa dikembalikan.</p>
									<p>Lanjutkan?</p>
									<!--
									<p class="debug-url"></p>
									-->
							</div>
							
							<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
									<a class="btn btn-danger btn-ok">Hapus</a>
							</div>
					</div>
			</div>
	</div>
	<script>
			$('#confirm-delete').on('show.bs.modal', function(e) {
					$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
					
//					$('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
			});
	</script>
	
<?php

$this->load->view('siteman/siteman_footer');
