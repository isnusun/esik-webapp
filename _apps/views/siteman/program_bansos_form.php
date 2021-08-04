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
			<li><a href="<?php echo site_url('program_bansos')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Data Pengguna</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-8 col-sm-6 col-xs-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><a href="<?php echo site_url('program_bansos');?>"><?php echo $boxTitle;?></a></h3>
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
							$tanggal = ($program_id > 0) ? $program['sdate']." - ".$program['edate']:date('Y-m-d')." - ".date('Y-m-d');
							?>
							<form action="<?php echo $form_action; ?>" method="POST" class="formular form-horizontal" role="form">
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Sasaran Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<select class="form-control  validate[required]" name="sasaran" id="cid">
											<option value="">Pilih Sasaran Program</option>
											<?php 
											foreach($program_sasaran as $key=>$item){
												$strS = ($key == $program['sasaran']) ? "selected=\"selected\"":"";
												echo "<option value=\"".$key."\" ".$strS.">".$item."</option>";
											}
											
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Nama Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
									<input type="text" class="form-control validate[required]" name="nama" id="nama" placeholder="Tuliskan nama program" value="<?php echo $program['nama']; ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Pemilik/Pelaksana Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<select name="lembaga_id" class="form-control validate[required]" placeholder="Pilih Lembaga Pemilik/Pelaksana Program">
											<?php 
											foreach($lembaga as $key=>$item){
												$strS = ($key == $program['lembaga_id']) ? "selected=\"selected\"":"";
												echo "<option value=\"".$key."\" ".$strS.">".$item['nama']."</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Keterangan</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
									<textarea class="form-control summernote" name="ndesc" id="summernote" placeholder="Tuliskan Keterangan"><?php echo $program['ndesc']; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Rentang Waktu Program</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<input type="text" class="form-control validate[required]" name="tanggal" id="tanggal" placeholder="" value="<?php echo $tanggal; ?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="label-control col-md-4 col-sm-12 col-xs-12">Program Status</label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<select class="form-control  validate[required]" name="status" id="status">
											<?php 
											foreach($program_status as $key=>$item){
												$strS = ($key == $program['status']) ? "selected=\"selected\"":"";
												echo "<option value=\"".$key."\" ".$strS.">".$item."</option>";
											}
											
											?>
										</select>
									</div>
								</div>
								
								
								<div class="form-group">
									<label class="label-control col-md-4"></label>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<input type="hidden" name="program_jenis" value="<?php echo $program_jenis; ?>" />
										<input type="hidden" name="program_id" value="<?php echo $program_id; ?>" />
										<div class="uibutton-group">
											<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
											<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
										</div>
									</div>
								</div>								
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-info fa-fw"></i> Panduan Penggunaan Modul</h3>
						</div>
						<div class="box-body">
							Cukup Jelas / menyusul
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<!-- footer section -->
<!-- CSS ValidationEngine -->
<link href="<?php echo base_url('assets/plugins/'); ?>validation-engine/validationEngine.jquery.css" rel="stylesheet" type="text/css">
<!-- JS ValidationEngine -->
<script src="<?php echo base_url('assets/plugins/'); ?>validation-engine/jquery.validationEngine.js"></script>
<script src="<?php echo site_url('jsphp/jqueryValidationEngineId'); ?>"></script>

<!-- JS DatePicker -->
<!-- Daterangepicker-->
<link href="<?php echo base_url('assets/plugins/'); ?>datepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url('assets/js/'); ?>moment.min.js"></script>
<script src="<?php echo base_url('assets/plugins/'); ?>datepicker/daterangepicker.js"></script>
<script src="<?php echo base_url('assets/plugins/'); ?>datepicker/locales/bootstrap-datepicker.id.js"></script>

<script>
	$(document).ready(function() {
		/*
		 * Inline Editable
		 */
		var formular = $("form.formular");
		if(formular){
			$("form.formular").validationEngine();
		}
		$('#tanggal').daterangepicker({
			locale: {
				format: 'YYYY-MM-DD'
      }
		}, function(start, end, label) {
			console.log(start.toISOString(), end.toISOString(), label);
		});
		
	});
</script>

<!-- Summernote-->
<link href="<?php echo base_url('assets/plugins/'); ?>summernote/summernote.css" / rel="stylesheet">
<script src="<?php echo base_url('assets/plugins/'); ?>summernote/summernote.min.js"></script>
<script src="<?php echo base_url('assets/plugins/'); ?>summernote/lang/summernote-id-ID.js"></script>
<script>
	$(document).ready(function() {
		$('#summernote').summernote({
			lang:'id-ID',
			height: 300,
			callbacks: {
				onImageUpload: function (image) {
					sendFile(image[0]);
				}
			}
		});

    function sendFile(image) {
        var data = new FormData();
        data.append("image", image);
        //if you are using CI 3 CSRF 
        // data.append("ci_csrf_token", "");
        $.ajax({
            data: data,
            type: "POST",
            url: "<?php echo site_url('ajax/summernote_upload/'); ?>",
            cache: false,
            contentType: false,
            processData: false,
            success: function (url) {
                var image = url;
                $('#summernote').summernote("insertImage", image);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
	});		
</script>
<?php


$this->load->view('siteman/siteman_footer');
