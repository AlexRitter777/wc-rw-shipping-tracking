jQuery(document).ready(function($) {

    //add tracking information request
    $('body').on('click','#rw-save-tracking-number',function(e) {
        e.preventDefault();

        startSpinner();

        //remove red borders from fields
        $('#rw-tracking-info-form input').removeClass('wc-rw-error');
        $('#rw-tracking-info-form select').removeClass('wc-rw-error');


        //check if order status change checkbox is checked
        let order_status_change = $("#rw-tracking-info-form #change_order_status_to_shipped").is(':checked');

        $.ajax({
            type: "POST",
            url: my_ajax_obj.ajax_url,
            data: {
                action: "add_track_number_action",
                order_id: getOrderIdFromUrl(),
                shipping_company: $("#rw-tracking-info-form #shipping_company").val(),
                tracking_number: $("#rw-tracking-info-form #tracking_number").val(),
                shipping_date: $("#rw-tracking-info-form #shipping_date").val(),
                change_status: order_status_change
            },
            dataType: "json",
            encode: true

        })
            .done((response) => {

                //console.log(response); //debugging
                stopSpinner();
                //show red border on wrong fields and stop script if server validation was not success
                if(!response['success']){
                    response['validation'].forEach(function (index, item){
                        //console.log(index); debugging
                        $('#rw-tracking-info-form #' + index ).addClass('wc-rw-error');
                    });
                return;
                }


                //get meta box template
                let template = response['template'];

                //console.log(template); //debugging

                //remove form
                $('#rw-tracking-container-content').remove();

                //add template
                $('#rw-tracking-container').append(template);

                //change Woo Commerce order status option - only front end
                if(order_status_change) {
                    $('#order_status').val('wc-completed').change();
                }


            })

            .fail(() =>{
                console.log('Server connection error! Please try again later!');
                alert('Server connection error! Please try again later!');
            })
        ;

    });


    //delete tracking information request
    $('body').on('click', '#rw-delete-tracking-number', function(e) {
        e.preventDefault();
        startSpinner();
        $.ajax({
            type: "POST",
            url: my_ajax_obj.ajax_url,
            data: {
                action: "remove_track_number_action",
                order_id: getOrderIdFromUrl()
            },
            dataType: "json",
            encode: true

        })
            .done((response) => {

                //get template from server
                let template = response['template'];

                //console.log(template); debugging
                //remove template with tracking information
                $('#rw-tracking-info-container').remove();

                stopSpinner();

                //add origin template
                $('#rw-tracking-container').append(template);

                //add date datepicker to date field
                $('body').on('focus', '.rw-wc-shipping-tracking-datepicker', function (){

                    $(this).datepicker({ dateFormat: 'yy-mm-dd' });
                });

                //console.log(response); debugging

            })

            .fail(() =>{
                console.log('Server connection error! Please try again later!');
                alert('Server connection error! Please try again later!');
            })
        ;

    });


} );


// Get current order Id
function getOrderIdFromUrl(){

    let queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    return urlParams.get('post');

}

//loader spinner start
function startSpinner(){
    jQuery('#wc-rw-opacity').addClass('opacity');
    jQuery('#wc-rw-spinner').addClass('is-active');
}

//loader spinner stop
function stopSpinner(){
    jQuery('#wc-rw-opacity').removeClass('opacity');
    jQuery('#wc-rw-spinner').removeClass('is-active');
}
