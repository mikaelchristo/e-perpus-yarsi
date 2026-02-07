<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with(['category', 'ebook'])
            ->ebooks()
            ->search($request->search)
            ->latest()
            ->paginate(15);

        return view('admin.ebooks.index', compact('books'));
    }

    public function create()
    {
        $category = Category::where('slug', 'ebook')->firstOrFail();

        return view('admin.ebooks.create', compact('category'));
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
            'pdf_file' => 'required|file|mimes:pdf|max:51200',
        ]);

        $category = Category::where('slug', 'ebook')->firstOrFail();

        // Handle cover upload
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        // Handle PDF upload (private storage)
        $pdfPath = $request->file('pdf_file')->store('ebooks', 'local');

        // Count PDF pages
        $totalPages = $this->countPdfPages($request->file('pdf_file'));

        // Create book
        $book = Book::create([
            'category_id' => $category->id,
            'code' => $validated['code'],
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'] ?? null,
            'year' => $validated['year'] ?? null,
            'description' => $validated['description'] ?? null,
            'cover_image' => $coverPath,
            'stock' => null,
        ]);

        // Create ebook record
        Ebook::create([
            'book_id' => $book->id,
            'file_path' => $pdfPath,
            'total_pages' => $totalPages,
        ]);

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'E-Book berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'ebook']);

        return view('admin.ebooks.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $book->load('ebook');

        return view('admin.ebooks.edit', compact('book'));
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
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        $book->update([
            'code' => $validated['code'],
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'] ?? null,
            'year' => $validated['year'] ?? null,
            'description' => $validated['description'] ?? null,
            'cover_image' => $validated['cover_image'] ?? $book->cover_image,
        ]);

        // Update PDF if new file uploaded
        if ($request->hasFile('pdf_file')) {
            $ebook = $book->ebook;
            if ($ebook && $ebook->file_path) {
                Storage::disk('local')->delete($ebook->file_path);
            }

            $pdfPath = $request->file('pdf_file')->store('ebooks', 'local');
            $totalPages = $this->countPdfPages($request->file('pdf_file'));

            if ($ebook) {
                $ebook->update([
                    'file_path' => $pdfPath,
                    'total_pages' => $totalPages,
                ]);
            } else {
                Ebook::create([
                    'book_id' => $book->id,
                    'file_path' => $pdfPath,
                    'total_pages' => $totalPages,
                ]);
            }
        }

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'E-Book berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $ebook = $book->ebook;

        if ($ebook && $ebook->file_path) {
            Storage::disk('local')->delete($ebook->file_path);
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'E-Book berhasil dihapus.');
    }

    /**
     * Attempt to count PDF pages.
     */
    private function countPdfPages($file): ?int
    {
        try {
            $content = file_get_contents($file->getRealPath());
            // Simple regex to count pages in PDF
            preg_match_all('/\/Type\s*\/Page[^s]/i', $content, $matches);
            $count = count($matches[0]);
            return $count > 0 ? $count : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
