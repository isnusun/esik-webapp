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
			<?php echo APP_TITLE;?>
			<small>Panel Konfigurasi</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Data Pengguna</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-8 col-sm-6 col-xs-12">
					
					<?php
					if($hasil){
						?>
						<div class="box box-primary box-solid">
							<div class="box-header">
								<h3 class="box-title">Berkas Data untuk <?php echo $data_desa; ?></h3>
							</div>
							<div class="box-body">
							<?php
							echo "<ul class=\"nav nav-pills nav-stacked\">";
							foreach($berkas as $key=>$item){
								echo "<li><a href=\"".base_url($item)."\"><i class=\"fa fa-download fa-fw\"></i> DATA ".strtoupper($key)." : ".str_replace("assets/uploads/","",$item)."</a></li>";
							}
							echo "</ul>";
							?>
							</div>
						</div>
						<?php
					}
					
					?>
					
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><a href="<?php echo site_url('admin/pengguna');?>"><?php echo $boxTitle;?></a></h3>
						</div>
						<div class="box-body">
							<?php 
							if($msg){
								echo "
								<div class=\"alert alert-success alert-dismissable\">
									<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
									<h4><i class=\"fa fa-check\"></i> ".$msg['msg']."</h4>
								</div>";
							}
							
							?>
							<form action="<?php echo $form_action; ?>" method="POST" class="formular form-horizontal" role="form">
								
								<div class="form-group">
									<label class="col-md-4 col-sm-6 col-xs-12" for="SebutanWilayah">Sebutan</label>
									<div  class="col-md-8 col-sm-6 col-xs-12">
										<select name="sebutan" class="validate[required] form-control" >
											<option value="desa">Desa</option>
											<option value="kelurahan">Kelurahan</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 col-sm-6 col-xs-12" for="wilayah">Desa/Kelurahan/Sebutan lainnya</label>
									<div  class="col-md-8 col-sm-6 col-xs-12">
										<select name="wilayah" id="wilayah" class="validate[required] form-control">
											<?php
											foreach($kecamatan as $key=>$item){
												echo "<optgroup label=\"Kecamatan ".$item['nama']."\">";
												foreach ($desa as $de=>$sa){
													if(substr($de,0,7) == $key){
														$strS = ($de == $param[1]) ? "selected=\"selected\"":"";
														echo "<option value=\"".$de."\" ".$strS.">&nbsp;&nbsp;&nbsp;-&nbsp;Kelurahan ".$sa['nama']."</option>";
													}
												}
												echo "</optgroup>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 col-sm-6 col-xs-12" ></label>
									<div  class="col-md-8 col-sm-6 col-xs-12">
										<button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> Unduh Data</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="box box-info">
						<div class="box-header">
							<h3 class="box-title"><i class="fa fa-info fa-fw"></i> Olah Data</h3>
						</div>
						<div class="box-body">
							<ul>
								<li>Dobelklik pada data untuk mengubah data</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<!-- footer section -->
<?php


$this->load->view('siteman/siteman_footer');
