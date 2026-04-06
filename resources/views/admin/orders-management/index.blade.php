@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div x-data="{
        tab: '{{ request('tab', session('tab', 'orders')) }}',
        role: '{{ request('role', session('role', 'admin')) }}',
        isNewUser: 'false',
        updateUrl(key, value, reload = false) {
            let url = new URL(window.location.href);
            url.searchParams.set(key, value);
            if (reload) {
                window.location.href = url.toString();
            } else {
                window.history.pushState({}, '', url);
            }
        }
    }" class="d-flex flex-column" style="height: 80vh; overflow: hidden;">
        {{-- header --}}
        <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h3 mb-0 text-gray-800 fw-bold">Quản lý Đơn hàng</h1>
                    <p class="mb-0 text-muted small">
                        <i class="fas fa-circle text-success me-1" style="font-size: 10px;"></i>
                        Hệ thống quản trị kinh doanh & dịch vụ
                    </p>
                </div>
            </div>


            <div class="mb-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card border-0 bg-dark text-white p-3 rounded-4 shadow-sm">
                            <small class="text-uppercase opacity-75 fw-bold small">Giá trị hợp đồng</small>
                            <h4 class="mb-0 mt-1 fw-bold">{{ number_format($stats->total_amount ?? 0) }}đ</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-white p-3 rounded-4 shadow-sm">
                            <small class="text-uppercase text-muted fw-bold small">Thực thu tiền mặt</small>
                            <h4 class="mb-0 mt-1 fw-bold text-success">{{ number_format($stats->total_collected ?? 0) }}đ
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 text-white p-3 rounded-4 shadow-sm" style="background-color: #fd7e14;">
                            <small class="text-uppercase opacity-75 fw-bold small">Hoa hồng nguồn</small>
                            <h4 class="mb-0 mt-1 fw-bold">{{ number_format($stats->total_commission ?? 0) }}đ</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-primary text-white p-3 rounded-4 shadow-sm">
                            <small class="text-uppercase opacity-75 fw-bold small">Số lượng đơn</small>
                            <h4 class="mb-0 mt-1 fw-bold">{{ $stats->total_orders ?? 0 }} Hợp đồng</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3 d-flex flex-wrap gap-4 align-items-center">
                <ul class="nav nav-pills nav-pills-tech" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link mt-2" :class="tab === 'orders' ? 'active' : ''"
                            @click="updateUrl('tab', 'orders', true)" type="button" role="tab">
                            <i class="fa-solid fa-cart-flatbed"></i> Đơn hàng
                        </button>
                    </li>
                    @can('view_services')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link mt-2" :class="tab === 'service' ? 'active' : ''"
                                @click="updateUrl('tab', 'service', true)" type="button" role="tab">
                                <i class="fas fa-box-open"></i> Gói dịch vụ
                            </button>
                        </li>
                    @endcan
                </ul>

                <ul class="nav nav-pills nav-pills-tech" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link mt-2" :class="role === 'admin' ? 'active' : ''"
                            @click="role = 'admin'; updateUrl('role', 'admin')" type="button" role="tab">
                            <i class="fas fa-user-shield"></i> Admin View
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link mt-2" :class="role === 'ctv' ? 'active' : ''"
                            @click="role = 'ctv'; updateUrl('role', 'ctv')" type="button" role="tab">
                            <i class="fas fa-users"></i> CTV View
                        </button>
                    </li>
                </ul>
            </div>


        </div>

        {{-- tabs --}}
        <div class="flex-grow-1 my-2" style="overflow-y: auto;">
            <div x-show="tab === 'orders'" x-transition:enter.duration.200ms>
                @include('admin.orders-management.tabs.orders')
            </div>

            <div x-show="tab === 'service'" x-transition:enter.duration.200ms>
                @include('admin.orders-management.tabs.service')
            </div>
        </div>
    </div>

    <style>
        .transition {
            transition: all 0.2s ease;
        }

        .rounded-4 {
            border-radius: 12px !important;
        }

        /* Ẩn thanh scroll mặc định của trình duyệt để nhìn đẹp hơn (Tùy chọn) */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
    </style>
@endsection
