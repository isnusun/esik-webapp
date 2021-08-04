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
			<?php
			$this->load->view('pbdt/pbdt_head');
			
			?>
			<!--box data indikator-->
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i>Pengaturan Indikator dan Skoring Indikator RTS/KK</h3>
				</div>
				<div class="box-body no-padding">
					<?php
						echo "
						<table class=\"table table-responsive table-bordered\">
							<thead><tr><th>#</th>
								<th colspan=\"2\">Indikator /<br /> Kode dan Opsi Jawaban</th>
								<th>Bobot</th>
								<th>Skoring Jawaban</th>
							</tr></thead>
							<tbody>";
							$nomer=0;
							foreach($param as $key=>$item){
								$nomer++;
								echo "
								<tr>
								<td>".$nomer."</td>
								<td colspan=\"2\">".$item['nama']."</td>
								<td>
									<div class=\"editable editable_gakin\" id=\"gk_param__bobot__".$item['id']."\">".$item['bobot']."</div>
									</td>
								</tr>";
								if($item['jenis'] == 1){
									if(is_array($opsi)){
										if(array_key_exists($item['id'],$opsi)){
											foreach($opsi[$item['id']] as $o=>$rs){
												echo "<tr>
												<td>&nbsp;</td>
												<td>".$rs['opsi_kode']."</td>
												<td>".$rs['nama']."</td>
												<td></td>
												<td>
													<div class=\"editable editable_gakin\" id=\"gk_param_opsi__bobot__".$rs['id']."\">".$rs['bobot']."</div>
													</td>
												</tr>";
											}													
										}
									}
								}
							}

							echo "
							</tbody>
						</table>
						";
					?>

				</div>
			</div>
			<!--/box data-->
		</section>
	</div>
<!-- footer section -->
<script src="<?php echo base_url("assets/plugins/"); ?>jeditable/jquery.jeditable.mini.js"></script>
<script>
	$(document).ready(function() {
		var gke = $('.editable_gakin');
		$(gke).editable('<?php echo site_url('gakin/simpan_gakin');?>',{
			indicator : '<img src="<?php echo base_url('assets/img/ajax-loader.gif');?>">',
			tooltip   : 'Klik untuk mengisi data...',
			event			: 'dblclick',
			submit : 'Simpan'
		});		
	});
</script>

<?php

$this->load->view('siteman/siteman_footer');
