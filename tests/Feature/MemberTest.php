<?php

namespace Tests\Feature;

use App\Models\User;
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
            'role' => 'Pengunjung',
            'status' => 'Aktif',
        ]);
    }

    public function test_guest_cannot_access_members_page(): void
    {
        $response = $this->get('/admin/data-pengunjung');
        $response->assertRedirect('/login');
    }

    public function test_pengunjung_cannot_access_or_crud_members_page(): void
    {
        $this->actingAs($this->member)->get('/admin/data-pengunjung')->assertStatus(403);
        $this->actingAs($this->member)->post('/admin/data-pengunjung', ['name' => 'Test'])->assertStatus(403);
        $this->actingAs($this->member)->put("/admin/data-pengunjung/{$this->member->id}", ['name' => 'Test'])->assertStatus(403);
        $this->actingAs($this->member)->delete("/admin/data-pengunjung/{$this->member->id}")->assertStatus(403);
    }

    public function test_authenticated_user_can_view_members_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/data-pengunjung');

        $response->assertStatus(200);
        $response->assertSee('Data Pengunjung Perpustakaan');
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
            'role' => 'Pengunjung',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/data-pengunjung?search=informatika');
        $response->assertStatus(200);
        $response->assertSee('Lintang Maulana');
        $response->assertDontSee('Siti Nurhaliza');

        $responseStatus = $this->actingAs($this->admin)->get('/admin/data-pengunjung?status=Nonaktif');
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

        $response = $this->actingAs($this->admin)->post('/admin/data-pengunjung', $payload);

        $response->assertRedirect('/admin/data-pengunjung');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad@kampus.ac.id',
            'role' => 'Pengunjung',
        ]);
    }

    public function test_can_update_member_and_reset_password(): void
    {
        $payload = [
            'name' => 'Lintang Maulana Updated',
            'member_id' => $this->member->member_id,
            'email' => 'lintang_new@kampus.ac.id',
            'department' => 'Teknik Komputer',
            'phone' => '081299998888',
            'status' => 'Nonaktif',
            'reset_password' => '1',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/data-pengunjung/{$this->member->id}", $payload);

        $response->assertRedirect('/admin/data-pengunjung');
        $response->assertSessionHas('success');

        $this->member->refresh();
        $this->assertEquals('Lintang Maulana Updated', $this->member->name);
        $this->assertEquals('lintang_new@kampus.ac.id', $this->member->email);
        $this->assertEquals('Nonaktif', $this->member->status);
        $this->assertTrue(Hash::check('password123', $this->member->password));
    }

    public function test_user_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->admin)->delete("/admin/data-pengunjung/{$this->admin->id}");

        $response->assertRedirect('/admin/data-pengunjung');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
        ]);
    }

    public function test_cannot_delete_member_with_active_loans(): void
    {
        $book = \App\Models\Book::create([
            'title' => 'Test Book',
            'author' => 'Author',
            'isbn' => '978-000-0000-01-1',
            'category' => 'Test',
            'stock' => 5,
            'available' => 4,
        ]);

        \App\Models\Loan::create([
            'user_id' => $this->member->id,
            'book_id' => $book->id,
            'loan_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => 'Dipinjam',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/data-pengunjung/{$this->member->id}");

        $response->assertRedirect('/admin/data-pengunjung');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $this->member->id,
        ]);
    }

    public function test_can_delete_member_without_active_loans(): void
    {
        $response = $this->actingAs($this->admin)->delete("/admin/data-pengunjung/{$this->member->id}");

        $response->assertRedirect('/admin/data-pengunjung');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $this->member->id,
        ]);
    }
}
