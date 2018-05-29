@extends('layouts.app')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @include('currency.statistic.dashboard-stats')

    @include('currency.statistic.snapshot-stats')
@endsection
