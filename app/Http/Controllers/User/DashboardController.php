<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $latestBooks = Book::with('category')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $recommendedBooks = Book::with('category')
            ->where('stock', '>', 0)
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('dashboard', [
            'latestBooks' => $latestBooks,
            'recommendedBooks' => $recommendedBooks,
            'categories' => $categories,
        ]);
    }
}

