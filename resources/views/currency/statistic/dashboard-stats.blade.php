<div class="card">
    <div class="card-header">Your currency steps</div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Currency</th>
                    <th>Price bought</th>
                    <th>Percentage bought</th>
                    <th>Amount</th>
                    <th>Predicted sell</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userDash as $item)
                    <tr>
                        <td>{{ $item->currency_index }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->percentage_step }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>{{ $item->predicted_sell }}</td>
                    </tr>
                @empty
                    <tr class="alert alert-danger">
                        <td colspan="12">
                            No results
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>