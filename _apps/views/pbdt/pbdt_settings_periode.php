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
							<?php
							if(@$periode){
								$value['nama'] = $periode['nama'];
								$value['ndesc'] = $periode['ndesc'];
								$value['sdate'] = $periode['sdate'];
								$value['edate'] = $periode['edate'];
								$value['rdate'] = $value['sdate']." - ".$periode['edate'];
								$value['id'] = $periode['id'];
							}else{
								$value['nama'] = "";
								$value['ndesc'] = "";
								$value['sdate'] = "";
								$value['edate'] = "";
								$value['rdate'] = date("Y-m-d")."-".date("Y-m-d");
								$value['id'] = 0;
							}
							?>
							<form action="<?php echo site_url('pbdt/simpan_pengaturan/periode'); ?>" method="POST" class="formular" role="form">
								<div class="form-group">
									<label class="control-label" for="nama">Nama Periode</label>
									<input type="text" class="form-control validate[required]" id="nama" name="nama" placeholder="Tuliskan Nama Periode" value="<?php echo $value['nama'];?>"/>
								</div>
								<div class="form-group">
									<label class="control-label" for="ndesc">Keterangan</label>
									<textarea class="form-control validate[optional] summernote" id="summernote" name="ndesc" placeholder="Tuliskan Keterangan Periode"><?php echo $value['ndesc'];?></textarea>
								</div>
								<div class="form-group">
									<label class="control-label" for="waktu">Masa Periode</label>
									<input type="text" class="form-control validate[required]" id="rdate" name="rdate" placeholder="Pilih Rentang Waktu Periode" value="<?php echo $value['rdate'];?>"/>
								</div>
								<div class="form-group">
									<input type="hidden" name="id" value="<?php echo $value['id'];?>"/>
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
								<th>Masa Berlaku</th>
								<th></th>
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
								<td>".$item['sdate']." - ".$item['edate']."</td>
								<td><div class=\"btn-group\">
									<a class=\"btn btn-xs btn-default\" href=\"".site_url('pbdt/pengaturan/periode/').$item['id']."\" title=\"Perbarui Data\"><i class=\"fa fa-pencil\"></i></a>
								</div></td>
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
<script src="<?php echo base_url("assets/plugins/"); ?>datepicker/moment.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datepicker/daterangepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url("assets/plugins/"); ?>datepicker/css/daterangepicker.css">
<script>
	$(document).ready(function() {
		var gke = $('.editable_gakin');
		$(gke).editable('<?php echo site_url('gakin/simpan_gakin');?>',{
			indicator : '<img src="<?php echo base_url('assets/img/ajax-loader.gif');?>">',
			tooltip   : 'Klik untuk mengisi data...',
			event			: 'dblclick',
			submit : 'Simpan'
		});		
		$('input#rdate').daterangepicker({
				locale: {
					format: 'YYYY-MM-DD'
				}
			}, 
			function(start, end, label) {
					alert("Periode Baru telah diatur : " + start.format('YYYY-MM-DD') + ' s.d ' + end.format('YYYY-MM-DD'));
		});
		
	});
</script>
<!-- Summernote-->
<link href="http://local.bappedawng.id/assets/plugins/summernote/summernote.css" / rel="stylesheet">
<script src="http://local.bappedawng.id/assets/plugins/summernote/summernote.min.js"></script>
<script src="http://local.bappedawng.id/assets/plugins/summernote/lang/summernote-id-ID.js"></script>
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
            url: "http://local.bappedawng.id/ajax/summernote_upload/",
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
