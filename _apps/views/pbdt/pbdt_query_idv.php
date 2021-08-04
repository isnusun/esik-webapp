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
			<small>TKPKD</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">PBDT</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
		<form action="<?php echo site_url('pbdt/customquery_idv')?>" method="POST" role="form">
			<div class="box box-primary <?php echo $collapse;?>" id="query">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-sliders-h fa-fw"></i><?php echo $boxTitle;?></h3>
					<div class="box-tools pull-right">
						<button type="reset" class="btn btn-default pull-right"><i class="fa fa-refresh"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
					<?php
					$i=0;
					// echo var_dump($opsi);
					$params = array();
					foreach($param as $key=>$item){
						$nomer= $i+1;
						echo "
						<div class=\"col-md-4\">
							<fieldset class=\"kotak\">
								<legend>".$nomer.". ".$item['nama']."</legend>
								<div class=\"form-group\">";
								if($item['jenis'] == 1){
									foreach($opsi[$item['id']] as $o=>$op){
										$op_name = "param_".$item['id'];
										// $strC = (@$_POST[$op_name][$op['id']]) ? "checked=\"checked\"":"";
										$strC = "";
										echo "
										<div class=\"checkbox\">
										<label><input type=\"checkbox\" name=\"".$op_name."[".$o."]\" value=\"".$op['opsi_kode']."\" ".$strC."/> ".$op['nama']."</label>
										</div>";

									}
								}elseif($item['jenis'] == 2){
									$val_min_name = "param_".$item['id']."_min";
									$val_max_name = "param_".$item['id']."_max";

									echo "<div>
									<label class=\"label-control\">Nilai Minimal ( >= )</label>
									<input type=\"text\" class=\"form-control\" name=\"".$val_min_name."\" value=\"".@$_POST[$val_min_name]."\"/>
									<label>Nilai Maksimal ( <= )</label>
									<input type=\"text\" class=\"form-control\" name=\"".$val_max_name."\" value=\"".@$_POST[$val_max_name]."\"/>
									</div>";
								}else{
									echo "<div>Isian Text</div>";
								}
								echo "</div>
							</fieldset>
						</div>
						";
						$i++;
						if(fmod($i,3) == 0){
							echo "</div><div class=\"row\">";
						}
					}
					
					?>
					</div>
				</div>
				<div class="box-footer">
					<button type="reset" class="btn btn-default pull-right">Reset <i class="fa fa-refresh"></i></button>
					<button type="submit" class="btn btn-primary pull-right">Proses <i class="fa fa-send"></i></button>
				</div>
			</div>
				</form>
			<div class="" id="hasil">
				<?php
				if(@$_POST){
					// echo var_dump($opsi);
					echo "
					<div class=\"box box-primary box-solid\">
						<div class=\"box-header\">
							<h3 class=\"box-title\">Hasil Query Data</h3>
						</div>
						<div class=\"box-body\">
							<fieldset class=\"kotak\">
								<legend>Indikator Terpilih: </legend>
								<div>
								<ol>
								";
								// echo var_dump($indikator_aktif);
								foreach($indikator_aktif as $p){
									$op_name = "param_".$p;
									echo "<li><label>".$param_pilihan[$p]['nama']."</label>";
									if($param_pilihan[$p]['jenis'] == 1){
										foreach($_POST[$op_name] as $nilai){
											echo "<br /><i class=\"fa fa-check fa-fw\"></i>".$opsi[$p][$nilai]['nama'];
										}
									}elseif($param_pilihan[$p]['jenis'] == 2){
										$min = $op_name."_min";
										$max = $op_name."_max";
										echo "<br />Nilai antara ".$_POST[$min]." dan ".$_POST[$max];
									}else{
										echo "Text";
			
									}
									echo "</li>";
								}
								echo "</ol>
								<div>
								";
								if($recordset){
									$column = array();
									$column[] = "No";
									foreach($recordset as $rs){
										foreach($rs as $key=>$col){
											$column[] = $key;
										}
									}
									$column = array_unique($column);
									
									echo "
									<div class=\"alert alert-success alert-dismissible\">
										<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
										<h4><i class=\"icon fa fa-check\"></i> Ditemukan <strong>".count($recordset)."</strong> Data!</h4>
									</div>
									<div class=\"scroll\">

									<table class=\"table table-responsive table-bordered datatables\">
									<thead><tr>";
									foreach($column as $key=>$col){
										echo "<th>".$col."</th>";
									}
									echo "</tr></thead><tbody>";
									$nomer=1;
									foreach($recordset as $rs){
										echo "<tr><td class=\"angka\">".$nomer."</td>";
										// $nokk = $rs['NO Kartu Keluarga'];
										foreach($rs as $key=>$td){
											if(($key == 'RID_RumahTangga') || ($key == 'Kepala Rumah Tangga')){
												echo "<td><a href=\"".site_url('pbdt/detail_rts/'.$rs['RID_RumahTangga'])."\" target=\"_blank\">".$td."</a></td>";
											}elseif(($key == 'NIK') || ($key == 'Nama Lengkap')){
												echo "<td><a href=\"".site_url('pbdt/detail_idv/'.$rs['NIK'])."\" target=\"_blank\">".$td."</a></td>";
											}else{
												echo "<td>".$td."</td>";
											}
										}
										echo "</tr>";
										$nomer++;
									}
									echo "</tbody>
									</table>
									</div>
									";
								}else{
									echo "
									<div class=\"alert alert-danger alert-dismissible\">
										<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
										<h4><i class=\"icon fa fa-check\"></i> Maaf, data dengan kriteria seperti diatas <strong>TIDAK DITEMUKAN</strong>!</h4>
									</div>
									";
								}

								echo "</div>
								</div>
							</fieldset>
						</div>
					</div>
					";
				}
				?>
			</div>
		</section>
	</div>
<!-- footer section -->
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
				$('table.datatables').DataTable( {
						"language": {
										"url": "<?php echo base_url("assets/plugins/"); ?>datatables/datatables_ID.js"
								},
						dom: 'Bfrtip',
						responsive: true,
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
								columns: ':lt(4)'
							}
						]
				} );
		} );
		</script>		
<?php

$this->load->view('siteman/siteman_footer');
