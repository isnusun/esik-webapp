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
					<h3 class="box-title">Query Data Penduduk (Anggota Rumah Tangga) </a></h3>
					<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="<?php echo $collapse?>"><i class="fa fa-minus"></i></button>
                    <div class="btn-group">
						<button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-sliders fa-fw"></i>Pilih Periode Pendataan</button>
						<ul class="dropdown-menu" role="menu">
							<?php 
							foreach($periodes['periode'] as $p){
								echo '<li><a href="'.site_url('backend/pbdt/').'?periode='.$p['id'].'&amp;kode='.$varKode.'">'.$p['nama'].'</a></li>';
							}
							?>
							<li class="divider"></li>
							<li><a href="#">Separated link</a></li>
						</ul>
                    </div>
                    <button class="btn btn-warning"><i class="fa fa-clock-o fa-fw"></i>Periode <?php echo $periodes['periode'][$periode]['nama']; ?></button>
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
					<form action="<?php echo site_url('backend/pbdt/query_art'); ?>" method="POST" role="form" class="formular">
						<div class="box box-solid box-primary <?php echo $collapse;?>">
							<div class="box-header with-border">
								<h3 class="box-title">Formulir Check Indikator</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div class="row">
								<?php 
								$i = 1;
								foreach($indikator as $key=>$rs){
									if(($rs['jenis'] == 'pilihan') || ($rs['jenis'] == 'angka')){

										echo '
										<div class="col-md-4">
											<div class="box box-default">
												<div class="box-header with-border">'.$rs['label'].'</div>
												<div class="box-body">
													<fieldset class="kotak">
														<legend>'.$rs['label'].'</legend>
														<div class="form-group">';
														// '.$rs['nourut'].'. 
														if($rs['jenis'] == 'pilihan'){
															foreach($rs['opsi'] as $op){
																$op_name = $rs['nama'];
																$strC = "";
																echo '
																<div class="checkbox">
																<label><input type="checkbox" name="'.$op_name.'['.$op['nama'].']" value="'.$op['nama'].'" '.$strC.'/> '.$op['label'].'</label>
																</div>';

															}
														}elseif($rs['jenis'] == 'angka'){
															$val_min_name = $rs['nama']."_min";
															$val_max_name = $rs['nama']."_max";
															// $val_min_name = 0;
															// $val_max_name = 0;

															echo '<div>
															<label class="label-control">Nilai Minimal ( >= ) </label>
															<input type="text" class="form-control" id="'.$val_min_name.'" name="'.$val_min_name.'" value="'.@$_POST[$val_min_name].'"/>
															<label>Nilai Maksimal  ( <= )</label>
															<input type="text" class="form-control validate[condRequired['.$val_min_name.']]" name="'.$val_max_name.'" value="'.@$_POST[$val_max_name].'"/>
															</div>';
														}else{
															echo "<div>Isian Text</div>";
														}
														echo '</div>
													</fieldset>
												</div>
											</div>							
										</div>';
										if(fmod($i,3)==0){
											echo '</div><div class="row">';
										}
										$i++;
									}
								}
								?>
								</div>
								<div class="box-footer">
									<input type="hidden" name="periode_id" value="<?php echo $periode;?>" />
									<button type="reset" class="btn btn-default pull-right">Reset <i class="fa fa-refresh"></i></button>
									<button type="submit" class="btn btn-primary pull-right">Proses <i class="fa fa-send"></i></button>
								</div>
							</div>
						</div>
					</form>

					<?php
					if($_REQUEST){
						$query = siteman_crypt($query_string,'d');
						// $params = siteman_crypt($_GET['param'],'d');
						$pre_qs = explode('&',$query);
						$string_query = array();
						foreach($pre_qs as $str){
							$d = explode('=',$str);
							if(!empty($d[1])){
								$string_query[$d[0]][]=$d[1];
							}
						}
						// echo var_dump($string_query);
						echo "
						<h3>Data Berbasis Indikator</h3>
						<fieldset class=\"kotak\">
							<legend>Indikator Terpilih: </legend>
							<div>
							<ol>
							";
							foreach($indikator_aktif as $p){
								$op_name = $p;
								echo "<li><label>".$param_pilihan[$p]['label']."</label>";
								if($param_pilihan[$p]['jenis'] == 'pilihan'){
									foreach($string_query[$op_name] as $nilai){
										echo "<br /><i class=\"fa fa-check fa-fw\"></i>".$param_pilihan[$p]['opsi'][$nilai]['label'];
									}
								}elseif($param_pilihan[$p]['jenis'] == 'angka'){
									$param_min_name = $p."_min";
									$param_max_name = $p."_max";
									$val_min_name = $string_query[$param_min_name][0];
									$val_max_name = $string_query[$param_max_name][0];
									if($val_max_name < $val_min_name){
										echo "<br />Nilai lebih dari ".$val_min_name;
									}else{
										echo "<br />Nilai antara ".$val_min_name." dan ".$val_max_name;
									}

								}else{
									echo "Text";
		
								}
								echo "</li>";
							}
							echo "
							</ol>
							</div>						
						</fieldset>
						";
						if($dataset){
							$paging = $dataset['paging'];

							echo "
							<div class='callout callout-success'>
							<h4>Telah ditemukan sebanyak <strong>".$dataset['numrows']."</strong> baris data Penduduk/Anggota Rumah Tangga</h4>
							</div>";
							echo "
							<div class='scroll'>
							<table class='table table-responsive table-bordered datatables'>
							<thead>
								<tr>
									<th>#</th><th>Propinsi</th>
									<th>Kabupaten/Kota</th>
									<th>Kecamatan</th>
									<th>Desa/Kelurahan</th>
									<th>Alamat</th>
									<th>IDBDT</th>
									<th>NAMA</th>
									<th>NIK</th>
									<th>Umur</th>
									<th>Percentile</th>
								</tr>
							</thead>
							<tbody>";
							$nomer=(($paging['page'] -1 ) * $app['limit_tampil']) +1;
							foreach($dataset['data'] as $key=>$rs){
								echo "<tr><td class='angka'>".number_format($nomer,0)."</td>
								<td>".$rs['propinsi']."</td>
								<td>".$rs['kabupaten']."</td>
								<td>".$rs['kecamatan']."</td>
								<td>".$rs['desa']."</td>
								<td>".$rs['alamat']."</td>
								<td><a href='".site_url('backend/pbdt/rts_detail/').$rs['idbdt']."'>".$rs['idbdt']."</a></td>
								<td><a href='".site_url('backend/pbdt/art_detail/').$rs['idartbdt']."'>".$rs['nama']."</a></td>
								<td><a href='".site_url('backend/pbdt/art_detail/').$rs['idartbdt']."'>".$rs['nik']."</a></td>
								<td class='angka'>".$rs['umur']."</td>
								<td>".$rs['percentile']." / ["._desil_mana($rs['percentile'])."]</td>
								</tr>";
								$nomer++;
							}
							echo "</tbody>
							</table>
							</div>";
							if($paging['page_total']> 1){
								// $query = http_build_query($post_param);
								// $query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $query);
								echo "<div><ul class='pagination'>";
								if($paging['page_prev'] > 1){
									echo "<li><a href='".site_url('backend/pbdt/query_art/?p=1&amp;param='.$query_string)."'><i class='fa fa-fast-backward'></i></a></li>
									<li><a href='".site_url('backend/pbdt/query_art/?p='.$paging["page_prev"].'&amp;param='.$query_string)."'><i class='fa fa-backward'></i></a></li>";
								}
								
								$spage = 5;
								if($paging['page'] +3 < $spage){
									$apage = 1;
									$epage = $spage;
								}else{
									$apage = (($paging['page']-2) > 0) ? $paging['page']-2:1;
									$epage = (($paging['page']+3) > $paging['page_total']) ? $paging['page_total']:$paging['page']+3;
								}
								for($i=$apage;$i<$epage;$i++){
									echo "<li><a href='".site_url('backend/pbdt/query_art/?param='.$query_string)."&amp;p=".$i."'>".$i."</a></li>";
								}
								if(($paging['page_total'] - $epage) > 3){
									echo "<li><a href='".site_url('backend/pbdt/query_art/?param='.$query_string)."&amp;p=".$paging['page_next']."'><i class='fa fa-ellipsis-h'></i></a></li>
									<li><a href='".site_url('backend/pbdt/query_art/?param='.$query_string)."&amp;p=".$paging['page_total']."'>".$paging['page_total']."</a></li>";
								}

								if($paging['page_next'] < $paging['page_total']){
									echo "<li><a href='".site_url('backend/pbdt/query_art/?param='.$query_string)."&amp;p=".$paging['page_next']."'><i class='fa fa-forward'></i></a></li>
									<li><a href='".site_url('backend/pbdt/query_art/?param='.$query_string)."&amp;p=".$paging['page_total']."'><i class='fa fa-fast-forward'></i></a></li>";
								}else{
									echo "<li><a href='".site_url('backend/pbdt/query_art/?param='.$query_string)."&amp;p=".$paging['page_total']."'><i class='fa fa-fast-forward'></i></a></li>";
								}
								echo "</ul></div>";
							}
						}else{
							echo "
							<div class='callout callout-danger'>
								<h4 class=''>Tidak ditemukan data dengan paramater indikator tersebut diatas</h4>
								<p>Silakan dicoba paramater yg lain</p>
							</div>";

						}


					}
					?>
				</div>
			</div>

		</section>
	</div>
	
<!-- footer section -->
<!-- Validate CSS -->
<link href="<?php echo base_url('assets/plugins/'); ?>validation-engine/validationEngine.jquery.css" rel="stylesheet" type="text/css">

<script src="<?php echo base_url("assets/plugins/"); ?>validation-engine/jquery.validationEngine.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>validation-engine/languages/jquery.validationEngine-id.js"></script>

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
	var formular = $("form.formular");
	if(formular){
		$("form.formular").validationEngine();
	}

    $('table.datatables').DataTable( {
				"language": {
                "url": "<?php echo base_url("assets/plugins/"); ?>datatables/datatables_ID.js"
            },
		'paging':false,
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

<?php


$this->load->view('siteman/siteman_footer');
