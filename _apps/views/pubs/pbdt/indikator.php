<?php $this->load->view('pubs/header');
?>
      <div id="content">
        <div class="container">
          <section class="bar">
            <div class="row">
              <div class="col-md-12">
                <div class="heading">
                  <h2>Indikator Basis Data Terpadu</h2>
                </div>
                <p class="lead"><?php echo $pageDescription;?></p>
              </div>
            </div>
          </section>
        </div>

		<section>
			<div class="container">

		<?php 
		if($responden=='rts'){
			if($num_responden){
				?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Telusur Data RTS berbasis Indikator</h3>
				</div>
				<div class="box-body">
				<?php 
				// echo var_dump($num_responden);
				?>
					<table class="table table-bordered table-responsives datatables">
					<thead><tr><th rowspan="2">#</th>
						<th rowspan="2" colspan="2">Indikator / Opsi</th>
						<th colspan="<?php echo count($periodes)?>">Data</th>
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
					$nomer = 1;
					foreach($bdt_indikator as $key=>$rs){
						if($rs['jenis'] == 'pilihan'){
							echo "<tr><td class='angka'>".$rs['nourut']."</td>";
							if($rs['opsi']){
								echo "<td colspan='2'><a href='".site_url('publik/indikator_detail/rts/'.$rs['nama'])."/?kode=".$varKode."'>".$rs['label']."</a></td>";
							}else{
								echo "<td colspan='2'>".$rs['label']."</td>";
							}
							foreach($periodes['periode'] as $periode){
								echo "<td></td>";
							}
							echo "</tr>";
							if($rs['opsi']){
								if(is_array($rs['opsi'])){
									foreach($rs['opsi'] as $o=>$op){
										echo "<tr><td></td>
											<td style='width:20px;'>".$op['nama']."</td>
											<td style='padding-left:30px;'>".$op['label']."</td>";
											foreach($periodes['periode'] as $periode){
												$nilai = 0;
												if(is_array($num_responden[$periode['id']][$rs['nama']])){
													if(array_key_exists($op['nama'],$num_responden[$periode['id']][$rs['nama']])){
														$nilai = $num_responden[$periode['id']][$rs['nama']][$op['nama']];
													}
												}
												// $nilai = ($num_responden[$periode['id'][$rs['nama']]])? $num_responden[$periode['id'][$rs['nama']]][$op['nama']]: 0;
												echo "<td class='angka'><a href='".site_url("publik/bdt_responden_by_opsi/?periode_id=".$periode['id']."&amp;indikator=".$key."&amp;opsi=".$o."&amp;kode=".$varKode)."'>".number_format($nilai,0)."</a></td>";
											}
											echo"
										</tr>";
									}
								}
							}
							$nomer++;
						}
					}
					?>
					</tbody>
					</table>
				</div>
			</div>				
				<?php
			}
		}elseif($responden=='art'){

		}
		
		?>
			</div><!--/container-->
        </section>
      </div>
      <!-- GET IT-->
      <div class="get-it">
        <div class="container">
          <div class="row">
            <div class="col-lg-8 text-center p-3">
              <h3>Anda memiliki ide, saran, kritik dan masukan untuk kami?</h3>
            </div>
            <div class="col-lg-4 text-center p-3">   <a href="<?php echo site_url('beranda/hubungikami');?>" class="btn btn-template-outlined-white">Sampaikan melalui formulir berikut ini</a></div>
          </div>
        </div>
      </div>
<?php 
$this->load->view('pubs/footer');