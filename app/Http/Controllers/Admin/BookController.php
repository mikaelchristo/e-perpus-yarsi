<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with('category')
            ->physicals()
            ->search($request->search)
            ->when($request->year, fn($q, $y) => $q->where('year', $y))
            ->latest()
            ->paginate(15);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $category = Category::where('slug', 'physical')->firstOrFail();

        return view('admin.books.create', compact('category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:books,code',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string|max:5000',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stock' => 'required|integer|min:0',
        ]);

        $category = Category::where('slug', 'physical')->firstOrFail();
        $validated['category_id'] = $category->id;

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku fisik berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'loans' => fn($q) => $q->latest()->take(10)]);

        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:books,code,' . $book->id,
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string|max:5000',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old cover
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku fisik berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->loans()->where('status', 'borrowed')->exists()) {
            return redirect()->route('admin.books.index')
                ->with('error', 'Buku tidak dapat dihapus karena masih ada peminjaman aktif.');
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku fisik berhasil dihapus.');
    }
}
