<?php get_header() ?>
<?php $tag_obj = $wp_query->get_queried_object() ?>

<div class="sleeve_main">
	
	<div id="main">
		<h2><?php printf( __( 'Tagged: %s', 'p2' ), single_tag_title('', false) ); ?> <a class="rss" href="<?php echo get_tag_feed_link( $tag_obj->term_id ); ?>">RSS</a></h2>
	
		<?php if ( have_posts() ) : ?>
			
			<ul id="postlist">
			<?php while ( have_posts() ) : the_post() ?>
				
				<?php p2_load_entry() // loads entry.php ?>
			
			<?php endwhile; ?>
			</ul>
		
		<?php else : ?>
		
			<div class="no-posts">
			    <h3><?php _e( 'No posts found!', 'p2' ) ?></h3>
			</div>
			
		<?php endif ?>
		
		<?php page_navigation(); ?>
		
	</div> <!-- main -->

</div> <!-- sleeve -->

<?php get_footer() ?>