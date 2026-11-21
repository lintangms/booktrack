<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'member_id' => 'MBR-001',
            'role' => 'Anggota',
        ]);

        $this->book = Book::create([
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'isbn' => '978-979-3062-79-2',
            'category' => 'Fiksi',
            'stock' => 5,
            'available' => 3,
            'theme' => 'cover-teal',
        ]);
    }

    public function test_guest_cannot_access_loans_page(): void
    {
        $response = $this->get('/peminjaman');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_loans_index(): void
    {
        Loan::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'loan_date' => Carbon::today()->subDays(2)->toDateString(),
            'due_date' => Carbon::today()->addDays(5)->toDateString(),
            'status' => 'Dipinjam',
            'notes' => 'Tugas sekolah',
        ]);

        $response = $this->actingAs($this->user)->get('/peminjaman');
        $response->assertStatus(200);
        $response->assertSee('Transaksi Peminjaman');
        $response->assertSee('Laskar Pelangi');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Dipinjam');
    }

    public function test_loan_creation_decrements_book_available_stock(): void
    {
        $initialAvailable = $this->book->available;

        $response = $this->actingAs($this->user)->post('/peminjaman', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'loan_date' => Carbon::today()->toDateString(),
            'due_date' => Carbon::today()->addDays(7)->toDateString(),
            'notes' => 'Peminjaman pertama',
        ]);

        $response->assertRedirect('/peminjaman');
        $response->assertSessionHas('success');

        $this->book->refresh();
        $this->assertEquals($initialAvailable - 1, $this->book->available);

        $loan = Loan::where('user_id', $this->user->id)->first();
        $this->assertNotNull($loan);
        $this->assertEquals('Dipinjam', $loan->status);
    }

    public function test_loan_cannot_be_created_if_book_stock_is_exhausted(): void
    {
        $this->book->update(['available' => 0]);

        $response = $this->actingAs($this->user)->post('/peminjaman', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'loan_date' => Carbon::today()->toDateString(),
            'due_date' => Carbon::today()->addDays(7)->toDateString(),
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, Loan::count());
    }

    public function test_loan_update_status_to_dikembalikan_increments_available_stock(): void
    {
        $loan = Loan::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'loan_date' => Carbon::today()->subDays(5)->toDateString(),
            'due_date' => Carbon::today()->addDays(2)->toDateString(),
            'status' => 'Dipinjam',
        ]);

        $initialAvailable = $this->book->available;

        $response = $this->actingAs($this->user)->put('/peminjaman/' . $loan->id, [
            'due_date' => $loan->due_date->format('Y-m-d'),
            'status' => 'Dikembalikan',
            'return_date' => Carbon::today()->toDateString(),
            'notes' => 'Buku kembali mulus',
        ]);

        $response->assertRedirect('/peminjaman');
        $response->assertSessionHas('success');

        $this->book->refresh();
        $this->assertEquals($initialAvailable + 1, $this->book->available);

        $loan->refresh();
        $this->assertEquals('Dikembalikan', $loan->status);
        $this->assertNotNull($loan->return_date);
    }

    public function test_deleting_active_loan_restores_book_available_stock(): void
    {
        $loan = Loan::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'loan_date' => Carbon::today()->subDays(1)->toDateString(),
            'due_date' => Carbon::today()->addDays(6)->toDateString(),
            'status' => 'Dipinjam',
        ]);

        $initialAvailable = $this->book->available;

        $response = $this->actingAs($this->user)->delete('/peminjaman/' . $loan->id);
        $response->assertRedirect('/peminjaman');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);

        $this->book->refresh();
        $this->assertEquals($initialAvailable + 1, $this->book->available);
    }

    public function test_overdue_loans_are_automatically_marked_terlambat_on_index(): void
    {
        $pastDueDate = Carbon::today()->subDays(3)->toDateString();
        $loan = Loan::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'loan_date' => Carbon::today()->subDays(10)->toDateString(),
            'due_date' => $pastDueDate,
            'status' => 'Dipinjam',
            'return_date' => null,
        ]);

        $this->actingAs($this->user)->get('/peminjaman');

        $loan->refresh();
        $this->assertEquals('Terlambat', $loan->status);
    }
}
