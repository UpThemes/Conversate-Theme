<?php
/*
    UpThemes Framework
*/

// Framework Version Number
define('UPTHEMES_VER', '0.3.9');

// ---------------  Theme Constants from style.css -------------------- //

$get_up_theme = get_theme_data(TEMPLATEPATH .'/style.css');
$theme_title = $get_up_theme['Title'];
$theme_shortname = strtolower(preg_replace('/ /', '_', $theme_title));
$theme_version = $get_up_theme['Version'];
$theme_template = $get_up_theme['Template'];
define('UPTHEMES_NAME', $theme_title);
define('UPTHEMES_SHORT_NAME', $theme_shortname);
define('UPTHEMES_THEME_VER', $theme_version);

if( !empty($theme_template) ):
	define( 'THEME_PATH' , STYLESHEETPATH );
	define( 'THEME_DIR' , get_bloginfo("stylesheet_directory") );
else:
	define( 'THEME_PATH' , TEMPLATEPATH );
	define( 'THEME_DIR' , get_bloginfo("template_directory") );
endif;

$uploads_dir = wp_upload_dir();

$base_upload_dir = $uploads_dir['basedir']."/upfw";
$base_upload_url = $uploads_dir['baseurl']."/upfw";

if(!is_dir($base_upload_dir)) { 
	$oldumask = umask(0);
	mkdir($base_upload_dir, 0777);
	umask($oldumask);
}

if($base_upload_dir)
	define('UPFW_UPLOADS_DIR',$base_upload_dir, true);

if($base_upload_url)
	define('UPFW_UPLOADS_URL',$base_upload_url, true);

function show_gallery_images(){
	global $wpdb; 

	$theme = THEME_DIR;
	
	$path = UPFW_UPLOADS_DIR."/";
	$dir_handle = @opendir($path) or die("Unable to open folder. Please make sure your /wp-content/uploads/ folder exists and has permissions of 777.");
	
	while (false !== ($file = readdir($dir_handle))):
	
		if($file == "index.php") continue;
		if($file == ".") continue;
		if($file == "..") continue;
		if($file == "list.php") continue;
		if($file == "Thumbs.db") continue;
		$list .= '<a class="preview" href="'. UPFW_UPLOADS_URL . "/". $file . '"><img src="' . UPFW_UPLOADS_URL . "/" . $file . '" /></a>';

	endwhile;
    
    $list .= '<div class="clear"></div>';
    
	echo $list;
	
	closedir($dir_handle);
	die();

}

add_action('wp_ajax_show_gallery_images','show_gallery_images');

// -------- CSS and JavaScript Includes for UpThemes Framework --------- //

function get_scripts_styles(){

	$upthemes =  THEME_DIR.'/admin/';
	
	wp_enqueue_style('up_framework',$upthemes."css/up_framework.css");
	
	//Check if theme-options/style.css exists and load it
	if(file_exists(THEME_PATH ."/theme-options/style.css")):
		wp_enqueue_style('theme_options',THEME_DIR."/theme-options/style.css");
	endif;
	
	wp_enqueue_style('farbtastic');
	
	wp_enqueue_script('jquery.history',
		$upthemes."js/jquery.history.js",
		array('jquery'));
	
	wp_enqueue_script('jquery.color',
		$upthemes."js/jquery.color.js",
		array('jquery'));

	wp_enqueue_script('ajaxupload',
		$upthemes."js/ajaxupload.js",
		array('jquery'));
	
	wp_enqueue_script('upfw', 
		$upthemes . "js/up_framework.js", 
		array('farbtastic','jquery.history','ajaxupload'));
}

function is_upthemes_page(){

	if( $_REQUEST['page']=='upthemes' || $_REQUEST['page']=='upthemes-buy' || $_REQUEST['page']=='upthemes-docs' )
		return true;
	else
		return false;
		
}

//Check if on framework pages
if( is_upthemes_page() )	
	add_action( 'admin_init', 'get_scripts_styles' );

// ---------------  Create Options Tabs -------------------- //

// Discover Options Files and Create Tabs Array
if( is_admin() ):
    $path = THEME_PATH."/theme-options/";
    $directory = @opendir($path) or die("Cannot open theme-options folder in the ".UPTHEMES_NAME." folder.");
    while (false !== ($file = readdir($directory))) {
		if(!preg_match('/_/', $file)) continue;
		if(preg_match('/_notes/', $file)) continue;
		if(preg_match('/.DS/', $file)) continue;
        
        //Take the extension off
        $file = explode('.php', $file);
        
        //Separate the ordinal
        $file = explode('_', $file[0]);
        $order = $file[1];
        //Define the shortname
        $shortname = $file[0];
        
        //Define the title
        $file = explode('-', $shortname);
        foreach ($file as $part):
            $title .= $part." ";
        endforeach;
        $title = ucwords($title);
        
        //Add tab to array
        global $up_tabs;
        $up_tabs[$order] =  array(trim($title) => $shortname);
        $title = '';
    }
    closedir($directory);
    
    //Sort tab order
    global $up_tabs;
    ksort($up_tabs);
endif;

// -------------- Create Default Options --------------------//

function up_defaults(){
	
	if(!get_option('up_themes_'.UPTHEMES_SHORT_NAME) && $_GET['page']!="upthemes"):
			
		//Redirect to options page where defaults will automatically be set
		header('Location: '.get_bloginfo('url').'/wp-admin/admin.php?page=upthemes');
		exit;

	endif;

}

if( is_admin() )
	add_action('admin_init', 'up_defaults');

// ---------------  Global Theme Options -------------------- //

$up_options_db = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
global $up_options;
//Check if options are stored properly
if(is_array($up_options_db)):
    //Check array to an object
    foreach ($up_options_db as $k => $v) {
	$up_options -> {$k} = $v;
    }
endif;

up_multiple($update_option->theme1);

// ---------------  UpThemes Admin Options ---------------------- //

if( is_admin() ):
    add_action('admin_menu', 'upthemes_admin');

function upthemes_admin() {
	
  add_menu_page(UPTHEMES_NAME. ' Options', UPTHEMES_NAME, '10', 'upthemes', 'upthemes_admin_home', THEME_DIR.'/admin/images/upfw_ico_up_16x16.png', 36);
  
	//Create tabbed pages from array
	global $up_tabs;
	if( is_array( $up_tabs ) ):
		foreach ($up_tabs as $tab):
			foreach($tab as $title => $shortname):
				add_submenu_page('upthemes', $title, $title, '10', 'upthemes#/'.$shortname, 'upthemes_admin_'.$shortname);
			endforeach;
		endforeach;
	endif;

  //Static subpages
	add_submenu_page('upthemes', 'Import/Export', 'Import/Export', '10', 'upthemes#/import-export', 'upthemes_admin_import_export');
	add_submenu_page('upthemes', 'Documentation', 'Documentation', '10', 'upthemes-docs', 'upthemes_admin_docs');
	add_submenu_page('upthemes', 'Buy Themes', 'Buy Themes', '10', 'upthemes-buy', 'upthemes_admin_buy');
}

function upthemes_admin_home() {require_once('home.php');}
function upthemes_admin_docs(){require_once('docs.php');}
function upthemes_admin_buy(){require_once('buy.php');}
function upthemes_admin_import_export(){require_once('import-export.php');}

// ---------------  Render Individual Options -------------------- //

    function find_defaults($options){
        global $up_defaults;
        print_r($options);
    }

// ---------------  Render Individual Options -------------------- //


    function render_options($options){
        global $up_options;
        global $wpdb;
        foreach ($options as $value) {
            //Check if there are additional attributes
            if(is_array($value['attr'])):
                $i = $value['attr'];
                global $attr;
                //Convert array into a string
                foreach($i as $k => $v):
                    $attr .= $k.'="'.$v.'" ';
                endforeach;
            endif;
            
            //Determine the type of input field
            switch ( $value['type'] ) {
                
                //Render Text Input
                case 'text':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                <input type="text" name="<?php echo $value['id']; ?>" value="<?php if($up_options->$value['id']): echo $up_options->$value['id']; else: echo $value['value']; endif;?>" id="<?php echo $value['id']; ?>" <?php echo $attr; ?> />
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;
                
                //Render Custom User Text Inputs
                case 'text_list':?>
                    <li id="<?php echo $value['id']; ?>_list">
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        <script type="text/javascript">
                            jQuery(function(){
                                jQuery('#<?php echo $value['id']; ?>_list p.add_text_list a').live('click', function(){
                                    jQuery(this).parents('li').find('div.text_list').append('<div class="entry"><input class="text_list" type="text" name="<?php echo $value['id']; ?>[]" /><p class="delete_text_list"><a href="#"><img src="<?php echo THEME_DIR;?>/admin/images/text_list_delete.jpg" alt="Delete Text Field" /></a></p></div><div class="clear"></div>');
                                    jQuery(this).parents('li').find('input.hiddentext_list').remove();
                                    return false;
                                });
                                jQuery('#<?php echo $value['id']; ?>_list p.delete_text_list a').live('click', function(){
                                    var parent = jQuery(this).parent();
                                    jQuery(parent).parent().fadeOut(function(){
                                        jQuery(this).remove();
                                    });
                                    var textList = jQuery(this).parents('li').find('div.text_list .entry').length;
                                    if(textList == 1){jQuery(this).parents('li').find('div.text_list').append('<input class="hiddentext_list" type="hidden" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>[]" />');}
                                    return false;
                                });
                            });
                        </script>
                        <fieldset class="data">
                            <div class="inner">
                                <div class="text_list">
                                    <p class="add_text_list"><a href="#">Add New Field</a></p>
                                    <?php
                                    if($up_options->$value['id']):
                                        if(is_array($up_options->$value['id'])):
                                            foreach($up_options->$value['id'] as $text):?>
                                                <div class="entry">
                                                    <input class="text_list" type="text" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" value="<?php echo $text?>" <?php echo $attr; ?> />
                                                    <p class="delete_text_list"><a href="#"><img src="<?php echo THEME_DIR; ?>/admin/images/text_list_delete.jpg" alt="Delete Text Field" /></a></p>
                                                    <div class="clear"></div>
                                                </div>
                                            <?php endforeach;
                                        endif;
                                    else:
                                        if($value['value']):
                                            if(preg_match('/,/', $value['value'])):
                                                $list = explode(', ', $value['value']);
                                                foreach($list as $text):?>
                                                        <div class="entry">
                                                            <input class="text_list" type="text" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" value="<?php echo $text?>" <?php echo $attr; ?> />
                                                            <p class="delete_text_list"><a href="#"><img src="<?php echo THEME_DIR; ?>/admin/images/text_list_delete.jpg" alt="Delete Text Field" /></a></p>
                                                            <div class="clear"></div>
                                                        </div>
                                                <?php endforeach;
                                            else:
                                                if($value['value'] == $v ):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            endif;
                                        endif;
                                    endif;?>
                                    
                                </div>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;
                
                //Render textarea options
                case 'textarea':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                <textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>><?php if($up_options->$value['id']): echo $up_options->$value['id']; else: echo $value['value']; endif;?></textarea>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;
                
                //Render select dropdowns
                case 'select':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                    <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                    <?php
                                    if(is_array($value['options'])):
                                        $i = $value['options'];
                                        foreach($i as $k => $v):
                                            if($up_options->$value['id']):
                                                if($up_options->$value['id'] == $v):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            else:
                                                if($value['value'] == $v):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            endif;?>
                                            <option value="<?php echo $v?>"<?php echo $selected?>><?php echo $k?></option>
                                            <?php $selected = '';?>
                                        <?php endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;
                
                //Render multple selects
                case 'multiple':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                <select MULTIPLE name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                    <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                    <?php
                                    if(is_array($value['options'])):
                                        $i = $value['options'];
                                        foreach($i as $k => $v):
                                            if($up_options->$value['id']):
                                                if(is_array($up_options->$value['id'])):
                                                    foreach($up_options->$value['id'] as $std):
                                                        if($v == $std):
                                                            $selected = ' selected = "selected"';
                                                        endif;
                                                    endforeach;
                                                endif;
                                            else:
                                                
                                                if($value['value']):
                                                    if(preg_match('/,/', $value['value'])):
                                                        $cats = explode(', ', $value['value']);
                                                        foreach($cats as $cat):
                                                            if(preg_match('/\b'.$v.'\b/', $cat)):
                                                                $selected = ' selected = "selected"';
                                                            endif;
                                                        endforeach;
                                                    else:
                                                        if($value['value'] == $v ):
                                                            $selected = ' selected = "selected"';
                                                        endif;
                                                    endif;
                                                else:
                                                    if($value['value'] == $v ):
                                                        $selected = ' selected = "selected"';
                                                    endif;
                                                endif;
                                                
                                            endif;?>
                                            <option value="<?php echo $v?>"<?php echo $selected?>><?php echo $k?></option>
                                            <?php $selected = '';?>
                                        <?php endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;
                
                //Render color picker
                case 'color':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="awesome3"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        <fieldset class="data">
                            <div class="inner">
                                <span class="colorPickerWrapper">
                                    <input type="text" class="popup-colorpicker" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php if($up_options->$value['id']): echo $up_options->$value['id']; else: echo $value['value']; endif;?>" <?php echo $attr; ?> />
                                    <div class="popup-guy">
                                        <div class="popup-guy-inside">
                                            <div id="<?php echo $value['id']; ?>picker" class="color-picker"></div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php
                break;
                
                //Render upload image
                case 'image':?>
                    <script type="text/javascript">
                        jQuery(function(){
                            
                            //View UpThemes Gallery
                            jQuery('a#<?php echo $value['id']; ?>viewgallery').toggle(
                                function(){
                                    jQuery(this).text('Hide Gallery');
                                    jQuery('#<?php echo $value['id']; ?>allimages').slideDown();
                                    
									var data = {
										action: 'show_gallery_images',
										id: '<?php echo $value['id']; ?>'
									};
								
									// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
									jQuery.post(ajaxurl, data, function(response) {
										jQuery('#<?php echo $value['id']; ?>allimages .thumbs').html(response);
									});
									
									return false;
                                },
                                function(){
                                    jQuery(this).text('Select from the UpThemes Gallery');
                                    jQuery('#<?php echo $value['id']; ?>allimages').slideUp();
                                    								                                    
                                    return false;
                                }
                            );
                            
                            //Select and image from the gallery
                            jQuery('#<?php echo $value['id']; ?>allimages a').live('click', function(){
                                //Add image source to hidden input
                                jQuery('input#<?php echo $value['id']; ?>').attr('value', jQuery(this).attr('href'));
                                //Send image to preview
                                jQuery('#<?php echo $value['id']; ?>preview').html('<img src="'+jQuery(this).attr('href')+'" alt="<?php echo $value['id']; ?> Image" />');
                                //Save Me Fool
                                jQuery('#button-zone').animate({ 
                                    backgroundColor: '#555',
                                    borderLeftColor: '#555',
                                    borderRightColor: '#555'
                                });
                                jQuery('#button-zone button').addClass('save-me-fool');
                                jQuery('.formState').fadeIn( 400 );
                                return false;
                            });
                            <?php //Upload Security
			    			$upload_security = md5($_SERVER['SERVER_ADDR']); ?>
                            //Upload an Image
                            var <?php echo $value['id']; ?>=jQuery('div.uploadify button#<?php echo $value['id']; ?>');
                            var status=jQuery('#<?php echo $value['id']; ?>status');
                            new AjaxUpload(<?php echo $value['id']; ?>, {
                                action: '<?php echo THEME_DIR; ?>/admin/upload-file.php',
                                name: '<?php echo $upload_security?>',
                                data: {
                                	upload_path : '<?php echo base64_encode(UPFW_UPLOADS_DIR); ?>'
                                },
                                onSubmit: function(file, ext){
                                    //Check if file is an image
                                    if (! (ext && /^(JPG|PNG|GIF|jpg|png|jpeg|gif)$/.test(ext))){ 
                                       // extension is not allowed 
                                       status.text('Only JPG, PNG or GIF files are allowed');
                                       return false;
                                    }
                                    jQuery('#<?php echo $value['id']; ?>loader').addClass('activeload');
                                },
                                onComplete: function(file, response){
                                    //On completion clear the status
                                    status.text('');
                                    //Successful upload
                                    if(response==="success"){
                                    	$file = file.toLowerCase().replace(/ /g,'_').replace(/(_)\1+/g, '_').replace(/[^\w\(\).-]/gi,'_').replace(/__/g,'_');
                                        //Preview uploaded file
										jQuery('#<?php echo $value['id']; ?>preview').removeClass('uploaderror');
                                        jQuery('#<?php echo $value['id']; ?>preview').html('<img class="preview" src="<?php echo UPFW_UPLOADS_URL; ?>/'+$file+'" alt="<?php echo $value['id']; ?> Image" />').addClass('success');
                                        //Add image source to hidden input
                                        jQuery('input#<?php echo $value['id']; ?>').attr('value', '<?php echo UPFW_UPLOADS_URL; ?>/'+$file);
                                        //Append thumbnail to gallery
                                        jQuery('.thumbs').append('<a class="preview" href="<?php echo UPFW_UPLOADS_URL; ?>/'+$file+'"><img src="<?php echo UPFW_UPLOADS_URL; ?>/'+$file+'" /></a>')
                                        //Save Me Fool
                                        jQuery('#button-zone').animate({ 
                                            backgroundColor: '#555',
                                            borderLeftColor: '#555',
                                            borderRightColor: '#555'
                                        });
                                        jQuery('#button-zone button').addClass('save-me-fool');
                                        jQuery('.formState').fadeIn( 400 );
                                    } else{
                                        //Something went wrong
                                        jQuery('#<?php echo $value['id']; ?>preview').text(file+' did not upload. Please try again.').addClass('uploaderror');
                                    }
                                  	jQuery('#<?php echo $value['id']; ?>loader').removeClass('activeload');
                                }
                            });
                        });
                    </script>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                            <!-- Image Preview Input -->
                            <div class="preview" id="<?php echo $value['id']; ?>preview"><?php
                            
                            if($up_options->$value['id']):
                                echo "<img src='".$up_options->$value['id']."' alt='Preview Image' />";
                            elseif($value['url']):
                                echo "<img src='".$value['url']."' alt='Preview Image' />";
                            else:
                            	echo "<img src='".THEME_DIR."/admin/images/upfw_noimage.gif' alt='No Image Available' />";
                            endif;?></div>	
                            
                        </fieldset>
    
                        <fieldset class="data">
                                <div class="inner">
                                    <div class="uploadify">
                                        <button type="button" id="<?php echo $value['id']; ?>" class="secondary" <?php echo $attr; ?>><?php echo $value['value']; ?></button>
                                        <span id="<?php echo $value['id']; ?>loader" class="loader"></span>
                                    </div>

                                    <!-- Hidden Input -->
                                    <input type="hidden" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php if($up_options->$value['id']): echo $up_options->$value['id']; else: echo $value['url']; endif;?>" />

                                    <!-- Upload Status Input -->
                                    <div class="status hide" id="<?php echo $value['id']; ?>status"></div>

                                    <!-- Divider -->
                                    <div class="divider">
                                        <span>OR</span>
                                    </div>

                                    <!-- View Gallery -->
                                    <div class="viewgallery">
                                        <a id="<?php echo $value['id']; ?>viewgallery" href="#">Select from the UpThemes Gallery</a>
                                    </div>
                                    
                                    <!-- All Images -->
                                    <div id="<?php echo $value['id']; ?>allimages" class="allimages">
                                        <div class="thumbs">
                                            <?php $path = UPFW_UPLOADS_DIR;
                                            $directory = @opendir($path) or die("Unable to open folder. Please make sure your /wp-content/uploads/upfw/ folder exists and has permissions of 777.");
                                            while (false !== ($file = readdir($directory))) {
                                                if($file == "index.php") continue;
                                                if($file == ".") continue;
                                                if($file == "..") continue;
                                                if($file == "list.php") continue;
                                                if($file == "Thumbs.db") continue;?>
                                                <a class="preview" href="<?php echo UPFW_UPLOADS_URL; ?>/<?php echo $file?>"><img src="<?php echo UPFW_UPLOADS_URL; ?>/<?php echo $file?>" /></a>
                                            <?php }
                                            closedir($directory);?>
                                            <div class="clear"></div>
                                        </div>
                                        <?php if($value['url']):?>
                                            <div class="default">
                                                <p><em>Default Image</em></p>
                                                <a href="<?php echo $value['url']; ?>"><img src="<?php echo $value['url']; ?>" /></a>
                                            </div>
                                        <?php endif;?>
                                    </div>
    
                                </div>
                        </fieldset>
                    </li>
                <?php
                break;
                
                //Render category dropdown
                case 'category':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                
                                <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                    <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                    <?php
                                    $i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = 'category' ORDER BY $wpdb->terms.name", ARRAY_A);
                                    foreach($i as $row):
                                            if($up_options->$value['id']):
                                                if($row['slug'] == $up_options->$value['id']):
                                                    $selected = " selected='selected'";
                                                endif;
                                            else:
                                                if($value['value'] == $row['slug']):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            endif;
                                        echo "<option value='".$row['slug']."'".$selected.">".$row['name']."</option>";
                                        $selected = '';
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;
                
                //Render categories multiple select
                case 'categories':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                <select MULTIPLE name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                    <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                    <?php
                                    $i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = 'category' ORDER BY $wpdb->terms.name", ARRAY_A);
                                    foreach($i as $row):
                                        if(!empty($up_options->$value['id'])):
                                            foreach($up_options->$value['id'] as $std):
                                                if($std == $row['slug']):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            endforeach;
                                        else:
                                            if($value['value']):
                                                if(preg_match('/,/', $value['value'])):
                                                    $cats = explode(', ', $value['value']);
                                                    foreach($cats as $cat):
                                                        if(preg_match('/\b'.$row['slug'].'\b/', $cat)):
                                                            $selected = ' selected = "selected"';
                                                        endif;
                                                    endforeach;
                                                else:
                                                    if($value['value'] == $row['slug'] ):
                                                        $selected = ' selected = "selected"';
                                                    endif;
                                                endif;
                                            else:
                                                if($value['value'] == $row['post_title'] ):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            endif;
                                        endif;
                                        
                                        echo "<option value='".$row['slug']."'".$selected.">".$row['name']."</option>";
                                        $selected = '';
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;
                
                //Render page dropdown
                case 'page':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                    <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                    <?php
                                    $i = $wpdb->get_results("SELECT post_title, ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='page' ORDER BY post_title", ARRAY_A);
                                    foreach($i as $row):
                                        if($up_options->$value['id']):
                                            if($row['ID'] == $up_options->$value['id']):
                                                $selected = " selected='selected'";
                                            endif;
                                        else:
                                            if($row['post_title'] == $value['value']):
                                                $selected = " selected='selected'";
                                            endif;
                                        endif;
                                        echo "<option value='".$row['ID']."'".$selected.">".$row['post_title']."</option>";
                                        $selected = '';
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;
                
                //Render pages muliple select
                case 'pages':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                <select multiple="multiple" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                    <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                    <?php
                                    $i = $wpdb->get_results("SELECT post_title, ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='page' ORDER BY post_title", ARRAY_A);
                                    foreach($i as $row):
                                        if(!empty($up_options->$value['id'])):
                                            if($up_options->$value['id']):
                                                foreach($up_options->$value['id'] as $std):
                                                    if($std == $row['ID']):
                                                        $selected = ' selected = "selected"';
                                                    endif;
                                                endforeach;
                                            endif;
                                        else:
                                            if($value['value']):
                                                if(preg_match('/,/', $value['value'])):
                                                    $pages = explode(', ', $value['value']);
                                                    foreach($pages as $page):
                                                        if(preg_match('/\b'.$row['post_title'].'\b/', $page)):
                                                            $selected = ' selected = "selected"';
                                                        endif;
                                                    endforeach;
                                                else:
                                                    if($value['value'] == $row['post_title'] ):
                                                        $selected = ' selected = "selected"';
                                                    endif;
                                                    
                                                endif;
                                            else:
                                                if($value['value'] == $row['post_title'] ):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            endif;
                                        endif;
                                        echo "<option value='".$row['ID']."'".$selected.">".$row['post_title']."</option>";
                                        $selected = '';
                                    endforeach
                                    ?>
                                </select>
                            </div>
                        </fieldset>
                    </li>
                    <?php $attr = '';
                break;
            
                //Render Form Button
                case 'submit':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                <div class="uploadify">
                                <button type="submit" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" class="secondary" <?php echo $attr; ?>><?php echo $value['value']; ?></button>
                                </div>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;

                //Render taxonomy multiple select
                case 'taxonomy':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                <select MULTIPLE name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                    <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                    <?php
                                    $taxonomy = $value['taxonomy'];
                                    $i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = '$taxonomy' ORDER BY $wpdb->terms.name", ARRAY_A);
                                    foreach($i as $row):
                                        if(!empty($up_options->$value['id'])):
                                            foreach($up_options->$value['id'] as $std):
                                                if($std == $row['slug']):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            endforeach;
                                        else:
                                            if($value['value']):
                                                if(preg_match('/,/', $value['value'])):
                                                    $cats = explode(', ', $value['value']);
                                                    foreach($cats as $cat):
                                                        if(preg_match('/\b'.$row['slug'].'\b/', $cat)):
                                                            $selected = ' selected = "selected"';
                                                        endif;
                                                    endforeach;
                                                else:
                                                    if($value['value'] == $row['slug'] ):
                                                        $selected = ' selected = "selected"';
                                                    endif;
                                                endif;
                                            else:
                                                if($value['value'] == $row['post_title'] ):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            endif;
                                        endif;
                                        
                                        echo "<option value='".$row['slug']."'".$selected.">".$row['name']."</option>";
                                        $selected = '';
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;

                //Render Form Button
                case 'button':?>
                    <li>
                        <fieldset class="title">
                            <div class="inner">
                                <label><?php echo $value['name']; ?></label>
                                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                            </div>
                        </fieldset>
                        
                        <fieldset class="data">
                            <div class="inner">
                                <div class="uploadify">
                                <button type="button" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" class="secondary" <?php echo $attr; ?>><?php echo $value['value']; ?></button>
                                </div>
                            </div>
                        </fieldset>
                    </li>
                    
                    <?php $attr = '';
                break;

            
                default:
                break;
            }
        }
    }
endif;

// ---------------  Remove Ugly First Link in WP Sidebar Menu -------------------- //

function remove_ugly_first_link(){?>
    <script type="text/javascript">
	jQuery(document).ready(function(){
	    jQuery('li#toplevel_page_upthemes li.wp-first-item').remove();
	});
    </script>
<?php }
if(is_admin()): add_action("admin_head","remove_ugly_first_link"); endif;

// ---------------  Feedburner Functions -------------------- //

// RSS URL: rss('return') will return the value and not echo it.
function rss($i = ''){
    global $up_options;
    if($up_options->feedburner):
        $rss = "http://feeds.feedburner.com/".$up_options->feedburner;
    else:
        $rss = get_bloginfo_rss('rss2_url');
    endif;
    if($i == 'return'): return $rss; else: echo $rss; endif;
}

//RSS Subscribe URL: rss_email('return') will return the value and not echo it.
function rss_email($i = ''){
    global $up_options;
    if($up_options->feedburner):
        $rssemail = "http://www.feedburner.com/fb/a/emailverifySubmit?feedId=" . $up_options->feedburner;
    else:
        $rssemail = "#";
    endif;
    if($i == 'return'): return $rssemail; else: echo $rssemail; endif;
}

// Iterate through multiple option fields
function up_multiple($option, $space = true, $echo = true){
    if(is_array($option)):
        if($space):
            $space = ' ';
            $offset = -1;
        else:
            $offset = -2;
        endif;
        foreach($option as $single):
            $string .= $single.','.$space;
        endforeach;
        $string = substr_replace($string, ','.$space, $offset);
        if(!$echo): return $string; else: echo $string; endif;
    endif;
}

?>