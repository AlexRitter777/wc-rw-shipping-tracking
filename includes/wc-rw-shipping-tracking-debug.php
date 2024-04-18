<?php
defined('ABSPATH') || exit;

function wc_rw_shipping_tracking_debug($arr) {
    echo '<pre>' . print_r($arr, true) . '</pre>';
}
