	<?php $upthemes =  THEME_DIR.'/admin/';?>
	
	<script type="text/javascript">
	    var upThemes = "<?php echo THEME_DIR; ?>";
	</script>

    <div id="upthemes_framework">
    
        <div id="up_header" class="polish">
        
            <h1 id="up_logo"><a href="<?php echo get_admin_url(); ?>admin.php?page=upthemes">UpThemes Framework</a></h1>
            
            <ul id="up_topnav">
                <li class="support"><a href="http://upthemes.com/forum/">Support</a></li>
                <li class="documentation current"><a href="<?php echo get_admin_url(); ?>admin.php?page=upthemes-docs">Theme Documentation</a></li>
                <li class="buy-themes"><a href="<?php echo get_admin_url(); ?>admin.php?page=upthemes-buy">Buy Themes</a></li>
            </ul><!-- /#up_topnav -->
    
            <div class="clear"></div>
        
        </div><!-- /#up_header -->
	
	<!--	Hidden Save Changes - Important to keep wp-admin from breaking -->
	<div class="button-zone-wrapper" style="display:none;">
            <div id="button-zone" class="polish">
                <span class="top">
                    <span class="formState">Theme options have changed. Make sure to save.</span>
                    <button class="save" type="submit">Save Changes</button>
                </span>
            </div><!-- #button-zone -->
        </div><!-- /.button-zone-wrapper -->
	
        <div id="up_docs">
	    <?php
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, THEME_DIR."/readme.html");
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    echo $data;
	    ?>
	    
	</div>
    </div>