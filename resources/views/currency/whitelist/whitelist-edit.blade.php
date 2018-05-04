@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Edit whitelist currencies</div>

        <div class="card-body">
            @forelse ($currency as $item)

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xs-6">
                            {{ $item->currency_index }}
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-6">
                            {{ $item->listed }}
                        </div>
                    </div>

            @empty
                <div class="col-lg-12">
                    No currencies available!
                </div>
            @endforelse
        </div>
    </div>
@endsection
