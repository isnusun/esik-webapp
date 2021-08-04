<?php $this->load->view('pubs/header'); ?>
	<div id="content">
		<div class="container">
			<section class="bar">
				<div class="row">
					<div class="col-md-12">
						<div class="heading">
						<h2><?php echo $indikator_one['label']; ?></h2>
						</div>
						<p class="lead">Distribusi Data Berbasis Indikator</p>
					</div>
				</div>
			</section>
			<section class="bar mt-0">
				<div class="box-body">
				<?php 
				// echo var_dump($num_responden);
				?>
					<table class="table table-bordered table-responsives datatables">
					<thead><tr>
						<th rowspan="2" colspan="2">Indikator / Opsi</th>
						<th colspan="<?php echo count($periodes['periode'])?>">Data</th>
					</tr><tr>
					<?php 
					foreach($periodes['periode'] as $periode){
						echo "<th>".$periode['nama']."</th>";
					}
					// echo var_dump($bdt_indikator);
					?>
					</tr></thead>
					<tbody>
					<?php
					$progres_chart = array();
					$pcdata = array();
					// foreach($bdt_indikator as $key=>$rs){
					$rs = $bdt_indikator[$indikator_one['nama']];
						echo "<tr>";
						if($rs['opsi']){
							echo "<td colspan='2'><a href='".site_url('backend/pbdt/indikator_detail/'.$responden.'/'.$rs['nama'])."/?kode=".$varKode."'>".$rs['label']."</a></td>";
						}else{
							echo "<td colspan='2'>".$rs['label']."</td>";
						}
						foreach($periodes['periode'] as $periode){
							echo "<td></td>";
						}
						echo "</tr>";
						if($rs['opsi']){
							if(is_array($rs['opsi'])){
								$seri = array();
								foreach($rs['opsi'] as $o=>$op){
									echo "<tr>
										<td style='width:20px;'>".$op['nama']."</td>
										<td style='padding-left:30px;'>".$op['label']."</td>";
										$n=0;
										foreach($periodes['periode'] as $periode){
											$selected = ($n==0)? "true":"false";
											$nilai = 0;
											if(array_key_exists($periode['id'],$num_responden)){
												if(is_array($num_responden[$periode['id']][$rs['nama']])){
													if(array_key_exists($op['nama'],$num_responden[$periode['id']][$rs['nama']])){
														$nilai = $num_responden[$periode['id']][$rs['nama']][$op['nama']];
													}
												}
	
											}
											if($n==0){
												$seri[$periode['id']][] = array(
													'name'=>$op['label'],
													'y'=>$nilai,
													'sliced'=>$selected,
													'selected'=>$selected,
												);
											}else{
												$seri[$periode['id']][] = array(
													'name'=>$op['label'],
													'y'=>$nilai);
											}
											$n++;
											// $nilai = ($num_responden[$periode['id'][$rs['nama']]])? $num_responden[$periode['id'][$rs['nama']]][$op['nama']]: 0;
											echo "<td class='angka'>".number_format($nilai,0)."</td>";
											$pcdata[$o][] = $nilai;
										}
										echo"
									</tr>";
									$progres_chart['series'][] = array(
										'name'=>$op['label'],
										'data'=>$pcdata[$o]
									);
								}
							}
						}
					// }
					?>
					</tbody>
					</table>
					<div class="bar">
						<div class="google-maps">
							<div id="highchart_indikator_progres_opsi"></div>
						</div>
					</div>
					<div class="bar">
						<div class="row">
							<?php 
							foreach($periodes['periode'] as $periode){
								$progres_chart['xAxis'][] = $periode['nama'];
								echo '
								<div class="col-md-6">
									<div class="google-maps">
										<div id="highchart_indikator_'.$periode['id'].'"></div>
									</div>
								</div>';
							}
							?>
						</div>
					</div>
				</div>
			</div>				
		</section>
	</div>
<!-- footer section -->
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>highcharts.js"></script>
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>modules/exporting.js"></script>
<script src="<?php echo base_url("assets/plugins/highcharts/"); ?>modules/offline-exporting.js"></script>
<script>
	<?php 
	foreach($periodes['periode'] as $periode){
		?>
		Highcharts.chart('<?php echo 'highchart_indikator_'.$periode['id']; ?>', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: 'Data Responden atas Indikator <?php echo $indikator_one['label'] ." Periode ".$periode['nama'];?>'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					},
					showInLegend: true
				}
			},
			series: [{
				name: 'Opsi Jawaban',
				colorByPoint: true,
				data: <?php echo json_encode($seri[$periode['id']],JSON_NUMERIC_CHECK);?>
			}]
		});

		<?php 
	}
	?>

	Highcharts.chart('highchart_indikator_progres_opsi', {
		chart: {
			type: 'line'
		},
		title: {
			text: '<?php echo $indikator_one['label'];?>'
		},
		xAxis: {
			categories: <?php echo json_encode($progres_chart['xAxis']);?>
		},
		yAxis: {
			title: {
				text: 'Jumlah Responden'
			}
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true
				},
				enableMouseTracking: false
			}
		},
		series: <?php echo json_encode($progres_chart['series'],JSON_NUMERIC_CHECK);?>
	});	
</script>	
<?php 
$this->load->view('pubs/footer');