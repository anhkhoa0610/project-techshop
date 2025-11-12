@extends('layouts.dashboard')

@section('title', 'Doanh số')

@section('content')
    <script src="{{ \ArielMejiaDev\LarapexCharts\LarapexChart::cdn() }}"></script>
    <h1>{{ $title }}</h1>
    {!! $lineChart->container() !!}
    {!! $lineChart->script() !!}
@endsection