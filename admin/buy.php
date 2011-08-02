	<?php $upthemes =  THEME_DIR.'/admin/';?>

	<script type="text/javascript">
	    var upThemes = "<?php echo THEME_DIR; ?>";
	</script>

    <div id="upthemes_framework">
    
        <div id="up_header" class="polish">
        
            <h1 id="up_logo"><a href="<?php echo get_admin_url(); ?>admin.php?page=upthemes">UpThemes Framework</a></h1>
            
            <ul id="up_topnav">
                <li class="support"><a href="http://upthemes.com/forum/">Support</a></li>
                <li class="documentation"><a href="<?php echo get_admin_url(); ?>admin.php?page=upthemes-docs">Theme Documentation</a></li>
                <li class="buy-themes current"><a href="<?php echo get_admin_url(); ?>admin.php?page=upthemes-buy">Buy Themes</a></li>
            </ul><!-- /#up_topnav -->
    
            <div class="clear"></div>
        
        </div><!-- /#up_header -->
        
        <!-- Hidden Save Changes - Important to keep wp-admin from breaking -->
        <div class="button-zone-wrapper" style="display:none;">
            <div id="button-zone" class="polish">
                <span class="top">
                    <span class="formState">Theme options have changed. Make sure to save.</span>
                    <button class="save" type="submit">Save Changes</button>
                </span>
            </div><!-- #button-zone -->
        </div><!-- /.button-zone-wrapper -->
        
        <div id="up_buy">
            <iframe src="http://upthemes.com/buy-themes/" frameborder="0"></iframe>
        </div>
    </div>
