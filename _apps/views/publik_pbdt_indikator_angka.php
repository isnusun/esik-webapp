<?php 
/*
 * publik_beranda.php
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

$this->load->view('header');
$this->load->view('navbar');
?>
	<!-- What is -->
	<div id="whatis" class="content-section-b" style="border-top: 0">
		<div class="container">
		<section class="content">
			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="box box-primary box-solid">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-check-square-o fa-fw"></i> DESIL</a></h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div><!-- /.box-tools -->
						</div>
						<div class="box-body">
							<ul class="nav nav-stacked">
							<?php 
							foreach($desil as $d=>$des){
								echo "<li><a href=\"#".$d."\">".$des[2]."</a></li>";
							}
							?>
							</ul>
						</div>
					</div>

					<!--box filter-->
					<div class="box box-primary box-solid">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-check-square-o fa-fw"></i> Indikator PBDT</a></h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div><!-- /.box-tools -->

						</div>
						<div class="box-body">

							<ul class="nav nav-pills nav-stacked">
								<?php
								$i = 1;
								$n = count($pbk_kategori);
								foreach($pbk_kategori as $k=>$cat){
									$strIn = ($i == 1) ? "in":"";
									echo "<li class=\"header\">".$cat['nama']."</li>";
									foreach($pbk_param[$k] as $key=>$item){
										$strA = ($item['id'] == $indikator['id'])? "class=\"active\"":"";
										echo "<li ".$strA."><a href=\"".site_url('dlmangka/pbdt/indikator/'.$varKode.'/'.$item['id'].'/'.fixNamaUrl($item['nama']))."\">".$item['nama']."</a></li>";
									}
									$i++;
								}
								
								?>
							</ul>

						</div>
					</div>
					
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="box box-primary box-solid">
						<div class="box-header">
							<h3 class="box-title"><?php echo $boxTitle; ?></h3>
						</div>
						<div class="box-body">

					<ol class="breadcrumb">
					<?php
					if(is_array($alamat)){
						foreach($alamat as $key=>$item){
							echo "<li><a href=\"".site_url('dlmangka/pbdt/indikator/'.$item['kode'].'/'.$indikator['id'].'/'.fixNamaUrl($indikator['nama']))."\">".$item['nama']."</a></li>";
						}
					}
					?>
					</ol>
					<?php

					foreach($desil as $d=>$des){
						?>
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title"><?php echo $des[2];?> | <small><?php echo $boxTitle; ?></small></h3>
							</div>
							<div class="box-body">
								<!--tabular-->
								<?php
								echo "<table class=\"table table-responsive datatables\">
								<thead><th>#</th><th>Wilayah / Data</th>
								<th>Maksimal</th>
								<th>Minimal</th>
								<th>Rerata</th>
								<th>Modus</th>
								</thead><tbody>";
								$nomer = 1;
								$data = $data_kelas[$d];
								foreach($sub_wilayah as $key=>$item){
									$v = $data[$key];
									echo "<tr><td class=\"angka\">".$nomer."</td>
									<td><a href=\"".site_url('dlmangka/pbdt/indikator/'.$key.'/'.$indikator['id'].'/'.fixNamaUrl($indikator['nama']))."\">".$item['nama']."</a></td>
									<td class=\"angka\">".$v['max']."</td>
									<td class=\"angka\">".$v['min']."</td>
									<td class=\"angka\">".number_format($v['rerata'],2)."</td>
									<td class=\"angka\">".$v['modus']."</td>
									</tr>";
									$nomer++;
								}
								
								echo "</tbody>
								</table>";
								?>
							</div>
						</div>
						<?php
					}
					?>
					
						
						
						
						</div>
					</div>
					<!--/box-->
				</div>
			</div>



			
		</section>
		</div>
	</div>


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
        "bPaginate": false,
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

$this->load->view('footer');
