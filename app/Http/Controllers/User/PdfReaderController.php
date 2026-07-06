<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfReaderController extends Controller
{
    public function show(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->load('category');

        $relatedBooks = Book::with('category')
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('stock', '>', 0)
            ->limit(4)
            ->get();

        return view('pdf-reader', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
        ]);
    }

    public function pdf(Request $request, $book)
    {
        if (!auth()->check()) {
            abort(403);
        }

        $bookModel = Book::find($book);
        if (!$bookModel) {
            abort(404);
        }

        $fileRelative = $bookModel->file_pdf;
        if (empty($fileRelative)) {
            abort(404);
        }

        // Fokus keamanan: jangan gunakan disk public.
        // Kita baca dari storage private: storage/app/private/books
        // diasumsikan $fileRelative menyimpan nama path relatif seperti sebelumnya (mis. file.pdf)
        $privateDiskPath = 'private/books/' . ltrim($fileRelative, '/');

        if (!Storage::disk('local')->exists($privateDiskPath)) {
            abort(404);
        }

        $absolutePath = Storage::disk('local')->path($privateDiskPath);

        return response()->streamDownload(function () use ($absolutePath) {
            $stream = fopen($absolutePath, 'rb');
            if ($stream === false) {
                abort(404);
            }
            while (!feof($stream)) {
                echo fread($stream, 1024 * 1024);
                flush();
            }
            fclose($stream);
        }, basename($absolutePath), [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}


