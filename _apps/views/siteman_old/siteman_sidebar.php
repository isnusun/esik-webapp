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
          <!-- /.search form -->
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
            /*
             * Menu Admin
             * */
            if($user['tingkat'] == 0){
					$strA = ($this->uri->segment(1, 0) == "admin") ? "active" : "";
					echo "
					<li class=\"". $strA." treeview\">
						<a href=\"#\">
							<i class=\"fa fa-files-o\"></i>
							<span>Administrasi Sistem</span>
							<i class=\"fa fa-angle-left pull-right\"></i>
						</a>
						<ul class=\"treeview-menu\">
							<li><a href=\"".site_url('admin/pengguna')."\"><i class=\"fa fa-users\"></i> Kelola Pengguna</a></li>
							<li><a href=\"".site_url('admin/lembaga')."\"><i class=\"fa fa-building-o\"></i> Data Lembaga</a></li>
							<li><a href=\"".site_url('admin/laman')."\"><i class=\"fa fa-sitemap\"></i> Halaman Statis Web</a></li>
							<li><a href=\"".site_url('admin/konfigurasi')."\"><i class=\"fa fa-cogs\"></i> Konfigurasi Sistem</a></li>
							<li><a href=\"".site_url('admin/subsites')."\"><i class=\"fa fa-globe\"></i> Web Desa/Kelurahan</a></li>
						</ul>
					</li>
					
					";
				}

				if($user['tingkat'] == 3){
					// Petugas Kelurahan 
					$strA = ($this->uri->segment(1, 0) == "administrasi") ? "active" : "";
					echo "
					<li class=\"". $strA." \"><a href=\"".site_url('backend/administrasi')."\"><i class=\"fa fa-cubes\"></i> <span>Administrasi dan Kependudukan</span></a></li>
					";

						
				}
				$strA = (($this->uri->segment(1, 0) == "bdt2015")) ? "active" : "";
				echo "
				<li class=\"". $strA." \"><a href=\"".site_url("bdt2015")."\"><i class=\"fa fa-vcard on text-blue\"></i> PBDT 2015</a></li>
				";

				$strA = (($this->uri->segment(1, 0) == "pbdt") ||($this->uri->segment(1, 0) == "pbk")) ? "active" : "";
				echo "
				<li class=\"". $strA." \"><a href=\"".site_url("pbdt")."\"><i class=\"fa fa-vcard on text-blue\"></i> Basis Data Terpadu (PBDT)</a></li>
				";
				$strA = (($this->uri->segment(1, 0) == "layanan_pkh") || ($this->uri->segment(1, 0) == "layanan_jamsos") || ($this->uri->segment(1, 0) == "bpnt")) ? "active" : "";
				// echo "
				// <li class=\"". $strA." \"><a href=\"".site_url('layanan_jamsos')."\"><i class=\"fa fa-gears\"></i> <span>Layanan Jaminan Sosial</span></a></li>
				// ";

				echo "
				<li class=\"". $strA." \">
					<a><i class=\"fa fa-empire\"></i> <span>Layanan Jaminan Sosial</span> <i class=\"fa fa-angle-left pull-right\"></i>
					<ul class=\"treeview-menu\">";
						$strA = ($this->uri->segment(1, 0) == "layanan_jamsos") ? "active" : "";
						echo "<li class='".$strA."'><a href=\"".site_url("layanan_jamsos/pbi")."\"><i class=\"fa fa-handshake-o\"></i> Program Bantuan Iuran</a></li>";

						$strA = ($this->uri->segment(1, 0) == "layanan_pkh") ? "active" : "";
						echo "<li class='".$strA."'><a href=\"".site_url("layanan_pkh")."\"><i class=\"fa fa-handshake-o\"></i> Program Keluarga Harapan</a></li>";

						$strA = ($this->uri->segment(1, 0) == "bpnt") ? "active" : "";
						echo "<li class='".$strA."'><a href=\"".site_url("bpnt")."\"><i class=\"fa fa-handshake-o\"></i> Bantuan Pangan Non Tunai</a></li>
					</ul>
				</li>";
				/*
						<li><a href=\"".site_url("pbk")."\"><i class=\"fa fa-vcard on text-red\"></i> Data Lokal</a></li>
				$strA = ($this->uri->segment(1, 0) == "skgakin") ? "active" : "";
				echo "
				<li class=\"". $strA." \"><a href=\"".site_url('skgakin')."\"><i class=\"fa fa-book\"></i> <span>SK GAKIN</span></a></li>
				";
				$strA = ($this->uri->segment(1, 0) == "laporan") ? "active" : "";
				echo "
				<li class=\"". $strA." \"><a href=\"".site_url('laporan')."\"><i class=\"fa fa-pie-chart\"></i> <span>Visualisasi Data</span></a></li>
				";

				$strA = (($this->uri->segment(1, 0) == "program_bantuan") ||($this->uri->segment(1, 0) == "program_bansos")) ? "active" : "";
				echo "
				<li class=\"". $strA." \">
					<a><i class=\"fa fa-cubes\"></i> <span>Program Bantuan</span> <i class=\"fa fa-angle-left pull-right\"></i>
					<ul class=\"treeview-menu\">
						<li><a href=\"".site_url('program_bantuan')."\"><i class=\"fa fa-cubes\"></i> <span>Layanan Program Kegiatan</span></a></li>
						<li><a href=\"".site_url('program_bansos')."\"><i class=\"fa fa-cubes\"></i> <span>Layanan Jaminan Sosial</span></a></li>
					</ul>
				</li>
				";

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

            ?>

          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
