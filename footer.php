<?php global $up_options; ?>

		<?php get_sidebar() ?>

	</div><!-- // wrapper -->
	
</div>

<div class="clear"></div>

<div id="footer">
	<p>
	    <?php echo $up_options->footertext; ?>
	</p>
</div>

<div id="notify"></div>

<div id="help">
	<dl class="directions">
		<dt>c</dt><dd><?php _e('compose new post', 'p2'); ?></dd>
		<dt>j</dt><dd><?php _e('next post/next comment', 'p2'); ?></dd>
		<dt>k</dt> <dd><?php _e('previous post/previous comment', 'p2'); ?></dd>
		<dt>r</dt> <dd><?php _e('reply', 'p2'); ?></dd>
		<dt>e</dt> <dd><?php _e('edit', 'p2'); ?></dd>
		<dt>o</dt> <dd><?php _e('show/hide comments', 'p2'); ?></dd>
		<dt>t</dt> <dd><?php _e('go to top', 'p2'); ?></dd>
		<dt>l</dt> <dd><?php _e('go to login', 'p2'); ?></dd>
		<dt>h</dt> <dd><?php _e('show/hide help', 'p2'); ?></dd>
		<dt>esc</dt> <dd><?php _e('cancel', 'p2'); ?></dd>
	</dl>
</div>

<?php wp_footer() ?>

</body>
</html>