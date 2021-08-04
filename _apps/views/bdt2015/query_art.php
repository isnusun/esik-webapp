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
			$this->load->view('bdt2015/_header');
			?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Query Data Anggota Rumah Tangga PBDT 2015</a></h3>
					<div class="box-tools pull-right">
                    	<button class="btn btn-box-tool" data-widget="<?php echo $collapse; ?>"><i class="fa fa-minus"></i></button>
					</div>

				</div>
				<div class="box-body">
					<ol class="breadcrumb">
						<?php 
						if($alamat_bc){
							foreach($alamat_bc as $key=>$rs){
								if(strlen($user['wilayah']) <= strlen($rs['kode'])){
									echo "<li class='nav-item'><a href='".site_url('backend/bdt2015/query_rts/?kode='.$rs['kode'])."'>".$rs['nama']."</a></li>";
								}else{
									echo "<li class='nav-item'>".$rs['nama']."</li>";
								}
							}
						}
						?>
					</ol>
					<form action="<?php echo site_url('backend/bdt2015/query_art'); ?>" method="POST" role="form" class="formular">
						<div class="box box-solid box-primary <?php echo $collapse;?>">
							<div class="box-header with-border">
								<h3 class="box-title">Formulir Query Indikator Anggota Rumah Tangga</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div class="row">
								<?php 
								// echo var_dump($indikator);
								$i = 1;
								foreach($indikator as $key=>$rs){
									if(($rs['jenis'] == 'pilihan') ||($rs['jenis'] == 'angka')){
										echo '
										<div class="col-md-4">
											<div class="box box-default">
												<div class="box-header with-border">'.$rs['nama'].'</div>
												<div class="box-body">
													<fieldset class="kotak">
														<legend>'.$rs['nama'].'</legend>
														<div class="form-group">';
														if($rs['jenis'] == 'pilihan'){
															foreach($rs['opsi'] as $o=>$op){
																$op_name = str_replace(' ','_',$rs['nama']);
																$strC = "";
																echo '
																<div class="checkbox">
																<label><input type="checkbox" name="'.$op_name.'['.$o.']" value="'.$o.'" '.$strC.'/> '.$op.'</label>
																</div>';

															}
														}elseif($rs['jenis'] == 'angka'){
															$val_min_name = str_replace(' ','_',$rs['nama'])."__min";
															$val_max_name = str_replace(' ','_',$rs['nama'])."__max";

															echo '<div>
															<label class="label-control">Nilai Minimal ( >= ) </label>
															<input type="text" class="form-control validate[optional,custom[number]]" id="'.$val_min_name.'" name="'.$val_min_name.'" value="'.@$_POST[$val_min_name].'"/>
															<label>Nilai Maksimal  ( <= )</label>
															<input type="text" class="form-control  validate[custom[number],condRequired['.$val_min_name.']]" id="'.$val_max_name.'" name="'.$val_max_name.'" value="'.@$_POST[$val_max_name].'"/>
															</div>';
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
								$key = $d[0];
								$key = str_replace('__min','',$key);
								$key = str_replace('__max','',$key);
								$key = str_replace('_',' ',$key);
								$string_query[$key][]=$d[1];
							}
						}

						// $colspan = count($desil) * 2;
						// echo var_dump($data_rts);
						// $colspan = count($periodes['periode']);
						// echo var_dump($string_query);
						echo "
						<h3>Data Berbasis Indikator</h3>
						<fieldset class=\"kotak\">
							<legend>Indikator Terpilih: </legend>
							<div>
							<ol>
							";
							foreach($indikator_aktif as $p){
								$op_name = str_replace('_',' ',$p);
								echo "<li><label>".$param_pilihan[$p]['nama']." :: ".$op_name."</label>";
								// echo "<pre>";
								// echo var_dump($string_query[$op_name]);
								// echo "</pre>";

								if($param_pilihan[$p]['jenis'] == 'pilihan'){
									foreach($string_query[$op_name] as $nilai){
										echo "<br /><i class=\"fa fa-check fa-fw\"></i>".$param_pilihan[$p]['opsi'][$nilai];
									}
								}elseif($param_pilihan[$p]['jenis'] == 'angka'){
									if(is_array($string_query[$op_name])){
										$val= array();
										foreach($string_query[$op_name] as $nilai){
											$val[] = $nilai;
										}
									}
									echo "<br />Nilai antara ".$val[0]." dan ".$val[1];
								}else{
									echo "Teks tidak bisa diquery";
		
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
							<h4>Telah ditemukan Data sebanyak <strong>".$dataset['numrows']."</strong> Warga</h4>
							</div>";
							echo "							
							<table class='table table-responsives table-bordered datatables'>
							<thead>
								<tr>
									<th>#</th><th>Propinsi</th>
									<th>Kabupaten/Kota</th>
									<th>Kecamatan</th>
									<th>Desa/Kelurahan</th>
									<th>Alamat</th>
									<th>NURT</th>
									<th>Nama</th>
									<th>NIK</th>
									<th>Desil</th>
								</tr>
							</thead>
							<tbody>";
							$nomer=$paging['offset'];
							foreach($dataset['data'] as $key=>$rs){
								$nomer++;
								echo "<tr><td class='angka'>".$nomer."</td>
								<td>".$rs['Provinsi']."</td>
								<td>".$rs['Kabupaten/Kota']."</td>
								<td>".$rs['Kecamatan']."</td>
								<td>".$rs['Desa/Kelurahan']."</td>
								<td>".$rs['Alamat']."</td>
								<td><a href='".site_url('backend/bdt2015/rts_detail/').$rs['Nomor Urut Rumah Tangga']."'>".$rs['Nomor Urut Rumah Tangga']."</a></td>
								<td><a href='".site_url('backend/bdt2015/art_detail/').$rs['id']."'>".$rs['Nama']."</a></td>
								<td><a href='".site_url('backend/bdt2015/art_detail/').$rs['id']."'>".$rs['NIK']."</a></td>
								<td>".$rs['Status Kesejahteraan']."</td>
								</tr>";
							}
							echo "</tbody>
							</table>";
							if($paging['page_total']> 1){
								// $query = http_build_query($post_param);
								// $query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $query);
								echo "<div><ul class='pagination'>";
								if($paging['page_prev'] > 1){
									echo "<li><a href='".site_url('backend/bdt2015/query_art/?param='.$query_string)."&amp;p=1'><i class='fa fa-fast-backward'></i></a></li>
									<li><a href='".site_url('backend/bdt2015/query_art/?param='.$query_string)."&amp;p=".$paging['page_prev']."'><i class='fa fa-backward'></i></a></li>";
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
									echo "<li><a href='".site_url('backend/bdt2015/query_art/?param='.$query_string)."&amp;p=".$i."'>".$i."</a></li>";
								}

								if($paging['page_next'] < $paging['page_total']){
									echo "<li><a href='".site_url('backend/bdt2015/query_art/?param='.$query_string)."&amp;p=".$paging['page_next']."'><i class='fa fa-forward'></i></a></li>
									<li><a href='".site_url('backend/bdt2015/query_art/?param='.$query_string)."&amp;p=".$paging['page_total']."'><i class='fa fa-fast-forward'></i></a></li>";
								}else{
									echo "<li><a href='".site_url('backend/bdt2015/query_art/?param='.$query_string)."&amp;p=".$paging['page_total']."'><i class='fa fa-fast-forward'></i></a></li>";
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
