function doAjax (type, url, formData) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: type,
        url: url,
        data: formData,
        dataType: 'json',
        beforeSend: function () {
            $('.btn').addClass('disabled');
        },
        complete: function () {
            $('.btn').removeClass('disabled');
        },
        success: function (data) {
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
                return false;
            }
            $.Notification.notify(
                'success',
                'top right',
                'Messages',
                data.message
            )
        },
        error: function (data) {
            var response = data.responseJSON;
            var error_msg = [];

            $.each( response.errors, function( key, value ) {
                error_msg += value + '<br>';
            });
            $(function(){
                $.Notification.notify(
                    'error',
                    'top right',
                    'Messages',
                    error_msg
                )
            });
        }
    });
}

$(function () {

    $('.save_client_btn').on('click', function () {
        var formData = {
            redirect: $(this).data('redirect'),
            id: $(this).data('id'),
            route: $('[name="route"]').val(),
            route_number: $('[name="route_number"]').val(),
            name: $('[name="name"]').val(),
            is_small: $('[name="is_small]').val(),
            invoiced_daily: $('[name="invoiced_daily"]').val()
        }

        var type = $('[name="_method"]').val();
        var url = $('form').prop('action');

        doAjax( type, url ,formData );
        
        return false;
    });

    $('.save_order_btn').on('click', function () {
        var redirect = $(this).data('redirect');
        var order_value = [];
        var sum = 0;
        $('.products').each(function (i) {
            var order_product_id = ($(this).data('order-product')) ? $(this).data('order-product') : null;
            var product_id = $(this).data('id');
            var quantity = $(this).find('.product-quantity').val();
            var price = parseFloat( $(this).data('price') );
            var total = ( parseFloat( quantity ) * parseFloat( price ) ) ? parseFloat( quantity ) * parseFloat( price ) : '' ;
            order_value.push({
                order_product_id: order_product_id,
                product_id: product_id,
                quantity: quantity,
                price: price,
                total: total
            });
            if ( quantity > 0 ) {
                sum += total;
            }    
        })

        var formData = {
            redirect: redirect,
            total: sum,
            ordered_date: $('[name="ordered_date"]').val(),
            order_value: order_value
        }

        var type = $('[name="_method"]').val() ? $('[name="_method"]').val() : 'POST';
        var url = $('form').prop('action');

        if ( $('[name="edit"]').val() ) {
            formData['order_id'] = $('[name="order_id"]').val();
        } else {
            formData['client_id'] = $('[name="client_id"]').val();
        }

        doAjax( type, url ,formData );
        
        return false;
    });

});

var handleDeleteRowAjax = function(arr_data) {
    "use strict";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    })

    var formData = { 'id': arr_data['id'], 'redirect': arr_data['redirect'] };

    $.ajax({
        type: 'DELETE',
        url: arr_data['action'],
        data: formData,
        dataType: 'json',
        success: function (data) {
            
            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return false;
                } else {
                    arr_data['table'].row( arr_data['row'] ).remove().draw();
                    arr_data['mdl'].modal('hide');
                    $.Notification.notify(
                        'success',
                        'top right',
                        data.message
                    );
                }
            }
        },
        error: function (data) {
            var response = data.responseJSON;
            var error_msg = [];

            $.each( response.errors, function( key, value ) {
                error_msg += value + '<br>';
            });
            $(function(){
                $.Notification.notify(
                    'error',
                    'top right',
                    'Messages',
                    error_msg
                )
            });
        }
    });
},
getProductsAjax = function(order_product) {
    "use strict";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    })

    var formData = { 'id': order_product['id'] };

    $.ajax({
        type: 'GET',
        url: order_product['action'],
        data: formData,
        dataType: 'json',
        success: function (data) {
            
            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    arr_data['table'].row( arr_data['row'] ).remove().draw();
                    arr_data['mdl'].modal('hide');
                    $.Notification.notify(
                        'success',
                        'top right',
                        data.message
                    );
                }
            }
        },
        error: function (data) {
            var response = data.responseJSON;
            var error_msg = [];

            $.each( response.errors, function( key, value ) {
                error_msg += value + '<br>';
            });
            $(function(){
                $.Notification.notify(
                    'error',
                    'top right',
                    'Messages',
                    error_msg
                )
            });
        }
    });
}    
Ajax = function() {
    "use strict";
    return {
        delete: function(data) {
            handleDeleteRowAjax(data)
        },
        clientsProducts: function (order_product) {
            getProductsAjax(order_product);
        }
    }
}();