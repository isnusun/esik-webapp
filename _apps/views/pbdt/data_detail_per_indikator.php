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
			<li class="active">PBDT</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			$this->load->view('pbdt/pbdt_head');
			?>
			
			<!--box data-->
			<div class="box box-primary box-solid">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i> Rangkuman <?php echo $boxTitle;?></strong></h3>
				</div>
				
				<!-- content index
				isi berupa rangkuman data
				-->
				<div class="box-body">
					
					
					<?php
					$wurl = substr(current_url(),0,strrpos(current_url(),"/"));
					$wkode = substr(current_url(),strrpos(current_url(),"/")+1);
						echo "<ol class=\"breadcrumb\">";
						if(is_array($alamat)){
							$nama = "";
							foreach($alamat as $key=>$item){
								echo "<li><a href=\"".$wurl."/".$item['kode']."\">".$item['nama']."</a></li>";
								$nama = $item['nama'];
							}
						}
						echo "</ol>";


					echo "
          <div class=\"box box-warning\">
            <div class=\"box-header with-border\">
              <h3 class=\"box-title\">Pilihan Sub Wilayah dari <strong>".$nama."</strong></h3>

              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
						<div><ol class=\"nav nav-stacked\">
							";
							foreach($sub_wilayah as $key=>$rs){
								echo "<li><a href=\"".$wurl."/".$key."\">".$rs['nama']."</a></li>";
							}

							echo "
							</ol>
						</div>
						</div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
					";
					if($idv){

						echo "
						<div class=\"box box-primary\">
							<div class=\"box-header with-border\">
								<h3 class=\"box-title\">Indikator Anggota Rumah Tangga</h3>
							</div>
							<div class=\"box-body\">
								<table class=\"table table-responsive table-bordered\">
								<thead><tr>
									<th rowspan=\"2\">No</th>
									<th rowspan=\"2\" colspan=\"2\">Indikator</th>
									<th colspan=\"".count($periode2show)."\">&Sigma; Responden</th>
								</tr>
								<tr>";
								foreach($periode2show as $periode){
									echo "<th>".$periode['nama']."</th>";
								}

								echo "</tr>
								</thead>
								<tbody>";
								$nomer = 0;

								foreach($param as $p=>$pa){
									$nomer++;
									// $total = (is_array($nilai)) ? array_sum($nilai[$p]):0;
									$total = 0;
									if($pa['jenis'] == 1){
										echo "<tr>
										<td class=\"angka\">".$nomer."</td>
										<td colspan=\"2\"><a href=\"".site_url('gakin/indikator_detail/idv/'.$pa['id'].'/'.$wkode)."\" target=\"_blank\">".$pa['nama']."</a></td>
										<td colspan=\"".count($periode2show)."\"></td>
										</tr>";
											if(is_array($opsi)){
											if(array_key_exists($pa['id'],$opsi)){
												foreach($opsi[$pa['id']] as $ops=>$o){
													echo "<tr>
													<td>&nbsp;</td>
													<td>".$o['opsi_kode']."</td>
													<td>".$o['nama']."</td>";
													foreach($periode2show as $periode=>$perio){
														$angka = 0;
														if(array_key_exists($perio['id'],$nilai[$pa['id']])){
															if(array_key_exists($o['opsi_kode'],$nilai[$pa['id']][$perio['id']])){
																$angka = $nilai[$pa['id']][$periode][$o['opsi_kode']];
															}
														}
														// if(array_key_exists($periode,$nilai[$pa['id']]))
														echo "
														<td class=\"angka\">
														<a href=\"".site_url('pbdt/idv_siapa_opsi/'.$o['id'].'/'.$wkode.'/'.fixNamaUrl($o['nama']))."\" target=\"_blank\">".number_format($angka,0)."</a>
														</td>";
													}
													echo "
													</tr>";
												}
											}
										}

									}else{
										echo "<tr>
										<td class=\"angka\">".$nomer."</td>
										<td colspan=\"2\"><a href=\"".site_url('gakin/indikator_detail/idv/'.$pa['id'].'/'.$wkode)."\" target=\"_blank\">".$pa['nama']."</a></td>";
										
										foreach($periode2show as $periode=>$perio){
											echo "<td class=\"angka\"><a class=\"btn btn-xs btn-default\" href=\"".site_url('gakin/indikator_detail/idv/'.$pa['id'].'/'.$wkode)."\"><i class=\"fa fa-list\"></i></a></td>";
										}
										echo "</tr>";
									}
								}

								echo "
								</tbody>
								</table>";
							echo "
							</div>
						</div>
						
						";
					}else{
						// echo "<pre>";
						// echo var_dump($nilai);
						// echo "</pre>";
						echo "
						<div class=\"box box-primary\">
							<div class=\"box-header with-border\">
								<h3 class=\"box-title\">".$boxTitle."</h3>
							</div>
							<div class=\"box-body\">
								<table class=\"table table-responsive table-bordered\">
								<thead>
								<tr>
									<th rowspan=\"2\">No</th>
									<th rowspan=\"2\" colspan=\"2\">Indikator</th>
									<th colspan=\"".count($periode2show)."\">&Sigma; Responden</th>
								</tr>
								<tr>";
								foreach($periode2show as $periode){
									echo "<th>".$periode['nama']."</th>";
								}
								echo"
								</tr>
								</thead>
								<tbody>";
								$nomor = 0;
								foreach($param as $p=>$pa){
									$pa_url = ($pa['jenis'] == 1) ? "<a href=\"".site_url('pbdt/indikator_detail/rts/'.$pa['id'].'/'.$wkode)."\" target=\"_blank\">".$pa['nama']."</a>":$pa['nama'];
									echo "<tr>
									<td>".$pa['id']."</td>
									<td colspan=\"2\">".$pa_url."</td>
									<td></td>
									<td></td>
									</tr>";

									if($pa['jenis'] == 1){
										if(is_array($opsi[$pa['id']])){
											if(array_key_exists($pa['id'],$opsi)){
												foreach($opsi[$pa['id']] as $ops=>$o){

													echo "<tr>
													<td colspan=\"2\">&nbsp;</td>
													<td>".$o['opsi_kode']." - ".$o['nama']."</td>";

													foreach($periode2show as $periode=>$perio){
														$angka = 0;
														if(array_key_exists($perio['id'],$nilai[$pa['id']])){
															if(array_key_exists($o['opsi_kode'],$nilai[$pa['id']][$perio['id']])){
																$angka = $nilai[$pa['id']][$periode][$o['opsi_kode']];
															}
														}
														// if(array_key_exists($periode,$nilai[$pa['id']]))
														echo "
														<td class=\"angka\">
														<a href=\"".site_url('pbdt/rts_siapa_opsi/'.$o['id'].'/'.$wkode.'/'.fixNamaUrl($o['nama']))."\" target=\"_blank\">".number_format($angka,0)."</a>
														</td>";
													}
													echo "
													</tr>";
												}
											}
										}
									}
								}
									echo "
									</tbody>
									</table>";
					}
							echo "
							</div>
						</div>
							";
					?>
				</div>
			</div>
			<!--box filter-->
		</section>
	</div>
<!-- footer section -->
<script>
	$(document).ready(function() {
		var $pilih = $('button.pilih');
		var $cuthat = $('button.cuthat');
		if($pilih){
			$pilih.click(function(){
				$varID = $(this).attr('id');
				$(this).removeClass('btn-default');
				$(this).addClass('btn-warning');
				$(this).children('i').removeClass('fa-check');
				$(this).children('i').addClass('fa-remove');
				$baris = $(this).closest('tr');
				$baris.addClass('terpilih');
				
				
				$.ajax({
					type:"POST",
					url: "<?php echo site_url('gakin/lembaga_pilih_indikator/');?>", 
					data:"id="+ $varID ,
					success: function(result){
						$hasil = result;
						console.log($hasil);
						if($hasil == 1){
							console.log('sampai disini');

						}else{
							console.log('tidak sampai disini');
						}
					}
				});

			});
		}
		if($cuthat){
			$cuthat.click(function(){
				$varID = $(this).attr('id');
				$(this).removeClass('btn-warning');
				$(this).addClass('btn-default');
				$(this).children('i').removeClass('fa-remove');
				$(this).children('i').addClass('fa-check');
				$baris = $(this).closest('tr');
				$baris.removeClass('terpilih');
				
				
				$.ajax({
					type:"POST",
					url: "<?php echo site_url('gakin/lembaga_pilih_indikator/');?>", 
					data:"rem=1&id="+ $varID ,
					success: function(result){
						$hasil = result;
						console.log($hasil);
						if($hasil == 1){
							console.log('sampai disini');

						}else{
							console.log('tidak sampai disini');
						}
					}
				});

			});
		}
	});
	
</script>

<?php

$this->load->view('siteman/siteman_footer');
