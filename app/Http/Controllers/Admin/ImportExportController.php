<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Book;
use App\Models\Ebook;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportExportController extends Controller
{
    // ════════════════════════════════════════════════════════════
    // EXPORT
    // ════════════════════════════════════════════════════════════

    /**
     * Export categories to CSV.
     */
    public function exportCategories(): StreamedResponse
    {
        $filename = 'kategori_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['ID', 'Nama', 'Slug', 'Deskripsi', 'Jumlah Buku', 'Dibuat']);

            Category::withCount('books')->orderBy('id')->chunk(500, function ($categories) use ($handle) {
                foreach ($categories as $cat) {
                    fputcsv($handle, [
                        $cat->id,
                        $cat->name,
                        $cat->slug,
                        $cat->description,
                        $cat->books_count,
                        $cat->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export physical books to CSV.
     */
    public function exportBooks(): StreamedResponse
    {
        $filename = 'buku_fisik_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['ID', 'Kode/ISBN', 'Judul', 'Penulis', 'Penerbit', 'Tahun', 'Stok', 'Deskripsi', 'Dibuat']);

            Book::physicals()->orderBy('id')->chunk(500, function ($books) use ($handle) {
                foreach ($books as $book) {
                    fputcsv($handle, [
                        $book->id,
                        $book->code,
                        $book->title,
                        $book->author,
                        $book->publisher,
                        $book->year,
                        $book->stock,
                        $book->description,
                        $book->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export ebooks to CSV.
     */
    public function exportEbooks(): StreamedResponse
    {
        $filename = 'ebook_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['ID', 'Kode/ISBN', 'Judul', 'Penulis', 'Penerbit', 'Tahun', 'Halaman', 'Deskripsi', 'Dibuat']);

            Book::ebooks()->with('ebook')->orderBy('id')->chunk(500, function ($books) use ($handle) {
                foreach ($books as $book) {
                    fputcsv($handle, [
                        $book->id,
                        $book->code,
                        $book->title,
                        $book->author,
                        $book->publisher,
                        $book->year,
                        $book->ebook->total_pages ?? '',
                        $book->description,
                        $book->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export loans to CSV.
     */
    public function exportLoans(): StreamedResponse
    {
        $filename = 'peminjaman_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['ID', 'Nama Peminjam', 'No HP', 'Email', 'No Identitas', 'Buku', 'Kode Buku', 'Tgl Pinjam', 'Jatuh Tempo', 'Tgl Kembali', 'Status', 'Catatan', 'Dibuat']);

            Loan::with('book')->orderBy('id')->chunk(500, function ($loans) use ($handle) {
                foreach ($loans as $loan) {
                    fputcsv($handle, [
                        $loan->id,
                        $loan->borrower_name,
                        $loan->borrower_phone,
                        $loan->borrower_email,
                        $loan->identity_number,
                        $loan->book->title ?? '-',
                        $loan->book->code ?? '-',
                        $loan->loan_date->format('Y-m-d'),
                        $loan->due_date->format('Y-m-d'),
                        $loan->return_date ? $loan->return_date->format('Y-m-d') : '',
                        $loan->status,
                        $loan->notes,
                        $loan->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    // ════════════════════════════════════════════════════════════
    // IMPORT
    // ════════════════════════════════════════════════════════════

    /**
     * Import categories from CSV.
     */
    public function importCategories(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $path = $request->file('file')->getRealPath();
        $rows = $this->parseCsv($path);

        if (count($rows) === 0) {
            return back()->with('error', 'File CSV kosong atau format tidak valid.');
        }

        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $name = trim($row['nama'] ?? $row['name'] ?? '');
                if (empty($name)) {
                    $skipped++;
                    continue;
                }

                Category::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'name' => $name,
                        'description' => trim($row['deskripsi'] ?? $row['description'] ?? ''),
                    ]
                );
                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }

        return back()->with('success', "Berhasil import {$imported} kategori. {$skipped} dilewati.");
    }

    /**
     * Import physical books from CSV.
     */
    public function importBooks(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $request->file('file')->getRealPath();
        $rows = $this->parseCsv($path);

        if (count($rows) === 0) {
            return back()->with('error', 'File CSV kosong atau format tidak valid.');
        }

        $category = Category::where('slug', 'physical')->first();
        if (!$category) {
            return back()->with('error', 'Kategori "Buku Fisik" belum ada. Jalankan seeder terlebih dahulu.');
        }

        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $code = trim($row['kode/isbn'] ?? $row['kode'] ?? $row['isbn'] ?? $row['code'] ?? '');
                $title = trim($row['judul'] ?? $row['title'] ?? '');

                if (empty($code) || empty($title)) {
                    $skipped++;
                    continue;
                }

                // Skip if code already exists
                if (Book::where('code', $code)->exists()) {
                    $skipped++;
                    continue;
                }

                Book::create([
                    'category_id' => $category->id,
                    'code' => $code,
                    'title' => $title,
                    'author' => trim($row['penulis'] ?? $row['author'] ?? ''),
                    'publisher' => trim($row['penerbit'] ?? $row['publisher'] ?? ''),
                    'year' => !empty($row['tahun'] ?? $row['year'] ?? '') ? (int) ($row['tahun'] ?? $row['year']) : null,
                    'stock' => !empty($row['stok'] ?? $row['stock'] ?? '') ? (int) ($row['stok'] ?? $row['stock']) : 0,
                    'description' => trim($row['deskripsi'] ?? $row['description'] ?? ''),
                ]);
                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }

        return back()->with('success', "Berhasil import {$imported} buku fisik. {$skipped} dilewati.");
    }

    /**
     * Import ebooks from CSV (metadata only, without PDF).
     */
    public function importEbooks(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $request->file('file')->getRealPath();
        $rows = $this->parseCsv($path);

        if (count($rows) === 0) {
            return back()->with('error', 'File CSV kosong atau format tidak valid.');
        }

        $category = Category::where('slug', 'ebook')->first();
        if (!$category) {
            return back()->with('error', 'Kategori "E-Book" belum ada. Jalankan seeder terlebih dahulu.');
        }

        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $code = trim($row['kode/isbn'] ?? $row['kode'] ?? $row['isbn'] ?? $row['code'] ?? '');
                $title = trim($row['judul'] ?? $row['title'] ?? '');

                if (empty($code) || empty($title)) {
                    $skipped++;
                    continue;
                }

                if (Book::where('code', $code)->exists()) {
                    $skipped++;
                    continue;
                }

                $book = Book::create([
                    'category_id' => $category->id,
                    'code' => $code,
                    'title' => $title,
                    'author' => trim($row['penulis'] ?? $row['author'] ?? ''),
                    'publisher' => trim($row['penerbit'] ?? $row['publisher'] ?? ''),
                    'year' => !empty($row['tahun'] ?? $row['year'] ?? '') ? (int) ($row['tahun'] ?? $row['year']) : null,
                    'stock' => null,
                    'description' => trim($row['deskripsi'] ?? $row['description'] ?? ''),
                ]);

                // Create ebook record (file must be uploaded manually later)
                Ebook::create([
                    'book_id' => $book->id,
                    'file_path' => '',
                    'total_pages' => !empty($row['halaman'] ?? $row['pages'] ?? '') ? (int) ($row['halaman'] ?? $row['pages']) : null,
                ]);

                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }

        return back()->with('success', "Berhasil import {$imported} e-book (metadata). File PDF harus diupload manual per e-book. {$skipped} dilewati.");
    }

    /**
     * Import loans from CSV.
     */
    public function importLoans(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $request->file('file')->getRealPath();
        $rows = $this->parseCsv($path);

        if (count($rows) === 0) {
            return back()->with('error', 'File CSV kosong atau format tidak valid.');
        }

        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $borrowerName = trim($row['nama peminjam'] ?? $row['nama'] ?? $row['borrower_name'] ?? '');
                $borrowerPhone = trim($row['no hp'] ?? $row['phone'] ?? $row['borrower_phone'] ?? '');
                $bookCode = trim($row['kode buku'] ?? $row['kode'] ?? $row['book_code'] ?? '');

                if (empty($borrowerName) || empty($bookCode)) {
                    $skipped++;
                    continue;
                }

                $book = Book::where('code', $bookCode)->first();
                if (!$book) {
                    $skipped++;
                    continue;
                }

                $status = strtolower(trim($row['status'] ?? 'borrowed'));
                if (!in_array($status, ['borrowed', 'returned', 'late'])) {
                    $status = 'borrowed';
                }

                Loan::create([
                    'book_id' => $book->id,
                    'borrower_name' => $borrowerName,
                    'borrower_phone' => $borrowerPhone,
                    'borrower_email' => trim($row['email'] ?? $row['borrower_email'] ?? ''),
                    'identity_number' => trim($row['no identitas'] ?? $row['identity_number'] ?? ''),
                    'loan_date' => !empty($row['tgl pinjam'] ?? $row['loan_date'] ?? '') ? $row['tgl pinjam'] ?? $row['loan_date'] : now()->format('Y-m-d'),
                    'due_date' => !empty($row['jatuh tempo'] ?? $row['due_date'] ?? '') ? $row['jatuh tempo'] ?? $row['due_date'] : now()->addDays(7)->format('Y-m-d'),
                    'return_date' => !empty($row['tgl kembali'] ?? $row['return_date'] ?? '') ? ($row['tgl kembali'] ?? $row['return_date']) : null,
                    'status' => $status,
                    'notes' => trim($row['catatan'] ?? $row['notes'] ?? ''),
                ]);

                // Decrement stock if borrowed
                if ($status === 'borrowed' && $book->stock !== null) {
                    $book->decrement('stock');
                }

                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }

        return back()->with('success', "Berhasil import {$imported} data peminjaman. {$skipped} dilewati.");
    }

    /**
     * Download CSV template.
     */
    public function downloadTemplate(string $type): StreamedResponse
    {
        $templates = [
            'categories' => [
                'filename' => 'template_kategori.csv',
                'headers' => ['Nama', 'Deskripsi'],
                'sample' => [['Fiksi', 'Buku-buku fiksi dan novel'], ['Non-Fiksi', 'Buku referensi dan ilmiah']],
            ],
            'books' => [
                'filename' => 'template_buku_fisik.csv',
                'headers' => ['Kode/ISBN', 'Judul', 'Penulis', 'Penerbit', 'Tahun', 'Stok', 'Deskripsi'],
                'sample' => [['978-602-123-456', 'Pemrograman Laravel', 'John Doe', 'Penerbit ABC', '2025', '10', 'Buku panduan Laravel']],
            ],
            'ebooks' => [
                'filename' => 'template_ebook.csv',
                'headers' => ['Kode/ISBN', 'Judul', 'Penulis', 'Penerbit', 'Tahun', 'Halaman', 'Deskripsi'],
                'sample' => [['978-602-789-012', 'Digital Marketing', 'Jane Smith', 'Digital Press', '2025', '180', 'Panduan digital marketing']],
            ],
            'loans' => [
                'filename' => 'template_peminjaman.csv',
                'headers' => ['Nama Peminjam', 'No HP', 'Email', 'No Identitas', 'Kode Buku', 'Tgl Pinjam', 'Jatuh Tempo', 'Status', 'Catatan'],
                'sample' => [['Ahmad Fadli', '081234567890', 'ahmad@mail.com', '1234567890', '978-602-123-456', '2026-02-07', '2026-02-14', 'borrowed', '']],
            ],
        ];

        if (!isset($templates[$type])) {
            abort(404);
        }

        $tmpl = $templates[$type];

        return response()->streamDownload(function () use ($tmpl) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $tmpl['headers']);
            foreach ($tmpl['sample'] as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $tmpl['filename'], [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    // ════════════════════════════════════════════════════════════
    // HELPER
    // ════════════════════════════════════════════════════════════

    /**
     * Parse CSV file into associative array with lowercase headers.
     */
    private function parseCsv(string $path): array
    {
        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            // Read header row
            $header = fgetcsv($handle);
            if (!$header) {
                fclose($handle);
                return [];
            }

            // Remove BOM from first column
            $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);

            // Normalize headers to lowercase
            $header = array_map(fn($h) => strtolower(trim($h)), $header);

            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) === count($header)) {
                    $rows[] = array_combine($header, $data);
                }
            }
            fclose($handle);
        }

        return $rows;
    }
}
