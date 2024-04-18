<?php defined( 'ABSPATH' ) || exit; ?>

<div id="rw-tracking-container-content">
    <button class="button button-primary rw-btn-add-tracking-number" type="button" style="display: block">Add Tracking Number</button>


    <div id="rw-tracking-info-form" style="display: none;">

        <?php

        woocommerce_wp_text_input( array(
            'id' => 'tracking_number',
            'label' => 'Tracking number:',
            'value' => '',
        ));

        woocommerce_wp_select( array(
            'id'      => 'shipping_company',
            'label'   => 'Shipping Company:',
            'selected' => true,
            'value' => '',
            'style' => 'width:100%',
            'options' =>  $shipping_companies,

        ) );

        woocommerce_wp_text_input( array(
            'id' => 'shipping_date',
            'label' => 'Date shipped:',
            'class' => 'date-picker rw-wc-shipping-tracking-datepicker',
            'value' => date('Y-m-d'),
        ))
        ?>

        <div class="rw-order-completed-checkbox">

            <?php

            woocommerce_wp_checkbox( array(
                'id'            => 'change_order_status_to_shipped',
                'label'         => 'Mark order as:',
                'value'         => 'change_order_status_to_shipped',
                'cbvalue'       => 'change_order_status_to_shipped',
                'wrapper_class' => 'rw-order-completed-label'

            ));

            ?>
            <span>Completed</span>
        </div>


        <div class="rw-tracking-form-buttons">
            <button class="button button-primary rw-save-tracking" id="rw-save-tracking-number">Save Tracking</button>
            <button class="button button-primary rw-close-tracking-form">Cancel</button>
        </div>
    </div>
</div>


