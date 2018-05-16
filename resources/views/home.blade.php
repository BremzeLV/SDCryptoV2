@extends('layouts.app')

@section('content')

    @include('currency.statistic.dashboard-stats')

    @include('currency.statistic.snapshot-stats')
@endsection
