<?php
/*  Array Options:
   
   name (string)
   desc (string)
   id (string)
   type (string) - text, color, image, select, multiple, textarea, page, pages, category, categories
   value (string) - default value - replaced when custom value is entered - (text, color, select, textarea, page, category)
   options (array)
   attr (array) - any form field attributes
   url (string) - for image type only - defines the default image
    
*/

$options = array (

    array(  "name" => "Choose Your Color Scheme",
            "desc" => "Choose the color scheme you would like to use.",
            "id" => "theme",
            "type" => "select",
			"value" => "dark",
            "options" => array(
                'Blue' => 'blue',
                'Orange' => 'orange',
                'Green' => 'green',
                'Red' => 'red')
    ),
    
    array(  "name" => "Logo Image",
            "desc" => "Upload your own image or select from the gallery. (any size)",
            "id" => "logo",
            "type" => "image",
            "value" => "Upload Your Logo",
			"url" => get_bloginfo('template_url') . "/i/conversate_logo.png"
    ),

    array(  "name" => "Background Color",
            "desc" => "Select a custom background color.",
            "id" => "backgroundcolor",
			"default" => "#ffffff",
            "type" => "color"
    ),

    array(  "name" => "Background Image",
            "desc" => "Upload your own image or select from the gallery.",
            "id" => "background",
            "type" => "image",
            "value" => "Upload Your Background",
			"url" => get_bloginfo('template_url') . "/i/body_bg.gif"
    ),

    array(  "name" => "Background Image Repeat",
            "desc" => "Do you want your background to be displayed just once or repeated across the page?",
            "id" => "background_repeat",
            "std" => "No Repeat",
            "value" => "no-repeat",
            "type" => "select",
            "options" => array(
            	"Repeat Horizontally (left to right)" => "repeat-x",
            	"Repeat Vertically (top to bottom)" => "repeat-y",
            	"Repeat Both" => "repeat"
            )
    ),

    array(  "name" => "Background Attachment",
            "desc" => "Do you want your background to scroll with the page or stand still?",
            "id" => "background_attachment",
            "std" => "no-repeat",
            "type" => "select",
            "options" => array(
            	"Scroll" => "scroll",
            	"Fixed" => "fixed",
            	"inherit" => "inherit"
            )
    ),

    array(  "name" => "Background Image Position",
            "desc" => "Where do you want your background to be positioned?",
            "id" => "background_position",
            "type" => "select",
            "std" => "top center",
            "options" => array(
            	"Middle of Page" => "center center",
            	"Top Left" => "top left",
            	"Top Right" => "top right",
            	"Top Center" => "top center",
            	"Bottom Left" => "bottom left",
            	"Bottom Right" => "bottom right",
            	"Bottom Center" => "bottom center")
    ),    


	array(  "name" => "Footer Text Color",
            "desc" => "Select a custom text color for your footer.",
            "id" => "custom_footer_color",
            "type" => "color"
    )

);

/* ------------ Do not edit below this line ----------- */

//Check if theme options set
global $default_check;
global $default_options;

if(!$default_check):
    foreach($options as $option):
        if($option['type'] != 'image'):
            $default_options[$option['id']] = $option['value'];
        else:
            $default_options[$option['id']] = $option['url'];
        endif;
    endforeach;
    $update_option = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
    if(is_array($update_option)):
        $update_option = array_merge($update_option, $default_options);
        update_option('up_themes_'.UPTHEMES_SHORT_NAME, $update_option);
    else:
        update_option('up_themes_'.UPTHEMES_SHORT_NAME, $default_options);
    endif;
endif;

render_options($options);

?>