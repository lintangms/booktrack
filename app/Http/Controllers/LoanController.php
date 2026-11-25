<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Tampilkan daftar transaksi peminjaman buku.
     */
    public function index(Request $request)
    {
        // Deteksi otomatis jika due_date terlewati dan belum kembali, set status ke 'Terlambat'
        Loan::where('status', 'Dipinjam')
            ->whereDate('due_date', '<', Carbon::today())
            ->whereNull('return_date')
            ->update(['status' => 'Terlambat']);

        $query = Loan::with(['user', 'book']);

        // Jika bukan Administrator, batasi hanya peminjaman miliknya sendiri
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        // Pencarian: nama anggota atau judul/penulis buku
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                      ->orWhereRaw('LOWER(member_id) LIKE ?', ["%{$search}%"]);
                })
                ->orWhereHas('book', function ($bq) use ($search) {
                    $bq->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                      ->orWhereRaw('LOWER(author) LIKE ?', ["%{$search}%"]);
                });
            });
        }

        // Filter status
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        $loans = $query->orderBy('id', 'desc')->get();

        // Statistik ringkasan
        if (Auth::user()->isAdmin()) {
            $totalLoans = Loan::count();
            $borrowedCount = Loan::where('status', 'Dipinjam')->count();
            $overdueCount = Loan::where('status', 'Terlambat')->count();
            $returnedCount = Loan::where('status', 'Dikembalikan')->count();
            $users = User::orderBy('name', 'asc')->get();
            $availableBooks = Book::where('available', '>', 0)->orderBy('title', 'asc')->get();
        } else {
            $userLoansQuery = Loan::where('user_id', Auth::id());
            $totalLoans = (clone $userLoansQuery)->count();
            $borrowedCount = (clone $userLoansQuery)->where('status', 'Dipinjam')->count();
            $overdueCount = (clone $userLoansQuery)->where('status', 'Terlambat')->count();
            $returnedCount = (clone $userLoansQuery)->where('status', 'Dikembalikan')->count();
            $users = collect([Auth::user()]);
            $availableBooks = collect();
        }

        return view('loans.index', compact(
            'loans',
            'totalLoans',
            'borrowedCount',
            'overdueCount',
            'returnedCount',
            'users',
            'availableBooks'
        ));
    }

    /**
     * Catat transaksi peminjaman baru.
     */
    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Transaksi peminjaman hanya dapat dicatat oleh Administrator.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'book_id' => ['required', 'exists:books,id'],
            'loan_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'user_id.required' => 'Peminjam / anggota wajib dipilih.',
            'user_id.exists' => 'Data anggota tidak valid.',
            'book_id.required' => 'Buku yang dipinjam wajib dipilih.',
            'book_id.exists' => 'Data buku tidak valid.',
            'loan_date.required' => 'Tanggal pinjam wajib diisi.',
            'due_date.required' => 'Tanggal jatuh tempo wajib diisi.',
            'due_date.after_or_equal' => 'Tanggal jatuh tempo tidak boleh sebelum tanggal pinjam.',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        // Logika bisnis: Tolak jika stok buku habis
        if ($book->available <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Stok buku "' . $book->title . '" sedang habis dan tidak dapat dipinjam.');
        }

        DB::transaction(function () use ($validated, $book) {
            // Logika bisnis: Kurangi stok tersedia 1
            $book->decrement('available');

            Loan::create([
                'user_id' => $validated['user_id'],
                'book_id' => $validated['book_id'],
                'loan_date' => $validated['loan_date'],
                'due_date' => $validated['due_date'],
                'status' => 'Dipinjam',
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()->route('loans.index')
            ->with('success', 'Transaksi peminjaman buku berhasil dicatat.');
    }

    /**
     * Perbarui data peminjaman (perpanjangan masa pinjam / perubahan status / catatan).
     */
    public function update(Request $request, Loan $loan)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Transaksi peminjaman hanya dapat diperbarui oleh Administrator.');
        }

        $validated = $request->validate([
            'due_date' => ['required', 'date'],
            'status' => ['required', 'in:Dipinjam,Terlambat,Dikembalikan'],
            'return_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'due_date.required' => 'Tanggal jatuh tempo wajib diisi.',
            'status.required' => 'Status peminjaman wajib dipilih.',
            'status.in' => 'Status peminjaman tidak valid.',
        ]);

        DB::transaction(function () use ($validated, $loan) {
            $book = $loan->book;
            $oldStatus = $loan->status;
            $newStatus = $validated['status'];

            // Logika pengembalian buku: stok bertambah jika status berubah ke Dikembalikan
            if ($oldStatus !== 'Dikembalikan' && $newStatus === 'Dikembalikan') {
                $book->increment('available');
                $validated['return_date'] = $validated['return_date'] ?: Carbon::today()->toDateString();
            }
            // Jika status sebelumnya Dikembalikan lalu diubah kembali ke Dipinjam/Terlambat
            elseif ($oldStatus === 'Dikembalikan' && $newStatus !== 'Dikembalikan') {
                if ($book->available > 0) {
                    $book->decrement('available');
                }
                $validated['return_date'] = null;
            }

            // Jika status saat ini Terlambat tapi due_date diperpanjang ke masa depan
            if ($newStatus === 'Terlambat' && Carbon::parse($validated['due_date'])->isFuture()) {
                $validated['status'] = 'Dipinjam';
            }

            $loan->update($validated);
        });

        return redirect()->route('loans.index')
            ->with('success', 'Data transaksi peminjaman #' . $loan->id . ' berhasil diperbarui.');
    }

    /**
     * Batalkan atau hapus data transaksi peminjaman.
     */
    public function destroy(Loan $loan)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Transaksi peminjaman hanya dapat dihapus oleh Administrator.');
        }

        $loanId = $loan->id;

        DB::transaction(function () use ($loan) {
            // Logika bisnis: Jika transaksi dihapus saat statusnya masih 'Dipinjam' atau 'Terlambat',
            // stok buku yang dipinjam otomatis dikembalikan (available bertambah 1).
            if (in_array($loan->status, ['Dipinjam', 'Terlambat', 'borrowed', 'overdue'])) {
                $loan->book->increment('available');
            }

            $loan->delete();
        });

        return redirect()->route('loans.index')
            ->with('success', 'Transaksi peminjaman #' . $loanId . ' berhasil dihapus.');
    }
}
