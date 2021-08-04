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
 * GNU General Public License for more details.x
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
			<h1>Verifikasi dan Validasi Data
				<small><?php echo APP_TITLE;?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">VeriVali Data</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<!--verivali Head-->

			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo $boxTitle;?></a></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-8 col-sm-12 col-xs-12">
							<div class="box box-primary">
								<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-th fa-fw"></i> Detail Kartu Keluarga</h3></div>
								<div class="box-body">

									<dl class="dl-horizontal">
										<?php 
											$kk_rw = substr($kk['kode_wilayah'],-4,2);
											$kk_rt = substr($kk['kode_wilayah'],-2);
											$dtlahir = ($kk['dtlahir'] == '0000-00-00') ? "Tidak Terdata":indonesian_date(strtotime($kk['dtlahir']),"j F Y","").", ".$kk['umur']." tahun";
											echo "
											<dt>NO Urut RT</dt><dd><a href=\"".site_url('verval/form_rts/'.$kk['rtm_no'])."\">".$kk['rtm_no']." <i class=\"fa fa-reply\"></i></a></dd>
											<dt>No Kartu Keluarga</dt><dd>".$kk['kk_no']."</dd>";
											if($kk['rts_utama'] == 1){
												echo "<dt>Keluarga sbg RTS</dt><dd>YA</dd>";
											}
											echo "
											<dt>Alamat RTS</dt><dd>".$rtm['alamat']."</dd>
											<dt>Kepala Rumah Tangga</dt><dd>".$kk['pnama']."</dd>
											<dt>Jenis Kelamin</dt><dd>".$kk['sex']."</dd>
											<dt>Tanggal Lahir, Umur</dt><dd>".$dtlahir." - ".$kk['dtlahir']."</dd>
											<dt>Alamat KK</dt><dd>
												Kecamatan : <strong>".$kk['kecamatan']."</strong>
												<br />Kelurahan : <strong>".$kk['kelurahan']."</strong>
												<br />RW <strong>".$kk_rw."</strong> / RT <strong>".$kk_rt."</strong>
												</dd>
											";
										// echo var_dump($rtm);
										?>
									</dl>
									
								</div>
								
							</div> <!--/box-->
						</div>
						

						<div class="col-md-4 col-sm-12 col-xs-12">
							<?php
							echo "
							<div class=\"info-box bg-red\">
								<span class=\"info-box-icon\"><i class=\"fa fa-calendar\"></i></span>

								<div class=\"info-box-content\">
									<span class=\"info-box-text\">PERIODE PENDATAAN</span>
									<span class=\"info-box-number\"><i class=\"fa fa-clock-o\"></i> ".$periodes['periode'][$periodes['periode_aktif']]['nama']."</span>

									<div class=\"progress\">
										<div class=\"progress-bar\" style=\"width: 88%\"></div>
									</div>
											<span class=\"progress-description\">
												<i class=\"fa fa-clock-o\"></i> Diperbarui per  
											</span>
								</div>
							</div>
							<!-- /.info-box -->
							";


							$quest = 0;
							foreach($indikator_kategori as $key=>$item){
								if(array_key_exists($key,$indikator_item)){
									$quest+= count($indikator_item[$key]);
								}
							}
							
							if(!$vali){
								$terisi = 0;
							}else{
								$terisi = count($vali);
								if($terisi > $quest){
									$terisi = $quest;
								}
							}
							$progress = ( $terisi / $quest ) * 100;
							$progress = number_format($progress,2);

							if($progress >= 100){
								$bg = "bg-green";
								$icone = "fa-thumbs-o-up";
							}elseif($progress == 0){
								$bg = "bg-red";
								$icone = "fa-circle-o";
							}else{
								$bg = "bg-yellow";
								$icone = "fa-puzzle-piece";
							}
							echo "
							<div class=\"info-box ".$bg."\">
								<span class=\"info-box-icon\"><i class=\"fa ".$icone."\"></i></span>

								<div class=\"info-box-content\">
									<span class=\"info-box-text\">INDIKATOR TERDATA</span>
									<span class=\"info-box-number\"><i class=\"fa fa-check\"></i> ".$terisi." / ".$quest." (".$progress.") %</span>

									<div class=\"progress\">
										<div class=\"progress-bar\" style=\"width: ".$progress."%\"></div>
									</div>
											<span class=\"progress-description\">
												<i class=\"fa fa-clock-o\"></i> Diperbarui per  ".date('j/m/y H:i')."
											</span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
							";

	
							?>
							
						</div>
					</div>

<!--- formulir PBDT -->

	<!-- start data per indikator -->
	<!--Jeditable-->
	<script src="<?php echo base_url("assets/plugins/"); ?>jeditable/jquery.jeditable.mini.js"></script>
	<?php 
/*
 * Menampilkan data indikator PBDT dikelompokkan berdasar kategori
 * 
 * */

	// echo var_dump($vali);

	echo "
	<form action=\"".$form_action_kks."\" method=\"POST\" class=\"formular\">
	<h3 class=\"box-title\">Data Verifikasi dan Validasi</h3>
	<div class=\"callout callout-warning\">
		<h4>Jangan Lupa Klik Tombol Biru Paling Bawah</h4>
		<p>Setelah selesai mengisikan data coklit, jangan lupa klik tombol biru <span class=\"btn btn-primary btn-lg\"><i class=\"fa fa-save\"></i> Sudah selesai utk data KK no <strong>".$kk['kk_no']."</strong>? Klik disini untuk Menyimpan Data dan Kembali ke Rumah Tangga Sasaran <strong>".$kk['rtm_no']."</strong></span> </p>
	</div>
	";
	// echo var_dump($vali);
	// echo var_dump($valix);

	foreach($indikator_kategori as $key=>$item){
		if(array_key_exists($key,$indikator_item)){

		echo "
		<div class=\"box box-warning\">
			<div class=\"box-header\">
				<h3 class=\"box-title with-border\"><i class=\"fa fa-th\"></i> ".$item['nama']."</h3>
				<div class=\"box-tools pull-right\">
					<button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-plus\"></i>
					</button>
				</div>
				
			</div>
			<div class=\"box-body\">
				<div class=\"scroll\">";
				echo "
				<table class=\"table table-responsive table-bordered\">
					<thead><tr><th style=\"width:40px;\">#</th><th>Indikator</th>
					<th>Nilai <em>".strtoupper($periodes['periode'][$periodes['periode_aktif']]['nama'])."</em></th>
					</tr></thead>
					<tbody>";

						foreach($indikator_item[$key] as $i=>$indi){

							$thisID = "gakin_idv_".$kk['id']."_".$kk['kode_wilayah']."_".$periodes['periode_aktif']."_".$indi['id'];
							
							// $kunci_indi = "col_".$indi['id'];
							$kunci_indi = $indi['id'];

							echo"
							<tr>
							<td>".$indi["kode"]."</td>
							<td style=\"width:35%\">".$indi["nama"]."</td>";
									$strCText = "class=\"text-red\"";
									$strResStat = "class=\"has-error\"";
		
									if($vali)
									{
										if(array_key_exists($kunci_indi,$vali)){
											$strResStat = "class=\"has-success\"";
											$strCText = "";
										}
									}
									echo "
									<td ".$strResStat."><div class=\"row\">
										<div class=\"col-md-10\">";
		
											$strCheck = "<button class=\"btn btn-default btn-xs\" type=\"button\"><i class=\"fa fa-minus\"></i></button>";
											if($indi["jenis"]==1){
												echo "
												<select name=\"kk[".$indi['id']."]\" id=\"kk_".$indi['id']."\" class=\"form-control validate[required]\" placeholder=\"pilih jawaban\">
													<option value=\"\"></option>";
													foreach($indi["opsi"] as $o=>$ops){
														$strS = "";
														if(array_key_exists($indi['id'],$vali)){
															$strCheck = "<button class=\"btn btn-success btn-xs\" type=\"button\"><i class=\"fa fa-check\"></i></button>";
															if($ops['id'] == $vali[$kunci_indi]['param_opsi_id']){
																$strS = "selected=\"selected\"";
															}
														}else{
															if($ops['baku'] == 1){
																$strS = "selected=\"selected\"";
															}
														}
														echo "<option value=\"".$ops['id']."\" ".$strS.">".$ops['kode']." ".$ops['nama']."</option>";
													}
												echo "
												</select>
												";
											}else{
												$strVal = "";
												if($vali)
												{
													if(array_key_exists($indi['id'],$vali)){
														$strCheck = "<button class=\"btn btn-success btn-xs\"><i class=\"fa fa-check\"></i></button>";
														$strVal = $vali[$kunci_indi]['param_opsi_value'];
													}
												}
			
												echo "
												<input type=\"text\" name=\"kk[".$indi['id']."]\" id=\"kk_".$indi['id']."\" value=\"".$strVal."\" class=\"form-control validate[optional]\" placeholder=\"0\"/>
												";
											}
											echo "
											</div>
											<div class=\"col-md-2 col-xs-2\">
												<div id=\"btn_".$thisID."\">".$strCheck."</div>
											</div>
									
										</div><!--end of row-->
									</td>";

							echo "</tr>";
						} // akhir dari looping
			
				echo "
					</tbody>
				</table>
				</div>
			</div><!-- /collapse-->
		</div><!-- /panel -->
		";
		} // akhir dari group

	}
	
	echo "
		<input type=\"hidden\" name=\"kk_nomor\" value=\"".$kk['kk_no']."\"/>
		<input type=\"hidden\" name=\"rts_utama\" value=\"".$kk['rts_utama']."\"/>
		<input type=\"hidden\" name=\"rtm_no\" value=\"".$kk['rtm_no']."\"/>
		<input type=\"hidden\" name=\"param\" value=\"".$kk['id']."_".$kk['kode_wilayah']."_".$periodes['periode_aktif']."\">
		
		<button class=\"btn btn-primary btn-lg\" type=\"submit\"><i class=\"fa fa-save\"></i> Sudah selesai utk data KK no <strong>".$kk['kk_no']."</strong>? Klik disini untuk Menyimpan Data dan Kembali ke Rumah Tangga Sasaran <strong>".$kk['rtm_no']."</strong></button>
	</form>
	";
			?>
<!--- /end of formulir PBDT -->


				</div>
			</div>

		</section>
	</div>
	
<!-- footer section -->
<!-- CSS ValidationEngine -->
<link href="<?php echo base_url('assets/plugins/'); ?>validation-engine/validationEngine.jquery.css" rel="stylesheet" type="text/css">
<!-- ValidationEngine -->
<script src="<?php echo base_url()?>assets/plugins/validation-engine/jquery.validationEngine.js"></script>
<script src="<?php echo base_url()?>assets/plugins/validation-engine/languages/jquery.validationEngine-id.js"></script>

<!-- Select INTERDEPENDENT -->
<link href="<?php echo base_url("assets/plugins/"); ?>dependent-dropdown/css/dependent-dropdown.css " rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/plugins/"); ?>dependent-dropdown/js/dependent-dropdown.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>dependent-dropdown/js/locales/id.js"></script>

<script>
	
$(document).ready(function() {

	/*
	 * jQueryValidation
	 */
						
	var formular = $("form.formular");
	if(formular){
		$("form.formular").validationEngine();
	}	
	/*
	Dependant dropdown
	*/
	var kel = $("#kel_id");
	if(kel){
		$("#kel_id").depdrop({
			url: '<?php echo site_url('ajax/siteman_wilayah/'.substr($kk['kode_wilayah'],0,10));?>',
			depends: ['kec_id'],
			loadingText: 'Memuat daftar Kelurahan ...'
		});
		$("#rw_id").depdrop({
			url: '<?php echo site_url('ajax/siteman_wilayah/'.substr($kk['kode_wilayah'],0,14));?>',
			depends: ['kel_id'],
			loadingText: 'Memuat daftar RW ...'
		});
		$("#rt_id").depdrop({
			depends: ['rw_id'],
			initialize: true,
			initDepends: ['kec_id'],
			url: '<?php echo site_url('ajax/siteman_wilayah/'.$kk['kode_wilayah']);?>',
			loadingText: 'Memuat daftar RT ...'
		});
	}
	
	/*
	DOMISILI RTS
	*/
	var form_rts_dom = $('form#form_rts_dom');
	if(form_rts_dom){
		
		$(form_rts_dom).submit(function(event) {
			var formData = {
				'rts_kec' 			: $('select[name=rts_kecamatan]').val(),
				'rts_kel' 			: $('select[name=rts_kelurahan]').val(),
				'rts_rw' 				: $('select[name=rts_rw]').val(),
				'rts_rt' 				: $('select[name=rts_rt]').val(),
				'rts_no' 				: $('input[name=rts_no_rtm]').val(),
				'rts_id' 				: $('input[name=rts_id_rtm]').val()
			};

			// process the form
			$.ajax({
				type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
				url 		: '<?php echo $form_action_domisili;?>', // the url where we want to POST
				data 		: formData, // our data object
				dataType 	: 'json', // what type of data do we expect back from the server
				encode 		: true
			})
				// using the done promise callback
				.done(function(data) {

					// log data to the console so we can see
					console.log(data); 

					// here we will handle errors and validation messages
					if ( ! data.success) {
						if (data.errors.rts_kecamatan) {
							$('#kec_id').parents('div.form-group').addClass('has-error');
							$('#kec_id').parents('div.form-group').append('<div class="help-block">' + data.errors.rts_kecamatan + '</div>');
						}
						if (data.errors.rts_kelurahan) {
							$('#kel_id').parents('div.form-group').addClass('has-error');
							$('#kel_id').parents('div.form-group').append('<div class="help-block">' + data.errors.rts_kelurahan + '</div>');
						}
						if (data.errors.rts_rt) {
							$('#rw_id').parents('div.form-group').addClass('has-error');
							$('#rw_id').parents('div.form-group').append('<div class="help-block">' + data.errors.rts_rw + '</div>');
						}
						if (data.errors.rts_rt) {
							$('#rt_id').parents('div.form-group').addClass('has-error');
							$('#rt_id').parents('div.form-group').append('<div class="help-block">' + data.errors.rts_rt + '</div>');
						}

					} else {

						// ALL GOOD! just show the success message!
						$('button#save_rts_dom').removeClass('btn-primary');
						$('button#save_rts_dom').addClass('btn-success');
						$('form').append('<div class="alert alert-success">' + data.message + '</div>');
						var strBtn = "<i class='fa fa-check'></i> Data Tersimpan";
						$('button#save_rts_dom').html(strBtn);

					}
				})
				.fail(function(data) {

					// show any errors
					// best to remove for production
					console.log(data);
				});
			event.preventDefault();
		});
		
	}
	
});
</script>


<?php


$this->load->view('siteman/siteman_footer');
