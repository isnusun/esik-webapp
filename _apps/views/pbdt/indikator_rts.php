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
			<small>Verifikasi dan Validasi Data <?php echo $user['wilayah_nama']; ?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">VeriVali Data</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			$this->load->view('pbdt/_header');
			?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('verval');?>">Telusur Data RTS berbasis Indikator <?php echo $user['wilayah_nama']?></a></h3>
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
					foreach($bdt_indikator as $key=>$rs){
						echo "<tr><td class='angka'>".$rs['nourut']."</td>";
						if($rs['opsi']){
							echo "<td colspan='2'><a href='".site_url('backend/pbdt/indikator_detail/rts/'.$rs['nama'])."/?kode=".$varKode."'>".$rs['label']."</a></td>";
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
											if(array_key_exists($periode['id'],$num_responden)){
												if(is_array($num_responden[$periode['id']][$rs['nama']])){
													if(array_key_exists($op['nama'],$num_responden[$periode['id']][$rs['nama']])){
														$nilai = $num_responden[$periode['id']][$rs['nama']][$op['nama']];
													}
												}
											}
											// $nilai = ($num_responden[$periode['id'][$rs['nama']]])? $num_responden[$periode['id'][$rs['nama']]][$op['nama']]: 0;
											echo "<td class='angka'><a href='".site_url("backend/pbdt/rts_mana/?periode_id=".$periode['id']."&amp;indikator=".$key."&amp;opsi=".$o."&amp;kode=".$varKode)."'>".number_format($nilai,0)."</a></td>";
										}
										echo"
									</tr>";
								}
							}
						}
					}
					?>
					</tbody>
					</table>
				</div>
			</div>

		</section>
	</div>
<!-- footer section -->
<?php


$this->load->view('siteman/siteman_footer');
