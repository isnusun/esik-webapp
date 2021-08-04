<!-- detail dpt/pemilih -->
<form action="<?php echo site_url('backend/administrasi/pindah_alamat_do/'.$varKode)?>" method="POST" class="formular" role="form">
	<div class="box box-warning">
		<div class="box-body">
		<div class="form-group">
			<fieldset class="form-group">
				<legend>Asal Tercatat</legend>
				<div><?php echo $rs['alamat'];?></div>
			</fieldset>
			<div class="form-group">
				<label class="control-label">Alamat yang BENAR</label>
				<select class="form-control" placeholder="Pilih alamat yang benar sesuai dgn list ini" name="tujuan">
					<option value="0">Pilih alamat yang benar sesuai dgn list ini</option>
					<?php 
					foreach($tujuan as $key=>$val){
						echo "<option value='".$key."'>".$val."</option>";
					}
					?>
				</select>
			</div>
		</div>
		<div class="tile-footer">
			<div class="row">
				<div class="col-md-8 col-md-offset-3">
					<input type="hidden" name="varKode" value="<?php echo $varKode;?>"/>
					<input type="hidden" name="idbdt" value="<?php echo $rs['idbdt'];?>"/>
					<button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-exchange"></i>Pindah Alamat</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Batal</a>
				</div>
			</div>
		</div>
	</div>
</form>