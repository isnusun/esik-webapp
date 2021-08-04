			<div class="row">
				<?php
				if($statistik){
				?>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="info-box bg-green">
							<span class="info-box-icon"><i class="fa fa-home"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Rumah Tangga Sasaran</span>
								<span class="info-box-number"><a class="putih" href="<?php echo site_url('verivali/done/rts');?>"><?php echo number_format($statistik['done']['rts'],0);?></a></span>

								<div class="progress">
									<div class="progress-bar" style="width: <?php echo $statistik['persen']['rts'];?>%"></div>
								</div>
										<span class="progress-description">
											<?php echo $statistik['persen']['rts'];?>% dari (<?php echo number_format($statistik['todo']['rts'],0);?>)
										</span>
								
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="info-box bg-green">
							<span class="info-box-icon"><i class="fa fa-newspaper-o"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Kartu Keluarga</span>
								<span class="info-box-number">
									<a class="putih" href="<?php echo site_url('verivali/done/kks');?>"><?php echo number_format($statistik['done']['kks'],0);?></a>
									<!--
									<?php echo number_format($statistik['done']['kks'],0);?>
									-->
									</span>

								<div class="progress">
									<div class="progress-bar" style="width: <?php echo $statistik['persen']['kks'];?>%"></div>
								</div>
										<span class="progress-description">
											<?php echo $statistik['persen']['kks'];?>% dari (<?php echo number_format($statistik['todo']['kks'],0);?>)
										</span>
									 
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="info-box bg-yellow">
							<span class="info-box-icon"><i class="fa fa-users"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Jiwa</span>
								<span class="info-box-number">
									<a class="putih" href="<?php echo site_url('verivali/done/idv');?>"><?php echo number_format($statistik['done']['idv'],0);?></a>
									</span>

								<div class="progress">
									<div class="progress-bar" style="width: <?php echo $statistik['persen']['idv'];?>%"></div>
								</div>
										<span class="progress-description">
											<?php echo $statistik['persen']['idv'];?>% dari (<?php echo number_format($statistik['todo']['idv'],0);?>)
										</span>
								
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->	
					
				<?php
				
				} // endof statistik

				if($user['tingkat'] < 3){
					/*
					 * Blok Data 
					 * */
					echo "

					<div class=\"col-md-4 col-sm-6 col-xs-12\">
						<div class=\"info-box bg-aqua\">
							<span class=\"info-box-icon\"><i class=\"fa fa-database\"></i></span>

							<div class=\"info-box-content\">
								<span class=\"info-box-text\">Data</span>
								<span class=\"info-box-number\">
									<a class=\"putih\" href=\"". site_url('pbdt/indikator/rts/'.$kode)."\"><i class=\"fa fa-cog\"></i> Indikator RTS</a>
									</span>

								<div class=\"progress\">
									<div class=\"progress-bar\" style=\"width: 70%\"></div>
								</div>
										<span class=\"progress-description\">
											Data Per Indikator
										</span>
								
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->

					<div class=\"col-md-4 col-sm-6 col-xs-12\">
						<div class=\"info-box bg-aqua\">
							<span class=\"info-box-icon\"><i class=\"fa fa-database\"></i></span>

							<div class=\"info-box-content\">
								<span class=\"info-box-text\">Data</span>
								<span class=\"info-box-number\">
									<a class=\"putih\" href=\"". site_url('pbdt/indikator/art/'.$kode)."\"><i class=\"fa fa-cog\"></i> Indikator ART</a>
									</span>

								<div class=\"progress\">
									<div class=\"progress-bar\" style=\"width: 70%\"></div>
								</div>
										<span class=\"progress-description\">
											Data Per Indikator
										</span>
								
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->


					
					<div class=\"col-md-4 col-sm-6 col-xs-12\">
						<div class=\"info-box bg-green\">
							<span class=\"info-box-icon\"><i class=\"fa fa-search\"></i></span>

							<div class=\"info-box-content\">
								<span class=\"info-box-text\">Data</span>
								<span class=\"info-box-number\">
									<a class=\"putih\" href=\"". site_url('pbdt/customquery/')."\"><i class=\"fa fa-filter\"></i> QUERY DATA</a>
									</span>

								<div class=\"progress\">
									<div class=\"progress-bar\" style=\"width: 70%\"></div>
								</div>
										<span class=\"progress-description\">
											Query Data Disesuaikan
										</span>
								
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->										


					<div class=\"col-md-4 col-sm-6 col-xs-12\">
						<div class=\"info-box bg-green\">
							<span class=\"info-box-icon\"><i class=\"fa fa-search\"></i></span>

							<div class=\"info-box-content\">
								<span class=\"info-box-text\">Data</span>
								<span class=\"info-box-number\">
									<a class=\"putih\" href=\"". site_url('pbdt/customquery_idv/')."\"><i class=\"fa fa-filter\"></i> QUERY DATA ART</a>
									</span>

								<div class=\"progress\">
									<div class=\"progress-bar\" style=\"width: 70%\"></div>
								</div>
										<span class=\"progress-description\">
											Query Data Individu Disesuaikan
										</span>
								
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->										

					";
					
					
					/*
					 * Blok Pengaturan 
					 * */
					if($user['tingkat'] < 1){
						echo "
						<div class=\"col-md-4 col-sm-6 col-xs-12\">
							<div class=\"info-box bg-red\">
								<span class=\"info-box-icon\"><i class=\"fa fa-cogs\"></i></span>

								<div class=\"info-box-content\">
									<span class=\"info-box-text\">PENGATURAN</span>
									<span class=\"info-box-number\">
										<a class=\"putih\" href=\"". site_url('pbdt/pengaturan/rts')."\"><i class=\"fa fa-cog\"></i> Indikator RTS</a>
										</span>

									<div class=\"progress\">
										<div class=\"progress-bar\" style=\"width: 70%\"></div>
									</div>
											<span class=\"progress-description\">
												Indikator RTS / KK
											</span>
									
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->

						<div class=\"col-md-4 col-sm-6 col-xs-12\">
							<div class=\"info-box bg-red\">
								<span class=\"info-box-icon\"><i class=\"fa fa-cogs\"></i></span>

								<div class=\"info-box-content\">
									<span class=\"info-box-text\">PENGATURAN</span>
									<span class=\"info-box-number\">
										<a class=\"putih\" href=\"". site_url('pbdt/pengaturan/art')."\"><i class=\"fa fa-cog fa-fw\"></i>Indikator ART</a>
										</span>

									<div class=\"progress\">
										<div class=\"progress-bar\" style=\"width: 70%\"></div>
									</div>
											<span class=\"progress-description\">
												Indikator Individu/ART
											</span>
									
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
						<div class=\"col-md-4 col-sm-6 col-xs-12\">
							<div class=\"info-box bg-red\">
								<span class=\"info-box-icon\"><i class=\"fa fa-cogs\"></i></span>

								<div class=\"info-box-content\">
									<span class=\"info-box-text\">PENGATURAN</span>
									<span class=\"info-box-number\">
										<a class=\"putih\" href=\"". site_url('pbdt/pengaturan/periode')."\"><i class=\"fa fa-calendar fa-fw\"></i>Periode Data</a>
										</span>

									<div class=\"progress\">
										<div class=\"progress-bar\" style=\"width: 70%\"></div>
									</div>
											<span class=\"progress-description\">
												Periode Pemutakhiran Data
											</span>
									
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->

					";
					}
				}
				?>
					
				
			</div>
