<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	
	<?php wp_head() ?>
	<?php global $up_options, $current_user; get_currentuserinfo(); ?>
</head>
<body <?php body_class() ?>>

<div id="header">

    <div id="user_login">
    
	<?php if( !is_user_logged_in() ): ?>
    
        <a href="<?php echo wp_login_url( logout_redirect() ); ?>"><?php _e('Log In'); ?></a>
        <?php wp_register('', ''); ?>
    
    <?php else: ?>

        <a href="<?php echo wp_logout_url( logout_redirect() ); ?>"><?php _e('Log Out'); ?> (<?php echo $current_user->display_name; ?>)</a>
    
    <?php endif; ?>
	
    </div>
    	
	<div class="sleeve">
		
			<?php branding(); ?>

	</div>
     
</div>

<div id="wrapper">