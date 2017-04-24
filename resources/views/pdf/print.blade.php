<!DOCTYPE html>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $title }}</title>
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
        #.simsun {
        #    font-family: 'simsun';
        #}
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
        <button class="no-print" onClick="window.print()">Print</button>
        @foreach ($clients_arr as $key => $data)
            <div class="block">
                <table class="table simsun borderless">
                    <tr style="font-size:17px">
                        <td style="width:15%">路線: <b>{{ $data->client->route . $data->client->route_number }}</b></td>
                        <td>客戶名稱: <b>{{ $data->client->name }}</b></td>
                        <td class="text-right">月份: <b>{{ $current_month }}</b></td>
                    </tr>
                </table>
                <table class="table text-center m-t-5">
                    <tbody>
                        <tr class="bold">
                            <th></th>
                            @for ($i = 1; $i < 16; $i++)
                                <th>{{ $i }}</th>
                            @endfor
                        </tr>
                        @for ($i = 0; $i < count($products); $i++)
                            <tr>
                                <td class="bold simsun l-col bold">{{ $products[$i]->name }}</td>
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
                            <td class="simsun l-col bold" style="border-top: 2px solid #b5b5b5">日總計</td>
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
                <table class="table text-center m-t-10">
                    <tbody>
                        <tr class="bold">
                            <th></th>
                            @for ($i = 16; $i < 32; $i++)
                                <th>{{ $i }}</th>
                            @endfor
                        </tr>
                        @for ($i = 0; $i < count($products); $i++)
                            <tr>
                                <td class="bold simsun l-col bold">{{ $products[$i]->name }}</td>
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
                            <td class="simsun l-col bold" style="border-top: 2px solid #b5b5b5">日總計</td>
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
                <table class="table borderless m-t-10">
                    <tr>
                        <td width="55%"><h2 class="simsun text-center">Receipt (Kept by Payee)</h2></td>
                        <td>
                            <table class="table simsun borderless text-right">
                                @for ($i = 0; $i < count($products); $i++)
                                    <tr>
                                        <td style="width:30%;border-bottom:1px solid #333">{{ $products[$i]->name }} (Total quantity)</td>
                                        @forelse ($data->monthly_sums as $item)
                                            @if ($item->name == $products[$i]->name)
                                            <td style="border-bottom:1px solid #333">{{ $item->total_quantity }}</td>
                                            <td style="border-bottom:1px solid #333">Price</td>
                                            <td style="width:15%;border-bottom:1px solid #333">${{ $item->price + 0 }}/{{ $item->unit }}</td>
                                            <td style="border-bottom:1px solid #333">Total</td>
                                            <td style="width:20%;border-bottom:1px solid #333">${{ $item->sum + 0 }}</td>
                                            @endif
                                        @empty
                                            <td style="border-bottom:1px solid #333"></td>
                                            <td style="border-bottom:1px solid #333">Price</td>
                                            <td style="width:15%;border-bottom:1px solid #333">/{{ $products[$i]->unit }}</td>
                                            <td style="border-bottom:1px solid #333">Total</td>
                                            <td style="width:20%;border-bottom:1px solid #333"></td>
                                        @endforelse
                                    </tr>
                                @endfor
                                <tr>
                                    <td colspan="5" style="width: 85%">Total amount</td>
                                    <td style="width: 15%;border-top:1px solid #333">{{ $data->client_sum ? 'NT$' . ($data->client_sum->sum + 0) : '' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <hr>
            <div class="block">
                <table class="table simsun borderless">
                    <tr style="font-size:17px">
                        <td style="width:15%">Type: <b>{{ $data->client->route . $data->client->route_number }}</b></td>
                        <td>Client: <b>{{ $data->client->name }}</b></td>
                        <td class="text-right">Month: <b>{{ $current_month }}</b></td>
                    </tr>
                </table>
                <table class="table text-center m-t-5">
                    <tbody>
                        <tr class="bold">
                            <th></th>
                            @for ($i = 1; $i < 16; $i++)
                                <th>{{ $i }}</th>
                            @endfor
                        </tr>
                        @for ($i = 0; $i < count($products); $i++)
                            <tr>
                                <td class="bold simsun l-col bold">{{ $products[$i]->name }}</td>
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
                            <td class="simsun l-col bold" style="border-top: 2px solid #b5b5b5">日總計</td>
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
                <table class="table text-center m-t-10">
                    <tbody>
                        <tr class="bold">
                            <th></th>
                            @for ($i = 16; $i < 32; $i++)
                                <th>{{ $i }}</th>
                            @endfor
                        </tr>
                        @for ($i = 0; $i < count($products); $i++)
                            <tr>
                                <td class="bold simsun l-col bold">{{ $products[$i]->name }}</td>
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
                            <td class="simsun l-col bold" style="border-top: 2px solid #b5b5b5">日總計</td>
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
                <table class="table borderless m-t-10">
                    <tr>
                        <td width="55%"><h2 class="simsun text-center">Receipt</h2></td>
                        <td>
                            <table class="table simsun borderless text-right">
                                @for ($i = 0; $i < count($products); $i++)
                                    <tr>
                                        <td style="width:30%;border-bottom:1px solid #333">{{ $products[$i]->name }} (Total quantity)</td>
                                        @forelse ($data->monthly_sums as $item)
                                            @if ($item->name == $products[$i]->name)
                                            <td style="border-bottom:1px solid #333">{{ $item->total_quantity }}</td>
                                            <td style="border-bottom:1px solid #333">Price</td>
                                            <td style="width:15%;border-bottom:1px solid #333">${{ $item->price + 0 }}/{{ $item->unit }}</td>
                                            <td style="border-bottom:1px solid #333">Total</td>
                                            <td style="width:20%;border-bottom:1px solid #333">${{ $item->sum + 0 }}</td>
                                            @endif
                                        @empty
                                            <td style="border-bottom:1px solid #333"></td>
                                            <td style="border-bottom:1px solid #333">Price</td>
                                            <td style="width:15%;border-bottom:1px solid #333">/{{ $products[$i]->unit }}</td>
                                            <td style="border-bottom:1px solid #333">Total</td>
                                            <td style="width:20%;border-bottom:1px solid #333"></td>
                                        @endforelse
                                    </tr>
                                @endfor
                                <tr>
                                    <td colspan="5" style="width: 85%">Total amount</td>
                                    <td style="width: 15%;border-top:1px solid #333">{{ $data->client_sum ? 'NT$' . ($data->client_sum->sum + 0) : '' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            @if ( $key + 1 < count($clients_arr) )
            <div class="page-break"></div>
            @endif
        @endforeach
    </body>
</html>