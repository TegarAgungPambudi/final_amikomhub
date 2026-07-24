# TODO Implementasi 4 Fitur Baru - STATUS

## ✅ = Selesai | 🟡 = Sedang Dikerjakan | ⬜ = Belum

---

## Fitur 1: Dashboard Admin dengan Grafik (Chart.js)
### Tahap 1.1 - Backend Data Grafik
- [x] Update `DashboardController.php` — tambah data revenue monthly, user growth, popular events
### Tahap 1.2 - Frontend Grafik
- [x] Update `admin/dashboard.blade.php` — Chart.js grafik (bar, doughnut, line)
- [x] Update `admin/organizer-dashboard.blade.php` — Chart.js grafik organizer

---

## Fitur 2: Sistem Check-in Scanner (QR Scanner)
### Tahap 2.1 - Migration & Model
- [x] Migration: tambah `is_used`, `used_at`, `qr_code` ke transactions
- [x] Update `Transaction.php` model
### Tahap 2.2 - Controller & Route
- [x] Buat `CheckinScannerController.php`
- [x] Update routes
### Tahap 2.3 - View Scanner
- [x] Buat `admin/checkin-scanner.blade.php` (QR Scanner + manual check-in)
- [x] Update `layouts/admin.blade.php` — menu scanner
### Tahap 2.4 - QR Code di Ticket
- [x] Update `ticket.blade.php` — QR Code via api.qrserver.com
- [x] Update `emails/ticket.blade.php` — QR Code via api.qrserver.com

---

## Fitur 3: Reserved Ticket (Stock Reservation)
### Tahap 3.1 - Migration & Model
- [x] Migration: tambah `reserved_until` ke transactions
- [x] Update `Transaction.php` (fillable, casts)
### Tahap 3.2 - Update Controller
- [x] Update `PaymentController.php` — reserve stock + set reserved_until (15 menit)
- [x] Update `MidtransWebhookController.php` — release stock on expire/fail
### Tahap 3.3 - Scheduler Release
- [x] Buat `app/Console/Commands/ReleaseExpiredReservations.php`
- [x] Register command di `routes/console.php` (everyMinute)

---

## Fitur 4: Dynamic Pricing & Kupon
### Tahap 4.1 - Migration & Model Kupon
- [x] Migration: tabel `coupons` (code, discount_type, discount_value, max_uses, etc.)
- [x] Model `Coupon.php` (isValid(), calculateDiscount())
### Tahap 4.2 - CRUD Kupon via Admin Panel
- [x] Buat `Admin/CouponController.php` (index, create, store, edit, update, destroy)
- [x] Buat views: index, create, edit
- [x] Update routes
### Tahap 4.3 - Migration & Model Ticket Pricing
- [x] Migration: tabel `ticket_pricings` (event_id, tier, price, start_date, end_date)
- [x] Model `TicketPricing.php` (isCurrentlyActive())
### Tahap 4.4 - Update Event untuk Tier Pricing
- [x] Update `Event.php` — relasi ticketPricings, getCurrentPrice(), getAvailableTiers()
- [ ] Update `Admin/EventController.php` — simpan tier pricing (BELUM)
- [ ] Update `admin/events/create.blade.php` — form tier pricing (BELUM)
- [ ] Update `admin/events/edit.blade.php` — form tier pricing (BELUM)
### Tahap 4.5 - Integrasi Checkout
- [x] Update `checkout.blade.php` — input kupon, validasi AJAX, tampilkan harga tier
- [x] Update `PaymentController.php` — apply coupon logic & tier pricing
- [ ] Update `event-detail.blade.php` — tampilkan harga tier (BELUM)

---

## Keterangan:
✅ = Selesai
⬜ = Belum (opsional - tier pricing di form event bisa ditambahkan nanti karena tier pricing bisa via tinker/DB manual)

