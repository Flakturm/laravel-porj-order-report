@extends('layouts.default')

@section('page_styles')
    <link href="{{ asset('plugins/jquery-datatables-editable/datatables.css') }}" rel="stylesheet" />
@stop

@section('page_title')
{{ crumbs()->pageTitle() }}
@stop
@section('content')

<div class="content">
    <div class="container">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">

                <div class="btn-group pull-right m-t-5">
                    <a href="{{ url('orders/create') }}" class="btn btn-default waves-effect waves-light">Add <span class="m-l-5"><i class="ion-plus"></i></span></a>
                </div>

                @crumbs
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">

                <div class="card-box table-responsive">
                    <table id="datatable-buttons" class="table table-striped table-bordered table-hover table-actions-bar">
                    </table>
                </div>
            </div>
        </div>
        <!-- end: page -->

        <!-- MODAL -->
        @include('includes.mdlbs_delete')
        <!-- end Modal -->

    </div> <!-- container -->
                
</div>

@stop

@section('page_script')
    <script src="{{ asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap.min.js') }}"></script>

    <script src="{{ asset('pages/datatables.init.js') }}"></script>
    <script src="{{ asset('pages/ajax.init.js') }}"></script>

    <script>
        $(document).ready(function () {
            @if (Session::has("success"))
                $.Notification.notify(
                    'success',
                    'top right',
                    '{{ Session::get("success") }}'
                );
            @endif

            $('#confirm-delete').on('click', '.btn-ok', function(e) {
                var id = $(this).data('order_id'),
                    data = {
                    mdl: $('#confirm-delete'),
                    id: id,
                    table: $('#datatable-buttons').DataTable(),
                    row: $('#order_' + id),
                    action: $(this).data('action'),
                    redirect: false
                }
                Ajax.delete(data);
                
            });

            $('#confirm-delete').on('show.bs.modal', function(e) {
                var data = $(e.relatedTarget).data();
                $('.btn-ok', this).data('order_id', data.id);
                $('.btn-ok', this).data('action', data.action);
            });
        });
        TableManageButtons.orders();
    </script>
@stop