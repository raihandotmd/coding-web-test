# web-app_test

Repo berisi dua hal yang dikerjakan terpisah:

1. `testCoding.go` - kumpulan jawaban soal coding test (Go).
2. `simple-cms-test/` - aplikasi Laravel: login + CRUD user dengan upload foto profil.

## 1. testCoding.go

Empat fungsi sebagai jawaban soal test (deskripsi soal ada di komentar tiap fungsi):

- `zeroThreeSum(arr)` - cari tiga angka di array yang totalnya 0. Brute force tiga loop. Return slice angka kalau ketemu, string `"Not Found"` kalau tidak.
- `uniqueArray(arr)` - buang duplikat tanpa pakai helper bawaan. Pakai map sebagai set.
- `sortEveryFive(arr)` - sort ascending pakai bubble sort, lalu rakit ulang ambil 5 angka dari kiri, 5 dari kanan, bergantian. Tidak pakai library sort.
- `isSymmetric(s)` - cek palindrom dengan dua pointer. Tidak pakai `strings.Reverse` atau sejenisnya.

Semua dipanggil di `main()` dengan test case dari soal.

Jalankan:

```bash
go run testCoding.go
```

## 2. simple-cms-test

Aplikasi Laravel 13 sederhana: halaman login, dashboard list user, tambah/edit/hapus user, upload foto profil.

### Stack

- Laravel 13
- Blade untuk view
- Auth pakai session bawaan Laravel (`Auth::attempt`) — tanpa Fortify/Livewire/Boost
- Frontend via CDN: Bootstrap 5.3.8, jQuery 4.0.0, DataTables 2.3.8
- SQLite sebagai database default

### Yang sudah ada

- **Auth** - `AuthController` handle login/logout. Login dikerjakan via AJAX, response JSON. Gagal login balikin 401 dengan pesan.
- **User CRUD** - `UserController`:
  - `index` - render halaman list
  - `data` - endpoint server-side untuk DataTables (search + pagination + ordering)
  - `store` / `update` / `destroy` - semua return JSON, dipanggil dari modal jQuery
  - upload foto disimpan di `storage/app/public/profiles` via disk `public`
- **Migrasi tambahan** - `2026_05_08_002811_add_profile_image_to_users.php` menambahkan kolom `profile_image` (nullable string) ke tabel `users`.
- **Routes** (`routes/web.php`) - root redirect ke `/users`. Semua route user di-protect middleware `auth`.
- **Layout** (`resources/views/layouts/app.blade.php`) - load Bootstrap + jQuery + DataTables dari CDN, set `X-CSRF-TOKEN` default untuk semua AJAX request.

### Setup

```bash
cd simple-cms-test
composer install
cp .env.example .env
php artisan key:generate
mkdir database/database.sqlite
php artisan migrate
php artisan storage:link
npm install
composer run dev
```