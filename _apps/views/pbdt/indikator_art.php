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
			<?php echo $app['app_title'];?>
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
			$this->load->view('pbdt/_header');
			?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><a href="<?php echo site_url('verval');?>">Telusur Data Penduduk Berbasis Indikator <?php echo $user['wilayah_nama']?></a></h3>
				</div>
				<div class="box-body">
				<?php 
				// echo var_dump($num_responden);
				?>
					<table class="table table-bordered table-responsives datatables">
					<thead><tr><th rowspan="2">#</th>
						<th rowspan="2" colspan="2">Indikator / Opsi</th>
						<th colspan="<?php echo count($periodes)?>">Data</th>
					</tr><tr>
					<?php 
					foreach($periodes['periode'] as $periode){
						echo "<th>".$periode['nama']."</th>";
					}
					?>
					</tr></thead>
					<tbody>
					<?php
					$nomer=0;
					foreach($bdt_indikator as $key=>$rs){
						if($rs['jenis']=='pilihan'){
							$nomer++;
							echo "<tr><td class='angka'>".$nomer."</td>";
							if($rs['opsi']){
								echo "<td colspan='2'><a href='".site_url('backend/pbdt/indikator_detail/art/'.$rs['nama'])."'>".$rs['label']."</a></td>";
							}else{
								echo "<td colspan='2'>".$rs['label']."</td>";
							}
							foreach($periodes['periode'] as $periode){
								echo "<td></td>";
							}
							echo "</tr>";
							if($rs['opsi']){
								if(is_array($rs['opsi'])){
									foreach($rs['opsi'] as $o=>$op){
										echo "<tr><td></td>
											<td style='width:20px;'>".$op['nama']."</td>
											<td style='padding-left:30px;'>".$op['label']."</td>";
											foreach($periodes['periode'] as $periode){
												$nilai = 0;
												if(array_key_exists($periode['id'],$num_responden)){
													if(is_array($num_responden[$periode['id']][$rs['nama']])){
														if(array_key_exists($op['nama'],$num_responden[$periode['id']][$rs['nama']])){
															$nilai = $num_responden[$periode['id']][$rs['nama']][$op['nama']];
														}
			
													}
												}
												// $nilai = ($num_responden[$periode['id'][$rs['nama']]])? $num_responden[$periode['id'][$rs['nama']]][$op['nama']]: 0;
												echo "<td class='angka'><a href='".site_url("backend/pbdt/art_mana/?periode=".$periode['id']."&indikator=".$rs['nama']."&opsi=". $op['nama'] ."&kode=". $varKode)."'>".number_format($nilai,0). "</a></td>";
											}
					
											echo"
										</tr>";
									}
								}
							}
	
						}
					}
					?>
					</tbody>
					</table>
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
