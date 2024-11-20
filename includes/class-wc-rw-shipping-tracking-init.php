<?php

defined('ABSPATH') || exit;

class Wc_Rw_Shipping_Tracking_Init{

    private static $instance;

    public function __construct(){
        //add admin meta box
        add_action( 'add_meta_boxes', array($this, 'create_admin_meta_box') );
        //add new column in admin order list
        add_filter('manage_edit-shop_order_columns', array($this, 'wc_rw_new_order_column'));
        //add content to added column
        add_action( 'manage_shop_order_posts_custom_column', array($this, 'wc_rw_new_order_column_add_content'), 10, 2);
        //add tracking information to customer order page
        add_action('woocommerce_order_details_before_order_table', array($this, 'wc_rw_customer_view_order_info'), 9);
        //add tracking information to order email
        add_action('woocommerce_email_before_order_table', array($this, 'wc_rw_add_tracking_to_email'), 9, 4);
        //hide plugin added meta fields on order page
        add_filter('is_protected_meta', array($this, 'wc_rw_hide_meta_field'), 10, 2);

    }



    public static function get_instance() {

        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Add new meta box to admin order page
     */
    public function create_admin_meta_box(){



        add_meta_box(
            'wc_rw_shipping_tracking_form',
            'Tracking number',
            array($this, 'get_admin_meta_box_template'),
            'shop_order'
        );

    }


    /**
     * Get meta box template
     */
    public function get_admin_meta_box_template(){

        $order_id = $_GET['post'];
        $order = new WC_Order($order_id);
        $tracking_number = $order->get_meta( 'tracking_number' );

        if(!empty($tracking_number)){
            $data = $this->get_order_meta_data($order);


            echo '
                <div id="wc-rw-opacity">
                    <div id="rw-tracking-container">';
            require WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/templates/admin_meta_box_tracking.php';
            echo '    
                    </div>
                </div>
                <span id="wc-rw-spinner" class="spinner"></span>
                ';

        }else{
            $shipping_companies = Wc_Rw_Shipping_Tracking_Companies::get_shipping_companies_options_list();
            echo '
                <div id="wc-rw-opacity">
                    <div id="rw-tracking-container">';
            require WP_PLUGIN_DIR . '/wc-rw-shipping-tracking/templates/admin_meta_box.php';
            echo '    
                    </div>
                </div>
                <span id="wc-rw-spinner" class="spinner"></span>
                ';
        }

    }

    public function get_order_meta_data($order){
        $data = [];
        $data['tracking_number'] = $order->get_meta('tracking_number');
        $data['shipping_company'] = $order->get_meta('shipping_company');
        $data['shipping_date'] = $order->get_meta('shipping_date');
        $data['shipping_url'] = $order->get_meta('shipping_url');


        return $data;
    }


    /**
     * Add additional column header in admin order list
     *
     * @param $columns
     * @return mixed
     *
     */

    public function wc_rw_new_order_column( $columns )
    {
        $columns['tracking_info'] = 'Tracking Info';
        return $columns;
    }


    /**
     *
     * Add tracking info for each order in additional column on admin orders list
     *
     * @param $column
     * @param $post_id
     */

    public function  wc_rw_new_order_column_add_content($column, $post_id){

        if ( 'tracking_info' === $column ) {

            $order    = wc_get_order( $post_id );

            if(!empty($tracking_number = $order->get_meta('tracking_number'))) {

                $shipping_company = $order->get_meta('shipping_company');
                $shipping_url = $order->get_meta('shipping_url');

                echo '<div id="wc-rw-shipping-tracking-column-content">
                    <p><b>' . $shipping_company . '</b></p>
                    <a href="' . $shipping_url . $tracking_number . '" target="_blank">' . $tracking_number . '</a>   
                  </div>';
            } else {
                echo '&ndash;';
            }
        }


    }

    /**
     * Add to customer order page tracking information
     *
     * @param $order
     */

    public function wc_rw_customer_view_order_info($order){

        if ( is_wc_endpoint_url( 'view-order' ) ) {

            if (!empty($tracking_number = $order->get_meta('tracking_number'))){
                        $shipping_company = $order->get_meta('shipping_company');
                        $shipping_url = $order->get_meta('shipping_url');
                        $shipping_date = $order->get_meta('shipping_date');

                        echo '
                        <h2>' . __('Shipment tracking', 'wc-rw-shipping-tracking') . '</h2>
                        <div class="wc-rw-customer-view-container">
                            <div class="wc-rw-customer-view-left">
                                <strong>' . __('Shipped by: ', 'wc-rw-shipping-tracking') . $shipping_company . '</strong>    
                                <a href="' . $shipping_url . $tracking_number . '" target="_blank">' . $tracking_number . '</a>
                                <p class = "shipping-date">'. __('Shipped on: ', 'wc-rw-shipping-tracking') . $shipping_date .'</p>                               
                            </div>
                            <div class="wc-rw-customer-view-right">
                               <a class="button" href="' . $shipping_url . $tracking_number . '" target="_blank">' . __('Track', 'wc-rw-shipping-tracking') . '</a>    
                            </div>
                        </div>

                        ';
            }
        }
    }

    /**
     * Add to "order completed" email tracking information after header
     *
     * @param $order
     * @param $sent_to_admin
     * @param $plain_text
     * @param $email
     *
     */

    public function wc_rw_add_tracking_to_email($order, $sent_to_admin, $plain_text, $email){

        if(!empty($tracking_number = $order->get_meta('tracking_number')) && $email->id === 'customer_completed_order'){

            $shipping_company = $order->get_meta('shipping_company');
            $shipping_url = $order->get_meta('shipping_url');
            $shipping_date = $order->get_meta('shipping_date');

            echo '
                <table style="width:100%;margin:10px 0;border:1px solid #e0e0e0;border-radius:3px;background:#fafafa;border-spacing:0" width="100%" bgcolor="#fafafa" cellspacing="0">
                        
                    <tr>
                        <td colspan="3" style="padding:10px;padding-bottom:0">
                            <h2 style="font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;text-align:left;display:inline-block;color:#333;vertical-align:middle;font-weight:500;line-height:80%;font-size:18px;margin:0 0 10px">' . __('Shipment tracking', 'wc-rw-shipping-tracking') . '</h2>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding:10px;padding-top:0; padding-bottom:0">
                            <span style="margin-top:5px;display:inline-block;font-size:14px">' . __('Shipped on: ', 'wc-rw-shipping-tracking') . '<b>' . $shipping_date . '</b></span>					
                        </td>
                    </tr>
                                                      
                    <tr>                                       
                        <td style="padding:10px; padding-top:0; padding-bottom:0">
                            <span style="word-break:break-word;margin-right:5px;font-size:14px;display:block">' .  __('Shipping company: ', 'wc-rw-shipping-tracking') . '<b>' . $shipping_company . '</b></span>
                            <span style="word-break:break-word;margin-right:5px;font-size:14px;display:block">' .  __('Tracking number: ', 'wc-rw-shipping-tracking') . '<a href="' . $shipping_url . $tracking_number . '" style="font-weight:normal;color:#03a9f4;text-decoration:none;font-size:14px;" target="_blank" >' . $tracking_number . '</a></span>	
                        </td>
                        
                        <td  style="text-align:right;padding:15px" align="right"> <!--class="m_2689815068876813734fluid_2cl_td_button"-->
                            <a href="' . $shipping_url . $tracking_number . '"  style="font-weight:normal;background:#005b9a;padding:7px 15px;text-decoration:none;display:inline-block;border-radius:3px;margin-top:2px;text-align:center;min-height:8px;white-space:nowrap;color:#fff;font-size:14px" bgcolor="#005b9a" target="_blank" >' . __('Track', 'wc-rw-shipping-tracking') . '</a>	
                        </td>                                      
                    </tr>
                </table>
            
            ';
        }
    }


    /**
     * Hide plugin added meta fields from admin order page
     *
     * @param $protected
     * @param $meta_key
     * @return bool|mixed
     */
    public function wc_rw_hide_meta_field($protected, $meta_key) {

        if( in_array($meta_key, array('tracking_number', 'shipping_company', 'shipping_url', 'shipping_date')) ){
            return true;
        }
        return $protected;

    }




}