jQuery(document).ready(function($) {

    $('body').on('click', '.rw-btn-add-tracking-number', function () {

       $(this).css('display', 'none');
       $('#rw-tracking-info-form').css('display', 'block');

    });

    $('body').on('click', '.rw-close-tracking-form', function (e) {
        e.preventDefault();
        $('#rw-tracking-info-form input').removeClass('wc-rw-error');
        $('#rw-tracking-info-form select').removeClass('wc-rw-error');
        $('.rw-btn-add-tracking-number').css('display', 'block');
        $('#rw-tracking-info-form').css('display', 'none');

    });



});