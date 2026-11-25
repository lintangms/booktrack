<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTrackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('BookTrack');
        $response->assertSee('Masuk ke Akun');
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Selamat datang kembali');
    }

    public function test_user_can_login_and_access_dashboard(): void
    {
        $user = User::where('email', 'alya@booktrack.id')->first();
        $this->assertNotNull($user);

        $response = $this->post('/login', [
            'email' => 'alya@booktrack.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);

        $dashboardResponse = $this->actingAs($user)->get('/admin/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Alya Prameswari');
        $dashboardResponse->assertSee('Total Buku');
        $dashboardResponse->assertSee('Aktivitas Peminjaman');
    }

    public function test_books_page_shows_collection_and_allows_creation(): void
    {
        $user = User::where('email', 'alya@booktrack.id')->first();

        $response = $this->actingAs($user)->get('/admin/data-buku');
        $response->assertStatus(200);
        $response->assertSee('Data Buku');
        $response->assertSee('Laskar Pelangi');
        $response->assertSee('Bumi Manusia');

        // Test tambah buku
        $newBookIsbn = '978-602-0000-00-1';
        $createResponse = $this->actingAs($user)->post('/admin/data-buku', [
            'title' => 'Negeri 5 Menara',
            'author' => 'Ahmad Fuadi',
            'isbn' => $newBookIsbn,
            'category' => 'Fiksi',
            'stock' => 15,
            'available' => 15,
            'theme' => 'cover-teal',
        ]);

        $createResponse->assertRedirect('/admin/data-buku');
        $this->assertDatabaseHas('books', ['isbn' => $newBookIsbn]);

        // Test update buku
        $createdBook = Book::where('isbn', $newBookIsbn)->first();
        $updateResponse = $this->actingAs($user)->put('/admin/data-buku/' . $createdBook->id, [
            'title' => 'Negeri 5 Menara (Edisi Khusus)',
            'author' => 'Ahmad Fuadi',
            'isbn' => $newBookIsbn,
            'category' => 'Novel Inspiratif',
            'stock' => 20,
            'available' => 20,
        ]);
        $updateResponse->assertRedirect('/admin/data-buku');
        $this->assertDatabaseHas('books', [
            'id' => $createdBook->id,
            'title' => 'Negeri 5 Menara (Edisi Khusus)',
            'stock' => 20,
        ]);

        // Test delete buku
        $deleteResponse = $this->actingAs($user)->delete('/admin/data-buku/' . $createdBook->id);
        $deleteResponse->assertRedirect('/admin/data-buku');
        $this->assertDatabaseMissing('books', ['id' => $createdBook->id]);
    }

    public function test_profile_page_can_be_viewed_and_updated(): void
    {
        $user = User::where('email', 'alya@booktrack.id')->first();

        $response = $this->actingAs($user)->get('/profil');
        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
        $response->assertSee($user->name);

        $updateResponse = $this->actingAs($user)->post('/profil', [
            'fullName' => 'Alya Prameswari, S.IIP.',
            'emailAddress' => 'alya@booktrack.id',
        ]);

        $updateResponse->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Alya Prameswari, S.IIP.',
        ]);
    }

    public function test_pengunjung_cannot_add_or_modify_books(): void
    {
        $pengunjung = User::factory()->create([
            'role' => 'Pengunjung',
        ]);

        $book = Book::first();

        // Pengunjung lihat katalog diperbolehkan di /user/data-buku
        $this->actingAs($pengunjung)->get('/user/data-buku')->assertStatus(200);

        // Pengunjung mengakses area admin ditolak
        $this->actingAs($pengunjung)->get('/admin/data-buku')->assertStatus(403);

        // Pengunjung tambah buku di admin ditolak
        $this->actingAs($pengunjung)->post('/admin/data-buku', [
            'title' => 'Buku Terlarang',
            'author' => 'Anonim',
            'isbn' => '999-999-9999-99-9',
            'category' => 'Umum',
            'stock' => 5,
        ])->assertStatus(403);

        // Pengunjung ubah buku ditolak
        if ($book) {
            $this->actingAs($pengunjung)->put('/admin/data-buku/' . $book->id, [
                'title' => 'Judul Baru',
                'author' => 'Penulis Baru',
                'isbn' => $book->isbn,
                'category' => $book->category,
                'stock' => 10,
                'available' => 10,
            ])->assertStatus(403);

            // Pengunjung hapus buku ditolak
            $this->actingAs($pengunjung)->delete('/admin/data-buku/' . $book->id)->assertStatus(403);
        }
    }
}
