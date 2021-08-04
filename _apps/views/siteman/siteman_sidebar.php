	<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
			<!-- Sidebar user panel -->
			<div class="user-panel">
				<div class="pull-left image">
					<img src="<?php echo $user['foto']['s']; ?>" class="img-circle" alt="User Image">
				</div>
				<div class="pull-left info">
					<p><?php echo $user['nama']; ?></p>
					<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
				</div>
			</div>
			<!-- search form 
			<form action="#" method="get" class="sidebar-form">
			<div class="input-group">
				<input type="text" name="q" class="form-control" placeholder="Search...">
				<span class="input-group-btn">
				<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
				</span>
			</div>
			</form>
			 /.search form -->
			
			<!-- sidebar menu: : style can be found in sidebar.less -->
			<?php 
			$strA = ($this->uri->segment(1, 0) == "siteman") ? "active" : "";
			?>
			<ul class="sidebar-menu">
				<li class="header">MENU UTAMA</li>
				<li class="<?php echo $strA; ?> treeview">
					<a href="<?php echo site_url('siteman');?>">
					<i class="fa fa-dashboard"></i> <span>Dashboard</span>
					</a>
				</li>
				<?php 
				if($user['tingkat'] >= 3){
					// Petugas Kelurahan 
					$strA = ($this->uri->segment(2, 0) == "administrasi") ? "active" : "";
					echo "
					<li class=\"". $strA." \"><a href=\"".site_url('backend/administrasi')."\"><i class=\"fa fa-map-marker\"></i> <span>Administrasi dan Kependudukan</span></a></li>
					";
				}

				$strA = (($this->uri->segment(2) == "bdt2015")) ? "active" : "";
				echo "
				<li class=\"". $strA." \"><a href=\"".site_url("backend/bdt2015")."\"><i class=\"fa fa-vcard on text-blue\"></i> PBDT 2015</a></li>
				";

				$strA = (($this->uri->segment(2, 0) == "pbdt")) ? "active" : "";
				echo "
				<li class=\"". $strA." \"><a href=\"".site_url("backend/pbdt")."\"><i class=\"fa fa-vcard on text-blue\"></i> DTKS</a></li>";
				$strA = (($this->uri->segment(2, 0) == "verval")) ? "active" : "";
					/*
				echo "
				<li class=\"". $strA." \"><a href=\"".site_url("backend/verval")."\"><i class=\"fa fa-vcard on text-blue\"></i> Verivali Data BDT</a></li>";

				$strA = (($this->uri->segment(2, 0) == "pkh") || ($this->uri->segment(2, 0) == "pbi") || ($this->uri->segment(2, 0) == "bpnt")) ? "active" : "";
				echo "
				<li class=\"". $strA." \">
					<a><i class=\"fa fa-empire\"></i> <span>Layanan Jaminan Sosial</span> <i class=\"fa fa-angle-left pull-right\"></i>
					<ul class=\"treeview-menu\">";
						$strA = ($this->uri->segment(2, 0) == "pbi") ? "active" : "";
						echo "<li class='".$strA."'><a href=\"".site_url("backend/pbi")."\"><i class=\"fa fa-handshake-o\"></i> Program Bantuan Iuran</a></li>";

						$strA = ($this->uri->segment(2, 0) == "pkh") ? "active" : "";
						echo "<li class='".$strA."'><a href=\"".site_url("backend/pkh")."\"><i class=\"fa fa-handshake-o\"></i> Program Keluarga Harapan</a></li>";

						$strA = ($this->uri->segment(2, 0) == "bpnt") ? "active" : "";
						echo "<li class='".$strA."'><a href=\"".site_url("backend/bpnt")."\"><i class=\"fa fa-handshake-o\"></i> Bantuan Pangan Non Tunai</a></li>
					</ul>
				</li>";

				$strA = (($this->uri->segment(1, 0) == "posts") ||($this->uri->segment(1, 0) == "galeri")) ? "active" : "";
					echo "
					<li class=\"". $strA." \">
						<a><i class=\"fa fa-send\"></i> <span>Publikasi</span> <i class=\"fa fa-angle-left pull-right\"></i>
						<ul class=\"treeview-menu\">
							<li><a href=\"".site_url('posts')."\"><i class=\"fa fa-newspaper-o fa-fw\"></i> <span>Artikel/Publikasi</span></a></li>
							
						</ul>
					</li>
					";
					// <li><a href=\"".site_url('galeri')."\"><i class=\"fa fa-image fa-fw\"></i> <span>Galeri Foto/Video</span></a></li>
		*/						
				/*
				* Menu Admin
				* */
				if($user['tingkat'] <= 2){
					$admin_menus = array('pengguna','laman','settings');
					$strA = (in_array($this->uri->segment(2, 0),$admin_menus)) ? "active" : "";
					echo "
					<li class=\"". $strA." treeview\">
						<a href=\"#\">
							<i class=\"fa fa-files-o\"></i>
							<span>Pengaturan Sistem</span>
							<i class=\"fa fa-angle-left pull-right\"></i>
						</a>
						<ul class=\"treeview-menu\">";
							$strA = ($this->uri->segment(2, 0) == "pengguna") ? "active" : "";
							echo "<li class='".$strA."'><a href=\"".site_url('backend/pengguna')."\"><i class=\"fa fa-users\"></i> Kelola Pengguna</a></li>";
							// $strA = ($this->uri->segment(2, 0) == "laman") ? "active" : "";
							// echo "<li class='".$strA."'><a href=\"".site_url('backend/laman')."\"><i class=\"fa fa-sitemap\"></i> Halaman Statis Web</a></li>";
							// $strA = ($this->uri->segment(2, 0) == "settings") ? "active" : "";
							// echo "<li class='".$strA."'><a href=\"".site_url('backend/settings')."\"><i class=\"fa fa-cogs\"></i> Konfigurasi Sistem</a></li>";
							echo "
						</ul>
					</li>
					
					";
				}
				/**
				 * Logout
				 */
				echo "
				<li><a href=\"".site_url("siteman/logout")."\"><i class=\"fa fa-sign-out on text-red\"></i> Log Out</a></li>
				";

				?>
			</ul>
		</section>
		<!-- /.sidebar -->
	</aside>
