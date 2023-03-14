<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       ttps://github.com/himycool/next-gen-converter
 * @since      0.1
 *
 * @package    NextGenConverter
 * @subpackage NextGenConverter/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>  
    <h2>Next Gen Bulk Converter</h2>  
        <!--NEED THE settings_errors below so that the errors/success messages are shown after submission - wasn't working once we started using add_menu_page and stopped using add_options_page so needed this-->
    <?php settings_errors(); ?>  
    <form method="POST" action="options.php">  
        <?php 
            settings_fields( 'ngc_bulk_options' );
            do_settings_sections( 'ngc_bulk' );
        ?>             
        <?php submit_button( __( 'Start Bulk Optimization', 'E25M' ) ); ?>  
    </form> 
</div>