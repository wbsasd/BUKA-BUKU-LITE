<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfReaderController extends Controller
{
    private const TRIAL_LIMIT = 10;

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

        $pdfPath = trim((string) ($book->file_pdf ?? ''));
        $documentAvailable = $pdfPath !== ''
            && (Storage::disk('local')->exists($pdfPath) || Storage::disk('public')->exists($pdfPath));

        $payload = [
            'book_id' => (int) $book->id,
            'user_id' => Auth::id(),
            'is_premium' => (bool) (Auth::user()?->hasPremiumAccess() ?? false),
            'expires_at' => now()->addMinutes(15)->timestamp,
            'nonce' => bin2hex(random_bytes(16)),
        ];

        $readerToken = base64_encode(json_encode($payload));
        $request->session()->put($this->sessionTokenKey((int) $book->id), $readerToken);

        return view('pdf-reader', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
            'readerToken' => $readerToken,
            'trialLimit' => self::TRIAL_LIMIT,
            'documentAvailable' => $documentAvailable,
        ]);
    }

    public function document(Request $request, $id): StreamedResponse
    {
        $book = Book::findOrFail($id);

        $token = (string) $request->query('token', '');
        $sessionToken = (string) $request->session()->get($this->sessionTokenKey((int) $book->id), '');

        abort_if($token === '' || $sessionToken === '' || !hash_equals($sessionToken, $token), 403);

        $decoded = json_decode((string) base64_decode($token, true), true);
        abort_if(!is_array($decoded), 403);

        $expiresAt = (int) ($decoded['expires_at'] ?? 0);
        abort_if($expiresAt <= now()->timestamp, 403);
        abort_if((int) ($decoded['book_id'] ?? 0) !== (int) $book->id, 403);

        $tokenUserId = (int) ($decoded['user_id'] ?? 0);
        $authUserId = (int) (Auth::id() ?? 0);
        if ($tokenUserId > 0 || $authUserId > 0) {
            abort_if($tokenUserId !== $authUserId, 403);
        }

        $pdfPath = trim((string) ($book->file_pdf ?? ''));
        abort_if($pdfPath === '', 404);

        $pdfDisk = null;
        if (Storage::disk('local')->exists($pdfPath)) {
            $pdfDisk = 'local';
        } elseif (Storage::disk('public')->exists($pdfPath)) {
            // Backward compatibility for existing public PDF files.
            $pdfDisk = 'public';
        }

        abort_if($pdfDisk === null, 404);

        $stream = Storage::disk($pdfDisk)->readStream($pdfPath);
        abort_if($stream === false, 404);

        $fileName = basename($pdfPath);
        $isPremium = (bool) ($decoded['is_premium'] ?? false);

        return response()->stream(function () use ($stream): void {
            try {
                fpassthru($stream);
            } finally {
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
            'X-Robots-Tag' => 'noindex, nofollow, noarchive',
            'X-Trial-Max-Pages' => $isPremium ? 'unlimited' : (string) self::TRIAL_LIMIT,
        ]);
    }

    private function sessionTokenKey(int $bookId): string
    {
        return 'reader_token_' . $bookId;
    }
}

