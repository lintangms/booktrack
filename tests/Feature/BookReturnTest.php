<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookReturn;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReturnTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
    protected Book $book;
    protected Loan $loan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin Pengembalian',
            'email' => 'admin_return@booktrack.id',
            'role' => 'Administrator',
            'status' => 'Aktif',
        ]);

        $this->user = User::factory()->create([
            'name' => 'Pengunjung Return',
            'email' => 'pengunjung_return@example.com',
            'role' => 'Pengunjung',
            'status' => 'Aktif',
        ]);

        $this->book = Book::create([
            'title' => 'Filosofi Teras',
            'author' => 'Henry Manampiring',
            'isbn' => '978-602-412-518-9',
            'category' => 'Pengembangan Diri',
            'stock' => 5,
            'available' => 3,
            'theme' => 'cover-amber',
        ]);

        $this->loan = Loan::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'loan_date' => Carbon::today()->subDays(10)->toDateString(),
            'due_date' => Carbon::today()->subDays(3)->toDateString(),
            'status' => 'Terlambat',
        ]);
    }

    public function test_admin_can_process_book_return_with_fine(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/pengembalian', [
            'loan_id' => $this->loan->id,
            'return_date' => Carbon::today()->toDateString(),
            'condition' => 'Baik',
            'notes' => 'Dikembalikan tepat',
        ]);

        $response->assertRedirect('/admin/pengembalian');
        $response->assertSessionHas('success');

        $this->loan->refresh();
        $this->assertEquals('Dikembalikan', $this->loan->status);

        $return = BookReturn::where('loan_id', $this->loan->id)->first();
        $this->assertNotNull($return);
        $this->assertEquals(3, $return->late_days);
        $this->assertEquals(3000, $return->fine_amount); // 3 hari * 1000
        $this->assertEquals('Belum Lunas', $return->fine_status);
    }

    public function test_pengunjung_can_only_view_own_returns_and_cannot_create_or_modify(): void
    {
        $otherUser = User::factory()->create(['role' => 'Pengunjung']);
        $otherLoan = Loan::create([
            'user_id' => $otherUser->id,
            'book_id' => $this->book->id,
            'loan_date' => Carbon::today()->subDays(5)->toDateString(),
            'due_date' => Carbon::today()->addDays(2)->toDateString(),
            'status' => 'Dikembalikan',
        ]);

        $myReturn = BookReturn::create([
            'loan_id' => $this->loan->id,
            'return_date' => Carbon::today()->toDateString(),
            'late_days' => 0,
            'fine_amount' => 0,
            'condition' => 'Baik',
            'fine_status' => 'Bebas Denda',
            'notes' => 'Pengembalian Budi',
        ]);

        $otherReturn = BookReturn::create([
            'loan_id' => $otherLoan->id,
            'return_date' => Carbon::today()->toDateString(),
            'late_days' => 0,
            'fine_amount' => 0,
            'condition' => 'Baik',
            'fine_status' => 'Bebas Denda',
            'notes' => 'Pengembalian Rahasia Other',
        ]);

        // Pengunjung view index: can only see own return di /user/pengembalian
        $response = $this->actingAs($this->user)->get('/user/pengembalian');
        $response->assertStatus(200);
        $response->assertSee($this->loan->book->title);
        $response->assertDontSee('Pengembalian Rahasia Other');

        // Pengunjung access admin returns ditolak
        $this->actingAs($this->user)->get('/admin/pengembalian')->assertStatus(403);

        // Pengunjung cannot store return
        $this->actingAs($this->user)->post('/admin/pengembalian', [
            'loan_id' => $this->loan->id,
            'return_date' => Carbon::today()->toDateString(),
            'condition' => 'Baik',
        ])->assertStatus(403);

        // Pengunjung cannot update return
        $this->actingAs($this->user)->put('/admin/pengembalian/' . $myReturn->id, [
            'fine_status' => 'Lunas',
        ])->assertStatus(403);

        // Pengunjung cannot delete return
        $this->actingAs($this->user)->delete('/admin/pengembalian/' . $myReturn->id)->assertStatus(403);
    }
}
