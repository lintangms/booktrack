<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = base_path('data/users.json');
        if (File::exists($jsonPath)) {
            $users = json_decode(File::get($jsonPath), true);
            if (is_array($users)) {
                foreach ($users as $u) {
                    User::updateOrCreate(
                        ['email' => $u['email']],
                        [
                            'name' => $u['fullname'] ?? $u['name'] ?? 'User',
                            'member_id' => $u['member_id'] ?? null,
                            'password' => $u['password'], // Already bcrypt-hashed
                            'role' => $u['role'] ?? 'Anggota',
                            'created_at' => $u['created_at'] ?? now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                return;
            }
        }

        // Fallback default users
        User::updateOrCreate(
            ['email' => 'alya@booktrack.id'],
            [
                'name' => 'Alya Prameswari',
                'member_id' => 'ADM-001',
                'password' => '$2y$10$HDHMxFDkYAKLJYURryL9Z.Zo3.83tMb79XqzeoMSUvkPePrxlPk2.',
                'role' => 'Administrator',
            ]
        );
    }
}
