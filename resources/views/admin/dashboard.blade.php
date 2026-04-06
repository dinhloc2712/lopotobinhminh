@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    /* Add Dashboard styles */
    .dashboard-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
        height: 100%;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .icon-shape {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .bg-light-primary { background-color: #f0f9ff; color: #3b82f6; } 
    .bg-light-danger { background-color: #fef2f2; color: #ef4444; } 
    .bg-light-purple { background-color: #faf5ff; color: #a855f7; } 
    .bg-light-warning { background-color: #fff7ed; color: #f97316; } 
    
    .text-success-custom { color: #10b981; font-weight: 500; font-size: 0.8rem;}
    .text-danger-custom { color: #ef4444; font-weight: 500; font-size: 0.8rem;}
    .text-muted-custom { color: #6b7280; font-size: 0.875rem; }
    
    .card-title-custom {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }
    .card-header-custom {
        border-bottom: 1px solid #f3f4f6;
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header-title {
        font-weight: 600;
        color: #1f2937;
        margin: 0;
        font-size: 0.95rem;
    }
    .header-btn {
        background-color: #fff;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        transition: all 0.2s;
        font-size: 0.875rem;
    }
    .header-btn:hover {
        background-color: #f9fafb;
    }
    .header-btn-primary {
        background-color: #3b82f6;
        border: 1px solid #3b82f6;
        color: #fff;
    }
    .header-btn-primary:hover {
        background-color: #2563eb;
        color: #fff;
    }
    
    /* Animation delays */
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }
    .delay-6 { animation-delay: 0.6s; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
    <div>
        <h1 class="h4 mb-1 text-gray-800 fw-bold">Hệ thống Quản lý Nội bộ</h1>
        <p class="text-muted-custom mb-0">Cập nhật dữ liệu thời gian thực • {{ date('d/m/Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        <button class="header-btn shadow-sm">
            <i class="far fa-calendar-alt me-1"></i> Tháng 2, 2026
        </button>
        <button class="header-btn header-btn-primary shadow-sm">
            <i class="fas fa-chart-line me-1"></i> Xuất báo cáo
        </button>
    </div>
</div>


<!-- Bottom Row -->
<div class="row g-3 mb-4">
    <!-- Donut Chart -->
    <div class="col-lg-12 animate__animated animate__fadeInUp delay-6">
        <div class="dashboard-card">
            <div class="card-header-custom border-0 pb-0">
                <h5 class="card-header-title">Cơ cấu Nhân sự</h5>
            </div>
            <div class="card-body p-4">
                <div id="userStructureChart" style="height: 220px; display: flex; justify-content: center; align-items: center;"></div>
                
                <div class="mt-4">
                    @php $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899']; @endphp
                    @foreach($userStructureDetails as $index => $detail)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background-color:{{ $colors[$index % count($colors)] }}; margin-right:8px;"></span> 
                            <span class="text-sm text-gray-700" style="font-size:0.875rem;">{{ $detail['name'] }}</span>
                        </div>
                        <span class="fw-bold" style="font-size:0.875rem;">{{ $detail['percent'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // User Structure Donut Chart
        var userOptions = {
            series: {!! json_encode($userStructure['series']) !!},
            labels: {!! json_encode($userStructure['labels']) !!},
            chart: {
                type: 'donut',
                height: 220,
                fontFamily: 'Inter, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 300
                    }
                }
            },
            colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6', '#f43f5e', '#6366f1'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: { show: true, fontSize: '12px', color: '#6b7280', offsetY: 20 },
                            value: { show: true, fontSize: '24px', fontWeight: 700, color: '#1f2937', offsetY: -10 },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Nhân sự',
                                fontSize: '14px',
                                color: '#6b7280',
                                formatter: function (w) {
                                    return "{{ $totalUserStructure }}"
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ['#fff'] },
            legend: { show: false },
            tooltip: { theme: 'light' }
        };
        var userChart = new ApexCharts(document.querySelector("#userStructureChart"), userOptions);
        userChart.render();
        

    });
</script>
@endsection
