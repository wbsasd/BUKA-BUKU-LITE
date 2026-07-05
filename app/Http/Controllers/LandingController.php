<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $books = Book::with('category')
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::all();

        return view('landing', compact('books', 'categories'));
    }
}
