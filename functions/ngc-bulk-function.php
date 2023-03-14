<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function ngc_bulk_settings() {
    register_setting( 'ngc_bulk_options', 'ngc_bulk_options', $args = array() );
    add_settings_section( 'ngc_bulk_settings', 'Bulk Settings', 'ngc_bulk_section_text', 'ngc_bulk' );

    add_settings_field( 'ngc_bulk_setting_format', 'Bulk Formats From', 'ngc_bulk_setting_format', 'ngc_bulk', 'ngc_bulk_settings' );
    //add_settings_field( 'ngc_plugin_setting_quality', 'Export Quality', 'ngc_plugin_setting_quality', 'ngc_bulk', 'ngc_bulk_settings' );
}
add_action( 'admin_init', 'ngc_bulk_settings' );

function ngc_bulk_section_text() {
    echo '<p>Here you can set bulk setting</p>';
}

function ngc_bulk_setting_format() {
    $options = get_option( 'ngc_bulk_options' );
?>
    <select id='ngc_bulk_setting_format' name='ngc_bulk_options[bulk_format]'>
        <option value='image/png' <?php if ( $options['bulk_format'] == 'image/png' ) echo 'selected="selected"'; ?>>PNG</option>
        <option value='image/jpeg' <?php if ( $options['bulk_format'] == 'image/jpeg' ) echo 'selected="selected"'; ?>>JPEG</option>
        <option value='image/svg+xml' <?php if ( $options['bulk_format'] == 'image/svg+xml' ) echo 'selected="selected"'; ?>>SVG</option>
        <option value='image/gif' <?php if ( $options['bulk_format'] == 'image/gif' ) echo 'selected="selected"'; ?>>GIF</option>
    </select>
    <span class="img-count"></span>
<?php
}

// register the ajax action for authenticated users
add_action('wp_ajax_get_image_count', 'get_image_count');
// register the ajax action for unauthenticated users
add_action('wp_ajax_nopriv_get_image_count', 'get_image_count');

/**
 * Handle the ajax request
 */
function get_image_count()
{
    $mimeType = $_POST['mimeType'];
    $counts_obj = wp_count_attachments($mimeType);

    $all_data = get_object_vars($counts_obj);
    wp_send_json_success($all_data[$mimeType]);

    // kill handler
    die;
}