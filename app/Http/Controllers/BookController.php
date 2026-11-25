<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(author) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(isbn) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        $books = $query->orderBy('id', 'asc')->get();

        // Ringkasan
        $totalTitles = Book::count();
        $totalCopies = Book::sum('stock');
        $availableCopies = Book::sum('available');

        // Daftar kategori unik
        $categories = Book::select('category')->distinct()->pluck('category');

        return view('books.index', compact(
            'books',
            'totalTitles',
            'totalCopies',
            'availableCopies',
            'categories'
        ));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang dapat mengelola koleksi buku.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:50', 'unique:books,isbn'],
            'category' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:1'],
            'available' => ['nullable', 'integer', 'min:0'],
            'theme' => ['nullable', 'string', 'in:cover-teal,cover-amber,cover-blue,cover-emerald'],
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Penulis buku wajib diisi.',
            'isbn.required' => 'Nomor ISBN wajib diisi.',
            'isbn.unique' => 'Buku dengan nomor ISBN ini sudah ada.',
            'category.required' => 'Kategori wajib dipilih atau diisi.',
            'stock.required' => 'Total stok wajib diisi.',
        ]);

        if (!isset($validated['available'])) {
            $validated['available'] = $validated['stock'];
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku "' . $validated['title'] . '" berhasil ditambahkan ke koleksi!');
    }

    public function update(Request $request, Book $book)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang dapat mengelola koleksi buku.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:50', 'unique:books,isbn,' . $book->id],
            'category' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:1'],
            'available' => ['required', 'integer', 'min:0'],
        ]);

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Informasi buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang dapat mengelola koleksi buku.');
        }

        $title = $book->title;
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku "' . $title . '" berhasil dihapus.');
    }
}
