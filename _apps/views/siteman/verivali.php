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
			<small>Verifikasi dan Validasi Data</small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">VeriVali Data</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-8 col-sm-6 col-xs-12">
					
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><a href="<?php echo site_url('verivali');?>"><?php echo $boxTitle;?></a></h3>
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
							<div class="callout callout-default">
								<h4>Cari Data Rumah Tangga</h4>
								<p>Gunakan formulir ini untuk mencari rumah tangga yang akan di verifikasi dan validaasi data nya</p>
								<form action="<?php echo $form_action; ?>" method="POST" class="form-horizontal formular" role="form">
									<div class="form-group" id="p_cari">
										<label class="form-label col-md-4 col-sm-12 col-xs-12">Nama Kepala Rumah Tangga</label>
										<div class="col-md-8 col-sm-12 col-xs-12">
											<input type="text" name="rtm_no" class="form-control select2-remote" id="rtm_no"/>
										</div>
									</div>
									<div id="detail_rtm" class="form-group hidden">
										<dl class="dl-horizontal">
											<dt>Nomor Rumah Tangga</dt>
												<dd id="rtm_no_view"></dd>
											<dt>Nama Kepala Rumah Tangga</dt>
												<dd id="rtm_kepala"></dd>
											<dt>Alamat Rumah Tangga</dt>
												<dd id="rtm_alamat"></dd>
											<dt>Jumlah Anggota Rumah Tangga</dt>
												<dd id="rtm_anggota"></dd>
											<dt>Indikator yang digunakan</dt>
												<dd id="rtm_indikator"></dd>
										</dl>
										<input type="hidden" name="datane" id="datane" value=""/>
									</div>
									<div class="form-group">
										<label class="form-label col-md-4 col-sm-12 col-xs-12">Sudah benar data yang akan diverifikasi/validasi?</label>
										<div class="col-md-8 col-sm-12 col-xs-12">
											<button type="reset" id="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
											<button type="submit" class="btn btn-primary"><i class="fa fa-bullseye"></i> Isi Data VeriVali</button>
										</div>
									</div>
								</form>
							</div>							
						</div>
					</div>
					
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="box box-info">
						<div class="box-header">
							<h3 class="box-title"><i class="fa fa-info fa-fw"></i> Verifikasi dan Validasi Data</h3>
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
