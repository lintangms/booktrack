<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Alya Prameswari',
            'email' => 'admin@booktrack.id',
            'member_id' => 'ADM-001',
            'role' => 'Administrator',
            'status' => 'Aktif',
        ]);

        $this->member = User::factory()->create([
            'name' => 'Lintang Maulana',
            'email' => 'lintang@kampus.ac.id',
            'member_id' => '231104028',
            'department' => 'Teknik Informatika',
            'phone' => '08123456789',
            'role' => 'Anggota',
            'status' => 'Aktif',
        ]);
    }

    public function test_guest_cannot_access_members_page(): void
    {
        $response = $this->get('/data-anggota');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_members_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/data-anggota');

        $response->assertStatus(200);
        $response->assertSee('Data Anggota Perpustakaan');
        $response->assertSee('Lintang Maulana');
        $response->assertSee('231104028');
        $response->assertSee('Teknik Informatika');
    }

    public function test_can_search_and_filter_members(): void
    {
        User::factory()->create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@kampus.ac.id',
            'member_id' => '231105555',
            'department' => 'Sistem Informasi',
            'status' => 'Nonaktif',
        ]);

        $response = $this->actingAs($this->admin)->get('/data-anggota?search=informatika');
        $response->assertStatus(200);
        $response->assertSee('Lintang Maulana');
        $response->assertDontSee('Siti Nurhaliza');

        $responseStatus = $this->actingAs($this->admin)->get('/data-anggota?status=Nonaktif');
        $responseStatus->assertStatus(200);
        $responseStatus->assertSee('Siti Nurhaliza');
    }

    public function test_can_store_new_member(): void
    {
        $payload = [
            'name' => 'Ahmad Dahlan',
            'member_id' => '231108888',
            'email' => 'ahmad@kampus.ac.id',
            'department' => 'Ilmu Komunikasi',
            'phone' => '08987654321',
            'status' => 'Aktif',
        ];

        $response = $this->actingAs($this->admin)->post('/data-anggota', $payload);

        $response->assertRedirect('/data-anggota');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Ahmad Dahlan',
            'member_id' => '231108888',
            'email' => 'ahmad@kampus.ac.id',
            'department' => 'Ilmu Komunikasi',
            'phone' => '08987654321',
            'status' => 'Aktif',
            'role' => 'Anggota',
        ]);

        $created = User::where('email', 'ahmad@kampus.ac.id')->first();
        $this->assertTrue(Hash::check('password123', $created->password));
    }

    public function test_can_update_member_and_reset_password(): void
    {
        $payload = [
            'name' => 'Lintang Maulana Updated',
            'member_id' => '231104028',
            'email' => 'lintang_new@kampus.ac.id',
            'department' => 'Sains Data',
            'phone' => '08111222333',
            'status' => 'Nonaktif',
            'reset_password' => '1',
        ];

        $response = $this->actingAs($this->admin)->put("/data-anggota/{$this->member->id}", $payload);

        $response->assertRedirect('/data-anggota');
        $response->assertSessionHas('success');

        $this->member->refresh();
        $this->assertEquals('Lintang Maulana Updated', $this->member->name);
        $this->assertEquals('lintang_new@kampus.ac.id', $this->member->email);
        $this->assertEquals('Sains Data', $this->member->department);
        $this->assertEquals('Nonaktif', $this->member->status);
        $this->assertTrue(Hash::check('password123', $this->member->password));
    }

    public function test_user_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->admin)->delete("/data-anggota/{$this->admin->id}");

        $response->assertRedirect('/data-anggota');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
        ]);
    }

    public function test_cannot_delete_member_with_active_loans(): void
    {
        $book = Book::create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'isbn' => '978-0132350884',
            'category' => 'Teknologi',
            'stock' => 3,
            'available' => 2,
            'theme' => 'cover-teal',
        ]);

        Loan::create([
            'user_id' => $this->member->id,
            'book_id' => $book->id,
            'loan_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(7),
            'status' => 'Dipinjam',
        ]);

        $response = $this->actingAs($this->admin)->delete("/data-anggota/{$this->member->id}");

        $response->assertRedirect('/data-anggota');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $this->member->id,
        ]);
    }

    public function test_can_delete_member_without_active_loans(): void
    {
        $response = $this->actingAs($this->admin)->delete("/data-anggota/{$this->member->id}");

        $response->assertRedirect('/data-anggota');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $this->member->id,
        ]);
    }
}
