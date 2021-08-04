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
			$this->load->view('bdt2015/_header');
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
					<thead><tr><th>#</th>
						<th colspan="2">Indikator / Opsi</th>
						<th>Data BDT 2015</th>
					</tr></thead>
					<tbody>
					<?php
					$nomer = 1;
					foreach($bdt_indikator['rts'] as $key=>$rs){
						// echo var_dump($rs);
						echo "<tr><td class='angka'>".$nomer."</td>";
						if($rs['opsi']){
							echo "<td colspan='2'><a href='".site_url('backend/bdt2015/indikator_detail/rts/'.$rs['id'])."/?kode=".$varKode."'>".$rs['nama']."</a></td>";
						}else{
							echo "<td colspan='2'>".$rs['nama']."</td>";
						}
						echo "<td></td>";
						echo "</tr>";
						if($rs['opsi']){
							if(is_array($rs['opsi'])){
								foreach($rs['opsi'] as $o=>$op){
									echo "<tr><td></td>
										<td style='width:20px;'>".$o."</td>
										<td style='padding-left:30px;'>".$op."</td>";
										
										$nilai = 0;
										if(is_array($num_responden[$rs['nama']])){
											if(array_key_exists($o,$num_responden[$rs['nama']])){
												$nilai = $num_responden[$rs['nama']][$o];
											}
										}
										echo "<td class='angka'><a href='".site_url("backend/bdt2015/rts_mana/?indikator=".$key."&amp;opsi=".$o."&amp;kode=".$varKode)."'>".number_format($nilai,0)."</a></td>
									</tr>";
								}
							}
						}
						$nomer++;
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
