<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser && $currentUser->isAdmin();

        // Statistik
        $totalBooks = Book::sum('stock');
        $totalMembers = User::count();

        if ($isAdmin) {
            $borrowedBooks = Loan::whereIn('status', ['borrowed', 'Dipinjam'])->count();
            $overdueBooks = Loan::whereIn('status', ['overdue', 'Terlambat'])->count();
        } else {
            $borrowedBooks = Loan::where('user_id', $currentUser->id)
                ->whereIn('status', ['borrowed', 'Dipinjam'])
                ->count();
            $overdueBooks = Loan::where('user_id', $currentUser->id)
                ->whereIn('status', ['overdue', 'Terlambat'])
                ->count();
        }

        // Aktivitas Peminjaman per bulan (data 6 bulan)
        $chartData = [
            ['label' => 'Jan', 'value' => 45, 'highlight' => false],
            ['label' => 'Feb', 'value' => 70, 'highlight' => false],
            ['label' => 'Mar', 'value' => 55, 'highlight' => false],
            ['label' => 'Apr', 'value' => 85, 'highlight' => false],
            ['label' => 'Mei', 'value' => 60, 'highlight' => false],
            ['label' => 'Jun', 'value' => 95, 'highlight' => true],
        ];

        return view('dashboard', compact(
            'currentUser',
            'totalBooks',
            'totalMembers',
            'borrowedBooks',
            'overdueBooks',
            'chartData'
        ));
    }
}
