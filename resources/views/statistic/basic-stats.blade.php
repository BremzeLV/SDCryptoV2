@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Basic market statistic</div>

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
                <tr class="pointer" onclick="window.location='{{ URL::to('/statistic/BTC_BNC') }}'">
                    <td>LTC</td>
                    <td>Doe</td>
                    <td>john@example.com</td>
                    <td>john@example.com</td>
                </tr>
                <tr onclick="window.location='{{ URL::to('/statistic/1') }}'">
                    <td>ETH</td>
                    <td>Moe</td>
                    <td>mary@example.com</td>
                    <td>mary@example.com</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
