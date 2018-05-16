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