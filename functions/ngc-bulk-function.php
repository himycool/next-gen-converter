<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// register the ajax action for authenticated users
add_action('wp_ajax_get_image_count', 'get_image_count');
// register the ajax action for unauthenticated users
add_action('wp_ajax_nopriv_get_image_count', 'get_image_count');

/**
 * Handle the ajax request - get image count
 */
function get_image_count()
{
    $mimeType = $_POST['mimeType'];
    $counts_obj = wp_count_attachments($mimeType); 

    $all_data = get_object_vars($counts_obj); //var_dump($all_data);

    wp_send_json_success($all_data[$mimeType]);

    // kill handler
    die;
}

/**
 * Handle the ajax request - convert images
 */
function convert_images()
{
    $mimeType = $_POST['mimeType'];
    $qualityVal = $_POST['qualityVal'];

    

   // wp_send_json_success($all_data[$mimeType]);

    // kill handler
    die;
}