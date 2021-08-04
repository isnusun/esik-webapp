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
			<?php echo $app['title'];?>
			<small>Verifikasi dan Validasi Data <?php echo $user['wilayah_nama']; ?></small>
			</h1>
			<ol class="breadcrumb">
			<li><a href="<?php echo site_url('siteman')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">VeriVali Data</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			$this->load->view('verivali/_header');
			?>
			<div class="row">
				<div class="col-md-6">
					<div class="callout callout-warning">
						<h4>Pencarian Data Kartu Keluarga</h4>
						<form action="<?php echo site_url('verval/cari_kk'); ?>" method="POST" class="formular" role="form">
							<div class="form-group">
								<label class="label-control" for="search">Data yang dicari</label>
								<div class="input-group">
									<input type="text" class="form-control" name="q" id="q" placeholder="masukkan nomor Kartu Keluarga"/>
									<span class="input-group-btn">
										<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
									</span>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-6">
					<div class="callout callout-info">
						<h4>Penambahan Rumah Tangga Baru</h4>
						<p>Kita bisa memulai verifikasi dan validasi data dengan mencari dari daftar Rumah Tangga Sasaran berbasis wilayah, atau pun menggunakan formulir pencarian data RTS berbasis nama</p>
						<p>Bila Rumah Tangga Sasaran belum/tidak terdaftar disini, silakan gunakan <a href="<?php echo site_url('verivali/new_rtm/?kode='.$user['wilayah'])?>" class="btn btn-sm btn-warning"><i class="fa fa-plus"></i> Tombol ini untuk menambahkan data Rumah Tangga Sasaran Baru</a></p>
					</div>

				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('verval');?>">Telusur Data RTS berbasis Wilayah Administrasi <?php echo $user['wilayah_nama']?></a></h3>
				</div>
				<div class="box-body no-padding">
						<div class="nav-tabs-custom">
							
							<ul class="nav nav-tabs">
								
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="#">
										Pilih Dusun <span class="caret"></span>
									</a>
									<ul class="dropdown-menu">
										<?php 
										if(count($dusun) > 0){
											foreach($dusun as $k=>$s){
												echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"".site_url('verval')."/?kode=".$k."\">".$s['nama']."</a></li>";
											}
										}
										?>
									</ul>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1">
									<?php
									if(@$_SESSION['msg']){
										echo "<div class=\"alert alert-".$_SESSION['msg']['jenis']." alert-dismissable\">
										<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
										<h4>".$_SESSION['msg']['title']."</h4>
										".$_SESSION['msg']['body']."
										</div>";
										$_SESSION['msg'] = "";
									}
									echo var_dump($periodes);
									if($sasaran){
										if(count($sasaran) > 0){
											echo "
											<h4>Daftar Rumah Tangga Sasaran di Wilayah <strong class=\"text-red\">". $wilayah."</strong> <strong>(". count($sasaran).") RTS</strong></h4>
											<table class=\"table table-bordered table-responsive datatables\">
											<thead><tr><th rowspan=\"2\">#</th>
											<th rowspan=\"2\">No RTM</th>
											<th rowspan=\"2\">&Sigma; KK</th>
											<th rowspan=\"2\">&Sigma; ART</th>
											<th rowspan=\"2\">Kepala RTS/<br />NIK</th>
											<th rowspan=\"2\">Action</th>
											<th colspan=\"".count($periodes)."\">PERIODE PENDATAAN</th>
											</tr>
											<tr>";
											foreach($periodes['periode'] as $p){
												echo "<th>".$p['nama']."</th>";
											}
											echo "</tr>
											</thead>
											<tbody>";
											$nomer = 1;
											foreach($sasaran as $k=>$rs){
												$nkk = (array_key_exists($rs['rtm_no'],$kks)) ? count($kks[$rs['rtm_no']]):0;
												echo "<tr><td class=\"angka\">".$nomer."</td>
												<td><a href=\"".site_url('verval/form_rts/'.$rs['rtm_no'])."\" target=\"_blank\">".$rs['rtm_no']."</a></td>
												<td>".$rs['nkks']."</td>
												<td>".$rs['narts']."</td>
												<td><a href=\"".site_url('verval/form_rts/'.$rs['rtm_no'])."\" target=\"_blank\">".$rs['pnama']."</a>
													<br /><a href=\"".site_url('verval/form_rts/'.$rs['rtm_no'])."\" target=\"_blank\">".$rs['pnik']."</a></td>
												<td>";
												if($rs['nkks'] == 0){
													echo "<a class=\"btn btn-danger btn-xs hapus_rts\" 
													rel=\"".$rs["rtm_no"]."\" 
													data-toggle=\"modal\" 
													data-target=\"#modalHapusRTS\" 
													title=\"".$rs["rtm_no"]." a.n ".$rs['pnama']."\"
													href=\"#\" 
													id=\"".$rs['id']."\"><i class=\"fa fa-trash\"></i></a>";
												}
												else
												{
													echo "<a class=\"btn btn-default btn-xs\" href=\"".site_url('verval/form_rts/'.$rs['rtm_no'])."\" target=\"_blank\"><i class=\"fa fa-info\"></i></a>";
												}
													
													echo "
												</td>
												";
												foreach($periodes['periode'] as $k=>$p){
													if($k == $periodes['periode_aktif'])
													{
														echo "<th>
														<a class=\"btn btn-success btn-xs\" href=\"".site_url('verval/form_rts/'.$rs['rtm_no'])."/".$p['id']."\" target=\"_blank\"><i class=\"fa fa-check-square\"></i><i class=\"fa fa-lock-o\"></i>
														</a></th>";
													}
													else
													{
														echo "<th>
														<a class=\"btn btn-default btn-xs\" href=\"".site_url('verval/form_rts/'.$rs['rtm_no'])."/".$p['id']."\" title=\"Lihat Data \"  target=\"_blank\">
														<i class=\"fa fa-lock\"></i>
														</a></th>";
													}
												}
		
												echo "
												</tr>";
												$nomer++;
											}
											echo "</tbody>
											</table>";
					
										}
									}
								?>
							</div>
						</div>
					</div><!--/nav-tabs-->
				</div>
			</div>

		</section>
	</div>
<!-- footer section -->

	<div class="modal" tabindex="-1" role="dialog" id="modalHapusRTS">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<form action="<?php echo site_url('verval/hapus_rts');?>" method="POST">

				<div class="modal-header">
					<h5 class="modal-title">Penghapusan RTS &ldquo;<strong id="postTitle"></strong>&rdquo;</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Yakin akan menghapus RTM &ldquo;<strong id="rtm_hapus"></strong>&rdquo;</p>
					<P class="text-danger">Tindakan ini tidak bisa dibatalkan</P>
					<div class="form-group">
						<label for="alasan" class="label-control">Alasan Penghapusan:</label>
						<textarea class="form-control" name="alasan" id="alasan"></textarea>
					</div>
				</div>
				<div class="modal-footer">
						<input type="hidden" name="rtm_id" id="post_id" value="">
						<input type="hidden" name="kode" id="kode_area" value="<?php echo $kode_ini;?>">
						<button type="submit" class="btn btn-danger"><i class="fa fa-trash fa-fw"></i>Hapus</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times fa-fw"></i>Batal</button>
				</div>
			</form>
			</div>
		</div>
	</div>
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
		var $reset_data = $('a.hapus_rts');
		if($reset_data){
			$reset_data.click(function(){
				var $idnya = $(this).attr('id');
				var $namanya = $(this).attr('rel');
				var $desc = $(this).attr('title');
				console.log($idnya);
				console.log($namanya);
				$('#postTitle').html($namanya);
				$('#rtm_hapus').html($desc);
				$('#post_id').val($idnya);
			});
		}		

		$(".select2-remote" ).select2({
			placeholder: "Cari Data Rumah Tangga dengan memasukkan NAMA atau NOMOR INDUK Kepala Rumah Tangga",
			minimumInputLength: 3,
			// instead of writing the function to execute the request we use Select2's convenient helper
			ajax: {
				url: "<?php echo site_url("ajax/"); ?>penduduk/",
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
		/*
		$("#rtm_indikator").html(repo.indikator);
		$("#datane").val(repo.indikator);
		*/

	}

</script>
<!-- DataTables CSS -->
<link href="<?php echo base_url("assets/plugins/"); ?>datatables/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- Datatables-->
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>jszip/jszip.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url("assets/plugins/"); ?>datatables/buttons/js/buttons.colVis.min.js"></script>
<script>
	
$(document).ready(function() {
    $('table.datatables').DataTable( {
				"language": {
                "url": "<?php echo base_url("assets/plugins/"); ?>datatables/datatables_ID.js"
            },
        dom: 'Bfrtip',
        buttons: [
					'excelHtml5',
					'csvHtml5',
					{extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A4'
					},
					'print',
					{
						extend: 'colvis',
						columns: ':gt(2)'
					}
        ]
    } );
} );
</script>

<?php


$this->load->view('siteman/siteman_footer');
