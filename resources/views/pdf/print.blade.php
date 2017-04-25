<!DOCTYPE html>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $title }}</title>
        <link href="{{ asset('public/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
        <style media="print">
        @page 
        {
            size:  auto;   /* auto is the initial value */
            margin: 10mm;  /* this affects the margin in the printer settings */
        }
        .no-print, .no-print *
        {
            display: none !important;
        }
        </style>
        <style>
        @page { margin: 20px !important; }
        @media screen {
            body {
                background: #ebeff2;
            }
            #container {
                margin: auto;
                width: 80%;
            }
            #print-btn {
                background-color: #5fbeaa;
                border: 1px solid #5fbeaa;
                color: #ffffff;
                border-radius: 3px;
                outline: none !important;
                font-size: 14px;
                font-weight: 400;
                padding: 6px 12px;
                line-height: 1.42857143;
                text-align: center;
                white-space: nowrap;
                margin: 15px 0;
                float: right;
            }
            #content {
                clear: both;
            }
            .block {
                padding: 20px;
                border: 1px solid rgba(54, 64, 74, 0.05);
                -webkit-border-radius: 5px;
                border-radius: 5px;
                -moz-border-radius: 5px;
                background-clip: padding-box;
                margin-bottom: 20px;
                background-color: #ffffff;
            }
        }
        .table {
            width: 100%;
            max-width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }
        .borderless td, .borderless, .table.borderless td {
            border: none;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .table th {
            background-color: #eee;
            border-bottom: 2px solid #ccc;
        }
        .l-col {
            border-right: 2px solid #b5b5b5;
            background-color: #eee;
            width: 8%;
            text-align: left;
            padding: 2px 8px;
        }
        .table td, .table th {
            border: 1px solid #b5b5b5;
        }
        .m-t-10 {
            margin-top: 10px;
        }
        .m-t-5 {
            margin-top: 5px;
        }
        .m-t-2 {
            margin-top: 2px;
        }
        .m-r-5 {
            margin-top: 5px;
        }
        hr {
            border-top:5px dotted #444;
            border-bottom: none;
            margin: 40px 0;
        }
        .page-break {
            page-break-after: always;
        }
        </style>
    </head>

	<body>
        <div id="container">
            <button id="print-btn" class="no-print" onClick="window.print()">Print</button>

            <div id="content"></div>
        </div>

        <script src="{{ asset('public/js/jquery.min.js') }}"></script>
        <script src="{{ asset('public/plugins/nprogress/nprogress.js') }}"></script>
        <script>
             var resizefunc = [];

            window.onload = function() {
                NProgress.start();
            }

            $(document).ready(function(){

                $.get('{{ URL::current() }}', function( data ) {
                    //console.log(data);
                    var html = [], block = [];
                    $.each(data.clients_arr, function( index, obj ) {
                        //console.log(obj);
                        html.push('<div class="block">');
                        for(var j = 0; j < 2; j++) {
                            html.push('<table class="table borderless">',
                            '<tr style="font-size:17px">',
                            '<td style="width:15%">Type: <span class="bold">' + obj.client.route + obj.client.route_number + '</span></td>',
                            '<td>Client: <span class="bold">' + obj.client.name + '</span></td>',
                            '<td class="text-right">Month: <span class="bold">{{ $current_month }}</span></td>',
                            '</tr></table><table class="table text-center m-t-5"><tbody><tr class="bold"><th></th>');
                            for(var i = 1; i < 16; i++) {
                                html.push('<th>' + i + '</th>');
                            }
                            html.push('</tr>');
                            $.each(data.products, function( index, product ) {
                                html.push('<tr>');
                                html.push('<td class="bold l-col bold">' + product.name + '</td>');
                                    for(var i = 1; i < 16; i++) {
                                        html.push('<td>');
                                        $.each(obj.monthly_orders, function( index, order ) {
                                            var date = new Date(order.ordered_date);
                                            if(date.getDate() == i && order.product_id == product.id) {
                                                html.push(order.quantity);
                                            }
                                        });
                                        html.push('</td>');
                                    }
                                html.push('</tr>');
                            });
                            
                            html.push('<tr><td class="l-col bold" style="border-top: 2px solid #b5b5b5">Daily sum</td>');
                                for(var i = 1; i < 16; i++) {
                                    html.push('<td style="border-top: 2px solid #b5b5b5;width:5%">');
                                    $.each(obj.daily_sums, function( index, sum ) {
                                        var date = new Date(sum.ordered_date);
                                        if(date.getDate() == i) {
                                            html.push(parseInt(sum.sum));
                                        }
                                    });
                                    html.push('</td>');
                                }
                            html.push('</tr></tbody></table>');
                            html.push('<table class="table text-center m-t-10"><tbody><tr class="bold"><th></th>');
                            for(var i = 16; i < 32; i++) {
                                html.push('<th>' + i + '</th>');
                            }
                            html.push('</tr>');
                            $.each(data.products, function( index, product ) {
                                html.push('<tr>');
                                html.push('<td class="bold l-col bold">' + product.name + '</td>');
                                    for(var i = 16; i < 32; i++) {
                                        html.push('<td>');
                                        $.each(obj.monthly_orders, function( index, order ) {
                                            var date = new Date(order.ordered_date);
                                            if(date.getDate() == i && order.product_id == product.id) {
                                                html.push(order.quantity);
                                            }
                                        });
                                        html.push('</td>');
                                    }
                                html.push('</tr>');
                            });
                            
                            html.push('<tr><td class="l-col bold" style="border-top: 2px solid #b5b5b5">Daily sum</td>');
                                for(var i = 16; i < 32; i++) {
                                    html.push('<td style="border-top: 2px solid #b5b5b5;width:4.7%">');
                                    $.each(obj.daily_sums, function( index, sum ) {
                                        var date = new Date(sum.ordered_date);
                                        if(date.getDate() == i) {
                                            html.push(parseInt(sum.sum));
                                        }
                                    });
                                    html.push('</td>');
                                }
                            html.push('</tr></tbody></table><table class="table borderless m-t-10"><tr><td width="55%"><h2 class="text-center receipt">Receipt</h2></td><td><table class="table  borderless text-right">');
                            $.each(data.products, function( index, product ) {
                                html.push('<tr><td style="border-bottom:1px solid #333">');
                                html.push(product.name + '</td>');
                                if(obj.monthly_sums.length > 0) {
                                    $.each(obj.monthly_sums, function( index, item ) {
                                        if(item.name == product.name) {
                                            html.push('<td style="border-bottom:1px solid #333">' + item.total_quantity + '</td>');
                                            html.push('<td style="border-bottom:1px solid #333">Price</td>');
                                            html.push('<td style="width:15%;border-bottom:1px solid #333">' + parseInt(item.price) + '/' + product.unit + '</td>');
                                            html.push('<td style="border-bottom:1px solid #333;padding-right:15px">Amount</td>');
                                            html.push('<td style="width:25%;border-bottom:1px solid #333">' + parseInt(item.sum) + '</td>');
                                        }
                                    });
                                } else {
                                    html.push('<td style="border-bottom:1px solid #333"></td>');
                                    html.push('<td style="border-bottom:1px solid #333">Price</td>');
                                    html.push('<td style="width:15%;border-bottom:1px solid #333">/' + product.unit + '</td>');
                                    html.push('<td style="border-bottom:1px solid #333;padding-right:15px">Amount</td>');
                                    html.push('<td style="width:25%;border-bottom:1px solid #333"></td>');
                                }

                                html.push('</tr>');
                            });

                            html.push('<tr><td colspan="5" style="width: 100%;padding-right: 15px;">Total NT$</td><td style="width: 25%;border-top:1px solid #333">');
                            html.push(obj.client_sum ? parseInt(obj.client_sum.sum) : '');
                            html.push('</td></tr>');

                            html.push('</table></td></tr></table><hr>');
                        }
                        html.push('</div>');
                    });
                    $('#content').append(html.join(''));
                    $('.receipt:even').append(' (Kept by Payee)');
                    $('hr:odd').hide();
                    $('.block:not(:last)').after('<div class="page-break"></div>');
                    NProgress.done();
                });
            });
        </script>
    </body>
</html>