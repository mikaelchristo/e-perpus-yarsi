@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-gray-900">Dashboard</li>
</ul>
@endsection

@section('content')
<!--begin::Row - Summary Cards-->
<div class="row g-5 g-xl-8 mb-5">
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.books.index') }}" class="card bg-body hoverable card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-book fs-2x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-900">{{ $totalBooks }}</div>
                    <div class="fw-semibold text-gray-500 fs-7">Total Buku</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.books.index') }}" class="card bg-body hoverable card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <span class="symbol-label bg-light-info">
                        <i class="ki-duotone ki-book-open fs-2x text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-900">{{ $totalPhysical }}</div>
                    <div class="fw-semibold text-gray-500 fs-7">Buku Fisik</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.ebooks.index') }}" class="card bg-body hoverable card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-duotone ki-tablet-book fs-2x text-success"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-900">{{ $totalEbooks }}</div>
                    <div class="fw-semibold text-gray-500 fs-7">E-Book</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.loans.index') }}" class="card bg-body hoverable card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <span class="symbol-label bg-light-warning">
                        <i class="ki-duotone ki-handcart fs-2x text-warning"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-900">{{ $booksBorrowed }}</div>
                    <div class="fw-semibold text-gray-500 fs-7">Sedang Dipinjam</div>
                </div>
            </div>
        </a>
    </div>
</div>
<!--end::Row-->

<!--begin::Row - Secondary Stats-->
<div class="row g-5 g-xl-8 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="card card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <span class="symbol-label bg-light-danger">
                        <i class="ki-duotone ki-timer fs-2x text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold {{ $lateReturns > 0 ? 'text-danger' : 'text-gray-900' }}">{{ $lateReturns }}</div>
                    <div class="fw-semibold text-gray-500 fs-7">Terlambat</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-eye fs-2x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-900">{{ $totalEbookReads }}</div>
                    <div class="fw-semibold text-gray-500 fs-7">Pembaca E-Book</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-duotone ki-check-circle fs-2x text-success"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-900">{{ $totalReturned }}</div>
                    <div class="fw-semibold text-gray-500 fs-7">Dikembalikan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.users.index') }}" class="card bg-body hoverable card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <span class="symbol-label bg-light-dark">
                        <i class="ki-duotone ki-people fs-2x text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-900">{{ $totalUsers }}</div>
                    <div class="fw-semibold text-gray-500 fs-7">Total User</div>
                </div>
            </div>
        </a>
    </div>
</div>
<!--end::Row-->

<!--begin::Row - Charts Row 1-->
<div class="row g-5 g-xl-8 mb-5">
    <!--begin::Loan Trend Chart-->
    <div class="col-xl-8">
        <div class="card card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Tren Peminjaman</span>
                    <span class="text-muted fw-semibold fs-7">12 bulan terakhir</span>
                </h3>
                <div class="card-toolbar">
                    <span class="badge badge-light-primary fs-7 fw-semibold">{{ $totalLoans }} Total</span>
                </div>
            </div>
            <div class="card-body">
                <div id="chart_loan_trend" style="height: 350px;"></div>
            </div>
        </div>
    </div>
    <!--end::Loan Trend Chart-->

    <!--begin::Category Distribution-->
    <div class="col-xl-4">
        <div class="card card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Distribusi Kategori</span>
                    <span class="text-muted fw-semibold fs-7">Jumlah buku per kategori</span>
                </h3>
            </div>
            <div class="card-body d-flex flex-column align-items-center">
                <div id="chart_category_pie" style="height: 250px; width: 100%;"></div>
                <div class="d-flex flex-wrap justify-content-center gap-4 mt-4">
                    @foreach($categoryDistribution as $cat)
                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-dot me-2 bg-{{ $loop->index === 0 ? 'primary' : ($loop->index === 1 ? 'success' : 'info') }} h-10px w-10px"></span>
                        <span class="text-gray-700 fw-semibold fs-7">{{ $cat['name'] }}: {{ $cat['total'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!--end::Category Distribution-->
</div>
<!--end::Row-->

<!--begin::Row - Charts Row 2-->
<div class="row g-5 g-xl-8 mb-5">
    <!--begin::Ebook Read Trend-->
    <div class="col-xl-8">
        <div class="card card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Trafik Pembaca E-Book</span>
                    <span class="text-muted fw-semibold fs-7">30 hari terakhir</span>
                </h3>
                <div class="card-toolbar">
                    <span class="badge badge-light-success fs-7 fw-semibold">{{ $totalEbookReads }} Pembaca</span>
                </div>
            </div>
            <div class="card-body">
                <div id="chart_ebook_traffic" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <!--end::Ebook Read Trend-->

    <!--begin::Weekly Activity-->
    <div class="col-xl-4">
        <div class="card card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Aktivitas Mingguan</span>
                    <span class="text-muted fw-semibold fs-7">7 hari terakhir</span>
                </h3>
            </div>
            <div class="card-body">
                <div id="chart_weekly_activity" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <!--end::Weekly Activity-->
</div>
<!--end::Row-->

<!--begin::Row - Top lists & Recent-->
<div class="row g-5 g-xl-8 mb-5">
    <!--begin::Top Borrowed Books-->
    <div class="col-xl-6">
        <div class="card card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Buku Terpopuler</span>
                    <span class="text-muted fw-semibold fs-7">Paling banyak dipinjam</span>
                </h3>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="w-25px">#</th>
                                <th class="min-w-200px">Buku</th>
                                <th class="min-w-80px text-end">Dipinjam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topBorrowedBooks as $book)
                            <tr>
                                <td class="text-gray-600 fw-semibold">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-4">
                                            @if($book->cover_image)
                                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="" class="symbol-label" style="object-fit: cover;"/>
                                            @else
                                                <div class="symbol-label bg-light-primary text-primary fs-6 fw-bold">
                                                    {{ strtoupper(substr($book->title, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-gray-800 fw-bold d-block fs-6">{{ Str::limit($book->title, 35) }}</span>
                                            <span class="text-muted fw-semibold d-block fs-7">{{ $book->author }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge badge-light-primary fs-7 fw-bold">{{ $book->loans_count }}x</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-8">Belum ada data peminjaman</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Top Borrowed Books-->

    <!--begin::Top Read Ebooks-->
    <div class="col-xl-6">
        <div class="card card-xl-stretch mb-xl-8 shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">E-Book Terpopuler</span>
                    <span class="text-muted fw-semibold fs-7">Paling banyak dibaca</span>
                </h3>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="w-25px">#</th>
                                <th class="min-w-200px">E-Book</th>
                                <th class="min-w-80px text-end">Dibaca</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topReadEbooks as $book)
                            <tr>
                                <td class="text-gray-600 fw-semibold">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-4">
                                            @if($book->cover_image)
                                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="" class="symbol-label" style="object-fit: cover;"/>
                                            @else
                                                <div class="symbol-label bg-light-success text-success fs-6 fw-bold">
                                                    {{ strtoupper(substr($book->title, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-gray-800 fw-bold d-block fs-6">{{ Str::limit($book->title, 35) }}</span>
                                            <span class="text-muted fw-semibold d-block fs-7">{{ $book->author }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge badge-light-success fs-7 fw-bold">{{ $book->ebook_reads_count }}x</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-8">Belum ada data pembacaan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Top Read Ebooks-->
</div>
<!--end::Row-->

<!--begin::Row - Recent Tables-->
<div class="row g-5 g-xl-8">
    <!--begin::Recent Loans-->
    <div class="col-xl-7">
        <div class="card card-xl-stretch mb-5 mb-xl-8 shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Peminjaman Terbaru</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">{{ $booksBorrowed }} buku sedang dipinjam</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-sm btn-light-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="min-w-150px">Peminjam</th>
                                <th class="min-w-150px">Buku</th>
                                <th class="min-w-100px">Jatuh Tempo</th>
                                <th class="min-w-80px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLoans as $loan)
                            <tr>
                                <td>
                                    <span class="text-gray-900 fw-bold d-block fs-6">{{ $loan->borrower_name }}</span>
                                    <span class="text-muted fw-semibold d-block fs-7">{{ $loan->borrower_phone }}</span>
                                </td>
                                <td><span class="text-gray-800 fw-semibold">{{ Str::limit($loan->book->title ?? '-', 30) }}</span></td>
                                <td><span class="text-muted fw-semibold">{{ $loan->due_date->format('d M Y') }}</span></td>
                                <td>{!! $loan->status_badge !!}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-10">Belum ada data peminjaman</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Recent Loans-->

    <!--begin::Recent Users-->
    <div class="col-xl-5">
        <div class="card card-xl-stretch mb-5 mb-xl-8 shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">User Terbaru</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">{{ $totalUsers }} user terdaftar</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-light-primary">
                        <i class="ki-duotone ki-plus fs-2"></i> Tambah
                    </a>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="min-w-200px">User</th>
                                <th class="min-w-80px">Role</th>
                                <th class="min-w-100px">Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-4">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="symbol-label"/>
                                            @else
                                                <div class="symbol-label fs-5 fw-semibold bg-light-primary text-primary">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-gray-900 fw-bold fs-6">{{ $user->name }}</span>
                                            <span class="text-muted fw-semibold d-block fs-7">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light-{{ $user->role === 'admin' ? 'danger' : 'primary' }} fs-7 fw-semibold">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold fs-7">{{ $user->created_at->format('d M Y') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-10">Belum ada user</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Recent Users-->
</div>
<!--end::Row-->
@endsection

@push('custom-js')
<script>
"use strict";

// ── Color helper ──
var cssVar = function(name) {
    return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
};

// ══════════════════════════════════════════════════════════════
// 1. Loan Trend - Area Chart (12 months)
// ══════════════════════════════════════════════════════════════
var loanTrendEl = document.getElementById('chart_loan_trend');
if (loanTrendEl) {
    var loanTrendOptions = {
        series: [{
            name: 'Total Pinjam',
            data: {!! json_encode($loanTrendData->pluck('total')) !!}
        }, {
            name: 'Dikembalikan',
            data: {!! json_encode($loanTrendData->pluck('returned')) !!}
        }, {
            name: 'Terlambat',
            data: {!! json_encode($loanTrendData->pluck('late')) !!}
        }],
        chart: {
            type: 'area',
            height: 350,
            fontFamily: 'inherit',
            toolbar: { show: false },
        },
        colors: ['#3E97FF', '#50CD89', '#F1416C'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: {
            categories: {!! json_encode($loanTrendData->pluck('month')) !!},
            labels: {
                style: { colors: '#A1A5B7', fontSize: '11px' }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: { colors: '#A1A5B7', fontSize: '11px' },
                formatter: function(val) { return Math.round(val); }
            }
        },
        grid: {
            borderColor: '#F1F1F4',
            strokeDashArray: 4
        },
        tooltip: {
            style: { fontSize: '12px' },
            y: { formatter: function(val) { return val + ' peminjaman'; } }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '12px',
            fontWeight: 500,
            labels: { colors: '#A1A5B7' },
            markers: { width: 10, height: 10, radius: 10 }
        }
    };
    new ApexCharts(loanTrendEl, loanTrendOptions).render();
}

// ══════════════════════════════════════════════════════════════
// 2. Category Distribution - Donut Chart
// ══════════════════════════════════════════════════════════════
var categoryPieEl = document.getElementById('chart_category_pie');
if (categoryPieEl) {
    var catData = {!! json_encode($categoryDistribution->values()) !!};
    var categoryPieOptions = {
        series: catData.map(function(c) { return c.total; }),
        chart: {
            type: 'donut',
            height: 250,
            fontFamily: 'inherit',
        },
        labels: catData.map(function(c) { return c.name; }),
        colors: ['#3E97FF', '#50CD89', '#FFC700', '#F1416C', '#7239EA'],
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        name: { show: true, fontSize: '13px', fontWeight: 600 },
                        value: {
                            show: true,
                            fontSize: '18px',
                            fontWeight: 700,
                            formatter: function(val) { return val + ' buku'; }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '13px',
                            fontWeight: 600,
                            formatter: function(w) {
                                return w.globals.seriesTotals.reduce(function(a, b) { return a + b; }, 0) + ' buku';
                            }
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        legend: { show: false },
        stroke: { width: 0 },
    };
    new ApexCharts(categoryPieEl, categoryPieOptions).render();
}

// ══════════════════════════════════════════════════════════════
// 3. Ebook Read Traffic - Line Chart (30 days)
// ══════════════════════════════════════════════════════════════
var ebookTrafficEl = document.getElementById('chart_ebook_traffic');
if (ebookTrafficEl) {
    var ebookTrafficOptions = {
        series: [{
            name: 'Pembaca',
            data: {!! json_encode($ebookReadData->pluck('total')) !!}
        }],
        chart: {
            type: 'area',
            height: 300,
            fontFamily: 'inherit',
            toolbar: { show: false },
            sparkline: { enabled: false }
        },
        colors: ['#50CD89'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.5,
                opacityTo: 0.05,
                stops: [0, 80, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        xaxis: {
            categories: {!! json_encode($ebookReadData->pluck('date')) !!},
            labels: {
                show: true,
                rotate: -45,
                rotateAlways: false,
                hideOverlappingLabels: true,
                style: { colors: '#A1A5B7', fontSize: '10px' }
            },
            axisBorder: { show: false },
            axisTicks: { show: false },
            tickAmount: 10,
        },
        yaxis: {
            labels: {
                style: { colors: '#A1A5B7', fontSize: '11px' },
                formatter: function(val) { return Math.round(val); }
            }
        },
        grid: {
            borderColor: '#F1F1F4',
            strokeDashArray: 4
        },
        tooltip: {
            style: { fontSize: '12px' },
            y: { formatter: function(val) { return val + ' pembaca'; } }
        },
        markers: {
            size: 0,
            hover: { size: 5 }
        }
    };
    new ApexCharts(ebookTrafficEl, ebookTrafficOptions).render();
}

// ══════════════════════════════════════════════════════════════
// 4. Weekly Activity - Bar Chart
// ══════════════════════════════════════════════════════════════
var weeklyEl = document.getElementById('chart_weekly_activity');
if (weeklyEl) {
    var weeklyOptions = {
        series: [{
            name: 'Peminjaman',
            data: {!! json_encode($weeklyActivity->pluck('loans')) !!}
        }, {
            name: 'Baca E-Book',
            data: {!! json_encode($weeklyActivity->pluck('reads')) !!}
        }],
        chart: {
            type: 'bar',
            height: 300,
            fontFamily: 'inherit',
            toolbar: { show: false },
            stacked: false,
        },
        colors: ['#3E97FF', '#50CD89'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 4,
                borderRadiusApplication: 'end'
            }
        },
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: {
            categories: {!! json_encode($weeklyActivity->pluck('day')) !!},
            labels: {
                style: { colors: '#A1A5B7', fontSize: '11px' }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: { colors: '#A1A5B7', fontSize: '11px' },
                formatter: function(val) { return Math.round(val); }
            }
        },
        grid: {
            borderColor: '#F1F1F4',
            strokeDashArray: 4
        },
        tooltip: {
            style: { fontSize: '12px' },
            y: { formatter: function(val) { return val + ' aktivitas'; } }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            fontSize: '12px',
            fontWeight: 500,
            labels: { colors: '#A1A5B7' },
            markers: { width: 10, height: 10, radius: 10 }
        }
    };
    new ApexCharts(weeklyEl, weeklyOptions).render();
}
</script>
@endpush
