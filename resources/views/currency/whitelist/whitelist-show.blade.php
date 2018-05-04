@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">My selected currencies</div>

        <div class="card-body">
            <div class="btn-toolbar">
                <a class="btn btn-success" href="{{ URL::to('currency-whitelist/create') }}">Create</a>
            </div>

            <br />

            @if(session('success'))
                <div class="col-lg-12">
                    <div class="alert alert-success">{{session('success')}}</div>
                </div>
            @endif

            @if(session('errors'))
                <div class="col-lg-12">
                    <div class="alert alert-danger">{{session('errors')}}</div>
                </div>
            @endif

            @forelse ($currency as $item)
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-xs-4">
                        {{ $item->currency_index }}
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-4">
                        {{ $item->listed }}
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-4">
                        <div class="btn-group btn-toolbar">
                            @if ($item->listed)
                                {{ Form::open(
                                    array(
                                        'url' => 'currency-whitelist/'.$item->id,
                                        'action' => 'CurrencyWhitelistController@update',
                                        'method' => 'PUT'
                                    )
                                )}}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger">Disable</button>
                                {{ Form::close() }}

                            @else
                                {{ Form::open(
                                     array(
                                        'url' => 'currency-whitelist/'.$item->id,
                                         'action' => 'CurrencyWhitelistController@update',
                                         'method' => 'PUT'
                                     )
                                )}}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-success">Enable</button>
                                {{ Form::close() }}
                            @endif

                            {{ Form::open(array(
                                'url' => 'currency-whitelist/'.$item->id,
                                'action' => 'CurrencyWhitelistController@destroy',
                                'method' => 'DELETE')
                            )}}
                            {{ csrf_field() }}
                            <button onclick="return confirm('Do you really want to delete this currency?');" type="submit" class="btn btn-default">Delete</button>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <br />
            @empty
                <div class="col-lg-12">
                    No currencies whitelisted!
                </div>
            @endforelse
        </div>
    </div>
@endsection
