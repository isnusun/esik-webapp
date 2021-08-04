			<div class="row">
				<?php
				if($user['tingkat'] < 3){
					$statistik = false;
					echo "
					<div class=\"col-md-3 col-sm-6 col-xs-12\">
					<div class=\"info-box bg-red\">
						<span class=\"info-box-icon\"><i class=\"fa fa-line-chart\"></i></span>

						<div class=\"info-box-content\">
						<span class=\"info-box-text\">PROGRESS ENTRY DATA</span>
						<span class=\"info-box-number\">
											<a class=\"putih\" href=\"". site_url('verivali/progress/')."\"><i class=\"fa fa-refresh\"></i></a>
											</span>

						<div class=\"progress\">
							<div class=\"progress-bar\" style=\"width: ". $statistik['overall']."%\"></div>
						</div>
							<span class=\"progress-description\">
								STATISTIK
							</span>
						
						</div>
						<!-- /.info-box-content -->
					</div>
					<!-- /.info-box -->
					</div>
					<!-- /.col -->

					<div class=\"col-md-3 col-sm-6 col-xs-12\">
					<div class=\"info-box bg-red\">
						<span class=\"info-box-icon\"><i class=\"fa fa-wrench\"></i></span>

						<div class=\"info-box-content\">
						<span class=\"info-box-text\">PENGATURAN PERIODE</span>
						<a class=\"putih\" href=\"". site_url('verivali/setting_periode/')."\">
						<span class=\"info-box-number\">
							<i class=\"fa fa-clock-o\"></i>
						</span>

						<div class=\"progress\">
							<div class=\"progress-bar\" style=\"width: ". $statistik['overall']."%\"></div>
						</div>

							<span class=\"progress-description\">
								Pengaturan Periode
							</span>
						</a>
						</div>
						<!-- /.info-box-content -->
					</div>
					<!-- /.info-box -->
					</div>
					<!-- /.col -->					
					";
				}else{

					?>
				
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box bg-aqua">
							<a class="putih" href="<?php echo site_url('verval')?>">
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
							<a class="putih" href="<?php echo site_url('verval/dafta_kk')?>">
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
		
					<?php
				}
				?>
					<!-- /.col -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box bg-yellow">
							<a class="putih" href="#">
							<span class="info-box-icon"><i class="fa fa-clock-o"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Periode Aktif</span>
								<span class="info-box-number">
									<?php 
									// echo var_dump($periodes);
									echo $periodes['periode'][$periodes['periode_aktif']]['nama'];?>
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

