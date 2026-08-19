<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun demo mahasiswa
        $user = User::updateOrCreate(
            ['nim' => '202401001'],
            [
                'name' => 'Budi Pratama',
                'email' => 'budi@student.ac.id',
                'password' => Hash::make('password123'),
            ]
        );

        // Buat beberapa contoh tugas awal untuk akun demo
        if ($user->tasks()->count() === 0) {
            $user->tasks()->createMany([
                [
                    'title' => 'Makalah Perancangan Basis Data Relasional',
                    'description' => 'Mengerjakan ERD dan normalisasi 3NF untuk sistem akademik kampus.',
                    'deadline' => now()->addDays(3)->toDateString(),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Tugas Coding Laravel Framework',
                    'description' => 'Membuat fitur CRUD dan autentikasi user untuk Student Task Manager.',
                    'deadline' => now()->addDays(7)->toDateString(),
                    'status' => 'completed',
                ],
                [
                    'title' => 'Review Jurnal Rekayasa Perangkat Lunak',
                    'description' => 'Membuat ringkasan metodologi agile scrum pada jurnal IEEE terindeks.',
                    'deadline' => now()->addDays(5)->toDateString(),
                    'status' => 'pending',
                ],
            ]);
        }
    }
}
