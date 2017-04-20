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

                <h4 class="page-title"></h4>
                @crumbs
            </div>
        </div>

        <div class="panel">
                            
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="m-b-30">
                            <button id="addToTable" class="btn btn-default waves-effect waves-light">Add <i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                
                <div class="">
                    <table class="table table-striped" id="datatable-editable">
                        <thead>
                            <tr>
                                <th>產品</th>
                                <th>單價</th>
                                <th>單價(小)</th>
                                <th>單位</th>
                                <th class="text-right">動作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                            <tr>
                                <td>{{ $result->name }}</td>
                                <td>{{ $result->price + 0 }}</td>
                                <td>{{ $result->price2 + 0 }}</td>
                                <td>{{ $result->unit }}</td>
                                <td class="actions text-right">
                                    <a href="" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
                                    <a href="" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>
                                    <a href="" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                    {{-- <a href="" class="on-default remove-row"><i class="fa fa-trash-o"></i></a> --}}
                                    {{ Form::hidden('row_id', $result->id) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
    <script src="{{ asset('plugins/jquery-datatables-editable/jquery.dataTables.js') }}"></script>
@stop
@section('page_script')
    <script src="{{ asset('pages/datatables.editable.prod.init.js') }}"></script>
@stop