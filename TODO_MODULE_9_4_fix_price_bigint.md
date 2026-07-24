# TODO Modul 9.4 - Perbaikan Error price out of range

- [ ] Ubah tipe kolom `events.price` dari `integer` ke `bigInteger` via migration baru.
- [ ] (Opsional tapi disarankan) Tambahkan validasi `max` atau biarkan karena sudah BIGINT.
- [ ] Jalankan `php artisan migrate`.
- [ ] Uji ulang:
  - [ ] Simpan event dengan `price` besar (contoh 2345678888) + poster upload.
  - [ ] Pastikan tidak ada lagi `SQLSTATE[22003]`.
  - [ ] Pastikan fitur hapus poster saat event delete masih berfungsi.

