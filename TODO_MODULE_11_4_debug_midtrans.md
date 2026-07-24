# Debug Midtrans: kenapa langsung sukses tanpa popup

## 1) Pastikan mode demo mati
- Periksa `.env`:
  - MIDTRANS_IS_DEMO=false
  - MIDTRANS_IS_PRODUCTION=false
  - MIDTRANS_SERVER_KEY=...
  - MIDTRANS_CLIENT_KEY=...
- Setelah perubahan:
  - php artisan config:clear

## 2) Pastikan frontend memilih cabang non-demo
File: `resources/views/checkout.blade.php`
- Cabang demo hanya terjadi jika: `config('midtrans.is_demo') == true`
- Non-demo harus mengarah ke: `window.location.href = data.redirect_url`

## 3) Verifikasi response dari POST /checkout/{event}/process
Dengan DevTools Network:
- Jika demo benar aktif, response akan punya:
  - is_demo: true
  - snap_token: "DEMO-TOKEN-..."
- Jika non-demo benar, response akan punya:
  - is_demo: false/tdk ada
  - snap_token: "SB-..." atau token midtrans
  - redirect_url berupa: /payment/{order_id}

## 4) Saat /payment/{order_id} terbuka
- Pastikan popup midtrans.js ter-load
- Pastikan `snap.pay(transaction.snap_token)` dijalankan

## 5) Jika masih gagal
- Cek console error (mis. 401 invalid key, CORS, snap.pay undefined)
- Kirim log/console ke saya untuk analisis lanjutan.

