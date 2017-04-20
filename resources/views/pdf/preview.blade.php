@section('page_title')
{{ $title }}
@stop
<!DOCTYPE html>
<html>
	<head>
        @include('includes.head')
        <style>
            .table-borderless td,
            .table-borderless th {
                border: 0 !important;
            }
        </style>
    </head>

	<body>
        <div class="content">
            <div class="container">
                <div class="row m-t-10 m-b-10">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="pull-right m-b-5">
                            <a href="{{ $pdf_url }}" class="btn btn-primary dropdown-toggle waves-effect" aria-expanded="false">下載 PDF <span class="m-l-5"><i class="fa fa-print"></i></span></a>
                        </div>
                    </div>
                </div>

                @foreach ($clients_arr as $data)
                    <div class="row">
                        <div class="card-box col-md-10 col-md-offset-1">
                            <table class="table table-borderless">
                                <tr style="font-size:17px">
                                    <td style="width:15%">路線: <b>{{ $data->client->route . $data->client->route_number }}</b></td>
                                    <td>客戶名稱: <b>{{ $data->client->name }}</b></td>
                                    <td class="text-right">月份: <b>{{ $current_month }}</b></td>
                                </tr>
                            </table>
                            <table class="table text-center table-bordered table-hover">
                                <tbody>
                                    <tr class=" active">
                                        <th></th>
                                        @for ($i = 1; $i < 16; $i++)
                                            <th>{{ $i }}</th>
                                        @endfor
                                    </tr>
                                    @for ($i = 0; $i < count($products); $i++)
                                        <tr>
                                            <th scope="row">{{ $products[$i]->name }}</th>
                                            @for ($j = 1; $j < 16; $j++)
                                                <td>
                                                    @foreach ( $data->monthly_orders as $order )
                                                        @if ( Carbon\Carbon::parse($order->ordered_date)->format('d') == $j 
                                                            AND $order->product_id == $products[$i]->id )
                                                            {{ $order->quantity }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                    <tr>
                                        <th scope="row" style="border-top: 2px solid #b5b5b5">日總計</th>
                                        @for ($i = 1; $i < 16; $i++)
                                            <td style="border-top: 2px solid #b5b5b5">
                                            @foreach ( $data->daily_sums as $sum )
                                                @if ( Carbon\Carbon::parse($sum->ordered_date)->format('d') == $i )
                                                    {{ $sum->sum + 0 }}
                                                @endif
                                            @endforeach
                                            </td>
                                        @endfor
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table text-center m-t-10 table-bordered table-hover">
                                <tbody>
                                    <tr class="active">
                                        <th></th>
                                        @for ($i = 16; $i < 32; $i++)
                                            <th>{{ $i }}</th>
                                        @endfor
                                    </tr>
                                    @for ($i = 0; $i < count($products); $i++)
                                        <tr>
                                            <th scope="row">{{ $products[$i]->name }}</th>
                                            @for ($j = 16; $j < 32; $j++)
                                                <td>
                                                    @foreach ( $data->monthly_orders as $order )
                                                        @if ( Carbon\Carbon::parse($order->ordered_date)->format('d') == $j 
                                                            AND $order->product_id == $products[$i]->id )
                                                            {{ $order->quantity }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                    <tr>
                                        <th scope="row" style="border-top: 2px solid #b5b5b5">日總計</th>
                                        @for ($i = 16; $i < 32; $i++)
                                            <td style="border-top: 2px solid #b5b5b5">
                                            @foreach ( $data->daily_sums as $sum )
                                                @if ( Carbon\Carbon::parse($sum->ordered_date)->format('d') == $i )
                                                    {{ $sum->sum + 0 }}
                                                @endif
                                            @endforeach
                                            </td>
                                        @endfor
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="55%"><h2 class="text-center">收執聯</h2></td>
                                    <td>
                                        <table class="table text-right">
                                            @for ($i = 0; $i < count($products); $i++)
                                                <tr>
                                                    <td style="width:30%;border-bottom:1px solid #333">{{ $products[$i]->name }} (總數量)</td>
                                                    @foreach ($data->monthly_sums as $item)
                                                        @if ($item->name == $products[$i]->name)
                                                        <td style="border-bottom:1px solid #333">{{ $item->total_quantity }}</td>
                                                        <td style="border-bottom:1px solid #333">單價</td>
                                                        <td style="width:15%;border-bottom:1px solid #333">${{ $item->price + 0 }}/{{ $item->unit }}</td>
                                                        <td style="border-bottom:1px solid #333">金額</td>
                                                        <td style="width:20%;border-bottom:1px solid #333">${{ $item->sum + 0 }}</td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endfor
                                            <tr>
                                                <td colspan="5" style="width: 85%">總金額</td>
                                                <td style="width: 15%">NT${{ $data->client_sum ? $data->client_sum->sum + 0 : '0' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <script>
            var resizefunc = [];

            window.onload = function() {
                NProgress.start();
                NProgress.done();
            }

        </script>

        <!-- jQuery  -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/detect.js') }}"></script>
        <script src="{{ asset('js/fastclick.js') }}"></script>
        <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('js/jquery.blockUI.js') }}"></script>
        <script src="{{ asset('js/waves.js') }}"></script>
        <script src="{{ asset('js/wow.min.js') }}"></script>
        <script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
        <script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>
        <script src="{{ asset('plugins/nprogress/nprogress.js') }}"></script>

    </body>
</html>