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

// register the ajax action for authenticated users
add_action('wp_ajax_convert_images', 'convert_images');
// register the ajax action for unauthenticated users
add_action('wp_ajax_nopriv_convert_images', 'convert_images');

/**
 * Handle the ajax request - convert images
 */
function convert_images()
{
    clearLogFile();
    $mimeValFrom = $_POST['mimeValFrom'];
    $perRun = $_POST['perRun'];
    $rmvImg = $_POST['rmvImg'];
    $genOptions = get_option( 'ngc_general_options' );
    $outFormat = (empty($genOptions['output_format'])
                ? 'webp' : $genOptions['output_format']);
    $quality = (empty($genOptions['quality'])
                ? '100' : $genOptions['quality']);

    
// need to do
//** Args for attachment query */
    $argsAtt = array(
        'orderby'        => 'title',
        'order'          => 'DESC',
        'post_type'      => 'attachment',
        'post_mime_type' => array(
            $mimeValFrom
        ),
        'post_status'    => array(
            'publish',
            'inherit',
            'any',
        ),
    );

    $queryAtt = new WP_Query($argsAtt);
    if ($queryAtt->have_posts()) {
        while ($queryAtt->have_posts()) { $queryAtt->the_post();

            $attachedID = $queryAtt->post->ID;
            $attachUrl = wp_get_attachment_url(); // Get file url
            $attachedPath = get_attached_file( $attachedID ); // Full path

            $dir = pathinfo($attachedPath, PATHINFO_DIRNAME);
            $name = pathinfo($attachedPath, PATHINFO_FILENAME);
            $destination = $dir . DIRECTORY_SEPARATOR . $name . '.' . $outFormat;
            
            if(file_exists($destination)){
                writeLog($destination.' Already exits', 'error');
            }else{
                $info = getimagesize($attachedPath);
                $isAlpha = false;
        
                if ($info['mime'] == 'image/jpeg')
                    $image = imagecreatefromjpeg($attachedPath);
                elseif ($isAlpha = $info['mime'] == 'image/gif') {
                    $image = imagecreatefromgif($attachedPath);
                } elseif ($isAlpha = $info['mime'] == 'image/png') {
                    $image = imagecreatefrompng($attachedPath);
                } else {
                    return $attachedPath;
                }
        
                if ($isAlpha) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
        
                $convertImg = imagewebp($image, $destination, $quality);
        
                if($convertImg){
                    $attachment = array(
                        'post_mime_type' => $info['mime'],
                        'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $destination ) ),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                
                    // creating an attachment in db and saving its ID to a variable
                    $attach_id = wp_insert_attachment($attachment, $destination);
        
                    // generation of attachment metadata
                    $attach_data = wp_generate_attachment_metadata($attach_id, $destination);
        
                    // attaching metadata and creating a thumbnail
                    wp_update_attachment_metadata($attach_id, $attach_data);
                }
        
                writeLog($destination.' Converted', 'success');
            }
        
            //remove old image
            // if ($rmvImg){
            //     //unlink($attachedPath);
            //     $attachmentID = attachment_url_to_postid($attachUrl);
            //     if($attachmentID){
            //         $fileDel = wp_delete_attachment( $attachmentID );
            //         if($fileDel){ writeLog($attachedPath.' Deleted', 'success'); }
            //     }else{
            //         writeLog($attachedPath.' file not exists', 'error');
            //     }
                
            // }

        }
    }
    // Restore original Post Data
    wp_reset_postdata();


   // wp_send_json_success($all_data[$mimeType]);

    // kill handler
    die;
}