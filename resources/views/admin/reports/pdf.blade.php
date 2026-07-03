<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h2 { margin: 0 0 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #f2f2f2; }
        .muted { color: #555; }
    </style>
</head>
<body>
    <h2>Laporan Admin</h2>
    <p class="muted">Dibuat pada: {{ $generatedAt->format('Y-m-d H:i:s') }}</p>
    <p class="muted">
        Filter:
        @if(!empty($filters['start_date'])) Mulai {{ $filters['start_date'] }} @endif
        @if(!empty($filters['end_date'])) Selesai {{ $filters['end_date'] }} @endif
        @if(!empty($filters['status'])) Status {{ $filters['status'] }} @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Buku</th>
                <th>Pengguna</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $b)
                <tr>
                    <td>{{ $b->id }}</td>
                    <td>{{ $b->book?->title }}</td>
                    <td>{{ $b->user?->name }}</td>
                    <td>{{ $b->borrow_date ? $b->borrow_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ $b->return_date ? $b->return_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ $b->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

