<?php

defined( 'ABSPATH' ) || exit;

class Wc_Rw_Shipping_Tracking_Actions{

    private static $instance;

    public static function get_instance() {

        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Add tracking information to post meta
     *
     * @param $data
     */
    public function add_shipping_meta_data($data){

        delete_post_meta($data['order_id'], 'tracking_number');
        delete_post_meta($data['order_id'], 'shipping_company');
        delete_post_meta($data['order_id'], 'shipping_date');
        delete_post_meta($data['order_id'], 'shipping_url');

        add_post_meta($data['order_id'], 'tracking_number', $data['tracking_number']);
        add_post_meta($data['order_id'], 'shipping_company', $data['shipping_company']);
        add_post_meta($data['order_id'], 'shipping_date', $data['shipping_date']);
        add_post_meta($data['order_id'], 'shipping_url', $data['shipping_url']);

    }

    /**
     * Remove tracking information from post meta
     *
     * @param $order_id
     */
    public function remove_shipping_meta_data($order_id){

        delete_post_meta($order_id, 'tracking_number');
        delete_post_meta($order_id, 'shipping_company');
        delete_post_meta($order_id, 'shipping_date');
        delete_post_meta($order_id, 'shipping_url');

    }


    /**
     * Get template of window with tracking information
     *
     * @param $data
     * @return false|string
     */
    public function get_template_of_tracking_window($data){
        ob_start();
        require_once WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/templates/admin_meta_box_tracking.php';
        return ob_get_clean();
    }

    /**
     * Get template of initial form for entering tracking information
     *
     * @param $shipping_companies
     * @return false|string
     */
    public function get_template_of_start_tracking_window($shipping_companies){
        ob_start();
        require_once WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/templates/admin_meta_box.php';
        return ob_get_clean();
    }

    /**
     * Add admin order note about adding shipping information
     *
     * @param $order_id
     */
    public function order_note_add_tracking($order_id) {
        $order = wc_get_order( $order_id );
        $shipping_company =  $order->get_meta('shipping_company');
        $tracking_number =  $order->get_meta('tracking_number');

        //$note = printf(__("Order was shipped with %s and tracking number is: %u"), $shipping_company, $tracking_number );

        $note = __("Order was shipped with $shipping_company and tracking number is: $tracking_number");

        $order->add_order_note($note);

    }

    /**
     * Add admin order note about deleting shipping information
     *
     * @param $order_id
     */
    public function order_note_remove_tracking($order_id) {
        $order = wc_get_order( $order_id );
        $shipping_company =  $order->get_meta('shipping_company');
        $tracking_number =  $order->get_meta('tracking_number');

        $note = __("Tracking info was deleted for shipping company $shipping_company with tracking number $tracking_number");

        $order->add_order_note($note);

    }

    /**
     * Change order status to "Completed"
     *
     * @param $order_id
     */
    public function change_order_status_to_completed($order_id){

        $order = wc_get_order($order_id);
        if ($order->get_status() !== 'completed'){

            $order->update_status('completed');

        }


    }


}

