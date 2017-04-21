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
                    <a href="" class="btn btn-danger waves-effect waves-light" data-id="{{ $results->id }}" data-action="{{ route('orders.destroy', $results->id) }}" data-toggle="modal" data-target="#confirm-delete">Delete <span class="m-l-5"><i class="md-delete"></i></span></a>
                    <a href="" class="btn btn-default waves-effect waves-light save_order_btn" data-redirect="0">Submit &amp; Stay <span class="m-l-5"><i class="md md-save"></i></span></a>
                    <a href="" class="btn btn-default waves-effect waves-light save_order_btn" data-redirect="1">Submit <span class="m-l-5"><i class="md md-save"></i></span></a>
                </div>

                @crumbs
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="m-t-0 header-title"><b>編輯訂單</b></h4>
                                    
                        {{ Form::open(['route' => ['orders.update', $results->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
                            <div class="form-group">
                                <label class="col-md-2 control-label">客戶</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" readonly="" value="{{ $results->clients->name }}">                                    
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
                                            <th class="text-right">小計</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $product)
                                            @php
                                                $price = 0;
                                                if ( $product->price == null )
                                                {
                                                    foreach ( $product_prices as $item )
                                                    {
                                                        if ( $item->id == $product->id )
                                                        {
                                                          $price = $item->price + 0;
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    $price = $product->price + 0;
                                                }
                                            @endphp
                                            <tr class="products" data-id="{{ $product->id }}" data-order-product="{{ $product->order_products_id }}" data-price="{{ $price }}">
                                                <td>{{ $product->name }}</td>
                                                <td style="width:160px">
                                                    ${{ $price }}/{{ $product->unit }}
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        {{ Form::number('quantity[]', $product->quantity, ['class' => 'form-control number_only text-right product-quantity']) }}
                                                        <span class="input-group-addon">{{ $product->unit }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-right">${{ $product->total ? $product->total + 0 : 0 }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">總計</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">${{ $results->total }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">訂購日期 <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        {{ Form::text('ordered_date', $results->ordered_date, ['class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'id' => 'datepicker']) }}
                                        <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                            {{ Form::hidden('order_id', $results->id) }}
                            {{ Form::hidden('edit', true) }}
                        {{ Form::close() }}
                </div>
            </div>
        </div>

        <!-- MODAL -->
        @include('includes.mdlbs_delete')
        <!-- end Modal -->

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
            $('.number_only').on('keypress', function(event){
                if (event.keyCode < 48 || event.keyCode > 57)
                    return false;
            });
            $('#confirm-delete').on('click', '.btn-ok', function(e) {
                var id = $(this).data('order_id'),
                    data = {
                    mdl: $('#confirm-delete'),
                    id: id,
                    table: $('#datatable-buttons').DataTable(),
                    row: $('#order_' + id),
                    action: $(this).data('action'),
                    redirect: true
                }
                Ajax.delete(data);
                
            });

            $('#confirm-delete').on('show.bs.modal', function(e) {
                var data = $(e.relatedTarget).data();
                $('.btn-ok', this).data('order_id', data.id);
                $('.btn-ok', this).data('action', data.action);
            });
        });
    </script>
@stop