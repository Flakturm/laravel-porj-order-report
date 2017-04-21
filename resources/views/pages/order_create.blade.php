@extends('layouts.default')

@section('page_title')
{{ crumbs()->pageTitle() }}
@stop
@section('page_styles')
    <link href="{{ asset('public/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@stop

@section('content')

<div class="content">
    <div class="container">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">

                <div class="button-list pull-right m-t-5">
                    <a href="{{ url('orders') }}" class="btn btn-white waves-effect waves-light">Cancel <span class="m-l-5"><i class="md-undo"></i></span></a>
                    <a href="" class="btn btn-default waves-effect waves-light save_order_btn" data-redirect="0">Submit &amp; add new <span class="m-l-5"><i class="md md-save"></i></span></a>
                    <a href="" class="btn btn-default waves-effect waves-light save_order_btn" data-redirect="1">Submit <span class="m-l-5"><i class="md md-save"></i></span></a>
                </div>

                @crumbs
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="m-t-0 header-title"><b>新增訂單</b></h4>
                                    
                        {{ Form::open(['action' => 'OrdersController@store', 'class' => 'form-horizontal']) }}
                            <div class="form-group">
                                <label class="col-md-2 control-label">客戶 <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <select id="client_id" class="form-control select2" name="client_id">
                                        <option value="">Select</option>
                                        @foreach ($routes as $route)
                                            <optgroup label="路線 {{ $route->route }}">
                                                @foreach ($clients as $client)
                                                    @if ($route->route == $client->route)
                                                        <option value="{{ $client->id }}">{{ $client->route . $client->route_number . ' ' . $client->name }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">產品 <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <table class="table table-striped m-0">
                                        <thead>
                                            <th>產品</th>
                                            <th>單價</th>
                                            <th class="text-right">數量</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $product)
                                            <tr id="row-{{ $product->id }}" class="products" data-id="{{ $product->id }}" data-price="{{ $product->price }}">
                                                <td>{{ $product->name }}</td>
                                                <td style="width:160px">
                                                    $<span class="product_price">{{ $product->price + 0 }}</span>/{{ $product->unit }}
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        {{ Form::number('quantity[]', null, ['class' => 'form-control number_only text-right product-quantity']) }}
                                                        <span class="input-group-addon">{{ $product->unit }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">訂購日期 <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        {{ Form::text('ordered_date', null, ['class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'id' => 'datepicker']) }}
                                        <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                            {{ Form::hidden('edit', false) }}
                        {{ Form::close() }}
                </div>
            </div>
        </div>


    </div> <!-- container -->
                
</div>

@stop

@section('page_script')
    <script src="{{ asset('public/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('public/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('public/pages/ajax.init.js') }}"></script>
    <script>
        $(document).ready(function () {
            @if (Session::has("success"))
                $.Notification.notify(
                    'success',
                    'top right',
                    '{{ Session::get("success") }}'
                );
            @endif
            $(".select2").select2();
            $('#datepicker').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });
            $('.number_only').on('keypress', function(event) {
                if (event.keyCode < 48 || event.keyCode > 57)
                    return false;
            });
            $('#client_id').on('change', function() {
                var url = '{{ url('orders', ['ajax', 'client']) }}' + '/' + $(this).val();
                $.get(url, function(data) {
                    $.each(data.products, function ( key, value ){
                        console.log(value);
                        $('#row-' + value.id).data('price', value.price);
                        $('#row-' + value.id).find('.product_price').text(parseInt(value.price));
                    });
                });
            });
        });
    </script>
@stop