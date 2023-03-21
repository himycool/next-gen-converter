<?php
/**
* Plugin Name: Next Gen Converter
* Plugin URI: https://github.com/himycool/next-gen-converter
* Description: Output a WebP or AVIF images.
* Version: 0.1
* Author: D.K. HIMAS KHAN
* Author URI: https://github.com/himycool
**/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

ini_set('max_execution_time', 0);
ini_set('memory_limit', '1G');

/********************************************************************************************
 * Activation & PHP version checks.
 ********************************************************************************************/

if (!function_exists('plugin_php_requirement_activation_check')) {

	/**
	 * check if we have the proper PHP version.
	 * Show an error if needed and don't continue with the plugin.
	 *
	 *
	 */
	function plugin_php_requirement_activation_check()
	{
		if (version_compare(PHP_VERSION, '5.3.0', '<')) {
			deactivate_plugins(basename(__FILE__));
			wp_die(
				sprintf(
					__('%s"Next Gen Converter" can not be activated. %s It requires PHP version 5.3.0 or higher, but PHP version %s is used on the site. Please upgrade your PHP version first ✌️ %s Back %s', BERG_I18N),
					'<strong>',
					'</strong><br><br>',
					PHP_VERSION,
					'<br /><br /><a href="' . esc_url(get_dashboard_url(get_current_user_id(), 'plugins.php')) . '" class="button button-primary">',
					'</a>'
				)
			);
		}
	}
	register_activation_hook(__FILE__, 'plugin_php_requirement_activation_check');
}

/**
 * Plugin activation function
 */
function next_gen_converter_activate_plugin( $network_wide ) {

	if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

	global $wpdb;

	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		if ( $network_wide ) {
			$old_blog =  $wpdb->blogid;
			//Get all blog ids
			$blog_ids =  $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
			}
			switch_to_blog( $old_blog );
			return;
		}
	}
	set_transient( 'next-gen-converter-admin-notice-on-activation', true, 5 );

}
register_activation_hook( __FILE__, 'next_gen_converter_activate_plugin' );

/**
 * Register a custom menu page.
 */
function register_my_custom_menu_page() {
	add_menu_page(
		__( 'Next Gen Converters', 'E25M' ),
		'Next Gen Converter',
		'manage_options',
		'next-gen-converter',
		'menuPage',
		//plugins_url( 'next-gen-converter/images/logo.png' ),
		'dashicons-download',
		100
	);

    add_submenu_page(
		'next-gen-converter',
		'General',
		'General',
		'manage_options',
		'next-gen-converter',
		'menuPage'
	);

    add_submenu_page(
		'next-gen-converter',
		'Bulk Converter',
		'Bulk Converter',
		'manage_options',
		'bulk-converter',
		'subMenuPage'
	);
}
add_action( 'admin_menu', 'register_my_custom_menu_page' );

$logFilePath = plugin_dir_path( __FILE__ ).'logs/info.log';
/**
 * Write logs
 * @param $message
 * @param string $type
 */
function writeLog($message, $type = 'Success')
{
	global $logFilePath;
	$message = date('Y-d-m H:i:s : ') . strtoupper($type) . ' : ' . $message;
	//Update log file
	file_put_contents($logFilePath, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
}

/**
 * clear the log file content
 */
function clearLogFile()
{
	global $logFilePath;
	file_put_contents($logFilePath, '');
}

//general setting
if ( is_file( plugin_dir_path( __FILE__ ) . 'functions/ngc-general-function.php' ) ) {
    include_once plugin_dir_path( __FILE__ ) . 'functions/ngc-general-function.php';
}

function menuPage() {
    if ( is_file( plugin_dir_path( __FILE__ ) . 'includes/ngc-general.php' ) ) {
        include_once plugin_dir_path( __FILE__ ) . 'includes/ngc-general.php';
    }
}

//bulk optimization
if ( is_file( plugin_dir_path( __FILE__ ) . 'functions/ngc-bulk-function.php' ) ) {
    include_once plugin_dir_path( __FILE__ ) . 'functions/ngc-bulk-function.php';
}

function subMenuPage() {
    if ( is_file( plugin_dir_path( __FILE__ ) . 'includes/bulk-converter.php' ) ) {
        include_once plugin_dir_path( __FILE__ ) . 'includes/bulk-converter.php';
    }
}

function ngc_add_editor_styles(){
    wp_enqueue_style('ngc-styles', plugins_url( 'css/ngc-main.css', __FILE__ ), array());
}
add_action('admin_init', 'ngc_add_editor_styles');

function enqueue_scripts_front_end(){
    wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js', array(), null, true);
    wp_enqueue_script('ngc-vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.3/vue.min.js', array(), null, false);
    wp_enqueue_script('ngc-custom', plugins_url( 'js/ngc-main.js', __FILE__ ), array('jquery'), null, true);
}
add_action('admin_enqueue_scripts','enqueue_scripts_front_end');
