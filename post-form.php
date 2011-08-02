	<script type="text/javascript" charset="utf-8">
		jQuery(document).ready(function($) {
			jQuery('#post_cat').val($('#post-types a.selected').attr('id'));
			$('#post-types a').click(function(e) {
				jQuery('.post-input').hide();
				$('#post-types a').removeClass('selected');
				jQuery(this).addClass('selected');
				if ($(this).attr('id') == 'post') {
					jQuery('#posttitle').val("<?php echo esc_js( __('Post Title', 'p2') ); ?>");
				} else {
					jQuery('#posttitle').val('');
				}
				jQuery('#postbox-type-' + $(this).attr('id')).show();
				jQuery('#post_cat').val($(this).attr('id'));
				return false;
			});
		});
	</script>

<div id="postbox">
		<ul id="post-types">
			<li<?php if ( $_GET['p'] == 'status' || !isset($_GET['p']) ) : ?> class="selected"<?php endif; ?>><a id="status" href="<?php echo site_url( '?p=status' ) ?>" title="<?php _e( 'Status Update', 'p2' ) ?>"><span><?php _e( 'Status Update', 'p2' ) ?></span></a></li>
			<li<?php if ( $_GET['p'] == 'post' ) : ?> class="selected"<?php endif; ?>><a id="post" href="<?php echo site_url( '?p=post' ) ?>" title="<?php _e( 'Blog Post', 'p2' ) ?>"><span><?php _e( 'Blog Post', 'p2' ) ?></span></a></li>
			<li<?php if ( $_GET['p'] == 'quote' ) : ?> class="selected"<?php endif; ?>><a id="quote" href="<?php echo site_url( '?p=quote' ) ?>" title="<?php _e( 'Quote', 'p2' ) ?>"><span><?php _e( 'Quote', 'p2' ) ?></span></a></li>
			<li<?php if ( $_GET['p'] == 'link' ) : ?> class="selected"<?php endif; ?>><a id="link" href="<?php echo site_url( '?p=link' ) ?>" title="<?php _e( 'Link', 'p2' ) ?>"><span><?php _e( 'Link', 'p2' ) ?></span></a></li>
		</ul>

		<div class="avatar">
			<div class="avatar_overlay"></div>
			<?php p2_user_avatar( 'size=64' ) ?>
		</div>

		<div class="inputarea">

			<form id="new_post" name="new_post" method="post" action="<?php echo site_url(); ?>/">
				<?php if ( 'status' == p2_get_posting_type() || '' == p2_get_posting_type() ) : ?>
				<label for="posttext">
					<?php p2_user_prompt() ?>
				</label>
				<?php endif; ?>

				<div id="postbox-type-post" class="post-input <?php if ( 'post' == p2_get_posting_type() ) echo ' selected'; ?>">
					<input type="text" name="posttitle" id="posttitle" tabindex="1" value=""
						onfocus="this.value=(this.value=='<?php echo js_escape( __( 'Post Title', 'p2' ) ); ?>') ? '' : this.value;"
						onblur="this.value=(this.value=='') ? '<?php echo js_escape( __( 'Post Title', 'p2' ) ); ?>' : this.value;" />
				</div>
				<?php if ( current_user_can( 'upload_files' ) ): ?>
				<div id="media-buttons" class="hide-if-no-js">
					<?php echo P2::media_buttons(); ?>
				</div>
				<?php endif; ?>
				<textarea name="posttext" id="posttext" tabindex="1" rows="3" cols="60"></textarea>
				<div id="postbox-type-quote" class="post-input <?php if ( 'quote' == p2_get_posting_type() ) echo " selected"; ?>">
					<label for="postcitation" class="invisible"><?php _e( 'Citation', 'p2' ); ?></label>
						<input id="postcitation" name="postcitation" type="text" tabindex="2"
							value="<?php echo attribute_escape( __( 'Citation', 'p2' ) ); ?>"
							onfocus="this.value=(this.value=='<?php echo js_escape( __( 'Citation', 'p2' ) ); ?>') ? '' : this.value;"
							onblur="this.value=(this.value=='') ? '<?php echo js_escape( __( 'Citation', 'p2' ) ); ?>' : this.value;" />
				</div>
				<label class="post-error" for="posttext" id="posttext_error"></label>
				<div class="postrow">
					<input id="tags" name="tags" type="text" tabindex="2" autocomplete="off"
						value="<?php echo attribute_escape( __( 'Tag it', 'p2' ) ); ?>"
						onfocus="this.value=(this.value=='<?php echo js_escape( __( 'Tag it', 'p2' ) ); ?>') ? '' : this.value;"
						onblur="this.value=(this.value=='') ? '<?php echo js_escape( __( 'Tag it', 'p2' ) ); ?>' : this.value;" />
					<input id="submit" type="submit" tabindex="3" value="<?php echo attribute_escape( __( 'Post it', 'p2' ) ); ?>" />
				</div>
				<input type="hidden" name="post_cat" id="post_cat" value="<?php echo ( isset( $_GET['p'] ) ) ? attribute_escape( $_GET['p'] ) : 'status' ?>" />
				<span class="progress" id="ajaxActivity">
					<img src="<?php echo str_replace( WP_CONTENT_DIR, content_url(), locate_template( array( 'i/indicator.gif' ) ) ) ?>"
						alt="<?php echo attribute_escape(__('Loading...', 'p2')); ?>" title='<?php echo attribute_escape(__('Loading...', 'p2')); ?>'/>
				</span>
				<input type="hidden" name="action" value="post" />
				<?php wp_nonce_field( 'new-post' ); ?>
			</form>

		</div>

		<div class="clear"></div>

</div> <!-- // postbox -->
