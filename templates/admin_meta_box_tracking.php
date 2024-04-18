<?php defined( 'ABSPATH' ) || exit; ?>

    <div id="rw-tracking-info-container" class="rw-tracking-info-container">
        <div class="rw-tracking-info">
            <strong><?= $data['shipping_company'] ;?>- </strong>
            <a href="<?= $data['shipping_url'] . $data['tracking_number'] ;?>" target="_blank"><?= $data['tracking_number'] ;?></a>
        </div>
        <div class="rw-shipping-info">
            <span>Shipped on <?= $data['shipping_date'] ;?>-</span>
            <a class="rw-delete-tracking-number" id="rw-delete-tracking-number" href="#">Delete</a>
        </div>
    </div>
