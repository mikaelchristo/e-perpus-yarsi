@extends('layouts.app')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Perpustakaan</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Kategori</li>
</ul>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <form method="GET" action="{{ route('admin.categories.index') }}">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-13"
                        placeholder="Cari kategori..." value="{{ request('search') }}"/>
                </form>
            </div>
        </div>
        <div class="card-toolbar d-flex gap-2">
            <a href="{{ route('admin.io.export.categories') }}" class="btn btn-light-success">
                <i class="ki-duotone ki-file-down fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Export
            </a>
            <button type="button" class="btn btn-light-info" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="ki-duotone ki-file-up fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Import
            </button>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> Tambah Kategori
            </a>
        </div>
    </div>
    <div class="card-body py-4">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">#</th>
                        <th class="min-w-200px">Nama</th>
                        <th class="min-w-150px">Slug</th>
                        <th class="min-w-100px">Jumlah Buku</th>
                        <th class="min-w-150px">Deskripsi</th>
                        <th class="text-end min-w-100px">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                        <td>
                            <span class="text-gray-800 fw-bold">{{ $category->name }}</span>
                        </td>
                        <td><span class="badge badge-light-primary">{{ $category->slug }}</span></td>
                        <td>{{ $category->books_count }} buku</td>
                        <td>{{ Str::limit($category->description, 50) }}</td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="menu-link px-3">Edit</a>
                                </div>
                                <div class="menu-item px-3">
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline form-delete">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="menu-link px-3 btn btn-link text-danger p-0 w-100 text-start border-0 bg-transparent"
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-10">Belum ada kategori</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $categories->withQueryString()->links() }}
    </div>
</div>

@include('partials._import-modal', [
    'title' => 'Import Kategori',
    'importRoute' => route('admin.io.import.categories'),
    'templateRoute' => route('admin.io.template', 'categories'),
])
@endsection
