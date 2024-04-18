<?php

defined('ABSPATH') || exit;

class Wc_Rw_Shipping_Tracking_Validation {

    private static $instance;

    public static function get_instance() {

        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public function validate_empty_field($field_value){

        if(empty($field_value)) return false;

        return true;

    }

    public function validate_tracking_number($tracking_number){

        if(!preg_match('~^[a-zA-Z0-9]{5,25}$~', $tracking_number)) return false;

        return true;

    }




}