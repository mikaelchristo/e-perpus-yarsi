@extends('layouts.app')

@section('title', 'Manajemen Peminjaman')
@section('page-title', 'Peminjaman')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Perpustakaan</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Peminjaman</li>
</ul>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <form method="GET" action="{{ route('admin.loans.index') }}" class="d-flex align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                        <input type="text" name="search" class="form-control form-control-solid w-200px ps-13"
                            placeholder="Cari peminjam..." value="{{ request('search') }}"/>
                    </div>
                    <select name="status" class="form-select form-select-solid w-150px" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                    <input type="date" name="date_from" class="form-control form-control-solid w-150px"
                        value="{{ request('date_from') }}" placeholder="Dari tanggal" onchange="this.form.submit()"/>
                    <input type="date" name="date_to" class="form-control form-control-solid w-150px"
                        value="{{ request('date_to') }}" placeholder="Sampai tanggal" onchange="this.form.submit()"/>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('admin.loans.index') }}" class="btn btn-sm btn-light-danger">
                            <i class="ki-duotone ki-cross fs-2"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-toolbar d-flex gap-2">
            <a href="{{ route('admin.io.export.loans') }}" class="btn btn-light-success">
                <i class="ki-duotone ki-file-down fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Export
            </a>
            <button type="button" class="btn btn-light-info" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="ki-duotone ki-file-up fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Import
            </button>
        </div>
    </div>
    <div class="card-body py-4">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">#</th>
                        <th class="min-w-150px">Peminjam</th>
                        <th class="min-w-200px">Buku</th>
                        <th class="min-w-100px">Tgl Pinjam</th>
                        <th class="min-w-100px">Jatuh Tempo</th>
                        <th class="min-w-80px">Status</th>
                        <th class="text-end min-w-100px">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($loans as $loan)
                    <tr>
                        <td>{{ $loop->iteration + ($loans->currentPage() - 1) * $loans->perPage() }}</td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">{{ $loan->borrower_name }}</span>
                            <span class="text-muted fw-semibold d-block fs-7">{{ $loan->borrower_phone }}</span>
                        </td>
                        <td>
                            <span class="text-gray-800 fw-semibold">{{ Str::limit($loan->book->title ?? '-', 40) }}</span>
                            <span class="text-muted fw-semibold d-block fs-7">{{ $loan->book->code ?? '' }}</span>
                        </td>
                        <td>{{ $loan->loan_date->format('d M Y') }}</td>
                        <td>
                            <span class="{{ $loan->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                {{ $loan->due_date->format('d M Y') }}
                            </span>
                        </td>
                        <td>{!! $loan->status_badge !!}</td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="{{ route('admin.loans.show', $loan) }}" class="menu-link px-3">Detail</a>
                                </div>
                                @if($loan->status !== 'returned')
                                <div class="menu-item px-3">
                                    <form action="{{ route('admin.loans.return', $loan) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="menu-link px-3 btn btn-link text-success p-0 w-100 text-start border-0 bg-transparent"
                                            onclick="return confirm('Konfirmasi pengembalian buku?')">
                                            Tandai Dikembalikan
                                        </button>
                                    </form>
                                </div>
                                @endif
                                @if($loan->status === 'returned')
                                <div class="menu-item px-3">
                                    <form action="{{ route('admin.loans.destroy', $loan) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="menu-link px-3 btn btn-link text-danger p-0 w-100 text-start border-0 bg-transparent"
                                            onclick="return confirm('Yakin ingin menghapus data peminjaman ini?')">Hapus</button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-10">Belum ada data peminjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $loans->withQueryString()->links() }}
    </div>
</div>

@include('partials._import-modal', [
    'title' => 'Import Peminjaman',
    'importRoute' => route('admin.io.import.loans'),
    'templateRoute' => route('admin.io.template', 'loans'),
])
@endsection
