@extends('layouts.app')

@section('styles')
    <script src="{{asset('vendor/highstocks/code/highstock.js')}}"></script>
    <script src="{{asset('vendor/highstocks/code/modules/exporting.js')}}"></script>
    <script src="{{asset('vendor/highstocks/code/modules/drag-panes.js')}}"></script>
    <script src="{{asset('vendor/highstocks/code/modules/export-data.js')}}"></script>

    <script type="text/javascript">
        window.onload = function() {
            var tick = [],
                data = {{ $tickData }},
                volume = [],
                dataLength = data.length,

                i = 0;

            for (i; i < dataLength; i += 1) {
                tick.push([
                    data[i][0], // the date
                    data[i][1], // Tick last price
                ]);

                volume.push([
                    data[i][0], // the date
                    data[i][2]  // base volume
                ]);
            }

            Highcharts.stockChart('container', {
                rangeSelector: {
                    selected: 1
                },

                title: {
                    text: '{{ $pair }}'
                },

                yAxis: [{
                    labels: {
                        align: 'right',
                        x: -3
                    },
                    title: {
                        text: 'Price'
                    },
                    height: '60%',
                    lineWidth: 2,
                    resize: {
                        enabled: true
                    }
                }, {
                    labels: {
                        align: 'right',
                        x: -3
                    },
                    title: {
                        text: 'Volume'
                    },
                    top: '65%',
                    height: '35%',
                    offset: 0,
                    lineWidth: 2
                }],

                plotOptions:{
                    series:{
                        turboThreshold: 0,
                    }
                },

                series: [{
                    type: 'spline',
                    name: '{{ $pair }}',
                    data: tick,
                    tooltip: {
                        valueDecimals: 9
                    }
                }, {
                    type: 'column',
                    name: 'Volume',
                    data: volume,
                    yAxis: 1,
                    tooltip: {
                        valueDecimals: 9
                    },
                }]
            });
        };
    </script>
@stop

@section('content')
    <div class="card">
        <div class="card-header">Basic market statistic</div>

        <div class="card-body">
            <div id="container" style="height: 400px; min-width: 310px"></div>
        </div>

    </div>
@endsection
