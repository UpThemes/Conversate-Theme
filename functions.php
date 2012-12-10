<?php

function conversate_header_image_args($args){
	$args['width'] = 340;
	$args['height'] = 100;

	return $args;
}

add_filter('p2_custom_header_args','conversate_header_image_args');

function conversate_display_header_image(){
	if ( get_header_image() ) {
		echo "<style type='text/css'>
			#header{
				background-position: 20px center !important;
				background-repeat: no-repeat !important;
			}
			#header h1,
			#header small{
				display: none;
			}
		</style>";
	}
}

add_action('wp_head','conversate_display_header_image');