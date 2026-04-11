# Panduan Login Sistem Laporan Foto PGN

Sistem login sudah dibuat! Berikut adalah penjelasan lengkapnya:

## 🎯 Cara Kerja Sistem Login

### Alur Login
1. User mengakses aplikasi
2. Jika belum login, akan diredirect ke halaman login (`/login`)
3. User memasukkan email dan password
4. Sistem memvalidasi credentials
5. Jika benar, user akan masuk ke dashboard
6. Jika salah, ditampilkan pesan error

### Akses Halaman Login
- URL: `http://localhost:8000/login`
- Halaman dibuat dengan desain yang menarik dan responsif

## 👤 Credentials Demo

Untuk testing, gunakan credentials berikut:

```
Email: demo@example.com
Password: password
```

Credentials ini sudah otomatis dibuat di database saat menjalankan seeder.

## 🚀 Cara Menggunakan

### 1. Setup Database
```bash
# Pindah ke folder aplikasi
cd laporanfotopgn-app

# Jalankan migration untuk membuat tabel users
php artisan migrate

# Jalankan seeder untuk membuat demo user
php artisan db:seed
```

### 2. Jalankan Development Server
```bash
# Terminal 1 - Jalankan Laravel development server
php artisan serve

# Terminal 2 - Jalankan Vite untuk assets (opsional)
npm run dev
```

### 3. Akses Aplikasi
- Buka browser ke `http://localhost:8000`
- Klik tombol "Log in" atau langsung buka `/login`
- Masukkan credentials demo
- Klik tombol Login

## 🔐 Fitur Keamanan

- **Session-based authentication**: User session disimpan di server
- **CSRF Protection**: Token CSRF otomatis di setiap form
- **Password Hashing**: Password disimpan dengan hash bcrypt
- **Middleware Guard**: Route dilindungi dengan middleware `auth` dan `guest`

## 📁 Struktur File Yang Dibuat

```
app/Http/Controllers/
├── LoginController.php          # Controller untuk handle login
└── ...

app/Http/Middleware/
├── Authenticate.php             # Middleware untuk check auth
├── Guest.php                    # Middleware untuk non-auth only
└── DeviceIdentifier.php

resources/views/auth/
└── login.blade.php              # Halaman login

routes/
└── web.php                      # Route dengan middleware auth/guest

database/seeders/
└── DatabaseSeeder.php           # Seeder dengan demo user
```

## 🔄 Route Yang Tersedia

### Public Routes (Tidak Perlu Login)
- `GET /login` - Tampilkan halaman login
- `POST /login` - Process login
- `GET /` - Redirect ke login atau dashboard

### Protected Routes (Perlu Login, menggunakan middleware `auth`)
- `GET /dashboard` - Dashboard
- `GET /reports/...` - Semua route reports
- `GET /bap/...` - Semua route BAP
- `GET /utilization/...` - Semua route utilization

### Logout
- `POST /logout` - Logout user (perlu login)

## 🛠️ Membuat User Baru

### Cara 1: Menggunakan Tinker (Laravel REPL)
```bash
php artisan tinker

# Di dalam tinker shell:
$user = new App\Models\User();
$user->name = 'Nama User';
$user->email = 'email@example.com';
$user->password = bcrypt('password123');
$user->save();

exit
```

### Cara 2: Menggunakan Factory
```bash
php artisan tinker

# Di dalam tinker shell:
App\Models\User::factory()->create([
    'name' => 'Nama User',
    'email' => 'email@example.com',
    'password' => bcrypt('password123'),
]);

exit
```

## 📋 Halaman Login Features

- ✅ Form validasi email dan password
- ✅ Tampilan error jika login gagal
- ✅ Informasi credentials demo di halaman login
- ✅ Tombol kembali ke beranda
- ✅ Desain responsive (mobile-friendly)
- ✅ Dark mode support

## 🚪 Logout

Untuk logout, bisa menambahkan logout button di navigation bar. Contoh:

```html
<form method="POST" action="{{ route('logout') }}" style="display: inline;">
    @csrf
    <button type="submit" class="btn">Logout</button>
</form>
```

## ⚠️ Important Notes

1. **Email Unique**: Email di database harus unik. Jangan membuat user dengan email yang sama.
2. **Password Hashing**: Selalu gunakan `bcrypt()` saat membuat password baru, jangan plain text.
3. **Session**: Session disimpan di storage/framework/sessions/ atau di cache/database sesuai konfigurasi.
4. **CSRF Token**: Selalu include `@csrf` di form POST/PUT/DELETE.

## 🔧 Troubleshooting

### "Login tidak berhasil"
- Pastikan email sesuai: `demo@example.com`
- Pastikan password sesuai: `password`
- Pastikan database sudah di-seed: `php artisan db:seed`

### "Migration error"
- Pastikan database sudah dikonfigurasi di `.env`
- Jalankan: `php artisan migrate:refresh --seed`

### "Session tidak tersimpan"
- Cek file `.env`, pastikan `SESSION_DRIVER=file` atau `database`
- Cek folder `storage/framework/sessions/` memiliki permission write

## 📞 Support

Jika ada pertanyaan atau masalah dengan sistem login, silakan bertanya lebih lanjut!
