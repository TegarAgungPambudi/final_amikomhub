# TODO - Fix Midtrans item_details sesuai jumlah tiket (quantity)

## Progress
- [x] Tambah field `quantity` ke tabel `transactions` via migration `2026_07_04_000001_add_quantity_to_transactions_table.php`
- [x] Tambah `quantity` ke `Transaction::$fillable`
- [x] Simpan `quantity` saat create transaksi di `PaymentController@processCheckout`
- [x] Update `PaymentService@createSnapToken` agar `item_details.quantity` memakai `transaction->quantity`

## Remaining (to verify)
1) Jalankan migration: `php artisan migrate`
2) Checkout 3 tiket → pastikan Snap menampilkan quantity 3 dan/atau nilai item sesuai
3) Lunas sampai settlement/success → cek status transaksi sukses di admin dashboard
4) Screenshot dashboard + bukti webhook (sesuai tugas)

## Notes
- VSCode Intelephense mungkin masih menampilkan warning terkait `Log`/type—tapi ini tidak selalu berarti error runtime.

