			<div class="row">
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box bg-aqua">
							<a class="putih" href="<?php echo site_url('backend/verval')?>">
							<span class="info-box-icon"><i class="fa fa-home"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Data Rumah Tangga</span>
								<span class="info-box-number"></span>

								<div class="progress">
									<div class="progress-bar" style="width: 75%"></div>
								</div>
										<span class="progress-description">
											Daftar Semua RTS
										</span>
								
							</div>
							</a>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box bg-green">
							<a class="putih" href="<?php echo site_url('backend/verval/dafta_kk')?>">
							<span class="info-box-icon"><i class="fa fa-newspaper-o"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Daftar Kartu Keluarga</span>
								<span class="info-box-number">
									
									</span>

								<div class="progress">
									<div class="progress-bar" style="width: 50%"></div>
								</div>
										<span class="progress-description">
											Daftar KK di 
										</span>
									 
							</div>
							</a>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->					
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box bg-yellow">
							<a class="putih" href="<?php echo site_url('backend/verval/progres/')?>">
							<span class="info-box-icon"><i class="fa fa-clock-o"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Periode Pendataan</span>
								<span class="info-box-number">
									<?php 
									// echo var_dump($periodes);
									echo $periodes['periode'][$periodes['periode_terbaru']]['nama'];?>
									</span>

								<div class="progress">
									<div class="progress-bar" style="width: 50%"></div>
								</div>
										<span class="progress-description">
											
										</span>
									 
							</div>
							</a>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->							
			</div>

<?php 

