<?php
function ngc_register_settings() {
    register_setting( 'ngc_general_options', 'ngc_general_options', $args = array() );
    add_settings_section( 'ngc_settings', 'General Settings', 'ngc_plugin_section_text', 'ngc_general' );

    add_settings_field( 'ngc_plugin_setting_output_format', 'Output Formats', 'ngc_plugin_setting_output_format', 'ngc_general', 'ngc_settings' );
    add_settings_field( 'ngc_plugin_setting_quality', 'Export Quality', 'ngc_plugin_setting_quality', 'ngc_general', 'ngc_settings' );
}
add_action( 'admin_init', 'ngc_register_settings' );

function ngc_plugin_section_text() {
    echo '<p>Here you can set all the options for Next Gen Converter</p>';
}

function ngc_plugin_setting_output_format() {
    $options = get_option( 'ngc_general_options' );
?>
    <select id='ngc_plugin_setting_output_format' name='ngc_general_options[output_format]'>
        <option value='webp' <?php if ( $options['output_format'] == 'webp' ) echo 'selected="selected"'; ?>>webP</option>
        <option value='avif' <?php if ( $options['output_format'] == 'avif' ) echo 'selected="selected"'; ?>>AVIF</option>
    </select>
<?php
}

function ngc_plugin_setting_quality() {
    $options = get_option( 'ngc_general_options' );
   // echo $options['quality'];
?>

    <div id="app">
        <span class="value"> {{rangeValue}} </span>
        <input :style="{'background': 'linear-gradient(90deg, darkred '+ (rangeValue) +'%, #FFF 1%)'}"
            class="range-input" type="range" v-model="rangeValue" min="0" max="100" name="ngc_general_options[quality]">
    </div>
    <script>
        new Vue(({
        el: '#app',
        data: {
                rangeValue: <?php echo $options['quality']; ?>
            }
        }))
    </script>
<?php
}
