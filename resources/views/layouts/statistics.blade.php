@extends('layouts.dashboard')

@section('title', 'Thống kê bán hàng')
<link rel="stylesheet" href="/css/charts.css">
@section('content')
    <script src="{{ \ArielMejiaDev\LarapexCharts\LarapexChart::cdn() }}"></script>

    <style>
        /* Style riêng cho Larapex chart titles */
        

        
    </style>
    <div class="dashboard-chart__container">
        {{-- Hàng 1: Doanh thu --}}
        <div class="dashboard-chart__row">
            <div class="dashboard-chart__card">
                {!! $revenueChart->container() !!}
            </div>
        </div>

        {{-- Hàng 2: Top sản phẩm + Danh mục --}}
        <div class="dashboard-chart__row dashboard-chart__row--two">
            <div class="dashboard-chart__card">
                {!! $topProductChart->container() !!}
            </div>
            <div class="dashboard-chart__card">
                {!! $categoryChart->container() !!}
            </div>
        </div>

        {{-- Hàng 3: Người dùng + Đơn hàng + Thiết bị --}}
        <div class="dashboard-chart__row dashboard-chart__row--three">
            <div class="dashboard-chart__card">
                {!! $userGrowthChart->container() !!}
            </div>
            <div class="dashboard-chart__card">
                {!! $orderStatusChart->container() !!}
            </div>
            <div class="dashboard-chart__card">
                {!! $revenueByPaymentMethodChart->container() !!}
            </div>
        </div>
    </div>

    {{-- Script render chart --}}
    {!! $revenueChart->script() !!}
    {!! $topProductChart->script() !!}
    {!! $categoryChart->script() !!}
    {!! $userGrowthChart->script() !!}
    {!! $orderStatusChart->script() !!}
    {!! $revenueByPaymentMethodChart->script() !!}
@endsection