	<!-- What is -->
	<div id="whatis" class="content-section-b" style="border-top: 0">
		<div class="container">

			<div class="col-md-6 col-md-offset-3 text-center wrap_title">
				<h2><?php echo $post['post_title']; ?></h2>
				<?php
				if($post['post_subtitle']){
					echo "<p class=\"lead\" style=\"margin-top:0\">".$post['post_subtitle']."</p>";
				}
				?>
				
				
			</div>
			
			<div class="row">
			
				<div class="col-sm-8 wow fadeInDown">
				  <?php 
					if($post['post_content']){
						echo $post['post_content'];
					}
				  
				  ?>
				  <!-- <p><a class="btn btn-embossed btn-primary view" role="button">View Details</a></p> -->
				</div><!-- /.col-lg-4 -->
				
				<div class="col-sm-4 wow fadeInDown text-center">
					<!--public right sidebar / widget -->
					
				  <img  class="rotate" src="<?php echo base_url('assets/img/'); ?>icon/picture.svg" alt="Generic placeholder image">
				   <h3>Gallery</h3>
				   <p class="lead">Epsum factorial non deposit quid pro quo hic escorol. Olypian quarrels et gorilla congolium sic ad nauseum. </p>
				   <!-- <p><a class="btn btn-embossed btn-primary view" role="button">View Details</a></p> -->
				</div><!-- /.col-lg-4 -->
				
			</div><!-- /.row -->

		</div>
	</div>
	
