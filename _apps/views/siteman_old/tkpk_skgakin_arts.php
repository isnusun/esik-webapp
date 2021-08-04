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
			<li class="active">SK GAKIN</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-8 col-sm-6 col-xs-12">
					<!-- akhir individual-->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">
								<span class="fa-stack">
								<i class="fa fa-square-o fa-stack-2x"></i>
								<i class="fa fa-info fa-stack-1x"></i>
								</span> Data SK Gakin Individual</a>
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
					<!--/box-->
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<!--box filter-->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">
								<span class="fa-stack">
								<i class="fa fa-square-o fa-stack-2x"></i>
								<i class="fa fa-info fa-stack-1x"></i>
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
										$strI = ($key==$periode_on)? "<i class=\"fa fa-check\"></i> ":"";
										echo "<a class=\"btn ".$strA."\" href=\"".site_url('skgakin/index/'.$kode.'/'.$key.'/')."\">".$strI.$item['nama']."</a></li>";
									}
									
									?>
							</div>
						</div>
					</div>
					<!--/periode-->

					
				</div>
			</div><!--/row-->

			<!-- Data -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">
						<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
						<i class="fa fa-info fa-stack-1x"></i>
						</span> <?php echo $pageTitle; ?></a>
					</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->

				</div>
				<div class="box-body">
					<?php 
					//echo var_dump($datane['sql']);
					if($datane){
						?>
						<table class="table table-responsive datatables">
							<thead><tr><th>#</th><th>Wilayah</th>
								<?php
								$kolom = $datane['cols']['kolom'];
								$sumVal = array();
								foreach($kolom as $key=>$item){
									$sumVal[$key] = 0;
									echo "<th>".$item."</th>";
								}
								?>
							</tr></thead>
							<tbody>
								<?php 
								$i = 1;
								$XAxis = "";
								$toGraph["title"] = $pageTitle;
								$toGraph["seri"] = array();
								$n = count($datane['data']);
								foreach($datane['data'] as $b=>$baris){
									$strKoma = ($i < $n) ? ",":"";
									$XAxis .= "'".$tingkatan[$baris['tingkat']]." ".$baris['nama']."'".$strKoma;
									echo "
									<tr><td class=\"angka\">".$i."</td>
										<td><a href=\"".site_url('skgakin/arts/'.$idQuery.'/'.$baris['kode'].'/')."\">".$tingkatan[$baris['tingkat']]." ".$baris['nama']."</a></td>";
										foreach($kolom as $key=>$item){
											$nilai = $baris[$key];
											$sumVal[$key] += $nilai;
											$toGraph["seri"][$key][$b] = $nilai;
											echo "<th class=\"angka\"><a href=\"".site_url('skgakin/arts_siapa/'.$idQuery.'/'.$baris['kode'].'/'.$key)."\">".number_format($nilai,0)."</a></th>";
										}
										echo "
									</tr>
									";
									$i++;
								}
								?>
							</tbody>
							<tfoot>
								<tr><th colspan="2" class="angka">JUMLAH</th>
								<?php
								foreach($kolom as $key=>$item){
									echo "<th class=\"angka\"><a href=\"".site_url('skgakin/arts_siapa/'.$idQuery.'/'.$kode.'/'.$key)."\">".number_format($sumVal[$key])."</a></th>";
								}
								?>
								</tr>
							</tfoot>
						</table>
						
						<div class="" id="bar_chart_container"></div>
						
						<?php
					}
					?>
					
				</div>
			</div>
			
			<!-- /Endof Data -->
			
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
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/highcharts.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/highcharts-more.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>highcharts/modules/exporting.js"></script>
<script>
	$(document).ready(function(){
		var bc_container = $('#bar_chart_container');
		if(bc_container){
			$('#bar_chart_container').highcharts({
				chart: {type: 'column'},
				title: {text: '<?php echo $toGraph["title"];?>'},
				subtitle: {text: '<?php echo $pageTitle ." ". $wilayah;?>'},
				xAxis: {
					categories: [ <?php 
					echo $XAxis;
					?>
					],
					crosshair: true			
				},        
				yAxis: {
					min: 0,
					title: {
						text: 'Populasi (Jiwa)'
					}
				},
				tooltip: {
					headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
					pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
							'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
					footerFormat: '</table>',
					shared: true,
					useHTML: true
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},	
				series: [
					<?php 
					$i=1;
					$n = count($kolom);
					foreach($kolom as $d=>$ds){
						$strKoma = ($i < $n) ? ",":"";
						echo "\n{
						name : '".$ds."',
						data : [";
						$x = 1;
						$y = count($toGraph['seri'][$d]);
						foreach($toGraph['seri'][$d] as $dt){
							$strC = ($x < $y) ? ",":"";
							echo $dt.$strC;
							$x++;
						}
						echo "]}".$strKoma;
					}

					?>				
				]
			});
		}
	});
</script>
<?php

$this->load->view('siteman/siteman_footer');
