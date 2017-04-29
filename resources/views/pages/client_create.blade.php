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
                    <a href="" class="btn btn-default waves-effect waves-light save_client_btn" data-redirect="0">Submit &amp; Stay <span class="m-l-5"><i class="md md-save"></i></span></a>
                    <a href="" class="btn btn-default waves-effect waves-light save_client_btn" data-redirect="1">Submit <span class="m-l-5"><i class="md md-save"></i></span></a>
                </div>

                @crumbs
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="m-t-0 header-title"><b>Add Client</b></h4>
                                    
                        {{ Form::open(['action' => 'ClientsController@store', 'method' => 'POST', 'class' => 'form-horizontal']) }}
                            <div class="form-group">
                                {{ Form::label('route', 'Type', array('class' => 'col-md-2 control-label')) }}
                                <div class="col-md-10 row">
                                        <div class="col-md-1">
                                            {{ Form::text('route', null, array('class' => 'form-control')) }}
                                        </div>
                                        {{ Form::label('route_number', 'Number', array('class' => 'col-md-1 control-label')) }}
                                        <div class="col-md-2">
                                            {{ Form::text('route_number', null, array('class' => 'form-control number_only')) }}
                                        </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('name', 'Name', array('class' => 'col-md-2 control-label')) }}
                                <div class="col-md-10">
                                    {{ Form::text('name', null, array('class' => 'form-control')) }}                               
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('size', 'Size', array('class' => 'col-md-2 control-label')) }}
                                <div class="col-md-2">
                                     {{ Form::select('is_small', [0 => 'Regular', 1 => 'Small'], 0, ['id' => 'size', 'class' => 'form-control selectpicker', 'data-style' => 'btn-white']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('invoiced_daily', 'Invoiced', array('class' => 'col-md-2 control-label')) }}
                                <div class="col-md-2">
                                    {{ Form::select('invoiced_daily', [0 => 'Annually', 1 => 'Daily'], 0, ['id' => 'invoiced_daily', 'class' => 'form-control selectpicker', 'data-style' => 'btn-white']) }}
                                </div>
                            </div>
                            {{ Form::hidden('edit', false) }}
                            {{ Form::token() }}
                        {{ Form::close() }}
                </div>
            </div>
        </div>

    </div> <!-- container -->
                
</div>

@stop

@section('page_script')
    <script src="{{ asset('public/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('public/pages/ajax.init.js') }}"></script>
    <script>
        $(document).ready(function () {
            @if (Session::has("state"))
                $.Notification.notify(
                    '{{ Session::get("state") }}',
                    'top right',
                    '{{ Session::get("message") }}'
                );
            @endif
            $('.number_only').on('keypress', function(event){
                if (event.keyCode < 48 || event.keyCode > 57)
                    return false;
            });
            
        });
    </script>
@stop