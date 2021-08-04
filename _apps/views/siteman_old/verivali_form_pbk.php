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

			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo $boxTitle;?></a></h3>
				</div>
				<div class="box-body">
					<dl class="dl-horizontal">
						<?php 
							echo "
							<dt>NO Urut RT</dt><dd>".$rtm['rtm_no']."</dd>
							<dt>Kepala Rumah Tangga</dt><dd>".$rtm['kepala']."</dd>
							<dt>Jenis Kelamin</dt><dd>".$rtm['kepala_sex']."</dd>
							<dt>Tanggal Lahir, Umur</dt><dd>".date("j F Y",strtotime(trim($rtm['kepala_dtlahir']))).", ".$rtm['kepala_umur']." tahun</dd>
							<dt>Alamat</dt><dd>".$rtm['alamat']."</dd>
							<dt></dt><dd>".$rtm['wilayah']."</dd>
							<dt>Klasifikasi Data Lokal/Sisiran</dt><dd>Prioritas ".$rtm['kelas_pbk']."</dd>
							";
						// echo var_dump($rtm);
						?>
					</dl>
					<?php
					if(count($rtm['anggota']) > 0){
						?>
						<div class="box box-primary box-solid">
							<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-users"></i> Anggota Rumah Tangga</h3></div>
							<div class="box-body">
								<table class="table table-responsive table-bordered">
									<thead><tr>
										<th>#</th><th>Nama</th>
										<th>NIK</th>
										<th>Jenis Kelamin</th>
										<th>Hubungan</th>
										<th>Tgl Lahir</th>
										<th>Umur</th><th></th>
									</tr></thead>
									<tbody>
										<?php
										$nomer =1;
										foreach($rtm['anggota'] as $a=>$o){
											echo "
											<tr><td class=\"angka\">".$nomer."</td>
												<td><a href=\"".site_url('kependudukan/individu/'.$o['id'].'/')."\">".$o['pnama']."</a></td>
												<td>".$o['pnik']."</td>
												<td>".$o['psex']."</td>
												<td>".$o['hubungan']."</td>
												<td>".$o['dtlahir']."</td>
												<td>".$o['umur']."</td>
												<td><div class=\"btn-group\">
													<a href=\"".site_url('kependudukan/individu/'.$o['id'].'/')."\"><i class=\"fa fa-edit\"></i></a>
													</div></td>
											</tr>
											";
											$nomer++;
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
						<?php
					}
					?>					

<!--- formulir PBDT -->

	<form action="<?php echo $form_action; ?>" method="POST" class="formular form-horizonal" role="form">
	<!-- start data per indikator -->
	<?php 
/*
 * Menampilkan data indikator PBDT dikelompokkan berdasar kategori
 * 
 * */
 
	echo "
	<input type=\"hidden\" name=\"periode_id\" value=\"".$periodes['periode_aktif']."\">
	<input type=\"hidden\" name=\"data_to\" value=\"pbk\">
	<input type=\"hidden\" name=\"responden_id\" value=\"".$rtm['rtm_no']."\">
	<input type=\"hidden\" name=\"responden_kw\" value=\"".$rtm['kode_wilayah']."\">";

	foreach($indikator_kategori as $key=>$item){

		echo "
		<div class=\"box box-warning\">
			<div class=\"box-header\">
				<h3 class=\"box-title with-border\"><i class=\"fa fa-th\"></i> ".$item['nama']."</h3>
			</div>
			<div class=\"box-body\">
			
				<table class=\"table table-responsive table-bordered\">
					<thead><tr><th>#</th><th>Indikator</th>";
					foreach($periodes['periode'] as $p){
						echo "<th>".$p["nama"]."</th>";
					}
					
					echo "
					</tr></thead>
					<tbody>";
			
			foreach($indikator_item[$key] as $i=>$indi){
				//echo var_dump($indi['jenis']);

				echo"
				<tr>
				<td>".$indi["kode"]." </td>
				<td style=\"width:35%\">".$indi["nama"]."</td>
				";
//				<td>".print_r($indi["opsi"])."</td>
				foreach($periodes['periode'] as $p=>$periode){
					if($indikator_data[$periode['id']]){
						//echo "<td>periode ".$p."</td>";
						
						if($periode["status"] == 0){
							$datane = "";
							$kelas = "";
							if($indi['jenis'] == 2){
								if(array_key_exists($indi["kode"],$indikator_data[$periode['id']])){
									$kuncine = $indikator_data[$periode['id']][$indi["kode"]];
									if($kuncine <> 0){
										$datane = $indi['opsi'][$kuncine]['nama'];
										$kelas = ($indi['opsi'][$kuncine]['nilai'] == 1)? "class=\"kosong\"": "";
									}
								}else{
									$datane = "n/a";
								}
						
//								$datane = $indikator_data[$periode['id']][$indi['kode']];
							}else{
								$datane = $indikator_data[$periode['id']][$indi['kode']];
							}
							//echo var_dump($indikator_data[$periode['id']]);
							echo "<td style=\"width:30%\" ".$kelas."><div class=\"form-group has-success\"><label>".$datane."</label></div></td>";
						}else{
							/*
							 * APAKAH sdh ada data tercatat ? 
							 * */
								//echo var_dump($indikator);
								$prevVal = $indikator_data[$periode['id']][$indi["kode"]];

								echo "
								<td class=\"terisi\" style=\"width:30%\" ><div class=\"form-group has-warning\">";
								if($indi["jenis"]==2){
									echo "<select name=\"".$indi["kode"]."__".$indi["id"]."\" class=\"form-control validate[required]\">";
									foreach($indi["opsi"] as $o=>$ops){
										$strS = ($ops['id'] == $prevVal) ? "selected=\"selected\"":"";
										echo "<option value=\"".$ops["id"]."\" ".$strS.">".$ops["nama"]."</option>";
									}
									echo "</select>";
								}else{
									echo "<input name=\"".$indi["kode"]."__".$indi["id"]."\" id=\"".$indi["kode"]."__".$indi["id"]."\" class=\"form-control validate[required]\" value=\"".$prevVal."\"/></td>";
								}
								echo "</div>
								</td>";
							
						}
						// echo "<td>".$periode['status']."</td>";
					}else{
						echo "
						<td class=\"kosong\" style=\"width:30%\" ><div class=\"form-group has-error\">";
						if($indi["jenis"]==2){
							echo "<select name=\"".$indi["kode"]."__".$indi["id"]."\" class=\"form-control validate[required]\">";
							foreach($indi["opsi"] as $o=>$ops){
								echo "<option value=\"".$ops["id"]."\">".$ops["nama"]."</option>";
							}
							echo "</select>";
						}else{
							echo "<input name=\"".$indi["kode"]."__".$indi["id"]."\" id=\"\" class=\"form-control validate[required]\" /></td>";
						}
						echo "</div></td>";
					}
				}
/*				
				if($indi['jenis'] == 2){
					echo "<td>".print_r($indi['opsi'])."</td>";
				}else{
					echo "<td>".$indi['opsi']."</td>";
				}
*/
			echo "
				</tr>";
			}
				echo "
					</tbody>
				</table>
			</div><!-- /collapse-->
		</div><!-- /panel -->";

	}
			?>
			<!-- end data per indikator -->
			<div class="form-group">
				<label class="control-label col-md-4">Data sudah lengkap?</label>
				<div class="col-md-8">
					<button class="btn btn-primary"><i class="fa fa-save"></i> Simpan Data</button>
				</div>
			</div>
	</form>
<!--- /end of formulir PBDT -->


				</div>
			</div>
		</section>
	</div>
<!-- footer section -->
<!-- ValidationEngine -->
<script src="<?php echo base_url()?>assets/plugins/validation-engine/jquery.validationEngine.js"></script>
<script src="<?php echo base_url()?>assets/plugins/validation-engine/languages/jquery.validationEngine-id.js"></script>
<!-- Select2 -->
<link href="<?php echo base_url("assets/plugins/"); ?>select2/select2.css" rel="stylesheet" type="text/css"/>
<!-- Select2 -->
<script src="<?php echo base_url("assets/plugins/"); ?>select2/select2.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>select2/select2_locale_id.js"></script>

<script>
	$(document).ready(function() {
		$(".select2-remote" ).select2({
			placeholder: "Cari Data Rumah Tangga dengan memasukkan NAMA atau NOMOR INDUK Kepala Rumah Tangga",
			minimumInputLength: 3,
			// instead of writing the function to execute the request we use Select2's convenient helper
			ajax: {
				url: "<?php echo site_url("ajax/"); ?>rumahtangga_dan_kepala/",
				dataType: "json",
				quietMillis: 250,
				data: function( term, page ) {
					return {
						// search term
						q: term
					};
				},
				results: function( data, page ) {
						// parse the results into the format expected by Select2.
						// since we are using custom formatting functions we do not need to alter the remote JSON data
						return { results: data.items };
				},
				cache: true
			},
			formatResult: repoFormatResult,
			formatSelection: repoFormatSelection,  // omitted for brevity, see the source of this page
			dropdownCssClass: "bigdrop",
			escapeMarkup: function( m ) {
				return m;
			}
		});
		$("#reset").click(function(){
			$("#p_cari").removeClass("hidden");
			$("#detail_rtm").addClass("hidden");
			
		});
	});

	function repoFormatResult( repo ) {
		var markup = "<div class='select2-result-repository clearfix'>" +
			"<div class='select2-result-repository__meta'>" +
				"<div class='select2-result-repository__title'>" + repo.nama + " ["+ repo.id +"] "+
				"<br />Alamat: " + repo.alamat + "</div>"+
			"</div></div>";
		return markup;
	}

	function repoFormatSelection(repo) {
		$("#p_cari").addClass("hidden");
		$("#detail_rtm").removeClass("hidden");
/*		
		$.getJSON("<?php echo site_url("ajax/pdetail/"); ?>"+ repo.id, function( data ) {
			var items = [];
			$.each( data, function( key, val ) {
				items.push( "<li id='" + key + "'>" + val + "</li>" );
			});
			$( "#thedetail", {
			"class": "my-new-list",
				html: items.join( "" )
			}).appendTo( "body" );
		});
*/
		
		$("#rtm_no_view").html(repo.id);
		$("#rtm_kepala").html(repo.nama);
		$("#rtm_anggota").html(repo.anggota);
		$("#rtm_alamat").html(repo.alamat);
		$("#rtm_indikator").html(repo.indikator);
		$("#datane").val(repo.indikator);

	}

</script>

<?php


$this->load->view('siteman/siteman_footer');
