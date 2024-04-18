<?php

defined( 'ABSPATH' ) || exit;

class Wc_Rw_Shipping_Tracking_Ajax_Handler{

    /**
     * Handle ajax request - add tracking information
     */
    public static function add_track_number_action(){

        $response=[];
        $response['success'] = false;

        require_once WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/includes/class-wc-rw-shipping-tracking-validation.php';
        $validation = Wc_Rw_Shipping_Tracking_Validation::get_instance();
        $data = $_POST;

        //make simple data validation

        if(!$validation->validate_tracking_number($data['tracking_number'])){
            $response['validation'][] = 'tracking_number';
        }

        if(!$validation->validate_empty_field($data['shipping_company'])){
            $response['validation'][] = 'shipping_company';
        }

        if(!$validation->validate_empty_field($data['shipping_date'])){
            $response['validation'][] = 'shipping_date';
        }

        // if validation result is success
        if (!isset($response['validation'])) {

            require_once WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/includes/class-wc-rw-shipping-tracking-companies.php';
            //$response['post'] = $data; //debugging
            $order_id = $_POST['order_id'];
            $change_order_status = $_POST['change_status'];
            $data['shipping_url'] = Wc_Rw_Shipping_Tracking_Companies::get_shipping_company_tracking_url($data['shipping_company']);

            //init class
            require_once WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/includes/class-wc-rw-shipping-tracking-actions.php';
            $plugin_actions = Wc_Rw_Shipping_Tracking_Actions::get_instance();

            //update meta
            $plugin_actions->add_shipping_meta_data($data);

            //get meta-box template
            $response['template'] = $plugin_actions->get_template_of_tracking_window($data);

            //add order note
            $plugin_actions->order_note_add_tracking($order_id);

            //change status to "Completed"
            if($change_order_status === "true") {
                $plugin_actions->change_order_status_to_completed($order_id);
            }

            $response['success'] = true;

        }


        echo json_encode($response);
        wp_die();


    }

    public static function remove_track_number_action(){
        $response = [];

        require_once WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/includes/class-wc-rw-shipping-tracking-companies.php';
        $shipping_companies = Wc_Rw_Shipping_Tracking_Companies::get_shipping_companies_options_list();


        $order_id = $_POST['order_id'];
        //init class
        require_once WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/includes/class-wc-rw-shipping-tracking-actions.php';
        $plugin_actions = Wc_Rw_Shipping_Tracking_Actions::get_instance();
        //delete order meta
        $plugin_actions->remove_shipping_meta_data($order_id);
        //get initial form template
        $response['template'] = $plugin_actions->get_template_of_start_tracking_window($shipping_companies);
        //add admin note
        $plugin_actions->order_note_remove_tracking($order_id);

        echo json_encode($response);
        wp_die();


    }




    



}

