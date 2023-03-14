<?php
function ngc_bulk_settings() {
    register_setting( 'ngc_bulk_options', 'ngc_bulk_options', $args = array() );
    add_settings_section( 'ngc_bulk_settings', 'General Settings', 'ngc_bulk_section_text', 'ngc_bulk' );

    add_settings_field( 'ngc_bulk_setting_format', 'Bulk Formats', 'ngc_bulk_setting_format', 'ngc_bulk', 'ngc_bulk_settings' );
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
        <option value='png' <?php if ( $options['bulk_format'] == 'png' ) echo 'selected="selected"'; ?>>PNG</option>
        <option value='jpeg' <?php if ( $options['bulk_format'] == 'jpeg' ) echo 'selected="selected"'; ?>>JPEG</option>
        <option value='jpg' <?php if ( $options['bulk_format'] == 'jpg' ) echo 'selected="selected"'; ?>>JPG</option>
        <option value='svg' <?php if ( $options['bulk_format'] == 'svg' ) echo 'selected="selected"'; ?>>SVG</option>
    </select>
<?php
}
?>
