@extends('layouts.app')

@section('title', 'Manajemen E-Book')
@section('page-title', 'E-Book')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Perpustakaan</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">E-Book</li>
</ul>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <form method="GET" action="{{ route('admin.ebooks.index') }}">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-13"
                        placeholder="Cari e-book..." value="{{ request('search') }}"/>
                </form>
            </div>
        </div>
        <div class="card-toolbar d-flex gap-2">
            <a href="{{ route('admin.io.export.ebooks') }}" class="btn btn-light-success">
                <i class="ki-duotone ki-file-down fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Export
            </a>
            <button type="button" class="btn btn-light-info" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="ki-duotone ki-file-up fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Import
            </button>
            <a href="{{ route('admin.ebooks.create') }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> Tambah E-Book
            </a>
        </div>
    </div>
    <div class="card-body py-4">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">#</th>
                        <th class="min-w-80px">Cover</th>
                        <th class="min-w-200px">Judul</th>
                        <th class="min-w-125px">Kode / ISBN</th>
                        <th class="min-w-125px">Penulis</th>
                        <th class="min-w-80px">Halaman</th>
                        <th class="text-end min-w-100px">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($books as $book)
                    <tr>
                        <td>{{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}</td>
                        <td>
                            <div class="symbol symbol-50px">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="symbol-label" style="object-fit: cover;"/>
                                @else
                                    <div class="symbol-label fs-5 fw-semibold bg-light-info text-info">
                                        <i class="ki-duotone ki-tablet-book fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.ebooks.show', $book) }}" class="text-gray-800 text-hover-primary fw-bold">{{ $book->title }}</a>
                            <span class="text-muted fw-semibold d-block fs-7">{{ $book->publisher }}</span>
                        </td>
                        <td><span class="badge badge-light-info">{{ $book->code }}</span></td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->ebook->total_pages ?? '-' }} hal</td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="{{ route('admin.ebooks.show', $book) }}" class="menu-link px-3">Detail</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('admin.ebooks.edit', $book) }}" class="menu-link px-3">Edit</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('ebook.reader', $book) }}" class="menu-link px-3" target="_blank">Baca</a>
                                </div>
                                <div class="menu-item px-3">
                                    <form action="{{ route('admin.ebooks.destroy', $book) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="menu-link px-3 btn btn-link text-danger p-0 w-100 text-start border-0 bg-transparent"
                                            onclick="return confirm('Yakin ingin menghapus e-book ini?')">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-10">Belum ada e-book</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $books->withQueryString()->links() }}
    </div>
</div>

@include('partials._import-modal', [
    'title' => 'Import E-Book (Metadata)',
    'importRoute' => route('admin.io.import.ebooks'),
    'templateRoute' => route('admin.io.template', 'ebooks'),
])
@endsection
