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

    array(  "name" => "Hide Sidebar",
            "desc" => "Would you like to disable and hide the sidebar?",
            "id" => "hide_sidebar",
            "type" => "select",
            "default_text" => "No",
            "options" => array("Yes"=>"1")),
            
    array(  "name" => "Post Prompt",
            "desc" => 'If empty, defaults to <strong>"Whatcha up to?"</strong>',
            "id" => "post_prompt",
            "type" => "text"),

    array(  "name" => "Post Titles",
            "desc" => "Display post titles?",
            "id" => "post_titles",
            "type" => "select",
            "default_text" => "No",
            "options" => array("Yes"=>"1"))
            
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