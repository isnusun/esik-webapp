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
					<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i><?php echo $boxTitle;?></h3>
				</div>
				<div class="box-body">
					<fieldset class="kotak">
						<legend>Formulir Periode Pemutakhiran Data</legend>
						<div>
							<form action="" method="POST" class="formular" role="form">
								<div class="form-group">
									<label class="control-label" for="nama">Nama Periode</label>
									<input type="text" class="form-control validate[required]" id="nama" name="nama" placeholder="Tuliskan Nama Periode"/>
								</div>
								<div class="form-group">
									<label class="control-label" for="ndesc">Keterangan</label>
									<textarea class="form-control validate[optional]" id="ndesc" name="ndesc" placeholder="Tuliskan Keterangan Periode">
									</textarea>
								</div>
								<div class="form-group">
									<label class="control-label" for="waktu">Masa Periode</label>
									<input type="text" class="form-control validate[required] dtpicker" id="rdate" name="rdate" placeholder="Pilih Rentang Waktu Periode"/>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
								</div>
							</form>
						</div>
					</fieldset>
					<?php
						echo "
						<table class=\"table table-responsive table-bordered\">
							<thead><tr><th>#</th>
								<th>Periode</th>
								<th colspan=\"2\">Masa Berlaku</th>
							</tr></thead>
							<tbody>";
							$nomer=0;
							foreach($periodes['periode'] as $key=>$item){
								$nomer++;
								echo "
								<tr>
								<td>".$nomer."</td>
								<td><h4>".$item['nama']."</h4>
									<div>".$item['ndesc']."</div>
								</td>
								<td>".$item['sdate']."</td>
								<td>".$item['edate']."</td>
								</tr>";
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
