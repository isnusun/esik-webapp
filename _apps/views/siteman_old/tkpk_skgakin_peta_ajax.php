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
?>
					<!--box data-->
					<div class="box box-primary box-solid">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-list-ol fa-fw"></i> Data SK GAKIN <strong><?php echo $wilayah; ?></strong></h3>
						</div>
						<!-- content index
						isi berupa rangkuman data
						-->
						<div class="box-body">

							<table class="table table-responsive table-bordered datatables">
								<thead><tr>
									<th rowspan="2">#</th>
									<th rowspan="2">Wilayah</th>
									<?php
									$i=1;
									foreach($periode as $key=>$item){
										if($i < 3){
											$toPeriode[$i] = $key;
											echo "<th colspan=\"2\">".$item['nama']."</th>";
										}
										$i++;
									}
									?>
								</tr>
								<tr>
									<?php 
									for($i=0;$i<2;$i++){
										echo "<th>RT</th><th>IDV</th>";
									}
									?>
									</tr>
								</thead>
								<tbody>
									<?php
									$nomer = 1;
									$sumRT1 = 0;
									$sumART1 = 0;
									$sumRT2 = 0;
									$sumART2 = 0;
									
									
									foreach($skgakin['sub'] as $key=>$item){
										echo "
										<tr><td class=\"angka\">".$nomer."</td>";
										if($skgakin['stingkat'] <7){
											echo "
											<td><a href=\"".site_url('skgakin/index/'.$key)."\">".$skgakin['stingkatan']." ".$item['nama']."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_rt/'.$key.'/'.$toPeriode[1])."\">".number_format($item['rt1'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_idv/'.$key.'/'.$toPeriode[1])."\">".number_format($item['art1'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_rt/'.$key.'/'.$toPeriode[2])."\">".number_format($item['rt2'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_idv/'.$key.'/'.$toPeriode[2])."\">".number_format($item['art2'],0)."</a></td>";
										}else{
											echo "
											<td>".$skgakin['stingkatan']." ".$item['nama']."</td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_rt/'.$key.'/'.$toPeriode[1])."\">".number_format($item['rt1'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_idv/'.$key.'/'.$toPeriode[1])."\">".number_format($item['art1'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_rt/'.$key.'/'.$toPeriode[2])."\">".number_format($item['rt2'],0)."</a></td>
											<td class=\"angka\"><a href=\"".site_url('skgakin/responden_idv/'.$key.'/'.$toPeriode[2])."\">".number_format($item['art2'],0)."</a></td>";
										}
										
											echo "
										</tr>
										";
										$sumART1 += $item['art1'];
										$sumRT1 += $item['rt1'];
										$sumART2 += $item['art2'];
										$sumRT2 += $item['rt2'];
										$nomer++;
									}
									?>
								</tbody>
								<tfoot>
									<tr><th colspan="2" class="angka">JUMLAH</th>
										<th class="angka"><?php echo number_format($sumRT1,0); ?></th>
										<th class="angka"><?php echo number_format($sumART1,0); ?></th>
										<th class="angka"><?php echo number_format($sumRT2,0); ?></th>
										<th class="angka"><?php echo number_format($sumART2,0); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					

