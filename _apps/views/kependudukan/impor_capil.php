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
			<small><?php echo APP_TITLE;?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">PBDT</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			// $this->load->view('pbdt/pbdt_head');
			
			?>
			<!--box data indikator-->
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i><?php echo $boxTitle;?></h3>
				</div>
				<div class="box-body">
					<?php
					if($msg){
						echo "<div class=\"alert alert-success\">".$msg."</div>";
					}
					?>
					<fieldset class="kotak">
						<legend>Formulir Import Data Capil</legend>
						<div>
							<form action="<?php echo $form_action;?>" method="POST" class="formular" role="form" enctype="multipart/form-data">
								<div class="form-group">
									<label class="control-label" for="nama">Desa/Kelurahan</label>
									<select name="desa" class="form-control validate['required'] select2"  placeholder="Pilih Desa/Kelurahan">
									<?php 
									if($user['tingkat'] < 3){
										foreach($kecamatan as $k=>$kec){
											echo "<optgroup label=\"".$kec['nama']."\">";
											foreach($desa[$k] as $d=>$des){
												echo "<option value=\"".$des['kode']."\">".$des['nama']." - ".$kec['nama']."</option>";
											}
											echo "</optgroup>";
										}
									}else{
										echo "<option value=\"".$user['wilayah']."\">Desa/Kel. ".$user['wilayah_nama']."</option>";
									}
									?>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label" for="ndesc">Berkas .xls dari CAPIL</label>
									<input type="file" class="form-control validate[required] dtpicker" id="berkas" name="berkas" placeholder="Pilih Berkas"/>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>
								</div>
							</form>
						</div>
					</fieldset>


				</div>
			</div>
			<!--/box data-->
		</section>
	</div>
<!-- footer section -->
<?php

$this->load->view('siteman/siteman_footer');
