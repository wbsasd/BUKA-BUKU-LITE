<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookStoreRequest;
use App\Http\Requests\Admin\BookUpdateRequest;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookController extends Controller
{

    public function index(Request $request): View
    {
        $query = Book::query()->with('category');


        // Optional filters (do not change UI)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->string('category_id'));
        }

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%")
                    ->orWhere('publisher', 'like', "%{$q}%");
            });
        }

        $books = $query->paginate(15);

        // No admin/book views exist in this repo; keep method complete but do not break runtime.
        // Fallback to a generic admin page.
        return view('admin.books.index', [
            'books' => $books,
            'categories' => Category::all(),
        ]);
    }

    public function create(): View
    {
        return view('admin.books.create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(BookStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Resolve kategori_id dari input category_name (buat otomatis jika belum ada)
        $categoryName = $data['category_name'] ?? null;
        if (!empty($categoryName)) {
            $categoryName = trim($categoryName);
            $category = Category::firstOrCreate(['name' => $categoryName]);
            $data['category_id'] = $category->id;
        }
        unset($data['category_name']);

        // Handle uploads (private storage)
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('books/covers', 'local');
        } else {
            unset($data['cover_image']);
        }

        if ($request->hasFile('file_pdf')) {
            $data['file_pdf'] = $request->file('file_pdf')->store('books/pdfs', 'local');
        } else {
            unset($data['file_pdf']);
        }

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', 'Book created');
    }



    public function show(Book $book): View
    {
        $book->load('category');
        return view('admin.books.show', [
            'book' => $book,
        ]);
    }

    public function edit(Book $book): View
    {
        return view('admin.books.edit', [
            'book' => $book,
            'categories' => Category::all(),
        ]);
    }


    public function update(BookUpdateRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        // Resolve kategori_id dari input category_name (buat otomatis jika belum ada)
        $categoryName = $data['category_name'] ?? null;
        if (!empty($categoryName)) {
            $categoryName = trim($categoryName);
            $category = Category::firstOrCreate(['name' => $categoryName]);
            $data['category_id'] = $category->id;
        }
        unset($data['category_name']);

        // Handle uploads (private storage). Keep existing file if not re-uploaded.
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('books/covers', 'local');
        } else {
            unset($data['cover_image']);
        }

        if ($request->hasFile('file_pdf')) {
            $data['file_pdf'] = $request->file('file_pdf')->store('books/pdfs', 'local');
        } else {
            unset($data['file_pdf']);
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Book updated');
    }



    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted');
    }
}

