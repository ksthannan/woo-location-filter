<?php
/*
Plugin Name: Woo Product Location Filter
Description: It allows you to show product based on location selection using shortcode [woo_location_filter]
Version: 1.0.0
Author: WPL
Author URI: #
License: GPLv2
Text Domain: wpl
 */

if (!defined('ABSPATH')) {
    exit;
}

// Constant values 
define('WPL_VERSION', '1.0.0');
define('WPL_FILE', __FILE__);
define('WPL_PATH', __DIR__);
define('WPL_URL', plugins_url('', WPL_FILE));
define('WPL_ASSETS', WPL_URL . '/assets');

register_activation_hook(__FILE__, 'wpl_activation');
function wpl_activation(){

}

register_deactivation_hook(__FILE__, 'wpl_deactivation');
function wpl_deactivation(){

}

add_action('plugins_loaded', 'wpl_init_plugin');
function wpl_init_plugin(){
	load_plugin_textdomain('wpl', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

// Admin enqueue 
add_action('admin_enqueue_scripts', 'wpl_admin_scripts');
function wpl_admin_scripts()
{
    wp_enqueue_style('wpl-select2-min', WPL_ASSETS . '/css/select2.min.css', array(), WPL_VERSION, 'all');

    wp_enqueue_style('wpl-admin-style', WPL_ASSETS . '/css/wpl_admin_style.css', array(), WPL_VERSION, 'all');
	
    wp_enqueue_script('wpl-admin-script', WPL_ASSETS . '/js/wpl_admin_script.js', array('jquery' ), WPL_VERSION, true);
}

// Frontend enqueue 
add_action('wp_enqueue_scripts', 'wpl_frontend_scripts');
function wpl_frontend_scripts()
{
    wp_enqueue_style('wpl-style', WPL_ASSETS . '/css/wpl_style.css', array(), WPL_VERSION, 'all');

    wp_enqueue_script( 'wpl-select2-full', 	 WPL_ASSETS . '/js/select2.full.min.js',  array('jquery'), WPL_VERSION, true );

    wp_enqueue_script( 'wpl-location-filter', 	 WPL_ASSETS . '/js/location-filter.js',  array('jquery'), WPL_VERSION, true );

    wp_enqueue_script('wpl-script', WPL_ASSETS . '/js/wpl_script.js', array('jquery'), WPL_VERSION, true);

    wp_localize_script( 'wpl-script', 'wpl_ajax_object', array( 
        'ajax_url' => admin_url( 'admin-ajax.php' ), 
	));
}

// require files 
require_once('inc/frontend/frontend.php');
require_once('inc/frontend/frontend-functions.php');
require_once('inc/admin/admin.php');
require_once('inc/admin/admin-functions.php');

