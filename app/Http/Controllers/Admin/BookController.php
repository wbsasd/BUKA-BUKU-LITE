<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookStoreRequest;
use App\Http\Requests\Admin\BookUpdateRequest;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;
// use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookController extends Controller
{
    private const COVER_DISK = 'public';
    private const PDF_DISK = 'local';


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

        // Handle uploads (storage for public/asset)
        // Store relative path under public disk, e.g. books/covers/cover.jpg
        if ($request->hasFile('cover_image')) {
            $storedCoverPath = $this->storeUpload($request, 'cover_image', 'books/covers', self::COVER_DISK);
            if (!empty($storedCoverPath)) {
                $data['cover_image'] = $storedCoverPath;
            }
        } else {
            unset($data['cover_image']);
        }

        if ($request->hasFile('file_pdf')) {
            $storedPdfPath = $this->storeUpload($request, 'file_pdf', 'books/pdf', self::PDF_DISK);
            if (!empty($storedPdfPath)) {
                $data['file_pdf'] = $storedPdfPath;
            }
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
        $originalCoverPath = $book->cover_image;
        $originalPdfPath = $book->file_pdf;

        // Resolve kategori_id dari input category_name (buat otomatis jika belum ada)
        $categoryName = $data['category_name'] ?? null;
        if (!empty($categoryName)) {
            $categoryName = trim($categoryName);
            $category = Category::firstOrCreate(['name' => $categoryName]);
            $data['category_id'] = $category->id;
        }
        unset($data['category_name']);

        // Handle uploads (public storage for cover, private storage for PDFs).
        if ($request->hasFile('cover_image')) {
            $storedCoverPath = $this->storeUpload($request, 'cover_image', 'books/covers', self::COVER_DISK);
            if (!empty($storedCoverPath)) {
                $data['cover_image'] = $storedCoverPath;
            } else {
                unset($data['cover_image']);
            }
        } else {
            unset($data['cover_image']);
        }

        if ($request->hasFile('file_pdf')) {
            $storedPdfPath = $this->storeUpload($request, 'file_pdf', 'books/pdf', self::PDF_DISK);
            if (!empty($storedPdfPath)) {
                $data['file_pdf'] = $storedPdfPath;
            } else {
                unset($data['file_pdf']);
            }
        } else {
            unset($data['file_pdf']);
        }

        $book->update($data);

        if (!empty($data['cover_image']) && $data['cover_image'] !== $originalCoverPath) {
            $this->safeDeleteFile($originalCoverPath, self::COVER_DISK, 'replace-cover', [
                'book_id' => $book->id,
                'new_path' => $data['cover_image'],
            ]);
        }

        if (!empty($data['file_pdf']) && $data['file_pdf'] !== $originalPdfPath) {
            $this->safeDeleteFromDisks($originalPdfPath, [self::PDF_DISK, self::COVER_DISK], 'replace-pdf', [
                'book_id' => $book->id,
                'new_path' => $data['file_pdf'],
            ]);
        }

        return redirect()->route('admin.books.index')->with('success', 'Book updated');
    }



    public function destroy(Book $book): RedirectResponse
    {
        $coverPath = $book->cover_image;
        $pdfPath = $book->file_pdf;

        $book->delete();

        $this->safeDeleteFile($coverPath, self::COVER_DISK, 'delete-book-cover', [
            'book_id' => $book->id,
        ]);
        $this->safeDeleteFromDisks($pdfPath, [self::PDF_DISK, self::COVER_DISK], 'delete-book-pdf', [
            'book_id' => $book->id,
        ]);

        Log::info('Book deleted', [
            'book_id' => $book->id,
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Book deleted');
    }

    private function storeUpload(Request $request, string $fieldName, string $directory, string $disk): ?string
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        try {
            $path = $request->file($fieldName)->store($directory, $disk);

            if (empty($path)) {
                Log::warning('Book upload returned empty path', [
                    'field' => $fieldName,
                    'directory' => $directory,
                ]);

                return null;
            }

            Log::info('Book file uploaded', [
                'field' => $fieldName,
                'path' => $path,
                'disk' => $disk,
            ]);

            return $path;
        } catch (Throwable $e) {
            Log::error('Book file upload failed', [
                'field' => $fieldName,
                'directory' => $directory,
                'disk' => $disk,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function safeDeleteFile(?string $path, string $disk, string $action, array $context = []): void
    {
        $normalizedPath = $this->normalizeStoragePath($path);
        if (empty($normalizedPath)) {
            return;
        }

        try {
            $deleted = Storage::disk($disk)->delete($normalizedPath);

            Log::info('Book file delete attempted', array_merge($context, [
                'action' => $action,
                'path' => $normalizedPath,
                'disk' => $disk,
                'deleted' => (bool) $deleted,
            ]));
        } catch (Throwable $e) {
            Log::warning('Book file delete failed', array_merge($context, [
                'action' => $action,
                'path' => $normalizedPath,
                'disk' => $disk,
                'error' => $e->getMessage(),
            ]));
        }
    }

    private function safeDeleteFromDisks(?string $path, array $disks, string $action, array $context = []): void
    {
        foreach ($disks as $disk) {
            $this->safeDeleteFile($path, (string) $disk, $action, $context);
        }
    }

    private function normalizeStoragePath(?string $path): ?string
    {
        $trimmed = trim((string) $path);
        if ($trimmed === '') {
            return null;
        }

        $normalized = str_replace('\\', '/', $trimmed);
        $normalized = ltrim($normalized, '/');

        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, 8);
        }

        return $normalized !== '' ? $normalized : null;
    }
}

