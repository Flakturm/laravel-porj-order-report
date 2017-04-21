@extends('layouts.default')

@section('page_title')
{{ crumbs()->pageTitle() }}
@stop
@section('page_styles')
    <link href="{{ asset('public/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/plugins/jquery-datatables-editable/datatables.css') }}" rel="stylesheet" />
@stop
@section('content')

<div class="content">
    <div class="container">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">

                <div class="button-list pull-right m-t-5">
                    <a href="{{ url('clients') }}" class="btn btn-white waves-effect waves-light">Cancel <span class="m-l-5"><i class="md-undo"></i></span></a>
                    <a href="" class="btn btn-danger waves-effect waves-light" data-id="{{ $results->id }}" data-action="{{ route('clients.destroy', $results->id) }}" data-toggle="modal" data-target="#confirm-delete">Delete <span class="m-l-5"><i class="md-delete"></i></span></a>
                    <a href="" class="btn btn-default waves-effect waves-light save_client_btn" data-id="{{ $results->id }}" data-redirect="0">Submit &amp; Stay <span class="m-l-5"><i class="md md-save"></i></span></a>
                    <a href="" class="btn btn-default waves-effect waves-light save_client_btn" data-id="{{ $results->id }}" data-redirect="1">Submit <span class="m-l-5"><i class="md md-save"></i></span></a>
                </div>

                @crumbs
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="m-t-0 header-title"><b>客戶資料</b></h4>
                                    
                        {{ Form::open(['route' => ['clients.update', $results->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
                            <div class="form-group">
                                {{ Form::label('route', '路線', array('class' => 'col-md-2 control-label')) }}
                                <div class="col-md-10 row">
                                        <div class="col-md-1">
                                            {{ Form::text('route', $results->route, array('class' => 'form-control')) }}
                                        </div>
                                        {{ Form::label('route_number', '編號', array('class' => 'col-md-1 control-label')) }}
                                        <div class="col-md-2">
                                            {{ Form::text('route_number', $results->route_number, array('class' => 'form-control number_only')) }}
                                        </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('name', '客戶', array('class' => 'col-md-2 control-label')) }}
                                <div class="col-md-10">
                                    {{ Form::text('name', $results->name, array('class' => 'form-control')) }}                               
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('size', '尺寸', array('class' => 'col-md-2 control-label')) }}
                                <div class="col-md-2">
                                     {{ Form::select('is_small', [0 => '普通', 1 => '小'], $results->is_small, ['class' => 'form-control selectpicker', 'data-style' => 'btn-white']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('invoiced_daily', '請款頻率', array('class' => 'col-md-2 control-label')) }}
                                <div class="col-md-2">
                                    {{ Form::select('invoiced_daily', [0 => '年', 1 => '日'], $results->invoiced_daily, ['class' => 'form-control selectpicker', 'data-style' => 'btn-white']) }}
                                </div>
                            </div>
                            {{ Form::hidden('edit', true) }}
                            {{ Form::token() }}
                        {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-b-15">
                        <button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false">預覽 <span class="m-l-5"><i class="fa fa-print"></i></span></button>
                        <ul class="dropdown-menu drop-menu-right" role="menu">
                            <li><a href="{{ url('clients', [$results->id, 'pdf', 'preview']) }}" target="_blank">當月訂單</a></li>
                        </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive">
                <h4 class="m-t-10 header-title"><b>訂單</b></h4>
                    <table id="datatable-buttons" class="table table-striped table-bordered table-hover table-actions-bar">
                        <thead>
                        <tr>
                            <th></th>
                            <th>訂單 ID</th>
                            <th>訂購日期</th>
                            <th class="text-right">總計</th>
                            <th class="text-right">動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($results->orders as $order)
                        <tr id="order_{{ $order->id }}">
                            <td><a href=""><i class="ion-arrow-right-b"></i></a></td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->ordered_date }}</td>
                            <td class="text-right">${{ $order->total }}</td>
                            <td class="actions text-right">
                                <a href="{{ route('orders.edit', $order->id) }}" class="table-action-btn"><i class="md md-edit"></i></a>
                                {{-- <a href="" class="on-default remove-row" data-id="{{ $order->id }}" data-action="{{ route('orders.destroy', $order->id) }}" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> --}}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
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
    <script src="{{ asset('public/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/plugins/datatables/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('public/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('public/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('public/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('public/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/plugins/datatables/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    
    <script src="{{ asset('public/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('public/pages/ajax.init.js') }}"></script>
    <script>
        var openRows = new Array();
        function format ( rowData ) {
            // rowData is the original data object for the row
            var orders = JSON.parse( "{{ $order_products }}".replace(/&quot;/g,'"') );
            var table = '<div class="col-sm-4"><table class="table table-responsive"><tbody>';

            $.each(orders, function( index, value ) {
                $.each(value.order_products, function( index, value ) {
                    if ( value.order_id == rowData[1] ) {
                        table += '<tr>';
                        table += '<td>' + value.products.name + '</td>';
                        table += '<td>' + value.quantity + ' ' + value.products.unit + '</td>';
                        table += '<td class="text-right">$' + value.total + '</td>';
                        table += '</tr>';
                    }
                });
            });

            table += '</tbody></table></div>';

            return table;
        }
        function closeOpenedRows(table, selectedRow) {
            $.each(openRows, function (index, openRow) {
                // not the selected row!
                if ($.data(selectedRow) !== $.data(openRow)) {
                    var rowToCollapse = table.row(openRow);
                    rowToCollapse.child.hide();
                    openRow.removeClass('shown');
                    // replace icon to expand
                    $(openRow).find('td.details-control').html('<a href=""><i class="ion-arrow-right-b"></i></a>');
                    // remove from list
                    var index = $.inArray(selectedRow, openRows);
                    openRows.splice(index, 1);
                }
            });
        }
        $(document).ready(function () {
            
            $('.number_only').on('keypress', function(event){
                if (event.keyCode < 48 || event.keyCode > 57)
                    return false;
            });
            $('#confirm-delete').on('click', '.btn-ok', function(e) {
                var id = $(this).data('id'),
                    data = {
                    mdl: $('#confirm-delete'),
                    id: id,
                    table: $('#datatable-buttons').DataTable(),
                    action: $(this).data('action'),
                    redirect: true
                }
                Ajax.delete(data);
                
            });

            $('#confirm-delete').on('show.bs.modal', function(e) {
                var data = $(e.relatedTarget).data();
                $('.btn-ok', this).data('id', data.id);
                $('.btn-ok', this).data('action', data.action);
            });
            TableManageButtons.clientOrders();
            // Add event listener for opening and closing details
            $('#datatable-buttons tbody').on('click', 'td.details-control', function () {
                var table = $('#datatable-buttons').DataTable();
                var tr = $(this).closest('tr');
                var row = table.row(tr);
        
                if (row.child.isShown()) {
                    // This row is already open - change icon
                    $(this).html('<a href=""><i class="ion-arrow-right-b"></i></a>');
                    // close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // close all previously opened rows
                    //closeOpenedRows(table, tr);
        
                    // This row should be opened - change icon
                    $(this).html('<a href=""><i class="ion-arrow-down-b"></i></a>');
                    // and open this row
                    row.child( format( row.data() ) ).show();
                    tr.addClass('shown');
        
                    // store current selection
                    openRows.push(tr);
                }
                return false;
            });
        });
    </script>
@stop