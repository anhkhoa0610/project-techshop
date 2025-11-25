@extends('layouts.dashboard')

@section('title', 'Thống kê bán hàng')
<link rel="stylesheet" href="/css/charts.css">
@section('content')
<script src="{{ \ArielMejiaDev\LarapexCharts\LarapexChart::cdn() }}"></script>

<style>
    /* Style riêng cho Larapex chart titles */
</style>
<div class="dashboard-chart__container">
    {{-- Statistics Info Boxes --}}
    <div class="stats-info-boxes">
        {{-- User mới tạo gần nhất --}}
        <div class="stats-info-box">
            <div class="stats-info-box__icon">👤</div>
            <div class="stats-info-box__label">User mới nhất</div>
            <div class="stats-info-box__value">{{ $newestUser ? $newestUser->full_name : 'N/A' }}</div>
            <div class="stats-info-box__subtitle">
                {{ $newestUser ? 'Tạo lúc: ' . $newestUser->created_at->format('d/m/Y H:i') : 'Chưa có dữ liệu' }}
            </div>
        </div>

        {{-- User mua hàng nhiều nhất --}}
        <div class="stats-info-box">
            <div class="stats-info-box__icon">🏆</div>
            <div class="stats-info-box__label">Khách hàng VIP</div>
            <div class="stats-info-box__value">{{ $topBuyer ? $topBuyer->full_name : 'N/A' }}</div>
            <div class="stats-info-box__subtitle">
                {{ $topBuyer ? 'Tổng chi: ' . number_format($topBuyer->total_spent, 0, ',', '.') . ' ₫' : 'Chưa có dữ liệu' }}
            </div>
        </div>

        {{-- Sản phẩm bán được gần nhất --}}
        <div class="stats-info-box">
            <div class="stats-info-box__icon">📦</div>
            <div class="stats-info-box__label">Sản phẩm bán gần đây</div>
            <div class="stats-info-box__value">{{ $recentlySoldProduct ? Str::limit($recentlySoldProduct->product_name, 25) : 'N/A' }}</div>
            <div class="stats-info-box__subtitle">
                {{ $recentlySoldProduct ? 'Bán lúc: ' . \Carbon\Carbon::parse($recentlySoldProduct->order_date)->format('d/m/Y H:i') : 'Chưa có dữ liệu' }}
            </div>
        </div>

        {{-- Doanh thu trong tuần --}}
        <div class="stats-info-box">
            <div class="stats-info-box__icon">💰</div>
            <div class="stats-info-box__label">Doanh thu tuần này</div>
            <div class="stats-info-box__value">{{ number_format($weeklyRevenue, 0, ',', '.') }} ₫</div>
            <div class="stats-info-box__subtitle">7 ngày gần nhất</div>
        </div>
    </div>

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