# TODO Implementasi 3 Fitur

## ✅ = Selesai | 🟡 = Sedang Dikerjakan | ⬜ = Belum

> **FIX:** Gambar tidak muncul di halaman kelola event ✅
> - Root cause: Stray karakter 'a' sebelum `<?php` di Event.php + poster_path tidak cocok path aset
> - Fix: Hapus stray char, perbaiki `getPosterUrlAttribute()` prioritaskan `public/assets/`, copy aset, refresh seed

---

## Fitur 1: SSO Login via Google (Laravel Socialite)

### Tahap 1.1 - Setup Socialite
- [x] Install `laravel/socialite` via composer
- [x] Update `config/services.php` dengan konfigurasi Google
- [ ] Update `.env` dengan variabel Google OAuth

### Tahap 1.2 - Database Migration
- [x] Buat migration tambah kolom `provider`, `provider_id`, `avatar` ke users
- [x] Buat `password` nullable
- [x] Jalankan migration

### Tahap 1.3 - Controller & Routes
- [x] Buat `Auth/SocialiteController.php`
- [x] Update routes web.php

### Tahap 1.4 - Frontend
- [x] Update `auth/login.blade.php` - tambah tombol "Continue with Google"
- [x] Update checkout form - prefilled data jika user login via Google

---

## Fitur 2: Rating & Review System

### Tahap 2.1 - Database
- [x] Buat migration tabel `reviews`
- [x] Buat Model `Review.php`
- [x] Jalankan migration

### Tahap 2.2 - Controller & Routes
- [x] Buat `ReviewController.php`
- [x] Update routes

### Tahap 2.3 - Model Relationships
- [x] Update `Event.php` - tambah relasi reviews & averageRating()
- [x] Update `User.php` - tambah relasi reviews()

### Tahap 2.4 - Frontend
- [x] Update `event-detail.blade.php` - tampilkan rating & form review

---

## Fitur 3: Multi-Tenant / Organizer Architecture

### Tahap 3.1 - Database
- [x] Buat migration tabel `organizations`
- [x] Migration alter events: tambah `organization_id`
- [x] Migration alter users: tambah `organization_id`
- [x] Jalankan migration

### Tahap 3.2 - Model
- [x] Buat Model `Organization.php`
- [x] Update `User.php` - tambah relasi organization, isOrganizer(), isSuperAdmin()
- [x] Update `Event.php` - tambah relasi organization()

### Tahap 3.3 - Middleware & Controller
- [x] Update `AdminMiddleware.php` - tenant-aware
- [x] Update `DashboardController.php` - filter by tenant
- [x] Update Admin `EventController.php` - filter by tenant
- [x] Buat `OrganizerMiddleware.php`
- [x] Buat `OrganizerController.php` (superadmin manage organizers)
- [x] Register middleware di `bootstrap/app.php`

### Tahap 3.4 - Views
- [x] Update `layouts/admin.blade.php` - sidebar dengan info organisasi
- [x] Update `admin/dashboard.blade.php` - tampilkan berdasarkan organisasi
- [x] Buat halaman manage organizer untuk superadmin (`admin/organizers/*`)
- [x] Buat dashboard khusus organizer (`organizer-dashboard.blade.php`)

### Tahap 3.5 - Seeder & Routes
- [x] Update `DatabaseSeeder.php` - tambah sample organizations & organizers
- [x] Update routes web.php untuk superadmin

---
