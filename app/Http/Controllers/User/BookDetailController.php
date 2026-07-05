<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookDetailController extends Controller
{
    public function show(Request $request, $id)
    {
        $book = Book::with('category')
            ->where('id', $id)
            ->firstOrFail();

        return view('book-detail', [
            'book' => $book,
        ]);
    }
}

