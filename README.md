# PBKK — Personal Website (Tugas 4 & Tugas 10)

NRP: **5025241089** · S1 Teknik Informatika ITS

Mini-website akademik individu berbasis Laravel untuk Tugas 4 PBKK. Website ini memuat profil akademik, rancangan Agentic AI, serta formulir masukan. Implementasinya mempraktikkan Blade templating, master layout, reusable components, passing data dari controller, serta bundling Tailwind melalui Vite.

Tugas 10 menambahkan backoffice AdminLTE di `/admin` yang diamankan login Laravel Breeze dan otorisasi Spatie Permission berbasis database.

##Visual

Dashboard
<img width="930" height="378" alt="Screenshot 2026-11-09 163313" src="https://github.com/user-attachments/assets/a167fc56-1197-4fe4-8d57-3039e81b669e" />

Dashboard Admin
<img width="926" height="397" alt="Screenshot 2026-11-09 161133" src="https://github.com/user-attachments/assets/e6c5eeb6-40af-4b25-ad0a-d33a680fd195" />

## Menjalankan

PHP 8.3+ dan Composer diperlukan. Dari folder proyek:

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve --port=8002
```

Salin `.env` hanya pada instalasi pertama. Buka http://127.0.0.1:8002. Session, cache, dan database memakai SQLite/file lokal. Tailwind dan CSS situs diproses melalui Vite lokal; interaksi situs memakai berkas JavaScript lokal. Font Manrope dan Playfair Display disimpan lokal di `public/fonts`. AdminLTE memakai aset lokal di `public/vendor/adminlte`; empat aset pihak ketiga (Bootstrap JS, Bootstrap Icons, Source Sans 3, OverlayScrollbars) memakai fallback CDN karena paket npm-nya tidak dipasang.

`php artisan migrate --seed` membuat tabel otorisasi Spatie sekaligus menjalankan `RolePermissionSeeder` (role, permission, dan akun demo admin).

## Routes tugas mandiri

Tiga halaman inti tugas menggunakan named routes dan satu `PageController`. Formulir ide menggunakan route POST yang terpisah; route tambahan Week 2 tetap dilayani `AcademicController`.

| Route | Method controller | Nama route | Peran |
| --- | --- | --- | --- |
| `/` | `PageController@index` | `home` | Sambutan dan profil singkat |
| `/beranda?user=Andi` | `PageController@index` | `beranda` | Challenge pesan pengguna dinamis |
| `/profil-mahasiswa` | `PageController@profile` | `profile` | Halaman profil akademis |
| `/ide-agent/{tema?}` | `PageController@agent` | `agent.idea` | Ide Agentic AI dengan tema opsional; default General Assistant Agent |
| `/ide-agent/masukan` | `PageController@submitIdea` | `agent.idea.submit` | Validasi dan penerimaan masukan ide |
| `/mahasiswa/{nrp}` | `profile` | `student` | Profil pemilik NRP, tepat 10 digit |
| `/agent/{tema?}` | `agent` | `agent` | Tema opsional, default General Assistant Agent |
| `/hitung-ipk/{ipk1}/{ipk2}` | `gpa` | `gpa.calculate` | Rata-rata dua IP, rentang 0–4 |
| `/dashboard` | `dashboard` | `dashboard.index` | Navigasi akademis |
| `/dashboard/mahasiswa/{nrp}` | `profile` | `dashboard.student` | Profil dalam prefix dashboard |
| `/dashboard/agent/{tema?}` | `agent` | `dashboard.agent` | Tema AI dalam prefix dashboard |
| `/dashboard/ipk` | `gpaForm` | `dashboard.gpa` | Form IP |
| `/dashboard/ipk/submit` | `submit` | `dashboard.gpa.submit` | Validasi form dan redirect hasil |
| `/login` | `Auth\AuthenticatedSessionController@create` | `login` | Form login Laravel Breeze |
| `/admin` | `AdminDashboardController@index` | `admin.dashboard` | Backoffice AdminLTE; wajib login, email terverifikasi, dan permission `view dashboard` |
| `/admin/users` | `AdminDashboardController@users` | `admin.users` | Tabel pengguna dengan pencarian `q` dan filter `role` berbasis `when()` |
| Route tidak ditemukan | `missing` | `fallback` | Halaman 404 |

Route portfolio dan route Week 2 tetap tersedia sebagai fitur tambahan. NRP valid yang bukan milik pemilik situs menghasilkan 404. Rata-rata IP memakai bobot semester sama, bukan penghitungan IPK resmi berbobot SKS.

## Blade dan komponen reusable

Semua halaman mewarisi `resources/views/layouts/app.blade.php` melalui `@extends`. Layout memusatkan title dinamis, navigasi, `@yield('content')`, footer, dan directive `@vite`.

Komponen custom dipakai dengan props, merged attributes, dan default slot:

- `<x-info-card>` untuk menampilkan label dan nilai profil secara reusable.
- `<x-status-banner>` untuk notifikasi status dengan tipe `success`, `warning`, `error`, atau `info`.

Banner digunakan untuk pesan form dan ucapan selamat datang. Data profil dikirim dari controller dan dicetak melalui sintaks Blade ter-escape `{{ }}`. View juga menggunakan directive kondisional dan parameter query `user` serta `mode`.

## Ide Agentic AI

Halaman Ide-Riset memperkenalkan rancangan Agentic AI untuk DAST. Tema dapat diberikan melalui parameter opsional, misalnya `/ide-agent/dast`; jika kosong, tema default adalah General Assistant Agent. Form meminta nama dan ide, memvalidasi isinya, lalu menampilkan tanda terima untuk sesi demo. Masukan belum disimpan permanen atau masuk database. Implementasi scanner atau agen belum menjadi bagian tugas ini.

## Backoffice Tugas 10

Backoffice memakai stack AdminLTE + Laravel Breeze + Spatie Permission. Kelima kewajiban diimplementasikan dan diverifikasi dengan test otomatis.

| Kewajiban | Implementasi |
| --- | --- |
| 1. Integrasi UI backoffice AdminLTE | `resources/views/admin/dashboard.blade.php` memakai `@extends('adminlte::page')`; konfigurasi merek dan menu ada di `config/adminlte.php` |
| 2. Autentikasi Laravel Breeze | Route `/admin` dilindungi middleware `auth` dan `verified`; scaffold Breeze menyediakan `/login`, `/register`, reset password, verifikasi email, dan profil |
| 3. Otorisasi database Spatie | Tabel `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, dan `role_has_permissions` dibuat oleh migrasi Spatie; model `User` memakai trait `HasRoles` |
| 4. Minimal 3 widget statistik dinamis | Dashboard menampilkan `User::count()`, `Role::count()`, dan `Permission::count()` beserta tabel role yang ikut berubah saat data berubah |
| 5. Pengaman controller | Route group memakai `permission:view dashboard`, dan `AdminDashboardController::__construct()` juga memasang `middleware(['auth', 'verified', 'permission:view dashboard'])` |

Akun demo hasil seeder:

```text
Email    : rheadebora@gmail.com
Password : password
Role     : admin
```

Seeder `RolePermissionSeeder` membuat tiga permission (`view dashboard`, `manage users`, `manage roles`) serta dua role:

- `admin` — seluruh permission, termasuk `view dashboard`.
- `operator` — hanya `view dashboard`.

Pengguna yang login tetapi tidak memiliki `view dashboard` mendapat HTTP 403. Guest yang membuka `/admin` diarahkan ke `/login`. Registrasi publik diarahkan ke halaman utama dan tidak otomatis memperoleh akses admin.

### Pencarian dan filter pengguna dengan `when()`

Halaman `/admin/users` menampilkan tabel pengguna AdminLTE dengan formulir pencarian dan filter role. `AdminDashboardController@users` memakai kueri kondisional agar alur controller tetap bersih tanpa `if` bersarang:

```php
$search = trim((string) $request->query('q', ''));
$roleFilter = trim((string) $request->query('role', ''));

$users = User::query()
    ->with('roles')
    ->when($search !== '', function (Builder $query) use ($search) {
        $query->where(function (Builder $query) use ($search) {
            $query->whereLike('name', "%{$search}%")
                ->orWhereLike('email', "%{$search}%");
        });
    })
    ->when($roleFilter !== '', function (Builder $query) use ($roleFilter) {
        $query->whereHas('roles', function (Builder $query) use ($roleFilter) {
            $query->where('name', $roleFilter);
        });
    })
    ->latest('id')
    ->paginate(10)
    ->withQueryString();
```

- `q` mencari nama atau email secara parsial dan case-insensitive.
- `role` memfilter pengguna berdasarkan role Spatie melalui `whereHas()`.
- `when()` hanya menambahkan klausa bila filter terisi, sehingga kueri dasar tetap terbaca.
- `withQueryString()` mempertahankan kata kunci dan role saat berpindah halaman.
- View `resources/views/admin/users.blade.php` menyediakan tombol Cari/Reset, empty state, badge role, status verifikasi, dan pagination Bootstrap 5.

Perintah pemeriksaan:

```powershell
php artisan route:list --path=admin
php artisan permission:show
php artisan test --filter=AdminDashboardTest
php artisan test --filter=AdminUserSearchTest
```

## Verifikasi

```powershell
php artisan migrate:fresh --seed
php artisan route:list --except-vendor
php artisan test
php vendor/bin/pint --test app/Http/Controllers app/Models/User.php database/seeders routes/web.php tests/Feature/AdminDashboardTest.php tests/Feature/AdminUserSearchTest.php
```


