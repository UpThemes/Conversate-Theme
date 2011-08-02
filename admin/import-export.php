<form method="post" action="#/import-export">
<?php //Security Nonce For Cross Site Hacking
wp_nonce_field('save_upthemes','upfw'); ?>


<?php
global $export_message;
echo $export_message;

//Create Export Code

$opts_to_export = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
if(is_array($opts_to_export)):
    $encoded = 'up_themes_'.UPTHEMES_SHORT_NAME."~~";
    foreach($opts_to_export as $k => $v):
        if($v):
            $encoded .= $k.'|'.$v.'||';
        endif;
    endforeach;

    $encoded = base64_encode(substr($encoded, 0, -2));
    $encoded_check = true;
else:
    $encoded = "No theme options found. Please refresh the theme options to generate an export code.";
    $encoded_check = false;
endif;

//Create Export Options

$export = array (
    array(  "name" => UPTHEMES_NAME." Export Code",
            "desc" => "Copy and paste this code to somewhere safe.",
            "id" => "up_export",
            "type" => "textarea",
            "value" => $encoded,
            "attr" => array("rows" => "12", "class" => "click-copy")
    )
);
render_options($export);

//Create Download Link

if($encoded_check):
    $export = array (
        array(  "name" => "Download ".UPTHEMES_NAME." Export Code",
                "desc" => "Download and save this file somewhere safe.",
                "id" => "export_file",
                "type" => "button",
                "value" => "Download File",
                "attr" => array("ONCLICK" => "window.location.href='".get_bloginfo('template_directory').'/admin/export-options.php?f=upthemes_'.UPTHEMES_SHORT_NAME.'_'.date('mdy').'&e='.$encoded."'")            
        ) 
    );
    render_options($export);
endif;


//Create import options

$import = array (
    array(  "name" => "Import ".UPTHEMES_NAME." Options",
            "desc" => "Paste your options code here.",
            "id" => "up_import_code",
            "type" => "textarea",
            "value" => '',
            "attr" => array("rows" => "12", "class" => "up_import_code")
            
    ),
        
    array(  "name" => "",
            "desc" => "Notice: This overwrites your current options.",
            "id" => "up_import",
            "type" => "submit",
            "value" => 'Import Options Code'
    )
);
render_options($import);

//Create Restore Defaults Option
$import = array (
    array(  "name" => "Restore Theme Defaults",
            "desc" => "Refresh all options to original defaults.",
            "id" => "up_defaults",
            "type" => "submit",
            "value" => 'Restore Defaults'
    ));
render_options($import);
?>

</form>