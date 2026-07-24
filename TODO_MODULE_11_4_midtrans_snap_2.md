# TODO MODULE 11.4 Midtrans Snap (Opsi 2)

## Langkah 1: Setup credentials (.env)
- [ ] Tambahkan MIDTRANS_SERVER_KEY, MIDTRANS_CLIENT_KEY, MIDTRANS_IS_PRODUCTION=false ke file `.env`.
- [ ] Pastikan MIDTRANS_IS_DEMO=false agar snap/popup aktif.

## Langkah 2: Pastikan config midtrans berjalan
- [ ] Pastikan `config/midtrans.php` membaca env (server_key, client_key, is_production).
- [ ] (Opsional) rapikan config agar tidak override demo.

## Langkah 3: Install library Midtrans-PHP
- [ ] Jalankan `composer require midtrans/midtrans-php`.

## Langkah 4: Modifikasi flow checkout (generate snap token + redirect ke popup)
- [ ] Update `PaymentController@processCheckout` untuk:
  - [ ] generate snap token menggunakan `PaymentService::createSnapToken`
  - [ ] simpan `snap_token` ke tabel `transactions` (sudah dilakukan di PaymentService)
  - [ ] redirect ke `route('checkout.payment', $transaction->order_id)`.

## Langkah 5: Buat controller untuk popup & success (masih di PaymentController)
- [ ] Tambahkan method `PaymentController@payment($order_id)` untuk render view popup.
- [ ] Buat route `checkout.payment`.
- [ ] Buat view `resources/views/checkout/payment.blade.php` (sesuai modul).

- [ ] Tambahkan method `PaymentController@success($order_id)` untuk validasi status dari Midtrans.
- [ ] Buat route `checkout.success`.
- [ ] Buat view `resources/views/checkout/success.blade.php`.

## Langkah 6: Bersihkan cache konfigurasi
- [ ] Jalankan `php artisan config:clear`.

## Langkah 7: Validasi manual (11.5)
- [ ] Simulasikan checkout di browser:
  - [ ] submit form
  - [ ] popup Midtrans muncul
  - [ ] pilih BCA VA / GoPay sandbox
  - [ ] sukses diarahkan ke success page
  - [ ] transaction status berubah menjadi `success/settlement`.

