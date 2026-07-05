<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

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
}

