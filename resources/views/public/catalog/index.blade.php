@extends('layouts.public')

@section('title', 'Katalog Buku')

@section('hero')
<div class="public-hero">
    <div class="container d-flex flex-column align-items-center justify-content-center py-15">
        <h1 class="text-white fw-bold fs-2x mb-3">Perpustakaan Digital</h1>
        <p class="text-white text-opacity-75 fs-5 mb-8">Jelajahi koleksi buku fisik dan e-book kami</p>
        <form method="GET" action="{{ route('catalog.index') }}" class="w-100" style="max-width: 600px;">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white border-0">
                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Cari judul, penulis, atau kode buku..."
                    value="{{ request('search') }}"/>
                <button type="submit" class="btn btn-primary px-6">Cari</button>
            </div>
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}"/>
            @endif
        </form>
    </div>
</div>
@endsection

@section('content')
<!--begin::Filters-->
<div class="d-flex align-items-center justify-content-between mb-8 flex-wrap gap-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('catalog.index', array_merge(request()->except('category', 'page'))) }}"
            class="btn btn-sm {{ !request('category') ? 'btn-primary' : 'btn-light' }}">
            Semua
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('catalog.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}"
            class="btn btn-sm {{ request('category') === $cat->slug ? 'btn-primary' : 'btn-light' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>
    <div class="text-muted fs-7">
        Menampilkan {{ $books->total() }} buku
    </div>
</div>
<!--end::Filters-->

<!--begin::Books Grid-->
<div class="row g-6">
    @forelse($books as $book)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card book-card h-100">
            <a href="{{ route('catalog.show', $book) }}">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="book-cover w-100"/>
                @else
                    <div class="book-cover-placeholder">
                        @if($book->isPhysical())
                            <i class="ki-duotone ki-book-open fs-3x text-gray-300"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        @else
                            <i class="ki-duotone ki-tablet-book fs-3x text-gray-300"><span class="path1"></span><span class="path2"></span></i>
                        @endif
                    </div>
                @endif
            </a>
            <div class="card-body p-5">
                <div class="mb-2">
                    @if($book->isPhysical())
                        <span class="badge badge-light-info badge-sm">Fisik</span>
                    @else
                        <span class="badge badge-light-primary badge-sm">E-Book</span>
                    @endif
                </div>
                <a href="{{ route('catalog.show', $book) }}" class="text-gray-900 text-hover-primary fw-bold fs-6 d-block mb-1">
                    {{ Str::limit($book->title, 40) }}
                </a>
                <span class="text-muted fs-7">{{ $book->author }}</span>

                <div class="mt-4">
                    @if($book->isPhysical())
                        @if($book->stock > 0)
                            <a href="{{ route('borrow.create', $book) }}" class="btn btn-sm btn-primary w-100">
                                <i class="ki-duotone ki-handcart fs-4"><span class="path1"></span><span class="path2"></span></i> Pinjam
                            </a>
                        @else
                            <button class="btn btn-sm btn-secondary w-100" disabled>
                                <i class="ki-duotone ki-lock fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Stok Habis
                            </button>
                        @endif
                    @else
                        @if($book->ebook)
                        <a href="{{ route('ebook.reader', $book) }}" class="btn btn-sm btn-info w-100">
                            <i class="ki-duotone ki-eye fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Baca
                        </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-20">
            <i class="ki-duotone ki-book fs-5x text-gray-300 mb-5"><span class="path1"></span><span class="path2"></span></i>
            <p class="text-muted fs-5">Belum ada buku yang tersedia.</p>
        </div>
    </div>
    @endforelse
</div>
<!--end::Books Grid-->

<!--begin::Pagination-->
<div class="mt-10">
    {{ $books->withQueryString()->links() }}
</div>
<!--end::Pagination-->
@endsection
