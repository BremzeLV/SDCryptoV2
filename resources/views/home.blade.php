@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Your currency balance</div>

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Currency</th>
                    <th>Current</th>
                    <th>Open</th>
                    <th>Close</th>
                </tr>
                </thead>
                <tbody>
                <tr onclick="window.location='{{ URL::to('/statistic/eth') }}'">
                    <td>ETH</td>
                    <td>Moe</td>
                    <td>mary@example.com</td>
                    <td>mary@example.com</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Dashboard</div>

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Currency</th>
                    <th>Last price</th>
                    <th>Lowest ask</th>
                    <th>Highest bid</th>
                    <th>Closed</th>
                </tr>
                </thead>
                <tbody>
                <tr class="pointer" onclick="window.location='{{ URL::to('/statistic/ltc') }}'">
                    <td>LTC</td>
                    <td>LTC</td>
                    <td>LTC</td>
                    <td>LTC</td>
                    {{--<td>{{ $ltc->last }}</td>
                    <td>{{ $ltc->lowest_ask }}</td>
                    <td>{{ $ltc->highest_bid }}</td>
                    <td>{{ $ltc->is_frozen }}</td>--}}
                </tr>
                <tr onclick="window.location='{{ URL::to('/statistic/eth') }}'">
                    <td>ETH</td>
                    <td>ETH</td>
                    <td>ETH</td>
                    <td>ETH</td>
                   {{-- <td>{{ $eth->last }}</td>
                    <td>{{ $eth->lowest_ask }}</td>
                    <td>{{ $eth->highest_bid }}</td>
                    <td>{{ $eth->is_frozen }}</td>--}}
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
