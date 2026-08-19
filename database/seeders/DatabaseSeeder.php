<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat atau dapatkan akun demo mahasiswa
        $user = User::updateOrCreate(
            ['nim' => '202401001'],
            [
                'name' => 'Budi Pratama',
                'email' => 'budi@student.ac.id',
                'password' => Hash::make('password123'),
            ]
        );

        // Buat Mata Kuliah untuk user
        $courseWeb = Course::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Pemrograman Web Lanjut'],
            ['code' => 'IF-301', 'lecturer' => 'Dr. Hendra, M.Kom.', 'color' => 'indigo']
        );

        $courseDB = Course::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Sistem Basis Data'],
            ['code' => 'IF-202', 'lecturer' => 'Ir. Siti Rahma, M.T.', 'color' => 'emerald']
        );

        $courseRPL = Course::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Rekayasa Perangkat Lunak'],
            ['code' => 'IF-304', 'lecturer' => 'Agus Susanto, M.Cs.', 'color' => 'purple']
        );

        $courseMath = Course::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Kalkulus & Aljabar'],
            ['code' => 'MA-102', 'lecturer' => 'Dra. Sri Wahyuni, M.Si.', 'color' => 'rose']
        );

        $courseJarkom = Course::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Jaringan Komputer'],
            ['code' => 'IF-208', 'lecturer' => 'Bambang Wijaya, M.Kom.', 'color' => 'sky']
        );

        // Bersihkan tugas lama untuk diisi dengan data contoh yang kaya relasi
        $user->tasks()->delete();

        // Buat tugas-tugas contoh dengan konteks akademik
        $user->tasks()->createMany([
            [
                'course_id' => $courseWeb->id,
                'title' => 'Project Akhir: Sistem Manajemen Tugas Mahasiswa',
                'description' => 'Membangun aplikasi web Laravel dengan Blade, Tailwind CSS, dan autentikasi NIM.',
                'deadline' => Carbon::today()->addDays(1)->toDateString(),
                'status' => 'pending',
                'priority' => 'high',
            ],
            [
                'course_id' => $courseDB->id,
                'title' => 'Tugas ERD & Normalisasi Database Toko Online',
                'description' => 'Desain skema tabel database hingga bentuk normal ketiga (3NF) dan query SQL.',
                'deadline' => Carbon::today()->addDays(4)->toDateString(),
                'status' => 'pending',
                'priority' => 'medium',
            ],
            [
                'course_id' => $courseRPL->id,
                'title' => 'Review Jurnal Metodologi Scrum vs Waterfall',
                'description' => 'Membuat resume komparasi metodologi pengembangan perangkat lunak 5 halaman PDF.',
                'deadline' => Carbon::today()->subDays(1)->toDateString(),
                'status' => 'pending',
                'priority' => 'high',
            ],
            [
                'course_id' => $courseMath->id,
                'title' => 'Latihan Soal Integral Lipat & Matriks Vektor',
                'description' => 'Mengerjakan soal bab 4 nomor 1-15 pada buku teks kalkulus purcell.',
                'deadline' => Carbon::today()->addDays(6)->toDateString(),
                'status' => 'pending',
                'priority' => 'low',
            ],
            [
                'course_id' => $courseJarkom->id,
                'title' => 'Simulasi Subnetting IPv4 di Cisco Packet Tracer',
                'description' => 'Konfigurasi topologi LAN 3 router dan 4 switch dengan routing OSPF.',
                'deadline' => Carbon::today()->addDays(10)->toDateString(),
                'status' => 'completed',
                'priority' => 'medium',
            ],
        ]);
    }
}
