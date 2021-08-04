	<!-- What is -->
	<div id="whatis" class="content-section-b" style="border-top: 0">
		<div class="container">
			<div class="col-md-6 col-md-offset-3 text-center wrap_title">
				<h2><?php echo $pageTitle; ?></h2>
				<?php
				if($subtitle){
					echo "<p class=\"lead\" style=\"margin-top:0\">".$subtitle."</p>";
				}
				?>
				
				
			</div>
			
			<div class="row">
			
				<div class="col-sm-8 wow fadeInDown">
				  <?php 

							if(count($posts) > 0){
								echo "
								<div class=\"blog\">
								";
								foreach($posts as $key=>$item){
									$sampul = (strlen($item["post_cover"]) > 0)? base_url()."assets/uploads/".$item["post_cover"] : "";
									$teks = fixTag($item["post_content"]);
									if(strlen($teks)>310){
										$abstrak = substr($teks,0,strpos($teks," ",300));
									}else{
										$abstrak = $teks;
									}
									$post_url = site_url("publikasi/baca/".$item["post_name"]."/");
									
									
									if($sampul != ""){
										echo "
										<div class=\"blog-item\">
											<div class=\"row\">
												<div class=\"col-xs-12 col-sm-4	text-center\">
													<div class=\"publish_date\">".indonesian_date(strtotime($item["post_date"]),"j F y","")."</div>
													<div class=\"overlay-container overlay-visible\">
													<img class=\"img-responsive\" src=\"".$sampul."\" width=\"100%\" alt=\"\" />
														<a href=\"".$post_url."\" class=\"overlay-link\">
															<i class=\"fa fa-link\"></i>
														</a>
													</div>
												</div>
														
												<div class=\"col-xs-12 col-sm-8 blog-content\">
													<h2><a href=\"".$post_url."\">".$item["post_title"]."</a></h2>
													<div>".$abstrak."... <a href=\"".$post_url."\"><em>selengkapnya</em> <i class=\"fa fa-angle-double-right\"></i></a></div>
													<ul class=\"post-meta\">
														<li><a href=\"#\"><i class=\"fa fa-user\"></i> ".$item["unama"]."</a></li>
														<li><a href=\"".$post_url."#comments\"><i class=\"fa fa-comment\"></i> ".$item["comment_count"]." Comments</a></li>
														<li><i class=\"fa fa-heart fa-fw\"></i><a href=\"#\" class=\"suka\" id=\"suka_".$key."\"><strong>".$item["post_like"]."</strong> Suka</a></li>
													</ul>
												</div>
											</div>    
										</div><!--/.blog-item-->
										";

									}else{
										echo "
										<div class=\"blog-item\">
											<div class=\"row\">
												<div class=\"col-xs-12 col-sm-4\">
													<div class=\"entry-meta\">
														<span id=\"publish_date\">".indonesian_date(strtotime($item["post_date"]),"j F","")."</span>
														<span><a href=\"#\"><i class=\"fa fa-user\"></i> ".$item["unama"]."</a></span>
														<span><a href=\"".$post_url."#comments\"><i class=\"fa fa-comment\"></i> ".$item["comment_count"]." Comments</a></span>
														<span><i class=\"fa fa-heart fa-fw\"></i><a href=\"#\" class=\"suka\" id=\"suka_".$item["post_id"]."\"><strong>".$item["post_like"]."</strong> Suka</a></span>
													</div>
												</div>
												<div class=\"col-xs-12 col-sm-8 blog-content\">
													<h2><a href=\"".$post_url."\">".$item["post_title"]."</a></h2>
													<h3>".$abstrak."... <a href=\"".$post_url."\"><em>selengkapnya</em> <i class=\"fa fa-angle-double-right\"></i></a></h3>
													
												</div>
											</div>    
										</div><!--/.blog-item-->
										";
										
									}
									
								}
								echo "
								</div>";								
							}
				  ?>
				  <!-- <p><a class="btn btn-embossed btn-primary view" role="button">View Details</a></p> -->
				</div><!-- /.col-lg-4 -->
				
				<div class="col-sm-4 wow fadeInDown text-center">
				  <img  class="rotate" src="<?php echo base_url('assets/img/'); ?>icon/picture.svg" alt="Generic placeholder image">
				   <h3>Gallery</h3>
				   <p class="lead">Epsum factorial non deposit quid pro quo hic escorol. Olypian quarrels et gorilla congolium sic ad nauseum. </p>
				   <!-- <p><a class="btn btn-embossed btn-primary view" role="button">View Details</a></p> -->
				</div><!-- /.col-lg-4 -->
				
			</div><!-- /.row -->

		</div>
	</div>
	
