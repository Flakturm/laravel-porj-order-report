@extends('layouts.default')

@section('page_styles')
    <link href="{{ asset('public/plugins/jquery-datatables-editable/datatables.css') }}" rel="stylesheet" />
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
                    <a href="{{ url('clients/create') }}" class="btn btn-default waves-effect waves-light">Add <span class="m-l-5"><i class="ion-plus"></i></span></a>
                </div>
                @crumbs
            </div>
        </div>

        <div class="panel">
                            
            <div class="panel-body">                
                <table class="table table-striped" id="datatable-editable">
                </table>
            </div>
            <!-- end: page -->

        </div> <!-- end Panel -->

        <!-- MODAL -->
        @include('includes.mdlbs_delete')
        <!-- end Modal -->

    </div> <!-- container -->
                
</div>

@stop

@section('editable_script')
    <script src="{{ asset('public/plugins/jquery-datatables-editable/jquery.dataTables.js') }}"></script>
@stop
@section('page_script')
    <script src="{{ asset('public/pages/datatables.editable.clients.init.js') }}"></script>
    <script>
    $(document).ready(function(){
        @if (Session::has("state"))
            $.Notification.notify(
                '{{ Session::get("state") }}',
                'top right',
                '{{ Session::get("message") }}'
            );
        @endif
        $('[data-toggle="tooltip"]').tooltip(); 
    });
    </script>
@stop