<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Ebook;
use App\Models\EbookRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EbookReaderController extends Controller
{
    /**
     * Show the ebook reader page.
     */
    public function show(Book $book, Request $request)
    {
        if (!$book->isEbook() || !$book->ebook) {
            abort(404);
        }

        // Track ebook read
        EbookRead::create([
            'book_id' => $book->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $book->load(['category', 'ebook']);

        return view('public.ebook.reader', compact('book'));
    }

    /**
     * Stream the PDF file securely without exposing the real path.
     */
    public function stream(Book $book): StreamedResponse
    {
        if (!$book->isEbook() || !$book->ebook) {
            abort(404);
        }

        $ebook = $book->ebook;
        $filePath = $ebook->file_path;

        if (!Storage::disk('local')->exists($filePath)) {
            abort(404);
        }

        return response()->stream(function () use ($filePath) {
            $stream = Storage::disk('local')->readStream($filePath);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }
}
