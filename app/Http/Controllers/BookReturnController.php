<?php

namespace App\Http\Controllers;

use App\Models\BookReturn;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookReturnController extends Controller
{
    public const FINE_PER_DAY = 1000;
    public const FINE_DAMAGED = 20000;
    public const FINE_LOST    = 50000;

    public function index(Request $request)
    {
        $query = BookReturn::with(['loan.user', 'loan.book'])->latest();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('return_code', 'like', "%{$search}%")
                  ->orWhereHas('loan.user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('loan.book', fn ($b) => $b->where('title', 'like', "%{$search}%"));
            });
        }

        if ($condition = $request->query('condition')) {
            $query->where('condition', $condition);
        }

        if ($status = $request->query('fine_status')) {
            $query->where('fine_status', $status);
        }

        $returns = $query->paginate(10)->withQueryString();

        $stats = [
            'total'      => BookReturn::count(),
            'paid_fine'  => (int) BookReturn::where('fine_status', 'Lunas')->sum('fine_amount'),
            'unpaid'     => (int) BookReturn::where('fine_status', 'Belum Lunas')->sum('fine_amount'),
            'good_books' => BookReturn::where('condition', 'Baik')->count(),
        ];

        $activeLoans = Loan::with(['user', 'book'])
            ->where('status', '!=', 'Dikembalikan')
            ->latest()
            ->get();

        return view('returns.index', compact('returns', 'stats', 'activeLoans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loan_id'     => ['required', 'exists:loans,id'],
            'return_date' => ['required', 'date'],
            'condition'   => ['required', 'in:Baik,Rusak,Hilang'],
            'notes'       => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            DB::transaction(function () use ($data) {
                $loan = Loan::with('book')->lockForUpdate()->findOrFail($data['loan_id']);

                if ($loan->status === 'Dikembalikan') {
                    throw new \RuntimeException('Peminjaman ini sudah dikembalikan.');
                }

                $returnDate = Carbon::parse($data['return_date'])->startOfDay();
                $dueDate    = Carbon::parse($loan->due_date)->startOfDay();

                $lateDays = $returnDate->greaterThan($dueDate)
                    ? $dueDate->diffInDays($returnDate)
                    : 0;

                $fine = $lateDays * self::FINE_PER_DAY;

                if ($data['condition'] === 'Rusak') {
                    $fine += self::FINE_DAMAGED;
                } elseif ($data['condition'] === 'Hilang') {
                    $fine += self::FINE_LOST;
                }

                BookReturn::create([
                    'loan_id'     => $loan->id,
                    'return_date' => $returnDate->toDateString(),
                    'late_days'   => $lateDays,
                    'fine_amount' => $fine,
                    'condition'   => $data['condition'],
                    'fine_status' => $fine > 0 ? 'Belum Lunas' : 'Bebas Denda',
                    'notes'       => $data['notes'] ?? null,
                ]);

                $loan->update([
                    'status'      => 'Dikembalikan',
                    'return_date' => $returnDate->toDateString(),
                ]);

                if ($data['condition'] !== 'Hilang' && $loan->book) {
                    $loan->book->increment('available');
                }
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('returns.index')
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    public function update(Request $request, BookReturn $return)
    {
        $data = $request->validate([
            'fine_status' => ['required', 'in:Lunas,Belum Lunas,Bebas Denda'],
            'notes'       => ['nullable', 'string', 'max:1000'],
        ]);

        if ($return->fine_amount === 0) {
            $data['fine_status'] = 'Bebas Denda';
        } elseif ($data['fine_status'] === 'Bebas Denda') {
            return back()->with('error', 'Pengembalian ini memiliki denda, status tidak bisa "Bebas Denda".');
        }

        $return->update($data);

        return redirect()->route('returns.index')
            ->with('success', 'Data pengembalian berhasil diperbarui.');
    }

    public function destroy(BookReturn $return)
    {
        $return->delete();

        return redirect()->route('returns.index')
            ->with('success', 'Arsip pengembalian berhasil dihapus.');
    }
}