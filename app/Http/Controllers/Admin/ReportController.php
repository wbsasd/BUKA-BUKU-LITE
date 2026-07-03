<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportFilterRequest;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Barryvdh\DomPDF\Facades\Pdf;


class ReportController extends Controller
{
    public function index(ReportFilterRequest $request)
    {
        $filters = $request->validated();

        $query = Borrowing::query()
            ->with(['book', 'user'])
            ->orderByDesc('borrow_date');

        if (!empty($filters['start_date'])) {
            $query->whereDate('borrow_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('borrow_date', '<=', $filters['end_date']);
        }

        // status tetap source of truth untuk menentukan kelompok laporan
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $borrowings = $query->get();

        $booksTotal = Book::count();
        $usersTotal = User::count();
        $borrowingsDipinjamCount = Borrowing::where('status', 'dipinjam')->count();
        $pengembalianCount = Borrowing::where('status', 'dikembalikan')
            ->whereNotNull('return_date')
            ->count();

        return view('admin.reports.index', [
            'filters' => $filters,
            'borrowings' => $borrowings,
            'booksTotal' => $booksTotal,
            'usersTotal' => $usersTotal,
            'borrowingsDipinjamCount' => $borrowingsDipinjamCount,
            'pengembalianCount' => $pengembalianCount,
        ]);
    }

    public function exportPdf(ReportFilterRequest $request)
    {
        $filters = $request->validated();

        $borrowings = $this->buildBorrowingQuery($filters)->get();

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'filters' => $filters,
            'borrowings' => $borrowings,
            'generatedAt' => now(),
        ]);

        return $pdf->download('laporan-admin-'.now()->format('Y-m-d_H-i-s').'.pdf');
    }

    public function exportExcel(ReportFilterRequest $request)
    {
        $filters = $request->validated();

        $borrowings = $this->buildBorrowingQuery($filters)->get();

        $rows = $borrowings->map(function ($b) {
            return [
                'ID Peminjaman' => $b->id,
                'Buku' => $b->book?->title,
                'Pengguna' => $b->user?->name,
                'Tanggal Peminjaman' => optional($b->borrow_date)->format('Y-m-d'),
                'Tanggal Pengembalian' => optional($b->return_date)->format('Y-m-d'),
                'Status' => $b->status,
            ];
        })->values()->all();

        return response()->streamDownload(function () use ($rows) {
            $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
            unlink($tempFile);

            // export via array
            $export = new class($rows) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
                public function __construct(private array $rows) {}
                public function array(array $cells = []): array
                {
                    return $this->rows;
                }
                public function headings(): array
                {
                    return array_keys($this->rows[0] ?? [
                        'ID Peminjaman' => null,
                        'Buku' => null,
                        'Pengguna' => null,
                        'Tanggal Peminjaman' => null,
                        'Tanggal Pengembalian' => null,
                        'Status' => null,
                    ]);
                }
            };

            // Use Laravel Excel facade export
            \Maatwebsite\Excel\Excel::store($export, basename($tempFile).'.xlsx', 'local', [
                'visibility' => 'private'
            ]);
        }, 'laporan-admin-'.now()->format('Y-m-d_H-i-s').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }

    private function buildBorrowingQuery(array $filters)
    {
        $query = Borrowing::query()
            ->with(['book', 'user'])
            ->orderByDesc('borrow_date');

        if (!empty($filters['start_date'])) {
            $query->whereDate('borrow_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('borrow_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query;
    }
}

