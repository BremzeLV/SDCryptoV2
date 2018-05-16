<div class="card">
    <div class="card-header">Currency snapshot</div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Currency</th>
                    <th>Last price</th>
                    <th>Lowest ask</th>
                    <th>Highest bid</th>
                    <th>Percent change</th>
                    <th>Day high</th>
                    <th>Day low</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($snapshot as $pair)
                    <tr onclick="window.location='{{ URL::to('/statistic/'.$pair->pair) }}'" class="{{  $pair->percent_change > 0 ? 'green' : 'red' }}">
                        <td>
                            {{ $pair->pair }}
                        </td>
                        <td>{{ sprintf('%f', $pair->last) }}</td>
                        <td>{{ sprintf('%f', $pair->lowest_ask) }}</td>
                        <td>{{ sprintf('%f', $pair->highest_bid) }}</td>
                        <td>{{ sprintf('%f', $pair->percent_change) }}</td>
                        <td>{{ sprintf('%f', $pair->day_high) }}</td>
                        <td>{{ sprintf('%f', $pair->day_low) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12">
                            <span class="alert alert-danger">
                                No results
                            </span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>