<?php
/**
Plugin Name:  WooCommerce RW Shipping Tracking
Description: Adds shipment tracking number to WooCommerce orders.
Version: 1.4.1
Author: Alexej Bogačev (RAIN WOLF s.r.o.)
 */


// If this file is called directly, abort.

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}



class Wc_Rw_Shipping_Tracking {

    public function __construct() {

        //load shipping companies list
        $this->load_shipping_companies();

        //register styles and scripts
        add_action( 'admin_enqueue_scripts', array($this, 'load_admin_scripts'));
        add_action( 'wp_enqueue_scripts', array($this, 'load_public_scripts'));

        $this->register_ajax_handler();
        $this->initialize_plugin();
        $this->load_debugger();

    }



    public function load_shipping_companies (){

        require_once WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/includes/class-wc-rw-shipping-tracking-companies.php';

    }

    /**
     * Initiate main plugin functionality
     */
    public function initialize_plugin(){

        require WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/includes/class-wc-rw-shipping-tracking-init.php';
        Wc_Rw_Shipping_Tracking_Init::get_instance();

    }

    protected function register_ajax_handler(){

        require_once WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/includes/class-wc-rw-shipping-tracking-ajax-handler.php';
        add_action( 'wp_ajax_add_track_number_action', array('Wc_Rw_Shipping_Tracking_Ajax_Handler','add_track_number_action' ));
        add_action( 'wp_ajax_remove_track_number_action', array('Wc_Rw_Shipping_Tracking_Ajax_Handler','remove_track_number_action' ));


    }

    public function load_admin_scripts(){

        wp_enqueue_script('ajax-script', WP_PLUGIN_URL  . '/wc-rw-shipping-tracking/assets/js/ajax.js', array('jquery'), "1.1", true);
        wp_enqueue_script('main-script', WP_PLUGIN_URL  . '/wc-rw-shipping-tracking/assets/js/main.js', array('jquery'), "1.1", true);
        wp_localize_script('ajax-script','my_ajax_obj', array('ajax_url' => admin_url( 'admin-ajax.php' ),));
        wp_enqueue_style( 'style', WP_PLUGIN_URL . '/wc-rw-shipping-tracking/assets/css/admin.css' );

    }

    public function load_public_scripts(){

        wp_enqueue_style( 'style', WP_PLUGIN_URL . '/wc-rw-shipping-tracking/assets/css/public.css' );

    }

    public function load_debugger() {

        require_once WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/includes/wc-rw-shipping-tracking-debug.php';

    }


}

/**
 * @return mixed|Wc_Rw_Shipping_Tracking
 *
 * Get instance of main plugin class
 */
function wc_rw_shipping_tracking() {
    static $instance;

    if ( ! isset( $instance ) ) {
        $instance = new Wc_Rw_Shipping_Tracking();
    }

    return $instance;
}

/**
 * Begin execution of the plugin.
 */
wc_rw_shipping_tracking();




