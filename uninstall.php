<?php
// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

wc_rw_drop_plugin_added_meta();

function wc_rw_drop_plugin_added_meta(){

    delete_post_meta_by_key('tracking_number');
    delete_post_meta_by_key('shipping_company');
    delete_post_meta_by_key('shipping_date');
    delete_post_meta_by_key('shipping_url');

}

