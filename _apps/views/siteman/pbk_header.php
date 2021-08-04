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

?>
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
			<?php echo $pageTitle;?>
			<small>Data Lokal</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Data Sisiran</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<!--box filter-->
			<div class="box box-danger">
				<div class="box-header with-border" <?php echo $box_collapse[0];?>>
					<h3 class="box-title"><i class="fa fa-check-square-o fa-fw"></i> Indikator Data Lokal</a></h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->

				</div>
				<div class="box-body" <?php echo $box_collapse[1];?>>
					<div class="row">
						<div class="col-md-8 col-sm-12 col-xs-12">
							<!-- data Idniakto-->
							<div class="box-group" id="accordion">
							<?php
							$i = 1;
							$n = count($pbk_kategori);
							foreach($pbk_kategori as $k=>$cat){
								$strIn = ($i == 1) ? "in":"";
								$strIn = "";
								echo "
										<!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
										<div class=\"panel box box-warning\">
											<div class=\"box-header\">
												<h4 class=\"box-title\">
													<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse".$k."\">
														".$cat['nama']."
													</a>
												</h4>
											</div>
											<div id=\"collapse".$k."\" class=\"panel-collapse collapse ".$strIn."\">
												<div class=\"box-body\">
												<ul class=\"nav\">
												";
												foreach($pbk_param[$k] as $key=>$item){
													echo "<li><a href=\"".site_url('pbk/indikator/'.$varKode.'/'.$item['id'].'/'.fixNamaUrl($item['nama']).'')."\">".$item['nama']."</a></li>";
												}
												echo "
												</ul>
												</div>
											</div>
										</div>
								
								";
								$i++;
							}
							
							?>
							</div>

							
						</div>
						<div class="col-md-4 col-sm-12 col-xs-12">
							<div class="box box-warning box-solid">
								<div class="box-header">
									<h4 class="box-title">Periode Pemutakhiran Data</h4>
								</div>
								<div class="box-body">
									<ul class="">
										<?php
										foreach($pbk_periode['periode'] as $key=>$item){
											$strStatus = ($item['status'] == 1) ? "Sedang Berproses":"Sudah Diarsipkan";
											echo "<li><a href=\"\">".$item['nama']."</a> (".$strStatus.")</li>";
										}
										?>
									</ul>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
