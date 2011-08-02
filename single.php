<?php get_header() ?>

<div class="sleeve_main">
	
	<div id="main">
		
		<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : the_post() ?>
			
				<div class="controls">
					<a href="#" id="togglecomments"><?php _e( 'Hide threads', 'p2' ); ?></a>
					&nbsp;
					<a href="#directions" id="directions-keyboard"><?php  _e( 'Keyboard Shortcuts', 'p2' ); ?></a>
				</div>
		
				<ul id="postlist">
		    		<?php p2_load_entry() // loads entry.php ?>
				</ul>
			
			<?php endwhile; ?>
			
		<?php else : ?>
			
			<ul id="postlist">
				<li class="no-posts">
			    	<h3><?php _e( 'No posts yet!', 'p2' ) ?></h3>
				</li>
			</ul>
			
		<?php endif; ?>

		<?php page_navigation(); ?>
		
	</div> <!-- main -->

</div> <!-- sleeve -->

<?php get_footer() ?>