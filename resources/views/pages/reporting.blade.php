@extends('layouts.default')

@section('page_styles')
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
                @crumbs
            </div>
        </div>

        <div class="panel">
            <div class="panel-body">
                <h4 class="m-t-0 m-b-20 header-title">Search</h4>
                {{-- <div class="well m-t-10">
                    <ul class="nav nav-pills m-b-30">
                        <li class="">
                            <a href="" data-month="current" data-toggle="tab">本月</a>
                        </li>
                        <li class="">
                            <a href="" data-month="previous" data-toggle="tab">上個月</a>
                        </li>
                    </ul>
                </dv> --}}
                <div class="well">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('month', 'Month', array('class' => 'control-label')) }}
                                <ul id="month" class="nav nav-pills">
                                    <li class="active">
                                        <a href="" data-month="{{ $current_month }}" data-toggle="tab">Current</a>
                                    </li>
                                    <li>
                                        <a href="" data-month="{{ $last_month }}" data-toggle="tab">Previous</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{ Form::label('month', 'Type', array('class' => 'control-label')) }}
                                <ul id="route" class="nav nav-pills">
                                    @forelse ($routes as $key => $route)
                                    <li @if ($key == 0) class="active" @endif>
                                        <a href="" data-route="{{$route->route}}" data-toggle="tab">{{$route->route}}</a>
                                    </li>
                                    @empty
                                    <li><a href="" class="disabled" data-toggle="tab">None</a></li>
                                    @endforelse
                                </ul>
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right {{ count($routes) ? 'enabled' : 'disabled' }}"><i class="fa fa-search"></i> Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        


    </div> <!-- container -->
                
</div>

@stop

@section('page_script')
    <script>
        $(document).ready(function(){
            @if (Session::has("state"))
                $.Notification.notify(
                    '{{ Session::get("state") }}',
                    'top right',
                    '{{ Session::get("message") }}'
                );
            @endif
            $('#button-filter.enabled').on('click', function() {
                var month = $('#month li.active > a').data('month'),
                    route = $('#route li.active > a').data('route');
                window.open('{{ url('reporting') }}/pdf/' + month + '/' + route + '/preview', '_blank');
            })
        });
    </script>
@stop