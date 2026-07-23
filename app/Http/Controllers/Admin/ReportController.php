<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportFilterRequest;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Barryvdh\DomPDF\Facades\Pdf;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;


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
        $borrowingsDipinjamCount = Borrowing::where('status', 'paid')->count();
        $pengembalianCount = Borrowing::where('status', 'returned')
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

        $export = new class($rows) implements FromArray, WithHeadings {
            public function __construct(private array $rows) {}

            public function array(): array
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

        return Excel::download($export, 'laporan-admin-'.now()->format('Y-m-d_H-i-s').'.xlsx');
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

