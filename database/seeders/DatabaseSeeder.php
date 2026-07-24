<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Partner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===== MULTI-TENANT: Create Organizations =====
        $orgHimaSi = Organization::firstOrCreate(
            ['slug' => 'hima-si'],
            [
                'name' => 'HIMA Sistem Informasi',
                'description' => 'Himpunan Mahasiswa Sistem Informasi Universitas Amikom',
                'is_active' => true,
            ]
        );

        $orgHimaTi = Organization::firstOrCreate(
            ['slug' => 'hima-ti'],
            [
                'name' => 'HIMA Teknik Informatika',
                'description' => 'Himpunan Mahasiswa Teknik Informatika Universitas Amikom',
                'is_active' => true,
            ]
        );

        $orgBem = Organization::firstOrCreate(
            ['slug' => 'bem-amikom'],
            [
                'name' => 'BEM Universitas Amikom',
                'description' => 'Badan Eksekutif Mahasiswa Universitas Amikom',
                'is_active' => true,
            ]
        );

        // ===== 1. Akun Admin Utama =====
        $admin = User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            [
                'name' => 'Admin Amikom',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // ===== 2. Akun Organizer untuk setiap HIMA =====
        $orgSiUser = User::firstOrCreate(
            ['email' => 'hima.si@amikom.ac.id'],
            [
                'name' => 'Ketua HIMA SI',
                'password' => bcrypt('password'),
                'role' => 'organizer',
                'organization_id' => $orgHimaSi->id,
            ]
        );

        $orgTiUser = User::firstOrCreate(
            ['email' => 'hima.ti@amikom.ac.id'],
            [
                'name' => 'Ketua HIMA TI',
                'password' => bcrypt('password'),
                'role' => 'organizer',
                'organization_id' => $orgHimaTi->id,
            ]
        );

        $orgBemUser = User::firstOrCreate(
            ['email' => 'bem@amikom.ac.id'],
            [
                'name' => 'Ketua BEM',
                'password' => bcrypt('password'),
                'role' => 'organizer',
                'organization_id' => $orgBem->id,
            ]
        );

        // Update owner organizations
        $orgHimaSi->update(['owner_id' => $orgSiUser->id]);
        $orgHimaTi->update(['owner_id' => $orgTiUser->id]);
        $orgBem->update(['owner_id' => $orgBemUser->id]);
            
        // ===== 3. Insert Kategori Event =====
        $catSeminar = Category::firstOrCreate(
            ['slug' => 'seminar-it'],
            ['name' => 'Seminar IT']
        );

        $catEntertainment = Category::firstOrCreate(
            ['slug' => 'entertaiment'],
            ['name' => 'Entertaiment']
        );

        $catWorkshop = Category::firstOrCreate(
            ['slug' => 'workshop'],
            ['name' => 'Workshop']
        );
            
        // ===== 4. Insert Events untuk setiap organisasi (tanggal FUTURE) =====
        Event::updateOrCreate(
            ['title' => 'Jazz Night 2025'],
            [
                'organization_id' => $orgBem->id,
                'category_id' => $catEntertainment->id,
                'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.',
                'date' => now()->addDays(30)->format('Y-m-d') . ' 19:00:00',
                'location' => 'Amikom Baru',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => 'lamteng.png',
            ]
        );

        Event::updateOrCreate(
            ['title' => 'Hackaton - Unleash Your Inner Developer'],
            [
                'organization_id' => $orgHimaTi->id,
                'category_id' => $catSeminar->id,
                'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif untuk tantangan masa depan!',
                'date' => now()->addDays(45)->format('Y-m-d') . ' 10:00:00',
                'location' => 'Inkubator Amikom',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => 'hackathon.png',
            ]
        );

        Event::updateOrCreate(
            ['title' => 'AI & FUTURE TECH SUMMIT 2026'],
            [
                'organization_id' => $orgHimaSi->id,
                'category_id' => $catSeminar->id,
                'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',
                'date' => now()->addDays(60)->format('Y-m-d') . ' 13:00:00',
                'location' => 'Cinema Unit 6',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => 'workshop.png',
            ]
        );

        Event::updateOrCreate(
            ['title' => 'Workshop UI/UX Design'],
            [
                'organization_id' => $orgHimaSi->id,
                'category_id' => $catWorkshop->id,
                'description' => 'Pelajari desain UI/UX dari praktisi industri terkemuka.',
                'date' => now()->addDays(90)->format('Y-m-d') . ' 09:00:00',
                'location' => 'Lab Komputer 3',
                'price' => 35000,
                'stock' => 50,
                'poster_path' => 'selarasa.png',
            ]
        );

        // ===== 5. Seed Partners jika table masih kosong =====
        if (Partner::count() === 0) {
            $this->call(PartnerSeeder::class);
        }
    }
}
